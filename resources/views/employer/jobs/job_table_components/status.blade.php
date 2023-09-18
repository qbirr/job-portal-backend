<?php
        $isJobClosed = false;
        $isJobPause = false;
        $isJobDraft = false;
        if ($row->status == 2) {
            $isJobClosed = true;
        }
        if ($row->status == 3) {
            $isJobPause = true;
        }
        if ($row->status == 0) {
            $isJobDraft = true;
        }

$statusArray = App\Models\Job::STATUS;
?>

@if(!$isJobClosed)
    @if($statusArray[$row->status] == 'Drafted')
        <button class="btn bg-light-warning mr-1 badge job-application-status"
                style="cursor:context-menu"><?php echo __('messages.common.drafted') ?></button>
    @else
        <div class="dropdown dropdown-transparent">
            {{--            <a class="btn btn-light btn-active-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"--}}
            {{--               aria-expanded="false">--}}
            {{--                {{$statusArray[$row->status]}}--}}
            {{--            </a>--}}

            <button class="btn dropdown-toggle text-gray-600 mr-1" type="button"
                    id="dropdownMenuButton1"
                    data-bs-toggle="dropdown" aria-expanded="false">
                @if($statusArray[$row->status]== 'Live')
                    {{__('messages.common.live')}}
                @else
                    {{__('messages.common.paused')}}
                @endif
                <i class="fa-solid fa-angle-down ms-2"></i>
            </button>

            <ul class="fw-bold fs-6 py-4 dropdown-menu customDropdown"
                aria-labelledby="dropdownMenuButton1">

                @if($statusArray[$row->status]== 'Live')
                    <li>
                        <a class="btn btn-sm action-pause change-status" data-id="{{$row->id}}"
                           data-option="Paused"><?php echo __('messages.common.paused') ?></a>
                    </li>
                    <li>
                        <a class="btn btn-sm action-close change-status" data-id="{{$row->id}}"
                           data-option="Closed"><?php echo __('messages.common.closed') ?></a>
                    </li>
                @endif
                @if($statusArray[$row->status]== 'Paused')
                    <li>
                        <a class="btn btn-sm action-open change-status" data-id="{{$row->id}}"
                           data-option="Live"><?php echo __('messages.common.live') ?></a>
                    </li>
                    <li>
                        <a class="btn btn-sm action-close change-status" data-id="{{$row->id}}"
                           data-option="Closed"><?php echo __('messages.common.closed') ?></a>
                    </li>
                @endif
            </ul>
        </div>
    @endif

@else
    <button class="btn btn-danger mr-1 badge job-application-status"
            style="cursor:context-menu"><?php echo __('messages.common.closed') ?></button>
@endif
