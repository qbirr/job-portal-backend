<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head>
    <base href="../../../">
    <title>@yield('title') | {{ getAppName() }}</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="shortcut icon" href="{{ getSettingValue('favicon') }}" type="image/x-icon">
    <!--begin::Fonts-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--end::Fonts-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/third-party.css') }}">
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{ mix('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ mix('css/plugins.css') }}" rel="stylesheet">
    <link href="{{ mix('assets/css/custom-auth.css') }}" rel="stylesheet">
{{--    <link href="{{ asset('assets/plugins/plugins.bundle.css')}}" rel="stylesheet" type="text/css"/>--}}
{{--    <link href="{{ asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css"/>--}}
<!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->
<!--begin::Body-->
<body>
<!--begin::Main-->
<div class="d-flex flex-column flex-root">
    <div class="d-flex flex-row flex-column-fluid">
        <div class="d-flex flex-column flex-row-fluid">
            <div class="content d-flex flex-column flex-column-fluid pt-7">
                <div class='d-flex flex-wrap flex-column-fluid'>
                    @yield('content')
                </div>
            </div>
            <div class='container-fluid'>
                <footer class="border-top w-100 pt-4 mt-7 text-center">
{{--                    <p class="fs-6 text-gray-600">{{$settings['copy_right_text']}} <a href="{{route('front.home')}}" class="text-decoration-none">--}}
{{--                            {{$settings['application_name']}}</a>--}}
{{--                    </p>--}}
                </footer>
            </div>
        </div>
    </div>
</div>

<script src="{{ mix('js/auth-third-party.js') }}"></script>
<script>
    $(document).ready(function () {
        $('.alert').delay(5000).slideUp(300);
        $('#loginBtn').click(function () {
            $(this).addClass('disabled')
        })
    })
</script>
{{--<script src="{{ asset('assets/js/auto_fill/auto_fill.js')}}"></script>--}}
</body>
<!--end::Body-->
</html>
