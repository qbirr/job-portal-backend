<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBrandingSliderRequest;
use App\Models\BrandingSliders;
use App\Repositories\BrandingSliderRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\View\View;

class BrandingSliderController extends AppBaseController
{
    /** @var BrandingSliderRepository */
    private $brandingSliderRepository;

    public function __construct(BrandingSliderRepository $brandingSliderRepository)
    {
        $this->brandingSliderRepository = $brandingSliderRepository;
    }

    /**
     * Display a listing of the BrandingSlider.
     *
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index()
    {
        return view('branding_sliders.index');
    }

    /**
     * Store a newly created BrandingSlider in storage.
     *
     * @param  CreateBrandingSliderRequest  $request
     * @return JsonResponse
     */
    public function store(CreateBrandingSliderRequest $request)
    {
        $input = $request->all();
        $input['is_active'] = (isset($input['is_active'])) ? 1 : 0;
        $this->brandingSliderRepository->store($input);

        return $this->sendSuccess(__('messages.flash.brand_save'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  BrandingSliders  $brandingSlider
     * @return JsonResponse
     */
    public function edit(BrandingSliders $brandingSlider)
    {
        return $this->sendResponse($brandingSlider, __('messages.flash.brand_retrieved'));
    }

    /**
     * Update the specified BrandingSlider in storage.
     *
     * @param  Request  $request
     * @param  BrandingSliders  $brandingSlider
     * @return JsonResource
     */
    public function update(Request $request, BrandingSliders $brandingSlider)
    {
        $request->validate([
            'title' => 'required|max:150',
            'branding_slider' => 'nullable|mimes:jpeg,jpg,png',
        ]);
        $input = $request->all();
        $input['is_active'] = (isset($input['is_active'])) ? 1 : 0;
        $this->brandingSliderRepository->updateBranding($input, $brandingSlider->id);

        return $this->sendSuccess(__('messages.flash.brand_update'));
    }

    /**
     * Remove the specified BrandingSlider from storage.
     *
     * @param  BrandingSliders  $brandingSlider
     * @return JsonResource
     *
     * @throws Exception
     */
    public function destroy(BrandingSliders $brandingSlider)
    {
        $brandingSlider->delete();

        return $this->sendSuccess(__('messages.flash.brand_delete'));
    }

    /**
     * @param  BrandingSliders  $brandingSlider
     * @return mixed
     */
    public function changeIsActive(BrandingSliders $brandingSlider)
    {
        $isActive = $brandingSlider->is_active;
        $brandingSlider->update(['is_active' => ! $isActive]);

        return $this->sendsuccess(__('messages.flash.status_change'));
    }
}
