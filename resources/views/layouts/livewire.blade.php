@php use Carbon\Carbon;use Illuminate\Support\Facades\Auth; @endphp
    <!DOCTYPE html>
<html lang="en">
<head>
    <title>Fleet Master</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:locale" content="en_US">
    <link rel="canonical" href="">
    <link rel="shortcut icon" href="{{ asset('assets/dist/img/icons/logo.png') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{asset('themes/plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('themes/ionicons/2.0.1/css/ionicons.min.css')}}">

    <link rel="stylesheet" href="{{asset('themes/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/bootstrap-5.2.3/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('themes/dist/css/adminlte.min2167.css')}}?v=3.2.0">
    <link rel="stylesheet" type="text/css"
          href="{{asset('themes/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('themes/plugins/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('themes/plugins/summernote/summernote-bs4.min.css')}}">

    <!--Add Ons-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/frappe/css/desk.bundle.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.bundle.css') }}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/plugins/toastr/toastr.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/plugins/sweetalert2/sweetalert2.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/plugins/vue-select/vue-select.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/custom/datatables/datatables.bundle.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset("assets/plugins/jquery.filer/css/jquery.filer.css")}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('libs/session.timeout/session.timeout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/asyncLoader.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/jquery-ui.css')}}"/>
    <livewire:styles/>

    <style>
        .nav-link {
            padding: 0.65rem 1rem;
        }

        #ui-datepicker-div {
            z-index: 9003 !important;
        }
    </style>
    <link rel="stylesheet" type="text/css"
          href="{{asset('assets/plugins/datatables/datetime/css/dataTables.dateTime.min.css')}}"/>
    <link rel="stylesheet" type="text/css"
          href="{{asset('assets/plugins/datatables/searchbuilder/css/searchBuilder.dataTables.min.css')}}"/>
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
<div class="wrapper">
    <x-page-preloader/>
    <x-async-loader></x-async-loader>
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

    @include('layouts.menus.top_bar')

    @include('layouts.menus.side_bar')

    <div class="content-wrapper">
        {{$slot}}
    </div>
    <!--Footer-->
    <aside class="control-sidebar control-sidebar-dark"></aside>
    <input type="hidden"
           id="sessionStatusUrl"
           name="sessionStatusUrl"
           value="{{route('session.status')}}"/>

    <x-approval-modal/>
    <x-app-modals/>
</div>
<input type="hidden" name="gatePassUrl" id="gatePassUrl" value="{{URL::signedRoute("gate.pass")}}"/>
<input type="hidden" name="currentUser" id="currentUser" value="{{Auth::user()->staff_no}}"/>
@include('modules.vehicleManagement.partial.data_end_point')
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

<script>
    window._version_number = "{{\Illuminate\Support\Str::uuid()}}";
    window.app = true;
    window.dev_server = 0;
    let tmsApp;
    if (!tmsApp) tmsApp = {};
    tmsApp.boot = {'messages': {}};
    tmsApp.messages = tmsApp?.boot['messages'];
    tmsApp.csrf_token = document.querySelector('meta[name="csrf-token"]').content;
</script>

<script src="{{asset('assets/plugins/jquery/jquery-3.6.3.js')}}"></script>
<script src="{{asset('themes/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('libs/echarts@5.4.2/dist/echarts.min.js')}}"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="{{asset('themes/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('libs/bootstrap-5.2.3/js/bootstrap.bundle.js')}}"></script>
<script src="{{asset('assets/plugins/toastr/toastr.min.js')}}"></script>
<script>
    $(document).ready(function () {
        toastr.options = {
            "preventDuplicates": true,
            "preventOpenDuplicates": true
        };

        const toastTrigger = document.getElementById('liveToastBtn')
        const toastLiveExample = document.getElementById('liveToast')
        if (toastTrigger) {
            toastTrigger.addEventListener('click', () => {
            });
        }

        tmsApp.toast = new bootstrap.Toast(toastLiveExample)
    })
</script>
<script src="{{asset('assets/plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('assets/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/plugins/jquery.filer/js/jquery.filer.min.js')}}"></script>
<script src="{{asset('libs/jqueryInputmask/3.3.4/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{asset('assets/plugins/form-masking/autoNumeric.js') }}"></script>
<script src="{{asset('themes/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<script src="{{asset('themes/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('themes/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('themes/plugins/summernote/summernote-bs4.min.js')}}"></script>
<script src="{{asset('themes/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<script src="{{asset('assets/js/accounting.min.js') }}"></script>
<script src="{{asset('assets/plugins/select2/js/select2.js')}}"></script>
<script src="{{asset('assets/plugins/vue/vue.js')}}"></script>
<script src="{{asset('assets/plugins/vue-select/vue-select.js')}}"></script>
<script src="{{asset('assets/plugins/vue-select2/js/vue-select.js')}}"></script>
<script src="{{asset('themes/dist/js/adminlte2167.js')}}?v=3.2.0"></script>
<script src="{{asset('assets/js/system/core.js')}}"></script>
<script src="{{asset('assets/js/system/workflow_approvals.js').'?v='.Carbon::now()->format('his')}}"></script>
<script src="{{asset('assets/js/global/async.loader.js').'?v='.Carbon::now()->format('his')}}"></script>
<script src="{{asset('assets/js/global/page.loader.js').'?v='.Carbon::now()->format('his')}}"></script>
<script src="{{asset('assets/js/global/system_alerts.js').'?v='.Carbon::now()->format('his')}}"></script>
<script src="{{asset('assets/js/global/custom_filer.js').'?v='.Carbon::now()->format('his')}}"></script>
<script src="{{ asset('libs/session.timeout/session.timeout.js').'?v='.Carbon::now()->format('his')}}"></script>
<script src="{{ asset('libs/qrcode/qrcode.min.js').'?v='.Carbon::now()->format('his')}}"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<livewire:scripts/>

@include('layouts.partials.dataTableScripts')
<script type="text/javascript">
    function generateBarcode(data) {
        let qrcode = new QRCode(document.getElementById("qrcode"), {
            text: data,
            width: 128,
            height: 128,
            colorDark: "#5868bf",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    }
</script>
<script src="{{asset('assets/js/system/app.js')}}"></script>
@stack('scripts')
</body>
</html>
