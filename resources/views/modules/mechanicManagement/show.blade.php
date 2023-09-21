@extends('layouts.app')
@php @endphp
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

      /*  input:checked + label {
            color: #555;
            border: 1px solid #ddd;
            border-top: 2px solid orange;
            border-bottom: 1px solid #fff;
        }*/

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
    <x-content-header :pageTitle="'MECHANIC DETAILS'"
                      :activeCrumb="'Mechanic Details'"
                      :link="'mechanic.list'"
                      :linkText="'Mechanic'"/>
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
                                             @if(auth::user()->id==$mechanic->id)
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

                            <p class="text-muted text-center">{{ $mechanic->job_title ?? 'Position' }}</p>

                            <p class="text-muted text-center">{{ $mechanic->staff_no ?? '' }}</p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Man Number</b> <a class="float-right">{{ $mechanic->staff_no }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>NRC</b> <a class="float-right">{{ $mechanic->nrc ?? '' }}</a>
                                </li>

                                <li class="list-group-item">
                                    <b>Phone</b> <a class="float-right">{{ $mechanic->mobile_no ?? '' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>
                                        Station
                                    </b>
                                    <a class="float-right">
                                        {{$mechanic->station ?? ''}}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b>
                                    <a class="float-right">{{ $mechanic->staff_email ?? '' }}</a>
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

                                @can(config('rights.edit_mechanic'))
                                    <li class="card-title">
                                        <a class="nav-link"
                                           href="#userInfoUpdate" data-toggle="tab">
                                            Data Update
                                        </a>
                                    </li>
                                @endcan
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
                                                    <b>Directorate:</b> {{ $mechanic->directorate ?? '--' }}
                                                </p>
                                                <p class="text-muted">
                                                    <b>Location:</b>
                                                    {{ $mechanic->functional_section ?? '' }}
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <p class="text-muted">
                                                    <b>
                                                        User Unit:
                                                    </b>
                                                    {{$mechanic->location}}
                                                </p>
                                                <p class="text-muted">
                                                    <b class="text-dark">
                                                        Business Unit Code:
                                                    </b>
                                                    {{ $mechanic->bu_code ?? '' }}
                                                </p>
                                                <p class="text-muted"><b>Cost Center:</b>
                                                    {{ $mechanic->cc_code ?? '' }}
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
                                                    {{ $mechanic->contract_type ?? '' }}
                                                </p>
                                                <p class="text-muted">
                                                    <strong>Grade:</strong>
                                                    {{ $mechanic->grade ?? '' }}
                                                </p>
                                                <p class="text-muted">
                                                    <strong>Category:</strong>
                                                    {{ $user->group_type ?? '' }}
                                                </p>
                                                <p class="text-muted">
                                                    <strong>User Position:</strong>
                                                    {{ $mechanic->job_title ?? '' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="post d-none">
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
                                            </div>
                                        </div>

                                        <div class="post">
                                            <div class="user-block">
                                                <span class="username ml-1"><a href="#">WORKSHOP</a> </span>
                                            </div>
                                            <div class="row">

                                                <div class="col-lg-6 col-sm-12">
                                                    <p class="text-muted">
                                                        <strong>Name:</strong>
                                                        {{ $mechanic->workshop_name ?? '' }}
                                                    </p>
                                                    <p class="text-muted">
                                                        <strong>Section .:</strong>
                                                        {{ $mechanic->wkshp_section_name ?? '' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="userInfoUpdate">
                                    @php $allowUpdate = false;  @endphp
                                    @if(auth()->user()->can(config('rights.edit_mechanic')))
                                        @php $allowUpdate = true;  @endphp
                                    @endif
                                    <form class="form-horizontal" name="updateDataUpdate" method="post"
                                          action="{{ route('mechanic.update') }}">
                                        @csrf
                                        <div class="form-group row">
                                            <label for="inputName"
                                                   class="col-sm-2 col-form-label field-required">Name:</label>
                                            <div class="col-sm-10">
                                                <input type="text"
                                                       class="form-control"
                                                       name="name"
                                                       @if(!$allowUpdate)
                                                           readonly
                                                       @endif
                                                       required
                                                       placeholder="Name"
                                                       value="{{ $mechanic->name ?? ''}}">
                                                <input type="hidden"
                                                       id="mechanicId"
                                                       name="mechanicId"
                                                       required
                                                       value="{{$mechanic->id ?? '--'}}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="email"
                                                   class="col-sm-2 col-form-label field-required">
                                                Email:
                                            </label>
                                            <div class="col-sm-10">
                                                <input type="email"
                                                       class="form-control"
                                                       name="email"
                                                       @if(!$allowUpdate)
                                                           readonly
                                                       @endif
                                                       required
                                                       placeholder="Email"
                                                       value="{{ $mechanic->staff_email ?? '' }}"/>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName2"
                                                   class="col-sm-2 col-form-label">Extension:</label>
                                            <div class="col-sm-10">
                                                <input type="text"
                                                       class="form-control"
                                                       name="phone"
                                                       @if(!$allowUpdate)
                                                           readonly
                                                       @endif
                                                       placeholder="extension"
                                                       value="{{ $mechanic->extension ?? '' }}"/>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="job_code"
                                                   class="col-sm-2 col-form-label">
                                                Job Code
                                            </label>
                                            <div class="col-sm-10">
                                                <input type="text"
                                                       class="form-control"
                                                       name="job_code"
                                                       placeholder="job_code" value="{{ $mechanic->job_code }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName2"
                                                   class="col-sm-2 col-form-label">Staff No:</label>
                                            <div class="col-sm-10">
                                                <input disabled type="text"
                                                       class="form-control"
                                                       name="staff_no"
                                                       required
                                                       @if(!$allowUpdate)
                                                           readonly
                                                       @endif
                                                       placeholder="Staff No"
                                                       value="{{ $mechanic->staff_no }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputExperience"
                                                   class="col-sm-2 col-form-label field-required">
                                                Workshop:
                                            </label>
                                            <div class="col-sm-10">
                                                <select @if(!$allowUpdate)
                                                            disabled
                                                        @endif
                                                        class="@if($allowUpdate)
                                                             form-select form-select-sm
                                                             @else form-control  @endif"
                                                        id="workshop_code"
                                                        name="workshop_code">
                                                    @foreach($workshopList as $workshop)
                                                        @if($workshop->workshop_code == $mechanic->workshop_code)
                                                            <option value="{{$workshop->workshop_code}}">
                                                                {{$workshop->workshop_name}}
                                                            </option>
                                                        @else
                                                            <option value="{{$workshop->workshop_code}}">
                                                                {{$workshop->workshop_name}}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="workShopSection"
                                                   class="col-sm-2 col-form-label field-required">
                                                Section:
                                            </label>
                                            <div class="col-sm-10">
                                                <select @if(!$allowUpdate)
                                                            disabled
                                                        @endif
                                                        class="@if($allowUpdate)
                                                             form-select form-select-sm
                                                             @else form-control  @endif"
                                                        id="work_shop_section"
                                                        name="workShopSection">
                                                    @foreach($workshopSectionList
                                                               as $workshop_section)
                                                        @if($workshop->code == $mechanic->section_code)
                                                            <option value="{{$workshop_section->code}}">
                                                                {{$workshop_section->name}}
                                                            </option>
                                                        @else
                                                            <option value="{{$workshop_section->code}}">
                                                                {{$workshop_section->name}}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="workShopSection"
                                                   class="col-sm-2 col-form-label field-required">
                                                WorkShop Supervisor:
                                            </label>
                                            <div class="col-sm-10">
                                                <label class="checkbox-inline pl-1">
                                                    <input type="radio"
                                                           id="policeNotification-yes"
                                                           name="workshopSupervisor"
                                                           value="Y">
                                                    <label for="policeNotification-yes">Yes</label>
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="radio" id="policeNotification-no"
                                                           checked
                                                           name="workshopSupervisor"
                                                           value="N">
                                                    <label for="policeNotification-no">No</label>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group justify-content-end">
                                            @canany([config('rights.edit_mechanic')])
                                                <div class="col-md-12">
                                                    <div class="d-flex justify-content-end">
                                                        <button type="submit"
                                                                id="updateUserData"
                                                                class="btn btn-sm btn-success mr-3">
                                                            Save
                                                        </button>

                                                        <button type="button"
                                                                id="syncUserData"
                                                                data-href="{{ route('mechanic.sync') }}"
                                                                class="btn btn-sm btn-default">
                                                            Sync <i class="fas fa-sync"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endcanany
                                        </div>
                                    </form>
                                    <x-employee-search-modal/>

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
            appInstance.initDatatable("#groupsTable", false, false, []);
        })(window.tmsApp || {});
    </script>
@endpush
