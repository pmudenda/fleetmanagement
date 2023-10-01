@php use Illuminate\Support\Facades\Auth; @endphp
<aside class="main-sidebar sidebar-dark-primary sidebar-collapse elevation-4">

    <a href="{{ URL::signedRoute('home') }}" class="brand-link" style="background: var(--bs-zesco-secondary)">
        <img class="h-45px app-sidebar-logo-default brand-image img-circle elevation-3"
             style="opacity: .8"
             src="{{ asset('assets/dist/img/icons/zesco_logo.png') }}"
             alt=""/>
        <span class="brand-text font-weight-light">
            {{ config('app.sys_name', 'Fleet Master') }}</span>
    </a>

    <div class="sidebar">

        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block">{{Auth::user()->name}}</a>
            </div>
        </div>

        <div class="form-inline">
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @php
                    $vehicleManagementPermissions = [
                        config('rights.view_vehicle_details'),
                        config('rights.view_vehicle_docs'),
                        config('rights.on_board_vehicle'),
                        config('rights.view_fleet'),
                        config('rights.edit_vehicle_details'),
                        config('rights.view_odometer_logs'),
                        config('rights.add_odometer_logs'),
                        config('rights.manage_tom_card'),

                        ];
                @endphp
                @canany($vehicleManagementPermissions)
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-truck-pickup" style="font-size: 20px;"></i>
                            <p>
                                Vehicles Management
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can(config('rights.on_board_vehicle'))
                                <li class="nav-item pl-2">
                                    <a href="{{ URL::signedRoute('new.vehicle') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>New Vehicle</p>
                                    </a>
                                </li>
                            @endcan

                            @canany([
                                    config('rights.view_vehicle_details'),
                                    config('rights.edit_vehicle_details'),
                                    config('rights.view_fleet'),
                                ])
                                <li class="nav-item pl-2">
                                    <a href="{{ URL::signedRoute('vehicles.list') }}"
                                       class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Vehicle List</p>
                                    </a>
                                </li>
                            @endcanany

                            @canany([
                               config('rights.view_odometer_logs'),
                               config('rights.add_odometer_logs')
                           ])
                                <li class="nav-item pl-2">
                                    <a href="{{ URL::signedRoute('new.fleet.movement') }}"
                                       class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Odometer Logs</p>
                                    </a>
                                </li>
                            @endcanany

                            @can(config('rights.manage_tom_card'))
                                <li class="nav-item pl-2">
                                    <a href="{{ URL::signedRoute('assign.tom.card') }}"
                                       class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Tom Card Management</p>
                                    </a>
                                </li>
                            @endcan

                            <li class="nav-item d-none">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-circle"></i>
                                    <p>
                                        Gate Pass
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="" class="nav-link">
                                            <i class="fas fa-plus nav-icon"></i>
                                            <p>
                                                New
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endcanany

                @php
                    $requisitionsPermissions = [
                        config('rights.view_fuel'),
                        config('rights.requisition_fuel'),
                        config('rights.set_vehicle_fuel_allocation'),
                        config('rights.approve_fuel_requisition')
                    ];
                @endphp
                @canany($requisitionsPermissions)
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-gas-pump" style="font-size: 20px;"></i>
                            <p>
                                Fuel Management
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview pl-3">
                            @canany([
                                config('rights.set_vehicle_fuel_allocation'),
                                config('rights.view_fuel'),
                                config('rights.requisition_fuel'),
                                config('rights.approve_fuel_requisition')])
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Fuel Requisitions
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview pl-4">
                                        @can(config('rights.requisition_fuel'))
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                   href="{{ route('new.fuel.requisition') }}">
                                                    <i class="fas fa-plus nav-icon"></i>
                                                    <p>New</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @canany(
                                        [
                                            config('rights.view_fuel'),
                                            config('rights.requisition_fuel'),
                                            config('rights.approve_fuel_requisition')
                                        ])
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                   data-href="{{ URL::signedRoute('list.fuel.requisition') }}"
                                                   href="{{ route('list.fuel.requisition') }}"
                                                >
                                                    <i class="fas fa-list nav-icon"></i>
                                                    <p>List</p>
                                                </a>
                                            </li>
                                        @endcanany
                                    </ul>
                                </li>
                            @endcanany

                            @can(config('rights.set_vehicle_fuel_allocation'))
                                <li class="nav-item">
                                    <a class="nav-link"
                                       href="{{ URL::signedRoute('vehicle.fuel.allocation') }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Fuel Allocation</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @php
                    $workshopPermissions = [
                        config('rights.create_job_card'),
                        config('rights.view_job_card'),
                        config('rights.approve_workshop_requisition'),
                        config('rights.requisition_spares'),
                    ];
                @endphp

                @canany($workshopPermissions)
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M20 14H18V10H20C20.6 10 21 10.4
                                            21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21
                                            16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21
                                            19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8
                                            21 7.6 21 7Z"
                                        fill="currentColor"></path>
                                    <path opacity="0.3"
                                          d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3
                                          2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17
                                          22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10
                                          11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3
                                          16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1
                                          12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999
                                          15.3 6.19999 16 7.39999 16H13.3Z"
                                          fill="currentColor"></path>
                                </svg>
                            </span>
                        </span>
                            <p>
                                Maintenance
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            @canany([config('rights.create_job_card'), config('rights.view_job_card')])
                                <li class="nav-item pl-2">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Job Card
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview pl-2">
                                        @canany([config('rights.create_job_card')])
                                            <li class="nav-item">
                                                <a href="{{URL::signedRoute('workshop.checkin')}}"
                                                   class="nav-link">
                                                    <i class="fas fa-plus nav-icon"></i>
                                                    <p>New (Job Card)</p>
                                                </a>
                                            </li>
                                        @endcanany

                                        @can(config('rights.view_job_card'))
                                            <li class="nav-item pl-2">
                                                <a href="{{URL::signedRoute('jobCard.list')}}"
                                                   class="nav-link">
                                                    <i class="fas fa-list nav-icon"></i>
                                                    <p>
                                                        View (Open Cards)
                                                    </p>
                                                </a>
                                            </li>

                                            <li class="nav-item pl-2">
                                                <a href="{{URL::signedRoute('closed.jobCard.list')}}"
                                                   class="nav-link">
                                                    <i class="fas fa-list nav-icon"></i>
                                                    <p>
                                                        View (Closed Cards)
                                                    </p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany

                            @canany([config('rights.material_booking')])
                                <li class="nav-item pl-2">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Booking
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview pl-2">
                                        <li class="nav-item">
                                            <a class="nav-link"
                                               href="{{ URL::signedRoute('new.booking') }}">
                                                <i class="fas fa-plus nav-icon"></i>
                                                <p>New</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endcanany

                            @canany([config('rights.approve_workshop_requisition'),
                                config('rights.view_workshop_requisition')])
                                <li class="nav-item pl-2">
                                    <a class="nav-link" href="{{URL::signedRoute('list.workshop.requisition') }}">
                                        <i class="fas fa-list nav-icon"></i>
                                        <p>Requisitions</p>
                                    </a>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany

                @php
                    $userManagementPermissions = [
                        config('rights.add_user'),
                        config('rights.view_user_detail'),
                        config('rights.view_user'),
                        config('rights.user_attach'),
                        config('rights.add_driver'),
                        config('rights.view_drivers'),
                        config('rights.view_mechanics'),
                        config('rights.add_mechanic')
                    ];
                @endphp

                @canany($userManagementPermissions)
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users" style="font-size: 20px;"></i>
                            <p>
                                Employee Management
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview pl-3">
                            @canany([config('rights.add_user'),config('rights.view_user')])
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-users"></i>
                                        <p>
                                            Users
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview pl-3">

                                        @canany([config('rights.add_user')])
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{URL::signedRoute('users.new')}}">
                                                    <i class="fas fa-user-plus nav-icon"></i>
                                                    <p class="menu-title">Add</p>
                                                </a>
                                            </li>
                                        @endcanany
                                        @canany([config('rights.view_user')])
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{URL::signedRoute('users.list')}}">
                                                    <i class="fas fa-users nav-icon"></i>
                                                    <p>List</p>
                                                </a>
                                            </li>
                                        @endcanany
                                    </ul>
                                </li>
                            @endcanany

                            @canany([config('rights.add_driver'),config('rights.view_drivers')])
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-users"></i>
                                        <p>
                                            Drivers
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview pl-3">
                                        @can(config('rights.add_driver'))
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ URL::signedRoute('driver.create') }}">
                                                    <i class="fas fa-user-plus nav-icon"></i>
                                                    <p>
                                                        Register
                                                    </p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can(config('rights.view_drivers'))
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ URL::signedRoute('driver.list') }}">
                                                    <i class="fas fa-users nav-icon"></i>
                                                    <p>
                                                        List
                                                    </p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany

                            @canany([
                                config('rights.add_mechanic'),
                                config('rights.view_mechanics'),
                                config('rights.view_mechanic')
                                ])
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-users"></i>
                                        <p>
                                            Mechanics
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview pl-3">
                                        @can(config('rights.add_mechanic'))
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ URL::signedRoute('mechanic.create') }}">
                                                    <i class="fas fa-user-plus nav-icon"></i>
                                                    <p>
                                                        Add
                                                    </p>
                                                </a>
                                            </li>
                                        @endcan
                                        @canany(config('rights.view_mechanics'), config('rights.view_mechanic'))
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ URL::signedRoute('mechanic.list') }}">
                                                    <i class="fas fa-users nav-icon"></i>
                                                    <p>
                                                        List
                                                    </p>
                                                </a>
                                            </li>
                                        @endcanany
                                    </ul>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany

                @php
                    $profileRights =
                    [
                        config('rights.role_create'),
                        config('rights.role_access'),
                        config('rights.role_show'),
                        config('rights.role_edit'),
                        config('rights.role_destroy'),
                        config('rights.role_attach'),
                        config('rights.role_detach')
                    ];
                    $permissionRights = [
                        config('rights.permission_access'),
                        config('rights.permission_show'),
                        config('rights.permission_edit'),
                        config('rights.permission_destroy'),
                        config('rights.permission_create'),
                        config('rights.permission_attach'),
                        config('rights.permission_revoke')
                    ];
                    $securityPermissions = array_merge($profileRights, $permissionRights);
                @endphp
                @canany($securityPermissions)
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-shield-alt " style="font-size: 20px;"></i>
                            <p>
                                Security
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview pl-3">
                            <li class="nav-item">
                                @canany($profileRights)
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-user-shield nav-icon"></i>
                                        <p>
                                            Profiles
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview pl-4">
                                        @canany($profileRights)
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ URL::signedRoute('roles.index') }}">
                                                    <i class="fas fa-list nav-icon"></i>
                                                    <p>List</p>
                                                </a>
                                            </li>
                                        @endcanany
                                    </ul>
                                @endcanany
                            </li>

                            @canany($permissionRights)
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-user-secret nav-icon"></i>
                                        <p>
                                            Permissions
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview pl-4">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ URL::signedRoute('permissions.index') }}">
                                                <i class="fa fa-list nav-icon"></i>
                                                <p class="menu-title">
                                                    Permission List
                                                </p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany

                @php
                    $configurationsPermissions  = [
                        config('rights.add_vehicle_brand'),
                        config('rights.add_vehicle_model'),
                        config('rights.add_vehicle_type'),
                        config('rights.set_vehicle_fuel_allocation'),
                        config('rights.create_veh_accessories'),
                        config('rights.create_veh_charge_out_rate'),
                        config('rights.add_workshop_section'),
                        config('rights.edit_workshop_section'),
                        config('rights.view_workshop_section'),
                        config('rights.add_workshop'),
                        config('rights.edit_workshop'),
                        config('rights.view_workshop'),
                        config('rights.add_license_class'),
                        config('rights.add_accident_nature')
                    ];
                @endphp
                @canany($configurationsPermissions)
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17.5 11H6.5C4 11 2 9 2
                                            6.5C2 4 4 2 6.5 2H17.5C20 2 22 4
                                            22 6.5C22 9 20 11 17.5 11ZM15
                                            6.5C15 7.9 16.1 9 17.5 9C18.9 9
                                            20 7.9 20 6.5C20 5.1 18.9
                                            4 17.5 4C16.1 4 15 5.1 15 6.5Z"
                                        fill="currentColor"></path>
                                    <path opacity="0.3"
                                          d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15
                                          4 13 6.5 13H17.5C20 13 22 15 22 17.5C22
                                          20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5
                                          20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5
                                          15C5.1 15 4 16.1 4 17.5Z"
                                          fill="currentColor"></path>
                                </svg>
                            </span>
                        </span>
                            <p>
                                Configurations
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            @can(config('rights.create_veh_accessories'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ URL::signedRoute('vehicle.accessories') }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="menu-title">
                                            Accessories
                                        </p>
                                    </a>
                                </li>
                            @endcan

                            @can(config('rights.create_veh_charge_out_rate'))
                                <li class="nav-item">
                                    <a class="nav-link"
                                       href="{{ URL::signedRoute('charge.out.rate') }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Charge Out Rates
                                        </p>
                                    </a>
                                </li>
                            @endcan

                            @canany(config('rights.add_vehicle_brand'),
                                        config('rights.add_vehicle_model'),
                                        config('rights.add_vehicle_type'),
                                        config('rights.add_license_class'),
                                        config('rights.add_accident_nature'),
                                        config('rights.add_workshop'),
                                        config('rights.edit_workshop'),
                                        config('rights.view_workshop'))
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            General Tables
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview pl-2">
                                        @canany([config('rights.add_vehicle_brand'),
                                        config('rights.add_vehicle_model'),
                                        config('rights.add_vehicle_type')])
                                            <li class="nav-item">
                                                <a href="#" class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>
                                                        Vehicle
                                                        <i class="right fas fa-angle-left"></i>
                                                    </p>
                                                </a>
                                                <ul class="nav nav-treeview pl-2">

                                                    <!--Define Vehicle Brands-->
                                                    @canany([config('rights.add_vehicle_brand')])
                                                        <li class="nav-item">
                                                            <a class="nav-link"
                                                               href="{{ URL::signedRoute('vehicle.make') }}">
                                                            <span class="menu-bullet">
                                                                <span class="bullet bullet-dot">
                                                                </span>
                                                            </span>
                                                                <span class="menu-title">
                                                            Make (Brand)
                                                        </span>
                                                            </a>
                                                        </li>
                                                    @endcanany

                                                    @canany([config('rights.add_vehicle_model')])
                                                        <li class="nav-item">
                                                            <a class="nav-link"
                                                               href="{{ URL::signedRoute('vehicle.models') }}">
                                                            <span class="menu-bullet">
                                                                <span class="bullet bullet-dot">
                                                                </span>
                                                            </span>
                                                                <span class="menu-title">
                                                            Models
                                                        </span>
                                                            </a>
                                                        </li>
                                                    @endcanany

                                                    <!--Define Vehicle Body Types-->
                                                    @canany([config('rights.add_vehicle_type')])
                                                        <li class="nav-item">
                                                            <a class="nav-link"
                                                               href="{{ URL::signedRoute('vehicle.body.types') }}">
                                                            <span class="menu-bullet">
                                                                <span class="bullet bullet-dot">
                                                                </span>
                                                            </span>
                                                                <p class="menu-title">
                                                                    Body Types
                                                                </p>
                                                            </a>
                                                        </li>
                                                    @endcanany

                                                </ul>
                                            </li>
                                        @endcanany

                                        @canany([config('rights.add_workshop'),
                                            config('rights.edit_workshop'),
                                            config('rights.view_workshop')])
                                            <li class="nav-item">
                                                <a href="#" class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>
                                                        Workshop Directory
                                                        <i class="right fas fa-angle-left"></i>
                                                    </p>
                                                </a>
                                                <ul class="nav nav-treeview pl-2">
                                                    @canany([config('rights.add_workshop'),
                                                        config('rights.edit_workshop'),
                                                        config('rights.view_workshop')])
                                                        <li class="nav-item">
                                                            <a class="nav-link"
                                                               href="{{ URL::signedRoute('workshop.sections') }}">
                                                                <i class="far fa-circle nav-icon"></i>
                                                                <p>Workshop Sections</p>
                                                            </a>
                                                        </li>
                                                    @endcanany

                                                    @canany([config('rights.add_workshop'),
                                                        config('rights.edit_workshop'),
                                                        config('rights.view_workshop')])
                                                        <li class="nav-item">
                                                            <a href="{{ URL::signedRoute('workshop.list') }}"
                                                               class="nav-link">
                                                                <i class="far fa-circle nav-icon"></i>
                                                                <p>Workshop</p>
                                                            </a>
                                                        </li>
                                                    @endcanany

                                                    <li class="nav-item d-none">
                                                        <a href="{{ URL::signedRoute('workshop.list') }}"
                                                           class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>External Garages</p>
                                                        </a>
                                                    </li>

                                                </ul>
                                            </li>
                                        @endcanany

                                        @canany([config('rights.add_license_class'),
                                                config('rights.edit_license_class')])
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                   href="{{ URL::signedRoute('configuration.general.table',
                                                            ['ref'=>'driver-license-class'])
                                                          }}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p class="menu-title">
                                                        Driver License Class
                                                    </p>
                                                </a>
                                            </li>
                                        @endcanany

                                        @canany([config('rights.add_accident_nature'),
                                                config('rights.update_accident_nature')])
                                            <li class="nav-item">
                                                <a href="#" class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>
                                                        Accidents
                                                        <i class="right fas fa-angle-left"></i>
                                                    </p>
                                                </a>
                                                <ul class="nav nav-treeview pl-2">
                                                    @canany([config('rights.add_accident_nature'),
                                                        config('rights.update_accident_nature')])
                                                        <li class="nav-item">
                                                            <a class="nav-link"
                                                               href="{{ URL::signedRoute('configuration.general.table',
                                                                ['ref'=>'accident-nature']) }}">
                                                                <i class="fas fa-car-burst"></i>
                                                                <p class="menu-title">
                                                                    Accident Natures
                                                                </p>
                                                            </a>
                                                        </li>
                                                    @endcanany

                                                    @canany([config('rights.add_accident_type'),
                                                        config('rights.add_accident_type')])
                                                        <li class="nav-item">
                                                            <a class="nav-link"
                                                               href="{{ URL::signedRoute('configuration.general.table',
                                                                ['ref'=>'accident-types']) }}">
                                                                <i class="fas fa-car-crash"></i>
                                                                <p class="menu-title">
                                                                    Accident Types
                                                                </p>
                                                            </a>
                                                        </li>
                                                    @endcanany
                                                </ul>
                                            </li>
                                        @endcanany

                                        @canany([config('rights.update_fuel_level'),config('rights.update_fuel_level')])
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                   href="{{ URL::signedRoute('configuration.general.table',
                                                        ['ref'=>'fuel-levels']) }}">
                                                    <i class="fas fa-gas-pump"></i>
                                                    <p class="menu-title">
                                                        Fuel Level
                                                    </p>
                                                </a>
                                            </li>
                                        @endcanany

                                        @canany([config('rights.add_general_status'),
                                                config('rights.add_general_status')])
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                   href="{{ URL::signedRoute('configuration.general.table',
                                                    ['ref'=>'general-status']) }}">
                                                    <p class="menu-title">
                                                        General Statuses
                                                    </p>
                                                </a>
                                            </li>
                                        @endcanany

                                        @canany(config('rights.update_insurance_company'),
                                            config('rights.update_insurance_company'))
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                   href="{{ URL::signedRoute('configuration.general.table',
                                                    ['ref'=>'insurance-company']) }}">
                                                    <p class="menu-title">
                                                        Insurance Company
                                                    </p>
                                                </a>
                                            </li>
                                        @endcanany

                                        @canany([config('rights.update_insurance_types'),
                                            config('rights.update_insurance_types')])
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                   href="{{ URL::signedRoute('configuration.general.table',
                                                    ['ref'=>'insurance-types']) }}">
                                                    <p class="menu-title">
                                                        Insurance Types
                                                    </p>
                                                </a>
                                            </li>
                                        @endcanany

                                        @canany([config('rights.add_general_table_data')])
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                   href="{{ URL::signedRoute('configuration.general.table',
                                                   ['ref'=>'insurance-sub-types']) }}">
                                                    <p class="menu-title">
                                                        Insurance Sub Types
                                                    </p>
                                                </a>
                                            </li>
                                        @endcanany

                                        @canany([config('rights.add_repair_type'), config('rights.update_repair_type')])
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                   href="{{ URL::signedRoute('configuration.general.table',
                                                        ['ref'=>'repair-category']) }}">
                                                    <p class="menu-title">
                                                        Repair Types
                                                    </p>
                                                </a>
                                            </li>
                                        @endcanany

                                        @canany([config('rights.add_store_movement'),
                                                config('rights.edit_store_movement')])
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                   href="{{URL::signedRoute('configuration.general.table',
                                                    ['ref'=>'store-movement-type']) }}">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot">
                                                    </span>
                                                </span>

                                                    <p class="menu-title">
                                                        Stores Movement Types
                                                    </p>
                                                </a>
                                            </li>
                                        @endcanany

                                        @canany([config('rights.add_vehicle_status'),
                                                config('rights.edit_vehicle_status')])
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                   href="{{ URL::signedRoute('configuration.general.table',
                                                    ['ref'=>'vehicle-status']) }}">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot">
                                                    </span>
                                                </span>
                                                    <p class="menu-title">
                                                        Vehicle Status
                                                    </p>
                                                </a>
                                            </li>
                                        @endcanany
                                    </ul>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany

                @php
                    $workflowPermissions  = [
                        config('rights.manage_tasks'),
                        config('rights.view_tasks')
                    ];
                @endphp
                @canany($workflowPermissions)
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-code-fork nav-icon" style="font-size: 20px;"></i>
                            <p>
                                Workflow & Tasks
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @canany($workflowPermissions)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ URL::signedRoute('workflow.task') }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p class="menu-title">
                                            Tasks
                                        </p>
                                    </a>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany

                @canany([
                    config('rights.add_accident_report'),
                    config('rights.view_accident_report')]
                    )
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-car-crash nav-icon" style="font-size: 20px;"></i>
                            <p>
                                Accidents
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview pl-3">
                            <li class="nav-item">
                                <a href="{{URL::signedRoute('accident.reporting')}}"
                                   class="nav-link">
                                    <i class="fas fa-plus nav-icon" style="font-size: 20px;"></i>
                                    <p>
                                        Report
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{URL::signedRoute('accident.list')}}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>List</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcanany

                <li class="nav-item d-none">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-bell" style="font-size: 20px;"></i>
                        <p>
                            Reminders
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        {{--@can(config('rights.on_board_vehicle'))--}}
                        <li class="nav-item pl-2">
                            <a href="{{ URL::signedRoute('reminder.renewal.new') }}" class="nav-link">
                                <i class="fas fa-plus nav-icon"></i>
                                <p>Renewal Reminders</p>
                            </a>
                        </li>

                        <li class="nav-item pl-2">
                            <a href="{{ URL::signedRoute('reminder.service.new') }}" class="nav-link">
                                <i class="fas fa-plus nav-icon"></i>
                                <p>Service Reminders</p>
                            </a>
                        </li>
                        {{-- @endcan--}}


                        {{--@canany(config('rights.view_vehicle_details'), config('rights.edit_vehicle_details'))--}}
                        <li class="nav-item pl-2">
                            <a href="{{ URL::signedRoute('reminder.list') }}" class="nav-link">
                                <i class="far fa-list nav-icon"></i>
                                <p>Resolve Reminder</p>
                            </a>
                        </li>
                        {{--@endcanany--}}
                    </ul>
                </li>

                @canany([
                        config('rights.add_toll_card'),
                        config('rights.view_toll_card'),
                        config('rights.update_toll_card')
                    ])
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-credit-card" style="font-size: 20px;"></i>
                            <p>
                                e-Toll Cards
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview pl-3">

                            <li class="nav-item">
                                <a href="{{URL::signedRoute('e-toll.card.report')}}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Usage</p>
                                </a>
                            </li>

                            @can(config('rights.add_toll_card'))
                                <li class="nav-item">
                                    <a href="{{URL::signedRoute('e-toll.card')}}"
                                       class="nav-link">
                                        <i class="fas fa-plus nav-icon"></i>
                                        <p>
                                            Add
                                        </p>
                                    </a>
                                </li>
                            @endcan

                            <li class="nav-item">
                                <a href="{{URL::signedRoute('e-toll.card.transaction')}}"
                                   class="nav-link">
                                    <i class="fas fa-plus nav-icon"></i>
                                    <p>
                                        Transactions
                                    </p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endcanany

                @php
                    $reportsPermissions  = [
                        config('rights.access_reports'),
                        config('rights.access_fuel_reports'),
                        config('rights.access_vehicle_status_reports'),
                        config('rights.access_maintenance_reports'),
                        ];
                @endphp
                @canany($reportsPermissions)
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chart-bar" style="font-size: 20px;"></i>
                            <p>
                                Reports
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview pl-3">
                            @canany(config('rights.access_reports'),
                                                    config('rights.access_fuel_reports'))
                                <li class="nav-item">
                                    <a href="{{URL::signedRoute('reports.fuel.requisitions')}}"
                                       class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Fuel
                                        </p>
                                    </a>
                                </li>
                            @endcanany

                            @canany(config('rights.access_reports'),
                                config('rights.access_vehicle_status_reports'))
                                <li class="nav-item">
                                    <a href="{{
                                        URL::signedRoute('reports.vehicle.status',
                                        ['step'=> 1]
                                    )}}"
                                       class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Vehicles</p>
                                    </a>
                                </li>
                            @endcanany

                        </ul>
                    </li>
                @endcanany
            </ul>
        </nav>

    </div>

</aside>
