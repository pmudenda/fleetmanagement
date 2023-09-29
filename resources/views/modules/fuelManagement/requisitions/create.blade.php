@php use App\Enums\RequisitionTypes;use App\Helpers\StatusHelper;use Carbon\Carbon; @endphp
@extends('layouts.app')
@push('styles')
    <link href="{{asset("assets/plugins/select2/css/select2.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css")}}" rel="stylesheet"
          type="text/css"/>

    <style>
        .select2-dropdown select2-dropdown--below {
            width: 400px !important;
        }
    </style>
@endpush
@section('content')

    <x-content-header :pageTitle="'Fuel Requisitions'"
                      :linkText="'Requisitions'"
                      :activeCrumb="'Fuel Requisition'"/>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h4>New Fuel Requisition</h4>
                </div>
                <div id="actionButtonsContainer" class="card-toolbar justify-content-end">
                    <button type="button" id="submitRequisitionBtn" class="btn btn-success btn-sm mr-3 when_odo_valid"
                            disabled>
                        <i class="fas fa-save"></i>
                        Submit
                    </button>
                    <button type="button" id="resetRequisitionBtn" class="btn btn-danger btn-sm mr-3">
                        <i class="fas fa-undo"></i> Cancel
                    </button>
                </div>
            </div>

            <div class="card-body pb-4 min-h-600px pt-0 pl-2">

                <x-error-view/>

                <form name="fuelRequisitionForm"
                      id="fuelRequisitionForm"
                      action="{{route('save.fuel.requisition')}}"
                      method="post">
                    @csrf
                    <div class="card-body user-data pl-0">
                        <label class="app-required-marker"></label>
                        <div class="container-fluid mt-2">
                            <div class="row">
                                <div class="col-9">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="staff_no">Registration #:
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <div class="input-group">
                                                                <input type="text"
                                                                       data-action="{{
                                                                        route('requisition.vehicle.details')
                                                                        }}"
                                                                       class="form-control form-control-sm"
                                                                       autocapitalize="characters"
                                                                       id="vehicle_registration"
                                                                       placeholder="Vehicle Reg e.g AAB 6757"
                                                                       name="vehicle_registration" required>
                                                                <div class="input-group-addon">
                                                                    <button type="button" id="vehicleSearchBtn"
                                                                            name="vehicleSearchBtn"
                                                                            class="btn btn-success
                                                                            btn-sm border-radius-0">
                                                                        <i class="fas fa-search"></i>
                                                                    </button>
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
                                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                            <input type="text"
                                                                   class="form-control form-control-sm"
                                                                   id="vehicle_description"
                                                                   name="vehicle_description"
                                                                   required readonly>
                                                        </div>
                                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                            <label
                                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4
                                                                app-field-label"
                                                                for="staff_no">Status:
                                                            </label>
                                                            <span id="vehicle_status" class="ml-3 badge badge-success"
                                                                  data-name="vehicle_status"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <div
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4
                                                            control-input-wrapper">
                                                            <div class="control-input">
                                                                <div class="link-field ui-front"
                                                                     style="position: relative;">
                                                                    <label class="form-check-inline">
                                                                        <input type="radio"
                                                                               id="costOnCostCentre"
                                                                               class="list-row-checkbox bold mr-3
                                                                               when_valid"
                                                                               name="CostAssignedTo"
                                                                               value="CostCenterBasedRequisition"
                                                                               checked>
                                                                        User Department
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   id="cost_centre_code"
                                                                   value="{{$organizationalUnit->code_unit ?? ''}}"
                                                                   name="cost_centre_code"
                                                                   required
                                                                   readonly
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   id="cost_center_name"
                                                                   value="{{$organizationalUnit->description ?? ''}}"
                                                                   name="cost_center_name"
                                                                   required
                                                                   readonly
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <div
                                                            class=" col-xs-12 col-sm-6 col-md-5 col-lg-4
                                                            control-input-wrapper">
                                                            <div class="control-input">
                                                                <div class="link-field ui-front"
                                                                     style="position: relative;">
                                                                    <label for="project_code" class="form-check-inline">
                                                                        <input type="radio"
                                                                               id="projectInput"
                                                                               class="list-row-checkbox
                                                                               bold mr-3 when_valid"
                                                                               autocomplete="off"
                                                                               name="CostAssignedTo"
                                                                               value="ProjectBasedRequisition">
                                                                        Project
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="col-xs-12 col-sm-6 col-md-7 col-lg-6
                                                            d-none project_view_item">
                                                            <select disabled type="text" name="project_code"
                                                                    class="form-select mt-1 project-code-ajax"
                                                                    id="project_code">
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-6 d-none project_view_item">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                                                            <input type="text"
                                                                   readonly
                                                                   id="ProjectName"
                                                                   class="form-control form-control-sm"
                                                                   name="ProjectName"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="requisition_type">
                                                            Requisition Type:
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <select name="requisition_type" id="requisition_type"
                                                                    disabled
                                                                    class="form-control form-select-sm when_valid"
                                                                    required>
                                                                <option value="">--Select--</option>
                                                                @foreach ($requisitionTypes as $requisitionType)
                                                                    <option
                                                                        value="{{$requisitionType->code}}">
                                                                        {{$requisitionType->name}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
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
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="odometer_reading">
                                                            Current Odometer Reading :
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <input type="number"
                                                                   min="1"
                                                                   data-url="{{route('fuel.odometer.validation')}}"
                                                                   data-validation="fuelRequisitionOdometerReading"
                                                                   data-params="[odometerNumber, vehicleRegistration]"
                                                                   class="form-control form-control-sm when_valid
                                                                   number_input"
                                                                   id="odometer_reading"
                                                                   disabled
                                                                   required
                                                                   name="odometer_reading"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="driver_staff_number">
                                                            Driver:
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <div class="input-group">
                                                                <input type="text"
                                                                       list="employee_list"
                                                                       data-action="{{route('driver.search')}}"
                                                                       class="form-control form-control-sm"
                                                                       autocapitalize="characters"
                                                                       id="driver_staff_number"
                                                                       placeholder=""
                                                                       name="driver_staff_number"/>
                                                                <div class="input-group-addon">
                                                                    <button type="button" id="employeeSearchBtn"
                                                                            name="employeeSearchBtn"
                                                                            class="btn btn-success
                                                                            btn-sm border-radius-0">
                                                                        <i class="fas fa-search"></i>
                                                                    </button>
                                                                </div>
                                                                <datalist id="employee_list">
                                                                </datalist>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                                                            <input type="text"
                                                                   class="form-control form-control-sm"
                                                                   id="driver_name"
                                                                   name="driver_name"
                                                                   readonly/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-6"></div>
                                        <div class="col-xs-12 col-sm-12 col-md-6"></div>
                                    </div>

                                    <div class="row d-none outOfTown">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="departure_date">
                                                            Departure Date:
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <div class="input-group date"
                                                                 data-target-input="nearest">
                                                                <input type="date"
                                                                       min="{{ date('Y-m-d',
                                                                       strtotime(Carbon::now())) }}"
                                                                       max="{{ date('Y-m-d',
                                                                       strtotime(Carbon::now())) }}"
                                                                       value="{{ date('Y-m-d',
                                                                       strtotime(Carbon::now())) }}"
                                                                       name="departure_date"
                                                                       id="departure_date"
                                                                       readonly
                                                                       autocomplete="off"
                                                                       class="form-control form-control-sm
                                                                       date_input"/>
                                                                <div class="input-group-append">
                                                                    <div type="button"
                                                                         class="input-group-text">
                                                                        <i class="fa fa-calendar"></i>
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
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="return_date">
                                                            Return Date:
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <div class="input-group date"
                                                                 data-target-input="nearest">
                                                                <input type="date"
                                                                       min="{{ date('Y-m-d',
                                                                        strtotime(Carbon::now())) }}"
                                                                       name="return_date"
                                                                       id="return_date"
                                                                       autocomplete="off"
                                                                       class="form-control form-control-sm
                                                                       date_input datetimepicker-opened"
                                                                       data-target="#dateOpened"/>
                                                                <div class="input-group-append"
                                                                     data-target="#dateOpened"
                                                                     data-action="dateOpenedPicker">
                                                                    <div type="button"
                                                                         data-target="return_date"
                                                                         data-action="open_picker"
                                                                         class="input-group-text">
                                                                        <i data-action="dateOpenedPicker"
                                                                           class="fa fa-calendar"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row d-none outOfTown">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="departureTown">
                                                            Departure Town:
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <select required class="form-control city select2"
                                                                    name="departureTown"
                                                                    id="departureTown">
                                                                <option value="">--Select Departure--</option>
                                                            </select>
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
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="destinationTown">
                                                            Destination Town:
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <select required
                                                                    class="form-control city select2"
                                                                    disabled
                                                                    name="destinationTown"
                                                                    id="destinationTown">
                                                                <option value="">--Select Destination--</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6 col-md-6">
                                                <div class="container-fluid pl-0">
                                                    <div class="row">
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4
                                                                field-required"
                                                                for="covered_kilometers">
                                                                Estimated Distance (Km):
                                                            </label>
                                                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                <input type="text"
                                                                       required
                                                                       class="form-control number_input"
                                                                       name="covered_kilometers"
                                                                       id="covered_kilometers"
                                                                       readonly
                                                                       placeholder="Enter the Kilometers to be Covered">
                                                                <table
                                                                    id="trip_path"
                                                                    aria-label="Distance Chart">
                                                                    <thead class="d-none">
                                                                    <tr>
                                                                        <th scope="row"></th>
                                                                        <th scope="row"></th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tr>
                                                                        <td id="one_way"></td>
                                                                        <td id="one_way_distance"></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6"
                                             id="allocationContainer">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="fuel_allocation">
                                                            Allocation Per Week:
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <div class="input-group input-group-sm">
                                                                <input type="text"
                                                                       class="form-control form-control-sm"
                                                                       id="fuel_allocation"
                                                                       name="fuel_allocation"
                                                                       readonly
                                                                />
                                                                <div class="input-group-text">
                                                                    Ltr
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
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="request_date">
                                                            Request Date:
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   id="request_date"
                                                                   readonly
                                                                   value="{{Carbon::now()->format('d/m/Y')}}"
                                                                   name="request_date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div id="nextRefuelingDateContainer"
                                                 class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-12 col-md-5 col-lg-4 field-required"
                                                            for="next_fuel_date">
                                                            Next Refueling Date :
                                                        </label>
                                                        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-6">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   id="next_fuel_date"
                                                                   value="{{Carbon::now()->add('days',
                                                                   $daysToNextRefuel)->format('d/m/Y')}}"
                                                                   name="next_fuel_date"
                                                                   readonly
                                                                   required
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="justification">
                                                            Purpose:
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8">
                                                        <textarea type="text"
                                                                  id="justification"
                                                                  name="justification"
                                                                  disabled
                                                                  style="height: 129px;"
                                                                  class="form-control
                                                                  form-control-sm when_valid"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row d-none" id="authorityToTravelContainer">
                                                    <div class="form-group row">
                                                        <label
                                                            id="authority"
                                                            class="col-xs-12 col-sm-12 col-md-12 col-lg-12
                                                            field-required">
                                                            Authority To Travel(<small>Any Authorization
                                                                Document</small>)
                                                        </label>
                                                        <input type="file"
                                                               id="authorityToTravel"
                                                               name="authorityToTravel"
                                                               class="form-control filer_input">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div id="vehicleDetailsContainer"
                                         style="display: none;"
                                         class="col-xs-12 col-sm-12 col-md-12">
                                    </div>

                                    <div id="image_view"
                                         class="card text-center my-2"
                                         style="display: none;">
                                        <div class="form-group">
                                            <div class="imagePreview"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div id="materialDetailsContainer"
                                 class="table-responsive mt-3">
                                <table id="materialDetailsTable"
                                       aria-label="fuel request detail"
                                       class="table table-bordered">
                                    <thead>
                                    <tr class="bg-dark">
                                        <th scope="row">Material Description</th>
                                        <th scope="row" class="project_view_item d-none">
                                            Project Number
                                        </th>
                                        <th scope="row" style="width: 15%;">Quantity</th>
                                        <th scope="row">Unit Of Measure</th>
                                        <th scope="row">Price (ZMW)</th>
                                        <th scope="row">Amount(ZMW)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <span data-material-input="material_description"
                                                  id="material_description"></span>
                                            <input type="hidden"
                                                   name="material_description"/>
                                            <input type="hidden"
                                                   name="material_article_code"/>
                                        </td>
                                        <td class="project_view_item d-none">
                                            <input type="text"
                                                   name="projectCode"
                                                   readonly value="000000"
                                                   class="form-control form-control-sm border-0"/>
                                        </td>
                                        <td>
                                            <input type="number" name="material_quantity"
                                                   max=""
                                                   min=""
                                                   disabled
                                                   id="material_quantity"
                                                   class="form-control form-control-sm when_valid"/>
                                        </td>
                                        <td>
                                            <span data-material-input="unit_of_measure"
                                                  id="unit_of_measure"></span>
                                            <input type="hidden" name="unit_of_measure">
                                        </td>
                                        <td>
                                            <span data-material-input="material_price"
                                                  id="material_price"></span>
                                            <input type="hidden" name="material_price" value="12">
                                        </td>
                                        <td>
                                            <span data-material-input="material_amount"
                                                  id="material_amount"></span>
                                            <input type="hidden" name="material_amount">
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td class="project_view_item d-none"></td>
                                        <td class="text-right"></td>
                                        <td style="display: flex;justify-content: space-between;">
                                            <strong>Total Quantity</strong>
                                            <span class="text-bold text-right" id="totalQty"></span>
                                        </td>
                                        <td></td>
                                        <td class="text-right"></td>
                                        <td style="display: flex;justify-content: space-between;">
                                            <strong>Total Amount</strong>
                                            <span class="text-bold" id="totalAmount"></span>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>

                <input type="hidden" value="{{route('user.search') }}" id="newUserSearchUrl">
                <input type="hidden" value="{{route('search.project')}}" id="projects_url">
                <input type="hidden" value="{{route('fuel.last.requisition')}}" id="previousRequisitionUrl">
                <input type="hidden" value="{{RequisitionTypes::OutOfTown}}" id="outOfTownReqCode">
                <input type="hidden" value="{{StatusHelper::onboardingComplete()}}" name="incompleteOnBoarding"
                       id="incompleteOnBoarding"/>
                <input type="hidden" value="{{VehicleStatus::vehicleInWorkshop()}}" name="vehicleInWorkshop"
                       id="vehicleInWorkshop"/>
                <input type="hidden" value="{{StatusHelper::active()}}" name="vehicleActive" id="vehicleActive"/>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        window.citiesMap = {!! json_encode($cities) !!};
        window.citiesFrom = {!! json_encode($citiesFrom) !!};
        window.tripPeriodLimit = {!! config('maxTripPeriod') ?? 7 !!};
    </script>
    <script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('assets/js/system/project_code.js')}}"></script>
    <script src="{{asset('modules/fuelManagement/requisitions/create.js')}}"></script>
@endpush
