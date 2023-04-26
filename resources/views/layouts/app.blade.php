<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Fleet Master</title>

    {{--    <link rel="stylesheet"--}}
    {{--          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">--}}

    <link rel="stylesheet" href="{{asset('themes/plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('themes/ionicons/2.0.1/css/ionicons.min.css')}}">
    <link rel="stylesheet"
          href="{{asset('themes/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <link rel="stylesheet" href="{{asset('themes/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    {{--<link rel="stylesheet" href="{{asset('themes/plugins/jqvmap/jqvmap.min.css')}}">--}}
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/bootstrap-5.2.3/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('themes/dist/css/adminlte.min2167.css')}}?v=3.2.0">
    <link rel="stylesheet" href="{{asset('themes/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <link rel="stylesheet" href="{{asset('themes/plugins/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('themes/plugins/summernote/summernote-bs4.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">

{{--
    <link rel="stylesheet" href="{{ asset('assets/css/fullpage_loader.css') }}" rel="stylesheet" type="text/css">
--}}
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <x-page-preloader/>

    @include('layouts.menus.top_bar')

    @include('layouts.menus.side_bar')

    <div class="content-wrapper">
        @yield('content')
    </div>
    <!--Footer-->
    <aside class="control-sidebar control-sidebar-dark"></aside>
</div>
<audio preload="auto" id="sound-email" volume=0.1>
    <source src="{{asset('assets/sounds/email.mp3')}}"/>
</audio>

<audio preload="auto" id="sound-submit" volume=0.1>
    <source src="{{asset('assets/sounds/submit.mp3')}}"/>
</audio>

<audio preload="auto" id="sound-cancel" volume=0.1>
    <source src="{{asset('assets/sounds/cancel.mp3')}}"/>
</audio>

<audio preload="auto" id="sound-delete" volume=0.05>
    <source src="{{asset('assets/sounds/delete.mp3')}}"/>
</audio>

<audio preload="auto" id="sound-click" volume=0.05>
    <source src="{{asset('assets/sounds/click.mp3')}}"/>
</audio>

<audio preload="auto" id="sound-error" volume=0.1>
    <source src="{{asset('assets/sounds/error.mp3')}}"/>
</audio>

<audio preload="auto" id="sound-alert" volume=0.2>
    <source src="{{asset('assets/sounds/alert.mp3')}}"/>
</audio>

<script src="{{asset('themes/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('themes/plugins/jquery-ui/jquery-ui.min.js')}}"></script>

<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>

<script src="{{asset('themes/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
{{--<script src="{{asset('libs/bootstrap-5.2.3/js/bootstrap.bundle.js')}}"></script>--}}
{{--<script src="{{ asset('assets/plugins/form-masking/jquery.inputmask.js') }}"></script>
<script src="{{ asset('assets/plugins/form-masking/autoNumeric.js') }}"></script>
<script src="{{ asset('assets/plugins/form-masking/form-mask.js') }}"></script>--}}
<script src="{{asset('themes/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<script src="{{asset('themes/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('themes/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('themes/plugins/summernote/summernote-bs4.min.js')}}"></script>
<script src="{{asset('themes/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<script src="{{asset('themes/dist/js/adminlte2167.js')}}?v=3.2.0"></script>
<script>
    // window.addEventListener("load", function () {
    //     document.querySelector('.page-loader-wrapper').classList.add('d-none');
    // });

    window._version_number = "{{\Illuminate\Support\Str::uuid()}}";
    window.app = true;
    window.dev_server = 0;
    let tmsApp;
    if (!tmsApp) tmsApp = {};

    tmsApp.boot = JSON.parse('{}');
    tmsApp.messages = window.tmsApp?.boot['messages'];
    tmsApp.csrf_token = document.querySelector('meta[name="csrf-token"]').content;
    tmsApp.play_alert = function playSound(soundSelector) {
        document.querySelector('#' + soundSelector).play()
    }
</script>
<script src="{{ asset('assets/plugins/vue/vue.js')}}"></script>
<script src="{{ asset('assets/plugins/vue-select/vue-select.js')}}"></script>
<script src="{{ asset('assets/plugins/vue-select2/js/vue-select.js')}}"></script>
<script src="{{ asset('assets/js/util.functions.js') }}"></script>
<script src="{{ asset('assets/js/accounting.min.js') }}"></script>
@stack('scripts')
</body>
</html>
