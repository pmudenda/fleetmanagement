@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet"
          href="{{ asset('dashboard/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush


@section('content')
    <x-content-header :pageTitle="'Add System Permission'"
                      :activeCrumb="'New System Permission'"
                      :link="'home'"
                      :linkText="'Home'"/>

    <section class="content">

    </section>

@endsection


@push('scripts')
    <!-- page script -->
    <script>
        (function (appInstance) {
            appInstance.initDatatable("#example1", true);
        })(window.tmsApp || {});
    </script>

@endpush
