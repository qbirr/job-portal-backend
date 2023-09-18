@if(!empty($row->phone_code))
    <div class="badge bg-light-info">
        {{ $row->phone_code }}
    </div>
@else
    <div class="badge bg-light-info">
        N/A
    </div>
@endif
