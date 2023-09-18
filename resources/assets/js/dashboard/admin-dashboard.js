document.addEventListener('turbo:load', loadAdminDashboardData);

function loadAdminDashboardData () {

    if (!$('#weeklyUserBarChartContainer').length) {
        return;
    }
    let timeRange = $('#timeRange');
    let isPickerApply = false;
    const today = moment();
    let start = today.clone().startOf('week');
    let end = today.clone().endOf('days');
    timeRange.on('apply.daterangepicker', function (ev, picker) {
        isPickerApply = true;
        start = picker.startDate.format('YYYY-MM-D  H:mm:ss');
        end = picker.endDate.format('YYYY-MM-D  H:mm:ss');
        loadDashboardData(start, end);
    });

    const lastMonth = moment().startOf('month').subtract(1, 'days');
    const thisMonthStart = moment().startOf('week');
    const thisMonthEnd = moment().endOf('week');

    window.cb = function (start, end) {
        timeRange.find('span').
            html(
                start.format('MMM D, YYYY') + ' - ' +
                end.format('MMM D, YYYY'));
    };

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
            [Lang.get('messages.datepicker.this_week')]: [moment().startOf('week'), moment().endOf('week')],
            [Lang.get('messages.datepicker.last_week')]: [
                moment().startOf('week').subtract(7, 'days'),
                moment().startOf('week').subtract(1, 'days')],
        },
    }, cb);

    cb(start, end);

    window.loadDashboardData = function (startDate, endDate) {
        $.ajax({
            type: 'GET',
            url: route('dashboard.chart.data'),
            dataType: 'json',
            data: {
                start_date: startDate,
                end_date: endDate,
            },
            cache: false,
        }).done(
            WeeklyBarChart,
            PostStatistics,
        );
    };

    window.WeeklyBarChart = function (result) {
        $('#weeklyUserBarChartContainer').html('');
        $('canvas#weeklyUserBarChart').remove();
        $('#weeklyUserBarChartContainer').
            append(
                '<canvas id="weeklyUserBarChart" width="515" height="400"></canvas>');

        let data = result.data.weeklyChartData;
        const weeklyData = {
            labels: data.weeklyLabels,
            datasets: [
                {
                    label: 'Employers',
                    backgroundColor: '#7239ea',
                    data: data.totalEmployerCount,
                }, {
                    label: 'Candidates',
                    backgroundColor: '#109ef7',
                    data: data.totalCandidateCount,
                }],
        };
        let ctx = $('#weeklyUserBarChart');
        let config = new Chart(ctx, {
            type: 'bar',
            data: weeklyData,
            options: {
                scales: {
                    xAxes: [
                        {
                            stacked: true,
                            gridLines: {
                                display: false,
                            },
                        }],
                    yAxes: [
                        {
                            stacked: true,
                            ticks: {
                                min: 0,
                                precision: 0,
                            },
                            type: 'linear',
                        }],
                },
            },
        });
    };

    window.PostStatistics = function (result) {
        $('#postStatisticsChartContainer').html('');
        $('canvas#postStatisticsChart').remove();
        $('#postStatisticsChartContainer').
            append(
                '<canvas id="postStatisticsChart" width="515" height="400"></canvas>');

        let data = result.data.postStatisticsChartData;
        const postStatisticsLineChartData = {
            labels: data.weeklyPostLabels,
            datasets: [
                {
                    label: 'Posts',
                    data: data.totalPostCount,
                    backgroundColor: '#109ef7',
                    borderColor: '#109ef7',
                    hoverOffset: 4,
                    pointRadius: 5,
                    pointHoverRadius: 5,
                    fill: false,
                    tension: 0.1,
                }],
        };

        let postStatistics = $('#postStatisticsChart');

        let myChart = new Chart(postStatistics, {
            type: 'line',
            data: postStatisticsLineChartData,
            options: {
                legend: false,
                scales: {
                    xAxes: [
                        {
                            stacked: true,
                            gridLines: {
                                display: false,
                            },
                        }],
                    yAxes: [
                        {
                            stacked: true,
                            ticks: {
                                min: 0,
                                precision: 0,
                            },
                            type: 'linear',
                        }],
                },
            }
        });
    }
    if (start !== null) {
        loadDashboardData(start.format('YYYY-MM-D H:mm:ss'),
            end.format('YYYY-MM-D H:mm:ss'));
    }
    let applyBtn = $('.range_inputs > button.applyBtn');
    if (applyBtn.length) {
        $(document).on('click', '.ranges li', function () {
            if ($(this).data('range-key') === 'Custom Range') {
                applyBtn.css('display', 'initial');
            } else {
                applyBtn.css('display', 'none');
            }
        });
        applyBtn.css('display', 'none');
    }
}
