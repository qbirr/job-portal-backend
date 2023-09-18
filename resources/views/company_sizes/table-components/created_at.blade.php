<div class="badge bg-secondary">
    {{ \Carbon\Carbon::parse($row->created_at)->format('jS M, Y') }}
</div>
