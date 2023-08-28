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
    <x-content-header :pageTitle="'DRIVER DETAILS'" :activeCrumb="'Driver Details'" :link="'driver.list'"
                      :linkText="'Drivers'"/>

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
                                    @if(!empty($mechanic->avatar))
                                        <img class="profile-user-img img-fluid img-circle" width="100%"
                                             src="{{ asset('storage/user_avatar/' . $mechanic->avatar) }}"
                                             alt="Image not found"
                                             @if(Auth::user()->id==$mechanic->id)
                                                 title="Click Here to Edit Image"
                                             data-toggle="modal"
                                             data-target="#modal-edit-profile"@endif
                                        />
                                    @else
                                        <img class="profile-user-img img-fluid img-circle" width="100%"
                                             src="{{ asset('assets/media/avatars/avatar.png') }}"
                                             alt="Image not found"
                                             @if(Auth::user()->id==$mechanic->id)
                                                 title="Click Here to Edit Image"
                                             data-toggle="modal"
                                             data-target="#modal-edit-profile"@endif
                                        />
                                    @endif
                                </a>
                            </div>

                            <h3 class="profile-username text-center">{{$mechanic->name}}</h3>

                            {{--<p class="text-muted text-center">{{ $mechanic->job_title ?? 'Position' }}</p>--}}

                            <p class="text-muted text-center">{{ $mechanic->staff_no ?? '' }}</p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Man Number</b> <a class="float-right">{{ $mechanic->staff_no }}</a>
                                </li>
                                {{--<li class="list-group-item">
                                    <b>NRC</b> <a class="float-right">{{ $mechanic->nrc }}</a>
                                </li>--}}

                                {{--<li class="list-group-item">
                                    <b>Phone</b> <a class="float-right">{{ $mechanic->mobile_no }}</a>
                                </li>--}}
                                {{--<li class="list-group-item">
                                    <b>Extension</b> <a class="float-right">{{ $mechanic->phone ?? '' }}</a>
                                </li>--}}
                                <li class="list-group-item">
                                    <b>
                                        Assigned Profile
                                    </b>
                                    <a class="float-right">
                                        <span class="badge badge-success">

                                        </span>
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b>
                                    <a class="float-right">{{ $mechanic->email ?? '' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Status</b> <a class="float-right">
                                        @if($mechanic->status == '01')
                                            <span class="badge badge-success p-2">
                                                Active
                                            </span>
                                        @else
                                            {{$mechanic->status ?? '--'}}
                                        @endif
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
                                    <a class="nav-link active"
                                       href="#activity"
                                       data-toggle="tab">
                                        Details
                                    </a>
                                </li>

                                <li class="card-title">
                                    <a class="nav-link"
                                       href="#userInfoUpdate" data-toggle="tab">
                                        Settings
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">

                                <div class="active tab-pane" id="activity">
                                    <!-- Post -->
                                    <div class="post">
                                        <div class="user-block">
                                            <div class="username ml-1">
                                                <a href="javascript:void(0);">COMPANY</a>
                                            </div>
                                        </div>
                                        <!-- /. user-block -->
                                        <div class="row">
                                            <div class="col-6">
                                                <p class="text-muted">
                                                    <b>Directorate:</b> {{ $user->directorate ?? '--' }}
                                                </p>
                                                {{--
                                                    <p class="text-muted">
                                                        <b>PayPoint:</b> {{ $user->pay_point->name ?? '' }}
                                                    </p>
                                                 --}}
                                                <p class="text-muted">
                                                    <b>Location:</b> {{ $user->functional_section ?? '' }}</p>
                                                <p class="text-muted"><b>Area:</b>
                                                    {{--@foreach(Area::get() as $area)
                                                        @if($area->area == $user->area_code)
                                                            <b value="{{$area->area}}">{{$area->description}}</b>
                                                        @endif
                                                    @endforeach--}}
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <p class="text-muted">
                                                    <b>
                                                        User Unit:
                                                    </b>
                                                    @if (Auth::user()->type_id == config('constants.user_types.developer') ||
                                                        Auth::user()->type_id == config('constants.user_types.mgt')
                                                        )
                                                        <a href="{{ route('logout') }}" class="text-dark"
                                                           onclick="event.preventDefault(); document.getElementById('search-form12').submit();">
                                                            {{ $user->user_unit ?? ''}}
                                                        </a>
                                                <form id="search-form12"
                                                      action="#"
                                                      method="post" class="d-none">
                                                    @csrf
                                                </form>
                                                @else
                                                    {{$user->user_unit ?? '' }}
                                                @endif
                                                </p>
                                                <p class="text-muted">
                                                    <b class="text-dark">
                                                        Business Unit Code:
                                                    </b>
                                                    {{ $user->bu_code ?? '' }}
                                                </p>
                                                <p class="text-muted"><b>Cost Center:</b> {{ $user->cc_code ?? '' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="post">
                                        <div class="user-block">
                                            <span class="username ml-1"><a href="#">POSITION AND PROFILES</a> </span>
                                        </div>
                                        <div class="row">

                                            <div class="col-lg-6 col-sm-12">
                                                <p class="text-muted">
                                                    <strong>Contract Type:</strong>
                                                    {{ $user->contract_type ?? '' }}
                                                </p>
                                                <p class="text-muted">
                                                    <strong>Grade:</strong>
                                                    {{ $user->grade ?? '' }}
                                                </p>
                                                {{--<p class="text-muted">
                                                    <strong>Category:</strong>
                                                    {{ $user->grade->category->name ?? '' }}
                                                </p>--}}
                                                <p class="text-muted">
                                                    <strong>User Position:</strong>
                                                    {{ $user->job_title ?? '' }}
                                                </p>
                                                {{-- <p class="text-muted ">
                                                     <strong class="text-orange ">
                                                         Job Code:
                                                     </strong>
                                                     {{ $user->job_code ?? '' }}
                                                 </p>--}}
                                            </div>

                                            @if(!empty($user_acting->acting_date_from))
                                                <div class="col-lg-6 col-sm-12">
                                                    <p class="text-muted">
                                                        <strong>Acting Period :</strong>
                                                        {{ Carbon\Carbon::parse($user_acting->acting_date_from ?? '0')->format('d-M-Y') ?? '' }}
                                                        To
                                                        {{ Carbon\Carbon::parse($user_acting->acting_date_to ?? '0')->format('d-M-Y') ?? ('' ?? '') }}
                                                    </p>
                                                    <p class="text-muted">
                                                        <b>Acting Grade:</b>
                                                        {{ $user_acting->grade->name ?? '' }}
                                                    </p>
                                                    <p class="text-muted">
                                                        <b>Acting Category:</b>
                                                        {{ $user_acting->grade->category->name ?? '' }}
                                                    </p>
                                                    <p class="text-muted">
                                                        <b>
                                                            Acting Position:
                                                        </b>
                                                        {{ $user_acting->acting_position ?? '' }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="post">
                                        <div class="user-block">
                                            <span class="username ml-1"><a href="#">LINE SUPERVISOR</a> </span>
                                        </div>
                                        <div class="row">

                                            <div class="col-lg-6 col-sm-12">
                                                <p class="text-muted">
                                                    <strong>Name:</strong>
                                                    {{--{{ $user->supervisor_name ?? '' }}--}}
                                                </p>
                                                <p class="text-muted">
                                                    <strong>Staff No.:</strong>
                                                    {{--{{ $user->supervisor_code ?? '' }}--}}
                                                </p>
                                            </div>

                                            @if(!empty($user_acting->acting_date_from))
                                                <div class="col-lg-6 col-sm-12">
                                                    <p class="text-muted">
                                                        <strong>Acting Period :</strong>
                                                        {{ Carbon\Carbon::parse($user_acting->acting_date_from ?? '0')->format('d-M-Y') ?? '' }}
                                                        To
                                                        {{ Carbon\Carbon::parse($user_acting->acting_date_to ?? '0')->format('d-M-Y') ?? ('' ?? '') }}
                                                    </p>
                                                    <p class="text-muted">
                                                        <b>Acting Grade:</b>
                                                        {{ $user_acting->grade->name ?? '' }}
                                                    </p>
                                                    <p class="text-muted">
                                                        <b>Acting Category:</b>
                                                        {{ $user_acting->grade->category->name ?? '' }}
                                                    </p>
                                                    <p class="text-muted">
                                                        <b>
                                                            Acting
                                                            Position:
                                                        </b>
                                                        {{ $user_acting->acting_position ?? '' }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="userInfoUpdate">
                                    {{--@include('modules.userManagement.userProfileTabs.details')--}}
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
        (function (appInstance) {
            appInstance.initDatatable("#groupsTable", false);
        })(window.tmsApp || {});
    </script>
@endpush
