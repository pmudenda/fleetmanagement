@extends('layouts.app')
@push('styles')
@endpush
@section('content')
    <x-content-header
        :activeCrumb="'Onboard Mechanic'"
        :linkText="'Booking'"
        :pageTitle="'New Reservation'"/>

    <section id="tms_app_main" class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h4>Add Mechanic</h4>
                </div>
                <div id="actionButtonsContainer"
                     class="card-toolbar justify-content-end"
                     style="display: none;">
                    <button type="button" id="submitUserBtn"
                            class="btn btn-success btn-sm mr-3">
                        <i class="fas fa-save"></i> Save
                    </button>
                    <button type="button" id="resetUserFormBtn"
                            class="btn btn-danger btn-sm mr-3">
                        <i class="fas fa-undo"></i> Clear Data
                    </button>
                </div>
            </div>

            <div class="card-body py-4 min-h-600px">

                <x-error-view/>

                <form name="tms_user_definition"
                      data-action="{{route('user.search')}}"
                      action="{{route('mechanic.save')}}"
                      method="post">
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
                                                           id="employee_search_criteria"
                                                           placeholder="Enter staff number"
                                                           name="employee_search_criteria" required>
                                                    <div class="input-group-addon">
                                                        <button type="button" id="employeeSearchBtn"
                                                                name="userSearchBtn"
                                                                class="btn btn-primary btn-sm border-radius-0">
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
                    <div class="card-body user-data pl-0">
                        <label class="app-required-marker"></label>
                        <div class="container-fluid mt-5">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                       for="staff_name">
                                                    Name:
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="nane"
                                                           name="name"
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
                                                           readonly
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
                                        {{--   <div class="row">
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
                                           </div>--}}
                                    </div>
                                </div>
                            </div>

                            <!--Grade And Position-->
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                       for="mobile_no">Grade:</label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="grade"
                                                           readonly
                                                           name="grade"
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
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                       for="job_title">
                                                    Position:
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="text"
                                                           class="form-control form-control-sm"
                                                           id="job_title"
                                                           name="job_title"
                                                           required readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--Email Address And Mobile Number-->
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

                            <!--Directorate And Department-->
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

                            <!--Business Unit And Cost Center-->
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                       for="mobile_no">Business Unit:</label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="hidden" name="business_unit_code">
                                                    <select type="text" class="form-select form-control-sm"
                                                            id="bc_code"
                                                            disabled
                                                            name="bu_code"
                                                            autocomplete="off">
                                                        <option></option>
                                                        @foreach ($businessUnits as $businessUnit)
                                                            <option
                                                                value="{{$businessUnit->code_bu}}">
                                                                {{$businessUnit->code_bu}}
                                                                -> {{$businessUnit->description}}
                                                            </option>
                                                        @endforeach
                                                    </select>
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
                                                    Cost Center:
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="hidden" name="cost_center_code">
                                                    <input type="hidden" name="nrc">
                                                    <select type="text" class="form-select form-control-sm"
                                                            id="cc_code"
                                                            name="cc_code"
                                                            required disabled>
                                                        <option></option>
                                                        @foreach ($costCenters as $costCenter)
                                                            <option
                                                                value="{{$costCenter->code_cost_center}}">
                                                                {{$costCenter->code_cost_center}}
                                                                -> {{$costCenter->description}}
                                                            </option>
                                                        @endforeach
                                                    </select>
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
                                                <label for="workshopCode"
                                                       class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required">
                                                    Workshop:
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <select
                                                        class="form-select form-select-sm"
                                                        id="workshopCode"
                                                        name="workshopCode">
                                                        <option>--Select Section--</option>
                                                        @foreach($workshopList as $workshop)
                                                            <option value="{{$workshop->workshop_code}}">
                                                                {{$workshop->workshop_name}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label for="workShopSection"
                                                       class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required">
                                                    Section:
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <select class="form-select form-select-sm"
                                                            id="workShopSection"
                                                            name="workShopSection">
                                                        <option>--Select Section--</option>
                                                        @foreach($workshopSectionList
                                                                   as $workshop_section)
                                                            <option value="{{$workshop_section->code}}">
                                                                {{$workshop_section->name}}
                                                            </option>
                                                        @endforeach
                                                    </select>
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
                                                       for="user_profile">
                                                    Profile :
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <select name="user_profile" id="user_profile"
                                                            class="form-control form-select-sm"
                                                            required>
                                                        <option value="{{$role->id}}">
                                                            {{$role->name}}
                                                        </option>
                                                    </select>
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
                                                       for="businessArea">
                                                    Business Area:
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <select name="businessArea" id="business_area"
                                                            class="form-control form-select-sm"
                                                            required>
                                                        <option value>--Choose Business Area--</option>
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
        </div>
    </section>
    <x-employee-search-modal/>
    <input type="hidden"
           id="businessAreaEndpoint"
           name="businessAreaEndpoint"
           value="{{ route('business.areas') }}">
@endsection
@push('scripts')
    <script src="{{asset('application/modules/userManagement/employee.search.js')}}"></script>
    <script src="{{asset('application/modules/userManagement/users/add_user.js')}}"></script>
@endpush
