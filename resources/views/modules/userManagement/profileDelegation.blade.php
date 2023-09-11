@extends('layouts.app')
@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush
@section('content')
    <x-content-header/>

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
                                {{--<li class="nav-item">
                                    <button class="btn btn-sm btn-outline-primary myBtns btn2" href="#">
                                        <i class="fas fa-upload"></i>
                                        Bulk Upload
                                    </button>
                                </li>--}}
                            </ul>
                        </div>
                        <div class="card-body ">
                            <div class="table-wrap">
                                <div class="table-responsive">
                                    @if(session()->has('message'))
                                        <div class="alert alert-success alert-dismissible">
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
                                        <div class="card-header">
                                            <span>Select User to delegate your profile</span>

                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                        data-toggle="tooltip"
                                                        title="Collapse">
                                                    <i class="fas fa-minus"></i></button>
                                            </div>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <!-- form start -->
                                            <form role="form-new"
                                                  method="post"
                                                  action="{{route('user.profile.delegation.store')}}">
                                                @csrf
                                                <div class="modal-body">

                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>Select User</label>
                                                                <select class="form-control select2" name="user_id"
                                                                        required
                                                                        style="width: 100%;">
                                                                    <option disabled value="" selected>
                                                                        Select User
                                                                    </option>
                                                                    @foreach($users as $user)
                                                                        @if( ($user->functional_unit_id
                                                                            == \Auth::user()->functional_unit_id)
                                                                            && ($user->id  != \Auth::user()->id))
                                                                            <option
                                                                                value="{{$user->id}}">
                                                                                {{$user->name}} : {{$user->staff_no}}
                                                                            </option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <!-- /.form-group -->
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>Select Profile</label>
                                                                <select class="form-control select2"
                                                                        id="profile_select"
                                                                        name="profile" required
                                                                        style="width: 100%;">
                                                                    <option disabled value="" selected>
                                                                        Select Profile to Delegate
                                                                    </option>
                                                                    @foreach($profiles as $profile)
                                                                        <option
                                                                            value="{{$profile->profiles->id ?? ''}}">
                                                                            {{$profile->form->name  ?? ""}} :
                                                                            {{$profile->profiles->code ?? ''}} :
                                                                            {{$profile->profiles->name ?? ''}}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <!-- /.form-group -->
                                                        </div>
                                                        <div class="col-6 ">
                                                            <div class="form-group">
                                                                <label>Select E-Form</label>
                                                                <select class="form-control select2"
                                                                        id="eform_select"
                                                                        name="eform_id" required
                                                                        style="width: 100%;">
                                                                </select>
                                                            </div>
                                                            <!-- /.form-group -->
                                                        </div>
                                                        <div class="col-6 ">
                                                            <div class="form-group">
                                                                <label>Delegation End-Date</label>
                                                                <input type="date"
                                                                       class="form-control"
                                                                       name="delegation_end_date"
                                                                       id="delegation_end_date">
                                                            </div>
                                                            <!-- /.form-group -->
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button"
                                                            class="btn btn-default"
                                                            data-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">
                                                        Submit
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection
@push('scripts')

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
                let formData = new FormData(form);

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
                    'POST',
                )
            })

            $(document).on('click', '.deleteButton', function (e) {
                let recordData = this.getAttribute('data-id');
                console.log(recordData)
                let formData = new FormData();
                formData.append('id', recordData);
                tmsApp.confirm(
                    'Remove Record',
                    "Are you sure you would like to delete this record?",
                    'Yes',
                    'No, Cancel',
                    function () {
                        //
                        tmsApp.asyncPostFormData(
                            document.querySelector('[name="deleteUrl"]').value,
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
                            'POST',
                        )
                    }
                );
            })


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

