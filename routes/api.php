<?php

use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\TokenController;
use App\Http\Controllers\API\CandidateController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\JobController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Candidates\CandidateProfileController;
use App\Http\Controllers\CareerLevelController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\FunctionalAreaController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobShiftController;
use App\Http\Controllers\JobStageController;
use App\Http\Controllers\JobTypeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OwnerShipTypeController;
use App\Http\Controllers\PostCategoryController;
use App\Http\Controllers\RequiredDegreeLevelController;
use App\Http\Controllers\SalaryCurrencyController;
use App\Http\Controllers\SalaryPeriodController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\Web\CategoriesController;
use App\Http\Controllers\Web\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Auth::routes(['verify' => true, 'register' => false]);

Route::post('sanctum/token', [TokenController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

Route::prefix('jobs')->group(function () {
    Route::get('latest', [JobController::class, 'latestJobs']);
    Route::get('search-autocomplete', [JobController::class, 'searchJobAutocomplete']);
    Route::post('search', [JobController::class, 'apiSearchJob']);
    Route::get('details/{job}', [JobController::class, 'detail']);
    Route::get('categories', [CategoriesController::class, 'fetch']);
    Route::get('types', [JobTypeController::class, 'fetch']);
    Route::get('skills', [SkillController::class, 'fetch']);

    Route::prefix('salaries')->group(function () {
        Route::get('currencies', [SalaryCurrencyController::class, 'fetch']);
        Route::get('periods', [SalaryPeriodController::class, 'fetch']);
    });
    Route::get('career-levels', [CareerLevelController::class, 'fetch']);
    Route::get('job-shifts', [JobShiftController::class, 'fetch']);
    Route::get('tags', [TagController::class, 'fetch']);
    Route::get('degrees', [RequiredDegreeLevelController::class, 'fetch']);
    Route::get('functional-areas', [FunctionalAreaController::class, 'fetch']);
});

Route::prefix('companies')->group(function () {
    Route::post('search', [CompanyController::class, 'search']);
    Route::get('detail/{company}', [CompanyController::class, 'detail']);
});

Route::middleware(['auth:sanctum', 'role:Employer|Candidate'])->group(function () {
    Route::get('profile', [UserController::class, 'profile']);
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'fetch']);
        Route::post('{notification}/read', [NotificationController::class, 'readNotification']);
        Route::post('read-all', [NotificationController::class, 'readAllNotification']);
    });
});

Route::middleware(['auth:sanctum', 'role:Employer'])->prefix('employer')->group(function () {
    Route::prefix('profiles')->group(function () {
        Route::get('/', [EmployerController::class, 'editProfile']);
        Route::post('change-password', [EmployerController::class, 'changePassword']);
        Route::post('update', [EmployerController::class, 'profileUpdate']);
    });
    Route::prefix('company')->group(function () {
        Route::get('/', [CompanyController::class, 'profile']);
        Route::put('/', [CompanyController::class, 'update']);
    });
    Route::prefix('candidates')->group(function () {
        Route::post('search', [CandidateController::class, 'search']);
        Route::get('detail/{candidate}', [CandidateController::class, 'detail']);
    });

    Route::prefix('jobs')->group(function () {
        Route::post('/', [JobController::class, 'employerJobs']);
        Route::post('{job}/applications', [JobApplicationController::class, 'fetch']);
        Route::post('{id}/status/{status}', [\App\Http\Controllers\JobController::class, 'changeJobStatus']);
        Route::prefix('stages')->group(function () {
            Route::get('/', [JobStageController::class, 'fetch']);
            Route::post('/', [JobStageController::class, 'store']);
            Route::put('{jobStage}', [JobStageController::class, 'update']);
            Route::delete('{jobStage}', [JobStageController::class, 'destroy']);
        });
        Route::prefix('applications')->group(function () {
            Route::post('{id}/status/{status}', [JobApplicationController::class, 'changeJobApplicationStatus']);
            Route::delete('{jobApplication}', [JobApplicationController::class, 'destroy']);
            Route::get('{jobApplication}/download-resume', [JobApplicationController::class, 'downloadMedia']);
            Route::prefix('{jobApplication}/slots')->group(function () {
                Route::get('/', [\App\Http\Controllers\API\JobApplicationController::class, 'fetchSlot']);
                Route::post('/', [\App\Http\Controllers\API\JobApplicationController::class, 'interviewSlotStore']);
                Route::get('history', [\App\Http\Controllers\API\JobApplicationController::class, 'history']);
            });
            Route::prefix('slots')->group(function () {
                Route::post('{jobApplicationSchedule}/cancel', [\App\Http\Controllers\API\JobApplicationController::class, 'cancel']);
                Route::put('{jobApplicationSchedule}', [\App\Http\Controllers\API\JobApplicationController::class, 'update']);
                Route::delete('{jobApplicationSchedule}', [\App\Http\Controllers\API\JobApplicationController::class, 'delete']);
            });
            Route::get('{jobApplication}', [JobApplicationController::class, 'getJobApplicationDetail']);
            Route::post('{jobId}/job-stage', [JobApplicationController::class, 'changeJobStage']);
        });
        Route::put('{job}', [JobController::class, 'update']);
    });

    Route::get('followers', [\App\Http\Controllers\API\FollowerController::class, 'fetchFollowers']);
});

Route::middleware(['auth:sanctum', 'role:Candidate'])->prefix('candidate')->group(function () {
    Route::prefix('profiles')->group(function () {
        Route::get('/', [\App\Http\Controllers\Candidates\CandidateController::class, 'editCandidateProfile']);
        Route::post('change-password', [\App\Http\Controllers\Candidates\CandidateController::class, 'changePassword']);
        Route::post('update', [\App\Http\Controllers\Candidates\CandidateController::class, 'profileUpdate']);
        Route::post('update-general-profile', [\App\Http\Controllers\Candidates\CandidateController::class, 'updateGeneralInformation']);
        Route::prefix('resumes')->group(function () {
            Route::get('/', [\App\Http\Controllers\Candidates\CandidateController::class, 'listResume']);
            Route::post('/', [\App\Http\Controllers\Candidates\CandidateController::class, 'uploadResume']);
            Route::get('/{media}', [\App\Http\Controllers\CandidateController::class, 'downloadResume']);
            Route::delete('/{media}', [\App\Http\Controllers\Candidates\CandidateController::class, 'deletedResume']);
        });
        Route::get('cv-data', [\App\Http\Controllers\Candidates\CandidateController::class, 'fetchCv']);
    });
    Route::prefix('experiences')->group(function () {
        Route::post('/', [CandidateProfileController::class, 'createExperience']);
        Route::get('/', [CandidateProfileController::class, 'fetchExperience']);
        Route::put('/{candidateExperience}', [CandidateProfileController::class, 'updateExperience']);
    });
    Route::prefix('educations')->group(function () {
        Route::post('/', [CandidateProfileController::class, 'createEducation']);
        Route::get('/', [CandidateProfileController::class, 'fetchEducation']);
        Route::put('{candidateEducation}', [CandidateProfileController::class, 'updateEducation']);
        Route::delete('{candidateEducation}', [CandidateProfileController::class, 'destroyEducation']);
    });
    Route::prefix('jobs')->group(function () {
        Route::prefix('applied')->group(function () {
            Route::get('/', [\App\Http\Controllers\Candidates\CandidateController::class, 'fetchCandidateAppliedJobs']);
            Route::get('{jobApplication}/slots', [\App\Http\Controllers\Candidates\AppliedJobController::class, 'showScheduleSlotBook']);
            Route::post('{jobApplication}/slots', [\App\Http\Controllers\Candidates\AppliedJobController::class, 'choosePreference']);
        });
        Route::prefix('favourite-jobs')->group(function () {
            Route::post('/', [\App\Http\Controllers\Web\JobController::class, 'saveFavouriteJob']);
            Route::get('/', [\App\Http\Controllers\Web\JobController::class, 'fetchFavouriteJobs']);
            Route::delete('{favouriteJob}', [\App\Http\Controllers\Candidates\CandidateController::class, 'deleteFavouriteJob']);
        });
        Route::prefix('job-alerts')->group(function () {
            Route::get('/', [\App\Http\Controllers\Candidates\CandidateController::class, 'fetchJobAlert']);
            Route::post('/', [\App\Http\Controllers\Candidates\CandidateController::class, 'updateJobAlert']);
        });
        Route::post('{job}/email-job', [JobController::class, 'emailJobToFriend']);
        Route::post('{job}/apply-job', [\App\Http\Controllers\API\JobApplicationController::class, 'applyJob']);
        Route::post('{job}/report-job', [JobController::class, 'reportJobAbuse']);
    });
    Route::prefix('favourite-companies')->group(function () {
        Route::post('/', [\App\Http\Controllers\Web\CompanyController::class, 'saveFavouriteCompany']);
        Route::get('/', [\App\Http\Controllers\Web\CompanyController::class, 'fetchFavouriteCompany']);
    });
    Route::post('companies/{company}/report-to-company', [CompanyController::class, 'reportToCompany']);
    Route::post('update-online-profile', [\App\Http\Controllers\Candidates\CandidateController::class, 'updateOnlineProfile']);
});

Route::prefix('articles')->group(function () {
    Route::get('/', [PostController::class, 'fetch']);
    Route::get('detail/{post}', [PostController::class, 'detail']);
    Route::get('categories', [PostCategoryController::class, 'fetch']);
    Route::get('by-category/{postCategory}', [PostController::class, 'detailByCategory']);
});

Route::get('cities', [CityController::class, 'fetch']);
Route::get('industries', [IndustryController::class, 'fetch']);
Route::get('ownership-types', [OwnerShipTypeController::class, 'fetch']);

Route::get('front-data', [\App\Http\Controllers\Web\HomeController::class, 'frontJson']);
Route::get('about-us', \App\Http\Controllers\API\AboutUsController::class);
Route::post('contact-us', \App\Http\Controllers\API\ContactUsController::class);
