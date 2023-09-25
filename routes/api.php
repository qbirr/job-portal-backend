<?php

use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\TokenController;
use App\Http\Controllers\API\JobController;
use App\Http\Controllers\Web;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('sanctum/token', [TokenController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

Route::get('/get-jobs-search', [JobController::class, 'searchJobAutocomplete'])->name('get.jobs.search');
Route::get('/search-jobs', [JobController::class, 'searchJob'])->name('front.search.jobs');
Route::get('/job-details/{uniqueId?}', [Web\JobController::class, 'jobDetails'])->name('front.job.details');
Route::get('/company-lists', [Web\CompanyController::class, 'getCompaniesLists'])->name('front.company.lists');
Route::get('/candidate-lists',
    [Web\CandidateController::class, 'getCandidatesLists'])->name('front.candidate.lists')->middleware('role:Admin|Employer');
Route::get('/company-details/{uniqueId?}', [Web\CompanyController::class, 'getCompaniesDetails'])->name('front.company.details');
