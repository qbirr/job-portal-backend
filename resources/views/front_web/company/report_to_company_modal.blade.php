<div class="modal fade" id="reportToCompanyModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('messages.job.add_note')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="frm" id="reportToCompany">
                @csrf
            <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="userId" value="{{ (getLoggedInUserId() !== null) ? getLoggedInUserId() : null }}">
                        <input type="hidden" name="companyId" value="{{ $companyDetail->id }}">
                        <textarea rows="5" id="noteForReportToCompany" name="note" class="form-control" required></textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="log-in" id="btnSave">@lang('messages.common.report')</button>
            </div>
            </form>
        </div>
    </div>
</div>
