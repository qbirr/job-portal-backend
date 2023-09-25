<?php

use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\TokenController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\JobController;
use App\Http\Controllers\Web;
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
});

Route::prefix('companies')->group(function () {
    Route::post('search', [CompanyController::class, 'search']);
});

Route::get('/candidate-lists',
    [Web\CandidateController::class, 'getCandidatesLists'])->name('front.candidate.lists')->middleware('role:Admin|Employer');
Route::get('/company-details/{uniqueId?}', [Web\CompanyController::class, 'getCompaniesDetails'])->name('front.company.details');
