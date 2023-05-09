@extends('layouts.app')
@push('styles')
@endpush
@section('content')
    <div class="row g-12 g-xl-12" id="tms_app_main">

        <!--BEGIN:::VEHICLE HEADER -->
         @include('vehicleManagement.onboarding.tabs.header')
        <!--END:::VEHICLE HEADER -->

        <input type="hidden" id="businessAreaEndpoint" name="businessAreaEndpoint"
               value="{{ route('business.areas') }}">

        <input type="hidden" id="brands-api" value="{{ route('brands.get') }}">

        <input type="hidden" id="modelEndpoint" name="modelEndpoint" value="{{ route('models.get') }}">

        <input type="hidden" id="bodyTypesEndpoint" name="bodyTypesEndpoint" value="{{ route('body_type.get') }}">

        <input type="hidden" id="orgUnitsEndpoint" name="orgUnitsEndpoint"
               value="{{ route('organizational.units',['cache'=> true, 'include_nulls'=> false]) }}">

        <input type="hidden" id="directoratesEndpoint" name="directoratesEndpoint" value="{{ route('directorates') }}">

        <input type="hidden" id="costCenterEndpoint" name="costCenterEndpoint" value="{{ route('cost.centers') }}">

        <input type="hidden" id="businessUnitsEndpoint" name="businessUnitsEndpoint"
               value="{{ route('business.units') }}">

        <input type="hidden" id="registeredVehicles" name="registeredVehicles"
               value="{{ route('vehicles.list') }}">

        <input type="hidden" id="documentValidationUrl" name="documentValidationUrl"
               value="{{ route('document.number.validation') }}">

    </div>
@endsection

@push('scripts')
    {{--<script src="{{asset('assets/plugins/form-masking/form-mask.js')}}"></script>--}}
    <script src="{{asset('application/modules/userManagement/employee.search.js')}}"></script>
    <script src="{{asset('application/modules/vehicleManagement/onboarding/step_one.js')}}"></script>
@endpush
