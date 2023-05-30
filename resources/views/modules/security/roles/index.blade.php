@php use Carbon\Carbon; @endphp
@extends('layouts.app')


@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet"
          href="{{ asset('dashboard/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush


@section('content')
    <x-content-header :pageTitle="'System Groups'" :activeCrumb="'System Groups'" :link="'home'" :linkText="'Home'"/>
    <!-- Main content -->
    <section class="content">
        <x-error-view/>

        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                @can(config('rights.role_create'))
                                    <button class="btn btn-sm btn-success m-1"
                                            data-sent_data="{{$roles}}"
                                            data-bs-toggle="modal" title="Create Group"
                                            data-bs-target="#create-Device">
                                        <i class="fas fa-user-shield "></i>
                                        Create Group
                                    </button>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body p-2">

                            <div class="table-responsive mt-10 ">
                                <table id="groupsTable" class="table table-hover ">
                                    <thead>
                                    <tr>
                                        <th>Group Name</th>
                                        @canany([config('rights.role_access'), config('rights.role_show')])
                                            <th>Slug</th>
                                            <th>Rights</th>
                                            <th>Date Created</th>
                                            <th>Action</th>
                                        @endcanany
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($roles as $item)
                                        <tr>
                                            <td>
                                                {{$item->description}}
                                            </td>
                                            @canany([config('rights.role_access'), config('rights.role_show')])
                                                <td>
                                                    {{$item->name}}
                                                </td>
                                                <td>
                                                    {{$item->permissions->count() }}

                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                                                        <li>
                                                            <button class="btn btn-sm btn-outline-dark-primary m-1 "
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editModal{{$item->id}}">
                                                                <i class="fas fa-pencil-alt"> Edit</i>
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>

                                                        <li>
                                                            <button class="btn btn-sm btn-danger m-1"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#deleteModal{{$item->id}}">
                                                                <i class="fas fa-trash">&nbsp;Remove</i>
                                                            </button>
                                                        </li>

                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <a href="{{route('roles.show', $item->id)}}"
                                                               class="btn btn-sm btn-outline-success m-1">
                                                                <i class="fas fa-eye">
                                                                    Details
                                                                </i>
                                                            </a>
                                                        </li>

                                                    </ul>
                                                </td>
                                                <td>
                                                    {{Carbon::parse($item->created_at)->format('dd/m/y')}}
                                                </td>
                                                <td>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        @can(config('rights.role_edit'))
                                                            <li>
                                                                <button class="btn btn-sm btn-outline-dark-primary m-1 "
                                                                        data-sent_data="{{$roles}}"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#editModal{{$item->id}}">
                                                                    <i class="fas fa-pencil-alt"> Edit</i>
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                        @endcan
                                                        @can(config('rights.role_destroy'))
                                                            <li>
                                                                <button class="btn btn-sm btn-danger m-1"
                                                                        data-sent_data="{{$roles}}"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#deleteModal{{$item->id}}">
                                                                    <i class="fas fa-trash">&nbsp;Remove</i>
                                                                </button>
                                                            </li>
                                                        @endcan

                                                        @can(config('rights.role_show'))
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li>
                                                                <a href="{{route('roles.show', $item->id)}}"
                                                                   class="btn btn-sm btn-outline-success m-1">
                                                                    <i class="fas fa-eye">
                                                                        Details
                                                                    </i>
                                                                </a>
                                                            </li>
                                                        @endcan
                                                    </ul>
                                                </td>
                                            @endcanany
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

    @foreach($roles as $item)
        <div class="modal fade" id="editModal{{$item->id}}" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Role: {{$item->name}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form name="db2" method="post" action="{{route('roles.update', $item->id )}}">
                            {{method_field('PATCH')}}
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12  form-group mt-4">
                                        <input type="number" id="id" name="id" value="{{$item->id}}" required hidden>
                                        <label for="name"> Group: <span class="required">*</span></label>
                                        <input type="text" class="form-control" value="{{$item->name}}" id="name"
                                               name="name" required maxlength="100"
                                        >
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12  form-group mt-4">
                                        <label for="slug"> Name: <span class="required">*</span></label>
                                        <input type="text" class="form-control" value="{{$item->slug}}" id="slug"
                                               name="slug" required maxlength="100"
                                        >
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close
                                </button>
                                @can(config('rights.role_update'))
                                    <button type="submit" class="btn btn-warning">Update</button>
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
                        <h5 class="modal-title" id="exampleModalLabel">Delete Group: {{$item->name}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form name="db2" method="post" action="{{route('roles.destroy', $item->id )}}">
                            {{method_field('DELETE')}}
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <span>Are you sure you want to remove this Group?</span>
                                    <div class="col-lg-12 col-md-12 col-sm-12  form-group mt-4">
                                        <input type="text" id="location_id_delete" value="{{$item->id}}" name="id"
                                               required hidden>
                                        <label for="name"> Group: <span class="required">*</span></label>
                                        <input readonly type="text" class="form-control" value="{{$item->name}}"
                                               id="name" name="name" required maxlength="100"
                                        >
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <!-- /.col -->
        </div>
    @endforeach
@endsection

<!-- Device Update Modal -->
<div class="modal fade" id="create-Device" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="db2" method="post" action="{{route('roles.store')}}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="row">
                                <div class="col-6 form-group mt-4">
                                    <label for="name"> ROLE NAME: <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required
                                           maxlength="100"
                                           placeholder="e.g Administrator">
                                </div>
                                <div class="col-6 form-group mt-4">
                                    <label for="slug"> SLUG: <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="slug" name="slug" required
                                           maxlength="100"
                                           placeholder="Enter Slug">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        @can(config('rights.role_create'))
                            <button type="submit" title="Create New system Role" class="btn btn-success">
                                Create
                            </button>
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

    <!-- page script -->
    <script>
        $(function () {
            $("#groupsTable").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#groupsTable_wrapper .col-md-6:eq(0)');
        });
    </script>

@endpush
