@php use Carbon\Carbon; @endphp
@extends('layouts.app')

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush


@section('content')

    <x-content-header :pageTitle="'System Access Rights'"
                      :activeCrumb="'System Rights'"
                      :link="'home'"
                      :linkText="'Home'"/>

    <!-- Main content -->
    <section class="content">
        <x-error-view/>
        <div class="container-fluid">

            <div class="row">
                <!-- Left col -->
                <div class="col-md-12">
                    <!-- TABLE: LATEST ORDERS -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                @can(config('rights.permission_create'))
                                    <a href="{{route('permissions.create')}}"
                                       title="Create New System Permission"
                                       class="btn btn-sm btn-success pull-right">
                                        <i class="fas fa-user-plus"></i>
                                        Add Access Right
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="rightsTable"
                                       class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer">
                                    <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Name</th>
                                        <th>Date Created</th>
                                        <th>Action</th>
                                       {{-- @can(config('rights.permission_edit'))@endcan--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($permissions as $item)
                                        <tr>
                                            <td>
                                                {{$item->description}}
                                            </td>
                                            {{--@can(config('rights.permission_edit'))@endcan--}}
                                            <td>
                                                {{$item->name}}
                                            </td>
                                            <td>
                                                {{Carbon::parse($item->created_at)->format('d/m/y')}}
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button
                                                        class="btn btn-light btn-active-light-primary btn-sm dropdown-toggle"
                                                        type="button"
                                                        id="dropdownMenuButton1"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        @can(config('rights.permission_edit'))
                                                            <li>
                                                                <a href="#"
                                                                   class="dropdown-item"
                                                                   data-sent_data="{{$item}}"
                                                                   data-bs-toggle="modal"
                                                                   data-bs-target="#editModal{{$item->id}}">
                                                                    <i class="fas fa-edit"> Edit</i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                        @endcan
                                                        @can(config('rights.permission_destroy'))
                                                            <li>
                                                                <a href="#"
                                                                   class="dropdown-item"
                                                                   data-sent_data="{{$permissions}}"
                                                                   data-bs-toggle="modal"
                                                                   data-bs-target="#deleteModal{{$item->id}}">
                                                                    <i class="fas fa-trash"> Remove</i>
                                                                </a>
                                                            </li>
                                                        @endcan
                                                    </ul>
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </div>

    </section>

    @foreach($permissions as $item)
        <!-- Device Update Modal -->
        <div class="modal fade" id="editModal{{$item->id}}" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Permission {{$item->name}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form name="db2" method="post" action="{{route('permissions.update', $item->id )}}">
                            {{method_field('PATCH')}}
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12  form-group mt-4">
                                        <input type="number" id="id" name="id" value="{{$item->id}}" required hidden>
                                        <label for="name"> PERMISSION NAME: <span class="required">*</span></label>
                                        <input type="text" class="form-control" value="{{$item->name}}" id="name"
                                               name="name" required maxlength="100"
                                        >
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12  form-group mt-4">
                                        <label for="slug"> SLUG: <span class="required">*</span></label>
                                        <input type="text" class="form-control" value="{{$item->slug}}" id="slug"
                                               name="slug" required maxlength="100"
                                        >
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close
                                </button>
                                @can(config('rights.permission_edit'))
                                    <button type="submit" title="Update System Permission"
                                            class="btn btn-sm btn-primary">
                                        <i class="fas fa-paper-plane"></i>
                                        Update
                                    </button>
                                @endcan
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>

        <!-- Status Delete Modal -->
        <div class="modal fade" id="deleteModal{{$item->id}}" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Permission{{$item->name}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form name="db2" method="post" action="{{route('permissions.destroy', $item->id )}}">
                            {{method_field('DELETE')}}
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <span>Are you sure you want to remove this Permission?</span>
                                    <div class="col-lg-12 col-md-12 col-sm-12  form-group mt-4">
                                        <input type="text" id="location_id_delete" value="{{$item->id}}" name="id"
                                               required hidden>
                                        <label for="name"> Name: <span class="required">*</span></label>
                                        <input readonly type="text" class="form-control" value="{{$item->name}}"
                                               id="name" name="name" required maxlength="100"
                                        >
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close
                                </button>
                                @can(config('rights.permission_destroy'))
                                    <button type="submit" class="btn btn-sm btn-danger">Yes</button>
                                @endcan
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>
    @endforeach
@endsection


@push('scripts')

    <!-- DataTables  & Plugins -->
    @include('layouts.partials.dataTableScripts')
    <!-- page script -->
    <script>
        $(function () {
            $("#rightsTable").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"],
                'columnDefs': [
                    {
                        "orderable": false,
                        "searchable": false,
                        "targets": [0]
                    }
                ]
            }).buttons().container().appendTo('#rightsTable_wrapper .col-md-6:eq(0)');
        });
    </script>

@endpush
