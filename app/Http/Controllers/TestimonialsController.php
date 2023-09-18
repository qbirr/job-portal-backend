<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTestimonialRequest;
use App\Http\Requests\UpdateTestimonialRequest;
use App\Models\Testimonial;
use App\Repositories\TestimonialRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TestimonialsController extends AppBaseController
{
    /**
     * @var TestimonialRepository
     */
    private $testimonialRepository;

    public function __construct(TestimonialRepository $testimonialRepository)
    {
        $this->testimonialRepository = $testimonialRepository;
    }

    /**
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index()
    {
        return view('testimonial.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateTestimonialRequest  $request
     * @return Response
     */
    public function store(CreateTestimonialRequest $request)
    {
        $input = $request->all();
        $this->testimonialRepository->store($input);

        return $this->sendSuccess(__('messages.flash.testimonial_save'));
//        return $this->sendSuccess('This functionality not allowed in demo.');
    }

    /**
     * Display the specified resource.
     *
     * @param  Testimonial  $testimonial
     * @return Response
     */
    public function show(Testimonial $testimonial)
    {
        return $this->sendResponse($testimonial, __('messages.flash.testimonial_retrieve'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Testimonial  $testimonial
     * @return Response
     */
    public function edit(Testimonial $testimonial)
    {
        return $this->sendResponse($testimonial, __('messages.flash.testimonial_retrieve'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Testimonial  $testimonial
     * @param  UpdateTestimonialRequest  $request
     * @return void
     */
    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial)
    {
        $input = $request->all();
        $this->testimonialRepository->updateTestimonial($input, $testimonial->id);

        return $this->sendSuccess(__('messages.flash.testimonial_update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Testimonial  $testimonial
     * @return Response
     *
     * @throws Exception
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return $this->sendSuccess(__('messages.flash.testimonial_delete'));
    }

    /**
     * @param  int  $media
     * @return Media
     */
    public function downloadImage(Testimonial $testimonial)
    {
        $media = $testimonial->getMedia('testimonials')->first()->id;
        /** @var Media $mediaItem */
        $mediaItem = Media::findOrFail($media);

        return $mediaItem;
    }
}
