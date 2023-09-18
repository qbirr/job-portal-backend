@extends('employer.layouts.app')
@section('title')
    {{ __('messages.employer_dashboard.dashboard') }}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
@endpush
@section('content')
    <div class="row">
        <div class="col-xl-4 col-sm-6 widget">
            <div class="bg-success shadow-md rounded-10 p-xxl-10 px-5 py-10 d-flex align-items-center justify-content-between my-sm-3 my-2">
                <div class="bg-green-300 widget-icon rounded-10 me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-briefcase text-white fs-1-xl fa-4x"></i>
                </div>
                <div class="text-end text-white">
                    <h2 class="fs-1-xxl fw-bolder text-white">{{ isset($totalJobs)?numberFormatShort($totalJobs):'0' }}</h2>
                    <h3 class="mb-0 fs-4 fw-light">{{ __('messages.employer_menu.total_jobs') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 widget">
            <div class="bg-primary shadow-md rounded-10 p-xxl-10 px-5 py-10 d-flex align-items-center justify-content-between my-sm-3 my-2">
                <div class="bg-cyan-300 widget-icon rounded-10 me-2 d-flex align-items-center justify-content-center">
                    <i class="far fa-clock text-white fs-1-xl fa-4x"></i>
                </div>
                <div class="text-end text-white">
                    <h2 class="fs-1-xxl fw-bolder text-white">{{ isset($jobCount)?numberFormatShort($jobCount):'0' }}</h2>
                    <h3 class="mb-0 fs-4 fw-light">{{ __('messages.employer_menu.live_jobs') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 widget">
            <div class="bg-warning shadow-md rounded-10 p-xxl-10 px-5 py-10 d-flex align-items-center justify-content-between my-sm-3 my-2">
                <div class="bg-yellow-300 widget-icon rounded-10 me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-pause-circle text-white fs-1-xl fa-4x"></i>
                </div>
                <div class="text-end text-white">
                    <h2 class="fs-1-xxl fw-bolder text-white">{{ isset($pausedJobCount)?numberFormatShort($pausedJobCount):'0' }}</h2>
                    <h3 class="mb-0 fs-4 fw-light">{{ __('messages.employer_menu.paused_jobs') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 widget">
            <div class="bg-danger shadow-md rounded-10 p-xxl-10 px-5 py-10 d-flex align-items-center justify-content-between my-sm-3 my-2">
                <div
                        class="bg-red-300 widget-icon rounded-10 me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-window-close text-white fs-1-xl fa-4x"></i>
                </div>
                <div class="text-end text-white">
                    <h2 class="fs-1-xxl fw-bolder text-white">{{ isset($closedJobCount)?numberFormatShort($closedJobCount):'0' }}</h2>
                    <h3 class="mb-0 fs-4 fw-light">{{ __('messages.employer_menu.closed_jobs') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 widget">
            <div class="bg-info shadow-md rounded-10 p-xxl-10 px-5 py-10 d-flex align-items-center justify-content-between my-sm-3 my-2">
                <div
                        class="bg-blue-300 widget-icon rounded-10 me-2 d-flex align-items-center justify-content-center">
                    <i class="far fa-user text-white fs-1-xl fa-4x"></i>
                </div>
                <div class="text-end text-white">
                    <h2 class="fs-1-xxl fw-bolder text-white">{{ isset($followersCount)?numberFormatShort($followersCount):'0' }}</h2>
                    <h3 class="mb-0 fs-4 fw-light">{{ __('messages.employer_menu.followers') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 widget">
            <div class="bg-dark shadow-md rounded-10 p-xxl-10 px-5 py-10 d-flex align-items-center justify-content-between my-sm-3 my-2">
                <div
                        class="bg-gray-700 widget-icon rounded-10 me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-file fa-4x fs-1-xl {{getLoggedInUser()->theme_mode ? 'text-muted' : 'text-white'}}"></i>
                </div>
                <div class="text-end {{getLoggedInUser()->theme_mode ? 'text-muted' : 'text-white'}}">
                    <h2 class="fs-1-xxl fw-bolder text-light"> {{ isset($jobApplicationsCount) ? numberFormatShort($jobApplicationsCount) : '0' }}</h2>
                    <h3 class="mb-0 fs-4 fw-light text-light">{{ __('messages.employer_menu.total_job_applications') }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-xl-stretch mb-xl-8">
        <!--begin::Header-->
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                                    <span
                                            class="card-label fs-3 mb-1">{{ __('messages.job_applications') }}</span>
            </h3>
            <div class="col-lg-8 col-md-8 col-sm-12">
                <div class="row justify-content-end">
                    <div class="col-lg-4 col-md-4 col-xl-3 col-sm-4 mt-3 mt-md-0 ">
                        <div class="card-header-action w-100">
                            {{  Form::select('jobs', $jobStatus, null, ['id' => 'jobStatus', 'class' => 'form-control status-filter', 'placeholder' => __('messages.flash.select_job')]) }}
                        </div>
                    </div>
                    <div class="col-lg-4  col-md-4 col-xl-3 col-sm-4 mt-3 mt-md-0">
                        <div class="card-header-action w-100">
                            {{  Form::select('gender', $gender, null, ['id' => 'gender', 'class' => 'form-control status-filter', 'placeholder' => __('messages.company.select_gender')]) }}
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xl-4 col-sm-4 mt-0">
                        <div id="timeRange" class="time_range time_range_width w-30 border rounded-2 p-3">
                            <i class="far fa-calendar-alt"
                               aria-hidden="true"></i>&nbsp;&nbsp;<span></span> <b
                                    class="caret"></b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="jobContainer" class="card-body">
            <canvas id="employerDashboardChart" width="400" height="400"></canvas>
        </div>
    </div>
    <div class="row">
        <!--begin::Col-->
        <div class="col-xl-6 ps-0">
            <!--begin::Tables Widget 1-->
            <div class="mb-xl-8">
                <!--begin::Header-->
                <div class="d-flex justify-content-between border-0 pt-5">
                    <h3 class="align-items-start flex-column">
                        <span class="fs-3 mb-1">{{ __('messages.employer_menu.recent_jobs') }}</span>
                    </h3>
                    <!--begin::Menu-->
                    <!--begin::Svg Icon | path: icons/duotune/general/gen024.svg-->
                    <span>
                         <a href="{{ route('job.index') }}"
                            class="btn btn-info">{{ __('messages.common.view_more') }} <i
                                     class="fas fa-chevron-right"></i></a>
                    </span>
                    <!--end::Svg Icon-->
                    <!--end::Menu-->
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="py-3">
                    <!--begin::Table container-->
                    <div class="table-responsive ">
                        <!--begin::Table-->
                        <table class="table table-striped align-middle gs-0 gy-5">
                            <!--begin::Table head-->
                            <thead>
                            <tr class="text-start text-muted  fs-7 text-uppercase gs-0">
                                <th class="">{{ __('messages.job.job_title') }}</th>
                                <th class="">{{ __('messages.employer_menu.expires_on') }}</th>
                                <th class="text-center">{{ __('messages.common.status') }}</th>
                            </tr>
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="text-gray-600 fw-bold">
                            @if(count($recentJobs) > 0)
                                @foreach($recentJobs as $recentJob)
                                    <tr>
                                        <td class="ps-3">
                                            <a href="{{ route('front.job.details',$recentJob->job_id) }}"
                                               class="text-decoration-none"
                                               data-turbo="false">{{ html_entity_decode($recentJob->job_title) }}</a>
                                        </td>
                                        <td>
                                            {{ Carbon\Carbon::parse($recentJob->job_expiry_date)->translatedFormat('jS M, Y') }}
                                        </td>
                                        <td class="text-center">
                                            <div
                                                    class="badge w-auto bg-{{\App\Models\Job::STATUS_COLOR[$recentJob->status]}}">
                                                <span
                                                        class="px-3">{{ \App\Models\Job::STATUS[$recentJob->status] }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Table container-->
                </div>
            </div>
            <!--endW::Tables Widget 1-->
        </div>
        <!--end::Col-->
        <div class="col-xl-6 pe-0">
            <!--begin::Tables Widget 1-->
            <div class="mb-xl-8">
                <!--begin::Header-->
                <div class="d-flex justify-content-between border-0 pt-5">
                    <h3 class="align-items-start flex-column">
                        <span class=" fs-3 mb-1">{{ __('messages.employer_menu.recent_follower') }}</span>
                    </h3>
                    <!--begin::Menu-->
                    <!--begin::Svg Icon | path: icons/duotune/general/gen024.svg-->
                    <span>
                         <a href="{{ route('followers.index') }}"
                            class="btn btn-info">{{ __('messages.common.view_more') }} <i
                                     class="fas fa-chevron-right"></i></a>
                    </span>
                    <!--end::Svg Icon-->
                    <!--end::Menu-->
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="py-3">
                    <!--begin::Table container-->
                    <div class="table-responsive ">
                        <!--begin::Table-->
                        <table class="table table-striped align-middle gs-0 gy-5">
                            <!--begin::Table head-->
                            <thead>
                            <tr class="text-start text-muted fs-7 text-uppercase gs-0">
                                <th class="">{{ __('messages.company.candidate_name') }}</th>
                                <th class="">{{ __('messages.company.candidate_phone') }}</th>
                                <th class=" text-center">{{ __('messages.company.candidate_email') }}</th>
                            </tr>
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="text-gray-600">
                            @if(count($recentFollowers) > 0)
                                @foreach($recentFollowers as $recentFollower)
                                    <tr>
                                        <td class="ps-3">
                                            {{ html_entity_decode($recentFollower->user->full_name) }}
                                        </td>
                                        <td>
                                            {{ empty($recentFollower->user->phone) ? __('messages.common.n/a') : $recentFollower->user->phone }}
                                        </td>
                                        <td>
                                            {{ $recentFollower->user->email }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="text-center">
                                        <span>{{ __('messages.employer_menu.no_data_available') }}.</span>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Table container-->
                </div>
            </div>
            <!--endW::Tables Widget 1-->
        </div>

    </div>
@endsection
