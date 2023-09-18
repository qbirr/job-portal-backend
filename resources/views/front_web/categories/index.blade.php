@extends('front_web.layouts.app')
@section('title')
    {{ __('web.job_seekers') }}
@endsection
@section('page_css')
    @if(\Illuminate\Support\Facades\App::getLocale() == 'ar')
        <style>
            .candidate-main ul.pagination {
                direction: rtl;
            }
        </style>
    @endif
    {{--    <link rel="stylesheet" href="{{ asset('front_web/scss/jobs.css') }}">--}}
    {{--    <link rel="stylesheet" href="{{ asset('front_web/scss/companies.css') }}">--}}
@endsection
@section('content')
    <div class="job-seekers-page">
        <section class="hero-section position-relative bg-light py-40">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6  text-center mb-lg-0 mb-md-5 mb-sm-4 ">
                        <div class="hero-content">
                            <h1 class="text-secondary mb-3">
                                @lang('web.post_menu.categories')
                            </h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center mb-0">
                                    <li class="breadcrumb-item "><a href="{{route('front.home')}}" class="fs-18 text-gray">{{ __('web.home') }} </a>
                                    </li>
                                    <li class="breadcrumb-item text-primary fs-18" aria-current="page">@lang('web.post_menu.categories')</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </section>
            @if(count($jobCategories) > 0)
                <section class="popular-job-categories-section py-100">
                    <div class="container">
                        <div class="job-categories-card">
                            <div class="row">
                                @foreach($jobCategories as $jobCategory)
                                    <div class="col-lg-4 col-md-6 px-xl-3 mb-40">
                                        <div class="card py-30">
                                            <div class="row align-items-center">
                                                <div class="col-3">
                                                    <img src="{{$jobCategory->image_url}}" class="card-img" alt="...">
                                                </div>
                                                <div class="col-8">
                                                    <div class="card-body ps-xl-0 ps-lg-3">
                                                        <a href="{{ route('front.search.jobs',array('categories'=> $jobCategory->id)) }}" class="text-secondary primary-link-hover">
                                                            <h5 class="card-title fs-18">{{html_entity_decode($jobCategory->name)}}</h5>
                                                        </a>
                                                        <p class="card-text fs-14 text-gray">
                                                            {{ (($jobCategory->jobs_count) ? $jobCategory->jobs_count : 0) .' '. __('web.open_positions')}}
                                                        </p>
                                                    </div>
                                                </div>
                                                @if($jobCategory->is_featured)
                                                    <div class="col-1 icon position-relative pe-0">
                                                        <i class="text-primary fa-solid fa-bookmark"></i>
                                                    </div>
                                                @endif
                                                @if($jobCategory->jobs_count <= 0)
                                                    <div class="card-desc mt-3">
                                                        <div class="desc d-flex mt-2">
                                                            <p class="jobs-position bg-gray fs-14 mb-0 me-3 text-secondary">
                                                                {{ __('web.no_positions') }} ->
                                                            </p>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="card-desc mt-3">
                                                        <div class="desc  d-flex mt-2">
                                                            <a href="{{ route('front.search.jobs',array('categories'=> $jobCategory->id)) }}" class="jobs-position  fs-14 mb-0 me-3">
                                                                {{ __('web.open_positions') }} ->
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
            @endif
    </div>
@endsection
