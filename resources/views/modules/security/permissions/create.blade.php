@extends('layouts.app')
@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet"
          href="{{ asset('dashboard/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush


@section('content')

    <!-- Content Header (Page header) -->
    <x-content-header :pageTitle="'Add System Permission'" :activeCrumb="'New System Permission'" :link="'home'" :linkText="'Home'" />
    <!-- /.content-header -->

    <!-- Main page content -->
    <section class="content">
        <x-error-view/>
        <!-- Default box -->
        <div class="card">
            <form name="device_from" action="{{route('permissions.store')}}" method="post">
                @csrf
                <div class="card-body">


                    <div class="row">
                        <div class="col-6 form-group mt-4">
                            <label for="name"> PERMISSION NAME: <span class="required">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required
                                   maxlength="100"
                                   placeholder="create-status">
                        </div>
                        <div class="col-6 form-group mt-4">
                            <label for="slug"> SLUG: <span class="required">*</span></label>
                            <input type="text" class="form-control" id="slug" name="slug" required
                                   maxlength="100"
                                   placeholder="Enter Slug">
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="row">
                        <div id="submit_button" class="col-12 text-center">
                            @can(config('chilolezo.permissions.permission_create'))
                                <input class="btn btn-lg btn-success" type="submit" value="Submit"
                                       name="submit_form" class="form-control">
                            @endcan
                            <input class="btn btn-lg btn-secondary" type="reset" value="Clear"
                                   name="reset_form" class="form-control">
                        </div>
                    </div>
                </div>
                <!-- /.card-footer-->
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection


@push('scripts')
    <!-- page script -->
    <script>
        (function (appInstance) {
            appInstance.initDatatable("#example1", true);
        })(window.tmsApp ||{});
    </script>

@endpush
