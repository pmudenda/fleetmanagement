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
                <div class="col-xs-12 col-sm-4 pl-0">

                    <div class="card card-outline">
                        <div class="card-header">
                            <div class="card-tools">
                                {{--<button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        data-toggle="tooltip"
                                        title="Collapse">
                                    <i class="fas fa-minus"></i></button>--}}
                            </div>
                        </div>
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <a href="#">
                                    @if(!empty($user->avatar))
                                        <img class="profile-user-img img-fluid img-circle" width="100%"
                                             src="{{ asset('storage/user_avatar/' . $user->avatar) }}"
                                             alt="Image not found"
                                             @if( Auth::user()->id==$user->id)
                                                 title="Click Here to Edit Image"
                                             data-toggle="modal"
                                             data-target="#modal-edit-profile"
                                            @endif
                                        />
                                    @else
                                        <img class="profile-user-img img-fluid img-circle" width="100%"
                                             src="{{ asset('assets/media/avatars/avatar.png') }}"
                                             alt="Image not found"
                                             @if(Auth::user()->id==$user->id)
                                                 title="Click Here to Edit Image"
                                             data-toggle="modal"
                                             data-target="#modal-edit-profile"
                                            @endif
                                        />
                                    @endif
                                </a>
                            </div>

                            <h3 class="profile-username text-center">{{ $user->name }}</h3>

                            <p class="text-muted text-center">{{ $user->job_title ?? 'Position' }}</p>

                            <p class="text-muted text-center">{{ $user->man_no ?? '' }}</p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Man Number</b> <a class="float-right">{{ $user->staff_no }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>NRC</b> <a class="float-right">{{ $user->nrc }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Phone</b> <a class="float-right">{{ $user->mobile_no }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Extension</b> <a class="float-right">{{ $user->phone ?? '' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>
                                        Assigned Profile
                                    </b>
                                    <a class="float-right">
                                        <span class="badge badge-success">
                                        {{$user->roles->count()}}
                                        </span>
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b>
                                    <a class="float-right">{{ $user->email }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Status</b> <a class="float-right">
                                        @if($user->con_st_code == '01')
                                            <span class="badge badge-success p-2">
                                                Active
                                            </span>
                                        @else
                                            {{$user->con_st_code ?? '--'}}
                                        @endif
                                    </a>
                                </li>

                                <li class="list-group-item">
                                    <b>Total Logins</b>
                                    <a class="float-right">
                                       <span class="badge badge-success p-2">
                                             {{ $user->total_logins }}
                                       </span>
                                    </a>
                                </li>
                            </ul>

                            <p class="text-muted">
                                <strong>System Profile:</strong>
                                @foreach ($user->roles() as $groupName)

                                    {{--{{strtoupper($groupName->description)}}--}}
                                @endforeach
                            </p>
                        </div>
                        <!-- /.card-body -->
                        @can('rights.search_user')
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="">
                                            <form class="row" method="post" action="">
                                                @csrf
                                                <div class="input-group input-group-sm">
                                                    <input class="form-control form-control-sm" type="search"
                                                           name="search" placeholder="Enter Man Number/Name"
                                                           aria-label="Enter Search Term">
                                                    <div class="input-group-addon">
                                                        <button class="btn btn-primary " type="submit">
                                                            <i class="fas fa-search"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- PROFILE FORM -->
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-xs-12 col-sm-7 pl-0">
                    <div class="card">
                        <div class="card-header ">
                            <div class="card-title">
                                <span>Select User to delegate your profile</span>
                            </div>
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
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <!-- form start -->
                                            <form role="form-new"
                                                  method="post"
                                                  action="{{route('user.profile.delegation.store')}}">
                                                @csrf
                                                <div class="modal-body">

                                                    <div class="row">
                                                        <div class="row">

                                                            <div class="col-xs-12 col-sm-6 col-md-6">
                                                                <div class="container-fluid pl-0">
                                                                    <div class="row">
                                                                        <div class="form-group row">
                                                                            <div
                                                                                class="col-xs-12 col-sm-6
                                                                                col-md-5
                                                                                col-lg-4 control-input-wrapper">
                                                                                <div class="control-input">
                                                                                    <div class=""
                                                                                         style="position: relative;">
                                                                                        <label
                                                                                            class="form-check-inline">
                                                                                            Select User
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="col-xs-12 col-sm-6
                                                                                col-md-7 col-lg-6">
                                                                                <select class="form-control select2"
                                                                                        name="user_id"
                                                                                        required
                                                                                        style="width: 100%;">
                                                                                    <option disabled value="" selected>
                                                                                        Select User
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="col-xs-12 col-sm-6 col-md-6">
                                                                <div class="container-fluid pl-0">
                                                                    <div class="row">
                                                                        <div class="form-group row">
                                                                            <div
                                                                                class="col-xs-12 col-sm-6
                                                                                col-md-5
                                                                                col-lg-4 control-input-wrapper">
                                                                                <div class="control-input">
                                                                                    <div class="link-field ui-front"
                                                                                         style="position: relative;">
                                                                                        <label
                                                                                            class="form-check-inline">
                                                                                            Delegation Start-Date
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="col-xs-12 col-sm-6
                                                                                col-md-7 col-lg-6">
                                                                                <input type="text"
                                                                                       class="form-control
                                                                                       form-control-sm"
                                                                                       id="cost_centre_code"
                                                                                       value="14456"
                                                                                       name="cost_centre_code" required
                                                                                       readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
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
                                                                            {{$profile->profiles->name ?? ''}}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <!-- /.form-group -->
                                                        </div>
                                                        <div class="col-6 ">
                                                            <div class="form-group">
                                                                <label for="eform_id">Delegation Start-Date</label>
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
                                                                <label for="delegation_end_date">

                                                                </label>
                                                                <input type="date"
                                                                       class="form-control"
                                                                       name="delegation_end_date"
                                                                       id="delegation_end_date">
                                                            </div>
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
    <script src="{{asset('modules/userManagement/employee.search.js')}}"></script>
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

