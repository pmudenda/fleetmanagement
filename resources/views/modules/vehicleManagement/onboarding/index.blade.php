@extends('layouts.app')
@push('styles')
    <style>

    </style>

    </style>
    <link href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}"/>
@endpush
@section('content')
    <div class="row g-12 g-xl-12" id="kt_app_main">

        <h1>ERROR</h1>

        <!--BEGIN:::VEHICLE HEADER -->
        @include('modules.vehicleManagement.onboarding.tabs.unsaved_header')
        <!--END:::VEHICLE HEADER -->

        <!--BEGIN:::DETAILS  -->
        <div v-show="isHeaderSaved" class="col-md-12 col-sm-12 mb-5 mb-xl-10" style="border-right: 1px solid dimgray;">

            <div class="card card-flush">

                <div class="card-body">

                    <!--BEGIN:::TAB HEADERS  -->
                    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-4 fw-semibold mb-5"
                        role="tablist">

                        <li class="nav-item" role="presentation" data-tab="tms_chassis_details_tab">
                            <a class="nav-link text-active-primary pb-5 active" data-bs-toggle="tab"
                               href="#tms_chassis_details_tab"
                               aria-selected="false"
                               role="tab"
                               tabindex="-1">
                                <span class="svg-icon svg-icon-2 me-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3"
                                              d="M18 10V20C18 20.6 18.4 21 19 21C19.6 21 20 20.6 20 20V10H18Z"
                                              fill="currentColor"></path>
                                        <path opacity="0.3"
                                              d="M11 10V17H6V10H4V20C4 20.6 4.4 21 5 21H12C12.6 21 13 20.6 13 20V10H11Z"
                                              fill="currentColor"></path>
                                        <path opacity="0.3" d="M10 10C10 11.1 9.1 12 8 12C6.9 12 6 11.1 6 10H10Z"
                                              fill="currentColor"></path>
                                        <path opacity="0.3" d="M18 10C18 11.1 17.1 12 16 12C14.9 12 14 11.1 14 10H18Z"
                                              fill="currentColor"></path>
                                        <path opacity="0.3" d="M14 4H10V10H14V4Z" fill="currentColor"></path>
                                        <path opacity="0.3" d="M17 4H20L22 10H18L17 4Z" fill="currentColor"></path>
                                        <path opacity="0.3" d="M7 4H4L2 10H6L7 4Z" fill="currentColor"></path>
                                        <path
                                            d="M6 10C6 11.1 5.1 12 4 12C2.9 12 2 11.1 2 10H6ZM10 10C10 11.1 10.9 12 12 12C13.1 12 14 11.1 14 10H10ZM18 10C18 11.1 18.9 12 20 12C21.1 12 22 11.1 22 10H18ZM19 2H5C4.4 2 4 2.4 4 3V4H20V3C20 2.4 19.6 2 19 2ZM12 17C12 16.4 11.6 16 11 16H6C5.4 16 5 16.4 5 17C5 17.6 5.4 18 6 18H11C11.6 18 12 17.6 12 17Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </span>
                                Chassis Details
                            </a>
                        </li>

                        <li class="nav-item" role="presentation" data-tab="tms_engine_details_tab">
                            <a :disabled="dataStatus < 1" class="nav-link text-active-primary pb-5"
                               data-bs-toggle="tab"
                               href="#tms_engine_details_tab" aria-selected="false" role="tab"
                               tabindex="-1">

                                <span class="svg-icon svg-icon-2 me-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11 2.375L2 9.575V20.575C2 21.175 2.4 21.575 3 21.575H9C9.6 21.575 10 21.175 10 20.575V14.575C10 13.975 10.4 13.575 11 13.575H13C13.6 13.575 14 13.975 14 14.575V20.575C14 21.175 14.4 21.575 15 21.575H21C21.6 21.575 22 21.175 22 20.575V9.575L13 2.375C12.4 1.875 11.6 1.875 11 2.375Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </span>
                                Engine & Other Details
                            </a>
                        </li>

                        <li class="nav-item" role="presentation" data-tab="tms_costing_valuation_tab">
                            <a :disabled="dataStatus < 2" class="nav-link text-active-primary pb-5"
                               data-bs-toggle="tab"
                               href="#tms_costing_valuation_tab" aria-selected="false" role="tab"
                               tabindex="-1">

                                <span class="svg-icon svg-icon-2 me-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3"
                                              d="M18.4 5.59998C21.9 9.09998 21.9 14.8 18.4 18.3C14.9 21.8 9.2 21.8 5.7 18.3L18.4 5.59998Z"
                                              fill="currentColor"></path>
                                        <path
                                            d="M12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2ZM19.9 11H13V8.8999C14.9 8.6999 16.7 8.00005 18.1 6.80005C19.1 8.00005 19.7 9.4 19.9 11ZM11 19.8999C9.7 19.6999 8.39999 19.2 7.39999 18.5C8.49999 17.7 9.7 17.2001 11 17.1001V19.8999ZM5.89999 6.90002C7.39999 8.10002 9.2 8.8 11 9V11.1001H4.10001C4.30001 9.4001 4.89999 8.00002 5.89999 6.90002ZM7.39999 5.5C8.49999 4.7 9.7 4.19998 11 4.09998V7C9.7 6.8 8.39999 6.3 7.39999 5.5ZM13 17.1001C14.3 17.3001 15.6 17.8 16.6 18.5C15.5 19.3 14.3 19.7999 13 19.8999V17.1001ZM13 4.09998C14.3 4.29998 15.6 4.8 16.6 5.5C15.5 6.3 14.3 6.80002 13 6.90002V4.09998ZM4.10001 13H11V15.1001C9.1 15.3001 7.29999 16 5.89999 17.2C4.89999 16 4.30001 14.6 4.10001 13ZM18.1 17.1001C16.6 15.9001 14.8 15.2 13 15V12.8999H19.9C19.7 14.5999 19.1 16.0001 18.1 17.1001Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </span>
                                Costing & Valuation
                            </a>
                        </li>

                        <li class="nav-item" role="presentation" data-tab="tms_body_weight_tab">
                            <a :disabled="dataStatus < 3" class="nav-link text-active-primary pb-5"
                               data-bs-toggle="tab"
                               href="#tms_body_weight_tab" aria-selected="true" role="tab">
                                <span class="svg-icon svg-icon-2 me-2"><svg width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16.0173 9H15.3945C14.2833 9 13.263 9.61425 12.7431 10.5963L12.154 11.7091C12.0645 11.8781 12.1072 12.0868 12.2559 12.2071L12.6402 12.5183C13.2631 13.0225 13.7556 13.6691 14.0764 14.4035L14.2321 14.7601C14.2957 14.9058 14.4396 15 14.5987 15H18.6747C19.7297 15 20.4057 13.8774 19.912 12.945L18.6686 10.5963C18.1487 9.61425 17.1285 9 16.0173 9Z"
                                            fill="currentColor"></path>
                                        <rect opacity="0.3" x="14" y="4" width="4"
                                              height="4" rx="2" fill="currentColor"></rect>
                                        <path
                                            d="M4.65486 14.8559C5.40389 13.1224 7.11161 12 9 12C10.8884 12 12.5961 13.1224 13.3451 14.8559L14.793 18.2067C15.3636 19.5271 14.3955 21 12.9571 21H5.04292C3.60453 21 2.63644 19.5271 3.20698 18.2067L4.65486 14.8559Z"
                                            fill="currentColor"></path>
                                        <rect opacity="0.3" x="6" y="5" width="6"
                                              height="6" rx="3" fill="currentColor"></rect>
                                    </svg>
                                </span>
                                Body & Weight Details
                            </a>
                        </li>

                        <li class="nav-item" role="presentation" data-tab="tms_assignment_tab">
                            <a :disabled="dataStatus < 4" class="nav-link text-active-primary pb-5"
                               data-bs-toggle="tab"
                               href="#tms_assignment_tab" aria-selected="false" role="tab"
                               tabindex="-1">
                                <span class="svg-icon svg-icon-2 me-2"><svg width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3"
                                              d="M20 22H4C3.4 22 3 21.6 3 21V2H21V21C21 21.6 20.6 22 20 22Z"
                                              fill="currentColor"></path>
                                        <path
                                            d="M12 14C9.2 14 7 11.8 7 9V5C7 4.4 7.4 4 8 4C8.6 4 9 4.4 9 5V9C9 10.7 10.3 12 12 12C13.7 12 15 10.7 15 9V5C15 4.4 15.4 4 16 4C16.6 4 17 4.4 17 5V9C17 11.8 14.8 14 12 14Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </span>
                                Assignment
                            </a>
                        </li>

                    </ul>
                    <!--END:::TAB HEADERS  -->

                    <!--BEGIN:::TAB CONTENT  -->
                    <div class="tab-content" id="myTabContent">

                        <!--Begin:::Chassis Details Tab pane-->
                        <div class="tab-pane fade active show" id="tms_chassis_details_tab" role="tabpanel">
                            @include('vehicleManagement.onboarding.tabs.chassis_tab')
                        </div>
                        <!--End:::Chassis Details Tab pane-->

                        <!--Begin:::Engine Details Tab pane-->
                        <div class="tab-pane fade" id="tms_engine_details_tab" role="tabpanel">
                            @include('vehicleManagement.onboarding.tabs.engine_details_tab')
                        </div>
                        <!--End:::Engine Details Tab pane-->

                        <!--Begin::: Costing And Valuation Tab pane-->
                        <div class="tab-pane fade" id="tms_costing_valuation_tab" role="tabpanel">
                            @include('vehicleManagement.onboarding.tabs.cost_details_tab')
                        </div>
                        <!--End:::Tab pane-->

                        <!--Begin:::Body Weight Tab pane-->
                        <div class="tab-pane fade" id="tms_body_weight_tab" role="tabpanel">
                            @include('vehicleManagement.onboarding.tabs.weight_details_tab')
                        </div>
                        <!--End::: Body WeightTab pane-->

                        <!--Begin:::Assignment Tab pane-->
                        <div class="tab-pane fade" id="tms_assignment_tab" role="tabpanel">
                            @include('vehicleManagement.onboarding.tabs.assignment_details')
                        </div>
                        <!--End::: Assignment Tab pane-->

                    </div>
                    <!--BEGIN:::TAB CONTENT  -->
                </div>
            </div>

        </div>

        <!--END:::DETAILS  -->
        <input type="hidden"
               name="vehicle_details"
               value="{{route('vehicle.details', [$reference])}}"/>
      @include('modules.vehicleManagement.partial.data_end_point')
    </div>
    <x-employee-search-modal/>
@endsection

@push('scripts')
    <script src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('modules/vehicleManagement/assets/js/lib.vehicle.data.js')}}"></script>
    <script src="{{asset('modules/vehicleManagement/assets/js/new-vehicle-registration.js')}}"></script>
    <script src="{{asset('modules/userManagement/employee.search.js')}}"></script>
@endpush
