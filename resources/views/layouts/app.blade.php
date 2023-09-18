<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head>
    <base href="../">
    <title>@yield('title') | {{ getAppName() }}</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ getSettingValue('favicon') }}"/>
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"/>
    <!--end::Fonts-->

    {{--    <link rel="stylesheet" type="text/css" href="{{ mix('css/third_party.css') }}">--}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/third-party.css') }}">
    @if(getLoggedInUser()->theme_mode)
        {{--        <link rel="stylesheet" href="{{ asset('assets/css/table-dark.css') }}">--}}
        {{--        <link rel="stylesheet" href="{{ asset('backend/style.dark.bundle.css') }}">--}}
        {{--        <link rel="stylesheet" type="text/css" href="{{ mix('assets/css/dark-main.css') }}">--}}
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom-dark.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.dark.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins.dark.css') }}">
    @else
        {{--        <link rel="stylesheet" href="{{ asset('assets/css/livewire-table.css') }}">--}}
        {{--        <link rel="stylesheet" href="{{ asset('assets/css/style.bundle.css')}}"/>--}}
        {{--        <link rel="stylesheet" type="text/css" href="{{ mix('assets/css/main.css') }}">--}}

        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins.css') }}">
    @endif
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}">
    @livewireStyles
    @routes
{{--    @livewireScripts--}}
    <script src="{{ asset('vendor/livewire/livewire.js') }}"></script>
    @include('livewire.livewire-turbo')

    <script src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js"
            data-turbolinks-eval="false" data-turbo-eval="false">
    </script>
    {{--    <script src="{{ mix('js/third_party.js') }}"></script>--}}
    <script src="{{ mix('js/third-party.js') }}"></script>

    {{--    <script src="{{ asset('assets/js/third-party.js') }}"></script>--}}
    <script src="{{ mix('js/pages.js') }}"></script>

</head>
<!--end::Head-->
<!--begin::Body-->
<body class="overflow-x-hidden">
<div class="d-flex flex-column flex-root vh-100">
    <div class="d-flex flex-row flex-column-fluid">
        @include('layouts.sidebar')
        <div class="wrapper d-flex flex-column flex-row-fluid">
            <div class='container-fluid d-flex align-items-stretch justify-content-between px-0'>
                @include('layouts.header')
            </div>
            <div class='content d-flex flex-column flex-column-fluid pt-7'>
                @yield('header_toolbar')
                <div class='d-flex flex-wrap flex-column-fluid'>
                    @yield('content')
                </div>
            </div>
            <div class='container-fluid'>
                @include('layouts.footer')
            </div>
        </div>
    </div>
</div>
{{Illuminate\Support\Facades\Log::info(Config::get('app.locale'))}}
{{Illuminate\Support\Facades\Log::info(getLoggedInUser()->language)}}
@include('user_profile.edit_profile_modal')
@include('user_profile.change_password_modal')

<!--begin::Javascript-->
{{Form::hidden('profile-phone-no', old('region_code').old('phone'), ['id' => 'profilePhoneNo'])}}


<script data-turbo-eval="false">
    (function ($) {
        let currentLocale = "{{ Config::get('app.locale') }}";
        Lang.setLocale(currentLocale);
        $.fn.button = function (action) {
            if (action === 'loading' && this.data('loading-text')) {
                this.data('original-text', this.html()).html(this.data('loading-text')).prop('disabled', true);
            }
            if (action === 'reset' && this.data('original-text')) {
                this.html(this.data('original-text')).prop('disabled', false);
            }
        };
    }(jQuery));
    $(document).ready(function () {
        $('.alert').delay(5000).slideUp(300);
    });
    $('[data-dismiss=modal]').on('click', function (e) {
        var $t = $(this),
            target = $t[0].href || $t.data('target') || $t.parents('.modal') || [];

        $(target).modal('hide');
    });
    let utilsScript = "{{asset('assets/js/inttel/js/utils.min.js')}}";
    {{--    let loggedInUserId = "{{ getLoggedInUserId() }}";--}}
    let currentUrlName = "{{ Request::url() }}";
    let readAllNotifications = "{{ url('admin/read-all-notification') }}";
    let readNotification = "{{ url('admin/notification') }}";
    let ajaxCallIsRunning = false;
    let usersRole = '{{ !empty(getLoggedInUser()->roles->first()) ? getLoggedInUser()->roles->first()->name : '' }}';
    let sweetAlertIcon = "{{ asset('images/remove.png') }}"
    let getLoggedInUserLang = '{{getCurrentLanguageCode()}}';
    let defaultCountryCodeValue = "{{ getSettingValue('default_country_code')}}"
</script>
<!--end::Page Custom Javascript-->
<!--end::Javascript-->
</body>
<!--end::Body-->
</html>
