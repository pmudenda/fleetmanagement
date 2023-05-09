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
@endpush
@section('content')
    <x-content-header/>
    <section class="content">
        <div class="row g-12 g-xl-12" id="kt_app_main">

            <!--BEGIN:::VEHICLE HEADER -->
            @include('vehicleManagement.onboarding.tabs.save_header')
            <!--END:::VEHICLE HEADER -->

            <!--BEGIN:::DETAILS  -->
            <div v-show="isHeaderSaved" class="col-md-12 col-sm-12 mb-5 mb-xl-10" style="border-right: 1px solid dimgray;">

                <div class="card card-flush">

                    <div class="card-body">

                        <!--BEGIN:::TAB HEADERS  -->
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-4 fw-semibold mb-5"
                            role="tablist">

                            <li class="nav-item" role="presentation" data-tab="tms_chassis_details_tab">
                                <a class="nav-link text-active-primary pb-5" data-bs-toggle="tab"
                                   href="#tms_chassis_details_tab"
                                   aria-selected="false"
                                   role="tab"
                                   tabindex="-1">
                                    @include('layouts.partials.chassis_icon')
                                    Chassis Details
                                </a>
                            </li>

                            <li class="nav-item" role="presentation" data-tab="tms_engine_details_tab">
                                <a class="nav-link text-active-primary pb-5"
                                   data-bs-toggle="tab"
                                   href="#tms_engine_details_tab" aria-selected="false" role="tab"
                                   tabindex="-1">
                                    @include('layouts.partials.engine_icon')
                                    Engine & Other Details
                                </a>
                            </li>

                            <li class="nav-item" role="presentation" data-tab="tms_costing_valuation_tab">
                                <a class="nav-link text-active-primary pb-5"
                                   data-bs-toggle="tab"
                                   href="#tms_costing_valuation_tab" aria-selected="false" role="tab"
                                   tabindex="-1">
                                    @include('layouts.partials.costing_icon')
                                    Costing & Valuation
                                </a>
                            </li>

                            <li class="nav-item" role="presentation" data-tab="tms_body_weight_tab">
                                <a class="nav-link text-active-primary pb-5 active"
                                   data-bs-toggle="tab"
                                   href="#tms_body_weight_tab" aria-selected="true" role="tab">
                                    @include('layouts.partials.body_icon')
                                    Body & Weight Details
                                </a>
                            </li>

                            <li class="nav-item" role="presentation" data-tab="tms_assignment_tab">
                                <a :disabled="dataStatus < 4" class="nav-link text-active-primary pb-5"
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
                            <div class="tab-pane fade" id="tms_chassis_details_tab" role="tabpanel">
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
                            <div class="tab-pane fade active show" id="tms_body_weight_tab" role="tabpanel">
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
            <input type="hidden" id="businessAreaEndpoint" name="businessAreaEndpoint"
                   value="{{ route('business.areas') }}">
            <input type="hidden" id="userSearchEndpoint" name="userSearchEndpoint" value="">
            {{--{{ route('api.users.search') }}--}}
            <input type="hidden" id="brands-api" value="{{ route('brands.get') }}">
            <input type="hidden" id="modelEndpoint" name="modelEndpoint" value="{{ route('models.get') }}">
            <input type="hidden" id="bodyTypesEndpoint" name="bodyTypesEndpoint"
                   value="{{ route('body_type.get') }}">
            <input type="hidden" id="orgUnitsEndpoint" name="orgUnitsEndpoint"
                   value="{{ route('organizational.units',['cache'=> true, 'include_nulls'=> false]) }}">
            <input type="hidden" id="directoratesEndpoint" name="directoratesEndpoint"
                   value="{{ route('directorates') }}">
            <input type="hidden" id="costCenterEndpoint" name="costCenterEndpoint" value="{{ route('cost.centers') }}">
            <input type="hidden" id="businessUnitsEndpoint" name="businessUnitsEndpoint"
                   value="{{ route('business.units') }}">

            <input type="hidden" id="registeredVehicles" name="registeredVehicles"
                   value="{{ route('vehicles.list') }}">

            <input type="hidden" id="documentValidationUrl" name="documentValidationUrl"
                   value="{{ route('document.number.validation') }}">

            <input type="hidden" name="vehicle_details" value="{{route('vehicle.details', [$reference])}}">
            <input type="hidden" id="fuelTypesUrl" value="{{route('fuel.types')}}">

        </div>
    </section>
    <x-employee-search-modal/>
@endsection

@push('scripts')
    <script>
        window.reference = `{!! $reference !!}`;
    </script>
    <script src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('application/modules/vehicleManagement/assets/js/new-vehicle-registration.js')}}"></script>
    <script src="{{asset('application/modules/userManagement/employee.search.js')}}"></script>
@endpush
