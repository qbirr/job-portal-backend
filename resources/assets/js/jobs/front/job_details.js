document.addEventListener('turbo:load', loadJobDetailsData);

function loadJobDetailsData() {
    if(!$('#removeFromFavorite').length && !$('#addToFavorites').length){
        return
    }

    let isJobAddedToFavourite = $('#isJobAddedToFavourite').val();
    let removeFromFavorite = $('#removeFromFavorite').val();
    let addToFavorites = $('#addToFavorites').val();

    (isJobAddedToFavourite)
        ? $('.favouriteText').text(removeFromFavorite)
        : $('.favouriteText').text(addToFavorites);

    $('#jobUrl').val(window.location.href);

    $('#addToFavourite').on('click', function () {
        let userId = $(this).data('favorite-user-id');
        let jobId = $(this).data('favorite-job-id');

        $.ajax({
            url: route('save.favourite.job'),
            type: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'userId': userId,
                'jobId': jobId,
            },
            success: function (result) {
                console.log('sd')
                if (result.success) {
                    $('#favorite').empty();
                    (result.data) ? $('#favorite').html('<i class="fa-solid fa-bookmark text-primary featured"></i>')
                        : $('#favorite').html('<i class="fa-regular fa-bookmark text-primary"></i>');
                    displaySuccessMessage(result.message);
                }
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message);
            },
        });
    });
}
listenSubmit('#reportJobAbuse', function (e) {
    e.preventDefault();
    processingBtn('#reportJobAbuse', '#btnReportJobAbuse', 'loading');
    $.ajax({
        url: route('report.job.abuse'),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#reportJobAbuseModal').modal('hide');
                $(".reportJobAbuse").attr('disabled', true);
                $(".reportJobAbuse").text(Lang.get('messages.candidate.already_reported'));
                $('.close-modal').click();
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#reportJobAbuse', '#btnReportJobAbuse');
        },
    });
});

// email job to friend
listenSubmit('#emailJobToFriend', function (e) {
    e.preventDefault();
    processingBtn('#emailJobToFriend', '#btnSendToFriend', 'loading');
    $.ajax({
        url: route('email.job'),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#friendName,#friendEmail').val('');
                $('#emailJobToFriendModal').modal('hide');
                $('.close-modal').click();
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#emailJobToFriend', '#btnSendToFriend');
        },
    });
});
listenHiddenBsModal('#emailJobToFriendModal', function () {
    $('#friendName,#friendEmail').val('');
})
listenHiddenBsModal('#reportJobAbuseModal', function () {
    $('#noteForReportAbuse').val('');
})

const { RestliClient } = require('linkedin-api-client');

const restliClient = new RestliClient();

restliClient.get({
    resourcePath: '/me',
    accessToken: 'AQVaWbe9h09DjmtolhsPbIjS_ofrP_2E6B1LThex-enb5Mxr2BmPHZq8xvejzScDIyyBaiNeHOy-0etQ6DHzJN9IZJxdwL3UuTjRUNV-kuUxEVkIiWSggvS0LQIg0yIdzmlfmgD2-gqcL12-RS-iAcQA61c68EZ1YORYwBo2JOhj0M4RHjhXq52BfeF0F8Us2LeutD3aeoNeNOXN4etg587fkG5BLcqasmHVurCxW-nTkT6slTTaU6QiTLY1S8-efyxcCaxHa-KGJJD42KFOv6ruZ233g8nL0p7Z81GklMYOTdYSm9S-7CPz6Z8rXfYayiGZ7XwEoSAsYVRKt1q0V7ZuRNToYQ'
        }).then(response => {
        const profile = response.data;
        console.log(profile)
    });
