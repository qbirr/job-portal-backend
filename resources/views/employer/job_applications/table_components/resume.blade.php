@if (!$row->hasResumeAvailable)
    <a href="{{url('employer/resume-download', $row->id)}}" class="text-decoration-none" data-turbo="false">
        Download
    </a>
@else
    {{'N/A'}}
@endif
