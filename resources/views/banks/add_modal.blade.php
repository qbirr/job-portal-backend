<div id="addBankModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ __('messages.bank.new_bank') }}</h3>
                <button type="button" aria-label="Close" class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>
            {{ Form::open(['id'=>'addBankForm']) }}
            <div class="modal-body ">
                <div class="alert alert-danger fs-4 text-white d-flex align-items-center d-none"
                     id="validationErrorsBox">
                    <i class="fa-solid fa-face-frown me-5"></i>
                </div>
                <div class=" mb-5">
                    {{ Form::label('name', __('messages.inquiry.name').(':'), ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::text('name', null, ['id'=>'name','class' => 'form-control','required','placeholder' => __('messages.inquiry.name')]) }}
                </div>
                <div class=" mb-5">
                    {{ Form::label('acc_no', __('messages.bank.acc_no').(':'), ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::text('acc_no', null, ['id'=>'acc_no','class' => 'form-control','required','placeholder' => __('messages.bank.acc_no')]) }}
                </div>
                <div class=" mb-5">
                    {{ Form::label('acc_name', __('messages.bank.acc_name').(':'), ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::text('acc_name', null, ['id'=>'acc_name','class' => 'form-control','required','placeholder' => __('messages.bank.acc_name')]) }}
                </div>
                <div class=" mb-5">
                    {{ Form::label('swift_code', __('messages.bank.swift_code').(':'), ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::text('swift_code', null, ['id'=>'swift_code','class' => 'form-control','required','placeholder' => __('messages.bank.swift_code')]) }}
                </div>
                <div class="mb-5">
                    {{ Form::label('notes', __('messages.bank.notes').(':'),['class' => 'form-label']) }}
                    <span class="required"></span>
                    <div id="addBankNotesQuillData"></div>
                    {{ Form::hidden('notes', null, ['id' => 'notes']) }}
                </div>
                <div class=" mb-5">
                    <label class="form-label ">{{ __('messages.common.status').':' }}</label><br>
                    <label class="form-check form-switch  form-switch-sm">
                        <input type="checkbox" name="is_active" class="form-check-input isActive"
                               value="1" id="active" checked>
                        <span class="custom-switch-indicator"></span>
                    </label>
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit','class' => 'btn btn-primary m-0','id' => 'bankSaveBtn','data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> ".__('messages.common.process')]) }}
                <button type="button" class="btn btn-secondary my-0 ms-5 me-0"
                        id="bankBtnCancel"
                        data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
