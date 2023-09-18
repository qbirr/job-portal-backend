document.addEventListener('turbo:load', loadSelectedCandidateData);

function loadSelectedCandidateData(){
    if ($('#filterCandidateStatus').length) {
        $('#filterCandidateStatus').select2();
    }
}

listenClick('#resetFilter', function () {
    $('#filterCandidateStatus').val('').trigger('change');
})
