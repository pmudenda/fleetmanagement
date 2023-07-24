@extends('layouts.app')

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <style>
        input[name="tabs"] {
            display: none;
        }

        label {
            display: inline-block;
            margin: 0 0 -1px;
            padding: 15px 25px;
            font-weight: 600;
            text-align: center;
            color: #bbb;
            border: 1px solid transparent;
        }

        label:before {
            font-family: fontawesome;
            font-weight: normal;
            margin-right: 10px;
        }

        label[for*='1']:before {
            content: '\f1cb';
        }

        label[for*='2']:before {
            content: '\f17d';
        }

        label[for*='3']:before {
            content: '\f16b';
        }

        label[for*='4']:before {
            content: '\f1a9';
        }

        label:hover {
            color: #888;
            cursor: pointer;
        }

        input:checked + label {
            color: #555;
            border: 1px solid #ddd;
            border-top: 2px solid orange;
            border-bottom: 1px solid #fff;
        }

        #tab4:checked ~ #content4 {
            display: block;
        }

        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            color: var(--bs-nav-pills-link-active-color);
            background-color: orange;
        }
    </style>
@endpush

@section('content')
    <x-content-header :pageTitle="'USER DETAILS'" :activeCrumb="'User Details'" :link="'user.index'"
                      :linkText="'Users'"/>

    <!-- Main content -->
    <section class="content">
        <x-error-view/>
        <div class="container-fluid">
            <div class="row">
                <!--LEFT COLUMN-->
                <div class="col-xs-12 col-sm-4 pl-0">
                    <div class="card card-outline">
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
                                             @if( Auth::user()->id==$user->id)
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
                                {{-- @endif --}}
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
                <!--RIGHT COLUMN-->
                <div class="col-xs-12 col-sm-7 pl-0">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-pills">
                                <li class="card-title">
                                    <a class="nav-link active" href="#activity"
                                       data-toggle="tab">
                                        Details
                                    </a>
                                </li>

                                @canany([config('rights.user_update')])
                                    <li class="card-title">
                                        <a class="nav-link"
                                           href="#userInfoUpdate"
                                           data-toggle="tab">
                                            Update Details
                                        </a>
                                    </li>
                                @endcanany

                                {{--<li class="card-title">
                                    <a class="nav-link " href="#units" data-toggle="tab">
                                        My User-Units
                                    </a>
                                </li>
                                <li class="card-title">
                                    <a class="nav-link " href="#workflow" data-toggle="tab">
                                        My Work-flow
                                    </a>
                                </li>--}}

                                <li class="card-title">
                                    <a class="nav-link" href="#pass_reset" data-toggle="tab">
                                        Password Reset
                                    </a>
                                </li>

                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">

                                <div class="active tab-pane" id="activity">
                                    @include('modules.userManagement.userProfileTabs.userDetailsSummary')
                                </div>

                                @canany([config('rights.user_update')])
                                    <div class="tab-pane" id="userInfoUpdate">
                                        @include('modules.userManagement.userProfileTabs.updateDetails')
                                    </div>
                                @endcanany

                                <div class="tab-pane" id="pass_reset">
                                    @include('modules.userManagement.userProfileTabs.passwordReset')
                                </div>

                                {{--
                                <div class="tab-pane" id="units">
                                    @include('UserManagement/userProfileTabs/units')
                                </div>
                                <div class="tab-pane" id="workflow">
                                    @include('UserManagement/userProfileTabs/workflow')
                                </div>
                                --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    @foreach($user->roles as $item)
        <!-- Device Delete Modal -->
        <div class="modal fade" id="removeFromGroup{{$item->id}}" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detach Roles: {{$item->name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form name="db2" method="post" action="{{route('user.detach')}}">
                            <input type="hidden" name="id" id="id" value="{{$user->id}}">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <span class="text-danger">Are you sure you want to detach?</span>

                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12  form-group mt-4">
                                        <label for="name"> ROLE NAME: <span class="required">*</span></label>
                                        <input type="text" class="form-control" value="{{$item->name}}" id="name"
                                               name="name" required readonly>
                                        <input type="text" hidden class="form-control" value="{{$item->id}}"
                                               id="role_id" name="role_id" required
                                        >
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                @can(config('rights.role_detach'))
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
    <div class="modal fade" id="addUserToGroup" tabindex="-1" role="dialog"
         aria-labelledby="addUserToGroupTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div
                    class="modal-header ui-dialog-titlebar ui-corner-all ui-widget-header ui-helper-clearfix ui-draggable-handle">
                    <h5 class="modal-title" id="addUserToGroupTitle">
                        Add Profile To User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form name="db2" method="post" action="{{route('user.attach')}}">
                        @csrf
                        <input type="hidden" name="id" id="id" value="{{$user->id}}">
                        <div class="card-body">
                            <div class="row">
                                <span class="text-danger">Select Profile:</span>
                            </div>
                            <table id="example11" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($roles->whereNotIn( 'id', $user->roles->pluck('id')->toArray()) as $item)
                                    <tr>
                                        <td>
                                            <input style="display: block" type="checkbox"
                                                   name="role_ids[]"
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
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close
                            </button>

                            @can(config('rights.role_attach'))
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fas fa-save"></i> Save
                                </button>
                            @endcan
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>

@endsection

@push('scripts')
    <!-- DataTables  & Plugins -->
    @include('layouts.partials.dataTableScripts')
    <!-- page script -->
    <script>
        (function (appInstance) {
            appInstance.initDatatable("#groupsTable", false);

            $(document).on('submit', 'form[name="updateDataUpdate"]', function (e) {
                e.preventDefault();
                e.stopPropagation();
                const form = document.querySelector('form[name="updateDataUpdate"]');
                const url = form.action;
                let formData = new FormData(form);
                tmsApp.asyncPostFormData(
                    url,
                    formData,
                    function (asyncResponse) {

                        if (asyncResponse.hasOwnProperty('success') && asyncResponse['success']) {
                            setTimeout(function () {
                                tmsApp.showSystemMessage(
                                    'Fuel Requisition',
                                    asyncResponse['message'],
                                    function () {
                                        window.location.href = asyncResponse["redirectUrl"]
                                        //window.location.reload();
                                    },
                                    'success'
                                );
                            }, 300);
                        } else {
                            if (asyncResponse.hasOwnProperty('errors')) {
                                tmsApp.printErrorMsg(asyncResponse.errors);
                                return
                            }
                            setTimeout(function () {
                                tmsApp.systemError(
                                    'Fuel Requisition',
                                    asyncResponse['message'],
                                    function () {
                                    }, 'error');
                            }, 300);
                        }
                    },
                    function (xhr, settings, errorThrown) {
                        console.log(errorThrown)
                        setTimeout(function () {
                            if ('responseJSON' in xhr) {
                                if (xhr.responseJSON.hasOwnProperty('errors')) {
                                    tmsApp.printErrorMsg(xhr.responseJSON.errors);
                                }
                                if (xhr.responseJSON.hasOwnProperty('message')) {
                                    tmsApp.systemError(
                                        'Fuel Requisition',
                                        xhr.responseJSON['message']
                                    );
                                }
                                return;
                            }

                            tmsApp.systemError(
                                'Fuel Requisition',
                                'We could not complete processing your request, please try again later');
                        }, 300)
                    });
            });

            $(document).on('click', "#syncUserData", function () {
                const url = this.getAttribute('data-href');
                let formData = new FormData();

                tmsApp.asyncPostFormData(
                    url,
                    formData,
                    function (asyncResponse) {
                        if (asyncResponse.hasOwnProperty('success') && asyncResponse['success']) {
                            setTimeout(function () {
                                tmsApp.showSystemMessage(
                                    'Fuel Requisition',
                                    asyncResponse['message'],
                                    function () {
                                        window.location.href = asyncResponse["redirectUrl"]
                                    },
                                    'success'
                                );
                            }, 300);
                        } else {
                            if (asyncResponse.hasOwnProperty('errors')) {
                                tmsApp.printErrorMsg(asyncResponse.errors);
                                return
                            }
                            setTimeout(function () {
                                tmsApp.systemError(
                                    'Fuel Requisition',
                                    asyncResponse['message'],
                                    function () {
                                    }, 'error');
                            }, 300);
                        }
                    },
                    function (xhr, settings, errorThrown) {
                        console.log(errorThrown)
                        setTimeout(function () {
                            if ('responseJSON' in xhr) {
                                if (xhr.responseJSON.hasOwnProperty('errors')) {
                                    tmsApp.printErrorMsg(xhr.responseJSON.errors);
                                }
                                if (xhr.responseJSON.hasOwnProperty('message')) {
                                    tmsApp.systemError(
                                        'Fuel Requisition',
                                        xhr.responseJSON['message']
                                    );
                                }
                                return;
                            }

                            tmsApp.systemError(
                                'Fuel Requisition',
                                'We could not complete processing your request, please try again later');
                        }, 300)
                    });
            });
        })(window.tmsApp || {});
    </script>
@endpush
