document.addEventListener('DOMContentLoaded', loadEmployerJobData);

function loadEmployerJobData() {
    if (!$('#indexEmployeeJobsData').length) {
        return;
    }

    $('#filter_featured').select2({
        width: '170px',
    });
    $('#filter_status').select2({
        width: '150px',
    });

}
    listenClick('.change-status', function (event) {
        let jobId = $(this).data('id');
        let statusArray = JSON.parse($('#employerJobStatusArray').val());
        let jobStatus = statusArray.indexOf($(this).attr('data-option'));
        // const swalWithBootstrapButtons = swal.mixin({
        //     className: {
        //         confirmButton: 'swal2-confirm btn fw-bold btn-danger mt-0',
        //         cancelButton: 'swal2-cancel btn fw-bold btn-bg-light btn-color-primary mt-0'
        //     },
        //     buttonsStyling: false
        // })

        swal({
            title: Lang.get('messages.flash.attention')+ '!',
            text: Lang.get('messages.flash.are_you_sure_to_change_status'),
            icon: 'warning',
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            confirmButtonColor: '#6777ef',
            cancelButtonColor: '#d33',
            buttons: {
                confirm: Lang.get('messages.common.yes'),
                cancel: Lang.get('messages.common.no'),
            },
        }).then((result) => {
            if (result) {
                changeStatus(jobId, jobStatus);
            }
        });
    });
    function changeStatus(jobId, jobStatus) {
        $.ajax({
            url: route('change-job-status', {'id' : jobId ,'status' : jobStatus} ),
            method: 'get',
            cache: false,
            success: function (result) {
                if (result.success) {
                    window.livewire.emit('refreshDatatable')
                }
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message);
            },
            complete: function () {
                swal.close();
            },
        });
    };
listenClick('.employer-job-delete-btn', function (event) {
    let jobId = $(this).attr('data-id');
    deleteItem(route('job.destroy', jobId), Lang.get('messages.job.job'));
    window.livewire.emit('refreshDatatable');
});

listenClick('.copy-btn', function (event) {
    let copyUrlId = $(event.currentTarget).data('job-id');
    let copyUrl = route('front.job.details', copyUrlId);
    let $temp = $('<input>');
    $('body').append($temp);
    $temp.val(copyUrl).select();
    document.execCommand('copy');
    $temp.remove();
    displaySuccessMessage(Lang.get('messages.flash.link_copy'));
});
