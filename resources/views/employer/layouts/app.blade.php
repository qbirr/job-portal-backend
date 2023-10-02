<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head>
    @include('google_analytics')
    <base href="../">
    <title>@yield('title') | {{ getAppName() }}</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ getSettingValue('favicon') }}"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/third-party.css') }}">
    @if(getLoggedInUser()->theme_mode)
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom-dark.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.dark.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins.dark.css') }}">
    @else
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins.css') }}">

        @if(request()->cookie('theme'))
            <link id="theme" href="{{asset('front_web/scss/bootstrap-' . request()->cookie('theme') . '.css')}}" rel="stylesheet"
                  type="text/css">
        @else
{{--            <link id="theme" href="{{asset('front_web/scss/bootstrap.css')}}" rel="stylesheet" type="text/css">--}}
        @endif
    @endif
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}">
    @livewireStyles
    @routes
    {{--    @livewireScripts--}}
    <script src="{{ asset('vendor/livewire/livewire.js') }}"></script>
    @include('livewire.livewire-turbo')
</head>

<script src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js"
        data-turbolinks-eval="false" data-turbo-eval="false">
</script>
<script src="https://js.stripe.com/v3/"></script>
<script src="{{ mix('js/third-party.js') }}"></script>
<script src="{{ mix('js/pages.js') }}"></script>
<body class="overflow-x-hidden">
<div class="d-flex flex-column flex-root">
    <div class="d-flex flex-column flex-column-fluid">
        <div class="header fixed-header">
            @include('employer.layouts.header')
        </div>
        <div class="theme-wrapper d-flex flex-column flex-row-fluid">
            <div class='d-flex flex-column flex-row-fluid'>
                <div class="d-flex flex-column flex-column-fluid pt-7">
                    <div class="content flex-column-fluid">
                        <div class="container-fluid container-xxl">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class='container-fluid container-xxl'>
            @include('layouts.footer')
        </div>
        @include('employer_profile.edit_profile_modal')
        @include('employer_profile.change_password_modal')
    </div>
</div>
@livewire('theme-selector')
{{Form::hidden('employerProfileData',true,['id'=>'indexEmployerProfileData'])}}
{{Form::hidden('default-image-url', asset('assets/img/infyom-logo.png'), ['id' => 'defaultImageUrl'])}}
<script data-turbo-eval="false">
    var hostUrl = 'assets/';
    let getLoggedInUserLang = '{{getCurrentLanguageCode()}}';
    let defaultCountryCodeValue = "{{ getSettingValue('default_country_code')}}"
    Lang.setLocale(getLoggedInUserLang);
</script>
<script>
    $(document).ready(function () {
        $('.alert').delay(5000).slideUp(300);
    });
    var stripe = '';
    @if(config('services.stripe.key'))
        stripe = Stripe('{{config('services.stripe.key')}}');
    @endif

    //fix menu overflow under the responsive table
    // hide menu on click... (This is a must because when we open a menu )
    $(document).click(function (event) {
        //hide all our dropdowns
        $('.dropdown-menu[data-parent]').hide();
    });

    $('.table-responsive').on('show.bs.dropdown', function () {
        $('.table-responsive').css("overflow", "unset");
    }).on('hide.bs.dropdown', function () {
        $('.table-responsive').css("overflow", "auto");
    })

    @if(auth()->user()->company->submission_status_id == 3)
        Swal.fire()
    @endif

</script>

@stack('scripts')
</body>
</html>
