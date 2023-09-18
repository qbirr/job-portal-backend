@if(is_null($row->description))
    N/A
@else
    @php
        $text = $row->description;
    $formatted_text = str_replace(['<p>', '</p>'], '', $text)
    @endphp


    {{ \Illuminate\Support\Str::limit($formatted_text,190) }}
@endif
