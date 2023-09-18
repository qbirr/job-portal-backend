<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Models\Country;
use App\Models\Job;
use App\Models\State;
use App\Repositories\CountryRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CountryController extends AppBaseController
{
    /**
     * @var CountryRepository
     */
    private $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return Application|Factory|Response|View
     */
    public function index()
    {
        return view('countries.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateCountryRequest  $request
     * @return JsonResponse
     */
    public function store(CreateCountryRequest $request): JsonResponse
    {
        $input = $request->all();
        $input['short_code'] = strtoupper($input['short_code']);
        $country = $this->countryRepository->create($input);

        return $this->sendResponse($country, __('messages.flash.country_save'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Country  $country
     * @return JsonResponse
     */
    public function edit(Country $country)
    {
        return $this->sendResponse($country, __('messages.flash.retrieved'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCountryRequest  $request
     * @param  Country  $country
     * @return JsonResponse
     */
    public function update(UpdateCountryRequest $request, Country $country)
    {
        $input = $request->all();
        $input['short_code'] = strtoupper($input['short_code']);

        $this->countryRepository->update($input, $country->id);

        return $this->sendSuccess(__('messages.flash.country_update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Country  $country
     * @return JsonResponse
     *
     * @throws \Exception
     */
    public function destroy(Country $country)
    {
        if (State::where('country_id', $country->id)->count() > 0) {
            return $this->sendError(__('messages.flash.country_cant_delete'));
        }
        if (Job::whereCountryId($country->id)->count() > 0) {
            return $this->sendError(__('messages.flash.country_cant_delete'));
        }
        $country->delete();

        return $this->sendSuccess(__('messages.flash.country_delete'));
    }
}
