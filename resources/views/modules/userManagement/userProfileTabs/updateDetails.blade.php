@php use App\Models\reference\Area; @endphp
<form class="form-horizontal" name="updateDataUpdate" method="post"
      action="{{ route('user.update') }}">
    @csrf
    <div class="form-group row">
        <label for="inputName" class="col-sm-2 col-form-label">Name:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="name" required
                   placeholder="Name" value="{{ $user->name }}">
            <input type="hidden" id="userId" name="userId" required value="{{ $user->id}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="inputEmail" class="col-sm-2 col-form-label">Email:</label>
        <div class="col-sm-10">
            <input type="email" class="form-control" name="email" required
                   placeholder="Email" value="{{ $user->email }}">
        </div>
    </div>

    <div class="form-group row">
        <label for="inputName2" class="col-sm-2 col-form-label">Extension:</label>
        <div class="col-sm-10">
            <input type="text"
                   class="form-control"
                   name="phone" required
                   placeholder="extension"
                   value="{{ $user->extension }}"/>
        </div>
    </div>

    {{--<div class="form-group row">
        <label for="inputjob_code" class="col-sm-2 col-form-label">Job
            Code</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="job_code"
                   placeholder="job_code" value="{{ $user->job_code }}">
        </div>
    </div>--}}

    {{--
         <div class="form-group row">
              <label for="inputExperience" class="col-sm-2 col-form-label">
                  User Type
              </label>
          <div class="col-sm-10">
              <select class="form-control" name="user_type_id" required>
                  <option value="{{ $user->user_type->id ?? '' }} ">
                      {{ $user->user_type->name ?? 'Please Select User Type' }}
                  </option>

                  @if (\Illuminate\Support\Facades\Auth::user()->id != $user->id)
                      @if (Auth::user()->type_id == config('constants.user_types.developer'))
                          @foreach ($user_types as $item)
                              <option value="{{ $item->id }}">{{ $item->name }}
                              </option>
                          @endforeach
                      @endif
                  @endif
              </select>
          </div>
      </div>
      --}}
    {{--<div class="form-group row">
        <label for="inputName2" class="col-sm-2 col-form-label text-orange">
            User Unit
        </label>
        <div class="col-sm-10">
            <select id="user_unit_new" class="form-control user_unit_new"
                    name="user_unit_new">
                <option value="{{ $user->user_unit->id ?? '' }} ">
                    {{ $user->user_unit->user_unit_description ?? '' }}
                    :
                    {{ $user->user_unit->user_unit_code ?? 'Please Select User Unit' }}
                </option>
                Auth::user()->type_id == config('constants.user_types.developer')
                @if (Auth::user()->id == $user->id ||
                     \App\Helpers\Authorise::hasDeveloperUserType(Auth::user()))
                    @foreach ($user_unit_new as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->user_unit_description }}
                            : {{ $item->user_unit_code }}
                        </option>
                    @endforeach
                @endif
            </select>

        </div>
    </div>--}}


    <div class="form-group row">
        <label for="inputName2" class="col-sm-2 col-form-label">Man No:</label>
        <div class="col-sm-10">
            <input disabled type="text"
                   class="form-control"
                   name="staff_no"
                   required
                   placeholder="Staff No"
                   value="{{ $user->staff_no }}">
        </div>
    </div>

    <div class="form-group row">
        <label for="inputExperience" class="col-sm-2 col-form-label">
            Business Area:
        </label>
        <div class="col-sm-10">
            @can(config('rights.user_update'))
                <select class="form-select" id="area"
                        name="area">
                    @foreach(Area::get() as $area)
                        @if($area->area == $user->area_code)
                            <option value="{{$area->area}}">{{$area->description}}</option>
                        @else
                            <option value="{{$area->area}}">{{$area->description}}</option>
                        @endif
                    @endforeach
                </select>
            @else
                <select disabled class="form-control" id="area"
                        name="area">
                    @foreach(Area::get() as $area)
                        @if($area->area == $user->area_code)
                            <option value="{{$area->area}}">{{$area->description}}</option>
                        @else
                            <option value="{{$area->area}}">{{$area->description}}</option>
                        @endif
                    @endforeach
                </select>
            @endcan

        </div>
    </div>

    <div class="form-group row">
        <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3"
               for="mobile_no">Supervisor:</label>
        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
            @canany([config('rights.user_update')])
                <div class="input-group">
                    <input type="text"
                           id="staff_supervisor"
                           name="staff_supervisor"
                           data-bs-toggle="modal"
                           autocomplete="off"
                           data-bs-target="#searchEmployeeModal"
                           data-assignmenttype="single"
                           data-inputfield="staff_supervisor"
                           class="form-control form-control-sm"/>

                    <input type="hidden"
                           data-assignmenttype="single"
                           data-inputfield="staff_supervisorId"
                           id="staff_supervisorId"
                           name="staff_supervisorId"/>
                    <div class="input-group-append">
                        <div
                            data-assignmenttype="single"
                            data-inputfield="staff_supervisor"
                            data-field="userSelection"
                            class="input-group-text">
                            <i class="fa fa-user"></i>
                        </div>
                        <div style="cursor: pointer;"
                             title="clear selection"
                             data-action="clearUsers"
                             class="input-group-text">
                            <i data-action="clearUsers"
                               class="fa fa-eraser"></i>
                        </div>
                    </div>
                </div>
            @endcanany
        </div>
    </div>

    <div class="form-group row">
        <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
               for="user_profile">Profile: </label>
        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
            <select name="user_profile" id="user_profile"
                    class="form-control form-select-sm"
                    required>
                <option value>--Choose Profile--</option>
                @foreach ($roles as auth()->user()->roles()->first()->id)
                    @if($groupName->id == 'default')
                        <option selected
                                value="{{$groupName->id}}">{{$groupName->description}}</option>
                    @else
                        <option
                            value="{{$groupName->id}}">{{$groupName->description}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>


    <div class="form-group justify-content-end">
        @canany([config('rights.user_update')])
            <div class="col-md-12">
                <div class="d-flex justify-content-end">
                    <button type="submit"
                            id="updateUserData"
                            class="btn btn-sm btn-success mr-3">
                        Update
                    </button>

                    <button type="button"
                            id="syncUserData"
                            data-href="{{ route('user.sync') }}"
                            class="btn btn-sm btn-default">
                        Sync <i class="fas fa-sync"></i>
                    </button>
                </div>
            </div>
        @endcanany
    </div>
</form>
<x-employee-search-modal/>
