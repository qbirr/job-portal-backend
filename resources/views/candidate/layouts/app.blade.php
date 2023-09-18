@php
    $settings = settings();
@endphp
        <!DOCTYPE html>
<html lang="en">
<head>
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
<script src="{{ mix('js/third-party.js') }}"></script>
<script src="{{ mix('js/pages.js') }}"></script>
<body class="overflow-x-hidden">
<div class="d-flex flex-column flex-root">
    <div class="d-flex flex-column flex-column-fluid">
        <div class="header fixed-header">
            @include('candidate.layouts.header')
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
        @include('candidate_profile.edit_profile_modal')
        @include('candidate_profile.change_password_modal')
    </div>
</div>
<script data-turbo-eval="false">
    var hostUrl = 'assets/';
    let getLoggedInUserLang = '{{getCurrentLanguageCode()}}';
    let defaultCountryCodeValue = "{{ getSettingValue('default_country_code')}}"
    Lang.setLocale(getLoggedInUserLang)
</script>
@stack('scripts')
</body>
</html>
