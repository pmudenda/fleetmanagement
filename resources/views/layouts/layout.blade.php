<html lang="en" data-bs-theme="light">

<head>
    <title>FleetMaster::Home</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:locale" content="en_US">
    <link rel="canonical" href="">
    <link rel="shortcut icon" href="{{ asset('assets/dist/img/icons/logo.png') }}" type="image/x-icon">

    <!--begin::Fonts(mandatory for all pages)-->
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700">-->
    <!--end::Fonts-->

    <link href="{{ asset('assets/plugins/fullcalendar/main.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css">

    <link type="text/css" rel="stylesheet" href="{{asset('assets/frappe/css/desk.bundle.css')}}">
    <link type="text/css" rel="stylesheet" href="{{asset('assets/frappe/css/report.bundle.css')}}">
    <link type="text/css" rel="stylesheet" href="{{asset('assets/frappe/css/erpnext.bundle.css')}}">


    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/plugins/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/fullpage_loader.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/plugins/vue-select/vue-select.css') }}" rel="stylesheet" type="text/css">

    @stack('styles')
    <style>
        .btn.btn-primary {
            color: var(--bs-primary-inverse);
            border-color: var(--bs-primary);
            background-color: var(--bs-zesco-primary) !important;
        }

        .btn-check:checked + .btn.btn-primary, .btn-check:active + .btn.btn-primary, .btn.btn-primary:focus:not(.btn-active), .btn.btn-primary:hover:not(.btn-active), .btn.btn-primary:active:not(.btn-active), .btn.btn-primary.active, .btn.btn-primary.show, .show > .btn.btn-primary {
            color: var(--bs-primary-inverse);
            border-color: var(--bs-primary-active);
            background-color: var(--bs-zesco-primary) !important;
        }
    </style>
</head>

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
      data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
      data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
      data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default" style="">

<div class="page-loader-wrapper">
    <div class="loader">
        <div class="preloader">
            <div class="spinner-layer pl-green">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
        <p>Please wait...</p>
    </div>
</div>

<script>
    let defaultThemeMode = "light";
    let themeMode;

    if (document.documentElement) {
        if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
            themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
        } else {
            if (localStorage.getItem("data-bs-theme") !== null) {
                themeMode = localStorage.getItem("data-bs-theme");
            } else {
                themeMode = defaultThemeMode;
            }
        }

        if (themeMode === "system") {
            themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
        }

        document.documentElement.setAttribute("data-bs-theme", themeMode);
    }
</script>

<div class="d-flex flex-column flex-root app-root" id="kt_app_root">

    <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">

        @include('layouts.partials.app_nav')


        <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">

            @include('layouts.partials.app_side')


            <div class="app-main flex-column flex-row-fluid" id="kt_app_main">

                <div class="d-flex flex-column flex-column-fluid">

                    @include('layouts.partials.app_toolbar')


                    <div id="kt_app_content" class="app-content  flex-column-fluid ">

                        <div id="kt_app_content_container" class="app-container  container-fluid ">
                            @yield('content')
                        </div>

                    </div>

                </div>

                @include('layouts.partials.app_footer')

            </div>

        </div>

    </div>

</div>

@include('layouts.widgets.scroll_top')

@include('layouts.partials.modals')

@include('layouts.components.modal_fullscreen')

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


<script type="text/javascript">
    window.addEventListener("load", function () {
        document.querySelector('.page-loader-wrapper').classList.add('d-none');
    });


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

<!--mandatory for all pages-->
<script src="{{ asset('assets/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/plugins/form-masking/jquery.inputmask.js') }}"></script>
<script src="{{ asset('assets/plugins/form-masking/autoNumeric.js') }}"></script>
<script src="{{ asset('assets/plugins/form-masking/form-mask.js') }}"></script>
<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
<script src="{{ asset('assets/js/util.functions.js') }}"></script>
<script src="{{ asset('assets/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('assets/plugins/vue/vue.js')}}"></script>
<script src="{{ asset('assets/plugins/vue-select/vue-select.js')}}"></script>
<script src="{{ asset('assets/plugins/vue-select2/js/vue-select.js')}}"></script>
<!-- page level javascript-->
<script>
    $(document).ready(function () {
        $(document).on('keypress', '.number_input', function (event) {
            tmsApp.tmsUtility.numberOnly(event);
        })
    });
</script>
@stack('scripts')

@include('layouts.widgets.date_range_picker')
</body>

</html>
