<div class="modal fade" id="reportJobAbuseModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('messages.job.add_note') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="frm" id="reportJobAbuse">
                <div class="modal-body">
                    <input type="hidden" name="userId"
                           value="{{ (getLoggedInUserId() !== null) ? getLoggedInUserId() : null }}">
                    <input type="hidden" name="jobId" value="{{ $job->id }}">
                    <div class="form-group">
                         <textarea rows="5" id="noteForReportAbuse" name="note" class="form-control"
                                   required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('messages.common.close') }}</button>
                    <button type="submit" class="btn btn-primary"
                            data-bs-loading-text="<span class='spinner-border spinner-border-sm'></span> {{__('messages.common.process')}}"
                            id="btnReportJobAbuse">{{ __('web.job_details.report') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
