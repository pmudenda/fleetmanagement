@extends('layouts.app')
@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet"
          href="{{ asset('dashboard/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush


@section('content')

    <x-content-header :pageTitle="'ROLE DETAILS'" :activeCrumb="'Role Details'" :link="'roles.index'"
                      :linkText="'Roles'"/>
    <!-- Main content -->
    <section class="content">
        <x-error-view></x-error-view>

        <div class="container-fluid">
            <div class="row">

                <div class="col-md-4 col-lg-4 col-sm-4">
                    <div class="card card-solid">
                        <div class="card-body">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="my-3 text-uppercase">{{$role->description}} DETAILS</h3>
                                </div>
                            </div>
                            <div class="col-sm-12 pl-0">
                                <div class="text-center">
                                    <a href="#">
                                        <i class="nav-icon fas fa-shield-alt " style="font-size:100px;"></i>
                                    </a>
                                </div>
                                <h3 class="profile-username text-center">Name: {{ $role->description }}</h3>
                                <h3 class="profile-username text-center">Slug: {{ $role->slug }}</h3>
                            </div>

                        </div>
                        <div class="tab-content p-3" id="nav-tabContent">

                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-6 col-sm-12">
                    <div class="card card-solid">
                        <div class="card-body">
                            <h3 class="my-3 text-uppercase">Attached Permissions</h3>

                            <div class="table-responsive mt-10 ">
                                <table id="example1" class="table">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        @can(config('rights.permission_revoke'))
                                            <th>Slug</th>
                                            <th>Action</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($role->permissions as $item)
                                        <tr>
                                            <td>
                                                {{$item->description}}
                                            </td>
                                            @can(config('rights.permission_revoke'))
                                                <td>
                                                    {{$item->slug}}
                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-06">
                                                            <button class="btn btn-sm btn-danger m-1"
                                                                    data-sent_data="{{$item}}"
                                                                    data-toggle="modal"
                                                                    data-target="#detach-permission{{$item->id}}">
                                                                <i class="fas fa-trash"> Detach</i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <div class="tab-content p-3" id="nav-tabContent">
                            <div class="col-12 ml-2">
                                @can(config('rights.role_attach'))
                                    <button type="button" data-toggle="modal"
                                            data-target="#attach-permission{{$role->id}}"
                                            title="To attach a Permission to this Role"
                                            class="btn btn-warning btn-sm">Attach
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
@endsection


@foreach($role->permissions as $item)
    <!-- Device Delete Modal -->
    <div class="modal fade" id="detach-permission{{$item->id}}" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detach Permission: {{$item->name}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form name="db2" method="post" action="{{route('roles.detach', $role->id )}}">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <span class="text-danger">Are you sure you wan to detach?</span>

                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12  form-group mt-4">
                                    <label for="name"> Permission NAME: <span class="required">*</span></label>
                                    <input type="text" class="form-control" value="{{$item->name}}" id="name"
                                           name="name" required>
                                    <input type="text" hidden class="form-control" value="{{$item->id}}"
                                           id="permission_id" name="permission_id" required readonly
                                    >
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            @can(config('rights.permission_revoke'))
                                <button type="submit" class="btn btn-danger">Detach</button>
                            @endcan
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>

@endforeach

<!-- Device Delete Modal -->
<div class="modal fade" id="attach-permission{{$role->id}}" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Device: {{$role->name}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="db2" method="post" action="{{route('roles.attach', $role->id )}}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <span class="text-danger">Select Permissions to Attach</span>

                        </div>

                        <table id="example1" class="table ">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($permissions->whereNotIn( 'id', $role->permissions->pluck('id')->toArray()) as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" id="permission_ids" name="permission_ids[]"
                                               value="{{$item->id}}">

                                    </td>
                                    <td>
                                        {{$item->name}}
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        @can(config('rights.permission_attach'))
                            <button type="submit" class="btn btn-success">Attach</button>
                        @endcan
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.col -->
</div>



@push('scripts')

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


    <script>
        let sliderImages = document.querySelectorAll('.slide'),
            arrowLeft = document.querySelector('#arrow-left'),
            arrowRight = document.querySelector('#arrow-right'),
            current = 0;

        // clear all images
        function reset() {
            for (let i = 0; i < sliderImages.length; i++) {
                sliderImages[i].style.display = 'none';
            }
        }

        // initialise slider
        function startSlide() {
            reset();
            sliderImages[0].style.display = 'block';
        }

        // show previous
        function slideLeft() {
            reset();
            sliderImages[current - 1].style.display = 'block';
            current--;
        }

        // show next
        function slideRight() {
            reset();
            sliderImages[current + 1].style.display = 'block';
            current++;
        }

        // left arrow click
        arrowLeft.addEventListener('click', function () {
            if (current === 0) {
                current = sliderImages.length;
            }
            slideLeft();
        });

        // right arrow click
        arrowRight.addEventListener('click', function () {
            if (current === sliderImages.length - 1) {
                current = -1;
            }
            slideRight();
        });

        startSlide();
    </script>

    <!-- page script -->
    <script>
        $(function () {

            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": []
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>

@endpush
