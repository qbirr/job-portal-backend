<?php

use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\TokenController;
use App\Http\Controllers\API\CandidateController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\JobController;
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
    Route::get('categories', [\App\Http\Controllers\web\CategoriesController::class, 'fetch']);
});

Route::prefix('companies')->group(function () {
    Route::post('search', [CompanyController::class, 'search']);
    Route::get('detail/{company}', [CompanyController::class, 'detail']);
});

Route::middleware(['auth:sanctum', 'role:Employer'])->group(function () {
    Route::prefix('candidates')->group(function () {
        Route::post('search', [CandidateController::class, 'search']);
        Route::get('detail/{candidate}', [CandidateController::class, 'detail']);
    });
});
