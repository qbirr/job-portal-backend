@if($row->status == \App\Models\Transaction::DIGITAL)
    <span class="badge bg-light-success">{{__('messages.filter_name.digital')}}</span>
@elseif($row->status == \App\Models\Transaction::MANUALLY)
    <span class="badge bg-light-success">{{__('messages.filter_name.manually')}}</span>
@else

@endif
