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
            {{--<div class="input-group"
                 data-widget="sidebar-search">
                <input class="form-control form-control-sidebar"
                       type="search"
                       placeholder="Search"
                       aria-label="Search">
                <div class="input-group-addon">
                    <button
                        class="btn btn-sm btn-sidebar border-radius-0">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>--}}
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                @php
                    $vehicleManagementPermissions = [
                        config('rights.view_vehicle_details'),
                        config('rights.view_vehicle_docs'),
                        config('rights.on_board_vehicle'),
                        config('rights.edit_vehicle_details'),
                        ];
                @endphp
                {{auth()->user()->can(config('rights.permission_revoke'))}}
                @canany($vehicleManagementPermissions)
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-truck-pickup" style="font-size: 20px;"></i>
                            <p>
                                Vehicle Management
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

                            {{--@can([config('rights.view_vehicle_details'), config('rights.edit_vehicle_details')])
                                <li class="nav-item">
                                    <a href="{{ URL::signedRoute('view.vehicle.detail') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Vehicle Details</p>
                                    </a>
                                </li>
                            @endcan--}}

                            @canany([config('rights.view_vehicle_details'), config('rights.edit_vehicle_details')])
                                <li class="nav-item pl-2">
                                    <a href="{{ URL::signedRoute('vehicles.list') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Vehicle List</p>
                                    </a>
                                </li>
                            @endcanany

                            @can('CleanUpData')
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-circle"></i>
                                        <p>
                                            Data Clean Up
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview pl-3">
                                        <li class="nav-item">
                                            <a href="{{URL::signedRoute('vehicle.data.cleanup')}}" class="nav-link">
                                                <i class="fas fa-circle nav-icon"></i>
                                                <p>
                                                    By Vehicle Reg.
                                                </p>
                                            </a>

                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ URL::signedRoute('vehicle.migration.list') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>By User Unit</p>
                                            </a>
                                        </li>
                                    </ul>
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

                <li class="nav-item">
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


                        {{--@canany([config('rights.view_vehicle_details'), config('rights.edit_vehicle_details')])--}}
                            <li class="nav-item pl-2">
                                <a href="{{ URL::signedRoute('reminder.list') }}" class="nav-link">
                                    <i class="far fa-list nav-icon"></i>
                                    <p>Resolve Reminder</p>
                                </a>
                            </li>
                        {{--@endcanany--}}
                    </ul>
                </li>

                @canany($workshopPermissions)
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                            d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z"
                                            fill="currentColor"></path>
                                    <path opacity="0.3"
                                          d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z"
                                          fill="currentColor"></path>
                                </svg>
                            </span>
                        </span>
                            <p>
                                Workshop Management
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            @canany([config('rights.create_job_card'),config('rights.view_job_card')])
                                <li class="nav-item pl-2">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Job Card
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview pl-2">
                                        @can(config('rights.create_job_card'))
                                            <li class="nav-item">
                                                <a href="{{URL::signedRoute('jobCard.requisition',['step'=> 1])}}"
                                                   class="nav-link">
                                                    <i class="fas fa-plus nav-icon"></i>
                                                    <p>New</p>
                                                </a>
                                            </li>
                                        @endcan

                                        @can(config('rights.view_job_card'))
                                            <li class="nav-item">
                                                <a href="{{URL::signedRoute('jobCard.list',['step'=> 1])}}"
                                                   class="nav-link">
                                                    <i class="fas fa-list nav-icon"></i>
                                                    <p>
                                                        In Workshop
                                                    </p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany

                            <li class="nav-item pl-2">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Booking
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview pl-2">
                                    {{--@can(config('rights.requisition_fuel'))--}}
                                    <li class="nav-item">
                                        <a class="nav-link"
                                           href="{{ URL::signedRoute('new.booking') }}">
                                            <i class="fas fa-plus nav-icon"></i>
                                            <p>New</p>
                                        </a>
                                    </li>
                                    {{--@endcan--}}
                                    {{-- @canany([config('rights.requisition_fuel'),config('rights.approve_fuel_requisition')])--}}
                                    <li class="nav-item">
                                        <a class="nav-link"
                                           href="{{ URL::signedRoute('list.booking') }}">
                                            <i class="fas fa-list nav-icon"></i>
                                            <p>List</p>
                                        </a>
                                    </li>
                                    {{-- @endcanany--}}
                                </ul>
                            </li>

                            @can(config('rights.approve_workshop_requisition'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ URL::signedRoute('list.workshop.requisition') }}">
                                        <i class="fas fa-list nav-icon"></i>
                                        <p>Requisitions</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @php
                    $requisitionsPermissions = [
                        config('rights.requisition_fuel'),
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
                            @canany($requisitionsPermissions)
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
                                                   href="{{ URL::signedRoute('new.fuel.requisition') }}">
                                                    <i class="fas fa-plus nav-icon"></i>
                                                    <p>New</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @canany([config('rights.requisition_fuel'),config('rights.approve_fuel_requisition')])
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                   href="{{ URL::signedRoute('list.fuel.requisition') }}">
                                                    <i class="fas fa-list nav-icon"></i>
                                                    <p>List</p>
                                                </a>
                                            </li>
                                        @endcanany
                                    </ul>
                                </li>
                            @endcanany
                            {{--@can(config('rights.set_vehicle_fuel_allocation'))
                                <li class="nav-item">
                                    <a class="nav-link"
                                       href="{{ URL::signedRoute('vehicle.fuel.allocation') }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Fuel Allocation</p>
                                    </a>
                                </li>
                            @endcan--}}

                            {{--<li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Vehicle Requisition</p>
                                </a>
                            </li>--}}

                            {{--<li class="nav-item">
                                <a href="{{URL::signedRoute('jobCard.requisition', ['step'=> 1])}}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Maintenance Requisition</p>
                                </a>
                            </li>--}}
                        </ul>
                    </li>
                @endcanany

                @php
                    $userManagementPermissions = [
                        config('rights.can_add_user'),
                        config('rights.view_user_detail'),
                        config('rights.view_user'),
                        config('rights.user_attach'),
                        config('rights.add_driver'),
                        config('rights.view_drivers'),
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
                            @canany([config('rights.can_add_user'),config('rights.view_user')])
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-users"></i>
                                        <p>
                                            Users
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview pl-3">

                                        @can(config('rights.can_add_user'))
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ URL::signedRoute('users.new') }}">
                                                    <i class="fas fa-user-plus nav-icon"></i>
                                                    <p class="menu-title">Add</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can(config('rights.view_user'))
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ URL::signedRoute('users.list') }}">
                                                    <i class="fas fa-users nav-icon"></i>
                                                    <p>List</p>
                                                </a>
                                            </li>
                                        @endcan
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
                                                        OnBoarding
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
                        </ul>
                    </li>
                @endcanany

                @php
                    $profileRights =  [
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
                                        {{--  <li class="nav-item">
                                              <a class="nav-link" href="{{ URL::signedRoute('roles.view') }}">
                                                  <span class="menu-bullet">
                                                      <span class="bullet bullet-dot"></span>
                                                  </span>
                                                  <span class="menu-title">View Role</span>
                                              </a>
                                          </li>--}}
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
                        config('rights.add_general_table_data'),
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
                                            d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z"
                                            fill="currentColor"></path>
                                    <path opacity="0.3"
                                          d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z"
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

                            @canany([
                                        config('rights.add_general_table_data'),
                                        config('rights.add_license_class'),
                                        config('rights.add_accident_nature')
                                    ])
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            General Tables
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview pl-2">

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
                                                @can(config('rights.add_vehicle_brand'))
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
                                                @endcan


                                                @can(config('rights.add_vehicle_model'))
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
                                                @endcan


                                                <!--Define Vehicle Body Types-->
                                                @can(config('rights.add_vehicle_type'))
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
                                                @endcan
                                            </ul>
                                        </li>

                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>
                                                    Workshop Directory
                                                    <i class="right fas fa-angle-left"></i>
                                                </p>
                                            </a>
                                            <ul class="nav nav-treeview pl-2">
                                                @canany([config('rights.add_workshop_section'),config('rights.edit_workshop_section'),config('rights.view_workshop_section')])
                                                    <li class="nav-item">
                                                        <a class="nav-link"
                                                           href="{{ URL::signedRoute('workshop.sections') }}">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Workshop Sections</p>
                                                        </a>
                                                    </li>
                                                @endcanany

                                                @canany([
                                                  config('rights.add_workshop'),
                                                  config('rights.edit_workshop'),
                                                  config('rights.view_workshop')
                                              ])
                                                    <li class="nav-item">
                                                        <a href="{{ URL::signedRoute('workshop.list') }}"
                                                           class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Workshop</p>
                                                        </a>
                                                    </li>
                                                @endcanany

                                                <li class="nav-item">
                                                    <a href="{{ URL::signedRoute('workshop.list') }}"
                                                       class="nav-link">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p>External Garages</p>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link"
                                               href="{{ URL::signedRoute('configuration.general.table',['ref'=>'driver-license-class']) }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span></span>

                                                <p class="menu-title">
                                                    License Class
                                                </p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>
                                                    Accidents
                                                    <i class="right fas fa-angle-left"></i>
                                                </p>
                                            </a>
                                            <ul class="nav nav-treeview pl-2">
                                                <li class="nav-item">
                                                    <a class="nav-link"
                                                       href="{{ URL::signedRoute('configuration.general.table',['ref'=>'accident-nature']) }}">
                                                        <span class="menu-bullet">
                                                            <span class="bullet bullet-dot">
                                                            </span>
                                                        </span>
                                                        <p class="menu-title">
                                                            Accident Natures
                                                        </p>
                                                    </a>
                                                </li>

                                                <li class="nav-item">
                                                    <a class="nav-link"
                                                       href="{{ URL::signedRoute('configuration.general.table',['ref'=>'accident-types']) }}">
                                                        <span class="menu-bullet">
                                                            <span class="bullet bullet-dot"></span>
                                                        </span>
                                                        <p class="menu-title">
                                                            Accident Types
                                                        </p>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link"
                                               href="{{ URL::signedRoute('configuration.general.table',['ref'=>'fuel-levels']) }}">

                                                <i class="fas fa-gas-pump"></i>

                                                <p class="menu-title">
                                                    Fuel Level
                                                </p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link"
                                               href="{{ URL::signedRoute('configuration.general.table',['ref'=>'general-status']) }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span></span>
                                                <p class="menu-title">
                                                    General Statuses
                                                </p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link"
                                               href="{{ URL::signedRoute('configuration.general.table',['ref'=>'insurance-company']) }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span>
                                            </span>
                                                <p class="menu-title">
                                                    Insurance Company
                                                </p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link"
                                               href="{{ URL::signedRoute('configuration.general.table',['ref'=>'insurance-types']) }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span></span>
                                                <span class="menu-title">
                                                Insurance Types
                                        </span>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link"
                                               href="{{ URL::signedRoute('configuration.general.table',['ref'=>'insurance-sub-types']) }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span></span>
                                                <span class="menu-title">
                                                Insurance Sub Types
                                        </span>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link"
                                               href="{{ URL::signedRoute('configuration.general.table',['ref'=>'repair-category']) }}">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot">
                                                    </span>
                                                </span>

                                                <p class="menu-title">
                                                    Repair Types
                                                </p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link"
                                               href="{{URL::signedRoute('configuration.general.table',['ref'=>'store-movement-type']) }}">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot">
                                                    </span>
                                                </span>

                                                <p class="menu-title">
                                                    Stores Movement Types
                                                </p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link"
                                               href="{{ URL::signedRoute('configuration.general.table',['ref'=>'vehicle-status']) }}">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot">
                                                    </span>
                                                </span>
                                                <p class="menu-title">
                                                    Vehicle Status
                                                </p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany

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
                        <li class="nav-item">
                            <a href="{{URL::signedRoute('e-toll.card')}}"
                               class="nav-link">
                                <i class="fas fa-plus nav-icon"></i>
                                <p>
                                    Add
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{URL::signedRoute('e-toll.card.list')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>List</p>
                            </a>
                        </li>
                    </ul>
                </li>


                @php
                    $reportsPermissions  = [config('right.access_reports')];
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

                            <li class="nav-item">
                                <a href="{{URL::signedRoute('reports.fuel.requisitions')}}"
                                   class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Fuel Requisitions
                                    </p>
                                </a>
                            </li>

                            {{--<li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Vehicle Requisition</p>
                                </a>
                            </li>--}}
                            {{--<li class="nav-item">
                                <a href="{{URL::signedRoute('jobCard.requisition', ['step'=> 1])}}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Maintenance Requisition</p>
                                </a>
                            </li>--}}
                        </ul>
                    </li>
                @endcanany
            </ul>
        </nav>

    </div>

</aside>
