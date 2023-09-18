listenClick('.job-expired-delete-btn', function (event) {
    let jobId = $(this).attr('data-id');
    deleteItem(route('admin.jobs.destroy', jobId), Lang.get('messages.job.expired_job'));
})
