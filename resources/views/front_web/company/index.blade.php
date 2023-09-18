@extends('front_web.layouts.app')
@section('title')
    {{ __('messages.company.company_listing') }}
@endsection
@section('page_css')
    @if(\Illuminate\Support\Facades\App::getLocale() == 'ar')
        <style>
            .job-post-wrapper ul.pagination {
                direction: rtl;
            }
        </style>
    @endif
@endsection
@section('content')
    <div class="companies-page">
        <section class="hero-section position-relative bg-light py-40">
            <div class="container">
                <div class="row align-items-center justify-content-center ">
                    <div class="col-lg-6 text-center mb-lg-2 mb-md-0 mb-sm-4 mb-3 pb-md-5 pb-4">
                        <div class="hero-content">
                            <h1 class=" text-secondary mb-md-3">
                                @lang('messages.companies')
                            </h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center  mb-lg-0">
                                    <li class="breadcrumb-item ">
                                        <a href="{{ route('front.home') }}" class="fs-18 text-gray">@lang('web.home')</a>
                                    </li>
                                    <li class="breadcrumb-item text-primary fs-18" aria-current="page">@lang('messages.companies')</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @livewire('company-search', ['isFeatured' => Request::get('is_featured')])
    </div>
@endsection
