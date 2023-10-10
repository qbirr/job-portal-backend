document.addEventListener('turbo:load', loadBankData)

function loadBankData() {
    if (!$('#bankNotes').length) {
        return;
    }
    window.addBankDetailQuill = new Quill(
        '#addBankNotesQuillData', {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['clean'],
                ],
                keyboard: {
                    bindings: {
                        tab: 'disabled',
                    }
                }
            },
            placeholder: 'Short descriptions...',
            theme: 'snow', // or 'bubble'
        })

    addBankDetailQuill.on('text-change',
        function (delta, oldDelta, source) {
            if (addBankDetailQuill.getText().trim().length === 0) {
                addBankDetailQuill.setContents([{insert: ''}]);
            }
        })

    window.editBankNotesQuill = new Quill('#editBankNotesQuillData', {
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['clean'],
            ],
            keyboard: {
                bindings: {
                    tab: 'disabled',
                }
            }
        },
        placeholder: 'Description',
        theme: 'snow', // or 'bubble'
    })
    editBankNotesQuill.on('text-change', function (delta, oldDelta, source) {
        if (editBankNotesQuill.getText().trim().length === 0) {
            editBankNotesQuill.setContents([{ insert: '' }]);
        }
    })
}

listenClick('.addBankModal', function () {
    $('#addBankModal').appendTo('body').modal('show');
})

listenSubmit('#addBankForm', function (e) {
    e.preventDefault();
    processingBtn('#addBankForm', '#bankSaveBtn', 'loading');

    let element = document.createElement('textarea');
    let addBankNotesEditorContent = addBankDetailQuill.root.innerHTML;
    element.innerHTML = addBankNotesEditorContent;
    let dataDesc = JSON.stringify(addBankNotesEditorContent);
    $('#notes').val(dataDesc.replace(/"/g, ''));
    $.ajax({
        url: route('banks.store'),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#addBankModal').modal('hide');
                window.livewire.emit('refresh');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#addBankForm', '#bankSaveBtn');
        },
    });
});

listenClick('.bank-edit-btn', function (event) {
    let bankId = $(this).attr('data-id');
    bankEditRenderData(bankId);
});

function bankEditRenderData(bankId) {
    $.ajax({
        url: route('banks.edit', bankId),
        type: 'GET',
        success: function (result) {
            if (result.success) {
                let element = document.createElement('textarea');
                element.innerHTML = result.data.name;
                $('#bankId').val(result.data.id);
                $('#editName').val(element.value);
                $('#editAccNo').val(result.data.acc_no);
                $('#editAccName').val(result.data.acc_name);
                $('#editSwiftCode').val(result.data.swift_code);
                $('#editNotes').val(result.data.notes);
                $('#editActive').prop('checked', result.data.is_active);
                $('#editBankModal').appendTo('body').modal('show');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
}

listenSubmit('#editBankForm', function (event) {
    event.preventDefault();
    processingBtn('#editBankForm', '#editBankSaveBtn', 'loading');
    let editBankId = $('#bankId ').val();

    let element = document.createElement('textarea');
    let editBankNotesEditorContent = editBankNotesQuill.root.innerHTML;
    element.innerHTML = editBankNotesEditorContent;
    let dataDesc = JSON.stringify(editBankNotesEditorContent);
    $('#editNotes').val(dataDesc.replace(/"/g, ''));
    $.ajax({
        url: route('banks.update', editBankId),
        type: 'put',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#editBankModal').modal('hide');
                window.livewire.emit('refresh');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#editBankForm', '#editBankSaveBtn');
        },
    });
});

listenHiddenBsModal('#addBankModal', function () {
    $('#functionalBtnSave').attr('disabled', false);
    resetModalForm('#addBankForm', '#validationErrorsBox');
})

listenHiddenBsModal('#editBankModal', function () {
    resetModalForm('#editBankForm', '#editValidationErrorsBox');
})

listenShowBsModal('#addBankModal', function () {
    $('#name').focus();
})
listenShowBsModal('#editBankModal', function () {
    $('#editName').focus();
})

listenChange('.isBankActive', function (event) {
    let bankId = $(this).attr('data-id');
    $.ajax({
        url: route('banks.changeStatus', bankId),
        method: 'post',
        cache: false,
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                livewire.emit('refreshDatatable');
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
})
