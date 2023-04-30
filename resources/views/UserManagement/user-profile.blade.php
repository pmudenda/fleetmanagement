<div>
    <!-- Main content -->
    <section class="content">

        @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible">
            <p class="lead"> {{ session()->get('message') }}</p>
        </div>
        @endif
        @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible">
            <p class="lead"> {{ session()->get('error') }}</p>
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


        <div class="container-fluid">
            <div class="row">
                <!--LEFT COLUMN-->

                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <a href="#">
                                    @if(!empty($user->avatar))
                                    <img class="profile-user-img img-fluid img-circle" width="100%"
                                    src="{{ asset('storage/user_avatar/' . $user->avatar) }}" alt="Image not found"
                                    @if( Auth::user()->id==$user->id)
                                title="Click Here to Edit Image"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-edit-profile"
                                @endif
                                    />
                                    @else
                                    <img class="profile-user-img img-fluid img-circle" width="100%"
                                    src="{{ asset('assets/img/avatar.png') }}" alt="Image not found"
                                    @if( Auth::user()->id==$user->id)
                                title="Click Here to Edit Image"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-edit-profile"
                                @endif
                                    />
                                    @endif
                                </a>
                            </div>

                            <h3 class="profile-username text-center">{{ $user->name }}</h3>

                            <p class="text-muted text-center">{{ $user->job_title ?? 'Position' }}</p>

                            <p class="text-muted text-center">{{ $user->man_no ?? 'Staff Number' }}</p>

                            <ul class="list-group list-group-unbordered mb-3">
                                {{-- @if (Auth::user()->id == $user->id ||
                                Auth::user()->type_id == config('constants.user_types.developer') ||
                                Auth::user()->type_id == config('constants.user_types.mgt')) --}}
                                <li class="list-group-item">
                                    <b>Man Number</b> <a class="float-right">{{ $user->man_no }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>NRC</b> <a class="float-right">{{ $user->nrc }}</a>
                                </li>
                                {{-- @endif --}}
                                <li class="list-group-item">
                                    <b>Phone</b> <a class="float-right">{{ $user->mobile_no }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Extension</b> <a class="float-right">{{ $user->extension ?? '' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b> <a class="float-right">{{ $user->email }}</a>
                                </li>
                              {{--  <li class="list-group-item">
                                    <b>Status</b> <a class="float-right">{{ $user->status ?? '' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Total Application Forms</b> <a class="float-right">{{ $user->total_forms }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Total Logins</b> <a class="float-right">{{ $user->total_login }}</a>
                                </li>--}}
                            </ul>

                        </div>
                        <!-- /.card-body -->
                        @if (Auth::user()->type_id == config('constants.user_types.developer') ||
                        Auth::user()->type_id == config('constants.user_types.mgt'))
                        <div class="card-footer d-none">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- SEARCH FORM -->
                                    <div class="modal-header">
                                        <label>FIND USER</label>
                                    </div>
                                    <div class="modal-body">
                                        <form class="form-inline ml-3" method="post" action="">
                                            @csrf
                                            <div class="input-group input-group-sm">
                                                <input class="form-control form-control-navbar" type="search"
                                                    name="search" placeholder="Enter Man Number/Name"
                                                    aria-label="Enter Search Term">
                                                <div class="input-group-append">
                                                    <button class="btn btn-navbar" type="submit">
                                                        <i class="fas fa-search"> Search User</i>
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
                    <!-- /.card -->
                </div>
                <!-- /.col -->

                <!--RIGHT COLUMN-->

                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item d-none">
                                    <a class="nav-link " href="#activity"
                                       data-toggle="tab">
                                        Details
                                    </a>
                                </li>
                                @if (Auth::user()->id == $user->id ||
                                Auth::user()->type_id == config('constants.user_types.developer') ||
                                Auth::user()->type_id == config('constants.user_types.mgt'))
                                <li class="nav-item d-none">
                                    <a class="nav-link" href="#settings" data-toggle="tab">
                                        Settings
                                    </a>
                                </li>
                                @endif
                                <li class="nav-item d-none">
                                    <a class="nav-link " href="#units" data-toggle="tab">
                                        My User-Units
                                    </a>
                                </li>
                                <li class="nav-item d-none">
                                    <a class="nav-link " href="#workflow" data-toggle="tab">
                                    My Work-flow
                                </a>
                                @if (Auth::user()->type_id == config('constants.user_types.developer') ||
                                Auth::user()->type_id == config('constants.user_types.mgt'))
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#pass_reset" data-toggle="tab">
                                            Password Reset
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">

                                <div class="tab-pane" id="activity">
                                    <!-- Post -->
                                    <div class="post">
                                        <div class="user-block">
                                            <span class="username"> <a href="#">Company</a> </span>
                                        </div>
                                        <!-- /.user-block -->
                                        <div class="row">
                                            <div class="col-6">
                                                <p class="text-muted">
                                                    <b>Directorate:</b> {{ $user->directorate->name ?? '' }}
                                                </p>
                                                <p class="text-muted">
                                                    <b>PayPoint:</b> {{ $user->pay_point->name ?? '' }}
                                                </p>
                                                <p class="text-muted"><b>Location:</b> {{ $user->location->name ?? '' }}
                                                </p>
                                                <p class="text-muted"><b>Division:</b> {{ $user->division->name ?? '' }}
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <p class="text-muted"><b>User
                                                        Unit:</b>
                                                    {{-- @if (Auth::user()->type_id ==
                                                    config('constants.user_types.developer') ||
                                                    Auth::user()->type_id == config('constants.user_types.mgt'))
                                                    <a href="{{ route('logout') }}" class="text-dark" onclick="event.preventDefault();
                                                               document.getElementById('search-form12').submit();">
                                                        {{ $user->user_unit->user_unit_description ?? '' }} </a> --}}
                                                <form id="search-form12" action="" method="post" class="d-none">
                                                    @csrf
                                                </form>
                                                {{-- @else
                                                {{ $user->user_unit->user_unit_description ?? '' }}
                                                @endif --}}
                                                </p>
                                                <p class="text-muted "><b class=" text-orange">User Unit
                                                        Code:</b> {{ $user->user_unit->user_unit_code ?? '' }} </p>
                                                <p class="text-muted"><b>Business
                                                        Unit:</b> {{ $user->user_unit->user_unit_bc_code ?? '' }}
                                                </p>
                                                <p class="text-muted"><b>Cost
                                                        Center:</b> {{ $user->user_unit->user_unit_cc_code ?? '' }} </p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.post -->
                                    <!-- Post -->
                                    <div class="post">
                                        <div class="user-block">
                                            <span class="username"> <a href="#">Position and Profiles</a> </span>
                                        </div>
                                        <div class="row">
                                            {{-- @if (Auth::user()->id == $user->id ||
                                            Auth::user()->type_id == config('constants.user_types.developer') ||
                                            Auth::user()->type_id == config('constants.user_types.mgt')) --}}
                                            <div class="col-6">
                                                <p class="text-muted"><b>Contract
                                                        Type:</b> {{ $user->contract_type ?? '' }} </p>
                                                <p class="text-muted"><b>Grade:</b> {{ $user->grade->name ?? '' }}
                                                </p>
                                                <p class="text-muted">
                                                    <b>Category:</b> {{ $user->grade->category->name ?? '' }}
                                                </p>
                                                <p class="text-muted"><b>User
                                                        Position:</b> {{ $user->position->name ?? '' }} </p>
                                                <p class="text-muted "><b class="text-orange ">Job
                                                        Code:</b> {{ $user->job_code ?? '' }} </p>
                                            </div>
                                            {{-- @endif --}}
                                            {{-- @if (Auth::user()->id == $user->id ||
                                            Auth::user()->type_id == config('constants.user_types.developer') ||
                                            Auth::user()->type_id == config('constants.user_types.mgt')) --}}
                                            <div class="col-6">
                                                <p class="text-muted"><b>Acting Period
                                                        :</b>
                                                    {{ Carbon\Carbon::parse($user_acting->acting_date_from ??
                                                    '0')->format('d-M-Y') ?? '' }}
                                                    To
                                                    {{ Carbon\Carbon::parse($user_acting->acting_date_to ??
                                                    '0')->format('d-M-Y') ?? ('' ?? '') }}
                                                </p>
                                                <p class="text-muted"><b>Acting Grade:</b>
                                                    {{ $user_acting->grade->name ?? '' }}
                                                </p>
                                                <p class="text-muted">
                                                    <b>Acting Category:</b>
                                                    {{ $user_acting->grade->category->name ?? '' }}
                                                </p>
                                                <p class="text-muted"><b>
                                                        Acting
                                                        Position:</b> {{ $user_acting->acting_position ?? '' }}
                                                </p>
                                            </div>
                                            {{-- @endif --}}
                                        </div>

                                    </div>
                                    <!-- /.post -->
                                    <!-- Post -->
                                    <div class="post">
                                        <div class="user-block">
                                            <span class="username"> <a href="#">PROFILES</a> </span>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <form role="form-remove" method="post" action="">
                                                    @csrf
                                                    <div class="form-group">
                                                        <input hidden value="{{ $user->id }}"
                                                            class="form-control select2" id="owner_id" name="owner_id"
                                                            required style="width: 100%;">
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label class="text-green">ACTIVE PROFILES</label>
                                                            <table class="table m-0">
                                                                {{-- @endif --}}
                                                                <thead>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th>Code</th>
                                                                        <th>Name</th>
                                                                        <th>Description</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="profiles">

                                                                    {{-- @foreach ($user->user_profile as $item)
                                                                    <tr>
                                                                        <td>
                                                                            <div class="icheck-warning d-inline">
                                                                                <input type="checkbox"
                                                                                    value='{"profiles": "{{ $item->profile }}" ,"form":{{ $item->form->id }}}'
                                                                                    id="remove_profiles[]"
                                                                                    name="remove_profiles[]">
                                                                            </div>
                                                                        </td>
                                                                        <td> {{ $item->profiles->code }} </td>
                                                                        <td> {{ $item->profiles->name }} </td>
                                                                        <td> {{ $item->form->name }} </td>
                                                                        <td><a
                                                                                href="{{ route('main.user.profile.sync', ['user' => $user ?? 0, 'profile' => $item->profiles ?? 0]) }}">Sync</a>
                                                                        </td>
                                                                    <tr>
                                                                        @endforeach --}}
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        {{-- @if (Auth::user()->type_id ==
                                                        config('constants.user_types.developer'))
                                                        <button type="submit" class="btn btn-sm btn-warning">
                                                            Remove
                                                        </button>
                                                        @endif --}}
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="col-6">
                                                <form role="form-remove-delegate" method="post" action="">
                                                    @csrf
                                                    <div class="form-group">
                                                        <input hidden value="{{ $user->id }} : {{ $user->name }}"
                                                            class="form-control select2" id="owner_id" name="owner_id"
                                                            required style="width: 100%;">
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label class="text-green">DELEGATED PROFILES</label>
                                                            <table class="table m-0">
                                                                {{-- @endif --}}
                                                                <thead>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th>Code</th>
                                                                        <th>Name</th>
                                                                        <th>Status</th>
                                                                        <th>Owner</th>
                                                                        <th>Delegating</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="profiles">
                                                                    {{-- @foreach ($delegated_profiles as $item_p)
                                                                    <tr>
                                                                        <td>
                                                                            <div class="icheck-warning d-inline">
                                                                                <input type="checkbox"
                                                                                    value='{{ $item_p->id }}'
                                                                                    id="delegated_profiles[]"
                                                                                    name="delegated_profiles[]">
                                                                            </div>
                                                                        </td>
                                                                        <td> {{ $item_p->form->code ?? '' }} </td>
                                                                        <td> {{ $item_p->profile->name ?? '' }} </td>
                                                                        <td> {{ $item_p->status->name ?? '' }} </td>
                                                                        <td> {{ $item_p->me->name ?? '' }} </td>
                                                                        <td> {{ $item_p->delegation->name ?? '' }}</td>
                                                                    <tr>
                                                                        @endforeach --}}
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        {{-- @if (Auth::user()->type_id ==
                                                        config('constants.user_types.developer'))
                                                        <button type="submit" class="btn btn-sm btn-warning">
                                                            Remove
                                                        </button>
                                                        @endif --}}
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="col-lg-12 col-sm-12">
                                                <hr>
                                                <label>Delegate Profiles</label>
                                                <div>
                                                    <a class="btn btn-sm bg-gradient-gray float-left "
                                                        style="margin: 1px" title="Edit" data-toggle="modal"
                                                        data-target="#modal-profile-delegate">
                                                        Delegate
                                                    </a>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <!-- /.post -->
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="settings">
                                    <form class="form-horizontal" method="post" action="">
                                        @csrf
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="name" required
                                                    placeholder="Name" value="{{ $user->name }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" name="email" required
                                                    placeholder="Email" value="{{ $user->email }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Phone</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="phone" required
                                                    placeholder="Phone" value="{{ $user->phone }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputExperience" class="col-sm-2 col-form-label">User
                                                Type</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="user_type_id" required>
                                                    <option value="{{ $user->user_type->id ?? '' }} ">
                                                        {{ $user->user_type->name ?? 'Please Select User Type' }}
                                                    </option>

                                                    {{-- @if (\Illuminate\Support\Facades\Auth::user()->id != $user->id)
                                                    @if (Auth::user()->type_id ==
                                                    config('constants.user_types.developer'))
                                                    @foreach ($user_types as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}
                                                    </option>
                                                    @endforeach
                                                    @endif
                                                    @endif --}}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label text-orange ">
                                                User Unit
                                            </label>
                                            <div class="col-sm-10">
                                                <select id="user_unit_new" class="form-control user_unit_new"
                                                    name="user_unit_new">
                                                    <option value="{{ $user->user_unit->id ?? '' }} ">
                                                        {{ $user->user_unit->user_unit_description ?? '' }}
                                                        :
                                                        {{ $user->user_unit->user_unit_code ?? 'Please Select User Unit'
                                                        }}
                                                    </option>
                                                    {{--Auth::user()->type_id ==
                                                    config('constants.user_types.developer')--}}
                                                    {{-- @if (Auth::user()->id == $user->id ||
                                                    \App\Helpers\Authorise::hasDeveloperUserType(Auth::user()))
                                                    @foreach ($user_unit_new as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->user_unit_description }}
                                                        : {{ $item->user_unit_code }}
                                                    </option>
                                                    @endforeach
                                                    @endif --}}
                                                </select>

                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Man No</label>
                                            <div class="col-sm-10">
                                                <input disabled type="text" class="form-control" name="staff_no"
                                                    required placeholder="Staff No" value="{{ $user->staff_no }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputExperience" class="col-sm-2 col-form-label">User
                                                Division</label>
                                            <div class="col-sm-10">
                                                <select disabled class="form-control" id="division_select"
                                                    name="user_division_id">
                                                    <option value="{{ $user->division->id ?? '' }}  ">
                                                        {{ $user->division->name ?? '' }} </option>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            {{-- @if (Auth::user()->id == $user->id || Auth::user()->type_id ==
                                            config('constants.user_types.developer'))
                                            <div class="offset-sm-2 col-sm-4">
                                                <button type="submit" class="btn btn-danger">Update</button>
                                            </div>
                                            <div class="offset-sm-5 col-sm-1" style="align-content: end">
                                                <a href="{{ route('main.user.sync', $user->id) }}"
                                                    class="btn btn-default"> Sync <i class="fas fa-sync"></i> </a>
                                            </div>
                                            @endif --}}
                                        </div>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="units">
                                    <!-- Post -->
                                    <div class="post">
                                        {{-- <div class="user-block"> --}}
                                            {{-- <span class="username"> <a href="#">My Units</a> </span> --}}
                                            {{-- </div> --}}
                                        <div class="row">
                                            <div class="col-6">
                                                <form role="form-units" method="post" action="">
                                                    @csrf
                                                    <div class="form-group">
                                                        <input hidden value="{{ $user->id }}"
                                                            class="form-control select2" id="owner_id" name="owner_id"
                                                            required style="width: 100%;">
                                                    </div>

                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label class="text-green">ACTIVE UNITS</label>
                                                            <table class="table m-0">
                                                                {{-- @endif --}}
                                                                <thead>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th>Name</th>
                                                                        <th>Code</th>
                                                                        <th>BU</th>
                                                                        <th>CC</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="units">

                                                                    {{-- @foreach ($responsible_units as $item)
                                                                    <tr>
                                                                        <td>
                                                                            <div class="icheck-warning d-inline">
                                                                                <input type="checkbox"
                                                                                    value='{"code": "{{ $item->user_unit_code }}" ,"id":{{ $item->id }}}'
                                                                                    id="transfer_units[]"
                                                                                    name="transfer_units[]">
                                                                            </div>
                                                                        </td>
                                                                        <td> {{ $item->user_unit_description }} </td>
                                                                        <td> {{ $item->user_unit_code }} </td>
                                                                        <td> {{ $item->user_unit_bc_code }} </td>
                                                                        <td> {{ $item->user_unit_cc_code }} </td>
                                                                    <tr>
                                                                        @endforeach --}}
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-6">
                                                <form role="form-assign_units" method="post" action="">
                                                    @csrf
                                                    <div class="form-group">
                                                        <input hidden value="{{ $user->id }}"
                                                            class="form-control select2" id="owner_id" name="owner_id"
                                                            required style="width: 100%;">
                                                    </div>
                                                    {{-- @if (Auth::user()->type_id ==
                                                    config('constants.user_types.developer')) --}}
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label class="text-orange">ASSIGN UNITS</label>
                                                            <div class="col-12">
                                                                <input class="form-control" id="myInput" type="text"
                                                                    placeholder="Search..">
                                                            </div>
                                                        </div>
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Code</th>
                                                                    <th>Name</th>
                                                                    <th>BU</th>
                                                                    <th>CC</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="myTable">
                                                                {{-- @foreach ($user_unit_new as $item)
                                                                <tr>
                                                                    <td>
                                                                        <div class="form-group clearfix">
                                                                            <div class="icheck-warning d-inline">
                                                                                <input type="checkbox"
                                                                                    value="{{ $item->id }}" id="units[]"
                                                                                    name="units[]">

                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td><span for="accounts"> <span class="text-gray">{{
                                                                                $item->user_unit_code }}</span>
                                                                        </span>
                                                                    </td>
                                                                    <td><span for="accounts"> <span class="text-gray">{{
                                                                                $item->user_unit_description }}</span>
                                                                        </span>
                                                                    </td>
                                                                    <td><span for="accounts"> <span class="text-gray">{{
                                                                                $item->user_unit_bc_code }}</span>
                                                                        </span>
                                                                    </td>
                                                                    <td><span for="accounts"> <span class="text-gray">{{
                                                                                $item->user_unit_cc_code }}</span>
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                                @endforeach --}}
                                                            </tbody>
                                                        </table>


                                                        <button type="submit" class="btn btn-sm btn-info">
                                                            Assign
                                                        </button>
                                                    </div>
                                                    {{-- @endif --}}
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.post -->
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="workflow">
                                    <!-- Post -->
                                    <div class="post">

                                        <div class="row">
                                            <div class="col-2">
                                                <button class="btn btn-sm btn-outline-success  mb-3"
                                                    onclick="getMyWorkflow('{{ $user->user_unit_code }}')" required
                                                    style="width: 100%;">Search
                                                </button>

                                            </div>
                                            <div class="col-6">
                                                <div id="loader_c_2" style="display: none;">
                                                    <img src=" {{ asset('assets/gif/Eclipse_loading.gif') }} "
                                                        width="100px" height="100px">
                                                </div>

                                            </div>
                                            {{-- @if (Auth::user()->type_id == config('constants.user_types.developer')
                                            ||
                                            Auth::user()->type_id == config('constants.user_types.mgt')) --}}
                                            <div class="col-2">
                                                <label>Sync Workflow for : </label>
                                                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                               document.getElementById('search-form123').submit();">
                                                    {{ $user->user_unit->user_unit_description ?? '' }} </a>
                                                <form id="search-form123" action="" method="post" class="d-none">
                                                    @csrf
                                                </form>
                                            </div>
                                            {{-- @endif --}}
                                            <div class="col-12">
                                                <div id="table_body_div">


                                                    <br> <label class="text-green">Director Approval</label>
                                                    <hr>
                                                    <div id="directors_div">
                                                    </div>


                                                    <br> <label class="text-green">Snr Manager Approval</label>
                                                    <hr>
                                                    <div id="divisional_div">
                                                    </div>


                                                    <br> <label class="text-green">Chief Accountant Approval</label>
                                                    <hr>
                                                    <div id="ca_div">
                                                    </div>


                                                    <br> <label class="text-green">HRM Approval</label>
                                                    <hr>
                                                    <div id="hrm_div">
                                                    </div>


                                                    <br> <label class="text-green">HOD Approval</label>
                                                    <hr>
                                                    <div id="hod_div">
                                                    </div>


                                                    <br> <label class="text-green">Audit Approval</label>
                                                    <hr>
                                                    <div id="audit_div">
                                                    </div>


                                                    <br> <label class="text-green">Expenditure Approval</label>
                                                    <hr>
                                                    <div id="expenditure_div">
                                                    </div>

                                                    <br> <label class="text-green">Management Accountants
                                                        Approval</label>
                                                    <hr>
                                                    <div id="ma_div">
                                                    </div>

                                                    <br> <label class="text-green">Security Approval</label>
                                                    <hr>
                                                    <div id="security_div">
                                                    </div>

                                                    <br> <label class="text-green">Sheq Approval</label>
                                                    <hr>
                                                    <div id="sheq_div">
                                                    </div>

                                                    <br> <label class="text-green">Transport Approval</label>
                                                    <hr>
                                                    <div id="transport_div">
                                                    </div>

                                                    <br> <label class="text-green">Payroll Approval</label>
                                                    <hr>
                                                    <div id="payroll_div">
                                                    </div>

                                                    <br> <label class="text-green">PSA Approval</label>
                                                    <hr>
                                                    <div id="psa_div">
                                                    </div>

                                                    <br> <label class="text-green">PHRO Approval</label>
                                                    <hr>
                                                    <div id="phro_div">
                                                    </div>

                                                    <br> <label class="text-green">Area Manager Approval</label>
                                                    <hr>
                                                    <div id="arm_div">
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.post -->
                                </div>
                                <!-- /.tab-pane -->

                                <div class="active tab-pane" id="pass_reset">
                                    <div>
                                        <!-- form start -->
                                        <form method="POST" wire:submit.prevent="resetPassword">
                                            @csrf
                                            <div class="p-4">

                                                <div class="form-group row">
                                                    <label for="password"
                                                        class="col-md-4 col-form-label text-md-right">{{ __('Password')
                                                        }}</label>
                                                    <div class="col-md-6">
                                                        <input id="password" type="otp"
                                                            class="form-control @error('otp') is-invalid @enderror"
                                                            wire:model="otp" value="{{ old('otp') }}" required
                                                            autocomplete="otp" autofocus>
                                                        @error('otp')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row mb-0">
                                                    <div class="col-md-8 offset-md-4">
                                                        <button type="submit" class="btn btn-primary">
                                                            {{ __('Change Password') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->



    <!-- VIEW MODAL-->
    <div class="modal fade" id="modal-edit-profile">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center">{{ $user->name }} Profile Picture</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form" method="post" action="" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <label>
                                    <input style="display: none" class="form-control" type="file" id="avatar"
                                        name="avatar" value="">
                                        @if(!empty($user->avatar))
                                        <img class="img-fluid " width="100%"
                                        src="{{ asset('storage/user_avatar/' . $user->avatar) }}" alt="Image not found" />
                                        @else
                                        <img class="img-fluid " width="100%"
                                        src="{{ asset('assets/img/avatar.png') }}" alt="Image not found">
                                        @endif

                                    <small id="fileHelp" class="form-text text-muted"><b>Click Image to change it</b>.
                                        Size of image should not be more than 2MB.</small>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.View modal -->


    <!-- VIEW MODAL-->
    <div class="modal fade" id="modal-profile-delegate">
        <div class="modal-dialog modal-lg ">
            <div class="modal-content">
                <div class="modal-header" id="delegate_name">

                </div>
                <!-- form start -->
                <form role="form" method="post" action="" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div id="delegate_div">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.View modal -->

</div>
