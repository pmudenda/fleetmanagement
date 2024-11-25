@php use App\Models\Reference\Area; @endphp
@php $allowUpdate = false;  @endphp
@if(auth()->user()->can(config('rights.user_update')))
    @php $allowUpdate = true;  @endphp
@endif
<form class="form-horizontal" name="updateDataUpdate" method="post"
      action="{{ route('user.update') }}">
    @csrf
    <fieldset @if(!$can_change) disabled @endif>
    <div class="form-group row">
        <label for="inputName" class="col-sm-2 col-form-label field-required">Name:</label>
        <div class="col-sm-10">
            <input type="text"
                   class="form-control"
                   name="name"
                   @if(!$allowUpdate)
                       readonly
                   @endif
                   required
                   placeholder="Name"
                   value="{{ $user->name }}">
            <input type="hidden" id="userId" name="userId" required value="{{ $user->id}}">
        </div>
    </div>

    <div class="form-group row">
        <label for="inputEmail" class="col-sm-2 col-form-label field-required">Email:</label>
        <div class="col-sm-10">
            <input type="email"
                   class="form-control"
                   name="email"
                   @if(!$allowUpdate)
                       readonly
                   @endif
                   required
                   placeholder="Email" value="{{ $user->email }}">
        </div>
    </div>

    <div class="form-group row">
        <label for="inputName2" class="col-sm-2 col-form-label">Extension:</label>
        <div class="col-sm-10">
            <input type="text"
                   class="form-control"
                   name="phone"
                   @if(!$allowUpdate)
                       readonly
                   @endif
                   placeholder="extension"
                   value="{{ $user->extension }}"/>
        </div>
    </div>

    <div class="form-group row">
        <label for="inputName2" class="col-sm-2 col-form-label">Man No:</label>
        <div class="col-sm-10">
            <input disabled type="text"
                   class="form-control"
                   name="staff_no"
                   required
                   @if(!$allowUpdate)
                       readonly
                   @endif
                   placeholder="Staff No"
                   value="{{ $user->staff_no }}">
        </div>
    </div>

    <div class="form-group row">
        <label for="inputExperience"
               class="col-sm-2 col-form-label field-required">
            Business Area:
        </label>
        <div class="col-sm-10">
            <select @if(!$allowUpdate)
                        disabled
                    @endif
                    class="@if($allowUpdate) form-select form-select-sm @else form-control  @endif"
                    id="area"
                    name="area">
                @foreach(Area::get() as $area)
                    @if($area->area == $user->area_code)
                        <option value="{{$area->area}}">{{$area->description}}</option>
                    @else
                        <option value="{{$area->area}}">{{$area->description}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label"
               for="mobile_no">Supervisor:</label>
        <div class="col-sm-10">
            @canany([config('rights.user_update')])
                <div class="input-group">
                    <input type="text"
                           id="staff_supervisor"
                           @if(!$allowUpdate)
                               readonly
                           @endif
                           name="staff_supervisor"
                           value="{{$user->supervisor_name ?? ''}}"
                           data-bs-toggle="modal"
                           autocomplete="off"
                           data-bs-target="#searchEmployeeModal"
                           data-assignmenttype="single"
                           data-inputfield="staff_supervisor"
                           class="form-control form-control-sm"/>

                    <input type="hidden"
                           value="{{$user->supervisor_code ?? ''}}"
                           data-assignmenttype="single"
                           data-inputfield="staff_supervisorId"
                           id="staff_supervisorId"
                           name="staff_supervisorId"/>
                    <div class="input-group-append">
                        @if($allowUpdate)
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
                        @else
                            <div
                                    class="input-group-text">
                                <i class="fa fa-user"></i>
                            </div>
                            <div style="cursor: pointer;"
                                 title="clear selection"
                                 class="input-group-text">
                                <i class="fa fa-eraser"></i>
                            </div>
                        @endif
                    </div>
                </div>
            @endcanany
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 field-required col-form-label"
               for="user_profile">Profile: </label>
        <div class="col-sm-10">
            <select name="user_profile"
                    id="user_profile"
                    @if(!$allowUpdate)
                        disabled
                    @endif

                    class="@if($allowUpdate) form-select form-select-sm @else form-control  @endif"
                    required >
                <option value>--Choose Profile--</option>
                @foreach ($roles as $groupName)
                    @if(!empty($user->roles()->first()))
                        @if($groupName->id == $user->roles()->first()->id)
                            <option selected
                                    value="{{$groupName->id}}" @if($groupName->is_superuser && !$can_change) disabled @endif>{{$groupName->description}}</option>
                        @else
                            <option
                                    value="{{$groupName->id}}">{{$groupName->description}}</option>
                        @endif
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
                        Save Changes
                    </button>

                    <button type="button"
                            title="Auto Updated User Information Based on HC Records"
                            id="syncUserData"
                            data-href="{{ route('user.sync') }}"
                            class="btn btn-sm btn-default">
                        Auto Update With HCMS
                        <i class="fas fa-sync"></i>
                    </button>
                </div>
            </div>
        @endcanany
    </div>
    </fieldset>
</form>
<x-employee-search-modal/>
