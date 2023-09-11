@extends('layouts.app')
@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush

@section('content')
    <x-content-header :pageTitle="'ROLE DETAILS'" :activeCrumb="'Role Details'" :link="'roles.index'"
                      :linkText="'Roles'"/>
    <section class="content">
        <x-error-view></x-error-view>

        <div class="container-fluid">
            <div class="row">

                <div class="col-md-4 col-lg-4 col-sm-4">
                    <div class="card card-solid">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="my-3 text-uppercase">{{$role->description}} DETAILS</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-sm-12 pl-0">
                                <div class="text-center mb-5">
                                    <a href="#">
                                        <i class="nav-icon fas fa-shield-alt " style="font-size:100px;"></i>
                                    </a>
                                </div>
                                <h3 class="profile-username text-center">Name: {{ $role->description }}</h3>
                                <h3 class="profile-username text-center">Slug: {{ $role->slug }}</h3>
                            </div>

                        </div>
                        <div class="tab-content p-3" id="nav-tabContent"></div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-6 col-sm-12">
                    <div class="card card-solid">
                        <div class="card-body">
                            <h3 class="my-3 text-uppercase">Attached Permissions</h3>

                            <div class="table-responsive mt-10 ">
                                <table aria-label="unassigned permissions"
                                       role="table"
                                       id="attachedPermissions"
                                       class="table table-striped">
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
                                                            <button class="btn btn-sm btn-danger"
                                                                    data-sent_data="{{$item}}"
                                                                    data-toggle="modal"
                                                                    data-target="#detach-permission{{$item->id}}">
                                                                <i class="fas fa-trash"></i> Revoke Permission
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
                                            class="btn btn-success btn-sm">
                                        <i class="fas fa-paper-plane"></i>
                                        Assign Permission
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="attach-permission{{$role->id}}" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Attach Permission: {{$role->name}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form name="attachPermissionsForm"
                          method="post"
                          action="{{route('roles.assign.permission')}}">
                        @csrf
                        <input type="hidden" value="{{$role->id}}" name="roleId">
                        <div class="card-body">
                            <div class="row">
                            <span class="text-danger">
                                Select the permissions you want to assign
                            </span>
                            </div>
                            <table id="permissionsTable"
                                   aria-label="permission table"
                                   role="table"
                                   class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($permissions->whereNotIn( 'id',
                                    $role->permissions->pluck('id')->toArray()) as $item)
                                    <tr>
                                        <td>
                                            <input type="checkbox"
                                                   id="permission_ids"
                                                   name="permission_ids[]"
                                                   value="{{$item->id}}">
                                        </td>
                                        <td>
                                            {{$item->description}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                    class="btn btn-default"
                                    data-dismiss="modal">Close
                            </button>
                            @can(config('rights.permission_attach'))
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fas fa-save"></i>
                                    Attach
                                </button>
                            @endcan
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach($role->permissions as $item)
        <!-- Device Delete Modal -->
        <div class="modal fade" id="detach-permission{{$item->id}}" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detach Permission: {{$item->name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form name="db2" method="post" action="{{route('roles.revoke.permission')}}">
                            @csrf
                            <input type="hidden" value="{{$role->id}}" name="roleId">
                            <div class="card-body">
                                <div class="row">
                                    <span class="text-danger">Are you sure you want to detach?</span>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12  form-group mt-4">
                                        <label for="name">Permission Name: <span class="required">*</span></label>
                                        <input type="text" class="form-control" value="{{$item->name}}" id="name"
                                               name="name" required>
                                        <input type="hidden"
                                               class="form-control"
                                               value="{{$item->id}}"
                                               id="permission_id"
                                               name="permission_id"
                                               required readonly/>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                @can(config('rights.permission_revoke'))
                                    <button type="submit" class="btn btn-danger">Revoke Permission</button>
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

    <script>
        (function (appInstance) {
            let $permissionsTable = '';

            const appMessages = {
                permissionAlertWindowTitle: "Permission Assignment",
                validationFailureMessage: "Sorry, the data did not pass validation check," +
                    "check the data and try again.",
                permissionsAttachedDefaultMessage: "Permission Assigned Successfully"
            };

            $(document).ready(function () {
                $('#attachedPermissions').DataTable({
                    'order': [],
                    "pageLength": 10,
                    "responsive": true,
                    "searchable": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    'columnDefs': [],
                    "buttons": []
                })
                    .buttons().container()
                    .appendTo('#attachedPermissions' + '_wrapper .col-md-6:eq(0)');

                $permissionsTable = $('#permissionsTable').DataTable({
                    'order': [],
                    "pageLength": 10,
                    "responsive": true,
                    "searchable": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    'columnDefs': [],
                    "buttons": []
                });

                $permissionsTable.buttons().container()
                    .appendTo('#permissionsTable' + '_wrapper .col-md-6:eq(0)');

                $('[name="attachPermissionsForm"]').on('submit', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    let $form = this;

                    let $checkboxes = $permissionsTable.$('input[type=checkbox]:checked');
                    const formData = {
                        roleId: $('[name="roleId"]').val()
                    };

                    let obj = [];
                    for (const $checkbox of $checkboxes) {
                        console.log($checkbox.value);
                        obj.push({'permissionId': $checkbox.value});
                    }

                    formData['permissionIds'] = obj;

                    let element = document.querySelector('.profileEditModal');
                    let modal = null;
                    if (element) {
                        modal = bootstrap.Modal.getOrCreateInstance(element);
                    }

                    tmsApp.asyncPostFormData(
                        $form.action,
                        formData,
                        function (response) {
                            window.loaderMessage = "Loading... please wait";
                            if (response.hasOwnProperty("state") && response.state === 'success') {
                                if (modal) {
                                    modal.hide();
                                }
                                const message = response.message > ""
                                    ? response.message
                                    : appMessages.permissionsAttachedDefaultMessage;

                                tmsApp.showSystemMessage(
                                    appMessages.permissionAlertWindowTitle,
                                    message,
                                    function () {
                                        window.location.reload();

                                    },
                                    "success"
                                );
                            } else {
                                tmsApp.play_alert('sound-error');
                                if (!Util.isEmpty(response.errors)) {
                                    if (response.errors) {
                                        tmsApp.printErrorMsg(response.errors);
                                    }
                                } else if (!Util.isEmpty(response.message)) {
                                    tmsApp.systemError(appMessages.permissionAlertWindowTitle,
                                        response.message);
                                }

                                if (modal) {
                                    modal.hide();
                                }
                            }
                        },
                        function (xhr) {
                            console.log(xhr);
                            tmsApp.play_alert('sound-error');
                            tmsApp.showErrorMessages(xhr, appMessages.permissionAlertWindowTitle);
                        },
                        'POST'
                    );

                    return false;
                });

            });
        })(window.tmsApp || {});
    </script>
@endpush
