@extends('layouts.app')
@push('styles')
    <link href="{{ asset('modules/vehicleManagement/assets/css/vehicle_migration.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <style>
        .imagePreview {
            width: 100%;
            min-height: 280px;
            background-position: center center;
            background-color: #fff;
            background-size: contain;
            background-repeat: no-repeat;
            display: inline-block;
            box-shadow: 0px -3px 6px 2px rgba(0, 0, 0, 0.2);
        }
    </style>
@endpush
@section('content')
    <x-content-header :pageTitle="'Data Migration'"/>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h4>Current Vehicle Details</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-10">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="registrationNumber" class="field-required">
                                    Registration Number
                                </label>
                                <div class="input-group">
                                    <input name="registrationNumber"
                                           type="text"
                                           value="{{$registration ?? ''}}"
                                           data-action=""
                                           class="form-control form-control-sm required"
                                           id="registrationNumber"
                                           placeholder=""
                                           required/>
                                    <div class="input-group-addon">
                                        <button type="button"
                                                id="vehicleSearchBtn"
                                                name="vehicleSearchBtn"
                                                class="btn btn-success btn-sm border-radius-0">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <x-error-view/>
                </div>
            </div>

        </div>
        <div class="card mt-10">
            <div class="card-header">
                <div class="card-title">
                    <h4>Data Migration</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-10">
                    <div class="wizard">
                        <div class="wizard-inner">
                            <ul class="nav nav-tabs steps" role="tablist">
                                <li role="presentation" data-index="0" class="active st1">
                                    <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab"
                                       aria-expanded="true">
                                        <i>Vehicle Details</i>
                                        <span class="round-tab">1</span>
                                    </a>
                                </li>
                                <li role="presentation" data-index="1" class="disabled st2">
                                    <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab"
                                       aria-expanded="false">
                                        <span class="round-tab">2</span>
                                        <i>Vehicle Details</i>
                                    </a>
                                </li>
                                <li role="presentation" data-index="2" class="disabled st3">
                                    <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab">
                                        <span class="round-tab">3</span>
                                        <i>Images</i>
                                    </a>
                                </li>

                            </ul>
                        </div>

                        <form role="form"
                              name="saveCleanDataForm"
                              action="{{route('save.clean.data')}}"
                              method="post" class="">
                            @csrf
                            <div class="tab-content px-5" id="main_form">
                                <div class="tab-pane active step" role="tabpanel" id="step1">
                                    <h3 class="text-center">Vehicle Details</h3>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="registrationNumber" class="field-required">
                                                    Registration Number
                                                </label>
                                                <div class="input-group">
                                                    <input name="registrationNumber"
                                                           type="text"
                                                           value="{{$registration ?? ''}}"
                                                           class="form-control form-control-sm required"
                                                           id="registrationNumber"
                                                           placeholder=""
                                                           required/>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group ">
                                                <label class="required-field" for="vehicleType">Make :</label>
                                                <select name="vehicleMake"
                                                        class="form-select make required" id="vehicleMake" required>
                                                    @foreach($vehicleMakes as $vehicleMake)
                                                        <option value="{{$vehicleMake->id}}">
                                                            {{$vehicleMake->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="vehicleType">Model*:</label>
                                                <select name="model" class="form-control required" id="modelNo"
                                                        required>
                                                    <option>Select Model</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group ">
                                                <label for="ownerName">Model Code:</label>
                                                <input name="model_code" type="text" class="form-control required"
                                                       id="ownerName" placeholder="Enter owner name" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="engineNo">Engine No*:</label>
                                                <input name="engineNo" type="text" readonly
                                                       class="form-control required"
                                                       id="ownerAddress" placeholder="" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group ">
                                                <label for="chassisNo">Chassis No*:</label>
                                                <input name="chassisNo" type="text" class="form-control required"
                                                       id="chassisNo" placeholder="" readonly required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group ">
                                                <label for="vehicleType">Color:</label>
                                                <select title="Color" name="vehicleColor" class="form-control"
                                                        id="color">
                                                    <option selected disabled>--Select Color--</option>

                                                    @foreach($colors as $color)
                                                        <option value="{{$color->code}}">{{$color->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group row">

                                                <label class="col-4">Branded:</label>

                                                <div class="col-8"><label class="inline-check row">
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio" name="isBranded" value="yes">
                                                            <label for="poolVariance-yes">Yes</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio" name="isBranded" value="no">
                                                            <label for="poolVariance-no">No</label>
                                                        </div>
                                                    </label></div>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group ">
                                                <label for="transmission">Transmission:</label>
                                                <select name="transmission" class="form-select" id="transmission">
                                                    <option disabled>--Select transmission--</option>
                                                    <option value="AT">AUTOMATIC</option>
                                                    <option value="MT">MANUAL</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4"></div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="odometer">Current Odometer:</label>
                                                <input name="odometer" type="text" class="form-control"
                                                       id="odometer" placeholder="Current Odometer" required>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="list-inline pull-right">
                                        <li>
                                            <button type="button" class="btn btn-success btn-sm next-step">
                                                Continue to next step
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-pane step" role="tabpanel" id="step2">
                                    <h4 class="text-center">Assignment Details</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                <label for="vehicleType">Directorate*:</label>
                                                <select name="directorate" class="form-control make" id="directorate"
                                                        required>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                <label for="vehicleType">User Unit*:</label>
                                                <select name="organizationalUnit"
                                                        class="form-control make"
                                                        id="organizationalUnit"
                                                        required>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                <label for="vehicleType" class="field-required">Business Unit :</label>
                                                <input name="businessUnit" class="form-control make" id="businessUnit"
                                                       required disabled>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                <label for="vehicleType">Cost Center*:</label>
                                                <input name="costCenter" class="form-control make" id="costCenter"
                                                       required disabled/>
                                            </div>
                                        </div>

                                        <div class="col-md-12 options">
                                            <p class="test">Pool Vehicle: </p>
                                            <div class="options-inner">
                                                <input type="radio"
                                                       name="isPoolVehicle"
                                                       value="YES">
                                                <label for="isPoolVehicle">Yes</label>
                                            </div>
                                            <div class="options-inner">
                                                <input type="radio"
                                                       name="isPoolVehicle"
                                                       value="NO">
                                                <label for="no">No</label>
                                            </div>

                                        </div>


                                        <div class="col-md-6 workWhenChecked" id="responsibleUserNumber">
                                            <div class="form-group">
                                                <label for="ownerAddress">Responsible User:</label>

                                                <div class="input-group">
                                                    <input type="text"
                                                           id="responsibleHOD"
                                                           data-bs-toggle="modal"
                                                           autocomplete="off"
                                                           data-bs-target="#searchEmployeeModal"
                                                           data-assignmenttype="single"
                                                           data-inputfield="responsibleHOD"
                                                           name="responsibleHOD"
                                                           class="form-control view_mode"
                                                           value="{{$vehicle->responsible_head_name ?? ''}}"
                                                           data-emp="staff_number"
                                                           data-doctype="AssignmentDetails"
                                                    />

                                                    <div class="input-group-append input-group-sm">
                                                        <button type="button"
                                                                data-assignmenttype="single"
                                                                data-inputfield="responsibleHOD"
                                                                data-field="userSelection"
                                                                class="input-group-text view_mode">
                                                            <i class="fa fa-user"></i>
                                                        </button>
                                                        <button type="button"
                                                                data-action="clearUsers"
                                                                class="input-group-text view_mode">
                                                            <i class="fa fa-eraser"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 workWhenChecked" id="responsibleUserName">
                                            <div class="form-group ">
                                                <label> `</label>
                                                <input type="text"
                                                       readonly
                                                       class="form-control"
                                                       data-assignmenttype="single"
                                                       data-inputfield="responsibleHODId"
                                                       id="responsibleHODId"
                                                       value="{{$vehicle->responsible_head_id ?? ''}}"
                                                       name="responsibleHODId"/>
                                            </div>
                                        </div>

                                        <div class="col-md-6 workWhenChecked" id="supervisorNumber">
                                            <div class="form-group">
                                                <label for="ownerAddress">Supervisor:</label>

                                                <div class="input-group">
                                                    <input type="text"
                                                           id="supervisor"
                                                           data-bs-toggle="modal"
                                                           autocomplete="off"
                                                           data-bs-target="#searchEmployeeModal"
                                                           data-assignmenttype="single"
                                                           data-inputfield="supervisor"
                                                           name="supervisor"
                                                           class="form-control view_mode"
                                                           value="{{$vehicle->responsible_head_name ?? ''}}"
                                                           data-emp="staff_number"
                                                           data-doctype="AssignmentDetails"
                                                    />

                                                    <div class="input-group-append input-group-sm">
                                                        <button type="button"
                                                                data-assignmenttype="single"
                                                                data-inputfield="supervisor"
                                                                data-field="userSelection"
                                                                class="input-group-text view_mode">
                                                            <i class="fa fa-user"></i>
                                                        </button>
                                                        <button type="button"
                                                                data-action="clearUsers"
                                                                class="input-group-text view_mode">
                                                            <i class="fa fa-eraser"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 workWhenChecked" id="supervisorName">
                                            <div class="form-group ">
                                                <label> `</label>
                                                <input type="text"
                                                       readonly
                                                       class="form-control"
                                                       data-assignmenttype="single"
                                                       data-inputfield="supervisorId"
                                                       id="supervisorId"
                                                       name="supervisorId"
                                                       value="{{$vehicle->responsible_head_id ?? ''}}"
                                                />
                                            </div>
                                        </div>


                                        <div class="col-md-6 workWhenChecked" id="operatorNumber">
                                            <div class="form-group">
                                                <label for="ownerAddress">Operator:</label>

                                                <div class="input-group">
                                                    <input type="text"
                                                           id="operator"
                                                           data-bs-toggle="modal"
                                                           autocomplete="off"
                                                           data-bs-target="#searchEmployeeModal"
                                                           data-assignmenttype="single"
                                                           data-inputfield="operator"
                                                           name="operator"
                                                           class="form-control view_mode"
                                                           value="{{$vehicle->responsible_head_name ?? ''}}"
                                                           data-emp="staff_number"
                                                           data-doctype="AssignmentDetails"
                                                    />

                                                    <div class="input-group-append input-group-sm">
                                                        <button type="button"
                                                                data-assignmenttype="single"
                                                                data-inputfield="responsibleHOD"
                                                                data-field="userSelection"
                                                                class="input-group-text view_mode">
                                                            <i class="fa fa-user"></i>
                                                        </button>
                                                        <button type="button"
                                                                data-action="clearUsers"
                                                                class="input-group-text view_mode">
                                                            <i class="fa fa-eraser"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 workWhenChecked" id="operatorName">
                                            <div class="form-group ">
                                                <label>`</label>

                                                <input type="text"
                                                       readonly
                                                       class="form-control"
                                                       data-assignmenttype="single"
                                                       data-inputfield="operatorId"
                                                       id="operatorId"
                                                       name="operatorId"
                                                       value="{{$vehicle->responsible_head_id ?? ''}}"
                                                />

                                            </div>
                                        </div>

                                        <div class="col-md-6 workWhenChecked" id="assignedToNumber">
                                            <div class="form-group">
                                                <label for="ownerAddress">Assigned To:</label>
                                                <div class="input-group">
                                                    <input type="text"
                                                           id="assignedTo"
                                                           data-bs-toggle="modal"
                                                           autocomplete="off"
                                                           data-bs-target="#searchEmployeeModal"
                                                           data-assignmenttype="single"
                                                           data-inputfield="assignedTo"
                                                           name="assignedTo"
                                                           class="form-control view_mode"
                                                           value="{{$vehicle->responsible_head_name ?? ''}}"
                                                           data-emp="staff_number"
                                                           data-doctype="AssignmentDetails"
                                                    />

                                                    <div class="input-group-append input-group-sm">
                                                        <button type="button"
                                                                data-assignmenttype="single"
                                                                data-inputfield="responsibleHOD"
                                                                data-field="userSelection"
                                                                class="input-group-text view_mode">
                                                            <i class="fa fa-user"></i>
                                                        </button>
                                                        <button type="button"
                                                                data-action="clearUsers"
                                                                class="input-group-text view_mode">
                                                            <i class="fa fa-eraser"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 workWhenChecked" id="assignedToName">
                                            <div class="form-group ">
                                                <label> `</label>
                                                <input type="text"
                                                       readonly
                                                       class="form-control"
                                                       data-assignmenttype="single"
                                                       data-inputfield="assignedToId"
                                                       id="assignedToId"
                                                       name="assignedToId"
                                                       value="{{$vehicle->responsible_head_id ?? ''}}"
                                                />
                                            </div>
                                        </div>


                                        <div class="col-md-12 condition">
                                            <p class="test">Remarks On Current Condition: </p>
                                            <textarea class="textarea" cols="80" rows="5"></textarea>
                                        </div>
                                        <div class="col-md-12 options  mobile">
                                            <p class="test">Mobile: </p>
                                            <div class="options-inner">
                                                <input type="radio" id="poolVariance-yes" name="options"
                                                       value="poolVariance-yes">
                                                <label for="poolVariance-yes">Yes</label>
                                            </div>
                                            <div class="options-inner">
                                                <input type="radio" id="poolVariance-no" name="options"
                                                       value="poolVariance-no">
                                                <label for="poolVariance-no">No</label>
                                            </div>

                                        </div>

                                    </div>


                                    <ul class="list-inline pull-right">
                                        <li>
                                            <button type="button" class="btn btn-success btn-sm prev-step">Previous
                                            </button>
                                        </li>

                                        <li>
                                            <button type="button" class="btn btn-success btn-sm next-step">Continue
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-pane step" role="tabpanel" id="step3">
                                    <h4 class="text-center">Vehicle Images</h4>
                                    <div class="row mt-10">
                                        <div class="col-md-3" data-if="images && images.frontView">
                                            <div class="form-group ">
                                                <label
                                                    class="col-12  field-required"
                                                    for="staff_name">
                                                    Front View:
                                                    <small class="text-danger">
                                                        JPG, JPEG,PNG, BMP
                                                    </small>
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">

                                                    <div class="card text-center py-5 my-2 pt-0">
                                                        <div class="form-group">
                                                            <p
                                                                class="text-gray-400 fs-4 fw-semibold mb-10 text-center">
                                                                <button type="button"
                                                                        data-select="file"
                                                                        data-input="selectFrontViewFile"
                                                                        class="upload-file btn btn-sm btn-primary me-2">
                                                                    <i class="fas fa-cloud"></i>
                                                                    Upload Image
                                                                </button>
                                                                <input type="file" accept="image/*"
                                                                       style="display: none;"
                                                                       class="fileElem"
                                                                       id="front_view"
                                                                       name="front_view"/>
                                                            </p>
                                                            <div class="imagePreview"
                                                                 style="display: none;">
                                                                <button type="button"
                                                                        class="btn btn-xs clearImage"
                                                                        style="top: 1px;
                                            position: relative;
                                            right: 1px;
                                            float: right;
                                            padding: 2px;"><i class="fa fa-window-close" style="font-size: 20px;"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="col-md-3" data-if="images && images.rearView">
                                            <div class="form-group row">
                                                <label
                                                    class="col-12 field-required"
                                                    for="staff_name">
                                                    Rear View:
                                                    <small class="text-danger">
                                                        JPG, JPEG,PNG, BMP
                                                    </small>
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <div class="card text-center py-5 my-2 pt-0">
                                                        <div class="form-group">
                                                            <p
                                                                class="text-gray-400 fs-4 fw-semibold mb-10 text-center">
                                                                <button type="button"
                                                                        data-select="file"
                                                                        data-input="selectFrontViewFile"
                                                                        class="upload-file btn btn-sm btn-primary me-2">
                                                                    <i class="fas fa-cloud"></i>
                                                                    Upload Image
                                                                </button>
                                                                <input type="file" accept="image/*"
                                                                       style="display: none;"
                                                                       class="fileElem"
                                                                       id="rear_view"
                                                                       name="rear_view"/>
                                                            </p>
                                                            <div class="imagePreview"
                                                                 style="display: none;">
                                                                <button type="button"
                                                                        class="btn btn-xs clearImage"
                                                                        style="top: 1px;
                                            position: relative;
                                            right: 1px;
                                            float: right;
                                            padding: 2px;"><i class="fa fa-window-close" style="font-size: 20px;"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3" data-if="images && images.rightView">
                                            <div class="form-group row">
                                                <label
                                                    class="col-12  field-required"
                                                    for="staff_name">
                                                    Right View:
                                                    <small class="text-danger">
                                                        JPG, JPEG,PNG, BMP
                                                    </small>
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <div class="card text-center py-5 my-2 pt-0">
                                                        <div class="form-group">
                                                            <p
                                                                class="text-gray-400 fs-4 fw-semibold mb-10 text-center">
                                                                <button type="button"
                                                                        data-select="file"
                                                                        data-input="selectFrontViewFile"
                                                                        class="upload-file btn btn-sm btn-primary me-2">
                                                                    <i class="fas fa-cloud"></i>
                                                                    Upload Image
                                                                </button>
                                                                <input type="file" accept="image/*"
                                                                       style="display: none;"
                                                                       class="fileElem"
                                                                       id="right_view"
                                                                       name="right_view"/>
                                                            </p>
                                                            <div class="imagePreview"
                                                                 style="display: none;">
                                                                <button type="button"
                                                                        class="btn btn-xs clearImage"
                                                                        style="top: 1px;
                                            position: relative;
                                            right: 1px;
                                            float: right;
                                            padding: 2px;"><i class="fa fa-window-close" style="font-size: 20px;"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3" data-if="images && images.leftView">
                                            <div class="form-group">
                                                <label
                                                    class="col-12 field-required"
                                                    for="staff_name">
                                                    Left View:
                                                    <small class="text-danger">
                                                        JPG, JPEG,PNG, BMP
                                                    </small>
                                                </label>
                                                <div class="col-12 col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <div class="card text-center py-5 my-2 pt-0">
                                                        <div class="form-group">
                                                            <p
                                                                class="text-gray-400 fs-4 fw-semibold mb-10 text-center">
                                                                <button type="button"
                                                                        data-select="file"
                                                                        data-input="selectFrontViewFile"
                                                                        class="upload-file btn btn-sm btn-primary me-2">
                                                                    <i class="fas fa-cloud"></i>
                                                                    Upload Image
                                                                </button>
                                                                <input type="file" accept="image/*"
                                                                       style="display: none;"
                                                                       class="fileElem"
                                                                       id="left_view"
                                                                       name="left_view"/>
                                                            </p>
                                                            <div class="imagePreview"
                                                                 style="display: none;">
                                                                <button type="button"
                                                                        class="btn btn-xs clearImage"
                                                                        style="top: 1px;
                                            position: relative;
                                            right: 1px;
                                            float: right;
                                            padding: 2px;"><i class="fa fa-window-close" style="font-size: 20px;"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="list-inline pull-right">
                                        <li>
                                            <button type="button" class="default-btn prev-step">Previous</button>
                                        </li>

                                        <li>
                                            <button role="finish"
                                                    type="button"
                                                    class="default-btn finish">
                                                Finish
                                            </button>
                                        </li>
                                    </ul>
                                </div>


                            </div>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <x-employee-search-modal/>
    </section>
    @include('modules.vehicleManagement.partial.data_end_point')
@endsection
@push('scripts')
    <script src="{{asset('modules/userManagement/employee.search.js')}}"></script>
    <script src="{{asset('libs/imageUpload/imageUpload.js')}}"></script>
    <script>
        window.vehicleMakes = {!! json_encode($vehicleMakes) !!};
    </script>
    <script>
        (function (tmsApp, $) {


            new ImageUpload().init();


            /**
             * Called to preload all ,models data
             */
            function getConfiguredModels() {
                let url = $('#modelEndpoint').val();
                fetch(url)
                    .then(response => response.json())
                    .then(response => {
                        // Populate results
                        if (response.state === 'failure') {
                            //show errors
                            toastr.error('Connection error, no data found')
                            return;
                        }
                        window.VehicleModels = response['payload'];
                    })
                    .catch(function (error) {
                        // notify of error
                        toastr.error('Connection error. Could not retrieve data, some feature might not work.')
                    });
            }

            function populateVehicleDetails(payload) {
                document.querySelector('[name="chassisNo"]').value = payload?.bastidor;
                document.querySelector('[name="engineNo"]').value = payload?.engine_no;
                document.querySelector('[name="model_code"]').value = payload?.tipo_motor;
                // document.querySelector('[name="model_code"]').value = payload?.year_pur;
                // document.querySelector('[name="model_code"]').value = payload?.fuel_allocation;
                // document.querySelector('[name="model_code"]').value = payload?.fuel_type;
                // document.querySelector('[name="vehicleMake"]').text = payload?.marca_motor;
                document.querySelector('[name="odometer"]').value = payload?.km_rr;
                prefillDropdownList(payload?.marca_motor);
            }

            function getBodyTypes() {
                fetch(document.querySelector('#bodyTypesEndpoint').value)
                    .then(response => response.json())
                    .then(response => {
                        // Populate results
                        if (response.state === 'failure') {
                            //show errors
                            toastr.error('Connection error, no data found')
                            return;
                        }

                        app.bodyTypes = response.payload;
                    })
                    .catch(function (error) {
                        // notify of error
                        toastr.error(
                            'Connection error. Could not retrieve data, some feature might not work.')
                    });
            }

            function prefillDropdownList(marca_motor) {
                $("#vehicleMake>option").filter(function () {
                    return $(this).text()?.trim() === marca_motor?.trim();
                }).attr('selected', true);

                $("#vehicleMake").trigger('change');
            }

            function removeSubmissionAndDetailsOptions() {

            }

            function getVehicleModels(selectedValue) {

                let selectedBrandModels = window.VehicleModels.filter(function (vehicle_model) {
                    return vehicle_model.brand_code?.toString().trim() === selectedValue?.toString().trim();
                });

                while ($model.options.length > 0) {
                    $model.options[0].remove();
                }

                tmsApp.populateDropDownList($($model),
                    selectedBrandModels, 'id', ['model_name', 'model_code'], '=>')
            }

            function getDirectorates() {
                fetch(document.querySelector('#directoratesEndpoint').value)
                    .then(response => response.json())
                    .then(function (response) {
                        // Populate results
                        if (response.state === 'failure') {
                            //show errors
                            toastr.error('Connection error, no data found')
                            return;
                        }
                        tmsApp.populateDropDownList($('[name="directorate"]'),
                            response['payload'], 'id', ['name']);
                    })
                    .catch(function (error) {
                        // notify of error
                        toastr.error('Connection error',
                            'Could not retrieve Directorates data, some feature might not work.'
                        )
                    });
            }

            function getBusinessUnits() {
                fetch(document.querySelector('#businessUnitsEndpoint').value)
                    .then(response => response.json())
                    .then(response => {
                        // Populate results
                        if (response.state === 'failure') {
                            //show errors
                            toastr.error('Connection error, no data found')
                            return;
                        }

                        window.businessUnits = response['payload'];
                    })
                    .catch(function (error) {

                        toastr.error(
                            'Connection error.',
                            'Could not retrieve business units data, some feature might not work.'
                        )
                    });
            }

            function getOrganizationalUnits() {
                fetch(document.querySelector('#orgUnitsEndpoint').value)
                    .then(response => response.json())
                    .then(response => {
                        // Populate results
                        let selectElem = $('select[name="organizationalUnit"]');

                        if (response.state === 'failure') {
                            //show errors
                            toastr.error('Connection error, no data found')
                            return;
                        }

                        let userUnits = response['payload'];
                        window.organizationUnits = userUnits;
                        tmsApp.populateDropDownList(selectElem,
                            userUnits, "code_unit",
                            ['code_unit', "description"],
                            " => ");

                        let userUnitId = selectElem.attr('data-value');
                        if (userUnitId) {
                            selectElem.val(userUnitId);
                            selectElem.trigger('change');
                        }
                    })
                    .catch(function (error) {
                        // notify of error
                        console.log(error)
                        toastr.error('Connection error',
                            'Could not retrieve Organizational units data, some feature might not work.'
                        )
                    });
            }

            function userUnitChanged(user_unit) {

                if (!user_unit) return;

                let user_units = window.organizationUnits.filter(function (userUnit) {
                    return userUnit['code_unit'].trim() === user_unit?.trim();
                });

                let cost_center_code = user_units[0]?.cc_code;
                let business_unit_code = user_units[0]?.bu_code;


                let filteredCostCenters = window.costCenters.filter(function (cost_center) {
                    return cost_center['code_cost_center'].trim() === cost_center_code?.trim();
                });


                if (filteredCostCenters.length !== 0) {
                    let costCentreOfInterest = filteredCostCenters[0];
                    const costCenterDescription = costCentreOfInterest['code_cost_center']
                        + ':' + costCentreOfInterest['description'];
                    $('[name="costCenter"]').val(costCenterDescription);
                    $('[name="costCenter"]').trigger('change');
                }

                let filteredBusinessUnits = window.businessUnits.filter(function (bu) {
                    return bu.code_bu?.trim() === business_unit_code?.trim();
                });

                if (filteredBusinessUnits.length === 0) return;

                let businessUnitOfInterest = filteredBusinessUnits[0];

                const val = businessUnitOfInterest['code_bu'] + ':' + businessUnitOfInterest['description'];
                $('[name="businessUnit"]').val(val);
                $('[name="businessUnit"]').trigger('change');


                return;
            }

            function getCostCenters() {
                const $urlCtrl = document.querySelector('#costCenterEndpoint');
                if (!$urlCtrl) return;
                let url = $urlCtrl.value

                if (!url) return;

                fetch(url)
                    .then(response => response.json())
                    .then(function (response) {
                        // Populate results
                        if (response.state === 'failure') {
                            //show errors
                            toastr.error('Connection error, no data found')
                            return;
                        }

                        window.costCenters = response['payload'];
                    })
                    .catch(function (error) {
                        // notify of error
                        toastr.error('Connection error. Could not retrieve data, some feature might not work.')
                    });
            }


            setTimeout(function () {
                if (document.querySelector('#registrationNumber').value > "") {
                    document.querySelector("#vehicleSearchBtn").click();
                }
            }, 300);

            /*=============================================================*/

            $("#vehicleSearchBtn").on('click', function () {
                let registrationNumber = document.querySelector('#registrationNumber').value;
                let formData = new FormData();
                formData.append('reg_num', registrationNumber);
                tmsApp.asyncPostFormData(
                    $('#registrationNumber').attr('data-action') + '?vehicle_cleanup=true',
                    formData,
                    function (response_data) {
                        if (response_data.success === 'true' || response_data.success === true) {
                            populateVehicleDetails(response_data.payload[0]);
                        } else {
                            removeSubmissionAndDetailsOptions();
                            let $message = response_data['message'] ?
                                response_data['message']
                                :
                                ' No Vehicle Found, Check your input and try again';
                            tmsApp.systemError('Vehicle', $message);
                        }
                    },
                    function (xhr) {
                        tmsApp.systemError('System Message',
                            'We could not complete processing your request,' +
                            'please try again later');
                    }
                );
            });

            $('[role="finish"]').on('click', function () {

                let form = document.querySelector('[name="saveCleanDataForm"]');
                let formData = new FormData(form);

                tmsApp.asyncPostFormData(
                    form.action,
                    formData,
                    function (response_data) {
                        if (response_data.state === 'true' || response_data.state === true) {
                            tmsApp.showSystemMessage(
                                'Data Clean Up',
                                response_data['message'],
                                function () {
                                    window.location.reload()
                                },
                                'success');
                        } else {
                            if (response_data.hasOwnProperty('errors')) {
                                tmsApp.printErrorMsg(response_data.errors);
                                return
                            }

                            let $message = response_data['message'] ? response_data['message'] :
                                'Could Not Process Your request';
                            tmsApp.systemError('Vehicle', $message);
                        }
                    },
                    function (xhr) {
                        tmsApp.systemError('System Message',
                            'We could not complete processing your request,' +
                            ' please try again later');
                    }
                );
            });

            const $make = document.getElementById("vehicleMake");

            tmsApp.populateDropDownList($($make),
                window.vehicleMakes, 'id', ['name']);

            const $model = document.getElementById("modelNo");

            $('#vehicleMake').on("change", function (e) {
                $model.removeAttribute("disabled")
                const ele = this;
                const makes = window['vehicleMakes'].filter(function (brand) {
                    return parseInt($(ele).val()) === parseInt(brand.id);
                });

                if (makes.length === 0) {
                    return;
                }

                let selectedMake = makes[0];

                getVehicleModels(selectedMake?.id);
            });

            $('#modelNo').on("change", function (e) {
                let id = $(this).val();
                let models = window.VehicleModels

                models.forEach((function (item) {
                    if (parseInt(id) === item.id) {
                        $('[name="model_code"]').val(item.model_code);
                    }
                }));
            });

            $(document).on("change", '[name="organizationalUnit"]', function (e) {
                const user_unit = $(this).val();
                userUnitChanged(user_unit);
            });

            const dummyOptions = ["One", "Two", "Three"]

            const optionCreation = (options, name) => {

                const optionElement = document.getElementById(name)

                options.forEach((item) => {
                    const option = document.createElement("option")
                    option.value = item
                    option.textContent = item;
                    optionElement.appendChild(option)
                })
            }

            optionCreation(dummyOptions, "businessUnit");
            optionCreation(dummyOptions, "costCenter");
            optionCreation(dummyOptions, "directorate");


            getConfiguredModels();
            getDirectorates();
            getBusinessUnits();
            getOrganizationalUnits();
            getCostCenters();

            const $isPoolVehicle = document.getElementById('[name="isPoolVehicle"]')

            const responsibleUserName = document.getElementById("responsibleUserName")
            const responsibleUserNumber = document.getElementById("responsibleUserNumber")
            const operatorName = document.getElementById("operatorName")
            const operatorNumber = document.getElementById("operatorNumber")
            const supervisorName = document.getElementById("supervisorName")
            const supervisorNumber = document.getElementById("supervisorNumber")

            const assignedToName = document.getElementById("assignedToName")
            const assignedToNumber = document.getElementById("assignedToNumber")


            $($isPoolVehicle).on("change", function () {
                if ($(this).val() === 'YES') {
                    responsibleUserName.style.display = "block"
                    responsibleUserNumber.style.display = "block"
                    operatorName.style.display = "block"
                    operatorNumber.style.display = "block"
                    supervisorName.style.display = "block"
                    supervisorNumber.style.display = "block"
                } else {
                    responsibleUserName.style.display = "none"
                    responsibleUserNumber.style.display = "none"
                    operatorName.style.display = "none"
                    operatorNumber.style.display = "none"
                    supervisorName.style.display = "none"
                    supervisorNumber.style.display = "none"

                    assignedToName.style.display = "block"
                    assignedToNumber.style.display = "block"
                }
            })


            'use strict';
            let currentIndex = 0;
            let numSteps = 0;
            let tryValid;


            $(document).ready(function () {
                initFormWizard()
            });

            function initFormWizard() {
                const stepsList = document.getElementsByClassName("steps")[0];
                numSteps = stepsList.children.length;

                $('.nav-tabs > li a[title]').tooltip();

                //Wizard
                $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

                    var target = $(e.target);

                    if (target.parent().hasClass('disabled')) {
                        return false;
                    }
                });

                $(".next-step").on('click', function (e) {
                    let active = $('.wizard .nav-tabs li.active');
                    let indexOfActiveElement = 0;

                    $.each(stepsList.children, function (index, li) {
                        if ($(li).hasClass('active')) {
                            indexOfActiveElement = index;
                        }
                    })

                    console.log('Current tab ', indexOfActiveElement);
                    currentIndex = indexOfActiveElement + 1;

                    if (indexOfActiveElement) {
                    }

                    active.next().removeClass('disabled');
                    active.addClass('done');
                    active.removeClass('active');
                    nextTab(active);
                });
                $(".prev-step").click(function (e) {

                    var active = $('.wizard .nav-tabs li.active');
                    prevTab(active);

                });
            }


            function nextTab(elem) {
                let isValid = false;
                if (true) {
                    $(elem).next().find('a[data-toggle="tab"]').click();
                }


                if (currentIndex == numSteps - 1) {
                    $('.skip-btn').addClass('d-none')
                } else {
                    $('.skip-btn').removeClass('d-none')
                }


            }

            function prevTab(elem) {
                $(elem).prev().find('a[data-toggle="tab"]').click();
                const stepsList = document.getElementsByClassName("steps")[0];
                let numSteps = stepsList.children.length;

                if (currentIndex > 0 || currentIndex < numSteps - 1) {
                    $('.skip-btn').removeClass('d-none')
                }
            }

            $('.nav-tabs').on('click', 'li', function () {
                $('.nav-tabs li.active').removeClass('active');
                $(this).addClass('active');
            });


            function validateStep(stepIndex) {
                let isValid;
                // const requiredValue = document.querySelectorAll("input, textarea")
                const steps = document.querySelectorAll(".step")


                return isValid
            }

            validateStep(1)

            //////// Try Functions ///////////

            function doSomething() {
                tryValid = validateStep(1)
            }
        })(window.tmsApp || {}, jQuery);

    </script>
@endpush

