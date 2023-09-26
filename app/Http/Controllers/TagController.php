<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use App\Repositories\JobTagRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends AppBaseController {
    /** @var JobTagRepository */
    private JobTagRepository $jobTagRepository;

    public function __construct(JobTagRepository $jobTagRepo) {
        $this->jobTagRepository = $jobTagRepo;
    }

    /**
     * Display a listing of the JobTag.
     *
     * @param Request $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index() {
        return view('job_tags.index');
    }

    /**
     * Store a newly created JobTag in storage.
     *
     * @param CreateTagRequest $request
     * @return JsonResponse
     */
    public function store(CreateTagRequest $request): JsonResponse {
        $input = $request->all();
        $jobTag = $this->jobTagRepository->create($input);

        return $this->sendResponse($jobTag, __('messages.flash.job_tag_save'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Tag $tag
     * @return JsonResponse
     */
    public function edit(Tag $tag) {
        return $this->sendResponse($tag, __('messages.flash.job_tag_retrieve'));
    }

    /**
     * Show the form for editing the specified JobTag.
     *
     * @param Tag $tag
     * @return JsonResponse
     */
    public function show(Tag $tag) {
        return $this->sendResponse($tag, __('messages.flash.job_tag_retrieve'));
    }

    /**
     * Update the specified JobTag in storage.
     *
     * @param UpdateTagRequest $request
     * @param Tag $jobTag
     * @return JsonResponse
     */
    public function update(UpdateTagRequest $request, Tag $tag) {
        $input = $request->all();
        $this->jobTagRepository->update($input, $tag->id);

        return $this->sendSuccess(__('messages.flash.job_tag_update'));
    }

    /**
     * Remove the specified JobTag from storage.
     *
     * @param Tag $jobTag
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function destroy(Tag $tag) {
        $jobTag = $tag->jobs()->pluck('tag_id')->toArray();
        if (in_array($tag->id, $jobTag)) {
            return $this->sendError(__('messages.flash.job_tag_cant_delete'));
        } else {
            $tag->delete();
        }

        return $this->sendSuccess(__('messages.flash.job_tag_delete'));
    }

    public function fetch() {
        return $this->jobTagRepository->fetch();
    }
}
