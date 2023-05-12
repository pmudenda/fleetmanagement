@extends('layouts.app')
@push('styles')
    <style>
        .imagePreview {
            width: 100%;
            min-height: 280px;
            background-position: center center;
            background-color: #fff;
            background-size: contain;
            background-repeat: no-repeat;
            display: inline-block;
            box-shadow: 0px -3px 6px 2px rgba(0, 0, 0, 0.2);
        }
    </style>
    <link href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}"/>
@endpush
@section('content')
    <x-content-header/>
    <section class="content">
        <div class="row g-12 g-xl-12" id="kt_app_main">

            <!--BEGIN:::VEHICLE HEADER -->
            @include('vehicleManagement.onboarding.tabs.save_header')
            <!--END:::VEHICLE HEADER -->

            <!--BEGIN:::DETAILS  -->
            <div v-show="isHeaderSaved" class="col-md-12 col-sm-12 mb-5 mb-xl-10"
                 style="border-right: 1px solid dimgray;">

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
                                        @include('layouts.partials.chassis_icon')
                                    </span>
                                    General Data
                                </a>
                            </li>

                            <li class="nav-item" role="presentation" data-tab="tms_engine_details_tab">
                                <a disabled class="nav-link text-active-primary pb-5"
                                   data-bs-toggle="tab"
                                   href="#tms_engine_details_tab" aria-selected="false" role="tab"
                                   tabindex="-1">

                                    <span class="svg-icon svg-icon-2 me-2">
                                      @include('layouts.partials.engine_icon')
                                    </span>
                                    Technical Data
                                </a>
                            </li>

                            <li class="nav-item" role="presentation" data-tab="tms_costing_valuation_tab">
                                <a disabled class="nav-link text-active-primary pb-5"
                                   data-bs-toggle="tab"
                                   href="#tms_costing_valuation_tab" aria-selected="false" role="tab"
                                   tabindex="-1">
                                    @include('layouts.partials.costing_icon')
                                    Costing & Valuation
                                </a>
                            </li>

                            <li class="nav-item" role="presentation" data-tab="tms_body_weight_tab">
                                <a disabled class="nav-link text-active-primary pb-5"
                                   data-bs-toggle="tab"
                                   href="#tms_body_weight_tab" aria-selected="true" role="tab">
                                    @include('layouts.partials.body_icon')
                                    Body & Weight Details
                                </a>
                            </li>

                            <li class="nav-item" role="presentation" data-tab="tms_assignment_tab">
                                <a disabled class="nav-link text-active-primary pb-5"
                                   data-bs-toggle="tab"
                                   href="#tms_assignment_tab" aria-selected="false" role="tab"
                                   tabindex="-1">
                                    @include('layouts.partials.assignment_icon')
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
            @include('vehicleManagement.partial.data_end_point')
        </div>
    </section>
    <x-employee-search-modal/>
@endsection

@push('scripts')
    <script>
        window.reference = `{!! $reference !!}`;
    </script>
   {{-- <script src="{{ asset('assets/global/plugins.bundle.js') }}"></script>--}}
    <script src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('application/modules/vehicleManagement/assets/js/new-vehicle-registration.js').'?v='.\Carbon\Carbon::now()->format('his')}}"></script>
    <script src="{{asset('application/modules/userManagement/employee.search.js').'?v='.\Carbon\Carbon::now()->format('his')}}"></script>
@endpush
