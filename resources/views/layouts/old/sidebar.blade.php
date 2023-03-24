@php use Illuminate\Support\Facades\Auth; @endphp
<nav class="sidebar sidebar-bunker">
    <div class="sidebar-header">
        <a href="{{route('home')}}" class="logo" style="width: 100%; text-align: center;" >
            <img
                src="{{asset('assets/dist/img/icons/zesco_logo.png')}}"
                alt="">
        </a>
    </div>

    <div class="profile-element d-flex align-items-center flex-shrink-0">
        <div class="avatar online">
            <img src="{{asset('assets/dist/img/avatar.png')}}"
                 class="img-fluid rounded-circle"
                 alt="">
        </div>
        <div class="profile-text">
            <h6 class="m-0">{{Auth::user()->name}}</h6>
        </div>
    </div>

    <div class="sidebar-body">
        <nav class="sidebar-nav">
            <ul class="metismenu">
                <li class="mm-active">
                    <a href="{{route('home')}}">
                        <i class="typcn typcn-home-outline mr-2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="" style="display: none;">
                    <a class="has-arrow material-ripple" href="#">
                        <i class='typcn icon-default typcn-group-outline mr-2'></i>
                        Employee Management
                    </a>
                    <ul class="nav-second-level ">

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/index')">
                                Manage Employee
                            </a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/create_position')">Position</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/employeeManagement/Department_controller/dept_view')">Department</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/employeeManagement/Driver_controller/index')">Manage
                                Driver</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/employeeManagement/Driver_controller/licensetypelist')">Manage
                                License</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/employeeManagement/Driver_controller/performancelist')">Driver
                                Performance</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/employeeManagement/Approval_list/index')">Manage
                                Req. Approval</a>
                        </li>

                    </ul>
                </li>


                <li class="">
                    <a class="has-arrow material-ripple" href="#">
                        <i class='typcn icon-default typcn-social-dribbble mr-2'></i>
                        Vehicle Management
                    </a>
                    <ul class="nav-second-level ">
                        <li class="">
                            <a style="cursor:pointer;"
                               href="{{route('index')}}"
                               data-action="navigate">
                                Manage Vehicle
                            </a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               href="{{route('insurancelist')}}">
                                Insurance
                                Details
                            </a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               href="{{route('legaldocumentlist')}}">
                                Manage Legal Document
                            </a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                            href="">
                                Reminder
                                Details</a>
                        </li>

                    </ul>
                </li>


                <li class="" style="display: none;">
                    <a class="has-arrow material-ripple" href="#"><i
                            class='typcn icon-default typcn-eject-outline mr-2'></i>Vehicle Requisition</a>
                    <ul class="nav-second-level ">


                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/vehicleRequisition/Vehicle_requisition/index')">Manage
                                Vehicle Requisition</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/vehicleRequisition/Vehicle_requisition/vehicleroute')">Vehicle
                                Route Details</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/vehicleRequisition/Vehicle_requisition/approvalauthority')">Manage
                                Approval Authority</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/vehicleRequisition/Vehicle_requisition/pickdropreq')">Pick
                                & Drop Requisition List</a>
                        </li>

                    </ul>
                </li>


                <li class="" style="display: none;">
                    <a class="has-arrow material-ripple" href="#"><i
                            class='typcn icon-default typcn-compass mr-2'></i>Maintenance</a>
                    <ul class="nav-second-level ">


                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/maintenance/Maintenance/index')">Maintenance
                                Req. Details</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/maintenance/Maintenance/approvalauthority')">Manage
                                Approval Authority</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/maintenance/Maintenance/maintenanceservice')">Maintenance
                                Service List</a>
                        </li>

                    </ul>
                </li>


                <li class="" style="display: none;">
                    <a class="has-arrow material-ripple" href="#"><i
                            class='typcn icon-default typcn-ticket mr-2'></i>Cost & Inventory</a>
                    <ul class="nav-second-level ">


                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/costManagement/costManagement/index')">Manage
                                Expense Type</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/costManagement/costManagement/partslist')">Manage
                                Parts</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/costManagement/costManagement/categorylist')">Category</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/costManagement/costManagement/locationlist')">Location</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/costManagement/costManagement/stocklist')">Stock
                                Management</a>
                        </li>

                    </ul>
                </li>


                <li class="" style="display: none;">
                    <a class="has-arrow material-ripple" href="#"><i
                            class='typcn icon-default typcn-ticket mr-2'></i>Purchase & Usage</a>
                    <ul class="nav-second-level ">


                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/purchase/Purchase/index')">Purchase
                                Details</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/purchase/Purchase/addpurchase')">Add
                                Purchase</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/purchase/Purchase/partsuse')">Parts
                                Usages List</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/purchase/Purchase/addusages')">Add
                                Parts Usage</a>
                        </li>

                    </ul>
                </li>


                <li class="" style="display: none;">
                    <a class="has-arrow material-ripple" href="#"><i
                            class='typcn icon-default typcn-arrow-sync-outline mr-2'></i>Refueling</a>
                    <ul class="nav-second-level ">


                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/refueling/Refueling/index')">Refuel
                                Setting</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/refueling/Refueling/fuelstation')">Manage
                                Fuel Station </a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/refueling/Refueling/refuelrequisition')">Refuel
                                Requisition Details</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/refueling/Refueling/refuelapproval_authority')">Manage
                                Approval Authority</a>
                        </li>

                    </ul>
                </li>


                <li class="" style="display: none;">
                    <a class="has-arrow material-ripple" href="#"><i
                            class='typcn icon-default typcn-chart-area-outline mr-2'></i>Reports</a>
                    <ul class="nav-second-level ">


                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/reports/Reports/index')">Employee
                                Report</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/reports/Reports/driver_reportlist')">Driver
                                Report</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/reports/Reports/vehiclelist_report')">Vehicle
                                Report</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/reports/Reports/vehicleRequisition_report')">Vehicle
                                Requisition</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/reports/Reports/renewallist_report')">Renewal
                                Report</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/reports/Reports/pickdropreport')">Pick
                                & Drop Req. List</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/reports/Reports/refuelreqreport')">Refuel
                                Requisition Details</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/reports/Reports/purchase_report')">Purchase
                                Details</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/reports/Reports/expensereport')">Expense
                                Details</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/reports/Reports/maintenancereport')">Maintenance
                                Req. Details</a>
                        </li>

                    </ul>
                </li>


                <li class="" style="display: none;">
                    <a class="has-arrow material-ripple" href="#"><i
                            class='typcn icon-default typcn-cog-outline mr-2'></i>Setting</a>
                    <ul class="nav-second-level ">


                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/company')">Manage
                                Company</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/recurringperiode')">Manege
                                Recurring Period</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/notification')">Notification</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/documentation')">Document
                                Type</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/vendor')">Manage
                                Vendor</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/vehicletype')">Vehicle
                                Type</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/reqpurpose')">Requisition
                                Purpose</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/reqtype')">Requisition
                                Type</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/reqphase')">Manage
                                Phase</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/maintenancelist')">Maintenance
                                Types</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/prioritylist')">Manage
                                Priority</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/servicetypelist')">Service
                                Types</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/fueltypelist')">Fuel
                                Types</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/triptypelist')">Trip
                                Details</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/divisionlist')">Division</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/brtaofficelist')">RTA
                                Office Details</a>
                        </li>

                        <li class="">
                            <a style="cursor:pointer;"
                               onclick="pageopen('https://vmsdemo.bdtask-demo.com/setting/Setting/ownershiplist')">Manage
                                Ownership</a>
                        </li>

                    </ul>
                </li>


                <li class="nav-label" style="display: none;">Admin menu</li>
                <li class="" style="display: none;">
                    <a class="has-arrow material-ripple" href="#">
                        <i class="typcn icon-default typcn-edit  mr-2"></i> User </a>
                    <ul class="nav-second-level ">
                        <li class=""><a href="https://vmsdemo.bdtask-demo.com/dashboard/user/form">Add User</a>
                        </li>
                        <li class=""><a href="https://vmsdemo.bdtask-demo.com/dashboard/user/index">Manage User</a>
                        </li>
                    </ul>
                </li>
                <li class="" style="display: none;">
                    <a class="has-arrow material-ripple" href="#">
                        <i class="typcn icon-default typcn-edit  mr-2"></i>
                        Role Permission </a>
                    <ul class="nav-second-level ">
                        <li class="">
                            <a href="https://vmsdemo.bdtask-demo.com/dashboard/role/create_system_role">Assign
                                role</a>
                        </li>
                        <li class=""><a href="https://vmsdemo.bdtask-demo.com/dashboard/role/role_list">Manage
                                Role</a>
                        </li>
                        <li class="">
                            <a href="https://vmsdemo.bdtask-demo.com/dashboard/role/user_access_role">User Access
                                Role Details</a>
                        </li>
                    </ul>
                </li>
                <li class="" style="display: none;">
                    <a href="https://vmsdemo.bdtask-demo.com/dashboard/language"><i
                            class="typcn icon-default typcn-flag-outline mr-2"></i>
                        Language</a>
                </li>
                <li class="" style="display: none;">
                    <a href="https://vmsdemo.bdtask-demo.com/dashboard/setting"><i
                            class="typcn icon-default typcn-cog-outline mr-2"></i>
                        Application Setting</a>
                </li>
            </ul>
        </nav>
    </div>
</nav>
