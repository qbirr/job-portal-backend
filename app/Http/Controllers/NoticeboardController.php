<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNoticeboardRequest;
use App\Http\Requests\UpdateNoticeboardRequest;
use App\Models\Noticeboard;
use App\Repositories\NoticeboardRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\View\View;

class NoticeboardController extends AppBaseController
{
    /** @var NoticeboardRepository */
    private $noticeboardRepository;

    public function __construct(NoticeboardRepository $noticeboardRepository)
    {
        $this->noticeboardRepository = $noticeboardRepository;
    }

    /**
     * Display a listing of the Noticeboard.
     *
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index()
    {
        $statusArr = Noticeboard::STATUS;
        $noticeboards = Noticeboard::toBase()->get();

        return view('noticeboards.index', compact('statusArr', 'noticeboards'));
    }

    /**
     * Store a newly created Noticeboard in storage.
     *
     * @param  CreateNoticeboardRequest  $request
     * @return JsonResponse
     */
    public function store(CreateNoticeboardRequest $request): JsonResponse
    {
        $input = $request->all();
        $input['is_active'] = (isset($input['is_active'])) ? 1 : 0;
        $this->noticeboardRepository->store($input);

        return $this->sendSuccess(__('messages.flash.noticeboard_save'));
//        return $this->sendSuccess('This functionality not allowed in demo.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Noticeboard  $noticeboard
     * @return JsonResponse
     */
    public function edit(Noticeboard $noticeboard)
    {
        return $this->sendResponse($noticeboard, __('messages.flash.noticeboard_retrieve'));
    }

    /**
     * Show the form for editing the specified Noticeboard.
     *
     * @param  Noticeboard  $noticeboard
     * @return JsonResource
     */
    public function show(Noticeboard $noticeboard)
    {
        return $this->sendResponse($noticeboard, __('messages.flash.noticeboard_retrieve'));
    }

    /**
     * Update the specified Noticeboard in storage.
     *
     * @param  UpdateNoticeboardRequest  $request
     * @param  Noticeboard  $noticeboard
     * @return JsonResource
     */
    public function update(UpdateNoticeboardRequest $request, Noticeboard $noticeboard)
    {
        $input = $request->all();
        $input['is_active'] = (isset($input['is_active'])) ? 1 : 0;
        $this->noticeboardRepository->update($input, $noticeboard->id);

        return $this->sendSuccess(__('messages.flash.noticeboard_update'));
    }

    /**
     * Remove the specified Noticeboard from storage.
     *
     * @param  Noticeboard  $noticeboard
     * @return JsonResource
     *
     * @throws Exception
     */
    public function destroy(Noticeboard $noticeboard)
    {
        $noticeboard->delete();

        return $this->sendSuccess(__('messages.flash.noticeboard_delete'));
    }

    /**
     * @param $id
     * @return JsonResource
     */
    public function changeStatus($id)
    {
        $notice = Noticeboard::findOrFail($id);
        $status = ! $notice->is_active;
        $notice->update(['is_active' => $status]);

        return $this->sendSuccess(__('messages.flash.status_update'));
    }
}
