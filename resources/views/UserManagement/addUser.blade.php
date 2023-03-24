@extends('layouts.layout')
@push('styles')
@endpush
@section('content')

    <div class="card">
        <div class="card-header border-0">
            <div class="card-title">
                <h4>Define New User</h4>
                {{-- <div class="d-flex align-items-center position-relative my-1">
                    - <span class="svg-icon svg-icon-1 position-absolute ms-6">
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
                </div>--}}
            </div>

            <div class="card-toolbar d-none" style="display: none;">
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


                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#tms_modal_add_user">

                        <span class="svg-icon svg-icon-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                  transform="rotate(-90 11.364 20.364)" fill="currentColor"></rect>
                            <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor"></rect>
                        </svg></span>
                        Add User
                    </button>

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
                {{--@include('UserManagement/components/add_user_modal')--}}

            </div>
        </div>

        <div class="card-body py-4 min-h-600px">
            <div id="kt_modal_user_search_handler" data-kt-search-keypress="true" data-kt-search-min-length="2"
                 data-kt-search-enter="enter" data-kt-search-layout="inline" data-kt-search="true" class="">

                <form data-kt-search-element="form" class="w-100 position-relative mb-5" autocomplete="off">
                    <input type="hidden">
                    <span
                        class="svg-icon svg-icon-2 svg-icon-lg-1 svg-icon-gray-500 position-absolute top-50 ms-5 translate-middle-y">
                       <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                              transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                        <path
                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                            fill="currentColor"></path>
                        </svg>
                    </span>

                    <input type="text" class="form-control form-control-lg form-control-solid px-15"
                           name="search" value=""
                           placeholder="Search by staff number, full name or email..."
                           data-kt-search-element="input">

                    <span class="position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-5"
                          data-kt-search-element="spinner">
                            <span class="spinner-border h-15px w-15px align-middle text-gray-400"></span>
                    </span>

                    <span
                        class="btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 me-5 d-none"
                        data-kt-search-element="clear">

                        <span class="svg-icon svg-icon-2 svg-icon-lg-1 me-0">
                            <svg width="24" height="24"
                                 viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                              transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                              fill="currentColor"></rect>
                        </svg>

                        </span>
                    </span>
                </form>

                <div class="py-5">
                    <div data-kt-search-element="suggestions">
                        <div class="text-center px-4 pt-10"></div>
                    </div>

                    <div data-kt-search-element="results" class="d-none">
                        <div class="mh-300px scroll-y me-n5 pe-5">
                            <div v-for="user in searchedUsers"
                                 :data-user="user.staff_number"
                                 class="d-flex align-items-center p-3 rounded-3 border-hover border border-dashed border-gray-300 cursor-pointer mb-1"
                                 data-kt-search-element="user">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-35px symbol-circle me-5">
                                    <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                        <a href="#">
                                            <div class="symbol-label fs-3 bg-light-primary text-primary">
                                                @{{user.name | nameInitialAvatar}}
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <!--end::Avatar-->

                                <!--begin::Info-->
                                <div class="fw-semibold">
                                    <span class="fs-6 text-gray-800 me-2">@{{ user.name }}</span>
                                    <span class="badge badge-light">@{{ user.position }}</span>
                                </div>
                                <!--end::Info-->
                            </div>
                        </div>
                    </div>
                    <div data-kt-search-element="empty" class="text-center d-none">
                        <div class="fw-semibold py-0 mb-10">
                            <div class="text-gray-600 fs-3 mb-2">No users found</div>

                            <div class="text-gray-400 fs-6">Try to search by staff number, full name or email...</div>
                        </div>

                        <div class="text-center px-4">
                            <img src="" alt="user" class="mw-100 mh-200px">
                        </div>
                    </div>
                </div>
            </div>


            <form v-show="userSelected.name" id="tms_modal_add_user_form"
                  class="form fv-plugins-bootstrap5 fv-plugins-framework" action="#">
                <div class="d-flex flex-column scroll-y me-n7 pe-7" id="tms_modal_add_user_scroll"
                     data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                     data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#tms_modal_add_user_header"
                     data-kt-scroll-wrappers="#tms_modal_add_user_scroll" data-kt-scroll-offset="300px"
                     style="max-height: 384px;">

                    <div class="fv-row mb-7">
                        <label class="d-block fw-semibold fs-6 mb-5">Avatar</label>

                        <div class="image-input image-input-outline image-input-placeholder"
                             data-kt-image-input="true">

                            <div class="image-input-wrapper w-125px h-125px"
                                 style="background-image: url('{{asset('/assets/media/avatars/avatar.png')}}')"></div>

                            <label
                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                aria-label="Change avatar" data-bs-original-title="Change avatar"
                                data-kt-initialized="1">
                                <i class="bi bi-pencil-fill fs-7"></i>

                                <input type="file" name="avatar" accept=".png, .jpg, .jpeg">
                                <input type="hidden" name="avatar_remove">

                            </label>

                            <span
                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                aria-label="Cancel avatar" data-bs-original-title="Cancel avatar"
                                data-kt-initialized="1">
                                                <i class="bi bi-x fs-2"></i>
                                            </span>

                            <span
                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                aria-label="Remove avatar" data-bs-original-title="Remove avatar"
                                data-kt-initialized="1">
                                                <i class="bi bi-x fs-2"></i>
                                            </span>

                        </div>

                        <div class="form-text">Allowed file types: png, jpg, jpeg.</div>

                    </div>

                    <div class="fv-row mb-7 fv-plugins-icon-container">

                        <div class="fv-row mb-7 fv-plugins-icon-container">

                            <label class="required fw-semibold fs-6 mb-2">Staff Number</label>

                            <input type="text" name="staff_number"
                                   v-model="userSelected.staff_number"
                                   readonly
                                   class="form-control form-control-solid mb-3 mb-lg-0"
                                   placeholder="Staff Number" value="">

                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="fv-row mb-7 fv-plugins-icon-container">

                            <label class="required fw-semibold fs-6 mb-2">Full Name</label>

                            <input type="text" name="user_full_name"
                                   v-model="userSelected.name"
                                   readonly
                                   class="form-control form-control-solid mb-3 mb-lg-0"
                                   placeholder="Full name" value="">

                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="fv-row mb-7 fv-plugins-icon-container">

                            <label class="required fw-semibold fs-6 mb-2">Email</label>

                            <input type="email" name="user_email"
                                   v-model="userSelected.email"
                                   readonly
                                   class="form-control form-control-solid mb-3 mb-lg-0"
                                   placeholder=""
                                   value="">

                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                        <div class="fv-row mb-7 fv-plugins-icon-container">

                            <label class="required fw-semibold fs-6 mb-2">User Name</label>

                            <input type="text" name="user_name"
                                   v-model="username"
                                   readonly
                                   class="form-control form-control-solid mb-3 mb-lg-0"
                                   placeholder="Login User name">

                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>

                    </div>

                    <div class="text-center pt-15">
                        <button type="reset"
                                v-on:click.prevent="discardAddUser"
                                class="btn btn-light me-3" data-kt-users-modal-action="cancel">
                            Clear
                        </button>

                        <button v-show="userSelected.name" type="button" class="btn btn-primary"
                                v-on:prevent="addNewUser" data-kt-users-modal-action="submit">
                                        <span class="indicator-label">
                                            Submit
                                        </span>
                            <span class="indicator-progress">
                                            Please wait... <span
                                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <input type="hidden" id="userSearchEndpoint" name="userSearchEndpoint" value="{{ route('api.users.search') }}">

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
    <script src="{{asset('application/modules/userManagement/users/add_user.js')}}"></script>
    <script src="{{asset('application/modules/userManagement/users/table.js')}}"></script>
    <script src="{{asset('application/modules/userManagement/users/users-search.js')}}"></script>
@endpush
