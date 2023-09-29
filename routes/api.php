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
use App\Http\Controllers\web\CategoriesController;
use App\Http\Controllers\Web\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('sanctum/token', [TokenController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

Route::prefix('jobs')->group(function () {
    Route::get('latest', [JobController::class, 'latestJobs']);
    Route::get('search-autocomplete', [JobController::class, 'searchJobAutocomplete']);
    Route::post('search', [JobController::class, 'searchJob']);
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
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'fetch']);
        Route::post('{notification}/read', [NotificationController::class, 'readNotification']);
        Route::post('read-all', [NotificationController::class, 'readAllNotification']);
    });

    Route::prefix('jobs')->group(function () {
        Route::post('/', [JobController::class, 'employerJobs']);
        Route::post('{job}/applications', [JobApplicationController::class, 'fetch']);
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
            Route::get('{jobApplication}', [JobApplicationController::class, 'getJobStage']);
            Route::post('{jobId}/job-stage', [JobApplicationController::class, 'changeJobStage']);
        });
    });
});

Route::middleware(['auth:sanctum', 'role:Candidate'])->prefix('candidate')->group(function () {
    Route::prefix('profiles')->group(function () {
        Route::get('/', [\App\Http\Controllers\Candidates\CandidateController::class, 'editCandidateProfile']);
        Route::post('change-password', [\App\Http\Controllers\Candidates\CandidateController::class, 'changePassword']);
        Route::post('update', [\App\Http\Controllers\Candidates\CandidateController::class, 'profileUpdate']);
        Route::prefix('resumes')->group(function () {
            Route::post('/', [\App\Http\Controllers\Candidates\CandidateController::class, 'uploadResume']);
            Route::get('/{media}', [\App\Http\Controllers\CandidateController::class, 'downloadResume']);
            Route::delete('/{media}', [\App\Http\Controllers\Candidates\CandidateController::class, 'deletedResume']);
        });
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
