document.addEventListener('turbo:load', loadEmployerDashboardData);

function loadEmployerDashboardData() {
    if (!$('#employerDashboardChart').length) {
        return;
    }
    let timeRange = $('#timeRange');
    const today = moment();
    let start = today.clone().startOf('month');
    let end = today.clone().endOf('month');
    let jobStatus = $('#jobStatus').val();
    let gender = $('#gender').val();
    let isPickerApply = false;

    $('#jobStatus, #gender').select2({
        width: '100%',
    });

    $('#jobStatus').on('change', function (e) {
        e.preventDefault();
        jobStatus = $('#jobStatus').val();
        let gender = $('#gender').val();
        loadTotalJobsApplication(moment(start).format('YYYY-MM-D  H:mm:ss'),
            moment(end).format('YYYY-MM-D  H:mm:ss'), jobStatus, gender);
    });
    $('#gender').on('change', function (e) {
        e.preventDefault();
        gender = $('#gender').val();
        jobStatus = $('#jobStatus').val();
        loadTotalJobsApplication(moment(start).format('YYYY-MM-D  H:mm:ss'),
            moment(end).format('YYYY-MM-D  H:mm:ss'), jobStatus, gender);
    });

    timeRange.on('apply.daterangepicker', function (ev, picker) {
        isPickerApply = true;
        start = picker.startDate.format('YYYY-MM-D  H:mm:ss');
        end = picker.endDate.format('YYYY-MM-D  H:mm:ss');
        loadTotalJobsApplication(start, end, jobStatus, gender);
    });

    window.cb = function (start, end) {
        timeRange.find('span').
            html(
                start.format('MMM D, YYYY') + ' - ' +
                end.format('MMM D, YYYY'));
    };

    cb(start, end);

    const lastMonth = moment().startOf('month').subtract(1, 'days');
    const thisMonthStart = moment().startOf('month');
    const thisMonthEnd = moment().endOf('month');

    timeRange.daterangepicker({
        startDate: start,
        endDate: end,
        opens: 'left',
        showDropdowns: true,
        autoUpdateInput: false,
        locale: {
            customRangeLabel: Lang.get('messages.common.custom'),
            applyLabel: Lang.get('messages.common.apply'),
            cancelLabel: Lang.get('messages.common.cancel'),
            fromLabel: Lang.get('messages.common.from'),
            toLabel: Lang.get('messages.common.to'),
            monthNames: [
                Lang.get('messages.months.jan'),
                Lang.get('messages.months.feb'),
                Lang.get('messages.months.mar'),
                Lang.get('messages.months.apr'),
                Lang.get('messages.months.may'),
                Lang.get('messages.months.jun'),
                Lang.get('messages.months.jul'),
                Lang.get('messages.months.aug'),
                Lang.get('messages.months.sep'),
                Lang.get('messages.months.oct'),
                Lang.get('messages.months.nov'),
                Lang.get('messages.months.dec')
            ],

            daysOfWeek: [
                Lang.get('messages.weekdays.sun'),
                Lang.get('messages.weekdays.mon'),
                Lang.get('messages.weekdays.tue'),
                Lang.get('messages.weekdays.wed'),
                Lang.get('messages.weekdays.thu'),
                Lang.get('messages.weekdays.fri'),
                Lang.get('messages.weekdays.sat')],
        },
        ranges: {
            [Lang.get('messages.datepicker.today')]: [moment(), moment()],
            [Lang.get('messages.datepicker.this_week')]: [moment().startOf('week'), moment().endOf('week')],
            [Lang.get('messages.datepicker.last_week')]: [
                moment().startOf('week').subtract(7, 'days'),
                moment().startOf('week').subtract(1, 'days')],
            [Lang.get('messages.datepicker.this_month')]: [thisMonthStart, thisMonthEnd],
            [Lang.get('messages.datepicker.last_month')]: [
                lastMonth.clone().startOf('month'),
                lastMonth.clone().endOf('month')],
        },
    }, cb);

    window.loadTotalJobsApplication = function (
        startDate, endDate, jobStatus = null, gender = null) {
        $.ajax({
            type: 'GET',
            url: route('employer.dashboard.chart'),
            dataType: 'json',
            data: {
                start_date: startDate,
                end_date: endDate,
                job_status: jobStatus,
                gender: gender,
            },
            cache: false,
        }).done(prepareJobsReport);
    };
    window.prepareJobsReport = function (result) {
        $('#employerDashboardChart').html('');
        let data = result.data;
        if (data.totalJobApplication === 0) {
            $('#jobContainer').html('');
            $('#jobContainer').
                append(
                    '<div align="center" class="pt50 h150">No Records Found</div>');
            return true;
        } else {
            $('#jobContainer').html('');
            $('#jobContainer').
                append('<canvas id="employerDashboardChart"></canvas>');
        }
        let barChartData = {
            labels: data.dates.dateArr,
            datasets: [
                {
                    label: 'Total Job Applications',
                    backgroundColor: '#11a3f7',
                    data: data.jobApplicationCounts,
                    borderWidth: 1,
                },
            ],
        };
        var ctx = document.getElementById('employerDashboardChart').
            getContext('2d');
        ctx.canvas.style.height = '400px';
        ctx.canvas.style.width = '100%';
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: barChartData,
            options: {
                legend: {
                    display: false,
                },
                scales: {
                    xAxes: [
                        {
                            stacked: true,
                        }],
                    yAxes: [
                        {
                            stacked: true,
                            ticks: {
                                min: 0,
                                stepSize: 1,
                            },
                        }],
                },
            },
        });
    };
    loadTotalJobsApplication(start.format('YYYY-MM-D  H:mm:ss'),
        end.format('YYYY-MM-D  H:mm:ss'), jobStatus, gender);
    
    let applyBtn = $('.range_inputs > button.applyBtn');
    $(document).on('click', '.ranges li', function () {
        if ($(this).data('range-key') === 'Custom Range') {
            applyBtn.css('display', 'initial');
        } else {
            applyBtn.css('display', 'none');
        }
    });
    applyBtn.css('display', 'none');
}


