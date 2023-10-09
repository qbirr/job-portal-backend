<div class="d-flex justify-content-center">
    <div class="form-check form-switch">
        <input class="form-check-input isBankActive" name="Is isActive" type="checkbox" role="switch"
               {{$row->is_active == 0 ? '' : 'checked'}}  data-id="{{$row->id}}">
        <span class="custom-switch-indicator"></span>
    </div>
</div>

