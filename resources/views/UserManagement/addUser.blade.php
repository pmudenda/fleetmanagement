@extends('layouts.app')
@push('styles')
@endpush
@section('content')

    <x-content-header/>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h4>Add User</h4>
                </div>
            </div>

            <div class="card-body py-4 min-h-600px">

                <x-error-view/>

                <form name="db2" action="{{route('user.store')}}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-5">
                                <div class="container-fluid pl-0">
                                    <div class="row">
                                        <div class="form-group row">
                                            <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 app-field-label"
                                                   for="staff_no">Find By:
                                            </label>
                                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="search_criteria"
                                                           placeholder="Find by full name | username | email "
                                                           name="search_criteria" required>
                                                    <div class="input-group-addon">
                                                        <button type="button" id="userSearchBtn"
                                                                name="userSearchBtn"
                                                                class="btn btn-outline-primary btn-sm ">
                                                            <i class="fas fa-search"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body user-data">
                        <label class="app-required-marker"></label>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                       for="staff_name">
                                                    First Name:
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="first_name"
                                                           name="first_name"
                                                           required readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                       for="staff_email"> Last Name:
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="last_name"
                                                           name="last_name"
                                                           required readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                       for="staff_email"> Email Address:
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="staff_email"
                                                           name="staff_email"
                                                           required readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                       for="staff_name">
                                                    Login Name:
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="login_name"
                                                           name="login_name"
                                                           required readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                       for="mobile_no">Staff Number:</label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="staff_number"
                                                           name="staff_number"
                                                           autocomplete="off"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3"
                                                       for="mobile_no">Mobile Number:</label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="mobile_no"
                                                           name="mobile_no">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                       for="directorate"> Directorate:
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="directorate"
                                                           name="directorate" readonly required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                       for="user_unit">
                                                    Department :
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="user_unit"
                                                           name="user_unit"
                                                           readonly required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3"
                                                       for="mobile_no">Supervisor:</label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
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
                                                                data-inputfield="nonConformanceOriginator"
                                                                data-field="userSelection"
                                                                class="input-group-text">
                                                                <i class="fa fa-user"></i>
                                                            </div>
                                                            <div style="cursor: pointer;" title="clear selection"
                                                                 data-action="clearUsers"
                                                                 class="input-group-text">
                                                                <i data-action="clearUsers"
                                                                   class="fa fa-eraser"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                       for="user_type_id">Group :</label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <select name="user_role_id" id="user_role_id"
                                                            class="form-control form-select-sm"
                                                            required>
                                                        <option value=""> --Choose Group--</option>
                                                        @foreach ($roles as $groupName)
                                                            @if($groupName == 'default')
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <input type="hidden" value="{{ route('user.search') }}" id="newUserSearchUrl">
            </div>

            {{--<input type="hidden" id="userSearchEndpoint" name="userSearchEndpoint" value="{{ route('api.users.search') }}">--}}
        </div>
    </section>
@endsection
@push('scripts')

    <script src="{{asset('assets/plugins/time-ago/time-ago.js')}}"></script>
    <script>
        window.TimeAgo.addDefaultLocale({
            locale: 'en',
            now: {
                now: {
                    current: "now",
                    future: "in a moment",
                    past: "just now"
                }
            },
            long: {
                year: {
                    past: {
                        one: "{0} year ago",
                        other: "{0} years ago"
                    },
                    future: {
                        one: "in {0} year",
                        other: "in {0} years"
                    }
                },
            }
        })
    </script>
    {{--  <script src="{{asset('application/modules/userManagement/users/add_user.js')}}"></script>
      <script src="{{asset('application/modules/userManagement/users/table.js')}}"></script>
      <script src="{{asset('application/modules/userManagement/users/users-search.js')}}"></script>--}}
@endpush
