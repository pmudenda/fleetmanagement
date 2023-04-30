@extends('layouts.main.master')

@push('custom-styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet"
          href="{{ asset('dashboard/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">System User Details Edit</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">System User Details Edit</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main page content -->
    <section class="content">

        @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible">
                <p class="lead"> {{session()->get('message')}}</p>
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible">
                <p class="lead"> {{session()->get('message')}}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
    @endif

    <!-- Default box -->
        <div class="card">
            <form name="db2" action="" method="{{route('user.update')}}">
                @csrf
                <div class="card-body">
                    <table border="1" width="100%" cellspacing="0" cellpadding="0" align="Centre"
                           class="mt-2 mb-4">
                        <thead>
                        <tr>
                            <th width="33%" colspan="1" class="text-center"><a href="#"><img
                                        src="{{ asset('dashboard/dist/img/zesco1.png')}}" title="ZESCO" alt="ZESCO"
                                        width="25%"></a></th>
                            <th width="33%" colspan="4" class="text-center">User Details Edit</th>
                            <th width="34%" colspan="1" class="p-3">Doc Number:<br>SC. .FORM. <br>Version:
                            </th>
                        </tr>
                        </thead>
                    </table>

                    <div class="row">
                        <div class="col-6 form-group mt-4">
                            <label for="user_id"> USERNAME: <span class="required">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" readonly
                                   value="{{$user->name}}">
                        </div>
                        <div class="col-6 form-group mt-4">
                            <label for="device_id"> EMAIL ADDRESS: <span class="required">*</span></label>
                            <input type="text" class="form-control" id="email" name="email"
                                   value="{{$user->email}}" readonly>
                        </div>
                        <div class="col-6 form-group mt-4">
                            <label for="location_id"> LOCATION ID: <span class="required">*</span></label>
                            <input type="text" class="form-control" id="location_id" name="location_id"
                                   value="{{$user->location->location_name}}" readonly>
                        </div>
                        <div class="col-6 form-group mt-4">
                            <label for="user_type_id"> USER TYPE ID: <span class="required">*</span></label>
                            <input type="text" class="form-control" id="user_type_id" name="user_type_id"
                                   value="{{$user->usertype->user_type_name}}" readonly>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="row">
                        @can(config('chilolezo.permissions.user_update'))
                        <div id="submit_button" class="col-12 text-center">
                            <a class="btn btn-primary" href="#"> Edit</a>
                            <a class="btn btn-lg btn-secondary" href="{{route('user.index')}}">Back</a>
                        </div>
                        @endcan
                    </div>
                </div>
                <!-- /.card-footer-->
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection


@push('custom-scripts')

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('dashboard/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>

    <!-- page script -->
    <script>
        $(function () {

            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>


@endpush
