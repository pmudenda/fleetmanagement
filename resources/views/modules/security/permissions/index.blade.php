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
                                    <button href=""
                                            data-bs-target="#addPermissionModal"
                                            data-bs-toggle="modal"
                                            title="Create New System Permission"
                                            class="btn btn-sm btn-success pull-right">
                                        <i class="fas fa-user-plus"></i>
                                        Add Access Right
                                    </button>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="rightsTable"
                                       aria-label="Permissions table"
                                       role="table"
                                       class="table align-middle table-row-dashed no-footer">
                                    <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Name</th>
                                        <th>Date Created</th>
                                        @canany(config('rights.permission_edit'),config('rights.permission_destroy'))
                                            <th>Action</th>
                                        @endcanany
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($permissions as $item)
                                        <tr>
                                            <td>
                                                {{$item->description}}
                                            </td>
                                            <td>
                                                {{$item->name}}
                                            </td>
                                            <td>
                                                {{Carbon::parse($item->created_at)->format('d/m/y')}}
                                            </td>
                                            @canany(config('rights.permission_edit'),
                                                    config('rights.permission_destroy'))
                                                <td>
                                                    <div class="dropdown">
                                                        <button
                                                            class="btn btn-light
                                                        btn-active-light-primary btn-sm dropdown-toggle"
                                                            type="button"
                                                            id="dropdownMenuButton1"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            Actions
                                                        </button>
                                                        <ul class="dropdown-menu"
                                                            aria-labelledby="dropdownMenuButton1">
                                                            @can(config('rights.permission_edit'))
                                                                <li>
                                                                    <a href="#"
                                                                       class="dropdown-item"
                                                                       data-sent_data="{{$item}}"
                                                                       data-bs-toggle="modal"
                                                                       data-bs-target="#editModal{{$item->id}}">
                                                                        <i class="fas fa-edit pull-right"></i>
                                                                        Edit
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
                                                                        <i class="fas fa-trash pull-right"></i>
                                                                        Remove
                                                                    </a>
                                                                </li>
                                                            @endcan
                                                        </ul>
                                                    </div>
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

    <div class="modal fade" id="addPermissionModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Add Permission
                    </h5>
                    <button type="button" class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <x-error-view/>
                    <div class="card">
                        <form name="addPermissionForm"
                              action="{{route('permissions.store')}}" method="post">
                            @csrf
                            <div class="card-header">
                                <div class="card-title">
                                    Add System Permission <i class="ml-2 fas fa-user-shield"></i>
                                </div>
                            </div>
                            <div class="card-body pb-2">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mt-4">
                                            <label for="name"
                                                   class="field-required">
                                                Name:
                                            </label>
                                            <input type="text"
                                                   class="form-control"
                                                   id="name"
                                                   name="name"
                                                   required
                                                   maxlength="100"
                                                   placeholder="Enter Permission name">
                                        </div>
                                        <div class="form-group mt-4">
                                            <label for="description" class="field-required">
                                                Description:
                                            </label>
                                            {{--<input type="text" class="form-control" id="name" name="name" required
                                                   maxlength="100"
                                                   placeholder="create-status">--}}
                                            <textarea type="text"
                                                      style="min-height: 29px"
                                                      class="form-control"
                                                      id="description"
                                                      name="description"
                                                      required
                                                      maxlength="255"
                                            ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer pt-2">
                                <div class="row pull-left">
                                    <div id="submit_button" class="col-12 text-center">
                                        @can(config('rights.permission_create'))
                                            <input class="btn btn-sm btn-success"
                                                   type="submit"
                                                   value="Submit">
                                        @endcan
                                        <input
                                            class="btn btn-sm btn-secondary"
                                            type="reset"
                                            value="Clear"
                                            name="reset_form">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach($permissions as $item)
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
                                        <input type="hidden" id="id"
                                               name="id"
                                               value="{{$item->id}}"
                                               required/>
                                        <input type="hidden"
                                               class="form-control"
                                               value="{{$item->name}}" id="name"
                                               name="name"
                                               required
                                        />
                                        <input type="hidden"
                                               class="form-control"
                                               value="{{$item->slug}}" id="slug"
                                               name="slug"
                                               required
                                        />
                                        <label for="description" class="field-required">
                                            PERMISSION DESCRIPTION:
                                        </label>
                                        <textarea type="text"
                                                  style="min-height: 29px"
                                                  class="form-control"
                                                  id="description"
                                                  name="description"
                                                  required maxlength="100"
                                        >{{$item->description}}</textarea>
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
        </div>

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
                                        <textarea readonly type="text"
                                                  class="form-control"
                                                  id="description" name="description" required maxlength="100"
                                        >{{$item->description}}</textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary"
                                        data-bs-dismiss="modal">
                                    Close
                                </button>
                                @can(config('rights.permission_destroy'))
                                    <button type="submit"
                                            class="btn btn-sm btn-danger">Yes
                                    </button>
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
        (function (tmsApp) {
            tmsApp.initDatatable("#rightsTable", true, true, [
                {
                    "orderable": false,
                    "searchable": false,
                    "targets": [0]
                }
            ]);

            $('[name="addPermissionForm"]').on('submit', function (e) {
                e.preventDefault();
                e.stopPropagation();
                let $form = document.forms['addPermissionForm'];

                if (!$($form).valid()) {
                    toastr.warning("Sorry, the data did not pass validation check, check the data and try again.");
                    return;
                }

                let myModalEl = document.querySelector('#addPermissionModal');
                let modal =  null;
                if (myModalEl) {
                    modal = bootstrap.Modal.getOrCreateInstance(myModalEl);
                }

                let formData = new FormData($form);
                tmsApp.play_alert('sound-submit');
                tmsApp.asyncPostFormData(
                    $form.action,
                    formData,
                    function (response) {
                        console.log(response);
                        window.loaderMessage = "Loading... please wait";
                        if (response.hasOwnProperty("state") && response.state === 'success') {
                            if (modal) {
                                modal.hide();
                            }
                            const message = response.message > ""
                                ? response.message
                                : "Permission Defined Successfully";

                            tmsApp.showSystemMessage(
                                "Permission Definition",
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
                                tmsApp.systemError("Permission Definition", response.message);
                            }

                            if (modal) {
                                modal.hide();
                                //window.loaderVisible = false;
                            }
                        }
                    },
                    function (xhr) {
                        console.log(xhr);
                        tmsApp.play_alert('sound-error');
                        tmsApp.showErrorMessages(xhr, "Permission Definition",);
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
                        $('.input-group.error-class').find('.help-block.form-error').each(function () {
                            $(this).closest('.form-group').addClass('error-class').append($(this));
                        });
                    },
                    rules: {
                        name: {
                            required: true
                        },
                        description: {
                            required: true
                        }
                    },
                    messages: {
                        description: {
                            required: "The description of the permission is required"
                        },
                        name: {
                            required: "Permission name is required"
                        },
                    }
                }
            );
        })(window.tmsApp || {});
    </script>

@endpush
