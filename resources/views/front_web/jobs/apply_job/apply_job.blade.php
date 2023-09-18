@extends('front_web.layouts.app')
@section('title')
    {{ __('web.job_details.apply_for_job') }}
@endsection
{{--@section('page_css')--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('web_front/css/header-span.css') }}">--}}
{{--        <link href="{{asset('front_web/scss/apply-details.css')}}" rel="stylesheet" type="text/css">--}}
{{--@endsection--}}
@section('content')
    <div class="apply-job-page">
        <section class="hero-section position-relative bg-light py-40">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6 text-center mb-lg-0 mb-md-5 mb-sm-4">
                        <div class="hero-content">
                            <h1 class=" text-secondary mb-3">
                                @lang('web.job_details.apply_for_job')
                            </h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb  justify-content-center mb-0">
                                    <li class="breadcrumb-item "><a href="{{route('front.home')}}" class="fs-18 text-gray">@lang('web.home')</a>
                                    </li>
                                    <li class="breadcrumb-item text-primary fs-18" aria-current="page">@lang('web.job_details.apply_for_job')</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="apply-job-section contact-section py-100">
            <div class="container">
                <div class="upper-box">
                    <div class="row">
                        <div class="col-xl-8 col-md-10 mx-auto mb-4">
                            <div class="row mb-3">
                                <div class="col-lg-2">
                                    <img src="{{$job->company->company_url}}" class="mb-4 apply-img">
                                </div>
                                <div class="col-lg-10">
                                    <h2 class="ml-3 mb-2">{{ __('web.apply_for_job.apply_for') }}</h2> <span
                                        class="text-primary ml-3">{{ $job->job_title }}</span>
                                </div>
                            </div>
                            <h3 class="fs-4 mb-0">{{ __('web.apply_for_job.fill_details') }}</h3>
                            <p class="font-weight-bold">@if($job->is_suspended)
                                    {{ 'job is suspended' }}
                                @elseif(!$isActive)
                                    {{ 'job is '.\App\Models\Job::STATUS[$job->status] }}
                                @else
                                    {{ __('web.apply_for_job.due_to_our_continued_growth') }} {{ $job->job_title }} {{ __('web.apply_for_job.or_words_to_that_effect') }}
                                @endif</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-md-10 mx-auto">
                    <form id="applyJobForm" class="py-40 px-40 bg-gray">
                        @csrf
                        @include('front_web.layouts.errors')
                        @include('flash::message')
                        <input type="hidden" value="{{ isset($job) ? $job->id : null }}" name="job_id">
                        <div class="row">
                            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                <div class="response"></div>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 form-group chosen-search">
                                <label class="fs-16 text-secondary mb-2" for="resumeId">{{ __('messages.apply_job.resume').':' }}<span
                                            class="text-danger">*</span></label>
                                <select class="chosen-search-select form-select" data-live-search="true" data-size="5"
                                        name="resume_id" id="resumeId" data-control="select2">
                                    <option value="">{{ __('web.job_menu.none') }}</option>
                                    @foreach($resumes as $key => $value)
                                        <option value="{{ $key }}" {{ ($isJobDrafted) ? $key==$draftJobDetails->resume_id ? 'selected' : '':'' }}>
                                            {{ html_entity_decode($value) }}
                                        </option>
                                    @endforeach
                                </select>
                                {{--                            {{ Form::select('resume_id', $resumes, ($isJobDrafted) ? $draftJobDetails->resume_id : $default_resume, ['class' => 'selectpicker form-control','id' => 'resumeId','placeholder'=>'Select Resume', 'required']) }}--}}
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 form-group">
                                <label class="fs-16 text-secondary mb-2" for="expected_salary">{{ __('messages.candidate.expected_salary').':' }}<span
                                        class="text-danger">*</span></label>
                                <input type="text" id="expected_salary" name="expected_salary" min="0" max="9999999999"
                                       value="{{ ($isJobDrafted) ? $draftJobDetails->expected_salary : '' }}"
                                       class="form-control price-input" required>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <label class="fs-16 text-secondary mb-2" for="notes">{{ __('messages.apply_job.notes').':' }}</label>
                                <textarea rows="5" id="notes" name="notes"
                                          class="form-control">{{ ($isJobDrafted) ? $draftJobDetails->notes : '' }}</textarea>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group mt10 text-center">
                                <div class="g-recaptcha d-flex justify-content-center"
                                     data-sitekey="{{ config('app.google_recaptcha_site_key') }}" name="g-recaptcha"></div>
                                <div id="g-recaptcha-error"></div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 form-group text-center">
                                @if(!$isApplied)
                                    @if(!$isJobDrafted)
                                        <button class="btn btn-primary mx-2 save-draft"
                                                data-loading-text="<span class='spinner-border spinner-border-sm'></span> {{__('messages.common.process')}}"
                                                id="draftJobSave">{{__('web.common.save_as_draft')}}
                                        </button>
                                    @endif
                                    @if($isActive && !$job->is_suspended)
                                        <button class="btn btn-secondary mx-2 apply-job"
                                                data-loading-text="<span class='spinner-border spinner-border-sm'></span> {{__('messages.common.process')}}"
                                                id="applyJobSave">{{ __('web.common.apply') }}</button>
                                    @endif
                                @else
                                    <button class="theme-btn btn-style-eight">{{ __('web.apply_for_job.already_applied') }}</button>
                                @endif
                            </div>
                        </div>
                    </form>
                    {{--                @endif--}}
                </div>
            </div>
        </section>
    </div>
@endsection
{{--@section('page_scripts')--}}
{{--    <script>--}}
{{--        let applyJobUrl = "{{ route('apply-job') }}";--}}
{{--        let jobDetailsUrl = "{{ url('job-details') }}";--}}
{{--    </script>--}}
{{--    <script src="{{asset('assets/js/custom/input_price_format.js')}}"></script>--}}
{{--    <script src="{{ asset('assets/js/jobs/front/apply_job.js') }}"></script>--}}
{{--@endsection--}}

