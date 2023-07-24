@php use App\Models\reference\Area; @endphp
<form class="form-horizontal" name="updateDataUpdate" method="post"
      action="{{ route('user.update') }}">
    @csrf
    <div class="form-group row">
        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="name" required
                   placeholder="Name" value="{{ $user->name }}">
            <input type="hidden" id="userId" name="userId" required value="{{ $user->id}}">
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
        <label for="inputName2" class="col-sm-2 col-form-label">Extension</label>
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
        <label for="inputName2" class="col-sm-2 col-form-label">Man No</label>
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
            Area
        </label>
        <div class="col-sm-10">
            <select disabled class="form-control" id="division_select"
                    name="user_division_id">
                {{--<option value="{{ $user->division->id ?? '' }}  ">
                    {{ $user->division->name ?? '' }} </option>--}}
                @foreach(Area::get() as $area)
                    <option value="{{$area->area}}">{{$area->description}}</option>
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
