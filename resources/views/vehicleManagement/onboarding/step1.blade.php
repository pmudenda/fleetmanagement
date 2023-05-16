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
            @include('vehicleManagement.partial.data_end_point')
        </div>
    </section>

@endsection

@push('scripts')
    {{--<script src="{{ asset('assets/global/plugins.bundle.js') }}"></script>--}}
    {{--<script src="{{asset('assets/plugins/form-masking/form-mask.js')}}"></script>--}}
{{--<script src="{{asset('application/modules/userManagement/employee.search.js').'?v='.\Carbon\Carbon::now()->format('his')}}"></script>--}}
   {{-- <script src="{{asset('application/modules/vehicleManagement/assets/js/new-vehicle-registration.js')}}"></script>--}}

@endpush
