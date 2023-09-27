<?php

namespace App\Repositories;

use App\Http\Requests\JobApplicationSearchRequest;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\JobApplication;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LaravelIdea\Helper\App\Models\_IH_JobApplication_C;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class JobApplicationRepository
 */
class JobApplicationRepository extends BaseRepository {
    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'job_id',
        'resume_id',
        'expected_salary',
        'notes',
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
        return JobApplication::class;
    }

    /**
     * @param int $jobId
     * @param int $candidateId
     * @param int $status
     * @return bool
     */
    public function checkJobStatus(int $jobId, int $candidateId, int $status): bool {
        return JobApplication::where('job_id', $jobId)
            ->where('candidate_id', $candidateId)
            ->where('status', $status)
            ->exists();
    }

    /**
     * @param int $jobId
     * @return array
     */
    public function showApplyJobForm(int $jobId): array {
        /** @var Candidate $candidate */
        $candidate = Candidate::findOrFail(Auth::user()->owner_id);

        /** @var Job $job */
        $job = Job::whereJobId($jobId)->with('company')->first();
        $data['isActive'] = ($job->status == Job::STATUS_OPEN) ? true : false;

        $jobRepo = app(JobRepository::class);
        $data['isApplied'] = $this->checkJobStatus($job->id, $candidate->id, JobApplication::STATUS_APPLIED);

        $data['resumes'] = [];
        $data['isJobDrafted'] = false;
        if (!$data['isApplied']) {
            // get candidate resumes
            $data['resumes'] = $candidate->getMedia('resumes')->pluck('custom_properties.title', 'id');
            $data['default_resume'] = $candidate->getMedia('resumes', ['is_default' => true])->first();
            if (isset($data['default_resume'])) {
                $data['default_resume'] = $data['default_resume']->id;
            }

            // check job is drafted or not
            $data['isJobDrafted'] = $this->checkJobStatus($job->id, $candidate->id, JobApplication::STATUS_DRAFT);

            if ($data['isJobDrafted']) {
                $data['draftJobDetails'] = $job->appliedJobs()->where('candidate_id', $candidate->id)->first();
            }
        }
        $data['job'] = $job;

        return $data;
    }

    /**
     * @param array $input
     * @return bool
     */
    public function store(array $input): bool {
        try {
            $input['candidate_id'] = Auth::user()->owner_id;

            $job = Job::findOrFail($input['job_id']);
            if ($job->status != Job::STATUS_OPEN) {
                throw new UnprocessableEntityHttpException('job is not active.');
            }

            /** @var JobApplication $jobApplication */
            $jobApplication = JobApplication::where('job_id', $input['job_id'])
                ->where('candidate_id', $input['candidate_id'])
                ->first();

            if ($jobApplication && $jobApplication->status == JobApplication::STATUS_APPLIED) {
                throw new UnprocessableEntityHttpException('You have already applied for this job.');
            }

            if ($jobApplication && $jobApplication->status == JobApplication::STATUS_DRAFT) {
                $jobApplication->delete();
            }

            $input['candidate_id'] = Auth::user()->owner_id;
            $input['expected_salary'] = removeCommaFromNumbers($input['expected_salary']);
            $input['status'] = $input['application_type'] == 'apply' ? JobApplication::STATUS_APPLIED : JobApplication::STATUS_DRAFT;

            $this->create($input);

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @param JobApplication $jobApplication
     * @return array
     */
    public function downloadMedia(JobApplication $jobApplication): array {
        try {
            $documentMedia = Media::find($jobApplication->resume_id);
            if ($documentMedia == null) {
                $documentMedia = Media::where('model_id', $jobApplication->candidate_id)->where('collection_name',
                    'resumes')->latest()->first();
            }
            $documentPath = $documentMedia->getPath();
            if (config('app.media_disc') === 'public') {
                $documentPath = (Str::after($documentMedia->getUrl(), '/uploads'));
            }

            $file = Storage::disk(config('app.media_disc'))->get($documentPath);

            $headers = [
                'Content-Type' => $documentMedia->mime_type,
                'Content-Description' => 'File Transfer',
                'Content-Disposition' => "attachment; filename={$documentMedia->file_name}",
                'filename' => $documentMedia->file_name,
            ];

            return [$file, $headers];
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function search(int $jobId, JobApplicationSearchRequest $request): _IH_JobApplication_C|LengthAwarePaginator|array {
        $query = JobApplication::with(['job.currency', 'candidate.user', 'jobStage', 'job'])
            ->where('job_id', $jobId)
            ->where('status', '!=', JobApplication::STATUS_DRAFT)
            ->select('job_applications.*');
        $query->when(!empty($request->q), function (Builder $q) use ($request) {
            $q->where(function (Builder $q) use ($request) {
                $q->whereHas('candidate.user', function (Builder $q) use ($request) {
                    $q->where('first_name', 'like', "%{$request->q}%");
                })
                    ->orWhereHas('candidate', function (Builder $q) use ($request) {
                        $q->orWhere('expected_salary', 'like', "%{$request->q}%")
                            ->orWhere('created_at', 'like', "%{$request->q}%")
                            ->orWhere('status', 'like', "%{$request->q}%");
                    });
            });
        });
        $query->when(!empty($request->status), function (Builder $q) use ($request) {
            $q->where(['status' => $request->status]);
        });

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
