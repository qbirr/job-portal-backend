<span class="badge
@switch($row->submissionStatus->id) @case(2) bg-success @break @case(3) bg-red-500 @break @case(4) bg-yellow-500 @break @default bg-warning @break @endswitch">
    {{ $row->submissionStatus->status_name }}
</span>
