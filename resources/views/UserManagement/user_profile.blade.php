@extends('layouts.layout')
@section('content')
    <div class="card mb-5 mb-xl-10">
        <div class="card-body pt-9 pb-0">
            <!--begin::Details-->
            <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                <!--begin: Pic-->
                <div class="me-7 mb-4">
                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative symbol-circle me-3">
                        <img alt="image" src="{{ asset('assets/media/avatars/profile.png') }}">
                        <div
                            class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px">
                        </div>
                    </div>
                </div>
                <!--end::Pic-->

                <!--begin::Info-->
                <div class="flex-grow-1">
                    <!--begin::Title-->
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <!--begin::User-->
                        <div class="d-flex flex-column">
                            <!--begin::Name-->
                            <div class="d-flex align-items-center mb-2">
                                <a class="text-gray-900 text-hover-primary fs-2 fw-bold me-1"
                                   href="#">@{{ user.name }}</a>
                                <a href="#">
                                    <span class="svg-icon svg-icon-1 svg-icon-primary">
                                        <img src="{{asset('assets/media/svg/icons/verified.svg')}}"
                                             alt="verified icon"/>
                                    </span>
                                </a>
                            </div>
                            <!--end::Name-->

                            <!--begin::Info-->
                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                <a class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2"
                                   href="#">
                                    <span class="svg-icon svg-icon-4 me-1">
                                        <img src="{{asset('assets/media/svg/icons/me.svg')}}" alt="me icon"/>
                                    </span>
                                    @{{ user.designation }}
                                </a>
                                <a class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2"
                                   href="#">
                                    <!--begin::Svg Icon-->
                                    <span class="svg-icon svg-icon-4 me-1">
                                        <img src="{{asset('assets/media/svg/icons/location.svg')}}"
                                             alt="location icon"/>
                                    </span>
                                    @{{ user.location }}
                                </a>
                                <a class="d-flex align-items-center text-gray-400
                                    text-hover-primary mb-2"
                                   href="#">
                                    <span class="svg-icon svg-icon-4 me-1">
                                        <img src="{{asset('assets/media/svg/icons/email.svg')}}" alt="email icon"/>
                                    </span>
                                    @{{ user.email }}
                                </a>
                            </div>

                        </div>

                        <div class="d-flex my-4">

                            <div class="me-0">
                                <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary"
                                        data-kt-menu-placement="bottom-end" data-kt-menu-trigger="click">
                                    <i class="bi bi-three-dots fs-3"></i>
                                </button>

                                <div
                                    class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                                    data-kt-menu="true">

                                    <div class="menu-item px-3">
                                        <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                            Disable Account
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="d-flex flex-wrap flex-stack">

                        <div class="d-flex flex-column flex-grow-1 pe-8">
                            <div class="d-flex flex-wrap">
                                <!--   <StatisticContainer indicator="down"
                                                    text="$4,500"
                                                    description="Payments" />
                                <StatisticContainer indicator="up"
                                                    text="%60"
                                                    description="Success Rate" />  -->
                            </div>
                        </div>
                        {{-- <ProgressBar tracking-text="Profile Completion" percentage-complete="50"/>--}}
                    </div>
                </div>
            </div>


            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">

                <li class="nav-item mt-2">
                    <a class="nav-link text-active-primary ms-0 me-10 py-5 active"
                       data-bs-toggle="tab"
                       href="#tms_profile_over_view_tab">
                        Overview
                    </a>
                </li>

                <li class="nav-item mt-2">
                    <a class="nav-link text-active-primary ms-0 me-10 py-5 " data-bs-toggle="tab"
                       href="#tms_profile_settings_tab">
                        Settings
                    </a>
                </li>

                <li class="nav-item mt-2">
                    <a class="nav-link text-active-primary ms-0 me-10 py-5 " data-bs-toggle="tab"
                       href="#tms_profile_security_tab">
                        Security
                    </a>
                </li>

                {{--   <li class="nav-item mt-2">
                       <a class="nav-link text-active-primary ms-0 me-10 py-5 "  data-bs-toggle="tab"
                          href="#tms_profile_over_view_tab">
                           Activity
                       </a>
                   </li>

                   <li class="nav-item mt-2">
                       <a class="nav-link text-active-primary ms-0 me-10 py-5 " href="">
                           Logs
                       </a>
                   </li>--}}

            </ul>

        </div>
    </div>


    <div class="tab-content">
        <div class="tab-pane fade active show" id="tms_profile_over_view_tab" role="tabpanel">
            <div id="tms_profile_over_view_tab" class="card mb-5 mb-xl-10">
                <div class="card-header cursor-pointer">

                    <div class="card-title m-0">
                        <h3 class="fw-bold m-0">Profile Details</h3>
                    </div>

                    <a class="btn btn-sm btn-primary align-self-center">
                        Edit Profile
                    </a>

                </div>
                <div class="card-body p-9">
                    <!--begin::Row-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">
                            Full Name
                        </label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">@{{ user.name }}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->

                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Designation</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">@{{ user.position }}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">
                            Contact Phone
                            <i aria-label="Phone number must be active" class="fas fa-exclamation-circle ms-1 fs-7"
                               data-bs-original-title="Phone number must be active" data-bs-toggle="tooltip"
                               data-kt-initialized="1"></i>
                        </label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8 d-flex align-items-center">
                            <span class="fw-bold fs-6 text-gray-800 me-2">@{{ user.phone }}</span>

                            <span class="badge badge-success">Verified</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Company Site</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <a class="fw-semibold fs-6 text-gray-800 text-hover-primary" href="#"></a>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">
                            Country
                            <i aria-label="Country of origination" class="fas fa-exclamation-circle ms-1 fs-7"
                               data-bs-original-title="Country of origination" data-bs-toggle="tooltip"
                               data-kt-initialized="1"></i>
                        </label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">Zambia</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Communication</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">Email, Phone</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--<Notice
                      title="We need your attention!"
                      message="Your payment was declined. To start using tools, please"
                      type="warning"
                      call-to-action-text="Add Payment Method"
                    />-->

                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="tms_profile_settings_tab" role="tabpanel">
            <div id="tms_profile_over_view_tab" class="card mb-5 mb-xl-10">
                <div class="card-header cursor-pointer">

                    <div class="card-title m-0">
                        <h3 class="fw-bold m-0">Account Setting</h3>
                    </div>

                    <a class="btn btn-sm btn-primary align-self-center">
                        Edit Profile
                    </a>

                </div>
                <div class="card-body p-9">

                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="tms_profile_security_tab" role="tabpanel">
            <div id="tms_profile_over_view_tab" class="card mb-5 mb-xl-10">
                <div class="card-header cursor-pointer">

                    <div class="card-title m-0">
                        <h3 class="fw-bold m-0">Security</h3>
                    </div>
                </div>
                <div class="card-body p-9">

                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="email" name="email" value="{{ $email}}">
    <input type="hidden" id="uuid" name="uuid" value="{{ $key }}">
    <input type="hidden" id="profileEndpoint" name="profileEndpoint"
           value="{{ route('api.user.profile',['key'=> $key]) }}">

@endsection
@push('scripts')
    <script>
        let app = new Vue({
            'el': '#kt_app_main', data() {
                return {
                    user: {},
                }
            },
            methods: {
                getUserProfile() {
                    // Hide recently viewed
                    axios.get(document.querySelector('#profileEndpoint').value)
                        .then(function (response) {

                            if (response.data?.payload?.state === 'failure') {
                                return;
                            }

                            // Populate results
                            app.user = response.data?.payload[0];

                            app.$nextTick(function () {
                                //app?.initUserTable();
                                setTimeout(function () {
                                    //app?.handleEditRow();
                                }, 50);
                            });

                        })
                        .catch(function (error) {

                        });
                }
            },
            filters: {
                formatTimeAgo(value) {
                    if (!value) value = new TimeAgo('en').format(new Date())
                    return new TimeAgo('en').format(new Date(value))
                },
                nameInitialAvatar(value) {
                    console.log(value)
                    if (!value) return;
                    let nameParts = value.toString().split(' ');
                    return nameParts[0].substring(0, 1).toUpperCase() + ' ' + nameParts[1].substring(0, 1).toUpperCase();
                },
                formatToFriendlyDate(value) {
                    if (!value) return;
                    return window.moment(new Date(value)).format('DD MMM YYYY, h:mm a')
                }
            },
            created() {
                this.getUserProfile();
            },
            mounted() {
            }
        });
    </script>
@endpush
