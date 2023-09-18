@extends('front_web.layouts.app')
@section('title')
    {{ __('messages.candidate.candidate_details') }}
@endsection
{{--@section('page_css')--}}
{{--    <link href="{{asset('front_web/scss/candidate-details.css')}}" rel="stylesheet" type="text/css">--}}
{{--@endsection--}}
@section('content')
    <section class="hero-section position-relative bg-light py-40">
        <div class="container">
            <div class="row align-items-center justify-content-center ">
                <div class="col-12">
                    <div class="row align-items-lg-center mb-3">
                        <div class="col-lg-1 col-sm-2 col-3">
                            <div class="candidate-profile-img mt-md-0 mt-3">
                                <img
                                        src="{{ (!empty($candidateDetails->user->avatar)) ? $candidateDetails->user->avatar : asset('assets/img/infyom-logo.png') }}"
                                        alt="candidate profile">
                            </div>
                        </div>
                        <div class="col-sm-10 col-9">
                            <div class="hero-content ps-xl-0 ps-3">
                                <h4 class="text-secondary mb-0">
                                    {{ html_entity_decode($candidateDetails->user->full_name) }}
                                </h4>
                                <div class="hero-desc d-flex align-items-center flex-wrap">
                                    <div class="d-flex align-items-center me-4 pe-2">
                                        <i class="fa-solid fa-briefcase text-gray me-3 fs-18"></i>
                                        <p class="fs-14 text-gray mb-0">
                                            {{!empty($candidateDetails->functionalArea->name)? $candidateDetails->functionalArea->name : __('messages.common.n/a')}}</p>
                                    </div>

                                    @if(!empty($candidateDetails->user->country_name))
                                        <div class="desc d-flex align-items-center me-lg-4 me-2 pe-2">
                                            <i class="fa-solid fa-location-dot text-gray me-3 fs-18"></i>
                                            <p class="fs-14 text-gray mb-0">
                                                    <span>{{$candidateDetails->user->country_name}}
                                                        @if(!empty($candidateDetails->user->state_name))
                                                            ,{{$candidateDetails->user->state_name }}
                                                        @endif
                                                        @if(!empty($candidateDetails->user->city_name))
                                                            ,{{$candidateDetails->user->city_name}}
                                                @endif
                                            </p>
                                        </div>
                                    @endif
                                    <div class="desc d-flex align-items-center me-lg-4 me-2 pe-2">
                                        <i class="fa-solid fa-envelope text-gray me-3 fs-18"></i>
                                        <p class="fs-14 text-gray mb-0">
                                            {{$candidateDetails->user->email}}
                                        </p>
                                    </div>
                                    @if($candidateDetails->user->dob)
                                        <div class="desc d-flex align-items-center me-lg-4 me-2 pe-2">
                                            <i class="fa-solid fa-location-dot text-gray me-3 fs-18"></i>
                                            <p class="fs-14 text-gray mb-0">
                                                {{ \Carbon\Carbon::parse($candidateDetails->user->dob)->translatedFormat('jS M, Y') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center flex-wrap">
                            @auth
                                @role('Employer')
                                <ul class="post-tags mt-3 ps-0">
                                    @if($isReportedToCandidate)
                                        <button class="btn btn-outline-danger reportToCompany reportToCandidate" disabled
                                        >{{ __('messages.candidate.already_reported') }}</button>
                                    @else
                                        <button type="button" class="btn btn-outline-danger reportToCompany reportToCandidate"
                                                data-bs-toggle="modal"
                                                data-bs-target="#reportToCandidateModal">
                                            {{ __('messages.candidate.reporte_to_candidate') }}
                                        </button>
                                    @endif
                                </ul>
                                @endrole
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="about-company-section py-60">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="mb-5">
                        <h5 class="fs-4 text-secondary mb-4">{{__('messages.candidate_profile.education')}}</h5>
                        <div class="job-description">
                            @forelse($candidateEducations as $candidateEducation)
                                <div class="job-description-block pb-3">
                                    <span class="name">{{ucfirst($candidateEducation->degreeLevel->name[0])}}</span>
                                    <div class="job-description-right">
                                        <h5 class="fs-18 text-gary mb-0">{{$candidateEducation->degreeLevel->name}}</h5>
                                        <span class="text-primary"> {{ucfirst($candidateEducation->institute)}}</span>
                                        <span class="badge bg-secondary">{{ $candidateEducation->year }}</span>
                                    </div>
                                </div>
                            @empty
                                <h4 class="text-center">{{ __('messages.candidate.education_not_found') }}</h4>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <h5 class="fs-4 text-secondary mb-4">{{__('messages.candidate_profile.work_experience')}}</h5>
                        <div class="job-description">
                            @forelse($candidateExperiences as $candidateExperience)
                                <div class="job-description-block pb-3">
                                    <span class="name">{{ucfirst($candidateExperience->experience_title[0])}}</span>
                                    <div class="job-description-right">
                                        <div class="info-box">
                                            <h5 class="fs-18 text-gary mb-0">{{$candidateExperience->experience_title}}</h5>
                                            <span class="text-primary">{{ucfirst($candidateExperience->company)}}</span>
                                            <span class="badge bg-secondary"> {{ \Carbon\Carbon::parse($candidateExperience->start_date)->format('Y') }} - {{($candidateExperience->currently_working) ? 'present' : \Carbon\Carbon::parse($candidateExperience->end_date)->format('Y') }}</span>
                                        </div>
                                    </div>
                                    @if(!empty($candidateExperience->description))
                                        <div class="mt-2">{!! Str::limit(nl2br($candidateExperience->description),300,'...') !!}</div>
                                    @endif
                                </div>
                            @empty
                                <h4 class="text-center">{{ __('messages.candidate.experience_not_found') }}</h4>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('front_web.candidate.candidate_detail_sidebar')
                </div>
            </div>
        </div>
    </section>
    <!-- end hero section -->
    @role('Employer')
    @include('front_web.candidate.report_to_candidate_modal')
    @endrole
@endsection
{{--@section('scripts')--}}
{{--    <script>--}}
{{--        let reportToCandidateUrl = "{{ route('report.to.candidate') }}"--}}
{{--    </script>--}}
{{--@endsection--}}

