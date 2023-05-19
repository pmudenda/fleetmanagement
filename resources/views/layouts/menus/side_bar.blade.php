@php use Illuminate\Support\Facades\Auth; @endphp
<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="{{ route('home') }}" class="brand-link" style="background: var(--bs-zesco-secondary)">
        <img class="h-45px app-sidebar-logo-default brand-image img-circle elevation-3"
             style="opacity: .8"
             src="{{ asset('assets/dist/img/icons/zesco_logo.png') }}"
             alt="" />
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
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                       aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-truck-pickup"></i>
                        <p>
                            Vehicle Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('new.vehicle') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>New Vehicle</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('view.vehicle.detail') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Vehicle Details</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('vehicles.list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Vehicle List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('vehicle.data.cleanup') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Clean Up</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @canany([config('rights.define_section')])
                @endcanany
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

                        <li class="nav-item">
                            <a href="{{ route('workshop.sections') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sections</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('workshop.list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Workshops List</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-circle"></i>
                                <p>
                                    Maintenance
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{route('maintenance.requisition')}}" class="nav-link">
                                        <i class="fas fa-plus nav-icon"></i>
                                        <p>
                                            Job Card
                                        </p>
                                    </a>

                                </li>
                            </ul>
                        </li>

                    </ul>

                </li>


                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-circle"></i>
                        <p>
                            Requisitions
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview pl-3">

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Fuel Requisitions
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview pl-4">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('new.fuel.requisition') }}">
                                        <i class="fas fa-plus nav-icon"></i>
                                        <p>New</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('list.fuel.requisition') }}">
                                        <i class="fas fa-list nav-icon"></i>
                                        <p>List</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Vehicle Requisition</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('maintenance.requisition')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Maintenance Requisition</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users" style="font-size: 20px;"></i>
                        <p>
                            Employee Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.new') }}">
                                <i class="fas fa-user-plus nav-icon"></i>
                                <p class="menu-title">Add User</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.list') }}">
                                <i class="fas fa-users nav-icon"></i>
                                <p>
                                    Users List
                                </p>
                            </a>
                        </li>        
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.driverList') }}">
                                <i class="fas fa-users nav-icon"></i>
                                <p>
                                    Drivers List
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.driver') }}">
                                <i class="fas fa-users nav-icon"></i>
                                <p>
                                    Drivers OnBoarding
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-shield-alt " style="font-size: 20px;"></i>
                         {{--<span class="menu-icon">
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
                        </span>--}}
                        <p>
                            Security
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-user-shield nav-icon"></i>
                                <p>
                                    Profiles
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview pl-4">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('roles.index') }}">
                                        <i class="fas fa-list nav-icon"></i>
                                        <p> Roles List</p>
                                    </a>
                                </li>
                                {{--  <li class="nav-item">
                                      <a class="nav-link" href="{{ route('roles.view') }}">
                                          <span class="menu-bullet">
                                              <span class="bullet bullet-dot"></span>
                                          </span>
                                          <span class="menu-title">View Role</span>
                                      </a>
                                  </li>--}}
                            </ul>
                        </li>

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
                                    <a class="nav-link" href="{{ route('permissions.index') }}">
                                        <i class="fa fa-list nav-icon"></i>
                                        <p class="menu-title">
                                            Permission List
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

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
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Vehicle Configurations
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('vehicle.make') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span>
                                            </span>
                                        <span class="menu-title">
                                                Make (Brand)
                                            </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('vehicle.models') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span>
                                            </span>
                                        <span class="menu-title">
                                                Models
                                            </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('vehicle.body.types') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span></span>
                                        <span class="menu-title">
                                                Body Types
                                            </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('vehicle.fuel.allocation') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span></span>
                                        <span class="menu-title">
                                                Fuel Allocation
                                            </span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('charge.out.rate') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot">
                                        </span>
                                    </span>
                                <span class="menu-title">
                                      Charge Out Rates
                                </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    General Tables
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a class="nav-link"
                                       href="{{ route('configuration.general.table',['ref'=>'accidentTypes']) }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span></span>
                                        <span class="menu-title">
                                                Accident Types
                                            </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link"
                                       href="{{ route('configuration.general.table',['ref'=>'accidentNature']) }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span>
                                            </span>
                                        <span class="menu-title">
                                                Accident Natures
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link"
                                       href="{{ route('configuration.general.table',['ref'=>'insuranceType']) }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span></span>
                                        <span class="menu-title">
                                                Insurance Types
                                        </span>
                                    </a>
                                </li>

                                {{-- <li class="nav-item myNavItems">
                                     <a onclick="testChange('insuranceSubtypes')" class="nav-link" href="/types/insurancesubtypes">Insurance Subtypes</a>
                                 </li>--}}

                                <li class="nav-item">
                                    <a class="nav-link"
                                       href="{{ route('configuration.general.table',['ref'=>'insuranceCompany']) }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span>
                                            </span>
                                        <span class="menu-title">
                                                Insurance Company
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link"
                                       href="{{ route('configuration.general.table',['ref'=>'vehicleStatus']) }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span></span>
                                        <span class="menu-title">
                                               Vehicle Status
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link"
                                       href="{{ route('configuration.general.table',['ref'=>'statusGeneral']) }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span></span>
                                        <span class="menu-title">
                                               General Statuses
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link"
                                       href="{{ route('configuration.general.table',['ref'=>'fuelType']) }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span>
                                            </span>
                                        <span class="menu-title">
                                                Fuel Types
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link"
                                       href="{{ route('configuration.general.table',['ref'=>'businessAreas']) }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span>
                                            </span>
                                        <span class="menu-title">
                                               Business Areas
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link"
                                       href="{{ route('configuration.general.table',['ref'=>'movementType']) }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span>
                                            </span>
                                        <span class="menu-title">
                                               Stores Movement Types
                                        </span>
                                    </a>
                                </li>

                    
                            </ul>
                        </li>

                    </ul>
                </li>
            </ul>
        </nav>

    </div>

</aside>
