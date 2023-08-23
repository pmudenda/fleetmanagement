@extends('layouts.app')
@push('styles')
@endpush
@section('content')
    <section class="content">
        <div class="row g-12 g-xl-12" id="kt_app_main">
            <!--BEGIN:::VEHICLE HEADER -->
            @include('vehicleManagement.onboarding.tabs.unsaved_header')
            {{--@include('vehicleManagement.onboarding.tabs.save_header')--}}
            <!--END:::VEHICLE HEADER -->
            <input type="hidden"
                   name="vehicle_details"
                   value="{{route('vehicle.details', [$reference])}}"/>
            @include('vehicleManagement.partial.data_end_point')
        </div>
    </section>

@endsection

@push('scripts')

@endpush
