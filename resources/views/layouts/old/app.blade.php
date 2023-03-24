<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Transport &amp; Management System">
    <meta name="author" content="ISD">
    <title>TMS :: Home </title>

    <link rel="shortcut icon" href="{{asset('assets/dist/img/icons/logo.png')}}"
          type="image/x-icon">
    <script src="{{asset('assets/webfont.js')}}"></script>
    <script>
        WebFont.load({
            google: {
                families: ['Nunito+Sans:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&display=swap']
            }
        });
    </script>

    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}"
          rel="stylesheet">
    <link href="{{asset('assets/plugins/datatables/responsive.bootstrap4.min.css')}}"
          rel="stylesheet">
    <link href="{{asset('assets/plugins/modals/component.css')}}" rel="stylesheet">

    <link href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}"
          rel="stylesheet">
    <link href="{{asset('assets/dist/css/select.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/icheck/skins/all.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/bootstrap4-toggle/css/bootstrap4-toggle.min.css')}}"
          rel="stylesheet">
    <link href="{{asset('assets/plugins/typehead/typehead.css')}}" rel="stylesheet">

    <link href="{{asset('assets/dist/css/app.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <link href="{{asset('assets/dist/css/custom.css')}}" rel="stylesheet">
    <link href="{{asset('assets/dist/css/create_system_role.css')}}" rel="stylesheet">

    <!--To be loaded on specific pages-->
    <link href="{{asset('application/modules/costManagement/assets/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('application/modules/employeeManagement/assets/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('application/modules/maintenance/assets/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('application/modules/purchase/assets/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('application/modules/refueling/assets/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('application/modules/reports/assets/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('application/modules/configurations/assets/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('application/modules/vehicleManagement/assets/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('application/modules/vehicleRequisition/assets/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/multiSelectedJs/jquery.multiselect.css')}}" rel="stylesheet">
    @stack('styles')
</head>
<body class="fixed">

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

<div class="wrapper">

    @include('layouts.sidebar')

    <div class="content-wrapper">
        <div class="main-content position-relative">
            @include('layouts.page_loader')

            @include('layouts.top_nav')

            <div class="content-header row align-items-center m-0" id="bedcumb">
                <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
                    <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a>
                        </li>
                        <li id="moduleName" class="breadcrumb-item active">
                            Dashboard
                        </li>
                    </ol>
                </nav>
                <div class="col-sm-8 header-title p-0">
                    <div class="media">
                        <div class="header-icon text-success mr-3"><i class="typcn typcn-spiral"></i></div>
                        <div class="media-body">
                            <h1 class="font-weight-bold" id="moduleName1">Dashboard</h1>
                            <small id="controllerName"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="body-content" id="bodycontent">
                @yield('content')
            </div>

        </div>

        @include('layouts.footer')

        <div class="overlay"></div>
    </div>

</div>

{{--<script src="{{asset('assets/dist/js/app.js')}}"></script>--}}
<script src="{{asset('assets/plugins/jquery/jquery-3.6.3.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap/js/bootstrap-5.3.0/bootstrap.bundle.min.js')}}"
        type="text/javascript"></script>
<script src="{{asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/plugins/jquery/jquery-3.6.3.js')}}"></script>
<script src="{{asset('assets/plugins/multiSelectedJs/jquery.multiselect.js')}}"></script>
<script>
    window.baseurl = "/";
</script>
<script src="{{asset('assets/plugins/jquery/jquery-ui.1.12.1.min.js')}}"></script>
<script src="{{asset('assets/plugins/metisMenu/metisMenu.min.js')}}"></script>
<script src="{{asset('assets/plugins/chart.js/Chart.min.js')}}"></script>
<script src="{{asset('assets/plugins/sparkline/sparkline.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables/dataTables.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>

<script src="{{asset('assets/dist/js/pages/dashboard.js')}}"></script>

<!-- pages with datatable-->

<script src="{{asset('assets/plugins/datatables/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables/jszip.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/plugins/datatables/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables/buttons.colVis.min.js')}}"></script>

<script src="{{asset('assets/plugins/datatables/data-bootstrap4.active.js')}}"></script>
<script src="{{asset('assets/plugins/modals/classie.js')}}"></script>
<script src="{{asset('assets/plugins/modals/modalEffects.js')}}"></script>

<script src="{{asset('assets/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>

<script src="{{asset('assets/plugins/daterangepicker/daterangepicker.active.js')}}"></script>
<script src="{{asset('assets/plugins/select2/js/select2.full.js')}}"></script>
<script src="{{asset('assets/dist/js/pages/demo.select2.js')}}"></script>

<script src="{{asset('assets/plugins/icheck/icheck.min.js')}}"></script>

<script src="{{asset('assets/plugins/bootstrap4-toggle/js/bootstrap4-toggle.min.js')}}"></script>
<script src="{{asset('assets/plugins/typehead/typeahead.js')}}"></script>

<script src="{{asset('assets/dist/js/sidebar.js')}}"></script>
<script src="{{asset('assets/dist/js/driver_performance.js')}}"></script>
<script src="{{asset('assets/plugins/jquery-validation/jquery.validate.js')}}"></script>

<script>

    $(document).ready(function () {
        let url = window.location.href;
        let segment2 = url.split('/');
        if (segment2[4] === 'dashboard' && segment2[5] === 'home') {
            $("#bedcumb").hide();
        }
    });

    class Router {
        constructor() {
        }

        navigateTo(url) {
            let siteUrl = url;

            $(".content-loder").show();
            setTimeout(function () {
                $(".content-loder").hide();
            }, 900);

            // load page without navigation to another link
            $('#bodycontent').load(url);

            return;


            let segment = siteUrl.split('/');
            let moduleName = segment[4].replace("_", " ");
            let controllerName = segment[5].replace("_", " ");

            let functionName = '';//segment[6].replace("_", " ");

            if (6 <= segment[segment.length - 1]) {
                functionName = segment[6].replace("_", " ");
            }

            $("#bedcumb").show();
            //let modulebase = '';
            switch (moduleName) {
                case 'employeeManagement':
                    modulebase = baseUrl + moduleName + '/' + segment[5];
                    moduleName = 'Employee Management';

                    if (segment[5] === 'Employees' && segment[6] === 'index') {
                        window.history.pushState('', '', modulebase + '/emplist');
                    }

                    if (segment[5] === 'Employees' && segment[6] === 'create_position') {
                        window.history.pushState('', '', modulebase + '/position_view');
                    }

                    if (segment[5] === 'Department_controller' && segment[6] === 'dept_view') {
                        window.history.pushState('', '', modulebase + '/departmentlist');
                    }

                    if (segment[5] === 'Driver_controller' && segment[6] === 'index') {
                        window.history.pushState('', '', modulebase + '/driverlist');
                    }

                    if (segment[5] === 'Driver_controller' && segment[6] === 'licensetypelist') {
                        window.history.pushState('', '', modulebase + '/alllicensetypelist');
                    }

                    if (segment[5] === 'Driver_controller' && segment[6] === 'performancelist') {
                        window.history.pushState('', '', modulebase + '/allperformancelist');
                    }

                    if (segment[5] === 'Approval_list' && segment[6] === 'index') {
                        window.history.pushState('', '', modulebase + '/allindex');
                    }
                    break;

                case 'vehicleManagement':
                    var modulebase = baseUrl + moduleName + '/' + segment[5];
                    moduleName = 'Vehicle Management';

                    if (segment[5] == 'Vehicle_management' && segment[6] == 'index') {
                        window.history.pushState('', '', modulebase + '/vehiclelist');
                    }

                    if (segment[5] == 'Vehicle_management' && segment[6] == 'insurancelist') {
                        window.history.pushState('', '', modulebase + '/insurance');
                    }
                    if (segment[5] == 'Vehicle_management' && segment[6] == 'legaldocumentlist') {
                        window.history.pushState('', '', modulebase + '/documentlist');
                    }
                    if (segment[5] == 'Vehicle_management' && segment[6] == 'managereminder') {
                        window.history.pushState('', '', modulebase + '/allmanagereminder');
                    }
                    break;

                case 'vehicleRequisition':
                    var modulebase = baseUrl + moduleName + '/' + segment[5];
                    moduleName = 'Vehicle Requisition';

                    if (segment[5] == 'Vehicle_requisition' && segment[6] == 'index') {
                        window.history.pushState('', '', modulebase + '/vreqlist');
                    }
                    if (segment[5] == 'Vehicle_requisition' && segment[6] == 'vehicleroute') {
                        window.history.pushState('', '', modulebase + '/allvehicleroute');
                    }
                    if (segment[5] == 'Vehicle_requisition' && segment[6] == 'approvalauthority') {
                        window.history.pushState('', '', modulebase + '/vapprovalauthority');
                    }
                    if (segment[5] == 'Vehicle_requisition' && segment[6] == 'pickdropreq') {
                        window.history.pushState('', '', modulebase + '/allpickdropreq');
                    }
                    break;

                case 'costManagement':
                    var modulebase = baseUrl + moduleName + '/' + segment[5];
                    moduleName = 'Cost & Inventory';

                    if (segment[5] == 'costManagement' && segment[6] == 'index') {
                        window.history.pushState('', '', modulebase + '/costindex');
                    }

                    if (segment[5] == 'costManagement' && segment[6] == 'partslist') {
                        window.history.pushState('', '', modulebase + '/allpartslist');
                    }
                    if (segment[5] == 'costManagement' && segment[6] == 'categorylist') {
                        window.history.pushState('', '', modulebase + '/allcategorylist');
                    }
                    if (segment[5] == 'costManagement' && segment[6] == 'locationlist') {
                        window.history.pushState('', '', modulebase + '/alllocationlist');
                    }
                    if (segment[5] == 'costManagement' && segment[6] == 'stocklist') {
                        window.history.pushState('', '', modulebase + '/allstocklist');
                    }
                    break;

                case 'dashboard':
                    var modulebase = baseUrl + moduleName + '/' + segment[5];
                    window.history.pushState('', '', baseUrl + moduleName + '/');
                    break;

                case 'maintenance':
                    var modulebase = baseUrl + moduleName + '/' + segment[5];

                    if (segment[5] == 'Maintenance' && segment[6] == 'index') {
                        window.history.pushState('', '', modulebase + '/maintenanceview');
                    }
                    if (segment[5] == 'Maintenance' && segment[6] == 'approvalauthority') {
                        window.history.pushState('', '', modulebase + '/mapprovalauthority');
                    }
                    if (segment[5] == 'Maintenance' && segment[6] == 'maintenanceservice') {
                        window.history.pushState('', '', modulebase + '/allmaintenanceservice');
                    }
                    break;

                case 'purchase':
                    var modulebase = baseUrl + moduleName + '/' + segment[5];

                    if (segment[5] == 'Purchase' && segment[6] == 'index') {
                        window.history.pushState('', '', modulebase + '/allpurchaseindex');
                    }

                    if (segment[5] == 'Purchase' && segment[6] == 'index') {
                        window.history.pushState('', '', modulebase + '/allpurchaseindex');
                    }

                    if (segment[5] == 'Purchase' && segment[6] == 'addpurchase') {
                        window.history.pushState('', '', modulebase + '/add_purchaselist');
                    }
                    if (segment[5] == 'Purchase' && segment[6] == 'partsuse') {
                        window.history.pushState('', '', modulebase + '/allusagelist');
                    }
                    if (segment[5] == 'Purchase' && segment[6] == 'addusages') {
                        window.history.pushState('', '', modulebase + '/addusagesall');
                    }
                    break;

                case 'refueling':
                    var modulebase = baseUrl + moduleName + '/' + segment[5];

                    if (segment[5] == 'Refueling' && segment[6] == 'index') {
                        window.history.pushState('', '', modulebase + '/refindex');
                    }

                    if (segment[5] == 'Refueling' && segment[6] == 'fuelstation') {
                        window.history.pushState('', '', modulebase + '/allfuelstation');
                    }
                    if (segment[5] == 'Refueling' && segment[6] == 'refuelrequisition') {
                        window.history.pushState('', '', modulebase + '/allrefuelrequisition');
                    }
                    if (segment[5] == 'Refueling' && segment[6] == 'refuelapproval_authority') {
                        window.history.pushState('', '', modulebase + '/allrefuelapproval_authority');
                    }
                    break;

                case 'reports':
                    var modulebase = baseUrl + moduleName + '/' + segment[5];

                    if (segment[5] == 'Reports' && segment[6] == 'index') {
                        window.history.pushState('', '', modulebase + '/empresult');
                    }
                    if (segment[5] == 'Reports' && segment[6] == 'driver_reportlist') {
                        window.history.pushState('', '', modulebase + '/alldriver_reportlist');
                    }
                    if (segment[5] == 'Reports' && segment[6] == 'vehiclelist_report') {
                        window.history.pushState('', '', modulebase + '/allvehiclelist_report');
                    }
                    if (segment[5] == 'Reports' && segment[6] == 'vehicleRequisition_report') {
                        window.history.pushState('', '', modulebase + '/allvehicleRequisition_report');
                    }
                    if (segment[5] == 'Reports' && segment[6] == 'renewallist_report') {
                        window.history.pushState('', '', modulebase + '/allrenewallist_report');
                    }
                    if (segment[5] == 'Reports' && segment[6] == 'pickdropreport') {
                        window.history.pushState('', '', modulebase + '/allpickdropreport');
                    }
                    if (segment[5] == 'Reports' && segment[6] == 'refuelreqreport') {
                        window.history.pushState('', '', modulebase + '/allrefuelreqreport');
                    }
                    if (segment[5] == 'Reports' && segment[6] == 'purchase_report') {
                        window.history.pushState('', '', modulebase + '/allpurchase_report');
                    }
                    if (segment[5] == 'Reports' && segment[6] == 'expensereport') {
                        window.history.pushState('', '', modulebase + '/allexpensereport');
                    }
                    if (segment[5] == 'Reports' && segment[6] == 'maintenancereport') {
                        window.history.pushState('', '', modulebase + '/allmaintenancereport');
                    }
                    break;

                case 'setting':
                    var modulebase = baseUrl + moduleName + '/' + segment[5];

                    if (segment[5] == 'Setting' && segment[6] == 'company') {
                        window.history.pushState('', '', modulebase + '/allcompany');
                    }
                    if (segment[5] == 'Setting' && segment[6] == 'recurringperiode') {
                        window.history.pushState('', '', modulebase + '/allrecurringperiode');
                    }
                    if (segment[5] == 'Setting' && segment[6] == 'notification') {
                        window.history.pushState('', '', modulebase + '/allnotification');
                    }
                    if (segment[5] == 'Setting' && segment[6] == 'documentation') {
                        window.history.pushState('', '', modulebase + '/alldocumentation');
                    }
                    if (segment[5] == 'Setting' && segment[6] == 'vendor') {
                        window.history.pushState('', '', modulebase + '/allvendor');
                    }
                    if (segment[5] == 'Setting' && segment[6] == 'vehicletype') {
                        window.history.pushState('', '', modulebase + '/allvehicletype');
                    }
                    if (segment[5] == 'Setting' && segment[6] == 'reqpurpose') {
                        window.history.pushState('', '', modulebase + '/allreqpurpose');
                    }
                    if (segment[5] == 'Setting' && segment[6] == 'reqtype') {
                        window.history.pushState('', '', modulebase + '/allreqtype');
                    }
                    if (segment[5] == 'Setting' && segment[6] == 'reqphase') {
                        window.history.pushState('', '', modulebase + '/allreqphase');
                    }
                    if (segment[5] == 'Setting' && segment[6] == 'maintenancelist') {
                        window.history.pushState('', '', modulebase + '/allmaintenancelist');
                    }
                    if (segment[5] == 'Setting' && segment[6] == 'prioritylist') {
                        window.history.pushState('', '', modulebase + '/allprioritylist');
                    }
                    if (segment[5] == 'Setting' && segment[6] == 'servicetypelist') {
                        window.history.pushState('', '', modulebase + '/allservicetypelist');
                    }
                    if (segment[5] == 'Setting' && segment[6] == 'fueltypelist') {
                        window.history.pushState('', '', modulebase + '/allfueltypelist');
                    }
                    if (segment[5] == 'Setting' && segment[6] == 'triptypelist') {
                        window.history.pushState('', '', modulebase + '/alltriptypelist');
                    }
                    if (segment[5] == 'Setting' && segment[6] == 'divisionlist') {
                        window.history.pushState('', '', modulebase + '/alldivisionlist');
                    }
                    if (segment[5] == 'Setting' && segment[6] == 'brtaofficelist') {
                        window.history.pushState('', '', modulebase + '/allbrtaofficelist');
                    }
                    if (segment[5] == 'Setting' && segment[6] == 'ownershiplist') {
                        window.history.pushState('', '', modulebase + '/allownershiplist');
                    }
                    break;

                default:
                    break;
            }

            //Employee Management Start
            switch (functionName) {
                case 'create position':
                    functionName = 'Position';
                    break;
            }
            if (functionName === 'dept view') {
                functionName = 'Department';
            }
            if (controllerName === 'Employees' && functionName === 'index') {
                functionName = 'Manage Employee';
            }
            if (controllerName === 'Driver controller' && functionName === 'index') {
                functionName = 'Manage Driver';
            }
            if (functionName === 'licensetypelist') {
                functionName = 'Manage License';
            }
            if (functionName === 'performancelist') {
                functionName = 'Driver Performance';
            }
            if (controllerName === 'Approval list' && functionName === 'index') {
                functionName = 'Manage Req. Approval';
            }
            //Employee Management end

            //Vehicle Management Start
            if (controllerName === 'Vehicle management' && functionName === 'index') {
                functionName = 'Manage Vehicle';
            }
            if (functionName === 'insurancelist') {
                functionName = 'Insurance Details';
            }
            if (functionName === 'legaldocumentlist') {
                functionName = 'Manage Legal Document';
            }
            if (functionName === 'managereminder') {
                functionName = 'Reminder Details';
            }
            //Vehicle Management end

            //Vehicle requisition start
            if (controllerName === 'Vehicle requisition' && functionName === 'index') {
                functionName = 'Manage Vehicle Requisition';
            }
            if (functionName === 'vehicleroute') {
                functionName = 'Vehicle Route Details';
            }
            if (functionName === 'approvalauthority') {
                functionName = 'Manage Approval Authority';
            }
            if (functionName === 'pickdropreq') {
                functionName = 'Pick & Drop Requisition List';
            }
            //Vehicle requisition End

            //Maintenance Start
            if (controllerName === 'Maintenance' && functionName === 'index') {
                functionName = 'Maintenance Requisition List';
            }
            if (functionName === 'maintenanceservice') {
                functionName = 'Maintenance Service List';
            }
            //Maintenance End

            //Cost Inventory start
            if (controllerName === 'costManagement' && functionName === 'index') {
                functionName = 'Manage Expense Type';
            }
            if (functionName === 'partslist') {
                functionName = 'Manage Parts';
            }
            if (functionName === 'categorylist') {
                functionName = 'Category';
            }
            if (functionName === 'locationlist') {
                functionName = 'Location';
            }
            if (functionName === 'stocklist') {
                functionName = 'Stock Management';
            }
            //Cost Inventory End

            //Purchase Start
            if (controllerName === 'Purchase' && functionName === 'index') {

                functionName = 'Purchase Details';
            }
            if (functionName === 'addpurchase') {
                functionName = 'Add Purchase';
            }
            if (functionName === 'partsuse') {
                functionName = 'Parts Usages List';
            }
            if (functionName === 'addusages') {
                functionName = 'Add Parts Usage';
            }
            //Purchase End

            //Refueling Start
            if (controllerName === 'Refueling' && functionName === 'index') {
                functionName = 'Refueling Setting';
            }
            if (functionName === 'fuelstation') {
                functionName = 'Manage Fuel Station ';
            }
            if (functionName === 'refuelrequisition') {
                functionName = 'Refuel Requisition Details';
            }
            if (functionName === 'refuelapproval authority') {
                functionName = 'Manage Approval Authority';
            }
            //Refueling Start

            //Reports Start
            if (controllerName === 'Reports' && functionName === 'index') {
                functionName = 'Employee Report';
            }
            if (functionName === 'driver reportlist') {
                functionName = 'Driver Report';
            }
            if (functionName === 'vehiclelist report') {
                functionName = 'Vehicle Report';
            }
            if (functionName === 'vehicleRequisition report') {
                functionName = 'Vehicle Requisition';
            }
            if (functionName === 'pickdropreport') {
                functionName = 'Pick & Drop Requisition';
            }
            if (functionName === 'refuelreqreport') {
                functionName = 'Refuel Requisition Details';
            }
            if (functionName === 'purchase report') {
                functionName = 'Purchase Details';
            }
            if (functionName === 'expensereport') {
                functionName = 'Expense Details';
            }
            if (functionName === 'maintenancereport') {
                functionName = 'Maintenance Req. Details';
            }
            if (functionName === 'renewallist report') {
                functionName = 'Renewal Report';
            }
            //Reports End

            //Setting Start
            if (functionName === 'company') {
                functionName = 'Manage Company';
            }
            if (functionName === 'recurringperiode') {
                functionName = 'Manege Recurring Period';
            }
            if (functionName === 'notification') {
                functionName = 'Notification';
            }
            if (functionName === 'documentation') {
                functionName = 'Document Type';
            }
            if (functionName === 'vendor') {
                functionName = 'Manage Vendor';
            }
            if (functionName === 'vehicletype') {
                functionName = 'Vehicle Type';
            }
            if (functionName === 'reqpurpose') {
                functionName = 'Requisition Purpose';
            }
            if (functionName === 'reqtype') {
                functionName = 'Requisition Type'
            }
            if (functionName === 'reqphase') {
                functionName = 'Manage Phase';
            }
            if (functionName === 'maintenancelist') {
                functionName = 'Maintenance Types';
            }
            if (functionName === 'prioritylist') {
                functionName = 'Manage Priority';
            }
            if (functionName === 'servicetypelist') {
                functionName = 'Service Types';
            }
            if (functionName === 'fueltypelist') {
                functionName = 'Fuel Types';
            }
            if (functionName === 'triptypelist') {
                functionName = 'Trip Details';
            }
            if (functionName === 'divisionlist') {
                functionName = 'Division';
            }
            if (functionName === 'brtaofficelist') {
                functionName = 'RTA Office Details';
            }
            if (functionName === 'ownershiplist') {
                functionName = 'Manage Ownership';
            }
            //Setting End

            functionName = functionName.toLowerCase().replace(/\b[a-z]/g, function (letter) {
                return letter.toUpperCase();
            });

            moduleName = moduleName.toLowerCase().replace(/\b[a-z]/g, function (letter) {
                return letter.toUpperCase();
            });

            $('#moduleName').html(moduleName);
            $('#moduleName1').html(moduleName);
            $('#controllerName').html(functionName);

        }
    }

    function pageopen(url) {
        new Router().navigateTo(url);
    }

    $('.newdatetimepicker').daterangepicker({

        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: false,

        minYear: 1901,
        maxDate: '2100',
        "drops": "down",
        locale: {
            format: 'YYYY-MM-DD'

        },

        maxYear: parseInt(moment().format('YYYY'), 10)
    }, function (start, end, label) {
        var years = moment().diff(start, 'years');
    });

    $('.newdatetimepicker').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD'));
    });

    $('.newdatetimepicker').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });

    $('.ttimepicker').daterangepicker({
        singleDatePicker: true,
        timePicker: true,
        timePicker24Hour: false,
        "locale": {
            "format": "hh:mm A"
        }
    }).on('show.daterangepicker', function (ev, picker) {
        picker.container.find(".calendar-table").hide();
    });
</script>
@stack('scripts')


<script src="{{asset('application/modules/costManagement/assets/js/script.js')}}"
        type="text/javascript">
</script>
<script src="{{asset('application/modules/employeeManagement/assets/js/script.js')}}"
        type="text/javascript"></script>
<script src="{{asset('application/modules/maintenance/assets/js/script.js')}}"
        type="text/javascript"></script>
<script src="{{asset('application/modules/purchase/assets/js/script.js')}}"
        type="text/javascript"></script>
<script src="{{asset('application/modules/refueling/assets/js/script.js')}}"
        type="text/javascript"></script>
<script src="{{asset('application/modules/reports/assets/js/script.js')}}"
        type="text/javascript"></script>
<script src="{{asset('application/modules/configurations/assets/js/script.js')}}"
        type="text/javascript"></script>
<script src="{{asset('application/modules/vehicleManagement/assets/js/script.js')}}"
        type="text/javascript"></script>
<script src="{{asset('application/modules/vehicleRequisition/assets/js/script.js')}}"
        type="text/javascript"></script>
</body>
</html>
