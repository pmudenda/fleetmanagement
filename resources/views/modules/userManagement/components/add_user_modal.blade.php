<div class="modal fade" id="tms_modal_add_user" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="tms_modal_add_user_header">
                <h2 class="fw-bold">Add User</h2>

                <div class="btn btn-icon btn-sm btn-active-icon-primary" v-on:click="closeAddUserModal">
                    <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                              transform="rotate(-45 6 17.3137)" fill="currentColor">

                                        </rect>
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                              transform="rotate(45 7.41422 6)" fill="currentColor">

                                        </rect>
                        </svg>
                    </span>
                </div>
            </div>

            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form id="tms_modal_add_user_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="#">
                    <div class="d-flex flex-column scroll-y me-n7 pe-7" id="tms_modal_add_user_scroll"
                         data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                         data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#tms_modal_add_user_header"
                         data-kt-scroll-wrappers="#tms_modal_add_user_scroll" data-kt-scroll-offset="300px"
                         style="max-height: 384px;">

                        <div id="kt_modal_user_search_handler" data-kt-search-keypress="true"
                             data-kt-search-min-length="2" data-kt-search-enter="enter" data-kt-search-layout="inline"
                             data-kt-search="true" class="">

                            <form data-kt-search-element="form" class="w-100 position-relative mb-5" autocomplete="off">

                                <input type="hidden">
                                <span
                                    class="svg-icon svg-icon-2 svg-icon-lg-1
                                    svg-icon-gray-500 position-absolute top-50 ms-5
                                    translate-middle-y">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                      transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                                <path
                                    d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556
                                    6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19
                                    15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5
                                    7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667
                                    17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                    fill="currentColor"></path>
                                </svg>
                                </span>

                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-lg form-control-solid px-15"
                                       autocapitalize="on"
                                       autofocus="on"
                                       autocomplete="off"
                                       name="search" value="" placeholder="Search by username, full name or email..."
                                       data-kt-search-element="input">
                                <!--end::Input-->

                                <!--begin::Spinner-->
                                <span class="position-absolute top-50 end-0 translate-middle-y lh-0 me-5 d-none"
                                      data-kt-search-element="spinner">
                                    <span class="spinner-border h-15px w-15px align-middle text-gray-400">
                                    </span>
                            </span>

                                <span
                                    class="btn btn-flush btn-active-color-primary
                                    position-absolute top-50 end-0
                                    translate-middle-y lh-0 me-5 d-none"
                                    data-kt-search-element="clear">
                                        <span class="svg-icon svg-icon-2 svg-icon-lg-1 me-0">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                              transform="rotate(-45 6 17.3137)" fill="currentColor">
                                        </rect>
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                              transform="rotate(45 7.41422 6)" fill="currentColor">
                                        </rect>
                                        </svg>
                                        </span>
                                </span>

                            </form>
                            <!--end::Form-->

                            <!--begin::Wrapper-->
                            <div class="py-5">

                                <!--begin::Suggestions-->
                                <div data-kt-search-element="suggestions" class="">
                                    <div class="text-center px-4 pt-10"></div>
                                </div>
                                <!--end::Suggestions-->

                                <!--begin::Results-->
                                <div data-kt-search-element="results" class="d-none">
                                    <!--begin::Users-->
                                    <div class="mh-300px scroll-y me-n5 pe-5">
                                        <!--begin::User-->
                                        <div v-for="user in searchedUsers"
                                             :data-user="user.staff_number"
                                             class="d-flex align-items-center
                                             p-3 rounded-3 border-hover border
                                             border-dashed border-gray-300
                                             cursor-pointer mb-1"
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
                                        <!--end::User-->
                                    </div>
                                    <!--end::Users-->
                                </div>
                                <!--end::Results-->

                                <div data-kt-search-element="empty" class="text-center d-none">
                                    <div class="fw-semibold py-0 mb-10">
                                        <div class="text-gray-600 fs-3 mb-2">No users found</div>

                                        <div class="text-gray-400 fs-6">Try to search by staff number,
                                            full name or
                                            email...
                                        </div>
                                    </div>
                                    <div class="text-center px-4"></div>
                                </div>
                            </div>
                        </div>



                        <div v-show="userSelected.name" class="fv-row mb-7">
                            <label class="d-block fw-semibold fs-6 mb-5">Avatar</label>

                            <div class="image-input image-input-outline image-input-placeholder"
                                 data-kt-image-input="true">

                                <div class="image-input-wrapper w-125px h-125px"
                                     style="background-image:
                                     url('{{asset('/assets/media/avatars/avatar.png')}}')">
                                </div>

                                <label
                                    class="btn btn-icon btn-circle
                                    btn-active-color-primary w-25px h-25px bg-body shadow"
                                    data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                    aria-label="Change avatar" data-bs-original-title="Change avatar"
                                    data-kt-initialized="1">
                                    <i class="bi bi-pencil-fill fs-7"></i>

                                    <input type="file" name="avatar" accept=".png, .jpg, .jpeg">
                                    <input type="hidden" name="avatar_remove">

                                </label>

                                <span
                                    class="btn btn-icon btn-circle
                                    btn-active-color-primary w-25px h-25px bg-body shadow"
                                    data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                    aria-label="Cancel avatar" data-bs-original-title="Cancel avatar"
                                    data-kt-initialized="1">
                                                <i class="bi bi-x fs-2"></i>
                                            </span>

                                <span
                                    class="btn btn-icon btn-circle
                                    btn-active-color-primary w-25px h-25px bg-body
                                    shadow"
                                    data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                    aria-label="Remove avatar" data-bs-original-title="Remove avatar"
                                    data-kt-initialized="1">
                                                <i class="bi bi-x fs-2"></i>
                                            </span>

                            </div>

                            <div class="form-text">Allowed file types: png, jpg, jpeg.</div>

                        </div>

                        <div class="fv-row mb-7 fv-plugins-icon-container">

                            <div v-show="userSelected.name" class="fv-row mb-7 fv-plugins-icon-container">

                                <label class="required fw-semibold fs-6 mb-2">Full Name</label>

                                <input type="text" name="user_name"
                                       v-model="userSelected.name"
                                       readonly
                                       class="form-control form-control-solid mb-3 mb-lg-0"
                                       placeholder="Full name" value="">

                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>

                            <div v-show="userSelected.name" class="fv-row mb-7 fv-plugins-icon-container">

                                <label class="required fw-semibold fs-6 mb-2">Email</label>

                                <input type="email" name="user_email"
                                       v-model="userSelected.email"
                                       readonly
                                       class="form-control form-control-solid mb-3 mb-lg-0"
                                       placeholder=""
                                       value="">

                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>

                            <div v-show="userSelected.name" class="fv-row mb-7 fv-plugins-icon-container">

                                <label class="required fw-semibold fs-6 mb-2">Enter User Name</label>

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
                                Discard
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
        </div>
    </div>
</div>
