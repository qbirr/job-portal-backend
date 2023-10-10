<div id="uploadPopModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3>{{ __('messages.plan.upload_pop') }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <form id="uploadPopForm" action="{{ route('transaction.upload-pop', $pendingManualTransaction) }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger hide d-none" id="validationErrorsBox">
                        <i class='fa-solid fa-face-frown me-4'></i>
                    </div>
                    <div class="mb-5">
                        <div>
                            {{ Form::label('customFile',__('messages.common.choose_file').(':'), ['class' => 'form-label']) }}
                            <span class="required"></span>
                            <input type="file" class="form-control custom-file-input" id="customFile" name="file"
                                   required accept=".png, .jpg, .jpeg">
                        </div>
                    </div>
                </div>
                <div class="modal-footer pt-0">
                    {{ Form::button(__('messages.common.save'), ['type' => 'submit','class' => 'btn btn-primary m-0','id' => 'uploadPopSaveBtn','data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> ".__('messages.common.process')]) }}
                    <button type="button" class="btn btn-secondary my-0 ms-5 me-0"
                            data-bs-dismiss="modal">{{ __('messages.common.cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        listenClick('.uploadPopModal', function () {
            $('#uploadPopModal').appendTo('body').modal('show');
        });

        listenSubmit('#uploadPopForm', function (e) {
            e.preventDefault();
            processingBtn('#uploadPopForm', '#uploadPopSaveBtn', 'loading');
            $.ajax({
                url: route('transaction.upload-pop', {{ $pendingManualTransaction->id }}),
                type: 'post',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        resetModalForm('#uploadPopForm', '#validationErrorsBox');
                        $('#candidateResumeModal').modal('hide');
                        setTimeout(function () {
                            processingBtn('#uploadPopForm', '#uploadPopSaveBtn', 'reset');
                        }, 1000);
                    }
                },
                error: function (result) {
                    displayErrorMessage(result.responseJSON.message);
                    setTimeout(function () {
                        processingBtn('#uploadPopForm', '#uploadPopSaveBtn', 'reset');
                    }, 1000);
                },
                complete: function () {
                    setTimeout(function () {
                        processingBtn('#uploadPopForm', '#uploadPopSaveBtn');
                    }, 1000);
                },
            });
        });

        listenChange('#customFile', function () {
            let extension = isValidDocument($(this), '#validationErrorsBox');
            if (!isEmpty(extension) && extension != false) {
                $('#validationErrorsBox').html('').hide();
            }
        });

        listen('hide.bs.modal', '#uploadPopModal', function () {
            $('#customFile').siblings('.custom-file-label').addClass('selected').html('Choose file');
            resetModalForm('#uploadPopForm', '#validationErrorsBox');
        });
    </script>
@endpush
