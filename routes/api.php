<?php

use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\TokenController;
use App\Http\Controllers\API\CandidateController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\JobController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\JobTypeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostCategoryController;
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
});

Route::prefix('companies')->group(function () {
    Route::post('search', [CompanyController::class, 'search']);
    Route::get('detail/{company}', [CompanyController::class, 'detail']);
});

Route::middleware(['auth:sanctum', 'role:Employer'])->prefix('employer')->group(function () {
    Route::prefix('profiles')->group(function () {
        Route::get('/', [EmployerController::class, 'editProfile']);
        Route::post('change-password', [EmployerController::class, 'changePassword']);
        Route::post('update', [EmployerController::class, 'profileUpdate']);
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
        Route::get('{jobId}/applications', [JobApplicationController::class, 'index'])->name('job-applications');
    });
});

Route::prefix('articles')->group(function () {
    Route::get('/', [PostController::class, 'fetch']);
    Route::get('detail/{post}', [PostController::class, 'detail']);
    Route::get('categories', [PostCategoryController::class, 'fetch']);
    Route::get('by-category/{postCategory}', [PostController::class, 'detailByCategory']);
});
