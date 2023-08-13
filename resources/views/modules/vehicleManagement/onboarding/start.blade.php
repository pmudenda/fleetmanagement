@php use Carbon\Carbon; @endphp
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

        th {
            white-space: nowrap;
        }
    </style>
@endpush
@section('content')
    <x-content-header/>
    <section class="content">
        <div class="row g-12 g-xl-12" id="kt_app_main">

            <!--BEGIN:::VEHICLE HEADER -->
            @include('modules.vehicleManagement.onboarding.tabs.unsaved_header')
            <!--END:::VEHICLE HEADER -->

            <!--BEGIN:::DETAILS  -->
            <div v-show="vehicleHeader.isHeaderSaved" class="col-md-12 col-sm-12 mb-5 mb-xl-10"
                 style="border-right: 1px solid dimgray;">

                <div class="card card-flush">

                    <div class="card-body">

                        <!--BEGIN:::TAB HEADERS  -->
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-4 fw-semibold mb-5"
                            role="tablist">

                            <li class="nav-item" role="presentation" data-tab="tms_chassis_details_tab">
                                <a class="nav-link text-active-primary pb-5 @if($step==2) active @endif"
                                   data-bs-toggle="tab"
                                   href="#tms_chassis_details_tab"
                                   aria-selected="false"
                                   role="tab"
                                   tabindex="-1">
                                    @include('layouts.partials.chassis_icon')
                                    Step 1: General Data
                                </a>
                            </li>

                            <li class="nav-item" role="presentation" data-tab="tms_engine_details_tab">
                                <a @if($step < 3 ) disabled="disabled"
                                   @endif class="nav-link text-active-primary pb-5 @if($step==3) active @endif"
                                   data-bs-toggle="tab"
                                   href="#tms_engine_details_tab" aria-selected="false" role="tab"
                                   tabindex="-1">
                                    @include('layouts.partials.engine_icon')
                                    Step 2: Technical Data
                                </a>
                            </li>

                            <li class="nav-item" role="presentation" data-tab="tms_engine_details_tab">
                                <a @if($step < 4 ) disabled="disabled"
                                   @endif class="nav-link text-active-primary pb-5 @if($step == 4) active @endif"
                                   data-bs-toggle="tab"
                                   href="#tms_access_checkin_tab" aria-selected="false" role="tab"
                                   tabindex="-1">
                                    @include('layouts.partials.engine_icon')
                                    Step 3: Accessories Check-in
                                </a>
                            </li>

                            <li class="nav-item" role="presentation" data-tab="tms_body_weight_tab">
                                <a @if($step < 5 ) disabled="disabled"
                                   @endif class="nav-link text-active-primary pb-5 @if($step==5) active @endif"
                                   data-bs-toggle="tab"
                                   href="#tms_body_weight_tab" aria-selected="true" role="tab">
                                    @include('layouts.partials.body_icon')
                                    Step 4: Body & Weight Details
                                </a>
                            </li>

                            <li class="nav-item" role="presentation" data-tab="tms_costing_valuation_tab">
                                <a @if($step < 6 ) disabled="disabled"
                                   @endif class="nav-link text-active-primary pb-5 @if($step==6) active @endif"
                                   data-bs-toggle="tab"
                                   href="#tms_costing_valuation_tab" aria-selected="false" role="tab"
                                   tabindex="-1">
                                    @include('layouts.partials.costing_icon')
                                    Step 5: Costing & Valuation
                                </a>
                            </li>

                            <li class="nav-item" role="presentation" data-tab="tms_assignment_tab">
                                <a @if($step < 7 ) disabled="disabled"
                                   @endif class="nav-link text-active-primary pb-5 @if($step==7) active @endif"
                                   data-bs-toggle="tab"
                                   href="#tms_assignment_tab" aria-selected="false" role="tab"
                                   tabindex="-1">
                                    @include('layouts.partials.assignment_icon')
                                    Step 6: Assignment
                                </a>
                            </li>

                        </ul>
                        <!--END:::TAB HEADERS  -->

                        <!--BEGIN:::TAB CONTENT  -->
                        <div class="tab-content" id="myTabContent">

                            <!--Begin:::Chassis Details Tab pane-->
                            <div class="tab-pane fade @if($step==2) active show @endif" id="tms_chassis_details_tab"
                                 role="tabpanel">
                                {{--  @include('vehicleManagement.onboarding.tabs.accessories_tab')--}}
                                @include('modules.vehicleManagement.onboarding.tabs.chassis_tab')
                            </div>
                            <!--End:::Chassis Details Tab pane-->

                            <!--Begin:::Engine Details Tab pane-->
                            <div class="tab-pane fade @if($step==3) active show @endif"
                                 id="tms_engine_details_tab"
                                 role="tabpanel">
                                @include('modules.vehicleManagement.onboarding.tabs.engine_details_tab')
                            </div>
                            <!--End:::Engine Details Tab pane-->

                            <!--Begin:::Engine Details Tab pane-->
                            <div class="tab-pane fade @if($step==4) active show @endif"
                                 id="tms_access_checkin_tab"
                                 role="tabpanel">
                                @include('modules.vehicleManagement.onboarding.tabs.accessories_tab')
                            </div>
                            <!--End:::Engine Details Tab pane-->

                            <!--Begin:::Body Weight Tab pane-->
                            <div class="tab-pane fade @if($step==5) active show @endif"
                                 id="tms_body_weight_tab"
                                 role="tabpanel">
                                @include('modules.vehicleManagement.onboarding.tabs.weight_details_tab')
                            </div>
                            <!--End::: Body WeightTab pane-->

                            <!--Begin::: Costing And Valuation Tab pane-->
                            <div class="tab-pane fade @if($step==6) active show @endif"
                                 id="tms_costing_valuation_tab"
                                 role="tabpanel">
                                @include('modules.vehicleManagement.onboarding.tabs.cost_details_tab')
                            </div>
                            <!--End:::Tab pane-->

                            <!--Begin:::Assignment Tab pane-->
                            <div class="tab-pane fade @if($step==7) active show @endif" id="tms_assignment_tab"
                                 role="tabpanel">
                                @include('modules.vehicleManagement.onboarding.tabs.assignment_details')
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
    </section>
    <x-employee-search-modal/>

    <div class="modal fade" id="vehicleDisk"
         tabindex="-1"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Vehicle Disk</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center" id="diskArea">
                    <img class="img-fluid" src="{{asset('assets/dist/img/disc.jpg')}}"/>
                </div>
                <div class="modal-footer">
                    <button type="button" id="print" class="btn btn-primary btn-sm">
                        <i class="fas fa-print"></i>
                        Print
                    </button>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-default">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="fileViewModal"
         tabindex="-1"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">File Viewer</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="documentView" src="" style="border: none;" width="100%" height="600px;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-default">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.reference = `{!! $reference !!}`;
    </script>
    <script src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script
        src="{{asset('application/modules/vehicleManagement/assets/js/new-vehicle-registration.js').'?v='.Carbon::now()->format('his')}}"></script>
    <script
        src="{{asset('application/modules/userManagement/employee.search.js').'?v='.Carbon::now()->format('his')}}"></script>
    <script>
        $(document).ready(function () {
            setInterval(function () {
                if ($('#registrationNumber').val()) {
                    if (!$('#qrcode').attr('title')) {
                        generateBarcode($('#registrationNumber').val());
                    }
                }
            },600);
        });

    </script>
@endpush
