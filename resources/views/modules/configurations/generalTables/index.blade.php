@extends('layouts.app')
@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush
@section('content')
    <x-content-header :pageTitle="$title" :activeCrumb="'Define '. $title" :link="'home'"
                      :linkText="$title"/>

    <section class="content">
        <x-error-view/>
        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <div class="col-md-12 pl-0">
                    <div class="card">
                        <div class="card-header ">
                            <div class="card-title">
                                {{$title}}
                            </div>
                            <ul class="nav nav-pills card-header-pills justify-content-end">
                                <li class="nav-item">
                                    <button class="btn btn-success btn-sm myBtns btn1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#createRecordModal"
                                            data-bs-whatever="@mdo" href="#">
                                        <i class="fas fa-plus"></i>
                                        Add
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="btn btn-sm btn-outline-primary myBtns btn2" href="#">
                                        <i class="fas fa-upload"></i>
                                        Bulk Upload
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body ">
                            <div class="table-wrap">
                                <div class="table-responsive">
                                    <table id="recordsTable" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Name</th>
                                            @if(str_contains(strtolower($type) ,"status"))
                                                <th>Active</th>
                                            @else
                                                <th>Status</th>
                                            @endIf
                                            <th>Action</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @if($title == 'nothing')
                                            <tr>
                                                <td colspan="4">No Records Found</td>
                                            </tr>
                                        @else
                                            @foreach($entries as $entry)
                                                <tr data-id="{{$entry->id}}">
                                                    <td class="code">
                                                        {{$entry->code}}
                                                    </td>
                                                    <td class="name">
                                                        {{$entry->name}}
                                                    </td>
                                                    <td class="status">
                                                        @if($entry->active == '01')
                                                            <span class="badge badge-success p-2">
                                                                    Active
                                                            </span>
                                                        @else
                                                            <span class="badge badge-danger p-2">
                                                                    Inactive
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-light btn-active-light-primary btn-sm dropdown-toggle"
                                                                    type="button"
                                                                    id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                                    aria-expanded="false">
                                                                Actions
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                                {{-- @can(config('rights.edit_vehicle'))--}}

                                                                    <li>
                                                                        <a href="#"
                                                                            id="editButton"
                                                                            data-id="{{$entry->id}}"
                                                                            data-record_name="{{$entry->name}}"
                                                                            data-record_status="{{$entry->active}}"
                                                                            data-record_code="{{$entry->code}}"
                                                                            class="dropdown-item"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#editRecordModal">
                                                                            <i class="fa fa-edit"></i>
                                                                            Edit
                                                                        </a>
                                                                    </li>

                                                                  {{--  <li>
                                                                        <button type="submit" id="deleteButton"
                                                                                data-id="{{$entry->id}}"
                                                                                class="delButton dropdown-item">
                                                                            <i class="fa fa-trash"></i>
                                                                            Delete
                                                                        </button>
                                                                    </li>--}}
                                                                {{--@endcan--}}
                                                            </ul>
                                                        </div>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    </div>

    <div class="modal fade" id="createRecordModal" tabindex="-1" aria-labelledby="createRecordModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    @if($title == 'nothing')
                        <h1 class="modal-title fs-5" id="createRecordModalLabel">Add <span
                                id="modalTitle">Add Record</span></h1>
                    @else
                        <h1 class="modal-title fs-5" id="createRecordModalLabel">Add <span
                                id="modalTitle">{{$title}}</span>
                        </h1>
                    @endif

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form name="configurationTableForm" method="post" action="{{route('save.data')}}">
                    @csrf
                    @if($title != 'nothing')
                        <input type="text" value="{{$type}}" name="type" style="display: none" id="data_type"/>
                    @endif

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Code:</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="data_code"
                                   name="code" required>
                            @error('code')
                            <p class=" errorText">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Description:</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                   id="data_name" required>

                            @error('name')
                            <p class=" errorText">{{$message}}</p>
                            @enderror
                        </div>

                        <div class="mb-3 d-none">
                            {{--@if(str_contains(strtolower($type) ,"status"))
                                <label style="display: none;" for="message-text" class="col-form-label">Active:</label>
                                <select name="status" style="display: none;"
                                        class="form-control @error('status') is-invalid @enderror" id="data_status"
                                        required>
                                    <option value="1">Yes</option>
                                </select>
                                @error('status')
                                <p class=" errorText">{{$message}}</p>
                                @enderror
                            @else
                                <label for="message-text" class="col-form-label">Status:</label>

                                <select name="status" class="form-control @error('status') is-invalid @enderror"
                                        id="data_status"
                                        required>
                                    <option>Select Status</option>
                                    @foreach($statusList as $status)
                                        <option value="{{$status->code}}">{{$status->name}}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                <p class=" errorText">{{$message}}</p>
                                @enderror
                            @endIf--}}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="closeButton" type="button"
                                class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i>
                            Close
                        </button>
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fas fa-save"></i>
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    {{--Edit modal--}}
    <div class="modal fade" id="editRecordModal" tabindex="-1" aria-labelledby="editRecordModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    @if($title == 'nothing')
                        <h1 class="modal-title fs-5" id="editRecordModalLabel">Add <span id="modalTitle">First</span>
                        </h1>
                    @else
                        <h1 class="modal-title fs-5" id="editRecordModalLabel">Edit <span
                                id="modalTitle">{{$title}}</span>
                        </h1>
                    @endif

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form name="configurationEditTableForm"
                      action="{{route('edit.data')}}"
                      method="post">
                    @csrf
                    @method('PUT')
                    @if($title != 'nothing')
                        <input type="text" value="{{$type}}" name="type" style="display: none" id="data_type"/>
                    @endif
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Code:</label>
                            <input type="text" readonly
                                   class="form-control"
                                   id="data_edit_code" name="code">
                        </div>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Description:</label>
                            <input type="text" class="form-control" name="name" id="data_edit_name">
                        </div>
                        {{--<div class="mb-3">
                            <label for="message-text" class="col-form-label">Status:</label>
                            <select name="status" class="form-control" id="data_edit_status">
                                <option>Select Status</option>
                                @foreach($statusList as $status)
                                    <option value="{{$status->code}}">{{$status->name}}</option>
                                @endforeach
                            </select>
                        </div>--}}
                    </div>
                    <div class="modal-footer">
                        <button id="closeEditButton" type="button"
                                class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-success">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <input type="hidden" name="deleteUrl" value="{{route('delete.data')}}">
    {{--Delete Modal--}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Oh No</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are You sure You would Like to delete this</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger">Yes</button>
                </div>
            </div>
        </div>
    </div>


    {{--error Display--}}
    <div class="modal fade" id="errorDisplay" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">OOOPS Erro</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-danger">Okay</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    @include('layouts.partials.dataTableScripts')
    <script>
        (function (tmsApp, $) {
            let id = 0;

            // submission of record data at creation
            $('form[name="configurationTableForm"]').on('submit', function (e) {
                e.preventDefault();
                e.stopPropagation();
                let $form = document.querySelector('form[name="configurationTableForm"]');
                tmsApp.asyncPostFormData(
                    $form.action,
                    new FormData($form),
                    function (asyncResponse) {
                        if ('success' in asyncResponse && !asyncResponse.success) {
                            if (asyncResponse.hasOwnProperty('errors')) {
                                toastr.error(
                                    asyncResponse.message
                                );
                                tmsApp.printErrorMsg(asyncResponse.errors);
                                return
                            }

                            setTimeout(function () {
                                tmsApp.systemError(
                                    'System Configuration',
                                    asyncResponse['message'],
                                    function () {
                                    }, 'error');
                            }, 300);
                            return;
                        }

                        if (asyncResponse.success) {
                            const entry = asyncResponse.payload;
                            tmsApp.showSystemMessage(
                                'System Configuration',
                                asyncResponse['message'],
                                function () {
                                    window.location.reload();
                                },
                                'success'
                            );
                        }
                    },
                    function (xhr, settings, error) {
                        setTimeout(
                            function () {
                                tmsApp.showErrorMessages(xhr, 'System Configuration');
                            },
                            300);
                    }
                )
            });

            document.querySelector("#editRecordModal").addEventListener('shown.bs.modal', (e) => {
                let recordData = e.relatedTarget.dataset;

                id = recordData.id;

                document.getElementById("data_edit_name").value = recordData.record_name
                // document.getElementById("data_type").value = ""
                document.getElementById("data_edit_code").value = recordData.record_code
                //document.getElementById("data_edit_status").value = recordData.record_status

            })

            document.querySelector("#createRecordModal").addEventListener('hidden.bs.modal', (e) => {
                document.getElementById("data_name").value = "";
                document.getElementById("data_code").value = ""
                //document.getElementById("data_status").value = "Select Status"
            })

            document.querySelector("#editRecordModal").addEventListener('hidden.bs.modal', (e) => {
                document.getElementById("data_name").value = "";
                document.getElementById("data_code").value = ""
                //document.getElementById("data_status").value = "Select Status"
            })

            $('form[name="configurationEditTableForm"]').on('submit', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const form = document.querySelector('form[name="configurationEditTableForm"]')
                let formData = new FormData();

                tmsApp.asyncPostFormData(
                    form.action,
                    formData,
                    function (asyncResponse) {
                        if ('success' in asyncResponse && !asyncResponse.success) {
                            if (asyncResponse.hasOwnProperty('errors')) {
                                toastr.error(
                                    asyncResponse.message
                                );
                                tmsApp.printErrorMsg(asyncResponse.errors);
                                return
                            }

                            setTimeout(function () {
                                tmsApp.systemError(
                                    'System Configuration',
                                    asyncResponse['message'],
                                    function () {
                                    }, 'error');
                            }, 300);
                            return;
                        }

                        if (asyncResponse.success) {
                            const entry = asyncResponse.payload;
                            tmsApp.showSystemMessage(
                                'System Configuration',
                                asyncResponse['message'],
                                function () {
                                    window.location.reload();
                                },
                                'success'
                            );
                        }
                    },
                    function (xhr, settings, error) {
                        setTimeout(
                            function () {
                                tmsApp.showErrorMessages(xhr, 'System Configuration');
                            },
                            300);
                    },
                    'PUT',
                )


            })

            $(document).on('click', '.delButton', function (e) {
                let recordData = e.currentTarget.dataset;
                console.log(recordData)
                tmsApp.confirm(
                    'Remove Record',
                    "Are you sure you would like to delete  ?",
                    'Yes',
                    'No, Cancel',
                    function () {
// deleteUrl
                    },
                    function () {

                    }
                );
            })

            function launchDeleteModal(recordData, id) {
                let modalElement = document.getElementById(id);
                let modal = new bootstrap.Modal(modalElement);
                modal.show();

                let modalBody = modalElement.querySelector(".modal-body");
                modalBody.innerHTML = "Are you sure you would like to delete  ?";

                let modalButton = modalElement.querySelector(".btn-danger");
                modalButton.addEventListener("click", function () {
                    $.ajax({
                        url: "/delete/" + recordData.id,
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            let tableRow = $("#myTable tbody tr[data-id='" + recordData.id + "']");
                            tableRow.remove();
                        },

                    })
                    modal.hide();
                });
            }

            function launchErrorModal(message, id) {
                const modalElement = document.getElementById(id);
                let modal = new bootstrap.Modal(modalElement);
                modal.show();

                let modalBody = modalElement.querySelector(".modal-body");
                modalBody.innerHTML = message;

                let modalButton = modalElement.querySelector(".btn-danger");
                modalButton.addEventListener("click", function () {
                    modal.hide();
                });
            }

            tmsApp.initDatatable("#recordsTable", true, true);
        })(window.tmsApp || {}, jQuery);
    </script>
@endpush

