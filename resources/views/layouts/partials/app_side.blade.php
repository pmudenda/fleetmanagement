<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
     data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
     data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">


    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo" style="background: var(--bs-zesco-secondary);">
        <!--begin::Logo image-->
        <a href="{{ route('home') }}"  class="text-bold text-center">
            <img class="h-45px app-sidebar-logo-default"
                 src="{{ asset('assets/dist/img/icons/zesco_logo.png') }}"
                 alt="">

            <img class="h-20px app-sidebar-logo-minimize"
                 src="{{ asset('assets/dist/img/icons/zesco_logo.png') }}"
                 alt="">

            <span style="color:#fff;text-align: center;font-weight: 500;">TRANSPORT MANAGEMENT SYSTEM</span>
        </a>
        <!--end::Logo image-->

        <!--begin::Sidebar toggle-->
        <div id="kt_app_sidebar_toggle"
             class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate "
             data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
             data-kt-toggle-name="app-sidebar-minimize">

            <span class="svg-icon svg-icon-2 rotate-180"><svg width="24" height="24" viewBox="0 0 24 24"
                                                              fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.5"
                          d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z"
                          fill="currentColor"></path>
                    <path
                        d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z"
                        fill="currentColor"></path>
                </svg>
            </span>

        </div>
        <!--end::Sidebar toggle-->
    </div>
    <!--end::Logo-->
    <!--begin::sidebar menu-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <!--begin::Menu wrapper-->
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5"
             data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto"
             data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
             data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true"
             style="height: 263px;">
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu"
                 data-kt-menu="true" data-kt-menu-expand="false">
                <!--begin:Menu item-->
                <div data-kt-menu-trigger="click" class="menu-item here show menu-accordion">
                    <span class="menu-link"><span class="menu-icon">

                            <span class="svg-icon svg-icon-2"><svg width="24" height="24" viewBox="0 0 24 24"
                                                                   fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="2" y="2" width="9" height="9" rx="2"
                                          fill="currentColor">
                                    </rect>
                                    <rect opacity="0.3" x="13" y="2" width="9" height="9"
                                          rx="2" fill="currentColor">
                                    </rect>
                                    <rect opacity="0.3" x="13" y="13" width="9" height="9"
                                          rx="2" fill="currentColor">
                                    </rect>
                                    <rect opacity="0.3" x="2" y="13" width="9" height="9"
                                          rx="2" fill="currentColor">
                                    </rect>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Dashboards</span>
                        <span class="menu-arrow"></span></span>

                    <div class="menu-sub menu-sub-accordion">

                        <div class="menu-item">
                            <a class="menu-link active" href="{{route('home')}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot">
                                    </span>
                                </span>
                                <span class="menu-title">
                                    Default
                                </span>
                            </a>
                        </div>

                    </div>

                </div>

                <!--begin:Menu item-->
                <div class="menu-item pt-5">
                    <div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">Apps</span>
                    </div>
                </div>

                <!--Vehicle Management:Menu item-->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <i class="fonticon-truck fs-2x"></i>
                            </span>
                        </span>

                        <span class="menu-title">
                            Vehicle Management
                        </span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('new.vehicle') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    New Vehicle
                                </span>
                            </a>

                        </div>

                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('vehicles.list') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    Vehicle List
                                </span>
                            </a>

                        </div>


                        <div class="menu-item d-none">
                            <a class="menu-link" href="{{ route('permissions.list') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    Vehicle Details
                                </span>
                            </a>

                        </div>
                    </div>
                </div>
                <!--Vehicle Management:Menu item-->

                <!--Requisition:Menu item-->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <i class="fonticon-truck fs-2x"></i>
                            </span>
                        </span>

                        <span class="menu-title">
                            Requisitions
                        </span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('new.vehicle.requisition') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    Vehicle Requisition
                                </span>
                            </a>

                        </div>

                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('new.parts.requisition') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    Parts Requisition
                                </span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('new.fuel.requisition') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    Fuel Requisition
                                </span>
                            </a>
                        </div>


                        <div class="menu-item d-none">
                            <a class="menu-link" href="{{ route('permissions.list') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    Vehicle Details
                                </span>
                            </a>

                        </div>
                    </div>
                </div>
                <!--Requisition:Menu item-->


                <!--User Management:Menu item-->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link">
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
                        <span class="menu-title">
                            User Management
                        </span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <!--begin:Menu item-->
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion mb-1">
                            <span class="menu-link">
                                <span class="menu-bullet"><span class="bullet bullet-dot">
                                    </span></span>
                                <span class="menu-title">Users</span><span class="menu-arrow"></span></span>

                            <div class="menu-sub menu-sub-accordion">

                                <div class="menu-item">
                                    <a class="menu-link" href="{{ route('users.new') }}"><span
                                            class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                            class="menu-title">Add User</span></a>

                                </div>

                                <div class="menu-item">
                                    <a class="menu-link" href="{{ route('users.list') }}"><span
                                            class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                            class="menu-title">Users
                                            List</span></a>

                                </div>

                                {{--<div class="menu-item d-none">
                                    <a class="menu-link" href="{{ route('view.user') }}"><span
                                            class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                            class="menu-title">View
                                            User</span></a>

                                </div>--}}
                            </div>
                        </div>

                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <span class="menu-link">
                                <span class="menu-bullet"><span class="bullet bullet-dot">
                                    </span></span><span class="menu-title">Roles</span>
                                <span class="menu-arrow"></span></span>

                            <div class="menu-sub menu-sub-accordion">

                                <div class="menu-item">
                                    <a class="menu-link" href="{{ route('roles.list') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">
                                            Roles List
                                        </span>
                                    </a>
                                </div>

                                <div class="menu-item d-none">

                                    <a class="menu-link" href="{{ route('roles.view') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">View Role</span>
                                    </a>

                                </div>

                            </div>

                        </div>

                        <div class="menu-item d-none">
                            <a class="menu-link" href="{{ route('permissions.list') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">
                                    Permissions
                                </span>
                            </a>

                        </div>
                    </div>
                </div>
                <!--User Management:Menu item-->

                <!--System Configurations :Menu item-->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link">
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
                        <span class="menu-title"> System Configurations</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->

                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion" kt-hidden-height="121"
                         style="display: none; overflow: hidden;">

                        <!--vehicles:Menu item-->
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">

                            <div data-kt-menu-trigger="click" class="menu-item menu-accordion mb-1">
                                <span class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot">
                                        </span>
                                    </span>
                                    <span class="menu-title">
                                        Vehicle
                                    </span>
                                    <span class="menu-arrow"></span>
                                </span>

                                <div class="menu-sub menu-sub-accordion">

                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('vehicle.make') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span>
                                            </span>
                                            <span class="menu-title">
                                                Make (Brand)
                                            </span>
                                        </a>
                                    </div>

                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('vehicle.models') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span>
                                            </span>
                                            <span class="menu-title">
                                                Models
                                            </span>
                                        </a>
                                    </div>

                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('vehicle.body.types') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span></span>
                                            <span class="menu-title">
                                                Body Types
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--vehicles:Menu item-->


                        <!--accidents:Menu item-->
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <div data-kt-menu-trigger="click" class="menu-item menu-accordion mb-1">
                                <span class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot">
                                        </span>
                                    </span>
                                    <span class="menu-title">
                                        Accidents
                                    </span>
                                    <span class="menu-arrow"></span>
                                </span>

                                <div class="menu-sub menu-sub-accordion">

                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('accident.types') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span></span>
                                            <span class="menu-title">
                                                Accident Types
                                            </span>
                                        </a>
                                    </div>

                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('accident.nature') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span>
                                            </span>
                                            <span class="menu-title">
                                                Accident Natures
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--accidents:Menu item-->


                        <!--insurance:Menu item-->
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <div data-kt-menu-trigger="click" class="menu-item menu-accordion mb-1">
                                <span class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot">
                                        </span>
                                    </span>
                                    <span class="menu-title">
                                        Insurance
                                    </span>
                                    <span class="menu-arrow"></span>
                                </span>

                                <div class="menu-sub menu-sub-accordion">

                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('insurance.types') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span></span>
                                            <span class="menu-title">
                                                Insurance Types
                                            </span>
                                        </a>
                                    </div>

                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('insurance.companies') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span>
                                            </span>
                                            <span class="menu-title">
                                                Insurance Company
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--insurance:Menu item-->

                        <!--General Table:Menu item-->
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <div data-kt-menu-trigger="click" class="menu-item menu-accordion mb-1">
                                <span class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot">
                                        </span>
                                    </span>
                                    <span class="menu-title">
                                         General Tables
                                    </span>
                                    <span class="menu-arrow"></span>
                                </span>

                                <div class="menu-sub menu-sub-accordion">

                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('insurance.types') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span></span>
                                            <span class="menu-title">
                                                Statuses
                                            </span>
                                        </a>
                                    </div>

                                    <div class="menu-item">
                                        <a class="menu-link" href="{{ route('insurance.companies') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot">
                                                </span>
                                            </span>
                                            <span class="menu-title">
                                                Fuel Types
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--General Tables:Menu item-->


                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--System Configurations :Menu item-->
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Menu wrapper-->
    </div>
    <!--end::sidebar menu-->


    <!--begin::Footer-->
    <div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">

    </div>
    <!--end::Footer-->
</div>
<!--end::Sidebar-->
