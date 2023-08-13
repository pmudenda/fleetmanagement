@php use Carbon\Carbon; @endphp
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

    <link rel="stylesheet"
          type="text/css"
          href="{{asset('themes/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <link rel="stylesheet" href="{{asset('themes/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    {{--<link rel="stylesheet" href="{{asset('themes/plugins/jqvmap/jqvmap.min.css')}}">--}}
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
    <style>
        .nav-link {
            padding: 0.65rem 1rem;
        }

        #ui-datepicker-div {
            z-index: 9003 !important;
        }
    </style>
    @stack('styles')
</head>
{{--@php dd(config('rights')) @endphp--}}
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
        @yield('content')
    </div>
    <!--Footer-->
    <aside class="control-sidebar control-sidebar-dark"></aside>
    <input type="hidden" id="sessionStatusUrl" name="sessionStatusUrl" value="{{route('session.status')}}"/>

    <x-approval-modal/>
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container position-fixed top-0 end-0 p-3">

            <div id="liveToast" class="toast align-items-center text-bg-primary border-0" role="alert"
                 aria-live="assertive" aria-atomic="true">
                {{--<div class="toast-header">
                    <strong class="me-auto">System Message</strong>
                    <small>now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>--}}
                <div class="toast-body bg-white">
                    Hello, world! This is a toast message.
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modal-auditTrail">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">
                        Document Audit Trail
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form name="documentAuditTrail" action="{{route('document.audit.trail')}}">
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row">
                                    <label for="documentType" class="col-4 form-label">Document Type:</label>
                                    <div class="col-8">
                                        <select class="form-select" id="documentType" name="documentType">
                                            <option></option>
                                            <option value="08">STORE REQUISITION</option>
                                            <option value="09">STORE RESERVATION</option>
                                            <option value="11">PURCHASE PROCESS</option>
                                            <option value="12">PURCHASE REQUISITION</option>
                                            <option value="13">TENDER</option>
                                            <option value="14">PURCHASE ORDER</option>
                                            <option value="15">GOODS RECEIPT</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row">
                                    <label for="documentNumber" class="col-4 form-label">Document No.</label>
                                    <div class="col-8">
                                        <input class="form-control uppercase" name="documentNumber"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row">
                                    <label class="form-label col-4">Document Status</label>
                                    <div class="col-8">
                                        <select class="form-select" id="documentType" name="documentType">
                                            <option value="">--Select--</option>
                                            <option value="userUnit">User Unit</option>
                                            <option value="workshopSection">Section</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row">
                                    <label class="form-label col-4">Responsible No.</label>
                                    <div class="col-8">
                                        <input class="form-control" name="operator"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6"></div>
                        </div>
                        <div class="row mb-2">
                            <label class="form-label col-4">Period</label>
                            <div class="col-8 row pr-0">
                                <div class="col-6">
                                    <label class="form-label">From:</label>
                                    <div class="input-group">
                                        <input class="form-control"
                                               type="date"
                                               max="{{date('Y-m-d', strtotime(Carbon::now()))}}"
                                               name="periodFrom"/>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-6 pr-0">
                                    <label class="form-label">To.</label>
                                    <div class="input-group">
                                        <input class="form-control"
                                               max="{{date('Y-m-d', strtotime(Carbon::now()))}}"
                                               type="date" name="periodTo"/>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button"
                                class="btn btn-sm btn-success"
                                value="applyFilter">
                            <i class="fas fa-hand-grab-o"></i>
                            Get
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>


    <div class="modal" id="modal-followUp">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">
                        Document Follow Up
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="documentFollowUpForm" name="documentFollowUpForm" action="{{route('document.followup')}}">
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row">
                                    <label for="documentType" class="col-4 form-label">Document Type:</label>
                                    <div class="col-8">
                                        <select class="form-select" id="documentType" name="documentType">
                                            <option></option>
                                            <option value="08">STORE REQUISITION</option>
                                            <option value="09">STORE RESERVATION</option>
                                            <option value="11">PURCHASE PROCESS</option>
                                            <option value="12">PURCHASE REQUISITION</option>
                                            <option value="13">TENDER</option>
                                            <option value="14">PURCHASE ORDER</option>
                                            <option value="15">GOODS RECEIPT</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row">
                                    <label for="documentNumber" class="col-4 form-label">Document No.</label>
                                    <div class="col-8">
                                        <input class="form-control uppercase" name="documentNumber"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label class="form-label col-4">Period</label>
                            <div class="col-8 row pr-0">
                                <div class="col-6">
                                    <label class="form-label">From.</label>
                                    <div class="input-group">
                                        <input class="form-control periodFrom"
                                               type="date"
                                               max="{{date('Y-m-d', strtotime(Carbon::now()))}}"
                                               name="periodFrom"/>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-6 pr-0">
                                    <label class="form-label">To.</label>
                                    <div class="input-group">
                                        <input class="form-control periodTo"
                                               max="{{date('Y-m-d', strtotime(Carbon::now()))}}"
                                               type="date" name="periodTo"/>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button"
                                class="btn btn-sm btn-success"
                                value="documentFollowUpFilter">
                            <i class="fas fa-hand-grab-o"></i>
                            Get
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>


    <div class="modal" id="modal-taskFollowUp">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">
                        Document Task Tracking
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <table id="filterProperty" class="table">
                        <tbody>
                        <tr>
                            <td>
                                <select class="form-select" name="property">
                                    <option value="" disabled>--Select--</option>
                                    <option value="userUnit">User Unit</option>
                                    <option value="workshopSection">Section</option>
                                    <option value="workshop">Workshop</option>
                                    <option value="dateIn">Date In</option>
                                    <option value="dateOut">Date Out</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-select" name="operator">
                                    <option value="=">Is</option>
                                    <option value="<>">Is not</option>
                                    <option value=">">Is After</option>
                                    <option value="<">Is Before</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-select" name="filterValue">
                                </select>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <button type="button"
                            data-table-id="filterProperty"
                            class="btn btn-sm btn-primary add pull-left"
                            value="addRow">
                        <i class="fa fa-plus"></i> Add Property
                    </button>
                    <div class="clearfix"></div>
                </div>

                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button"
                            class="btn btn-sm btn-success"
                            value="applyFilter"> Apply Filter
                    </button>
                </div>

            </div>

        </div>
    </div>
</div>
<input type="hidden" name="gatePassUrl" id="gatePassUrl" value="{{URL::signedRoute("gate.pass")}}"/>

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
<script src="{{asset('themes/plugins/jquery/jquery.min.js')}}"></script>
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

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    (function (tmsApp, $) {
        $(document).on('keypress', '.number_input', function (event) {
            // tmsApp.numberOnly(event);
        });

        $(document).on('keyup', '.uppercase', function (event) {
            this.value = this.value.toUpperCase();
        });

        $(document)
            .on("click", 'button[value="documentFollowUpFilter"]', function (event) {
                console.log("Here");
                const form = document.querySelector('form[name="documentFollowUpForm"]');
                const formData = new FormData(form);
                let postData = {};
                for (const keyValuePair of formData.entries()) {
                    postData[keyValuePair[0]] = keyValuePair[1];
                }

                const settings = {
                    url: form.action,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    dataType: 'json',
                    data: postData
                };

                $.post(settings).done(function (response) {
                    if (response['state'] === 'success') {
                        showDocumentFollowUpResults(response);
                    } else {
                        toastr.error('Request Could Not Be Completed')
                        Swal.fire('Hi');
                    }
                }).fail(function (xhr) {
                    tmsApp.showErrorMessages(xhr, 'Document Follow-up')
                });
            });


        $(document)
            .on("click", 'button[value="applyFilter"]', function (event) {
                console.log("Here");
                const form = document.querySelector('form[name="documentFollowUpForm"]');
                const formData = new FormData(form);
                let postData = {};
                for (const keyValuePair of formData.entries()) {
                    postData[keyValuePair[0]] = keyValuePair[1];
                }

                const settings = {
                    url: form.action,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    dataType: 'json',
                    data: postData
                };

                $.post(settings).done(function (response) {
                    if (response['state'] === 'success') {
                        showDocumentFollowUpResults(response);
                    } else {
                        toastr.error('Request Could Not Be Completed')
                        Swal.fire('Hi');
                    }
                }).fail(function (xhr) {
                    tmsApp.showErrorMessages(xhr, 'Document Follow-up')
                });
            });

        /* $('.periodTo').datepicker({
             // maxDate: new Date(),
             dateFormat: 'dd/mm/yy',
         });

         $('.periodFrom').datepicker({
             // minDate: new Date(),
             dateFormat: 'dd/mm/yy',
         });*/

        function showDocumentFollowUpResults() {
            toastr.success('I Managed');
        }
    }(window.tmsApp || {}, jQuery));
</script>
@stack('scripts')
</body>
</html>
