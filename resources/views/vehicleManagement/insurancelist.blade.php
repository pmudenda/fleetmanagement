
<!doctype html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
    <meta name="author" content="Bdtask">
    <title>AMS :: Home		</title>

    <link rel="shortcut icon" href="https://vmsdemo.bdtask-demo.com/assets/img/icons/2023-02-26/j.png" type="image/x-icon">
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ['Nunito+Sans:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&display=swap']
            }
        });
    </script>

    <link href="https://vmsdemo.bdtask-demo.com/assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://vmsdemo.bdtask-demo.com/assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet">
    <link href="https://vmsdemo.bdtask-demo.com/assets/plugins/modals/component.css" rel="stylesheet">
    <link href="https://vmsdemo.bdtask-demo.com/assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="https://vmsdemo.bdtask-demo.com/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://vmsdemo.bdtask-demo.com/assets/plugins/select2-bootstrap4/dist/select2-bootstrap4.min.css" rel="stylesheet">
    <link href="https://vmsdemo.bdtask-demo.com/assets/dist/css/select.css" rel="stylesheet">
    <link href="https://vmsdemo.bdtask-demo.com/assets/plugins/icheck/skins/all.css" rel="stylesheet">
    <link href="https://vmsdemo.bdtask-demo.com/assets/plugins/bootstrap4-toggle/css/bootstrap4-toggle.min.css" rel="stylesheet">
    <link href="https://vmsdemo.bdtask-demo.com/assets/plugins/typehead/typehead.css" rel="stylesheet">

    <link href="https://vmsdemo.bdtask-demo.com/assets/dist/css/app.min.css" rel="stylesheet">

    <link href="https://vmsdemo.bdtask-demo.com/assets/dist/css/custom.css" rel="stylesheet">
    <link href="https://vmsdemo.bdtask-demo.com/assets/dist/css/create_system_role.css" rel="stylesheet">

    <link href=https://vmsdemo.bdtask-demo.com/application/modules/costManagement/assets/css/style.css rel="stylesheet"><link href=https://vmsdemo.bdtask-demo.com/application/modules/employeeManagement/assets/css/style.css rel="stylesheet"><link href=https://vmsdemo.bdtask-demo.com/application/modules/maintenance/assets/css/style.css rel="stylesheet"><link href=https://vmsdemo.bdtask-demo.com/application/modules/purchase/assets/css/style.css rel="stylesheet"><link href=https://vmsdemo.bdtask-demo.com/application/modules/refueling/assets/css/style.css rel="stylesheet"><link href=https://vmsdemo.bdtask-demo.com/application/modules/reports/assets/css/style.css rel="stylesheet"><link href=https://vmsdemo.bdtask-demo.com/application/modules/setting/assets/css/style.css rel="stylesheet"><link href=https://vmsdemo.bdtask-demo.com/application/modules/vehicleManagement/assets/css/style.css rel="stylesheet"><link href=https://vmsdemo.bdtask-demo.com/application/modules/vehicleRequisition/assets/css/style.css rel="stylesheet">
    <link href="https://vmsdemo.bdtask-demo.com/assets/plugins/multiselectedjs/jquery.multiselect.css" rel="stylesheet">
    <script src="https://vmsdemo.bdtask-demo.com/assets/dist/js/app.min.js"></script>
    <script src="https://vmsdemo.bdtask-demo.com/assets/plugins/multiselectedjs/jquery.multiselect.js"></script>
    <script>
        var baseurl = "https://vmsdemo.bdtask-demo.com/";
    </script></head>
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

    <nav class="sidebar sidebar-bunker">
        <div class="sidebar-header">
            <a href="https://vmsdemo.bdtask-demo.com/dashboard/home" class="logo"><img src="https://vmsdemo.bdtask-demo.com/assets/img/icons/2023-02-26/j.jpg" alt=""></a>
        </div>

        <div class="profile-element d-flex align-items-center flex-shrink-0">
            <div class="avatar online">
                <img src="https://vmsdemo.bdtask-demo.com/./assets/img/user/admin.jpg" class="img-fluid rounded-circle" alt="">
            </div>
            <div class="profile-text">
                <h6 class="m-0">Super Admin</h6>
            </div>
        </div>

        <div class="sidebar-body">
            <nav class="sidebar-nav">
                <ul class="metismenu">
                    <li class="mm-active">
                        <a href="https://vmsdemo.bdtask-demo.com/dashboard/home"><i class="typcn typcn-home-outline mr-2"></i> Dashboard</a>
                    </li>
                    <li class="">
                        <a class="has-arrow material-ripple" href="#"><i class='typcn icon-default typcn-group-outline mr-2'></i>Employee Management</a>
                        <ul class="nav-second-level ">


                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/index')">Manage Employee</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/create_position')">Position</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/employeeManagement/Department_controller/dept_view')">Department</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/employeeManagement/Driver_controller/index')">Manage Driver</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/employeeManagement/Driver_controller/licensetypelist')">Manage License</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/employeeManagement/Driver_controller/performancelist')">Driver Performance</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/employeeManagement/Approval_list/index')">Manage Req. Approval</a>
                            </li>

                        </ul>
                    </li>


                    <li class="">
                        <a class="has-arrow material-ripple" href="#"><i class='typcn icon-default typcn-social-dribbble mr-2'></i>Vehicle Management</a>
                        <ul class="nav-second-level ">


                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/vehicleManagement/Vehicle_management/index')">Manage Vehicle</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/vehicleManagement/Vehicle_management/insurancelist')">Insurance Details</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/vehicleManagement/Vehicle_management/legaldocumentlist')">Manage Legal Document</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/vehicleManagement/Vehicle_management/managereminder')">Reminder Details</a>
                            </li>

                        </ul>
                    </li>


                    <li class="">
                        <a class="has-arrow material-ripple" href="#"><i class='typcn icon-default typcn-eject-outline mr-2'></i>Vehicle Requisition</a>
                        <ul class="nav-second-level ">


                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/vehicleRequisition/Vehicle_requisition/index')">Manage Vehicle Requisition</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/vehicleRequisition/Vehicle_requisition/vehicleroute')">Vehicle Route Details</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/vehicleRequisition/Vehicle_requisition/approvalauthority')">Manage Approval Authority</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/vehicleRequisition/Vehicle_requisition/pickdropreq')">Pick & Drop Requisition List</a>
                            </li>

                        </ul>
                    </li>


                    <li class="">
                        <a class="has-arrow material-ripple" href="#"><i class='typcn icon-default typcn-compass mr-2'></i>Maintenance</a>
                        <ul class="nav-second-level ">


                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/maintenance/Maintenance/index')">Maintenance Req. Details</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/maintenance/Maintenance/approvalauthority')">Manage Approval Authority</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/maintenance/Maintenance/maintenanceservice')">Maintenance Service List</a>
                            </li>

                        </ul>
                    </li>


                    <li class="">
                        <a class="has-arrow material-ripple" href="#"><i class='typcn icon-default typcn-ticket mr-2'></i>Cost & Inventory</a>
                        <ul class="nav-second-level ">


                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/costManagement/costManagement/index')">Manage Expense Type</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/costManagement/costManagement/partslist')">Manage Parts</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/costManagement/costManagement/categorylist')">Category</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/costManagement/costManagement/locationlist')">Location</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/costManagement/costManagement/stocklist')">Stock Management</a>
                            </li>

                        </ul>
                    </li>


                    <li class="">
                        <a class="has-arrow material-ripple" href="#"><i class='typcn icon-default typcn-ticket mr-2'></i>Purchase & Usage</a>
                        <ul class="nav-second-level ">


                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/purchase/Purchase/index')">Purchase Details</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/purchase/Purchase/addpurchase')">Add Purchase</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/purchase/Purchase/partsuse')">Parts Usages List</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/purchase/Purchase/addusages')">Add Parts Usage</a>
                            </li>

                        </ul>
                    </li>


                    <li class="">
                        <a class="has-arrow material-ripple" href="#"><i class='typcn icon-default typcn-arrow-sync-outline mr-2'></i>Refueling</a>
                        <ul class="nav-second-level ">


                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/refueling/Refueling/index')">Refuel Setting</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/refueling/Refueling/fuelstation')">Manage Fuel Station </a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/refueling/Refueling/refuelrequisition')">Refuel Requisition Details</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/refueling/Refueling/refuelapproval_authority')">Manage Approval Authority</a>
                            </li>

                        </ul>
                    </li>


                    <li class="">
                        <a class="has-arrow material-ripple" href="#"><i class='typcn icon-default typcn-chart-area-outline mr-2'></i>Reports</a>
                        <ul class="nav-second-level ">


                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/reports/Reports/index')">Employee Report</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/reports/Reports/driver_reportlist')">Driver Report</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/reports/Reports/vehiclelist_report')">Vehicle Report</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/reports/Reports/vehicleRequisition_report')">Vehicle Requisition</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/reports/Reports/renewallist_report')">Renewal Report</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/reports/Reports/pickdropreport')">Pick & Drop Req. List</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/reports/Reports/refuelreqreport')">Refuel Requisition Details</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/reports/Reports/purchase_report')">Purchase Details</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/reports/Reports/expensereport')">Expense Details</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/reports/Reports/maintenancereport')">Maintenance Req. Details</a>
                            </li>

                        </ul>
                    </li>


                    <li class="">
                        <a class="has-arrow material-ripple" href="#"><i class='typcn icon-default typcn-cog-outline mr-2'></i>Setting</a>
                        <ul class="nav-second-level ">


                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/company')">Manage Company</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/recurringperiode')">Manege Recurring Period</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/notification')">Notification</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/documentation')">Document Type</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/vendor')">Manage Vendor</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/vehicletype')">Vehicle Type</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/reqpurpose')">Requisition Purpose</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/reqtype')">Requisition Type</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/reqphase')">Manage Phase</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/maintenancelist')">Maintenance Types</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/prioritylist')">Manage Priority</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/servicetypelist')">Service Types</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/fueltypelist')">Fuel Types</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/triptypelist')">Trip Details</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/divisionlist')">Division</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/brtaofficelist')">RTA Office Details</a>
                            </li>

                            <li class="">
                                <a style="cursor:pointer;" onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/ownershiplist')">Manage Ownership</a>
                            </li>

                        </ul>
                    </li>


                    <li class="nav-label">Admin menu</li>
                    <li class="">
                        <a class="has-arrow material-ripple" href="#">
                            <i class="typcn icon-default typcn-edit  mr-2"></i> User </a>
                        <ul class="nav-second-level ">
                            <li class=""><a href="https://vmsdemo.bdtask-demo.com/dashboard/user/form">Add User</a>
                            </li>
                            <li class=""><a href="https://vmsdemo.bdtask-demo.com/dashboard/user/index">Manage User</a>
                            </li>
                        </ul>
                    </li>
                    <li class="">
                        <a class="has-arrow material-ripple" href="#">
                            <i class="typcn icon-default typcn-edit  mr-2"></i>
                            Role Permission </a>
                        <ul class="nav-second-level ">
                            <li class="">
                                <a href="https://vmsdemo.bdtask-demo.com/dashboard/role/create_system_role">Assign role</a>
                            </li>
                            <li class=""><a href="https://vmsdemo.bdtask-demo.com/dashboard/role/role_list">Manage Role</a>
                            </li>
                            <li class="">
                                <a href="https://vmsdemo.bdtask-demo.com/dashboard/role/user_access_role">User Access Role Details</a>
                            </li>
                        </ul>
                    </li>
                    <li class=""><a href="https://vmsdemo.bdtask-demo.com/dashboard/language"><i class="typcn icon-default typcn-flag-outline mr-2"></i>
                            Language</a></li>
                    <li class=""><a href="https://vmsdemo.bdtask-demo.com/dashboard/setting"><i class="typcn icon-default typcn-cog-outline mr-2"></i>
                            Application Setting</a></li>
                </ul>
            </nav>
        </div> </nav>

    <div class="content-wrapper">
        <div class="main-content position-relative">
            <div class="page-loader-wrapper content-loder">
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
            <nav class="navbar-custom-menu navbar navbar-expand-lg m-0">
                <div class="sidebar-toggle-icon" id="sidebarCollapse">
                    sidebar toggle<span></span>
                </div>

                <div class="d-flex flex-grow-1">
                    <ul class="navbar-nav flex-row align-items-center ml-auto">
                        <li class="nav-item dropdown user-menu">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                                <i class="typcn typcn-user-add-outline"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <div class="dropdown-header d-sm-none">
                                    <a href="" class="header-arrow"><i class="icon ion-md-arrow-back"></i></a>
                                </div>
                                <div class="user-header">
                                    <div class="img-user">
                                        <img src="https://vmsdemo.bdtask-demo.com/./assets/img/user/admin.jpg" alt="">
                                    </div>
                                    Super Admin </div>
                                <a href="https://vmsdemo.bdtask-demo.com/dashboard/home/profile" class="dropdown-item"><i class="typcn typcn-user-outline"></i> My Profile</a>
                                <a href="https://vmsdemo.bdtask-demo.com/dashboard/home/setting" class="dropdown-item"><i class="typcn typcn-edit"></i> Edit Profile</a>
                                <a href="https://vmsdemo.bdtask-demo.com/dashboard/setting" class="dropdown-item"><i class="typcn typcn-cog-outline"></i> Application Settings</a>
                                <a href="https://vmsdemo.bdtask-demo.com/dashboard/auth/logout" class="dropdown-item"><i class="typcn typcn-key-outline"></i> Sign Out</a>
                            </div>

                        </li>
                    </ul>

                    <div class="nav-clock">
                        <div class="time">
                            <span class="time-hours"></span>
                            <span class="time-min"></span>
                            <span class="time-sec"></span>
                        </div>
                    </div>
                </div>

            </nav>


            <div class="content-header row align-items-center m-0" id="bedcumb">
                <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
                    <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
                        <li class="breadcrumb-item"><a href="https://vmsdemo.bdtask-demo.com/dashboard/home">Home</a></li>
                        <li id="moduleName" class="breadcrumb-item active">
                            Dashboard</li>
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
                <div class="row">
                    <div class="col-lg-12">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fs-17 font-weight-600 mb-0">Vehicles</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-column">
                                    <div>
                                        <i class="fas fa fa-caret-right text-success"></i>
                                        <a href="#">On Requisition<span class="float-right"><strong>27</strong></span></a>
                                    </div>
                                    <div>
                                        <i class="fas fa fa-caret-right text-success"></i>
                                        <a href="#">On Maintenance <span class="float-right"><strong>13</strong></span></a>
                                    </div>
                                    <div>
                                        <i class="fas fa fa-caret-right text-success"></i>
                                        <a href="#">Available <span class="float-right"><strong>2</strong></span></a>
                                    </div>
                                    <div>
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fs-17 font-weight-600 mb-0">Todays Requisition</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-column">
                                    <div>
                                        <i class="fas fa fa-caret-right text-success"></i>
                                        <a href="#">Vehicle Requisition <span class="float-right"><strong>0</strong></span></a>
                                    </div>
                                    <div>
                                        <i class="fas fa fa-caret-right text-success"></i>
                                        <a href="#">Pick & Drop Requisition <span class="float-right"><strong>0</strong></span></a>
                                    </div>
                                    <div>
                                        <i class="fas fa fa-caret-right text-success"></i>
                                        <a href="#">Maintenance Requisition<span class="float-right"><strong>0</strong></span></a>
                                    </div>
                                    <div>
                                        <i class="fas fa fa-caret-right text-success"></i>
                                        <a href="#">Fuel Requisition<span class="float-right"><strong>0</strong></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fs-17 font-weight-600 mb-0">Reminder </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-column">
                                    <div>
                                        <i class="fas fa fa-caret-right text-success"></i>
                                        <a href="#">Legal Doc Soon Expire <span class="float-right"><strong>0</strong></span></a>
                                    </div>
                                    <div>
                                        <i class="fas fa fa-caret-right text-success"></i>
                                        <a href="#">Legal Doc Expired <span class="float-right"><strong>0</strong></span></a>
                                    </div>
                                    <div>
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fs-17 font-weight-600 mb-0"> Others Activities</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-column">
                                    <div>
                                        <i class="fas fa fa-caret-right text-success"></i>
                                        <a href="#">Stock In <span class="float-right"><strong>115</strong></span></a>
                                    </div>
                                    <div>
                                        <i class="fas fa fa-caret-right text-success"></i>
                                        <a href="#"> Stock Out <span class="float-right"><strong>772040</strong></span></a>
                                    </div>
                                    <div>
                                        &nbsp;
                                    </div>
                                    <div>
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fs-17 font-weight-600 mb-0">Expense Summary </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart mb-3">
                                    <canvas id="doughutChart" height="310"></canvas>
                                </div>
                                <div class="chart-legend">
                                    <div class="chart-legend-item">
                                        <div class="chart-legend-color kelly-green"></div>
                                        <p>Fuel Cost</p>
                                        <p class="percentage text-muted">13500.00</p>
                                    </div> <div class="chart-legend-item">
                                        <div class="chart-legend-color kelly-green2"></div>
                                        <p>Maintenance Cost</p>
                                        <p class="percentage text-muted">44260.00</p>
                                    </div> <div class="chart-legend-item">
                                        <div class="chart-legend-color whisper"></div>
                                        <p>Other Cost</p>
                                        <p class="percentage text-muted">1960.00</p>
                                    </div> </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h2 class="fs-18 font-weight-bold mb-0">Maintenance Cost</h2>
                            </div>
                            <div class="card-body">
                                <canvas id="barChart" height="190"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    $(document).ready(function() {
                        //doughut chart
                        var ctx = document.getElementById("doughutChart");
                        var myChart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                datasets: [{
                                    data: [13500.00,44260.00,1960.00],
                                    backgroundColor: [
                                        "#37a000",
                                        "#42b704",
                                        "#e4e4e4",
                                    ],
                                    hoverBackgroundColor: [
                                        "#4cd604",
                                        "#4cd604",
                                        "#4cd604"
                                    ]
                                }],
                                labels: ["Fuel","Maintenance","Other"]
                            },
                            options: {
                                legend: false,
                                responsive: true,
                                cutoutPercentage: 80
                            }
                        });

                        //bar chart
                        var chartColors = {
                            gray: '#e4e4e4',
                            orange: 'rgb(255, 159, 64)',
                            yellow: 'rgb(255, 205, 86)',
                            green: '#37a000',
                            blue: 'rgb(54, 162, 235)',
                            purple: 'rgb(153, 102, 255)',
                            grey: 'rgb(231,233,237)'
                        };

                        var randomScalingFactor = function() {
                            return (Math.random() > 0.5 ? 1.0 : 1.0) * Math.round(Math.random() * 100);
                        };

                        // draws a rectangle with a rounded top
                        Chart.helpers.drawRoundedTopRectangle = function(ctx, x, y, width, height, radius) {
                            ctx.beginPath();
                            ctx.moveTo(x + radius, y);
                            // top right corner
                            ctx.lineTo(x + width - radius, y);
                            ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
                            // bottom right	corner
                            ctx.lineTo(x + width, y + height);
                            // bottom left corner
                            ctx.lineTo(x, y + height);
                            // top left
                            ctx.lineTo(x, y + radius);
                            ctx.quadraticCurveTo(x, y, x + radius, y);
                            ctx.closePath();
                        };

                        Chart.elements.RoundedTopRectangle = Chart.elements.Rectangle.extend({
                            draw: function() {
                                var ctx = this._chart.ctx;
                                var vm = this._view;
                                var left, right, top, bottom, signX, signY, borderSkipped;
                                var borderWidth = vm.borderWidth;

                                if (!vm.horizontal) {
                                    // bar
                                    left = vm.x - vm.width / 2;
                                    right = vm.x + vm.width / 2;
                                    top = vm.y;
                                    bottom = vm.base;
                                    signX = 1;
                                    signY = bottom > top ? 1 : -1;
                                    borderSkipped = vm.borderSkipped || 'bottom';
                                } else {
                                    // horizontal bar
                                    left = vm.base;
                                    right = vm.x;
                                    top = vm.y - vm.height / 2;
                                    bottom = vm.y + vm.height / 2;
                                    signX = right > left ? 1 : -1;
                                    signY = 1;
                                    borderSkipped = vm.borderSkipped || 'left';
                                }

                                // Canvas doesn't allow us to stroke inside the width so we can
                                // adjust the sizes to fit if we're setting a stroke on the line
                                if (borderWidth) {
                                    // borderWidth shold be less than bar width and bar height.
                                    var barSize = Math.min(Math.abs(left - right), Math.abs(top - bottom));
                                    borderWidth = borderWidth > barSize ? barSize : borderWidth;
                                    var halfStroke = borderWidth / 2;
                                    // Adjust borderWidth when bar top position is near vm.base(zero).
                                    var borderLeft = left + (borderSkipped !== 'left' ? halfStroke * signX : 0);
                                    var borderRight = right + (borderSkipped !== 'right' ? -halfStroke * signX : 0);
                                    var borderTop = top + (borderSkipped !== 'top' ? halfStroke * signY : 0);
                                    var borderBottom = bottom + (borderSkipped !== 'bottom' ? -halfStroke * signY : 0);
                                    // not become a vertical line?
                                    if (borderLeft !== borderRight) {
                                        top = borderTop;
                                        bottom = borderBottom;
                                    }
                                    // not become a horizontal line?
                                    if (borderTop !== borderBottom) {
                                        left = borderLeft;
                                        right = borderRight;
                                    }
                                }

                                // calculate the bar width and roundess
                                var barWidth = Math.abs(left - right);
                                var roundness = this._chart.config.options.barRoundness || 0.5;
                                var radius = barWidth * roundness * 0.5;

                                // keep track of the original top of the bar
                                var prevTop = top;

                                // move the top down so there is room to draw the rounded top
                                top = prevTop + radius;
                                var barRadius = top - prevTop;

                                ctx.beginPath();
                                ctx.fillStyle = vm.backgroundColor;
                                ctx.strokeStyle = vm.borderColor;
                                ctx.lineWidth = borderWidth;

                                // draw the rounded top rectangle
                                Chart.helpers.drawRoundedTopRectangle(ctx, left, (top - barRadius + 1), barWidth,
                                    bottom - prevTop, barRadius);

                                ctx.fill();
                                if (borderWidth) {
                                    ctx.stroke();
                                }

                                // restore the original top value so tooltips and scales still work
                                top = prevTop;
                            }
                        });

                        Chart.defaults.roundedBar = Chart.helpers.clone(Chart.defaults.bar);

                        Chart.controllers.roundedBar = Chart.controllers.bar.extend({
                            dataElementType: Chart.elements.RoundedTopRectangle
                        });

                        var ctx = document.getElementById("barChart").getContext("2d");
                        var myBar = new Chart(ctx, {
                            type: 'roundedBar',
                            data: {
                                labels: ["Apr-22", "May-22", "Jun-22", "Jul-22", "Aug-22", "Sep-22", "Oct-22", "Nov-22", "Dec-22", "Jan-23", "Feb-23", "Mar-23", ],
                                datasets: [{
                                    label: 'Maintenance Cost',
                                    backgroundColor: chartColors.green,
                                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, ]
                                }]
                            },
                            options: {
                                legend: false,
                                responsive: true,
                                barRoundness: 1,
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true,
                                            padding: 10
                                        },
                                        gridLines: {
                                            borderDash: [2],
                                            borderDashOffset: [2],
                                            drawBorder: false,
                                            drawTicks: false
                                        }
                                    }],
                                    xAxes: [{
                                        maxBarThickness: 10,
                                        gridLines: {
                                            lineWidth: [0],
                                            drawBorder: false,
                                            drawOnChartArea: false,
                                            drawTicks: false
                                        },
                                        ticks: {
                                            padding: 20
                                        }
                                    }]
                                }
                            }
                        });
                    });
                </script>
            </div>

        </div>

        <footer class="footer-content">
            <div class="footer-text d-flex align-items-center justify-content-between">
                <div class="copy">2023<a href="https://vmsdemo.bdtask-demo.com/dashboard/home">
                        AMS</a></div>
                <div class="credit">Guaymas, Sonora Designed by: <a href="#">Bdtask</a>
                </div>
            </div>
        </footer>

        <div class="overlay"></div>
    </div>

</div>

<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/chartJs/Chart.min.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/sparkline/sparkline.min.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/datatables/dataTables.min.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>

<script src="https://vmsdemo.bdtask-demo.com/assets/dist/js/pages/dashboard.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/datatables/responsive.bootstrap4.min.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/datatables/buttons.bootstrap4.min.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/datatables/jszip.min.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/datatables/pdfmake.min.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/datatables/vfs_fonts.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/datatables/buttons.print.min.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/datatables/buttons.colVis.min.js"></script>

<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/datatables/data-bootstrap4.active.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/modals/classie.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/modals/modalEffects.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/moment/moment.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/daterangepicker/daterangepicker.js"></script>

<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/daterangepicker/daterangepicker.active.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/select2/dist/js/select2.full.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/dist/js/pages/demo.select2.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/icheck/icheck.min.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/bootstrap4-toggle/js/bootstrap4-toggle.min.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/plugins/typehead/typeahead.js"></script>

<script src="https://vmsdemo.bdtask-demo.com/assets/dist/js/sidebar.js"></script>
<script src="https://vmsdemo.bdtask-demo.com/assets/dist/js/driver_performance.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script>
    $(document).ready(function(){
        var url = window.location.href;
        var segment2 = url.split('/');
        if(segment2[4] == 'dashboard' && segment2[5] == 'home'){
            $("#bedcumb").hide();
        }
    });
    function pageopen(url) {
        var siteUrl = url;

        $(".content-loder").show();
        setTimeout(function() {
            $(".content-loder").hide();
        }, 900);
        $('#bodycontent').load(url);

        var segment = siteUrl.split('/');
        var moduleName = segment[4].replace("_", " ");
        var controllerName = segment[5].replace("_", " ");
        var functionName = segment[6].replace("_", " ");

        var baseUrl = "https://vmsdemo.bdtask-demo.com/";
        $("#bedcumb").show();

        if(moduleName == 'employeeManagement'){
            var modulebase=baseUrl+moduleName+'/'+segment[5];
            moduleName = 'Employee Management';

            if(segment[5] == 'Employees' && segment[6] == 'index'){
                window.history.pushState('','',modulebase+'/emplist');
            }

            if(segment[5] == 'Employees' && segment[6] == 'create_position'){
                window.history.pushState('','',modulebase+'/position_view');
            }

            if(segment[5] == 'Department_controller' && segment[6] == 'dept_view'){
                window.history.pushState('','',modulebase+'/departmentlist');
            }

            if(segment[5] == 'Driver_controller' && segment[6] == 'index'){
                window.history.pushState('','',modulebase+'/driverlist');
            }

            if(segment[5] == 'Driver_controller' && segment[6] == 'licensetypelist'){
                window.history.pushState('','',modulebase+'/alllicensetypelist');
            }

            if(segment[5] == 'Driver_controller' && segment[6] == 'performancelist'){
                window.history.pushState('','',modulebase+'/allperformancelist');
            }

            if(segment[5] == 'Approval_list' && segment[6] == 'index'){
                window.history.pushState('','',modulebase+'/allindex');
            }
        }
        if(moduleName == 'vehicleManagement'){

            var modulebase=baseUrl+moduleName+'/'+segment[5];
            moduleName = 'Vehicle Management';

            if(segment[5] == 'Vehicle_management' && segment[6] == 'index'){
                window.history.pushState('','',modulebase+'/vehiclelist');
            }

            if(segment[5] == 'Vehicle_management' && segment[6] == 'insurancelist'){
                window.history.pushState('','',modulebase+'/insurance');
            }
            if(segment[5] == 'Vehicle_management' && segment[6] == 'legaldocumentlist'){
                window.history.pushState('','',modulebase+'/documentlist');
            }
            if(segment[5] == 'Vehicle_management' && segment[6] == 'managereminder'){
                window.history.pushState('','',modulebase+'/allmanagereminder');
            }
        }
        if(moduleName == 'vehicleRequisition'){
            var modulebase=baseUrl+moduleName+'/'+segment[5];
            moduleName = 'Vehicle Requisition';

            if(segment[5] == 'Vehicle_requisition' && segment[6] == 'index'){
                window.history.pushState('','',modulebase+'/vreqlist');
            }
            if(segment[5] == 'Vehicle_requisition' && segment[6] == 'vehicleroute'){
                window.history.pushState('','',modulebase+'/allvehicleroute');
            }
            if(segment[5] == 'Vehicle_requisition' && segment[6] == 'approvalauthority'){
                window.history.pushState('','',modulebase+'/vapprovalauthority');
            }
            if(segment[5] == 'Vehicle_requisition' && segment[6] == 'pickdropreq'){
                window.history.pushState('','',modulebase+'/allpickdropreq');
            }
        }
        if(moduleName == 'costManagement'){
            var modulebase=baseUrl+moduleName+'/'+segment[5];
            moduleName = 'Cost & Inventory';

            if(segment[5] == 'costManagement' && segment[6] == 'index'){
                window.history.pushState('','',modulebase+'/costindex');
            }

            if(segment[5] == 'costManagement' && segment[6] == 'partslist'){
                window.history.pushState('','',modulebase+'/allpartslist');
            }
            if(segment[5] == 'costManagement' && segment[6] == 'categorylist'){
                window.history.pushState('','',modulebase+'/allcategorylist');
            }
            if(segment[5] == 'costManagement' && segment[6] == 'locationlist'){
                window.history.pushState('','',modulebase+'/alllocationlist');
            }
            if(segment[5] == 'costManagement' && segment[6] == 'stocklist'){
                window.history.pushState('','',modulebase+'/allstocklist');
            }
        }
        if(moduleName == 'dashboard'){
            var modulebase=baseUrl+moduleName+'/'+segment[5];
            window.history.pushState('','',baseUrl+moduleName+'/');

        }
        if(moduleName == 'maintenance'){
            var modulebase=baseUrl+moduleName+'/'+segment[5];

            if(segment[5] == 'Maintenance' && segment[6] == 'index'){
                window.history.pushState('','',modulebase+'/maintenanceview');
            }
            if(segment[5] == 'Maintenance' && segment[6] == 'approvalauthority'){
                window.history.pushState('','',modulebase+'/mapprovalauthority');
            }
            if(segment[5] == 'Maintenance' && segment[6] == 'maintenanceservice'){
                window.history.pushState('','',modulebase+'/allmaintenanceservice');
            }
        }
        if(moduleName == 'purchase'){
            var modulebase=baseUrl+moduleName+'/'+segment[5];

            if(segment[5] == 'Purchase' && segment[6] == 'index'){
                window.history.pushState('','',modulebase+'/allpurchaseindex');
            }

            if(segment[5] == 'Purchase' && segment[6] == 'index'){
                window.history.pushState('','',modulebase+'/allpurchaseindex');
            }

            if(segment[5] == 'Purchase' && segment[6] == 'addpurchase'){
                window.history.pushState('','',modulebase+'/add_purchaselist');
            }
            if(segment[5] == 'Purchase' && segment[6] == 'partsuse'){
                window.history.pushState('','',modulebase+'/allusagelist');
            }
            if(segment[5] == 'Purchase' && segment[6] == 'addusages'){
                window.history.pushState('','',modulebase+'/addusagesall');
            }
        }
        if(moduleName == 'refueling'){
            var modulebase=baseUrl+moduleName+'/'+segment[5];

            if(segment[5] == 'Refueling' && segment[6] == 'index'){
                window.history.pushState('','',modulebase+'/refindex');
            }

            if(segment[5] == 'Refueling' && segment[6] == 'fuelstation'){
                window.history.pushState('','',modulebase+'/allfuelstation');
            }
            if(segment[5] == 'Refueling' && segment[6] == 'refuelrequisition'){
                window.history.pushState('','',modulebase+'/allrefuelrequisition');
            }
            if(segment[5] == 'Refueling' && segment[6] == 'refuelapproval_authority'){
                window.history.pushState('','',modulebase+'/allrefuelapproval_authority');
            }
        }
        if(moduleName == 'reports'){
            var modulebase=baseUrl+moduleName+'/'+segment[5];

            if(segment[5] == 'Reports' && segment[6] == 'index'){
                window.history.pushState('','',modulebase+'/empresult');
            }
            if(segment[5] == 'Reports' && segment[6] == 'driver_reportlist'){
                window.history.pushState('','',modulebase+'/alldriver_reportlist');
            }
            if(segment[5] == 'Reports' && segment[6] == 'vehiclelist_report'){
                window.history.pushState('','',modulebase+'/allvehiclelist_report');
            }
            if(segment[5] == 'Reports' && segment[6] == 'vehicleRequisition_report'){
                window.history.pushState('','',modulebase+'/allvehicleRequisition_report');
            }
            if(segment[5] == 'Reports' && segment[6] == 'renewallist_report'){
                window.history.pushState('','',modulebase+'/allrenewallist_report');
            }
            if(segment[5] == 'Reports' && segment[6] == 'pickdropreport'){
                window.history.pushState('','',modulebase+'/allpickdropreport');
            }
            if(segment[5] == 'Reports' && segment[6] == 'refuelreqreport'){
                window.history.pushState('','',modulebase+'/allrefuelreqreport');
            }
            if(segment[5] == 'Reports' && segment[6] == 'purchase_report'){
                window.history.pushState('','',modulebase+'/allpurchase_report');
            }
            if(segment[5] == 'Reports' && segment[6] == 'expensereport'){
                window.history.pushState('','',modulebase+'/allexpensereport');
            }
            if(segment[5] == 'Reports' && segment[6] == 'maintenancereport'){
                window.history.pushState('','',modulebase+'/allmaintenancereport');
            }
        }

        if(moduleName == 'setting'){
            var modulebase=baseUrl+moduleName+'/'+segment[5];

            if(segment[5] == 'Setting' && segment[6] == 'company'){
                window.history.pushState('','',modulebase+'/allcompany');
            }
            if(segment[5] == 'Setting' && segment[6] == 'recurringperiode'){
                window.history.pushState('','',modulebase+'/allrecurringperiode');
            }
            if(segment[5] == 'Setting' && segment[6] == 'notification'){
                window.history.pushState('','',modulebase+'/allnotification');
            }
            if(segment[5] == 'Setting' && segment[6] == 'documentation'){
                window.history.pushState('','',modulebase+'/alldocumentation');
            }
            if(segment[5] == 'Setting' && segment[6] == 'vendor'){
                window.history.pushState('','',modulebase+'/allvendor');
            }
            if(segment[5] == 'Setting' && segment[6] == 'vehicletype'){
                window.history.pushState('','',modulebase+'/allvehicletype');
            }
            if(segment[5] == 'Setting' && segment[6] == 'reqpurpose'){
                window.history.pushState('','',modulebase+'/allreqpurpose');
            }
            if(segment[5] == 'Setting' && segment[6] == 'reqtype'){
                window.history.pushState('','',modulebase+'/allreqtype');
            }
            if(segment[5] == 'Setting' && segment[6] == 'reqphase'){
                window.history.pushState('','',modulebase+'/allreqphase');
            }
            if(segment[5] == 'Setting' && segment[6] == 'maintenancelist'){
                window.history.pushState('','',modulebase+'/allmaintenancelist');
            }
            if(segment[5] == 'Setting' && segment[6] == 'prioritylist'){
                window.history.pushState('','',modulebase+'/allprioritylist');
            }
            if(segment[5] == 'Setting' && segment[6] == 'servicetypelist'){
                window.history.pushState('','',modulebase+'/allservicetypelist');
            }
            if(segment[5] == 'Setting' && segment[6] == 'fueltypelist'){
                window.history.pushState('','',modulebase+'/allfueltypelist');
            }
            if(segment[5] == 'Setting' && segment[6] == 'triptypelist'){
                window.history.pushState('','',modulebase+'/alltriptypelist');
            }
            if(segment[5] == 'Setting' && segment[6] == 'divisionlist'){
                window.history.pushState('','',modulebase+'/alldivisionlist');
            }
            if(segment[5] == 'Setting' && segment[6] == 'brtaofficelist'){
                window.history.pushState('','',modulebase+'/allbrtaofficelist');
            }
            if(segment[5] == 'Setting' && segment[6] == 'ownershiplist'){
                window.history.pushState('','',modulebase+'/allownershiplist');
            }
        }

        //Employee Management Start
        if(functionName == 'create position'){ functionName = 'Position';}
        if(functionName == 'dept view'){ functionName = 'Department';}
        if(controllerName == 'Employees' && functionName == 'index'){ functionName = 'Manage Employee';}
        if(controllerName == 'Driver controller' && functionName == 'index'){ functionName = 'Manage Driver';}
        if(functionName == 'licensetypelist'){ functionName = 'Manage License';}
        if(functionName == 'performancelist'){ functionName = 'Driver Performance';}
        if(controllerName == 'Approval list' && functionName == 'index'){ functionName = 'Manage Req. Approval';}
        //Employee Management end

        //Vehicle Management Start
        if(controllerName == 'Vehicle management' && functionName == 'index'){ functionName = 'Manage Vehicle';}
        if(functionName == 'insurancelist'){ functionName = 'Insurance Details';}
        if(functionName == 'legaldocumentlist'){ functionName = 'Manage Legal Document';}
        if(functionName == 'managereminder'){ functionName = 'Reminder Details';}
        //Vehicle Management end

        //Vehicle requisition start
        if(controllerName == 'Vehicle requisition' && functionName == 'index'){ functionName = 'Manage Vehicle Requisition';}
        if(functionName ==  'vehicleroute'){ functionName = 'Vehicle Route Details';}
        if(functionName ==  'approvalauthority'){ functionName = 'Manage Approval Authority';}
        if(functionName ==  'pickdropreq'){ functionName = 'Pick & Drop Requisition List';}
        //Vehicle requisition End

        //Maintenance Start
        if(controllerName == 'Maintenance' && functionName == 'index'){ functionName = 'Maintenance Requisition List';}
        if(functionName ==  'maintenanceservice'){ functionName = 'Maintenance Service List';}
        //Maintenance End

        //Cost Inventory start
        if(controllerName == 'costManagement' && functionName == 'index'){ functionName = 'Manage Expense Type';}
        if(functionName ==  'partslist'){ functionName = 'Manage Parts';}
        if(functionName ==  'categorylist'){ functionName = 'Category';}
        if(functionName ==  'locationlist'){ functionName = 'Location';}
        if(functionName ==  'stocklist'){ functionName = 'Stock Management';}
        //Cost Inventory End

        //Purchase Start
        if(controllerName == 'Purchase' && functionName == 'index'){

            functionName = 'Purchase Details';
        }
        if(functionName ==  'addpurchase'){ functionName = 'Add Purchase';}
        if(functionName ==  'partsuse'){ functionName = 'Parts Usages List';}
        if(functionName ==  'addusages'){ functionName = 'Add Parts Usage';}
        //Purchase End

        //Refueling Start
        if(controllerName == 'Refueling' && functionName == 'index'){ functionName = 'Refueling Setting';}
        if(functionName ==  'fuelstation'){ functionName = 'Manage Fuel Station ';}
        if(functionName ==  'refuelrequisition'){ functionName = 'Refuel Requisition Details';}
        if(functionName ==  'refuelapproval authority'){ functionName = 'Manage Approval Authority';}
        //Refueling Start

        //Reports Start
        if(controllerName == 'Reports' && functionName == 'index'){ functionName = 'Employee Report';}
        if(functionName ==  'driver reportlist'){ functionName = 'Driver Report';}
        if(functionName ==  'vehiclelist report'){ functionName = 'Vehicle Report';}
        if(functionName ==  'vehicleRequisition report'){ functionName = 'Vehicle Requisition';}
        if(functionName ==  'pickdropreport'){ functionName = 'Pick & Drop Requisition';}
        if(functionName ==  'refuelreqreport'){ functionName = 'Refuel Requisition Details';}
        if(functionName ==  'purchase report'){ functionName = 'Purchase Details';}
        if(functionName ==  'expensereport'){ functionName = 'Expense Details';}
        if(functionName ==  'maintenancereport'){ functionName = 'Maintenance Req. Details';}
        if(functionName ==  'renewallist report'){ functionName = 'Renewal Report';}
        //Reports End

        //Setting Start
        if(functionName ==  'company'){ functionName = 'Manage Company';}
        if(functionName ==  'recurringperiode'){ functionName = 'Manege Recurring Period';}
        if(functionName ==  'notification'){ functionName = 'Notification';}
        if(functionName ==  'documentation'){ functionName = 'Document Type';}
        if(functionName ==  'vendor'){ functionName = 'Manage Vendor';}
        if(functionName ==  'vehicletype'){ functionName = 'Vehicle Type';}
        if(functionName ==  'reqpurpose'){ functionName = 'Requisition Purpose';}
        if(functionName ==  'reqtype'){ functionName = 'Requisition Type'}
        if(functionName ==  'reqphase'){ functionName = 'Manage Phase';}
        if(functionName ==  'maintenancelist'){ functionName = 'Maintenance Types';}
        if(functionName ==  'prioritylist'){ functionName = 'Manage Priority';}
        if(functionName ==  'servicetypelist'){ functionName = 'Service Types';}
        if(functionName ==  'fueltypelist'){ functionName = 'Fuel Types';}
        if(functionName ==  'triptypelist'){ functionName = 'Trip Details';}
        if(functionName ==  'divisionlist'){ functionName = 'Division';}
        if(functionName ==  'brtaofficelist'){ functionName = 'RTA Office Details';}
        if(functionName ==  'ownershiplist'){ functionName = 'Manage Ownership';}
        //Setting End

        functionName = functionName.toLowerCase().replace(/\b[a-z]/g, function(letter) {
            return letter.toUpperCase();
        });

        moduleName = moduleName.toLowerCase().replace(/\b[a-z]/g, function(letter) {
            return letter.toUpperCase();
        });

        $('#moduleName').html(moduleName);
        $('#moduleName1').html(moduleName);
        $('#controllerName').html(functionName);

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
    }, function(start, end, label) {
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
<script src=https://vmsdemo.bdtask-demo.com/application/modules/costManagement/assets/js/script.js type="text/javascript"></script><script src=https://vmsdemo.bdtask-demo.com/application/modules/employeeManagement/assets/js/script.js type="text/javascript"></script><script src=https://vmsdemo.bdtask-demo.com/application/modules/maintenance/assets/js/script.js type="text/javascript"></script><script src=https://vmsdemo.bdtask-demo.com/application/modules/purchase/assets/js/script.js type="text/javascript"></script><script src=https://vmsdemo.bdtask-demo.com/application/modules/refueling/assets/js/script.js type="text/javascript"></script><script src=https://vmsdemo.bdtask-demo.com/application/modules/reports/assets/js/script.js type="text/javascript"></script><script src=https://vmsdemo.bdtask-demo.com/application/modules/setting/assets/js/script.js type="text/javascript"></script><script src=https://vmsdemo.bdtask-demo.com/application/modules/vehicleManagement/assets/js/script.js type="text/javascript"></script><script src=https://vmsdemo.bdtask-demo.com/application/modules/vehicleRequisition/assets/js/script.js type="text/javascript"></script></body>
</html>
