<?php

namespace App\Repositories;

use App\Http\Requests\CompanySearchRequest;
use App\Models\Company;
use App\Models\CompanySize;
use App\Models\FavouriteCompany;
use App\Models\Industry;
use App\Models\Job;
use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Models\OwnerShipType;
use App\Models\ReportedToCompany;
use App\Models\SubmissionLog;
use App\Models\User;
use Arr;
use Auth;
use Carbon\Carbon;
use DB;
use Exception;
use Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use LaravelIdea\Helper\App\Models\_IH_Company_C;
use LaravelIdea\Helper\App\Models\_IH_FavouriteCompany_C;
use LaravelIdea\Helper\App\Models\_IH_FavouriteJob_C;
use PragmaRX\Countries\Package\Countries;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

/**
 * Class CompanyRepository
 *
 * @version June 22, 2020, 12:34 pm UTC
 */
class CompanyRepository extends BaseRepository {
    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'ceo',
        'established_in',
        'website',
        'is_active',
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable(): array {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model(): string {
        return Company::class;
    }

    /**
     * @return array
     */
    public function prepareData(): array {
        $countries = new Countries();
        $data['industries'] = Industry::pluck('name', 'id');
        $data['ownerShipTypes'] = OwnerShipType::pluck('name', 'id');
        $data['companySize'] = CompanySize::pluck('size', 'id');
        $data['countries'] = getCountries();

        return $data;
    }

    /**
     * @param array $input
     * @return bool
     *
     * @throws Throwable
     */
    public function store(array $input): bool {
        try {
            DB::beginTransaction();
            $input['unique_id'] = getUniqueCompanyId();
            /** @var Company $company */
            $company = $this->create(Arr::only($input, (new Company())->getFillable()));

            // Create User
            $input['password'] = Hash::make($input['password']);
            $input['first_name'] = $input['name'];
            $input['owner_id'] = $company->id;
            $input['owner_type'] = Company::class;
            $input['is_verified'] = isset($input['is_verified']) ? 1 : 0;
            $userInput = Arr::only($input,
                [
                    'first_name', 'email', 'phone', 'password', 'owner_id', 'owner_type', 'country_id', 'state_id',
                    'city_id', 'is_active', 'dob', 'gender',
                    'facebook_url', 'twitter_url', 'linkedin_url', 'google_plus_url', 'pinterest_url', 'is_verified',
                    'is_default', 'region_code',
                ]);

            /** @var User $user */
            $user = User::create($userInput);
            $companyRole = Role::whereName('Employer')->first();
            $user->assignRole($companyRole);
            $company->update(['user_id' => $user->id]);

            if ((isset($input['image']))) {
                $user->addMedia($input['image'])
                    ->toMediaCollection(User::PROFILE, config('app.media_disc'));
            }
            if ((isset($input['image_url']))) {
                $user->addMediaFromUrl($input['image_url'])
                    ->toMediaCollection(User::PROFILE, config('app.media_disc'));
            }

            /** @var SubscriptionRepository $subscriptionRepo */
            $subscriptionRepo = app(SubscriptionRepository::class);
            $subscriptionRepo->createStripeCustomer($user);

            /*if ($user->is_verified) {
                $user->update(['email_verified_at' => Carbon::now()]);
            } else {
                $user->sendEmailVerificationNotification();
            }*/
            $user->update(['email_verified_at' => Carbon::now()]);

            SubmissionLog::create([
                'company_id' => $company->id,
                'submission_status_id' => 2,
                'notes' => '',
                'user_id' => auth()->id()
            ]);

//            if ($user->is_verified) {
//                $user->update(['email_verified_at' => Carbon::now()]);
//            } else {
//                $user->sendEmailVerificationNotification();
//            }

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();

            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @param array $input
     * @param Company $company
     * @return bool|Builder|Builder[]|Collection|Model
     *
     * @throws Throwable
     */
    public function update($input, $company): Model|Collection|Builder|bool|array {
        try {
            DB::beginTransaction();
            $oldSubmissionStatusId = $company->submission_status_id;

            $company->update($input);

            $input['first_name'] = $input['name'];
            $userInput = Arr::only($input,
                [
                    'first_name', 'email', 'phone', 'password', 'country_id', 'state_id', 'city_id', 'is_active',
                    'facebook_url', 'twitter_url', 'linkedin_url', 'google_plus_url', 'pinterest_url', 'region_code',
                ]);
            /** @var User $user */
            $user = $company->user;
            $user->phone = preparePhoneNumber($user->phone, $user->region_code);
            $user->update($userInput);

            if ((isset($input['image']))) {
                $user->clearMediaCollection(User::PROFILE);
                $user->addMedia($input['image'])
                    ->toMediaCollection(User::PROFILE, config('app.media_disc'));
            }

            if (auth()->user()->id == $user->id && $company->submission_status_id == 3) {
                $company->update(['submission_status_id' => 4]);
                SubmissionLog::create([
                    'company_id' => $company->id,
                    'submission_status_id' => 4,
                    'notes' => '',
                    'user_id' => auth()->id()
                ]);

                addNotification([
                    Notification::NEW_EMPLOYER_REGISTERED,
                    User::role('Admin')->first()->id,
                    Notification::ADMIN,
                    "Employer {$user->email} change company data"
                ]);
            } elseif (isset($input['submission_status_id']) && auth()->user()->role('Admin') && $oldSubmissionStatusId != $input['submission_status_id']) {
                SubmissionLog::create([
                    'company_id' => $company->id,
                    'submission_status_id' => $input['submission_status_id'],
                    'notes' => $input['submission_notes'] ?? '',
                    'user_id' => auth()->id()
                ]);
                $message = match ((int) $input['submission_status_id']) {
                    2 => "Congratulation! Admin has approved Your company registration, you may now post new jobs.",
                    3 => "We are sorry! Admin has rejected Your company registration: {$input['submission_notes']}",
                    default => false,
                };
                if ($message)
                    addNotification([
                        Notification::NEW_EMPLOYER_REGISTERED,
                        $user->id,
                        Notification::EMPLOYER,
                        $message
                    ]);
            }

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();

            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @param $companyId
     * @return bool
     */
    public function isCompanyAddedToFavourite($companyId): bool {
        return FavouriteCompany::where('user_id', Auth::id())
            ->where('company_id', $companyId)
            ->exists();
    }

    /**
     * @param $companyId
     * @return bool
     */
    public function isReportedToCompany($companyId): bool {
        return ReportedToCompany::where('user_id', Auth::id())
            ->where('company_id', $companyId)
            ->exists();
    }

    /**
     * @param $companyId
     * @return array
     */
    public function getCompanyDetail($companyId): array {
        $data['companyDetail'] = Company::with('user')->findOrFail($companyId);
        $data['jobDetails'] = Job::with('jobShift', 'company', 'jobCategory')
            ->whereDate('job_expiry_date', '>=', Carbon::now()->toDateString())
            ->where('is_suspended', '===', Job::NOT_SUSPENDED)
            ->where([
                ['company_id', $companyId], ['status', Job::STATUS_OPEN],
            ])->take(3)->get();
        $data['isCompanyAddedToFavourite'] = $this->isCompanyAddedToFavourite($companyId);
        $data['isReportedToCompany'] = $this->isReportedToCompany($companyId);

        return $data;
    }

    /**
     * @param array $input
     * @return bool
     *
     * @throws Exception
     */
    public function storeFavouriteJobs(array $input): bool {
        $favouriteJob = FavouriteCompany::where('user_id', $input['userId'])
            ->where('company_id', $input['companyId'])
            ->exists();
        if (!$favouriteJob) {
            $companyUser = User::findOrFail(Company::findOrFail($input['companyId'])->user_id);
            FavouriteCompany::create([
                'user_id' => $input['userId'],
                'company_id' => $input['companyId'],
            ]);
            $user = getLoggedInUser();
            NotificationSetting::where('key', 'FOLLOW_COMPANY')->first()->value == 1 ?
                addNotification([
                    Notification::FOLLOW_COMPANY,
                    $companyUser->id,
                    Notification::EMPLOYER,
                    $user->first_name . ' ' . $user->last_name . ' started following You.',
                ]) : false;

            return true;
        }

        FavouriteCompany::where('user_id', $input['userId'])
            ->where('company_id', $input['companyId'])
            ->delete();

        return false;
    }

    public function fetchFavouriteCompanies(User $user): array|Collection|_IH_FavouriteJob_C|_IH_FavouriteCompany_C {
        return FavouriteCompany::whereUserId($user->id)->with(['company'])->latest()->get();
    }

    /**
     * @param array $input
     * @return bool
     */
    public function storeReportToCompany(array $input): bool {
        $jobReportedAsAbuse = ReportedToCompany::where('user_id', $input['userId'])
            ->where('company_id', $input['companyId'])
            ->exists();

        if (!$jobReportedAsAbuse) {
            $reportedCompanyNote = trim($input['note']);
            if (empty($reportedCompanyNote)) {
                throw ValidationException::withMessages([
                    'note' => 'The Note Field is required',
                ]);
            }
            ReportedToCompany::create([
                'user_id' => $input['userId'],
                'company_id' => $input['companyId'],
                'note' => $input['note'],
            ]);

            return true;
        }

        FavouriteCompany::where('user_id', $input['userId'])
            ->where('company_id', $input['companyId'])
            ->delete();

        return true;
    }

    /**
     * @param $reportedToCompany
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function getReportedToCompany($reportedToCompany): Model|Collection|Builder|array|null {
        $query = ReportedToCompany::with([
            'user', 'company.user',
        ])->select('reported_to_companies.*')->findOrFail($reportedToCompany);

        return $query;
    }

    public function get($input = []): array {
        /** @var Company $query */
        $query = Company::with(['user' => function ($query) {
            $query->without(['country', 'state', 'city']);
        }, 'activeFeatured'])->select('companies.*');

        $query->when(isset($input['is_featured']) && $input['is_featured'] == 1,
            function (Builder $q) {
                $q->has('activeFeatured');
            });

        $query->when(isset($input['is_featured']) && $input['is_featured'] == 0,
            function (Builder $q) {
                $q->doesnthave('activeFeatured');
            });

        $query->when(isset($input['is_status']) && $input['is_status'] == 1,
            function (Builder $q) {
                $q->wherehas('user', function (Builder $q) {
                    $q->where('is_active', '=', 1);
                });
            });

        $query->when(isset($input['is_status']) && $input['is_status'] == 0,
            function (Builder $q) {
                $q->wherehas('user', function (Builder $q) {
                    $q->where('is_active', '=', 0);
                });
            });

        $subQuery = $query->get();

        $result = $data = [];
        $subQuery->map(function (Company $company) use ($data, &$result) {
            $data['id'] = $company->id;
            $data['user'] = [
                'full_name' => $company->user->full_name,
                'first_name' => $company->user->first_name,
                'last_name' => $company->user->last_name,
                'email' => $company->user->email,
                'is_active' => $company->user->is_active,
                'email_verified_at' => $company->user->email_verified_at,
            ];
            $data['company_url'] = $company->company_url;
            $data['active_featured'] = $company->activeFeatured;

            $result[] = $data;
        });

        return $result;
    }

    public function search(CompanySearchRequest $request): _IH_Company_C|LengthAwarePaginator|array {
        $query = Company::with(['user.media', 'jobs', 'activeFeatured', 'industry', 'user.city'])
            ->whereSubmissionStatusId(Job::SUBMISSION_STATUS_APPROVED);
        $query->whereHas('user', function (Builder $q) use ($request) {
            $q->where('first_name', 'like', '%' . strtolower($request->name) . '%')->where('is_active', '=',
                1);
        });

        $query->when(!empty($request->searchByCity), function (Builder $q) use ($request) {
            $q->where('location', 'like', '%' . strtolower($request->location) . '%');
            $q->orWhere('location2', 'like', '%' . strtolower($request->location) . '%');
        });

        $query->whereHas('industry', function (Builder $q) use ($request) {
            $q->where('name', 'like', '%' . strtolower($request->industry) . '%');
        });
        $query->when(!empty($request->isFeatured), function (Builder $query) {
            $query->has('activeFeatured');
        });
        $query->withCount([
            'jobs' => function (Builder $q) {
                $q->where('status', '!=', Job::STATUS_DRAFT);
                $q->where('status', '!=', Job::STATUS_CLOSED);
                $q->where('job_expiry_date', '>=', Carbon::now()->toDateString());
            },
        ]);

        $all = $query->paginate($request->perPage);
        $currentPage = $all->currentPage();
        $lastPage = $all->lastPage();
        if ($currentPage > $lastPage) {
            $request->page = $lastPage;
            $all = $query->paginate($request->perPage);
        }

        return $all;
    }
}
