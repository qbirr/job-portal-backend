<?php

namespace App\Http\Controllers;

use App\Exports\CandidatesExport;
use App\Http\Requests\CreateCandidateRequest;
use App\Http\Requests\UpdateCandidateRequest;
use App\Models\Candidate;
use App\Models\Country;
use App\Models\SalaryCurrency;
use App\Models\State;
use App\ReportedToCandidate;
use App\Repositories\Candidates\CandidateRepository;
use Carbon\Carbon;
use Exception;
use Flash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CandidateController extends AppBaseController
{
    /** @var CandidateRepository */
    private $candidateRepository;

    public function __construct(CandidateRepository $candidateRepo)
    {
        $this->candidateRepository = $candidateRepo;
    }

    /**
     * Display a listing of the Candidate.
     *
     * @param  Request  $request
     * @return Application|Factory|View
     *
     * @throws Exception
     */
    public function index()
    {
        return view('candidates.index');
    }

    /**
     * Show the form for creating a new Candidate.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $data = $this->candidateRepository->prepareData();
        $countries = Country::pluck('name', 'id');
        $states = State::toBase()->pluck('name', 'id');

        return view('candidates.create', compact('data', 'countries', 'states'));
    }

    /**
     * Store a newly created Candidate in storage.
     *
     * @param  CreateCandidateRequest  $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(CreateCandidateRequest $request)
    {
        $input = $request->all();
        $candidate = $this->candidateRepository->store($input);

        Flash::success(__('messages.flash.candidate_save'));

        return redirect(route('candidates.index'));
    }

    /**
     * Display the specified Candidate.
     *
     * @param  Candidate  $candidate
     * @return Application|Factory|View
     */
    public function show(Candidate $candidate)
    {
        $currency = SalaryCurrency::pluck('currency_name', 'id');

        return view('candidates.show', compact('currency'))->with('candidate', $candidate);
    }

    /**
     * Show the form for editing the specified Candidate.
     *
     * @param  Candidate  $candidate
     * @return Application|RedirectResponse|Redirector
     */
    public function edit(Candidate $candidate)
    {
        $user = $candidate->user;
        $user->phone = preparePhoneNumber($user->phone, $user->region_code);
        $data = $this->candidateRepository->prepareData();
        $data['candidateSkills'] = $user->candidateSkill()->pluck('skill_id')->toArray();
        $data['candidateLanguage'] = $user->candidateLanguage()->pluck('language_id')->toArray();
        $userStates = $userCities = null;
        $countries = Country::pluck('name', 'id');
        $states = State::toBase()->pluck('name', 'id');
        if (! empty($user->country_id)) {
            $userStates = getStates($user->country_id);
        }
        if (! empty($user->state_id)) {
            $userCities = getCities($user->state_id);
        }

        return view('candidates.edit', compact('candidate', 'user', 'data', 'countries', 'states', 'userStates', 'userCities'));
    }

    /**
     * Update the specified Candidate in storage.
     *
     * @param  Candidate  $candidate
     * @param  UpdateCandidateRequest  $request
     * @return Application|RedirectResponse|Redirector
     */
    public function update(Candidate $candidate, UpdateCandidateRequest $request)
    {
        $input = $request->all();
        if (empty($candidate)) {
            Flash::error(__('messages.flash.candidate_not_found'));

            return redirect(route('candidates.index'));
        }

        $candidate = $this->candidateRepository->updateCandidate($candidate, $input);

        Flash::success(__('messages.flash.candidate_update'));

        return redirect(route('candidates.index'));
    }

    /**
     * Remove the specified Candidate from storage.
     *
     * @param  Candidate  $candidate
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function destroy(Candidate $candidate)
    {
        if ($candidate->user->hasRole('Candidate')) {
            $candidate->user->delete();
            $candidate->delete();

            return $this->sendSuccess(__('messages.flash.candidate_delete'));
        } else {
            return $this->sendError(__('messages.common.seems_message'));
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function changeStatus($id)
    {
        $candidate = Candidate::findOrFail($id);

        $status = ! $candidate->user->is_active;
        $candidate->user->update(['is_active' => $status]);

        if ($candidate) {
            if (Auth::user()->hasRole('Admin')) {
                $candidate->last_change = Auth::user()->id;
                $candidate->save();
            }
        }

        return $this->sendSuccess(__('messages.flash.status_update'));
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function reportCandidate(Request $request)
    {
        $input = $request->all();
        $this->candidateRepository->storeReportCandidate($input);

        return $this->sendSuccess(__('messages.flash.candidate_reported'));
    }

    /**
     * @param  Request  $request
     * @return Application|Factory|View
     *
     * @throws Exception
     */
    public function showReportedCandidates()
    {
        return view('candidate.reported_candidate.reported_candidates');
    }

    /**
     * @param  ReportedToCompany  $reportedToCompany
     * @return mixed
     *
     * @throws Exception
     */
    public function showReportedCandiateNote(Request $request)
    {
        $data = $this->candidateRepository->getReportedToCandidate($request->reportedToCandidate);
        $data['date'] = \Carbon\Carbon::parse($data->created_at)->formatLocalized('%d %b, %Y');

        return $this->sendResponse($data, 'Retrieved successfully.');
    }

    /**
     * @param  ReportedToCompany  $reportedToCompany
     * @return mixed
     *
     * @throws Exception
     */
    public function deleteReportedCandidate(ReportedToCandidate $reportedToCandidate)
    {
        $reportedToCandidate->delete();

        return $this->sendSuccess(__('messages.flash.reported_candidate_delete'));
    }

    /**
     * @param  Candidate  $candidate
     * @return mixed
     */
    public function changeIsEmailVerified(Candidate $candidate)
    {
        if (empty($candidate->user->email_verified_at)) {
            $candidate->user->update([
                'email_verified_at' => Carbon::now(),
                'is_verified' => 1,
            ]);
        } else {
            $candidate->user->update(['email_verified_at' => null]);
        }

        if (Auth::user()->hasRole('Admin')) {
            $candidate->last_change = Auth::user()->id;
            $candidate->save();
        }

        return $this->sendSuccess(__('messages.flash.email_verify'));
    }

    /**
     * @param  Candidate  $candidate
     * @return mixed
     */
    public function resendEmailVerification(Candidate $candidate)
    {
//        $candidate->user->sendEmailVerificationNotification();
        if (Auth::user()->hasRole('Admin')) {
            $candidate->last_change = Auth::user()->id;
            $candidate->save();
        }

        return $this->sendSuccess(__('messages.flash.verification_mail'));
    }

    /**
     * @return BinaryFileResponse
     */
    public function candidateExportExcel()
    {
        return Excel::download(new CandidatesExport(), 'candidates-'.time().'.xlsx');
    }

    public function resumes()
    {
        return view('resumes.index');
    }

    public function downloadResume($media)
    {
        try {
            if (Auth::user()->hasRole('Admin')) {
                $mediaFile = Media::where('id', $media)->first();

                return $mediaFile;
            } else {
                $mediaFile = Media::where('id', $media)->where('model_id', getLoggedInUser()->candidate->id)->first();
                if ($mediaFile) {
                    return $mediaFile;
                } else {
                    return view('errors.404');
                }
            }
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }

    public function deleteResume($id)
    {
        $media = Media::where('model_id', $id)->where('model_type', Candidate::class)->first();
        $media->delete();

        return $this->sendSuccess(__('messages.flash.resume_delete'));
    }
}
