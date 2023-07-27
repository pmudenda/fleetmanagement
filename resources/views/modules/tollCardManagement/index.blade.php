@php use Carbon\Carbon; @endphp
@extends('layouts.app')


@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <style>
        label {
            display: flex;
            align-items: center;
        }

        span::after {
            padding-left: 5px;
        }

        input:invalid + span::after {
            content: "✖";
        }

        input:valid + span::after {
            content: "✓";
        }
    </style>
@endpush

@section('content')

    <x-content-header :pageTitle="'e-Toll Card On Boarding'" :activeCrumb="'OnBoarding'" :link="'e-toll.card'"
                      :linkText="'e-Toll Card'"/>

    <!-- Main content -->
    <section class="content">
        <x-error-view/>
        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <div class="col-md-12 pl-0">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h4>e-Toll Card Management</h4>
                            </div>
                            <div id="actionButtonsContainer" class="card-toolbar justify-content-end">
                                <button type="button" id="submitRequisitionBtn"
                                        class="btn btn-success btn-sm mr-3 when_odo_valid"
                                        disabled>
                                    <i class="fas fa-save"></i> Submit
                                </button>
                                <button type="button" id="resetRequisitionBtn" class="btn btn-danger btn-sm mr-3">
                                    <i class="fas fa-undo"></i> Cancel
                                </button>

                            </div>
                        </div>
                        <div class="card-body p-2">
                            <div style="display: none;" class="table-responsive mt-10 ">

                                <div class="card-body py-4 min-h-600px pt-0">
                                    <label class="app-required-marker"></label>
                                    <x-error-view/>
                                    <form name="tms_driver_definition"
                                          action="{{route('save.driver')}}"
                                          id="tms_driver_definition" method="post">
                                        @csrf
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6 col-md-5">
                                                    <div class="container-fluid pl-0">
                                                        <div class="row">
                                                            <div class="form-group row">
                                                                <label
                                                                    class="pl-0 col-xs-12 col-sm-6 col-md-5 col-lg-4 app-field-label"
                                                                    for="staff_no">Find By:
                                                                </label>
                                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                    <div class="input-group">
                                                                        <input type="search"
                                                                               data-action="{{route('user.search')}}"
                                                                               class="form-control form-control-sm"
                                                                               id="staff_number"
                                                                               placeholder="Enter staff number"
                                                                               name="staff_number" required/>
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
                                            <div class="container-fluid mt-5">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="card-title pl-2">
                                                                <h4>Employee Details</h4>
                                                                <hr/>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xs-12 col-sm-6 col-md-5">
                                                                <div class="container-fluid pl-0">
                                                                    <div class="row">
                                                                        <div class="form-group row">
                                                                            <label
                                                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                                for="staff_name">
                                                                                Staff Number:
                                                                            </label>
                                                                            <div
                                                                                class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                                <input type="text"
                                                                                       class="form-control form-control-sm"
                                                                                       id="employee_number"
                                                                                       name="employee_number"
                                                                                       required readonly/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-5">
                                                                <div class="container-fluid pl-0">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-xs-12 col-sm-6 col-md-5">
                                                                <div class="container-fluid pl-0">
                                                                    <div class="row">
                                                                        <div class="form-group row">
                                                                            <label
                                                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                                for="staff_name">
                                                                                Name:
                                                                            </label>
                                                                            <div
                                                                                class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                                <input type="text"
                                                                                       class="form-control form-control-sm"
                                                                                       id="driver_name"
                                                                                       name="driver_name"
                                                                                       required readonly/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-5">
                                                                <div class="container-fluid pl-0">
                                                                    <div class="row" style="display: none;">
                                                                        <div class="form-group row">
                                                                            <label
                                                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                                for="mobile_no">Grade:</label>
                                                                            <div
                                                                                class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                                <input type="text"
                                                                                       class="form-control form-control-sm"
                                                                                       id="grade"
                                                                                       readonly name="grade"
                                                                                       autocomplete="off"/>
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
                                                                            <label
                                                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                                for="staff_name">
                                                                                Position:
                                                                            </label>
                                                                            <div
                                                                                class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                                <input type="text"
                                                                                       class="form-control form-control-sm"
                                                                                       id="job_title"
                                                                                       name="job_title"
                                                                                       required
                                                                                       readonly>
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
                                                                            <label
                                                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                                for="department">
                                                                                Department :
                                                                            </label>
                                                                            <div
                                                                                class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                                <input type="text"
                                                                                       class="form-control form-control-sm"
                                                                                       id="department"
                                                                                       name="department"
                                                                                       readonly
                                                                                       required>
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
                                                                            <label
                                                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                                for="staff_name">
                                                                                Location:
                                                                            </label>
                                                                            <div
                                                                                class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                                <input type="hidden"
                                                                                       name="cost_center_code">
                                                                                <input type="hidden" name="nrc">

                                                                                <input type="text"
                                                                                       class="form-control form-control-sm"
                                                                                       id="location"
                                                                                       name="location" required
                                                                                       readonly/>
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
                                                                            <label
                                                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                                for="staff_name">
                                                                                Is Driver by designation ?:
                                                                            </label>
                                                                            <div
                                                                                class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                                <label class="inline-check">
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input
                                                                                            id="designated-driver-yes"
                                                                                            type="radio"
                                                                                            name="isDesignatedDriver"
                                                                                            value="yes" disabled/>
                                                                                        <label
                                                                                            for="designated-driver-yes">Yes</label>
                                                                                    </div>
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input id="designated-driver-no"
                                                                                               type="radio"
                                                                                               checked
                                                                                               name="isDesignatedDriver"
                                                                                               value="no" disabled/>
                                                                                        <label
                                                                                            for="designated-driver-no">No</label>
                                                                                    </div>
                                                                                </label>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="card-title pl-2">
                                                                <h4>License Details</h4>
                                                                <hr/>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-xs-12 col-sm-6 col-md-5">
                                                                <div class="container-fluid pl-0">
                                                                    <div class="row">
                                                                        <div class="form-group row">
                                                                            <label
                                                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                                for="staff_license">
                                                                                License No:
                                                                            </label>
                                                                            <div
                                                                                class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                                <input type="text"
                                                                                       class="form-control form-control-sm"
                                                                                       id="license_number"
                                                                                       name="license_number"
                                                                                       required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-5">
                                                                <div class="container-fluid pl-0">
                                                                    <div class="row">
                                                                        <div class="form-group row">
                                                                            <label
                                                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                                for="staff_name">
                                                                                Date Issued:
                                                                            </label>
                                                                            <div
                                                                                class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                                <input type="date"
                                                                                       max="{{ date('Y-m-d', strtotime(Carbon::now())) }}"
                                                                                       class="form-control form-control-sm"
                                                                                       id="license_date_issued"
                                                                                       name="license_date_issued"
                                                                                       required>
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
                                                                            <label
                                                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                                for="staff_name">
                                                                                Expiry Date:
                                                                            </label>
                                                                            <div
                                                                                class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                                <input type="date"
                                                                                       {{--min="{{ date('Y-m-d', strtotime(Carbon::now())) }}"--}}
                                                                                       class="form-control form-control-sm"
                                                                                       id="license_date_expiry"
                                                                                       name="license_date_expiry"
                                                                                       required/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-5">
                                                                <div class="container-fluid pl-0">
                                                                    <div class="row">
                                                                        <div class="form-group row">
                                                                            <label
                                                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                                for="staff_name">
                                                                                License Category:
                                                                            </label>
                                                                            <div
                                                                                class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                                <select
                                                                                    id="license_class"
                                                                                    name="license_class"
                                                                                    class="form-select">
                                                                                    {{-- @foreach($licenseClasses as $licenseClass)
                                                                                         <option
                                                                                             value="{{$licenseClass->code}}">{{$licenseClass->name}}</option>
                                                                                     @endforeach--}}
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
                                                                            <label
                                                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4"
                                                                                for="staff_name">
                                                                                Copy Of License:
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-5">
                                                                <div class="container-fluid pl-0">
                                                                    <div class="row">
                                                                        <div class="form-group row">
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
                                                                            <label
                                                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                                for="staff_name">
                                                                                Front View:
                                                                                <small class="text-danger">
                                                                                    JPG, JPEG,PNG, BMP
                                                                                </small>
                                                                            </label>
                                                                            <div
                                                                                class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                                <div
                                                                                    class="card text-center py-5 my-2 pt-0">
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
                                                                                            <input type="file"
                                                                                                   accept="image/*"
                                                                                                   style="display: none;"
                                                                                                   class="fileElem"
                                                                                                   id="license_front_view"
                                                                                                   name="license_front_view"/>
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
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-5">
                                                                <div class="container-fluid pl-0">
                                                                    <div class="row">
                                                                        <div class="form-group row">
                                                                            <label
                                                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                                for="staff_name">
                                                                                Back View:
                                                                                <small class="text-danger">
                                                                                    JPG, JPEG,PNG, BMP
                                                                                </small>
                                                                            </label>
                                                                            <div
                                                                                class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                                <div
                                                                                    class="card text-center py-5 my-2 pt-0">
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
                                                                                            <input type="file"
                                                                                                   accept="image/*"
                                                                                                   style="display: none;"
                                                                                                   class="fileElem"
                                                                                                   id="license_back_view"
                                                                                                   name="license_back_view"/>
                                                                                        </p>
                                                                                        <div class="imagePreview"
                                                                                             style="display: none;">
                                                                                            <button type="button"
                                                                                                    class="btn btn-xs clearImage"
                                                                                                    style="top: 1px;
                                            position: relative;
                                            right: 1px;
                                            float: right;
                                            padding: 2px;">
                                                                                                <i class="fa fa-window-close"
                                                                                                   style="font-size: 20px;"></i>
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
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{--<div class="card-body user-data pl-0 pt-0">
                                            <div class="container-fluid mt-5">
                                            </div>
                                        </div>
                                        --}}
                                        <div class="row">
                                            <div class="card-title pl-2">
                                                <h4>Permit Details</h4>
                                                <hr/>
                                            </div>
                                        </div>
                                        <div class="card-body user-data pl-0 pt-0">

                                            <div class="container-fluid mt-5">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-6 col-md-5">
                                                        <div class="container-fluid pl-0">
                                                            <div class="row">
                                                                <div class="form-group row">
                                                                    <label
                                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                        for="staff_license">
                                                                        Permit No:
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="text"
                                                                               readonly
                                                                               class="form-control form-control-sm"
                                                                               id="permit_number"
                                                                               name="permit_number"
                                                                               required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-6 col-md-5">
                                                        <div class="container-fluid pl-0">
                                                            <div class="row">
                                                                <div class="form-group row">
                                                                    <label
                                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                        for="staff_name">
                                                                        Date Issued:
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="date"
                                                                               max="{{ date('Y-m-d', strtotime(Carbon::now())) }}"
                                                                               class="form-control form-control-sm"
                                                                               id="permit_date_issued"
                                                                               name="permit_date_issued"
                                                                               required>
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
                                                                    <label
                                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                        for="staff_name">
                                                                        Expiry Date:
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="date"
                                                                               min="{{ date('Y-m-d', strtotime(Carbon::now())) }}"
                                                                               class="form-control form-control-sm"
                                                                               id="permit_date_expiry"
                                                                               name="permit_date_expiry"
                                                                               required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-6 col-md-5"></div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-6 col-md-5">
                                                        <div class="container-fluid pl-0">
                                                            <div class="row">
                                                                <div class="form-group row">
                                                                    <label
                                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                        for="staff_name">
                                                                        Copy Of Permit:
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="file"
                                                                               accept="image/*,.pdf"
                                                                               class="form-control form-control-sm"
                                                                               id="permit_copy"
                                                                               name="permit_copy"
                                                                               required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <form class="" name="newTaskForm" action="{{route('save.toll.card')}}" id="newTaskForm"
                                  method="post">
                                @csrf
                                <input type="hidden" name="relatedReference" id="relatedReference"
                                       value="{{$relatedReference ?? ''}}"/>
                                <div class="errorTxt"></div>
                                <x-error-view></x-error-view>

                                <label class="app-required-marker"></label>

                                <fieldset style="" class="form-group border p-3">
                                    <legend>General Information:</legend>
                                    <table class="app_form_table table">
                                        <tr>
                                            <td>
                                                <label class="app-field-label" data-field="taskOriginator">
                                                    NRFA Batch Number <span class="text-danger">*</span>
                                                </label>
                                            </td>
                                            <td>
                                                    <span class="app-field-input" data-field="taskOriginator">
                                                        <div class="input-group">
                                                            <input type="text"
                                                                   id="batchNumber"
                                                                   required
                                                                   autocomplete="off"
                                                                   name="batchNumber"
                                                                   class="form-control"/>
                                                            <div class="input-group-append">
                                                                <button type="button" data-assignmenttype="single"
                                                                        data-inputfield="taskOriginator"
                                                                        data-field="userSelection"
                                                                        class="input-group-text">
                                                                    <i class="fa fc-day-number"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </span>
                                            </td>
                                            <td class="pl-5">
                                                <label class="field-required app-field-label-">
                                                    Scheme
                                                </label>
                                            </td>
                                            <td>
                                                <span class="app-field-input" data-field="dateoriginated">
                                                    <div class="input-group">
                                                        <select
                                                            name="cardScheme"
                                                            class="form-select">
                                                            <option value="ST">Standard</option>
                                                        </select>
                                                    </div>
                                                </span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <label class="app-field-label" data-field="specificlocationofia">
                                                    Card Number <span class="text-danger">*</span>
                                                </label>
                                            </td>
                                            <td>
                                                <div class="input-group date"
                                                     id="date_opened"
                                                     data-target-input="nearest">
                                                    <input type="text" name="dateOpened" required id="dateOpened"
                                                           autocomplete="off"
                                                           class="form-control datetimepicker-opened"
                                                           data-target="#dateOpened"/>
                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <label class="hq-field field-required" data-field="">
                                                    Card Status
                                                </label>
                                            </td>
                                            <td>
                                                <span class="app-field-input" data-field="">
                                                     <div class="input-group">
                                                        <select id="cardStatus"
                                                                name="cardStatus"
                                                                required
                                                                class="form-select form-select-sm">
                                                            <option disabled value=""></option>
                                                            <option value="01">NEW</option>
                                                            <option value="02">ASSIGNED</option>
                                                        </select>
                                                    </div>
                                                </span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <label class="app-field-label" data-field="specificlocationofia">
                                                    Date Issued <span class="text-danger">*</span>
                                                </label>
                                            </td>
                                            <td>
                                                <div class="input-group date"
                                                     data-target-input="nearest">
                                                    <input type="text" name="dateIssued" required id="dateIssued"
                                                           autocomplete="off"
                                                           class="form-control datetimepicker-input"/>
                                                    <div class="input-group-append"
                                                         data-target="#dateIssued"
                                                         data-toggle="datetimepicker">
                                                        <span type="button" data-action="datetimepicker"
                                                              class="input-group-text ui-datepicker-trigger">
                                                            <i data-action="datetimepicker" class="fa fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                    <button type="button" data-action="clearDate"
                                                            class="input-group-text">
                                                        <i data-action="clearDate" class="fa fa-eraser"></i>
                                                    </button>
                                                </div>
                                            </td>

                                            <td style="background: none;">
                                                <label class="hq-field">
                                                </label>
                                            </td>
                                            <td>
                                                <span class="hq-field">
                                                </span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <label class="app-field-label" data-field="specificlocationofia">
                                                    Expiry Date <span class="text-danger">*</span>
                                                </label>
                                            </td>

                                            <td>
                                                <div class="input-group date" id="date_opened"
                                                     data-target-input="nearest">
                                                    <input type="text" name="dateOpened" required id="dateOpened"
                                                           autocomplete="off"
                                                           class="form-control datetimepicker-opened"
                                                           data-target="#dateOpened"/>
                                                    <div class="input-group-append" data-target="#dateOpened"
                                                         data-action="dateOpenedPicker">
                                                        <span type="button" data-action="dateOpenedPicker"
                                                              class="input-group-text">
                                                            <i data-action="dateOpenedPicker"
                                                               class="fa fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                    <button type="button" data-action="clearDate"
                                                            class="input-group-text">
                                                        <i data-action="clearDate" class="fa fa-eraser"></i>
                                                    </button>
                                                </div>
                                            </td>

                                            <td style="background: none;">
                                                <label class="hq-field-" data-field="">
                                                </label>
                                            </td>

                                            <td>
                                                <span class="hq-field-" data-field="">
                                                </span>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td>
                                                <label class="app-field-label" data-field="specificlocationofia">
                                                    Card Verification Value (<small>CVV</small>)
                                                    <span class="text-danger">*</span>
                                                </label>
                                            </td>
                                            <td>
                                                <div class="input-group"
                                                     id="date_opened"
                                                     data-target-input="nearest">
                                                    <input type="text" name="cvv" required
                                                           id="cvv"
                                                           autocomplete="off"
                                                           class="form-control"
                                                           data-target="#dateOpened"/>
                                                    <div class="input-group-append">
                                                        <span type="button" data-action="datetimepicker"
                                                              class="input-group-text ui-datepicker-trigger">
                                                            <i data-action="datetimepicker" class="fa fa-lock"></i>
                                                        </span>
                                                    </div>

                                                </div>
                                            </td>
                                            <td class="pl-5">
                                                <label class="hq-field field-required" data-field="">
                                                    Mobile
                                                </label>
                                            </td>
                                            <td>
                                                <span class="app-field-input" data-field="">
                                                     <div class="input-group">
                                                        <input type="tel" id="contactNumber"
                                                               name="contactNumber"
                                                               required
                                                               class="form-control form-control-sm"/>
                                                         <div class="input-group-append">
                                                        <span type="button" data-action="datetimepicker"
                                                              class="input-group-text ui-datepicker-trigger">
                                                            <i data-action="datetimepicker" class="fa fa-phone"></i>
                                                        </span>
                                                    </div>
                                                    </div>
                                                </span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <label class="field-required app-field-label-">
                                                    Assigned To
                                                </label>
                                            </td>
                                            <td>
                                                <span class="app-field-input" data-field="dateoriginated">
                                                    <div class="input-group">
                                                        <select
                                                            name="cardScheme"
                                                            class="form-select">
                                                            <option value="ND">NORTHERN DIVISION</option>
                                                            <option value="LR">LUSAKA</option>
                                                        </select>
                                                    </div>
                                                </span>
                                            </td>
                                        </tr>

                                        <td>
                                            <label class="app-field-label" data-field="taskOriginator">
                                                Responsible Officer <span class="text-danger">*</span>
                                            </label>
                                        </td>
                                        <td>
                                            <span class="app-field-input" data-field="taskOriginator">
                                                <div class="input-group">
                                                    <input type="text"
                                                           id="taskOriginator"
                                                           required
                                                           data-bs-toggle="modal"
                                                           autocomplete="off"
                                                           data-bs-target="#searchEmployeeModal"
                                                           data-assignmenttype="single"
                                                           data-inputfield="taskOriginator"
                                                           name="taskOriginator"
                                                           class="form-control"/>
                                                    <input type="hidden"
                                                           data-assignmenttype="single"
                                                           data-inputfield="taskOriginatorId"
                                                           id="taskOriginatorId"
                                                           name="taskOriginatorId"/>
                                                    <div class="input-group-append">
                                                        <button type="button" data-assignmenttype="single"
                                                                data-inputfield="taskOriginator"
                                                                data-field="userSelection"
                                                                class="input-group-text">
                                                            <i class="fa fa-user"></i>
                                                        </button>
                                                        <button type="button" data-action="clearUsers"
                                                                class="input-group-text">
                                                            <i class="fa fa-eraser"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </span>
                                        </td>

                                        <tr>
                                            <td colspan="4">
                                                <label class="app-field-label" data-field="typeia">
                                                    Comments
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background: none;" colspan="4">
                                                <span class="app-field-input">
                                                    <textarea name="comments" id="comments"
                                                              class="form-control"></textarea>
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </fieldset>

                                <!--Attachments FieldsSets-->

                                <fieldset style="margin-top:30px;" class="form-group border p-3">
                                    <legend>Attachments:</legend>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                        <span class="app-field-input" data-field="iaclassification">
                                        <input type="file" id="supportingDocument" name="supportingDocument"
                                               class="form-control"/>
                        </span>
                                        </div>
                                        <div class="col-xs-12 col-sm-3">
                                            <div class="row pl-2 d-none">
                            <span class="app-field-input" data-field="iaclassification">
                                        <button type="button" id="btn_link" name="btnExternalLink"
                                                class="btn btn-secondary toolbarButtonClick">
                                             External Link <i class="fa fa-paperclip"></i>
                                        </button>
                                        <input type="hidden" name="externalLink" class="form-control"/>
                                        <input type="hidden" name="externalLinkDescription" class="form-control"/>
                            </span>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-3">
                                            <div class="row pl-2 d-none">
                           <span class="app-field-input" data-field="associateRecord">
                                <button type="button"
                                        data-bs-toggle="modal"
                                        data-="" id="btn_reference"
                                        class="btn btn-secondary toolbarButtonClick">
                                  Associate Record  <i class="fa fa-history"></i>
                                </button>
                               <input type="hidden" name="internalLink" class="form-control"/>
                           </span>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <input type="hidden" value="{{route('license.details.verification')}}" id="rtsaLicenseVerificationEndPoint">
@endsection

@push('scripts')

    <!-- DataTables  & Plugins -->
    @include('layouts.partials.dataTableScripts')
    <script src="{{asset('libs/imageUpload/imageUpload.js')}}"></script>
    <!-- page script -->
    <script>
        (function (tmsApp, $) {
            /* function ImageUpload() {
                 const selector = '.fileElem';

                 this.init = function () {
                     $(document).on('click', '[data-select="file"]', function () {
                         let fileInput = $(this).closest('p').find('input[type="file"]');
                         $(fileInput).trigger('click');
                     });

                     let fileSelects = [].slice.call(document.querySelectorAll(selector));
                     fileSelects.map(function (fileSelect) {
                         fileSelect.addEventListener("change",
                             (e) => {
                                 preview(e);
                             },
                             false);
                     });

                     function preview(event) {
                         //$('#frame').src = URL.createObjectURL(event.target.files[0]);
                         let uploadFile = $(event.target);
                         let self = event.target;
                         let files = !!self.files ? self.files : [];
                         if (!files.length || !window.FileReader) return;
                         // no file selected, or no FileReader support

                         if (/^image/.test(files[0].type)) {
                             // only image file
                             let reader = new FileReader();
                             // instance of the FileReader
                             reader.readAsDataURL(files[0]);
                             // read the local file

                             reader.onloadend = function () {
                                 // set image data as background of div
                                 uploadFile.closest("div").find('.imagePreview').css({
                                     "background-image": "url(" + this.result + ")", 'display': 'block'
                                 });
                             }

                             $(uploadFile).closest('div').find('p').addClass('d-none');
                         } else {
                             toastr.error('only image (.jpg, .jpeg, .png, .bmp) file types are allowed', 'Invalid File Format Selected')
                         }
                     }

                     $(document).on('click', '.clearImage', function (event) {
                         let btn = this;
                         Swal.fire({
                             text: "Are you sure you would like to remove the image?",
                             icon: "warning",
                             showCancelButton: true,
                             buttonsStyling: false,
                             confirmButtonText: "Yes, remove it!",
                             cancelButtonText: "No, return",
                             customClass: {
                                 confirmButton: "btn btn-primary", cancelButton: "btn btn-active-light"
                             }
                         }).then(function (result) {
                             if (result.value) {
                                 $(btn).parent().css({
                                     "background-image": "", 'display': 'none'
                                 });
                                 // find the upload btn and make visible
                                 $(btn).parent().parent().find('p').removeClass('d-none');
                             }
                         });

                     });
                 }
             }*/

            function verifyingDriverLicense() {

                setTimeout(function () {
                    tmsApp.asyncPostJson(
                        document.querySelector("#rtsaLicenseVerificationEndPoint").value,
                        {
                            licenseNumber: $('[name="license_number"]').val(),
                        },
                        function (response) {
                            window.loaderMessage = "Please wait...";
                            if (!response.success) {
                                toastr.error(response.message);
                                return;
                            }

                            toastr.success(response.message);
                        }, function (xhr, settings, error) {
                            window.loaderMessage = "Please wait...";
                            tmsApp.showErrorMessages(xhr, 'License Verification');
                        }
                    )
                }, 1000);
            }

            function populateEmployeeDetails(response) {
                let data = response;

                if (!data.con_per_no) {
                    tmsApp.showToast('User Not Found', 'error')
                    return;
                }
                document.querySelector('[name="driver_name"]').value = data?.name;
                document.querySelector('[name="grade"]').value = data?.grade;
                document.querySelector('[name="job_title"]').value = data?.job_title;
                document.querySelector('[name="location"]').value = data?.location;
                document.querySelector('[name="department"]').value = data?.functional_section;
                document.querySelector('[name="employee_number"]').value = data?.con_per_no;

                //document.querySelector('[name="staff_email"]').value = data?.staff_email;
                //document.querySelector('[name="cc_code"]').value = data?.cc_code;
                //document.querySelector('[name="bu_code"]').value = data?.bu_code;
                //document.querySelector('[name="cost_center_code"]').value = data?.cc_code;
                //document.querySelector('[name="business_unit_code"]').value = data?.bu_code;
                //document.querySelector('[name="login_name"]').value = data?.con_per_no;
                //document.querySelector('[name="directorate"]').value = data?.directorate;
                //document.querySelector('[name="mobile_no"]').value = data?.mobile_no;

                document.querySelector('[name="nrc"]').value = data?.nrc;
                document.querySelector('#actionButtonsContainer').style.display = null;

                if (data?.job_title.toLowerCase().indexOf("driver") > -1) {
                    document.querySelector('#designated-driver-yes').checked = true;
                    document.querySelector('#designated-driver-yes').removeAttribute('disabled');
                } else {
                    document.querySelector('#designated-driver-no').checked = true;
                    document.querySelector('#designated-driver-no').removeAttribute('disabled');
                }
            }

            function findEmployee() {
                const staff_number = document.querySelector('#staff_number').value
                let formData = new FormData();
                formData.append('searchCriteria', staff_number);

                fetch(
                    document.querySelector("#staff_number").getAttribute('data-action'),
                    {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: formData,
                        referrer: window.baseUrl,
                        mode: 'cors',
                        credentials: 'same-origin',
                    }
                )
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }

                        return response.json();
                    })
                    .then(response => {
                        console.log(response);

                        if (!response.success) {
                            toastr.error(response.message);
                            return;
                        }

                        document.querySelector("#submitRequisitionBtn").removeAttribute('disabled');

                        let optionListStr = '';
                        if (Array.isArray(response.payload)) {
                            response.payload.forEach(function (item) {
                                optionListStr += `<option value="${item['con_per_no']}">${item['con_per_no']} =>${item.name}</option>`;
                            })

                            $('#employee_list').html(optionListStr);
                            return;
                        }

                        populateEmployeeDetails(response.payload);
                    })
                    .catch(function (xhr, settings, error) {
                        tmsApp.showErrorMessages(xhr, 'Driver Onboarding');
                    });
            }

            new ImageUpload().init();
            Inputmask({
                "mask": "99999999"
            }).mask("#permit_number");

            Inputmask({
                "mask": "99999999"
            }).mask("#license_number");

            function addYears(date, years) {
                date.setFullYear(date.getFullYear() + years);
                return date;
            }

            function reformatDate(date, format) {

                let data = '';
                if (format === 'ISO') {
                    let datePart = new Intl.DateTimeFormat('en-GB').format(date);// .toDateString().split(' ')[0];
                    console.log(datePart);
                    let dateParts = datePart.split('/');
                    data = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
                }

                return data

            }

            $('[name="license_date_issued"]').on('change', function () {
                let date = new Date(this.value);
                // reformatDate(date, "ISO")
                document.querySelector('[name="license_date_expiry"]').setAttribute('min', this.value);
                let expiryDate = addYears(date, 5);
                document.querySelector('[name="license_date_expiry"]').setAttribute('max', reformatDate(expiryDate, "ISO"));

            });

            $('[name="license_date_expiry"]').on('change', function () {
                //let date = new Date(this.value);
                //document.querySelector('[name="permit_date_expiry"]').value = reformatDate(date, "ISO");
            });

            $('#license_number').on('keyup paste enter', function () {
                if (!this.value || this.value.replaceAll("_", '').length < 8) {
                    return;
                }

                window.loaderMessage = "Verifying License Number with RTSA, Please wait";

                $('#license_number').val(this.value);

                setTimeout(function () {
                    verifyingDriverLicense();
                }, 300);
            });

            $('#staff_number').on('keyup paste enter change', function () {
                if (!this.value || this.value.length < 5) {
                    return;
                }
                setTimeout(function () {
                    findEmployee();
                }, 300);
            });

            $('#employeeSearchBtn').on('click', function () {
                const staff_number = document.querySelector('#staff_number').value
                if (!staff_number || staff_number.length < 5) {
                    toastr.warning('Invalid Staff Number Provided')
                    return;
                }
                setTimeout(function () {
                    findEmployee();
                }, 300);
            });


            $("#submitRequisitionBtn").on('click', function () {
                let $form = document.forms['tms_driver_definition'];
                if (!$($form).valid()) {
                    return;
                }

                $('.print-error-msg').css('display', 'none');
                let formData = new FormData($form);
                tmsApp.confirm(
                    'Driver onboarding',
                    'Are you sure you want to onboard this driver ?',
                    'Yes',
                    'No',
                    function () {
                        window.top.tmsApp.asyncPostFormData(
                            $form.action,
                            formData,
                            function (asyncResponse) {

                                if (asyncResponse.hasOwnProperty('success') && asyncResponse['success']) {
                                    setTimeout(function () {
                                        tmsApp.showSystemMessage(
                                            'Driver onboarding',
                                            asyncResponse['message'],
                                            function () {
                                                window.location.href = asyncResponse["redirectUrl"]
                                            },
                                            'success'
                                        );
                                    }, 300);
                                } else {
                                    if (asyncResponse.hasOwnProperty('errors')) {
                                        tmsApp.printErrorMsg(asyncResponse.errors);
                                        return
                                    }
                                    setTimeout(function () {
                                        tmsApp.systemError(
                                            'Driver onboarding',
                                            asyncResponse['message'],
                                            function () {
                                            }, 'error');
                                    }, 300);
                                }
                            },
                            function (xhr, settings, errorThrown) {
                                console.log(errorThrown)
                                setTimeout(function () {
                                    if ('responseJSON' in xhr) {
                                        if (xhr.responseJSON.hasOwnProperty('errors')) {
                                            tmsApp.printErrorMsg(xhr.responseJSON.errors);
                                        }
                                        if (xhr.responseJSON.hasOwnProperty('message')) {
                                            tmsApp.systemError(
                                                'Driver onboarding',
                                                xhr.responseJSON['message']
                                            );
                                        }
                                        return;
                                    }

                                    tmsApp.systemError(
                                        'Driver onboarding',
                                        'We could not complete processing your request, please try again later');
                                }, 300)
                            }
                        )
                    },
                    function () {
                    }
                );
            });

            tmsApp.appFormValidator('form[name="tms_driver_definition"]',
                {},
                {}
            );

            $(".datetimepicker-input").datepicker({
                minDate: new Date(),
                dateFormat: 'dd/mm/yy',
            });


        })(window.tmsApp, jQuery);
    </script>

@endpush
