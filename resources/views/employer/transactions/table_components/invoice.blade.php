@if($row->invoice_id != null)
    <div class=" d-flex justify-content-center">
        <a data-bs-toggle="tooltip" title="{{__('messages.common.show')}}" class="btn px-2 text-primary fs-3 ps-0 action-btn employee-invoice-btn
            {{ $row->amount != 0 ? 'view-invoice' : 'N/A'}}"
           data-invoice-id="{{ $row->invoice_id }}"
           href="javascript:void(0)">
            <i class="fas fa-eye"></i>
        </a>
    </div>
@else
    <div class="d-flex justify-content-center">
        N/A
    </div>

@endif
