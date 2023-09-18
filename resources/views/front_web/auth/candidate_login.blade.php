@extends('front_web.layouts.app')
@section('title')
    {{ __('web.login') }}
@endsection
@section('content')
    <div class="login-page">
        <!-- start hero section -->
        <section class="hero-section position-relative bg-light py-40">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6 text-center mb-lg-0 mb-md-5 mb-sm-4 ">
                        <div class="hero-content">
                            <h1 class=" text-secondary mb-3">
                                {{__('web.register_menu.candidate').' '.__('web.login') }}
                            </h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb  justify-content-center mb-0">
                                    <li class="breadcrumb-item "><a href="{{route('front.home')}}"
                                                                    class="fs-18 text-gray">
                                            @lang('web.home')
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item text-primary fs-18" aria-current="page">@lang('web.login')</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end hero section -->

        <!-- start candidate login section -->
        <section class="py-100">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-8 mx-auto">
                        @include('flash::message')
                        <form method="POST" action="{{ route('front.login') }}" id="candidateForm"
                              class="py-40 px-40 bg-gray">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <div class="form-group row">
                                        <div class="col-sm-6 col-12 mb-3 mb-sm-0">
                                            <a href="{{route('front.candidate.login')}}"
                                               class="btn btn-success d-block">
                                                {{__('web.register_menu.candidate')}} </a>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <a href="{{ route('front.employee.login') }}"
                                               class="btn btn-light-success d-block">
                                                {{__('web.register_menu.employer')}} </a>
                                        </div>
                                    </div>
                                </div>
                                @csrf
                                <div id="candidateValidationErrBox">
                                    @include('layouts.errors')
                                </div>
                                <input type="hidden" name="type" value="1"/>
                                <div class="col-md-12 mb-4">
                                    <div class="form-group">
                                        <label for="" class="fs-16 text-secondary mb-2">{{ __('web.common.email') }}
                                            <span class="text-primary">*</span>
                                        </label>
                                        <input type="email" class="form-control fs-14 text-gray br-10" name="email"
                                               id="email"
                                               value="{{ (Cookie::get('email') !== null) ? Cookie::get('email') : '' }}"
                                               autofocus placeholder="Enter Your Email" required>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <div class="form-group">
                                        <label for="" class="fs-16 text-secondary mb-2">{{ __('web.common.password') }}
                                            <span class="text-danger">*</span></label>
                                        <input type="password" name="password"
                                               class="form-control fs-14 text-gray br-10" id="password"
                                               placeholder="Your Password"
                                               value="{{ (Cookie::get('password') !== null) ? Cookie::get('password') : '' }}"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div class="form-check">
                                        <input type="checkbox" name="remember" class="form-check-input"
                                               id="remember" {{ (Cookie::get('remember') !== null) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            {{ __('web.login_menu.remember_me') }}
                                        </label>
                                    </div>
                                    <a href="{{ route('password.request') }}"
                                       class="text-primary">{{ __('web.login_menu.forget_password') }}</a>
                                </div>
                            </div>
                            <div class="col-12 d-grid my-4">
                                <button type="submit" class="btn btn-secondary" data-turbo="false">{{ __('web.login') }}</button>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">Don't have an account? <a
                                            href="{{ route('employer.register') }}">{{ __('web.sign_up') }}</a></div>
                                <div class="d-grid">
                                    @if(!empty(config('services.facebook.client_id')) && !empty(config('services.facebook.client_secret')) && !empty(config('services.facebook.redirect')) )
                                    <a href="{{ url('/login/facebook?type=1') }}"
                                       class="btn facebook-btn d-flex align-items-center justify-content-center mb-3"><i
                                                class="fa-brands fa-facebook-f fs-5 me-3"></i>{{ __('web.login_menu.login_via_facebook') }}
                                    </a>
                                    @endif
                                    @if(!empty(config('services.google.client_id')) && !empty(config('services.google.client_secret')) && !empty(config('services.google.redirect')) )
                                    <a href="{{ url('/login/google?type=1') }}"
                                       class="btn google-btn d-flex align-items-center justify-content-center mb-3"><i
                                                class="fa-brands fa-google fs-5 me-3"></i>{{ __('web.login_menu.login_via_google') }}
                                    </a>
                                    @endif
                                    @if(!empty(config('services.linkedin.client_id')) && !empty(config('services.linkedin.client_secret')) && !empty(config('services.linkedin.redirect')) )
                                    <a href="{{ url('/login/linkedin?type=1') }}"
                                       class="btn linkedin-btn d-flex align-items-center justify-content-center"><i
                                                class="fa-brands fa-linkedin-in fs-5 me-3"></i>{{ __('web.login_menu.login_via_linkedin') }}
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- end candidate login section -->
    </div>
@endsection

{{--@section('page_scripts')--}}
{{--    <script>--}}
{{--        let registerSaveUrl = "{{ route('front.save.register') }}";--}}
{{--    </script>--}}
{{--    <script src="{{asset('assets/js/auto_fill/auto_fill.js')}}"></script>--}}
{{--@endsection--}}
