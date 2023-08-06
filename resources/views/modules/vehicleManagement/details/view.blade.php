@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@push('styles')
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
    <x-content-header/>
    <section class="content">
        <div class="row g-12 g-xl-12" id="kt_app_main">

            <!--BEGIN:::VEHICLE HEADER -->
            <div class="card mb-xl-10">
                <div id="card_header" class="card-header min-h-2px">
                    <div class="card-title">
                        {{--<h2> Vehicle On-Boarding</h2>
                        <span v-if="!vehicleHeader.isHeaderSaved"
                              class="ml-2 indicator-pill whitespace-nowrap orange"><span>Not Saved</span></span>
                        <span v-else class="ml-2 indicator-pill whitespace-nowrap green">
                            <span>
                                @{{ vehicleHeader.on_boarding_status | formatStatus }}
                            </span>
                        </span>--}}
                    </div>

                    <div v-if="!vehicleHeader.isHeaderSaved" id="actionButtonsContainer"
                         class="card-toolbar justify-content-end">
                        <button type="button" id="submitBtn" disabled class="btn btn-success btn-sm mr-3">
                            <i class="fas fa-paper-plane"></i> Submit
                        </button>
                        <button type="button" id="resetFormBtn" class="btn btn-danger btn-sm mr-3">
                            <i class="fas fa-undo"></i> Cancel
                        </button>
                    </div>
                    <div class="card-toolbar justify-content-end" v-if="vehicleHeader.isHeaderSaved">
                        {{--<button type="button" data-bs-target="#vehicleDisk" data-bs-toggle="modal"
                                class="btn btn-default btn-sm mr-3">
                            <i class="fas fa-print"></i> Print Disk
                        </button>--}}
                    </div>
                </div>

                <!--begin::Card body-->
                <div class="card-body">
                    <x-error-view/>
                    <form name="vehicleHeaderForm" id="tms_vehicle_header_form"
                          class="form"
                          action="{{route('new.vehicle.header')}}">
                        <input type="hidden" name="doctype" value="VehicleHeader"/>
                        <div class="row">
                            <div class="col-4">
                                <div v-if="images && images.frontView">
                                    <img style="height: 50px;" class="frontImagePreview"
                                         v-bind:src='"/storage" + images.frontView.path' alt=""/>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label style="display: none;"
                                               for="registration_type"
                                               class="fs-6 fw-semibold form-label mt-3 col-md-3">
                                            <span class="required">Registration Type</span>
                                        </label>
                                        <div class="col-md-9 fv-row">
                                            <div class="col-md-9" style="display: none;">
                                                <div class="w-100 fv-row">
                                                    <select class="form-select form-select-sm"
                                                            id="registration_type"
                                                            name="registration_type"
                                                            @input="registrationTypeChanged"
                                                            v-model="vehicleHeader.registration_type">
                                                        <option v-for="regType in registrationTypes"
                                                                :key="regType.code"
                                                                :value="regType.code">
                                                            @{{ regType.label }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="brand" class="fs-6 fw-semibold form-label col-md-3">
                                            <span class="required">Brand/Make</span>
                                        </label>
                                        <div class="col-md-9 fv-row">
                                            <div class="col-md-9">
                                                <div class="w-100 fv-row">
                                                    <select class="form-control view_mode"
                                                            name="brand"
                                                            id="brand">
                                                        <option>--Select Brand--</option>
                                                        <option v-for="brand in vehicleBrands"
                                                                :key="brand.id"
                                                                :value="brand.id | trimSpaces">
                                                            @{{brand.name}}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="model" class="fs-6 fw-semibold form-label col-md-3">
                                            <span class="required">Model</span>
                                        </label>
                                        <div class="col-md-9 fv-row ">
                                            <div class="col-md-9">
                                                <div class="w-100">
                                                    <select class="form-select form-select-sm view_mode"
                                                            required
                                                            name="model"
                                                            id="model">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="model_code" class="fs-6 fw-semibold form-label col-md-3">
                                            <span class="required">Model Code</span>
                                        </label>

                                        <div class="col-md-9 fv-row">
                                            <div class="col-md-9">
                                                <div class="w-100">
                                                    {{-- :value="vehicleHeader.model_code"--}}
                                                    <input class="form-control form-control-solid"
                                                           name="model_code"
                                                           readonly
                                                           id="model_code"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="brand" class="fs-6 fw-semibold form-label col-md-3">
                                            <span class="required">Body Type</span>
                                        </label>

                                        <div class="col-md-9 fv-row ">
                                            <div class="col-md-9">
                                                <div class="w-100">
                                                    <select class="form-select form-select-sm view_mode"
                                                            required
                                                            id="bodyType"
                                                            name="bodyType">
                                                    </select>
                                                    <input type="hidden"
                                                           id="bodyType_holder"
                                                           name="bodyType_holder"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label for="user_unit" class="fs-6 fw-semibold form-label col-md-3">
                                            <span class="required">User Unit</span>
                                        </label>

                                        <div class="col-md-9 fv-row ">
                                            <div class="col-sm-12 col-md-12">
                                                <div class="control-input-wrapper">
                                                    <div class="control-input">
                                                        <div class="link-field ui-front" style="position: relative;">
                                                            <select class="form-control"
                                                                    required
                                                                    name="user_unit"
                                                                    id="user_unit"
                                                                    data-doctype="vehicleHeader"></select>
                                                        </div>
                                                    </div>
                                                    <div class="control-value like-disabled-input bold"
                                                         style="display: none;"></div>
                                                    <p class="help-box small text-muted"></p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="registrationNumber" class="fs-6 fw-semibold form-label col-md-3">
                                            <span class="required">Registration #.</span>
                                        </label>
                                        <div class="col-md-9 fv-row">
                                            <div class="col-md-9">
                                                {{--v-model="vehicleHeader.registration_number"--}}
                                                <input type="text"
                                                       class="form-control"
                                                       name="registrationNumber"
                                                       id="registrationNumber"
                                                       autocomplete="off"
                                                       required
                                                />
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="vehicleLocation" class="fs-6 fw-semibold form-label col-md-3">
                                            <span class="required">Location</span>
                                        </label>
                                        <div class="col-md-9 fv-row">
                                            <div class="col-md-9">
                                                {{--v-model="vehicleHeader.location_code"--}}
                                                <select
                                                        required
                                                        class="form-control"
                                                        name="vehicleLocation"
                                                        autocomplete="off"
                                                        id="vehicleLocation">
                                                </select>
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>

                                    {{--@if($vehicle && !$empty($vehicle->barcode)) @endif--}}
                                    <div class="form-group row mt-10 d-none" id="barcodeContainer">
                                        <label for="barcode" class="fs-6 fw-semibold form-label col-md-3">
                                            <span class="required">Vehicle Badge</span>
                                        </label>
                                        <div class="col-md-9 fv-row">
                                            <div class="col-md-9">
                                                <img id="barcode" alt="vehicle barcode" src="">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>

                    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-4 fw-semibold mb-5"
                        role="tablist">

                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link active" data-toggle="tab" href="#overview" role="tab">Overview</a>
                        </li>

                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link" data-toggle="tab" href="#specs" role="tab">Specs</a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-primary pb-5"
                               data-bs-toggle="tab"
                               href="#financial" aria-selected="false" role="tab"
                               tabindex="-1">
                                Financial
                            </a>
                        </li>

                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link" data-toggle="tab" href="#serviceHistory" role="tab">Service History</a>
                        </li>
                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link" data-toggle="tab" href="#inspectionHistory" role="tab">Inspection
                                History</a>
                        </li>
                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link" data-toggle="tab" href="#workOrders" role="tab">Work Orders</a>
                        </li>
                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link" data-toggle="tab" href="#serviceReminders" role="tab">
                                Service
                                Reminders</a>
                        </li>
                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link" data-toggle="tab" href="#renewalReminder" role="tab">Renewal
                                Reminders</a>
                        </li>
                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link" data-toggle="tab" href="#odometerHistory" role="tab">Odometer
                                History</a>
                        </li>

                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link" data-toggle="tab" href="#fuelHistory" role="tab">Fuel History</a>
                        </li>

                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link" data-toggle="tab" href="#assignmentHistory" role="tab">Assignment
                                History</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="overview" role="tabpanel">
                            <div class="container-fluid pl-0 mt-5">
                                Overview
                            </div>
                        </div>

                        <div class="tab-pane fade" id="specs" role="tabpanel">
                            @include('modules.vehicleManagement.onboarding.tabs.chassis_tab')
                            @include('modules.vehicleManagement.onboarding.tabs.engine_details_tab')
                            @include('modules.vehicleManagement.onboarding.tabs.accessories_tab')
                            @include('modules.vehicleManagement.onboarding.tabs.weight_details_tab')
                        </div>

                        <div class="tab-pane fade" id="financial" role="tabpanel">
                            <form id="tms_costing_valuation_form"
                                  name="tms_costing_valuation_form"
                                  class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                  action="{{route('vehicle.cost.detail')}}">
                                <input type="hidden" name="doctype" value="CostingDetails"/>
                                <input type="hidden" name="headerId" value="{{$reference}}"/>
                                <input type="hidden" name="costAndValuationId"
                                       value="{{$vehicle->costAndValuationId ?? 0}}"/>

                                <x-error-view/>
                                <div class="col-8">
                                    <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                                        <tbody>
                                        <tr>
                                            <td class="frappe-control ">
                                                <label class="app-field-label reqd"
                                                       for="staff_no">Purchase Order Number :
                                                </label>
                                            </td>
                                            <td>

                                            </td>
                                            <td class="frappe-control"></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="frappe-control ">
                                                <label for="supplierName" class="control-label reqd"
                                                       style="padding-right: 0px;">
                                                    Supplier Name:
                                                </label>
                                            </td>
                                            <td colspan="1">
                                                <div class="control-input-wrapper">
                                                    <div class="control-input">
                                                        <div class="link-field ui-front" style="position: relative;">
                                                            <div>
                                                                {{--v-model="costingAndValuation.supplierName"--}}
                                                                <select class="form-select form-control-sm view_mode"
                                                                        data-doctype="CostingDetails"
                                                                        data-value=""
                                                                        id="supplierName"
                                                                        name="supplierName">
                                                                </select>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="frappe-control"></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td class="frappe-control ">
                                                <label for="costPrice" class="control-label reqd"
                                                       style="padding-right: 0px;">
                                                    Cost Price:
                                                </label>
                                            </td>
                                            <td>
                                                <div class="control-input-wrapper">
                                                    <div class="control-input">
                                                        <div class="link-field ui-front" style="position: relative;">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-addon">
                                                                        <select name="bookValueCurrency"
                                                                                class="form-select form-select-sm"
                                                                                style="height: 2.5em; border-radius: 0;">
                                                                            <option value="001">ZMW</option>
                                                                            <option value="002">USD</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <input type="text"
                                                                       class="input-with-feedback form-control bold view_mode"
                                                                       maxlength="15"
                                                                       data-a-sign="ZMW "
                                                                       id="costPrice"
                                                                       name="costPrice"
                                                                       placeholder=""
                                                                       autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="frappe-control">
                                                <div class="clearfix">
                                                    <label for="yearOfPurchase" class="control-label reqd"
                                                           style="padding-right: 0px;">
                                                        Year Purchased:
                                                    </label>
                                                    <span class="help"></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="control-input-wrapper">
                                                    <div class="control-input">
                                                        <div class="link-field ui-front" style="position: relative;">
                                                            <div class="input-group">
                                                                <input type="number" min="1990" max="{{date('Y')}}"
                                                                       step="1"
                                                                       class="input-with-feedback form-control bold number_input view_mode"
                                                                       maxlength="4"
                                                                       name="yearOfPurchase"
                                                                       id="yearOfPurchase"
                                                                       data-doctype="CostingDetails"
                                                                       autocomplete="off">
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text">
                                                                        <i class="fas fa-calender"></i>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <p class="help-box small text-muted"></p>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="frappe-control ">
                                                <label for="bookValue" class="control-label reqd"
                                                       style="padding-right: 0px;">
                                                    Book Value:
                                                </label>
                                            </td>
                                            <td>
                                                <div class="control-input-wrapper">
                                                    <div class="control-input">
                                                        <div class="link-field ui-front" style="position: relative;">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-addon">
                                                                        <select name="bookValueCurrency"
                                                                                class="form-select form-select-sm"
                                                                                style="height: 2.5em; border-radius: 0;">
                                                                            <option value="001">ZMW</option>
                                                                            <option value="002">USD</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <input type="text"
                                                                       class="input-with-feedback form-control bold view_mode"
                                                                       id="bookValue"
                                                                       data-a-sign="ZMW "
                                                                       name="bookValue"
                                                                       placeholder=""
                                                                       data-doctype="CostingDetails"
                                                                       autocomplete="off"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="frappe-control">
                                                <div class="clearfix">
                                                    <label for="assetNumber" class="control-label reqd"
                                                           style="padding-right: 0px;">
                                                        Asset No. :
                                                    </label>
                                                    <span class="help"></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="control-input-wrapper">
                                                    <div class="control-input">
                                                        <div class="link-field ui-front" style="position: relative;">
                                                            <div>
                                                                <input type="text"
                                                                       class="input-with-feedback form-control bold view_mode"
                                                                       maxlength="140"
                                                                       data-fieldtype="Link"
                                                                       data-fieldname="company"
                                                                       id="assetNumber"
                                                                       name="assetNumber"
                                                                       placeholder=""/>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="frappe-control ">
                                                <label for="costOfLicense" class="control-label reqd"
                                                       style="padding-right: 0px;">
                                                    Cost Of License (Road Tax):
                                                </label>
                                            </td>
                                            <td>
                                                {{--v-model="costingAndValuation.costOfLicense"--}}
                                                <div class="control-input-wrapper">
                                                    <div class="control-input">
                                                        <div class="link-field ui-front" style="position: relative;">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-addon">
                                                                        ZMW
                                                                    </div>
                                                                </div>
                                                                <input type="text"
                                                                       class="input-with-feedback form-control bold number_input view_mode"
                                                                       id="costOfLicense"
                                                                       data-a-sign="ZMW"
                                                                       name="costOfLicense"
                                                                       placeholder=""
                                                                       data-target="Company">
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="frappe-control ">
                                                <label for="premium" class="control-label reqd"
                                                       style="padding-right: 0px;">
                                                    Insurance Premium:
                                                </label>
                                            </td>
                                            <td>
                                                <div class="control-input-wrapper">
                                                    <div class="control-input">
                                                        <div class="link-field ui-front" style="position: relative;">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-addon">
                                                                        ZMW
                                                                    </div>
                                                                </div>
                                                                <input type="text"
                                                                       class="input-with-feedback form-control bold view_mode"
                                                                       maxlength="140"
                                                                       id="premium"
                                                                       name="premium"
                                                                       placeholder=""/>
                                                            </div>
                                                        </div>
                                                        <small>10% of Cost Price</small>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="frappe-control ">
                                                <label for="premium" class="control-label"
                                                       style="padding-right: 0px;">
                                                    Purchase Order:
                                                </label>
                                            </td>
                                            <td>
                                                <div class="col-md-7 fv-row pl-0">
                                                    <div class="col-md-9 pl-0">
                                                        <input type="file"
                                                               accept="image/*,.pdf"
                                                               class="filer_input"
                                                               name="purchaseOrderDocument"/>
                                                    </div>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <div class="create_mode">
                                        {{-- <button type="submit" id="tms_save_costing"
                                                 class="btn btn-success btn-sm">
                                             <i class="fas fa-paper-plane"></i>
                                             <div class="indicator-label">
                                                 Save
                                             </div>
                                             <div class="indicator-progress">
                                                 Please wait...
                                                 <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                             </div>
                                         </button>--}}
                                    </div>
                                </div>
                                <div class="col-4">
                                    <table
                                            class="table align-middle table-row-dashed dataTable no-footer">
                                        <thead>
                                        <tr class="bg-dark">
                                            <th>Document No.</th>
                                            <th>Document Type</th>
                                            <th>File Name</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tr>
                                            <td>
                                                <div class="input-group">
                                                    <input type="text"
                                                           data-action="{{route('verify.purchase.order')}}"
                                                           class="form-control form-control-sm view_mode"
                                                           id="purchase_order_number"
                                                           placeholder=""
                                                           name="purchase_order_number">
                                                    <div class="input-group-addon">
                                                        <button type="button" id="poSearchBtn"
                                                                name="poSearchBtn"
                                                                class="btn btn-primary btn-sm border-radius-0 view_mode">
                                                            <i class="fas fa-search"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Purchase Order</td>
                                            <td v-if="documents && documents.purchase_order">@{{
                                                documents.purchase_order?.originalDocumentName }}
                                            </td>
                                            <td v-if="documents && documents.purchase_order">
                                                <button data-zfm-view-file="insurance"
                                                        type="button"
                                                        :data-document-url="'/storage'+documents.purchase_order?.path"
                                                        class="btn btn-sm btn-success">View File
                                                </button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="serviceHistory" role="tabpanel">
                            <div class="container-fluid pl-0 mt-5">
                                Service History
                            </div>
                        </div>

                        <div class="tab-pane fade" id="inspectionHistory" role="tabpanel">
                            <div class="container-fluid pl-0 mt-5">
                                Inspection History
                            </div>
                        </div>

                        <div class="tab-pane fade" id="workOrders" role="tabpanel">
                            <div class="container-fluid pl-0 mt-5">
                                Work Orders
                            </div>
                        </div>

                        <div class="tab-pane fade" id="serviceReminders" role="tabpanel">
                            <div class="container-fluid pl-0 mt-5">
                                Service Reminders
                            </div>
                        </div>

                        <div class="tab-pane fade" id="odometerHistory" role="tabpanel">
                            <div class="container-fluid pl-0 mt-5">
                                Odometer History
                            </div>
                        </div>

                        <div class="tab-pane fade" id="renewalReminder" role="tabpanel">
                            <div class="container-fluid pl-0 mt-5">
                                Renewal Reminder
                            </div>
                        </div>

                        <div class="tab-pane fade" id="fuelHistory" role="tabpanel">
                            <div class="container-fluid pl-0 mt-5">
                                Fuel History
                            </div>
                        </div>

                        <div class="tab-pane fade" id="assignmentHistory" role="tabpanel">
                            <div class="container-fluid pl-0 mt-5">
                                @include('modules.vehicleManagement.onboarding.tabs.assignment_details')
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!--END:::VEHICLE HEADER -->


            <!--BEGIN:::DETAILS  -->
            {{--<div v-show="isHeaderSaved" class="col-md-12 col-sm-12 mb-5 mb-xl-10"
                 style="border-right: 1px solid dimgray;">

                <div class="card card-flush">

                    <div class="card-body">

                        <!--BEGIN:::TAB HEADERS  -->
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-4 fw-semibold mb-5"
                            role="tablist">
                            <li class="nav-item" role="presentation" data-tab="tms_engine_details_tab">
                                <a
                                        class="nav-link text-active-primary pb-5"
                                        data-bs-toggle="tab"
                                        href="#tms_access_checkin_tab" aria-selected="false" role="tab"
                                        tabindex="-1">
                                    @include('layouts.partials.engine_icon')
                                    Accessories Check-in
                                </a>
                            </li>

                            <li class="nav-item" role="presentation" data-tab="tms_body_weight_tab">
                                <a class="nav-link text-active-primary pb-5"
                                   data-bs-toggle="tab"
                                   href="#tms_body_weight_tab" aria-selected="true" role="tab">
                                    @include('layouts.partials.body_icon')
                                    Body & Weight Details
                                </a>
                            </li>

                            <li class="nav-item" role="presentation" data-tab="tms_assignment_tab">
                                <a class="nav-link text-active-primary pb-5"
                                   data-bs-toggle="tab"
                                   href="#tms_assignment_tab" aria-selected="false" role="tab"
                                   tabindex="-1">
                                    @include('layouts.partials.assignment_icon')
                                    Assignment
                                </a>
                            </li>

                        </ul>
                        <!--END:::TAB HEADERS  -->

                        <!--BEGIN:::TAB CONTENT  -->
                        <div class="tab-content" id="myTabContent">

                            <!--Begin:::Chassis Details Tab pane-->

                            <!--End:::Chassis Details Tab pane-->

                            <!--Begin:::Engine Details Tab pane-->
                            <div class="tab-pane fade" id="tms_engine_details_tab" role="tabpanel">

                            </div>
                            <!--End:::Engine Details Tab pane-->

                            <div class="tab-pane fade"
                                 id="tms_access_checkin_tab"
                                 role="tabpanel">

                            </div>

                            <!--Begin::: Costing And Valuation Tab pane-->

                            <!--End:::Tab pane-->

                            <!--Begin:::Body Weight Tab pane-->
                            <div class="tab-pane fade" id="tms_body_weight_tab" role="tabpanel">



                            </div>
                            <!--End::: Body WeightTab pane-->

                            <!--Begin:::Assignment Tab pane-->
                            <div class="tab-pane fade" id="tms_assignment_tab" role="tabpanel">

                            </div>
                            <!--End::: Assignment Tab pane-->

                        </div>
                        <!--BEGIN:::TAB CONTENT  -->
                    </div>
                </div>

            </div>--}}

            <!--END:::DETAILS  -->
            <input type="hidden"
                   name="vehicle_details"
                   value="{{route('vehicle.details', [$reference])}}"/>
            @include('modules.vehicleManagement.partial.data_end_point')
        </div>
    </section>
    <x-employee-search-modal/>

    <div class="modal fade" id="vehicleDisk"
         tabindex="-1"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Vehicle Disk</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center" id="diskArea">
                    <img class="img-fluid" src="{{asset('assets/dist/img/disk.jpeg')}}"/>
                </div>
                <div class="modal-footer">
                    <button type="button" id="print" class="btn btn-primary btn-sm">
                        <i class="fas fa-print"></i>
                        Print
                    </button>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-default">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="fileViewModal"
         tabindex="-1"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">File Viewer</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="documentView" src="" style="border: none;" width="100%" height="600px;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-default">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.reference = `{!! $reference !!}`;
    </script>
    <script src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script
            src="{{asset('application/modules/vehicleManagement/assets/js/new-vehicle-registration.js').'?v='.Carbon::now()->format('his')}}"></script>
    <script
            src="{{asset('application/modules/userManagement/employee.search.js').'?v='.Carbon::now()->format('his')}}"></script>
    <script>
        $(document).ready(function () {
            let elements = document.querySelectorAll('.view_mode');
            let elementsOnCreate = document.querySelectorAll('.create_mode');

            elements.forEach(function (element) {
                //element.removeAttribute('disabled');
                element.setAttribute('disabled', 'disabled');
            });

            elementsOnCreate.forEach(function (element) {
                element.style.display = 'none';
                //('disabled', 'disabled');
            })
        });
    </script>
@endpush
