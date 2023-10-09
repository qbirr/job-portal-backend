@if($row->latestMedia != null)
    <div class=" d-flex justify-content-center">
        <a class="invoice btn px-1 text-info fs-3"
           data-bs-toggle="tooltip"
           id="mediaShow"
           title="{{__('messages.common.show')}}"
           data-media-id="{{ $row->latestMedia->id }}"
           href="{{ route('transaction.download-pop', $row->id) }}">
            <i class="fas fa-download"></i>
        </a>
    </div>
@else
        N/A
@endif
