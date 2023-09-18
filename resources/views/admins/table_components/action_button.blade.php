<div class="d-flex justify-content-center">
    <a href="{{route('admin.edit', $row->id)}}" title="{{__('messages.common.edit') }}"
       class="btn px-2 text-primary fs-3 ps-0 candidates-edit-btn" data-bs-toggle="tooltip">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    <button type="button" title="{{__('messages.common.delete')}}" data-id="{{ $row->id }}"
            class="admins-delete-btn btn px-2 text-danger fs-3 pe-0" id="deleteAdmin" data-bs-toggle="tooltip">
        <i class="fa-solid fa-trash"></i>
    </button>
</div>
