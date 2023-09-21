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
                            <form role="form"
                                  method="post"
                                  action="{{route('user.profile.delegation.store')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-5">
                                        <div class="container-fluid pl-0">
                                            <div class="row">
                                                <div class="form-group row">
                                                    <label
                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4"
                                                        for="staff_number">Staff No.:
                                                    </label>
                                                    <div
                                                        class="col-xs-12 col-sm-6 col-md-7 col-lg-8">
                                                        <div class="input-group">
                                                            <input type="text"
                                                                   class="form-control
                                                                   form-control-sm"
                                                                   id="staffNumber"
                                                                   data-action="{{route('find.user')}}"
                                                                   placeholder="Staff number"
                                                                   name="staffNumber"
                                                                   required
                                                            />
                                                            <div class="input-group-addon">
                                                                <button type="button"
                                                                        id="employeeSearchBtn"
                                                                        name="userSearchBtn"
                                                                        class="btn btn-primary
                                                                        btn-sm border-radius-0">
                                                                    <i class="fas fa-search">
                                                                    </i>
                                                                </button>
                                                                <div class="d-none" id="divSubmitHide">
                                                                    <input class="btn btn-sm btn-success"
                                                                           value="Searching. Please wait..."
                                                                           disabled
                                                                           name="submit_form">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="container-fluid pl-0">
                                            <div class="row">
                                                <div class="form-group row">
                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-10">
                                                        <input type="text"
                                                               class="form-control
                                                               form-control-sm"
                                                               id="employeeName"
                                                               name="employeeName"
                                                               required
                                                               readonly/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-5">
                                    <div class="col-xs-12 col-sm-6 col-md-5">
                                        <div class="container-fluid pl-0">
                                            <div class="row">
                                                <div class="form-group row">
                                                    <label
                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                        for="staff_number">Start-Date:
                                                    </label>
                                                    <div
                                                        class="col-xs-12 col-sm-6 col-md-7 col-lg-8">
                                                        <div class="input-group">
                                                            <input type="datetime-local"
                                                                   class="form-control
                                                                   form-control-sm"
                                                                   id="startDate"
                                                                   name="startDate"
                                                                   required
                                                            />
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-calendar">
                                                                    </i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="container-fluid pl-0">
                                            <div class="row">
                                                <div class="form-group row">
                                                    <label
                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4"
                                                        for="staff_number">End Date:
                                                    </label>
                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8">
                                                        <input type="datetime-local"
                                                               class="form-control
                                                               form-control-sm"
                                                               id="endDate"
                                                               name="endDate"
                                                               required
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-paper-plane"></i>
                                        Submit
                                    </button>
                                </div>
                            </form>
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

            $('#employeeSearchBtn').on('click', function () {

                if (!document.querySelector("#staffNumber").value
                    || document.querySelector("#staffNumber").value.length < 5) {
                    toastr.warning('Invalid Employee Number')
                    return;
                }

                $("#employeeSearchBtn").hide();
                setTimeout(function () {
                    findEmployee();
                }, 300);
            });

            function findEmployee() {
                const staff_number = document.querySelector('#staffNumber').value
                let formData = new FormData();
                formData.append('searchCriteria', staff_number);
                $('#employeeName').val('');
                fetch(
                    document.querySelector("#staffNumber").getAttribute('data-action'),
                    {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: formData,
                        referrer: window['baseUrl'],
                        mode: 'cors',
                        credentials: 'same-origin',
                    }
                )
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }

                        return response.json();
                    })
                    .then(response => {
                        if (!response.success || response.payload.length === 0) {
                            tmsApp.systemError('User Search', response['message']);
                            return;
                        }

                        if (response.payload.hasOwnProperty('con_st_code')
                            && ['01', 'ACT'].indexOf(response.payload['con_st_code']) === -1) {
                            tmsApp.systemError('User Search',
                                appMessages.inactiveEmployee.replace('@staff', staff_number)
                            );
                            return;
                        }

                        if (response.payload.hasOwnProperty('status')
                            && ['01', 'ACT'].indexOf(response.payload['status']) === -1) {
                            tmsApp.systemError('User Search',
                                appMessages.inactiveEmployee.replace('@staff', staff_number)
                            );
                            return;
                        }

                        document.querySelector('#employeeName').value = response.payload.name;
                    })
                    .catch(function (xhr, settings, error) {
                        tmsApp.showErrorMessages(xhr, 'User Search');
                    });
            }

            tmsApp.initDatatable("#recordsTable", true, true);
        })(window.tmsApp || {}, jQuery);
    </script>
@endpush

