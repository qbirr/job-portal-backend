<a href="{{ url('candidate-details', $row->candidate->unique_id)}}" class="text-decoration-none" data-turbo="false">
    {{$row->candidate->user->full_name }}
</a>
