@extends('front_web.layouts.app')
@section('title')
    {{ __('web.job_details.job_details') }}
@endsection
{{--@section('page_css')--}}
{{--    <link href="{{asset('front_web/scss/company-details.css')}}" rel="stylesheet" type="text/css">--}}
{{--@endsection--}}
@section('content')
    <div class="company-details-page">
        <!-- start hero section -->
        <section class="hero-section position-relative bg-light py-40">
            <div class="container">
                <div class="row align-items-center justify-content-center ">
                    <div class="col-12">
                        <div class="row align-items-lg-center mb-3">
                            <div class="col-lg-1 col-sm-2 col-3">
                                <div class="company-profile-img mt-md-0 mt-3">
                                    <img src="{{ (!empty($companyDetail->company_url)) ? $companyDetail->company_url : asset('assets/img/infyom-logo.png') }}"
                                         alt="job_detail_logo">
                                </div>
                            </div>
                            <div class="col-sm-10 col-9">
                                <div class="hero-content ps-xl-0 ps-3">
                                    <h4 class="text-secondary mb-0">
                                        {{ html_entity_decode($companyDetail->user->full_name) }}
                                    </h4>
                                    <div class="hero-desc d-flex align-items-center flex-wrap">
                                        <div class="d-flex align-items-center me-4 pe-2">
                                            <i class="fa-solid fa-briefcase text-gray me-3 fs-18"></i>
                                            <p class="fs-14 text-gray mb-0">
                                                {{!empty($companyDetail->industry->name)? $companyDetail->industry->name : __('messages.common.n/a')}}</p>
                                        </div>
                                        @if(!empty($companyDetail->user->city_id) || (!empty($companyDetail->user->state_id)) || (!empty($companyDetail->user->country_id)))
                                            <div class="desc d-flex align-items-center me-lg-4 me-2 pe-2">
                                                <i class="fa-solid fa-location-dot text-gray me-3 fs-18"></i>
                                                <p class="fs-14 text-gray mb-0">
                                                    {{ (!empty($companyDetail->user->city_id)) ? $companyDetail->user->city_name.', ' : '' }} {{ (!empty($companyDetail->user->country_id)) ? $companyDetail->user->country_name : '' }}</p>
                                            </div>
                                        @endif
                                        @isset($companyDetail->user->phone)
                                            <div class="desc d-flex align-items-center me-lg-4 me-2 pe-2">
                                                <i class="fa-solid fa-phone text-gray me-3 fs-18"></i>
                                                <p class="fs-14 text-gray mb-0">
                                                    {{$companyDetail->user->phone}}</p>
                                            </div>
                                        @endisset
                                        <div class="desc d-flex align-items-center me-lg-4 me-2 pe-2">
                                            <i class="fa-solid fa-envelope text-gray me-3 fs-18"></i>
                                            <p class="fs-14 text-gray mb-0">
                                                {{$companyDetail->user->email}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @role('Candidate')
                        <div class="row align-items-lg-center">
                            <div class="hero-desc d-md-flex">
                                <div class="desc d-flex me-4 pe-2">
                                    <a href="javascript:void(0)" class="btn btn-outline-success reportJobAbuse"
                                       data-favorite-user-id="{{ (getLoggedInUserId() !== null) ? getLoggedInUserId() : null }}"
                                       data-favorite-company_id="{{ $companyDetail->id }}" id="addToFavourite">
                                        <i class="favouriteIcon"></i>
                                        <span class="favouriteText"></span>
                                    </a>
                                </div>
                                <div class="desc d-flex me-4 pe-2">
                                    @if($isReportedToCompany)
                                        <button type="button" class="btn btn-outline-danger reportToCompanyBtn"
                                                disabled data-bs-toggle="modal"
                                                data-bs-target="#reportToCompanyModal">
                                            {{ __('messages.candidate.already_reported') }}
                                        </button>
                                    @else
                                        <button data-bs-toggle="modal" data-bs-target="#reportToCompanyModal"
                                                class="btn btn-outline-danger  reportToCompanyBtn {{ ($isReportedToCompany) ? 'disabled' : '' }}"
                                                {{ ($isReportedToCompany) ? 'style=pointer-events:none;' : '' }}>{{ __('messages.company.report_to_company') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endrole
                    </div>
                </div>
            </div>
        </section>
        <!-- end hero section -->

        <!-- start about-comapany section -->
        <section class="about-company-section py-60">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="about-company-left">
                            <h5 class="fs-18 text-secondary">@lang('web.web_company.about_company')</h5>
                            <div class="job-description mb-5 pb-lg-2">
                                {!! nl2br($companyDetail->details) !!}
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-lg-4">
                        <div class="col-12">
                            <div class="col-12 mb-40">
                                <div class="job-card card py-30">
                                    <div class="row d-flex justify-content-lg-between">
                                        <div class="col-5 mt-3">
                                            <i class="fa-solid fa-cake-candles text-primary fs-4"></i>
                                            <p class="details-page-card-text mb-0" >
                                                @lang('web.web_jobs.founded_in')</p>
                                            <p class="text-secondary fs-14">
                                                {{!empty($companyDetail->established_in)? $companyDetail->established_in : __('messages.common.n/a')}}
                                            </p>
                                        </div>
                                        <div class=" col-5 mt-3">
                                            <i class="fa-regular fa-map text-primary fs-4"></i>
                                            <p class="details-page-card-text mb-0">
                                                @lang('web.web_company.ownership')
                                            </p>
                                            <p class="text-secondary fs-14">
                                                {{!empty($companyDetail->ownerShipType->name)? $companyDetail->ownerShipType->name : __('messages.common.n/a')}}
                                            </p>
                                        </div>
                                        <div class=" col-5 mt-3">
                                            <i class="fa-solid fa-users text-primary fs-4"></i>
                                            <p class="details-page-card-text mb-0" >
                                                @lang('web.web_company.company_size')
                                            </p>
                                            <p class="text-secondary fs-14">
                                                {{!empty($companyDetail->companySize->size)? $companyDetail->companySize->size : __('messages.common.n/a')}}
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="col-12 mb-40">
                                <div class="job-card card py-30">
                                    <div class="row d-flex justify-content-lg-between">
                                        @if($companyDetail->user->phone)
                                            <div class="col-10 m-3 d-flex align-items-center">
                                                <i class="fa-solid fa-phone text-primary fs-4"></i>
                                                <div class="mx-3">
                                                    <p class="details-page-card-text mb-0">
                                                        @lang('web.web_jobs.phone')
                                                    </p>
                                                    <p class="text-secondary fs-14 mb-0">
                                                        {{$companyDetail->user->phone}}</p>
                                                </div>
                                            </div>
                                            <hr>
                                        @endif
                                        <div class="col-10 m-3 d-flex align-items-center">
                                            <i class="fa-solid fa-location-dot text-primary fs-4"></i>
                                            <div class="mx-3">
                                                <p class="details-page-card-text mb-0">
                                                    @lang('web.common.location')
                                                </p>
                                                <p class="text-secondary fs-14 mb-0">
                                                    {{ (isset($companyDetail->location)) ? html_entity_decode(Str::limit($companyDetail->location,12,'...')) : __('messages.common.n/a') }} {{ (isset($companyDetail->location2)) ? ','.html_entity_decode(Str::limit($companyDetail->location2,12,'...')) : '' }}</p>
                                            </div>
                                        </div>
                                        <hr>
                                        @isset($companyDetail->website)
                                            <div class="col-10 m-3 d-flex align-items-center">
                                                <i class="fa-solid fa-globe text-primary fs-4"></i>
                                                <div class="mx-3">
                                                    <p class="details-page-card-text mb-0">
                                                        @lang('web.common.location')
                                                    </p>
                                                    <p class="text-secondary fs-14 mb-0">
                                                        <a href="{{ (isset($companyDetail->website))
                                        ?
                                            (!str_contains($companyDetail->website,'https://')
                                            ? 'https://'.$companyDetail->website
                                            : $companyDetail->website)
                                        : 'javascript:void(0)' }}"
                                                           target="_blank">{{ (isset($companyDetail->website)) ? preg_replace("(^https?://www.)", "", $companyDetail->website) : 'N/A' }}</a>
                                                    </p>
                                                </div>
                                            </div>
                                            <hr>
                                        @endisset
                                            <div class="col-10 m-3 d-flex align-items-center">
                                                <i class="fa-regular fa-envelope text-primary fs-4"></i>
                                                <div class="mx-3">
                                                    <p class="details-page-card-text mb-0">
                                                        @lang('web.common.email')
                                                    </p>
                                                    <p class="text-secondary fs-14 mb-0">
                                                        {{$companyDetail->user->email}}</p>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(isset($companyDetail->user->facebook_url) || isset($companyDetail->user->twitter_url) || isset($companyDetail->user->pinterest_url) || isset($companyDetail->user->google_plus_url) || isset($companyDetail->user->linkedin_url))
                        <div class="col-12">
                            <div class="col-12 mb-40">
                                <div class="job-card card py-30">
                                    <div class="row d-flex justify-content-lg-between">
                                            <p class="fs-18 text-secondary">@lang('web.web_company.social_media')</p>
                                            <div class="mt-3">
                                                @if(isset($companyDetail->user->facebook_url))
                                                    <a href="{{ (isset($companyDetail->user->facebook_url)) ? addLinkHttpUrl($companyDetail->user->facebook_url) : 'javascript:void(0)' }}"
                                                       class="ms-2" target="_blank">
                                                        <i class="fa-brands fa-facebook-f fs-3 mx-2"></i></a>
                                                @endif
                                                @if(isset($companyDetail->user->linkedin_url))
                                                    <a href="{{ (isset($companyDetail->user->linkedin_url)) ? addLinkHttpUrl($companyDetail->user->linkedin_url) : 'javascript:void(0)' }}"
                                                       class="ms-2" target="_blank">
                                                        <i class="fa-brands fa-linkedin-in fs-3 mx-2"></i></a>
                                                @endif
                                                @if(isset($companyDetail->user->twitter_url))
                                                    <a href="{{ (isset($companyDetail->user->twitter_url)) ? addLinkHttpUrl($companyDetail->user->twitter_url) : 'javascript:void(0)' }}"
                                                       class="ms-2" target="_blank">
                                                        <i class="fa-brands fa-twitter fs-3 mx-2"></i></a>
                                                @endif
                                                @if(isset($companyDetail->user->google_plus_url))
                                                    <a href="{{ (isset($companyDetail->user->google_plus_url)) ? addLinkHttpUrl($companyDetail->user->google_plus_url) : 'javascript:void(0)' }}"
                                                       class="ms-2" target="_blank">
                                                        <i class="fa-brands fa-google-plus-g fs-3 mx-2"></i></a>
                                                @endif
                                                @if(isset($companyDetail->user->pinterest_url))
                                                    <a href="{{ (isset($companyDetail->user->pinterest_url)) ? addLinkHttpUrl($companyDetail->user->pinterest_url) : 'javascript:void(0)' }}"
                                                       class="ms-2" target="_blank">
                                                        <i class="fa-brands fa-pinterest-p fs-3 mx-2"></i></a>
                                                @endif
                                            </div>
                                      
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="our-latest-jobs">
                        <h5 class="fs-18 text-secondary mb-40">
                            {{ ($jobDetails->count() > 0 ) ? __('web.company_details.our_latest_jobs')  : __('web.home_menu.latest_job_not_available') }}
                        </h5>
                        <div class="row">
                            @foreach($jobDetails as $job)
                                <div class="col-12 px-xl-3 mb-20">
                                    <div class="card py-30 border-left-color ">
                                        <div class="row position-relative">
                                            <div class="col-xl-1 col-md-2 col-3 mb-md-0 mb-3">
                                                <img src="{{$job->company->company_url}}" class="card-img" alt="">
                                            </div>
                                            <div class="col-xl-10 col-md-9 col-sm-8 col-12">
                                                <div class="card-body p-0 ps-xl-3">
                                                    <a href="{{route('front.job.details',$job['job_id']) }}"
                                                       class="text-secondary primary-link-hover">
                                                        <h5 class="card-title fs-18 mb-0 d-inline-block">
                                                            {{ html_entity_decode(Str::limit($job['job_title'], 50)) }}

                                                        </h5>
                                                    </a>
                                                    @if(isset($job->jobShift->shift))
                                                        <span class="text text-primary fs-12 mb-0 me-3">
                                {{$job->jobShift->shift}}
                                </span>
                                                    @endif

                                                    <div class="col-xl-12">
                                                        <div class="card-desc d-flex flex-wrap mt-2 ">

                                                            <div class="desc d-flex me-4">
                                                                <i class="fa-solid fa-location-dot text-gray me-3 fs-18"></i>
                                                                <p class="fs-14 text-gray mb-2">
                                                                    {{ (!empty($job->full_location)) ? $job->full_location : 'Location Info. not available.'}}</p>
                                                            </div>
                                                            <div class="desc d-flex">
                                        <span class="text-gray">
                                            {{$job->currency->currency_icon}}&nbsp</span>
                                                                <p class="fs-14 text-gray mb-2">
                                                                    {{ $job->salary_from}} - {{$job->salary_to}}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($job->activeFeatured)
                                                <div class="bookmark text-end position-absolute">
                                                    <i class="text-primary fa-solid fa-bookmark"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @if(($jobDetails->count() > 0 ))
                                <div class="row justify-content-center">
                                    <div class="col-8 text-center">
                                        <a href="{{ route('front.search.jobs',array('company'=> $companyDetail->id)) }}"
                                           class="btn btn-primary mb-40 mt-lg-4">
                                            @lang('web.common.show_all')</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @role('Candidate')
        @include('front_web.company.report_to_company_modal')
        @endrole
        <!-- end about-comapany section -->
        {{ Form::hidden('isCompanyAddedToFavourite', $isCompanyAddedToFavourite, ['id' => 'isCompanyAddedToFavourite']) }}
        {{ Form::hidden('followText', __('web.company_details.follow'), ['id' => 'followText']) }}
        {{ Form::hidden('unfollowText', __('web.company_details.unfollow'), ['id' => 'unfollowText']) }}
    </div>
@endsection
{{--@section('page_scripts')--}}
{{--    <script>--}}
{{--let addCompanyFavouriteUrl = "{{ route('save.favourite.company') }}"--}}
{{--let isCompanyAddedToFavourite = "{{ $isCompanyAddedToFavourite }}"--}}
{{--let reportToCompanyUrl = "{{ route('report.to.company') }}"--}}
{{--let followText = "{{ __('web.company_details.follow') }}"--}}
{{--let unfollowText = "{{ __('web.company_details.unfollow') }}"--}}
{{--    </script>--}}
{{--@endsection--}}
