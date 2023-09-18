<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostCategoryRequest;
use App\Http\Requests\UpdatePostCategoryRequest;
use App\Models\PostCategory;
use App\Repositories\PostCategoryRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\View\View;

class PostCategoryController extends AppBaseController
{
    /** @var PostCategoryRepository */
    private $postCategoryRepository;

    public function __construct(PostCategoryRepository $postCategoryRepository)
    {
        $this->postCategoryRepository = $postCategoryRepository;
    }

    /**
     * Display a listing of the BlogCategory.
     *
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index()
    {
        return view('blog_categories.index');
    }

    /**
     * Store a newly created BlogCategory in storage.
     *
     * @param  CreatePostCategoryRequest  $request
     * @return JsonResponse
     */
    public function store(CreatePostCategoryRequest $request)
    {
        $input = $request->all();
        $this->postCategoryRepository->create($input);

        return $this->sendSuccess(__('messages.flash.post_category_save'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  PostCategory  $postCategory
     * @return JsonResponse
     */
    public function edit(PostCategory $postCategory)
    {
        return $this->sendResponse($postCategory, 'Post category Retrieved Successfully.');
    }

    /**
     * Show the form for editing the specified BlogCategory.
     *
     * @param  PostCategory  $postCategory
     * @return JsonResource
     */
    public function show(PostCategory $postCategory)
    {
        return $this->sendResponse($postCategory, __('messages.flash.post_category_retrieve'));
    }

    /**
     * Update the specified BlogCategory in storage.
     *
     * @param  UpdatePostCategoryRequest  $request
     * @param  PostCategory  $postCategory
     * @return JsonResource
     */
    public function update(UpdatePostCategoryRequest $request, PostCategory $postCategory)
    {
        $input = $request->all();

        $this->postCategoryRepository->update($input, $postCategory->id);

        return $this->sendSuccess(__('messages.flash.post_category_update'));
    }

    /**
     * Remove the specified BlogCategory from storage.
     *
     * @param  PostCategory  $postCategory
     * @return JsonResource
     *
     * @throws Exception
     */
    public function destroy(PostCategory $postCategory)
    {
        $postCategory->delete();

        return $this->sendSuccess(__('messages.flash.post_category_delete'));
    }
}
