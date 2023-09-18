$(window).scrollTop(0);

document.addEventListener('turbo:load', loadJobSearchData);

function loadJobSearchData() {
    let salaryFromSlider = $('#salaryFrom');
    let salaryToSlider = $('#salaryTo');
    if(!$('#salaryFrom').length && !$('#salaryTo').length) {
        return
    }
    let jobExperienceSlider = $('#jobExperience');
    if (!salaryFromSlider.length && !salaryToSlider.length &&
        !jobExperienceSlider.length) {
        return;
    }
    
    $('#searchCategories').select2()
    $('#searchSkill').select2()
    $('#searchGender').select2()
    $('#searchCareerLevel').select2()
    $('#searchFunctionalArea').select2()
    let input = JSON.parse($('#input').val())
    
    $(document).on('change', '.jobType', function () {
        let jobType = [];
        $('input:checkbox[name=job-type]:checked').each(function () {
            jobType.push($(this).val());
        });
        if (jobType.length > 0) {
            window.livewire.emit('changeFilter', 'types', jobType);
        } else {
            window.livewire.emit('resetFilter');
        }
    });
    $('input[name=job-type]').prop('checked', false);
    if ($('#jobExperience').length) {
        var rangEle = $('#jobExperience').siblings()[1]
        if (typeof rangEle !== "undefined"){
            rangEle.remove()
        }
        $('#jobExperience').ionRangeSlider({
            type: 'single',
            min: 0,
            step: 1,
            max: 30,
            max_postfix: '+',
            onFinish: function (data) {
                window.livewire.emit('changeFilter', 'jobExperience',
                    data.from);
            },
        });
        $('#jobExperience').addClass('irs-hidden-input')
    }
    // $("#salaryFrom").ionRangeSlider({
    //     min: 0,
    //     max: 150000,
    //     from: 0,
    // });
    if (salaryFromSlider.length) {
        var rangEle = $('#salaryFrom').siblings()[1]
        if (typeof rangEle !== "undefined"){
            rangEle.remove()
        }
        $("#salaryFrom").ionRangeSlider({
            type: 'single',
            min: 0,
            step: 100,
            max: 150000,
            max_postfix: '+',
            onFinish: function (data) {
                window.livewire.emit('changeFilter', 'salaryFrom', data.from);
            },
        })
        $('#salaryFrom').addClass('irs-hidden-input')
    }

    if (salaryToSlider.length) {
        var rangEle = salaryToSlider.siblings()[1]
        if (typeof rangEle !== "undefined"){
            rangEle.remove()
        }
        salaryToSlider.ionRangeSlider({
            type: 'single',
            min: 0,
            step: 100,
            max: 150000,
            max_postfix: '+',
            onFinish: function (data) {
                window.livewire.emit('changeFilter', 'salaryTo', data.from);
            },
        });
        salaryToSlider.addClass('irs-hidden-input')
    }
    $('#searchCategories').on('change', function () {
        window.livewire.emit('changeFilter', 'category', $(this).val());
    });

    $('#searchSkill').on('change', function () {
        window.livewire.emit('changeFilter', 'skill', $(this).val());
    });

    $('#searchGender').on('change', function () {
        window.livewire.emit('changeFilter', 'gender', $(this).val());
    });

    $('#searchCareerLevel').on('change', function () {
        window.livewire.emit('changeFilter', 'careerLevel', $(this).val());
    });

    $('#searchFunctionalArea').on('change', function () {
        window.livewire.emit('changeFilter', 'functionalArea', $(this).val());
    });

    $('#searchByLocation').on('keyup', function () {
        window.livewire.emit('changeFilter', 'searchByLocation', $(this).val());
    });
    // $('#searchByLocation').on('click', function () {
    //     window.livewire.emit('resetFilter');
    // });
    if (input.location != '') {
        $('#searchByLocation').val(input.location);
        window.livewire.emit('changeFilter', 'searchByLocation',
            input.location);
    }

    if (input.keywords != '') {
        window.livewire.emit('changeFilter', 'title', input.keywords);
    }

    // $(document).on('change', '.jobType',function () {
    $(document).on('click', '.reset-filter',function () {
        window.livewire.emit('resetFilter');
        salaryFromSlider.data('ionRangeSlider').update({
            from: 0,
            to: 0,
        });
        salaryToSlider.data('ionRangeSlider').update({
            from: 0,
            to: 0,
        });
        jobExperienceSlider.data('ionRangeSlider').update({
            from: 0,
            to: 0,
        });
        $('#searchByLocation').val("");
        $('#searchFunctionalArea').val('').trigger("change");
        $('#searchCareerLevel').val('').trigger("change");
        $('#searchGender').val('').val('').trigger("change");
        $('#searchSkill').val('').val('').trigger("change");
        $("#searchCategories").val('').trigger("change");
        $('.jobType').prop('checked', false);
    });
    if ($(window).width() > 991) {
        $('#search-jobs-filter').show();
        $('#collapseBtn').hide();
    } else {
        $('.job-post-sidebar').hide();
        $('#collapseBtn').click(function () {
            $('.job-post-sidebar').show();
        });
    }
}
document.addEventListener('livewire:load', function () {
    window.livewire.hook('message.processed', () => {
        $(window).scrollTop(0);
        $(document).on('click', '#jobsSearchResults ul li', function () {
            $('#searchByLocation').val($(this).text());
            $('#jobsSearchResults').fadeOut();
        });
    });
});
