@extends('layouts.layout')
@push('styles')
@endpush
@section('content')

    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <svg width="24" height="24"
                             viewBox="0 0 24 24" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                              transform="rotate(45 17.0365 15.1223)" fill="currentColor">

                        </rect>
                        <path
                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                            fill="currentColor">
                        </path>
                    </svg>
                </span>
                    <input type="text" data-kt-user-table-filter="search"
                           class="form-control form-control-solid w-250px ps-14"
                           placeholder="Search user">
                </div>
            </div>

            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                            data-kt-menu-placement="bottom-end">
                        <span class="svg-icon svg-icon-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                fill="currentColor">
                            </path>
                        </svg>
                    </span>
                        Filter
                    </button>

                    <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">

                        <div class="px-7 py-5">
                            <div class="fs-5 text-dark fw-bold">Filter Options</div>
                        </div>

                        <div class="separator border-gray-200"></div>

                        <div class="px-7 py-5" data-kt-user-table-filter="form">
                            <!--begin::Input group-->
                            <div class="mb-10">
                                <label class="form-label fs-6 fw-semibold">Role:</label>
                                <select class="form-select form-select-solid fw-bold select2-hidden-accessible"
                                        data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true"
                                        data-kt-user-table-filter="role" data-hide-search="true"
                                        data-select2-id="select2-data-10-aqmm" tabindex="-1" aria-hidden="true">
                                    <option data-select2-id="select2-data-12-72di"></option>
                                    <option value="Administrator">Administrator</option>
                                    <option value="Analyst">Analyst</option>
                                    <option value="Developer">Developer</option>
                                    <option value="Support">Support</option>
                                    <option value="Trial">Trial</option>
                                </select>
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="mb-10">
                                <label class="form-label fs-6 fw-semibold">Two Step Verification:</label>
                                <select class="form-select form-select-solid fw-bold select2-hidden-accessible"
                                        data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true"
                                        data-kt-user-table-filter="two-step" data-hide-search="true"
                                        data-select2-id="select2-data-13-sryr" tabindex="-1" aria-hidden="true">
                                    <option data-select2-id="select2-data-15-zdag"></option>
                                    <option value="Enabled">Enabled</option>
                                </select>
                            </div>
                            <!--end::Input group-->

                            <!--begin::Actions-->
                            <div class="d-flex justify-content-end">
                                <button type="reset"
                                        class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                        data-kt-menu-dismiss="true" data-kt-user-table-filter="reset">Reset
                                </button>
                                <button type="submit" class="btn btn-primary fw-semibold px-6"
                                        data-kt-menu-dismiss="true" data-kt-user-table-filter="filter">Apply
                                </button>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Content-->
                    </div>


                    <button type="button" class="btn btn-light-primary me-3" style="display: none;"
                            data-bs-toggle="modal"
                            data-bs-target="#kt_modal_export_users">

                        <span class="svg-icon svg-icon-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.3" x="12.75" y="4.25" width="12" height="2" rx="1"
                                  transform="rotate(90 12.75 4.25)" fill="currentColor"></rect>
                            <path
                                d="M12.0573 6.11875L13.5203 7.87435C13.9121 8.34457 14.6232 8.37683 15.056 7.94401C15.4457 7.5543 15.4641 6.92836 15.0979 6.51643L12.4974 3.59084C12.0996 3.14332 11.4004 3.14332 11.0026 3.59084L8.40206 6.51643C8.0359 6.92836 8.0543 7.5543 8.44401 7.94401C8.87683 8.37683 9.58785 8.34458 9.9797 7.87435L11.4427 6.11875C11.6026 5.92684 11.8974 5.92684 12.0573 6.11875Z"
                                fill="currentColor">
                            </path>
                            <path opacity="0.3"
                                  d="M18.75 8.25H17.75C17.1977 8.25 16.75 8.69772 16.75 9.25C16.75 9.80228 17.1977 10.25 17.75 10.25C18.3023 10.25 18.75 10.6977 18.75 11.25V18.25C18.75 18.8023 18.3023 19.25 17.75 19.25H5.75C5.19772 19.25 4.75 18.8023 4.75 18.25V11.25C4.75 10.6977 5.19771 10.25 5.75 10.25C6.30229 10.25 6.75 9.80228 6.75 9.25C6.75 8.69772 6.30229 8.25 5.75 8.25H4.75C3.64543 8.25 2.75 9.14543 2.75 10.25V19.25C2.75 20.3546 3.64543 21.25 4.75 21.25H18.75C19.8546 21.25 20.75 20.3546 20.75 19.25V10.25C20.75 9.14543 19.8546 8.25 18.75 8.25Z"
                                  fill="currentColor">
                            </path>
                        </svg>
                    </span>
                        Export
                    </button>

                    {{--data-bs-toggle="modal" data-bs-target="#tms_modal_add_user"--}}
                    <a href="{{route('users.new')}}" type="button" class="btn btn-primary">

                        <span class="svg-icon svg-icon-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                  transform="rotate(-90 11.364 20.364)" fill="currentColor"></rect>
                            <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor"></rect>
                        </svg></span>
                        Add User
                    </a>

                </div>


                <!--begin::Group actions-->
                <div class="d-flex justify-content-end align-items-center d-none" data-kt-user-table-toolbar="selected">
                    <div class="fw-bold me-5">
                        <span class="me-2" data-kt-user-table-select="selected_count"></span> Selected
                    </div>

                    <button type="button" class="btn btn-danger" data-kt-user-table-select="delete_selected">
                        Delete Selected
                    </button>
                </div>
                <!--end::Group actions-->

                @include('UserManagement/components/export_users_modal')

            </div>
        </div>

        <div class="card-body py-4">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                       id="kt_table_users">
                    <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="w-10px pe-2 sorting_disabled" rowspan="1" colspan="1" aria-label=""
                            style="width: 29.8906px;">
                            <div class="list-row-checkbox form-check-sm form-check-custom form-check-solid me-3">
                                <input class="form-check-input" type="checkbox" data-kt-check="true"
                                       data-kt-check-target="#kt_table_users .form-check-input" value="1">
                            </div>
                        </th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Staff Number</th>
                        <th>Last login</th>
                        <th>Two-step Auth</th>
                        <th>Date Added</th>
                        <th>Actions</th>
                    </tr>

                    </thead>

                    <tbody class="text-gray-600 fw-semibold">

                    <tr v-for="user in users"  v-bind:data-identity="user.email" v-bind:data-objectguid="user.guid" >

                        <td>
                            <div class="list-row-checkbox form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="user.email">
                            </div>
                        </td>

                        <td class="d-flex align-items-center">

                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                <a href="#">
                                    <div class="symbol-label fs-3 bg-light-primary text-primary">
                                        @{{user.name | nameInitialAvatar}}
                                    </div>
                                </a>
                            </div>

                            <div class="d-flex flex-column">
                                <a href="#"
                                   class="text-gray-800 text-hover-primary mb-1">@{{ user.name }}</a>
                                <span>@{{ user.email }}</span>
                            </div>
                        </td>

                        <td>
                            @{{ user.position ?? 'Engineer Software Development' }}
                        </td>

                        <td>
                            @{{ user.staff_no }}
                        </td>

                        <td :data-order="user.last_login">
                            <div class="badge badge-light fw-bold">@{{ user.last_login | formatTimeAgo  }}</div>
                        </td>

                        <td>
                            <div v-if="user.two_fac_auth_status && user.two_fac_auth_status.toLowerCase() === 'enabled'" class="badge badge-light-success fw-bold">
                                @{{ user.two_fac_auth_status }}
                            </div>
                            <div v-else-if="user.two_fac_auth_status && user.two_fac_auth_status.toLowerCase() !== 'enabled'" class="badge badge-light-danger fw-bold">
                                @{{ user.two_fac_auth_status }}
                            </div>
                            <div v-else class="badge badge-light-danger fw-bold">
                                @{{ user.two_fac_auth_status ?? 'Inactive' }}
                            </div>
                        </td>

                        <td :data-order="user.created_at">
                            @{{ user.created_at | formatToFriendlyDate }}
                        </td>

                        <td>
                            <div class="d-flex my-3 ms-9">

                                @include('layouts.widgets.edit_button')

                                <a href="#" class="btn btn-icon btn-active-light-primary w-30px h-30px me-3"
                                   data-bs-toggle="tooltip" data-tms-method="delete" aria-label="Delete"
                                   data-bs-original-title="Delete" data-kt-initialized="1">
                                    <span class="svg-icon svg-icon-3">
                                        <svg width="24" height="24" viewBox="0 0 24 24"
                                             fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z"
                                                fill="currentColor"></path>
                                            <path opacity="0.5"
                                                  d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z"
                                                  fill="currentColor"></path>
                                            <path opacity="0.5"
                                                  d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z"
                                                  fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </a>


                                <a href="#" class="btn btn-icon btn-active-light-primary w-30px h-30px"
                                   data-bs-toggle="tooltip" data-kt-menu-trigger="click"
                                   data-kt-menu-placement="bottom-end" aria-label="More Options"
                                   data-bs-original-title="More Options" data-kt-initialized="1">
                                    <span class="svg-icon svg-icon-3">
                                        <svg width="24" height="24" viewBox="0 0 24 24"
                                             fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.3"
                                              d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z"
                                              fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </a>

                                <div
                                    class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold w-150px py-3"
                                    data-kt-menu="true">

                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3"
                                           data-kt-payment-mehtod-action="set_as_primary">
                                           Roles
                                        </a>
                                    </div>

                                </div>

                            </div>
                        </td>

                    </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <input type="hidden" id="usersEndpoint" name="usersEndpoint" value="{{ route('api.users.list') }}">
        <input type="hidden" id="profileUrl" name="profileUrl" value="{{ route('profile') }}">

    </div>
@endsection
@push('scripts')

    <script src="{{asset('assets/plugins/time-ago/time-ago.js')}}"></script>
    <script>
        window.TimeAgo.addDefaultLocale({
            locale: 'en',
            now: {
                now: {
                    current: "now",
                    future: "in a moment",
                    past: "just now"
                }
            },
            long: {
                year: {
                    past: {
                        one: "{0} year ago",
                        other: "{0} years ago"
                    },
                    future: {
                        one: "in {0} year",
                        other: "in {0} years"
                    }
                },
            }
        })
    </script>
    <script src="{{asset('application/modules/userManagement/users/list.js')}}"></script>
    <script src="{{asset('application/modules/userManagement/users/users-search.js')}}"></script>
@endpush
