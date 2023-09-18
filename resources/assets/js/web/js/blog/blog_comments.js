document.addEventListener('turbo:load', loadFrontBlogComments)

function loadFrontBlogComments () {
    window.scrollTo(0, 0);
}

listenSubmit('#commentForm', function (event) {
    event.preventDefault();
    processingBtn('#commentForm', '#submitBtn', 'loading');

    if ($('.comment-id').val() === '') {
        addComment();
    } else {
        updateComment();
    }
});

listenClick('.delete-comment-btn', function (event) {
    event.preventDefault();
    let deleteId = $(this).data('id');
    let deletedCommentBtn = $(this);
    swal({
        title: Lang.get('messages.common.delete') + ' !',
        text: Lang.get('messages.common.are_you_sure_want_to_delete') + '"' + Lang.get('messages.post.comment') + '" ?',
        type: 'warning',
        showCancelButton: true,
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
        confirmButtonColor: '#6777ef',
        cancelButtonColor: '#d33',
        buttons: {
            confirm: Lang.get('messages.common.yes'),
            cancel: Lang.get('messages.common.no')
        },
    }, function (isConfirmed) {
        if (isConfirmed) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
            });
            $.ajax({
                type: 'DELETE',
                url: route('blog.delete.comment', deleteId),
                success: function (result) {
                    deletedCommentBtn.closest('.comment-card').remove();
                    $('.comment-count').text('');
                    if ($('.comments').find('.comment-card').length !== 0) {
                        $('.comment-count').append('<span>(' +
                            $('.comments').find('.comment-card').length +
                            ')</span>');
                    } else {
                        postComment();
                    }
                    swal({
                        title: Lang.get('messages.common.deleted') + ' !',
                        text: Lang.get('messages.post.comment') +
                            Lang.get('messages.common.has_been_deleted'),
                        type: 'success',
                        confirmButtonColor: '#1967D2',
                        timer: 2000,
                    });
                    // location.reload();
                }
            });
        }
    });
});

listenClick('.edit-comment-btn', function (event) {
    event.preventDefault();

    let editId = $(this).data('id');
    $('.comment-id').val($('.delete-comment-btn').data('id'));
    $.ajax({
        type:'GET',
        url:route('blog.edit.comment', editId),
        success:function(result){
            $('.comment').val(result.data.comment);
            $('.comment-name').val(result.data.name);
            $('.comment-email').val(result.data.email);
            $('.comment-id').val(result.data.id);
            $('#comment-field').focus();
        },
        error:function (result){
            displayErrorMessage(result.responseJSON.message);
        }
    });
});

function addComment(){
    $.ajax({
        type: 'POST',
        url: $('#blogComment').val(),
        data: $('#commentForm').serialize(),
        success: function (result) {
            if (result.success) {
                // setTimeout(function () {
                //     location.reload();
                // }, 5000);
                let commentCount = $('.comments').find('.comment-card').length +
                    1;
                if ($('.comments').find('.comment-card').length === 0) {
                    $('.comment-count').append('(' + commentCount + ')');
                } else {
                    $('.comment-count').text('');
                    $('.comment-count').append('(' + commentCount + ')');
                }
                if (commentCount >= 0) {
                    $('.comments').show()
                    $('#post-comment').show();
                }
                let data = [
                    {
                        'image': !isEmpty(result.data.user) ? result.data.user.avatar : $('#defaultBlogImage').val(),
                        'commentName': result.data.name,
                        'commentCreated': moment(result.data.created_at).format('DD, MMM yy hh:mm a'),
                        'comment': result.data.comment,
                        'id': result.data.id,
                    }];
                $('.comment-box').prepend(
                    prepareTemplateRender('#blogTemplate', data));
                $('#commentForm')[0].reset();
                displaySuccessMessage(result.message);
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#commentForm', '#submitBtn');
        },
    });
}
function updateComment(){
    let updateId = $('.comment-id').val();
    $.ajax({
        type: 'PUT',
        url: route('blog.update.comment', updateId),
        data: $('#commentForm').serialize(),
        success:function (result){
            $('#comment-'+updateId).html('');
            $('#comment-'+updateId).html(result.data.comment);

            $('#commentForm')[0].reset();
            $('.comment-id').val('');
            displaySuccessMessage(result.message);
            processingBtn('#commentForm', '#submitBtn');
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            processingBtn('#commentForm', '#submitBtn');
        },
    });
}

function postComment() {
    let count = $('.comment-count').text();
    let newCount = count.replace('(', '').replace(')', '');
    if (newCount == 0) {
        $('.comments').hide()
        $('#post-comment').hide();
    }
}

postComment();
