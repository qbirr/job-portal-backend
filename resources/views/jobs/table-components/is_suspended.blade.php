<div class="d-flex justify-content-center">
    <label class="form-check form-switch form-switch-sm justify-content-center">
        <input type="checkbox" name="Is Suspended" class="form-check-input isSuspended" data-id="{{ $row->id }}" {{ $row->is_suspended ? 'checked' : '' }}>
    </label>
</div>
