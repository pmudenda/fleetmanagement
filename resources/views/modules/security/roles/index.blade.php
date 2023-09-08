@php use Carbon\Carbon; @endphp
@extends('layouts.app')

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush


@section('content')

    <x-content-header :pageTitle="'System Profile'"
                      :activeCrumb="'System Profile'"
                      :link="'home'"
                      :linkText="'Home'"/>

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
                                            data-bs-target="#createProfileModal">
                                        <i class="fas fa-user-shield "></i>
                                        Create Profile
                                    </button>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="groupsTable"
                                       aria-label="Profile List"
                                       class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer">
                                    <thead>
                                    <tr>
                                        <th>NAME</th>
                                        <th>SYSTEM ID</th>
                                        <th>PERMISSIONS</th>
                                        <th>DATE REGISTERED</th>
                                        {{--@canany([config('rights.role_access'), config('rights.role_show')])--}}
                                        <th>ACTION</th>
                                        {{--@endcanany--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($roles as $item)
                                        <tr>
                                            <td>
                                                {{$item->name}}
                                            </td>
                                            {{-- @canany([config('rights.role_access'), config('rights.role_show')])--}}
                                            <td>
                                                {{$item->slug}}
                                            </td>
                                            <td>
                                                {{$item->permissions->count() }}
                                            </td>
                                            <td>
                                                {{Carbon::parse($item->created_at)->format('dd/m/y')}}
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-light
                                                            btn-active-light-primary
                                                            btn-sm dropdown-toggle"
                                                            type="button"
                                                            id="dropdownMenuButton1"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        @can(config('rights.role_update'))
                                                            <li>
                                                                <button class="btn btn-sm"
                                                                        data-sent_data="{{$item}}"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#editModal{{$item->id}}">
                                                                    <i class="fas fa-pencil-alt pull-right"> </i>
                                                                    Edit
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            </li>
                                                            <li>
                                                                <a href="{{route('roles.show', $item->id)}}"
                                                                   class="btn btn-sm">
                                                                    <i class="fas fa-eye pull-right"></i>
                                                                    Permissions
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

    <!-- Profile Addition Modal -->
    <div class="modal fade"
         id="createProfileModal"
         tabindex="-1"
         role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Create Role
                    </h5>
                    <button type="button" class="close"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form name="db2" method="post" action="{{route('roles.store')}}">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="row">
                                    <div class="col-12 form-group mt-4">
                                        <label class="field-required" for="name">
                                            ROLE NAME:
                                        </label>
                                        <input type="text"
                                               class="form-control"
                                               id="name"
                                               name="name"
                                               required
                                               maxlength="100"
                                               placeholder="e.g Administrator">
                                    </div>
                                    <div class="col-12 form-group mt-4">
                                        <label class="field-required" for="slug">
                                            SLUG:
                                        </label>
                                        <input type="text"
                                               class="form-control"
                                               id="slug"
                                               name="slug"
                                               required
                                               maxlength="100"
                                               placeholder="Enter Slug">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                    class="btn btn-sm btn-secondary"
                                    data-bs-dismiss="modal">
                                Close
                            </button>
                            @can(config('rights.role_create'))
                                <button type="submit"
                                        title="Create New system Role"
                                        class="btn btn-sm btn-success">
                                    Create
                                </button>
                            @endcan
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach($roles as $item)
        <div class="modal fade profileEditModal" id="editModal{{$item->id}}" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Role: {{$item->name}}</h5>
                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form name="roleUpdateForm" method="post" action="{{route('roles.update', $item->id )}}">
                            {{method_field('POST')}}
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12  form-group mt-4">
                                        <input type="number" id="id" name="id"
                                               value="{{$item->id}}" required hidden>
                                        <label for="name" class="field-required">
                                            Profile Name:
                                        </label>
                                        <input type="text"
                                               class="form-control"
                                               value="{{$item->name}}"
                                               id="name"
                                               name="name" required maxlength="100"
                                        />
                                    </div>
                                    {{--<div class="col-lg-12 col-md-12 col-sm-12  form-group mt-4">
                                        <label for="slug" class="field-required">
                                            System Name(slug):
                                        </label>
                                        <input type="text"
                                               class="form-control"
                                               value="{{$item->slug}}"
                                               id="slug"
                                               name="slug"  maxlength="100"
                                        />
                                    </div>--}}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button"
                                        class="btn btn-sm btn-secondary"
                                        data-bs-dismiss="modal">
                                    Close
                                </button>
                                @can(config('rights.role_update'))
                                    <button type="submit"
                                            class="btn btn-sm btn-warning">
                                        Update
                                    </button>
                                @endcan
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Delete Modal -->
        <div class="modal fade" id="deleteModal{{$item->id}}" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"
                            id="exampleModalLabel">
                            Delete Group: {{$item->name}}
                        </h5>
                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"></button>
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
                                <button type="button"
                                        class="btn btn-secondary"
                                        data-bs-dismiss="modal">
                                    Close
                                </button>
                                <button
                                        type="submit"
                                        class="btn btn-danger">
                                    Delete
                                </button>
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
        (function (tmsApp) {
            const appMessages = {
                profileUpdateTitle: "Profile Update",
                validationFailureMessage: "Sorry, the data did not pass validation check," +
                    "check the data and try again.",
                profileUpdateDefaultMessage: "Profile Updated Successfully"
            };

            tmsApp.initDatatable("#groupsTable", true, true, []);

            $('[name="roleUpdateForm"]').on('submit', function (e) {
                e.preventDefault();
                e.stopPropagation();
                let $form = this;

                if (!$($form).valid()) {
                    toastr.warning(appMessages.validationFailureMessage);
                    return;
                }

                let element = document.querySelector('.profileEditModal');
                let modal = null;
                if (element) {
                    modal = bootstrap.Modal.getOrCreateInstance(element);
                }

                let formData = new FormData($form);
                tmsApp.play_alert('sound-submit');
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
                                : appMessages.profileUpdateDefaultMessage;

                            tmsApp.showSystemMessage(
                                appMessages.profileUpdateTitle,
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
                                tmsApp.systemError(appMessages.profileUpdateTitle,
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
                        tmsApp.showErrorMessages(xhr, appMessages.profileUpdateTitle);
                    },
                    'POST'
                );
            }).validate(
                {
                    errorClass: "error-class",
                    validClass: "valid-class",
                    errorElement: 'div',
                    errorPlacement: function (error, element) {
                        if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    onError: function () {
                        $('.input-group.error-class')
                            .find('.help-block.form-error')
                            .each(function () {
                                $(this).closest('.form-group')
                                    .addClass('error-class')
                                    .append($(this));
                            });
                    },
                    rules: {
                        name: {
                            required: true
                        }
                    },
                    messages: {
                        name: {
                            required: "Permission name is required"
                        }
                    }
                }
            );

        })(window.tmsApp || {});
    </script>
@endpush
