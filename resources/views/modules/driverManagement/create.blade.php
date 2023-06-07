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

    <x-content-header :pageTitle="'Driver On Boarding'" :activeCrumb="'OnBoarding'" :link="'home'"
                      :linkText="'System Users'"/>

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
                                <h4>Driver Management</h4>
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
                            <div class="table-responsive mt-10 ">

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
                                            <div class="row">
                                                <div class="card-title pl-2">
                                                    <h4>Employee Details</h4>
                                                    <hr/>
                                                </div>
                                            </div>
                                            <div class="container-fluid mt-5">
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
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
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
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
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
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
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
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
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
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
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
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="hidden" name="cost_center_code">
                                                                        <input type="hidden" name="nrc">

                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="location"
                                                                               name="location" required readonly/>
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
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <label class="inline-check">
                                                                            <div class="form-check form-check-inline">
                                                                                <input id="designated-driver-yes"
                                                                                       type="radio"
                                                                                       name="isDesignatedDriver"
                                                                                       value="yes" disabled/>
                                                                                <label
                                                                                    for="designated-driver-yes">Yes</label>
                                                                            </div>
                                                                            <div class="form-check form-check-inline">
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
                                        </div>

                                        <div class="row">
                                            <div class="card-title pl-2">
                                                <h4>License Details</h4>
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
                                                                        License No:
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="license_number" name="license_number"
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
                                                                               id="license_date_issued"
                                                                               name="license_date_issued" required>
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
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                        <select
                                                                            id="license_class"
                                                                            name="license_class"
                                                                            class="form-select">
                                                                            @foreach($licenseClasses as $licenseClass)
                                                                                <option
                                                                                    value="{{$licenseClass->code}}">{{$licenseClass->name}}</option>
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
    <!-- page script -->
    <script>
        (function (tmsApp, $) {
            function ImageUpload() {
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
            }

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
                //reformatDate(date, "ISO")
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
                {
                    employee_number: {
                        required: true,
                        maxlength: 10,
                        minlength: 5
                    },
                    name: {
                        required: true
                    },
                    grade: {
                        required: true
                    },
                    job_title: {
                        required: true
                    },
                    location: {
                        required: true
                    },
                    department: {
                        required: true
                    },
                    license_number: {
                        required: true
                    },
                    license_date_issued: {
                        required: true
                    },
                    license_date_expiry: {
                        required: true
                    },
                    license_class: {
                        required: true
                    },
                    license_front_view: {
                        required: true
                    },
                    license_back_view: {
                        required: true
                    },
                    permit_number: {
                        required: true
                    },
                    permit_date_issued: {
                        required: true
                    },
                    permit_date_expiry: {
                        required: true
                    },
                    permit_copy: {
                        required: true
                    }
                },
                {
                    'employee_number': {
                        required: "You have not provided employee staff number",
                        maxlength: 'Staff number can not be more than 10 characters',
                        minlength: 'Staff number can not be less than 5 characters'
                    },
                }
            );


        })(window.tmsApp, jQuery);
    </script>

@endpush
