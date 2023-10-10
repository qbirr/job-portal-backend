@if(strip_tags($row->notes) == "")
    N/A
@else
    {!! nl2br( \Illuminate\Support\Str::limit($row->notes, 190) ) !!}
@endif
