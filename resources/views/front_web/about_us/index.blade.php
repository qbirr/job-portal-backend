@extends('front_web.layouts.app')
@section('title')
    {{ __('messages.about_us') }}
@endsection
{{--@section('page_css')--}}
{{--    <link rel="stylesheet" href="{{ asset('front_web/scss/about-us.css') }}">--}}
{{--@endsection--}}
@section('content')
    <div class="About Us-page">
        <!-- start hero section -->
        <section class="hero-section position-relative bg-light py-40">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6  text-center mb-lg-0 mb-md-5 mb-sm-4 ">
                        <div class="hero-content">
                            <h1 class=" text-secondary mb-3">
                                {{ __('web.about_us') }}
                            </h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb  justify-content-center mb-0">
                                    <li class="breadcrumb-item "><a href="{{ route('front.home') }}" class="fs-18 text-gray">{{ __('web.home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item text-primary fs-18" aria-current="page">{{ __('web.about_us') }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end hero section -->

        <!-- start-about-section -->
        <div class="about-section py-60">
            <div class="container">
                <div class="about-infyjob mb-40">
                    <h5 class="fs-18 text-secondary mb-3">{{ __('web.about_us') }}</h5>
                    <p class="fs-16 text-gray">
                        {!! getSettingValue('about_us') !!}
                    </p>
                </div>
            </div>
        </div>
        <!-- end-about-section -->

        <!-- start-how-it-works section -->
        <section class="how-it-works-section bg-gray py-100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="section-heading mx-xxl-0 mx-xl-3 mx-md-2 text-center">
                            <h2 class="text-secondary bg-gray">
                                {{ __('web.about_us_menu.how_it_works') }}?</h2>
                            <div class="text-center text-gray">{{ __('web.web_jobSeeker.job_for_anyone_anywhere') }}</div>
                        </div>
                    </div>
                </div>
                <div class="work-process">
                    <div class="row justify-content-center">
                        <div class="col-xl-10">
                            <div class="row justify-content-center position-relative">
                                <div class="col-lg-4 text-center">
                                    <div class="img bg-white mx-auto d-flex justify-content-center align-items-center mb-lg-4 mb-3">
                                        <img src="{{$settings['about_image_one']}}" >
                                    </div>
                                    <div class="card-body">
                                        <h6 class="fs-18 text-secondary">
                                            {{ __('web.about_us_menu.step_1') }}</h6>
                                        <h5 class="fs-18 text-secondary">
                                            {{$settings['about_title_one']}}</h5>
                                        <p class="fs-14 text-gray">
                                            {{$settings['about_description_one']}}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <div class="img bg-white mx-auto d-flex justify-content-center align-items-center mb-lg-4 mb-3">
                                        <img src="{{$settings['about_image_two']}}" >
                                    </div>
                                    <div class="card-body">
                                        <h6 class="fs-18 text-secondary">
                                            {{ __('web.about_us_menu.step_2') }}</h6>
                                        <h5 class="fs-18 text-secondary">
                                            {{$settings['about_title_two']}}</h5>
                                        <p class="fs-14 text-gray">
                                            {{ $settings['about_description_two'] }}</p>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <div class="img bg-white mx-auto d-flex justify-content-center align-items-center mb-lg-4 mb-3">
                                        <img src="{{$settings['about_image_three']}}" >
                                    </div>
                                    <div class="card-body">
                                        <h6 class="fs-18 text-secondary">
                                            {{ __('web.about_us_menu.step_3') }}</h6>
                                        <h5 class="fs-18 text-secondary">
                                            {{$settings['about_title_three']}}</h5>
                                        <p class="fs-14 text-gray">
                                            {{ $settings['about_description_three'] }}</p>
                                    </div>
                                </div>
                                <div class="arrow1 position-absolute d-lg-block d-none">
                                    <img src="{{asset('front_web/images/arrow-1.png')}}">
                                </div>
                                <div class="arrow2 position-absolute d-lg-block d-none">
                                    <img src="{{asset('front_web/images/arrow-2.png')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end-how-it-works section -->

        <!-- start question-section -->
        <section class="question-section py-100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-8">
                        <div class="section-heading mx-xxl-5 text-center">
                            <h2 class="text-secondary bg-white">
                                {{ __('web.about_us_menu.frequently_asked_questions') }}
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="questions">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            @if(count($faqLists) > 0)
                                <div class="accordion" id="accordionExample">
                                    @foreach($faqLists as $key => $faqList)
                                        <div class="accordion-item br-10">
                                            <h2 class="accordion-header" id="heading-{{$key}}">
                                                <button class="accordion-button collapsed fs-18  p-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{$key}}" aria-expanded="false" aria-controls="collapse-{{$key}}"> {{ html_entity_decode($faqList->title) }}
                                                </button>
                                            </h2>
                                            <div id="collapse-{{$key}}" class="accordion-collapse collapse " aria-labelledby="heading-{{$key}}" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    {!! nl2br( $faqList->description) !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div>
                                    <h5 class="text-center">{{__('web.about_us_menu.faq_not_available')}}.</h5>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end question-section -->
    </div>
@endsection
