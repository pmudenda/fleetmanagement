@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@push('styles')
    <style>
        .imagePreview {
            width: 100%;
            min-height: 280px;
            background-position: center center;
            background-color: #fff;
            background-size: cover;
            background-repeat: no-repeat;
            display: inline-block;
            box-shadow: 0px -3px 6px 2px rgba(0, 0, 0, 0.2);
        }

        .img_title {
            background-color: #454546ad;
        }

        .form-control:disabled {
            border: none !important;
            background-color: transparent !important;
        }

        .form-control:disabled {
            border: none !important;
            background-color: transparent !important;
            box-shadow: none !important;

        }

        /*.input-group {
            justify-content: center !important;
            align-items: center !important;
        }*/
    </style>
    <link rel="stylesheet" href="{{asset('libs/handsontable/handsontable.full.min.css')}}"/>
@endpush
@section('content')
    <x-content-header/>
    <section class="content">
        <div class="row g-12 g-xl-12" id="kt_app_main">

            <!--BEGIN:::VEHICLE HEADER -->
            <div class="card mb-xl-10">
                <div id="card_header" class="card-header min-h-2px">
                    <div class="card-title">
                        <h2> Vehicle Details</h2>
                        {{--<span v-if="!vehicleHeader.isHeaderSaved"
                              class="ml-2 indicator-pill whitespace-nowrap orange"><span>Not Saved</span></span>
                        <span v-else class="ml-2 indicator-pill whitespace-nowrap green">
                            <span>
                                @{{ vehicleHeader.on_boarding_status | formatStatus }}
                            </span>
                        </span>--}}
                    </div>
                    <div class="card-toolbar justify-content-end">
                        {{--<span class="ml-2 indicator-pill whitespace-nowrap green">
                            <span>
                                @{{ vehicleHeader.on_boarding_status | formatStatus }}
                            </span>
                        </span>--}}


                        @if($vehicle && !$empty($vehicle->barcode))
                            <img id="barcode" alt="vehicle barcode" style="max-height: 40px;" src="/storage/{{$vehicle->barcode}}">
                        @endif
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
                          class="form mb-10"
                          action="{{route('new.vehicle.header')}}">
                        <input type="hidden" name="doctype" value="VehicleHeader"/>
                        <div class="row">
                            <table>
                                <tr>
                                    <td style="vertical-align: baseline; width:15%;">
                                        <div v-if="images && images.frontView">
                                            <img style="height: 200px;" class="frontImagePreview"
                                                 v-bind:src='"/storage" + images.frontView.path' alt=""/>
                                        </div>
                                    </td>
                                    <td>
                                        <table>
                                            <tr>
                                               {{-- <td>Brand(Make) :</td>--}}
                                                <td>
                                                    <div class="row">
                                                        <input readonly
                                                               class="form-control view_mode"
                                                               name="brand"
                                                               id="brand"/>
                                                        <input class="form-control form-control-sm view_mode"
                                                               required
                                                               name="model"
                                                               id="model"/>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>

                                            </tr>
                                        </table>
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label style="display: none;"
                                                       for="registration_type"
                                                       class="fs-6 fw-semibold form-label mt-3 col-md-3">
                                                    <span class="required">Registration Type</span>
                                                </label>
                                                <div class="col-md-9 fv-row"
                                                     style="display: none; visibility: hidden; ">
                                                    <div class="col-md-9">
                                                        <div class="w-100 fv-row">
                                                            <select class="form-control form-control-sm"
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
                                        <div class="row">
                                            <div class="col-6">
                                                {{--<div class="form-group row">
                                                    <label for="brand" class="fs-6 fw-semibold form-label col-md-3">
                                                        <span class="required"></span>
                                                    </label>
                                                    <div class="col-md-9 fv-row">
                                                        <div class="col-md-9">
                                                            <div class="w-100 fv-row">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>--}}

                                               {{-- <div class="form-group row">
                                                    <label for="model" class="fs-6 fw-semibold form-label col-md-3">
                                                        <span class="required"></span>
                                                    </label>
                                                    <div class="col-md-9 fv-row ">
                                                        <div class="col-md-9">
                                                            <div class="w-100">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>--}}

                                                <div class="form-group row" style="display: none;">
                                                    <label for="model_code"
                                                           class="fs-6 fw-semibold form-label col-md-3">
                                                        <span class="required">Model Code</span>
                                                    </label>

                                                    <div class="col-md-9 fv-row">
                                                        <div class="col-md-9">
                                                            <div class="w-100">
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
                                                        <span class="required">Type</span>
                                                    </label>

                                                    <div class="col-md-9 fv-row ">
                                                        <div class="col-md-9">
                                                            <div class="w-100">
                                                                <input class="form-control form-control-sm view_mode"
                                                                       required
                                                                       id="bodyType"
                                                                       name="bodyType">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-group row" style="display: none;">
                                                    <label for="user_unit" class="fs-6 fw-semibold form-label col-md-3">
                                                        <span class="required">User Unit</span>
                                                    </label>

                                                    <div class="col-md-9 fv-row ">
                                                        <div class="col-sm-12 col-md-12">
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
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

                                            <div class="col-6">
                                                <div class="form-group row">
                                                    <label for="registrationNumber"
                                                           class="fs-6 fw-semibold form-label col-md-3">
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
                                                            <div
                                                                class="fv-plugins-message-container invalid-feedback"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="vehicleLocation"
                                                           class="fs-6 fw-semibold form-label col-md-3">
                                                        <span class="required">Location</span>
                                                    </label>
                                                    <div class="col-md-9 fv-row">
                                                        <div class="col-md-9">
                                                            <input
                                                                required
                                                                class="form-control"
                                                                name="vehicleLocation"
                                                                autocomplete="off"
                                                                id="vehicleLocation"/>
                                                            <div
                                                                class="fv-plugins-message-container invalid-feedback"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{--<div class="form-group row mt-10 d-none" id="barcodeContainer">
                                                    <label for="barcode" class="fs-6 fw-semibold form-label col-md-3">
                                                        <span class="required">Vehicle Badge</span>
                                                    </label>
                                                    <div class="col-md-9 fv-row">
                                                        <div class="col-md-9">

                                                        </div>
                                                    </div>
                                                </div>--}}

                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <div class="col-4">

                            </div>
                            <div class="col-8">

                            </div>
                        </div>
                    </form>

                    <hr/>

                    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-4 fw-semibold mb-5"
                        role="tablist">

                        <li class="nav-item" style="list-style: none; display: none;">
                            <a class="nav-link" data-toggle="tab" href="#overview" role="tab">Overview</a>
                        </li>

                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link active" data-toggle="tab" href="#specs" role="tab">Specs</a>
                        </li>

                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link" data-toggle="tab" href="#accessoriesTab"
                               role="tab">Accessories</a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-primary pb-5"
                               data-bs-toggle="tab"
                               href="#financial" aria-selected="false" role="tab"
                               tabindex="-1">
                                Financial
                            </a>
                        </li>

                        <li class="nav-item" style="list-style: none; display: none;">
                            <a class="nav-link" data-toggle="tab" href="#serviceHistory" role="tab">Service History</a>
                        </li>
                        <li class="nav-item" style="list-style: none; display: none;">
                            <a class="nav-link" data-toggle="tab" href="#inspectionHistory" role="tab">Inspection
                                History
                            </a>
                        </li>
                        <li class="nav-item" style="list-style: none; display: none;">
                            <a class="nav-link" data-toggle="tab" href="#workOrders" role="tab">Work Orders</a>
                        </li>
                        <li class="nav-item" style="list-style: none; display: none;">
                            <a class="nav-link" data-toggle="tab" href="#serviceReminders" role="tab">
                                Service
                                Reminders</a>
                        </li>
                        <li class="nav-item" style="list-style: none; display: none;">
                            <a class="nav-link" data-toggle="tab" href="#renewalReminder" role="tab">Renewal
                                Reminders</a>
                        </li>
                        <li class="nav-item" style="list-style: none; display: none;">
                            <a class="nav-link" data-toggle="tab" href="#odometerHistory" role="tab">
                                Meter History
                            </a>
                        </li>

                        <li class="nav-item" style="list-style: none; display: none;">
                            <a class="nav-link" data-toggle="tab" href="#fuelHistory" role="tab">Fuel History</a>
                        </li>

                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link" data-toggle="tab" href="#assignmentHistory" role="tab">Assignment
                                History</a>
                        </li>
                    </ul>
                    <hr/>
                    <div class="tab-content">
                        <div class="tab-pane" id="overview" role="tabpanel">
                            <div class="container-fluid pl-0 mt-5">
                                Overview
                            </div>
                        </div>

                        <div class="tab-pane active" id="specs" role="tabpanel">
                            <div
                                id="tms_chassis_details_form"
                                name="tmsChassisDetailsForm"
                                class="form"
                                action="{{route('vehicle.chassis.detail')}}">
                                <input type="hidden" name="doctype" value="ChassisDetails"/>
                                <input type="hidden" name="headerId" value="{{$reference}}"/>
                                <input type="hidden" name="chassisDetailsId"
                                       value="{{$vehicle->chassisDetailsId ?? 0}}"/>
                                <x-error-view/>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="row">
                                            <fieldset class="border p-3">
                                                <legend style="width: inherit;">
                                                    <h4 class="pt-2">Technical Details</h4>
                                                </legend>
                                                <table class="gs-0 gy-3 my-0">
                                                    <tbody>
                                                    <tr>
                                                        <td class="frappe-control ">
                                                            <label for="chassisNumber" class="control-label reqd"
                                                                   style="padding-right: 0px;">
                                                                Chassis #:
                                                            </label>
                                                        </td>
                                                        <td>
                                                            {{--@change="checkChassisNumberValidity" v-model="chassisDetails.chassisNumber"--}}
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <div class="">
                                                                            <input type="text"
                                                                                   required
                                                                                   id="chassisNumber"
                                                                                   name="chassisNumber"
                                                                                   class="input-with-feedback form-control bold view_mode"
                                                                                   maxlength="140"
                                                                                   data-fieldtype="Link"
                                                                                   data-fieldname="company"
                                                                                   placeholder=""
                                                                                   data-doctype="ChassisDetails"
                                                                                   data-target="Company"
                                                                                   autocomplete="off" role="combobox"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="frappe-control">
                                                            <div class="clearfix">
                                                                <label for="engineNumber" class="control-label reqd"
                                                                       style="padding-right: 0px;">Engine #:</label>
                                                                <span class="help"></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <div class="">
                                                                            {{--v-model="chassisDetails.engineNumber"--}}
                                                                            <input type="text"
                                                                                   required
                                                                                   class="input-with-feedback form-control view_mode"
                                                                                   maxlength="140" data-fieldtype="Link"
                                                                                   data-fieldname="company"
                                                                                   id="engineNumber"
                                                                                   name="engineNumber"
                                                                                   placeholder=""
                                                                                   data-doctype="ChassisDetails"
                                                                                   autocomplete="off"/>
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="frappe-control ">
                                                            <label for="whiteBookSerial" class="control-label reqd"
                                                                   style="padding-right: 0px;">
                                                                White Book Serial #:
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <div class="">
                                                                            {{--v-model="chassisDetails.whiteBookSerial"--}}
                                                                            <input type="text"
                                                                                   class="input-with-feedback form-control view_mode"
                                                                                   maxlength="50"
                                                                                   required
                                                                                   data-fieldname="company"
                                                                                   id="whiteBookSerial"
                                                                                   name="whiteBookSerial"
                                                                                   placeholder=""
                                                                                   autocomplete="off">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="frappe-control">
                                                            <div class="clearfix" style="display: none;">
                                                                <label for="stickerRegistrationNumber"
                                                                       class="control-label"
                                                                       style="padding-right: 0px;">
                                                                    Sticker #:
                                                                </label>
                                                                <span class="help"></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper" style="display: none;">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <div class="">
                                                                            <input type="text"
                                                                                   class="input-with-feedback form-control view_mode"
                                                                                   maxlength="140"
                                                                                   name="stickerRegistrationNumber"
                                                                                   id="stickerRegistrationNumber"
                                                                                   placeholder=""
                                                                                   data-doctype="ChassisDetails"
                                                                                   autocomplete="off"/>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <p class="help-box small text-muted"></p>
                                                            </div>
                                                        </td>
                                                    </tr>


                                                    <tr>
                                                        <td class="frappe-control ">
                                                            <label for="yearOfManufacture" class="control-label reqd"
                                                                   style="padding-right: 0px;">
                                                                Year Manufactured:
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <div class="">
                                                                            <input
                                                                                date-format="YYYY"
                                                                                class="input-with-feedback form-control bold number_input view_mode"
                                                                                type="number" min="1990"
                                                                                max="{{date('Y')}}"
                                                                                step="1"
                                                                                required
                                                                                id="yearOfManufacture"
                                                                                name="yearOfManufacture"
                                                                                v-model="chassisDetails.yearOfManufacture"
                                                                                placeholder=""
                                                                                data-doctype="ChassisDetails"/>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="frappe-control">
                                                            <div class="clearfix">
                                                                <label for="registrationDate" class="control-label reqd"
                                                                       style="padding-right: 0px;">Reg. Date:</label>
                                                                <span class="help"></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            {{--min="{{ date('Y-m-d', strtotime($form->trip->date_to)) }}"--}}
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <div class="">
                                                                            <input type="date"
                                                                                   max="{{ date('Y-m-d', strtotime(\Carbon\Carbon::now())) }}"
                                                                                   required
                                                                                   class="input-with-feedback form-control view_mode"
                                                                                   data-fieldname="registrationDate"
                                                                                   name="registrationDate"
                                                                                   id="registrationDate"
                                                                                   placeholder=""
                                                                                   data-doctype="ChassisDetails"
                                                                            />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr class="d-none">
                                                        <td>
                                                            <div class="clearfix">
                                                                <label for="dateOnRoad" class="control-label"
                                                                       style="padding-right: 0px;">
                                                                    Date on road :
                                                                </label>
                                                                <span class="help"></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <input type="date" name="dateOnRoad" id="dateOnRoad"
                                                                           disabled
                                                                           autocomplete="off"
                                                                           class="input-with-feedback form-control view_mode"
                                                                           data-fieldtype="Datetime"
                                                                           data-fieldname="first_date_on_road"
                                                                           placeholder=""
                                                                           data-doctype="ChassisDetails"/>
                                                                </div>

                                                            </div>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="frappe-control ">
                                                            <label for="chargeOutRate" class="control-label reqd"
                                                                   style="padding-right: 0px;">
                                                                Charge-Out Rate (/Km):
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <div class="input-group">
                                                                            {{--v-model="chassisDetails."--}}
                                                                            <input type="text"
                                                                                   name="chargeOutRate"
                                                                                   id="chargeOutRate"
                                                                                   class="input-with-feedback form-control view_mode"
                                                                                   required
                                                                                   placeholder=""
                                                                                   data-doctype="ChassisDetails"
                                                                                   autocomplete="off"/>
                                                                            {{-- <div
                                                                                     class="input-group-append align-self-center pr-2">

                                                                             </div>--}}
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </td>
                                                        <td class="frappe-control" colspan="1">
                                                            <div class="clearfix">
                                                                <label for="requiredMinimumDrivingLicense"
                                                                       class="control-label reqd"
                                                                       style="padding-right: 0px;">Driving License
                                                                    Class:</label>
                                                                <span class="help"></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <div class="">
                                                                            <select
                                                                                class="form-control form-control-sm view_mode"
                                                                                required
                                                                                name="requiredMinimumDrivingLicense"
                                                                                id="requiredMinimumDrivingLicense"
                                                                                v-model="chassisDetails.requiredMinimumDrivingLicense"
                                                                                data-doctype="ChassisDetails"
                                                                                :placeholder="'License Class'">
                                                                                <option>--Select Licence Class--
                                                                                </option>
                                                                                <option
                                                                                    v-for="licenseClass in licenseTypes"
                                                                                    :value="licenseClass.code">
                                                                                    @{{ licenseClass.name}}
                                                                                </option>
                                                                            </select>

                                                                            {{-- <input type="hidden"
                                                                                    class="input-with-feedback form-control bold"
                                                                                    required
                                                                                    data-fieldtype="Link"
                                                                                    :value="chassisDetails.requiredMinimumDrivingLicense"
                                                                                    placeholder=""
                                                                                    autocomplete="licenseTypes"/>--}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="frappe-control ">
                                                            <label for="initialOdometerReading"
                                                                   class="control-label reqd"
                                                                   style="padding-right: 0px;">
                                                                Initial Odometer Reading:
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <input type="number"
                                                                               v-model="chassisDetails.initialOdometerReading"
                                                                               name="initialOdometerReading"
                                                                               id="initialOdometerReading"
                                                                               class="input-with-feedback number_input form-control view_mode"
                                                                               placeholder=""
                                                                               required
                                                                               data-doctype="ChassisDetails"
                                                                               autocomplete="off"/>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </td>
                                                        <td class="frappe-control" colspan="1">
                                                            <div class="clearfix" style="display: none;">
                                                                <label for="currentOdometerReading"
                                                                       class="control-label reqd"
                                                                       style="padding-right: 0px;">Km Done:</label>
                                                                <span class="help"></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper" style="display: none;">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <div class="">
                                                                            <input type="text"
                                                                                   class="input-with-feedback number_input form-control view_mode"
                                                                                   required
                                                                                   name="currentOdometerReading"
                                                                                   id="currentOdometerReading"
                                                                                   value="0"
                                                                                   {{--v-model="chassisDetails.currentOdometerReading"--}}
                                                                                   placeholder=""
                                                                                   data-doctype="ChassisDetails"
                                                                                   autocomplete="off"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr style="display: none;">
                                                        <td class="frappe-control ">
                                                            <label for="odometerReadingLastService"
                                                                   class="control-label reqd"
                                                                   style="padding-right: 0px;">
                                                                Odometer Reading Last Service
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <input type="text"
                                                                               name="odometerReadingLastService"
                                                                               id="odometerReadingLastService"
                                                                               value="0"
                                                                               class="input-with-feedback number_input form-control bold view_mode"
                                                                               required
                                                                               placeholder=""
                                                                               data-doctype="ChassisDetails"
                                                                               autocomplete="off">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </td>
                                                        <td class="frappe-control" colspan="1">
                                                            <div class="clearfix">
                                                                <label for="nextServiceOdometerReading"
                                                                       class="control-label reqd"
                                                                       style="padding-right: 0px;">Next Service Odometer
                                                                    Reading:</label>
                                                                <span class="help"></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">

                                                                        <input type="text"
                                                                               class="input-with-feedback number_input form-control bold"
                                                                               required
                                                                               name="nextServiceOdometerReading"
                                                                               id="nextServiceOdometerReading"
                                                                               value="0"
                                                                               placeholder=""
                                                                               data-doctype="ChassisDetails"
                                                                               autocomplete="off"/>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr style="display: none;">
                                                        <td class="frappe-control ">
                                                            <label for="inspectionDate" class="control-label reqd"
                                                                   style="padding-right: 0px;">
                                                                Inspection Date:
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <input type="date"
                                                                               max="{{ date('Y-m-d', strtotime(\Carbon\Carbon::now())) }}"
                                                                               v-model="chassisDetails.inspectionDate"
                                                                               name="inspectionDate"
                                                                               id="inspectionDate"
                                                                               value="{{ date('Y-m-d', strtotime(\Carbon\Carbon::now())) }}"
                                                                               required
                                                                               class="input-with-feedback form-control bold view_mode"
                                                                               placeholder=""
                                                                               data-doctype="ChassisDetails"
                                                                               autocomplete="off">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </td>
                                                        <td class="frappe-control" colspan="1">
                                                            <div class="clearfix" style="display: none;">
                                                                <label for="odometerReset"
                                                                       class="control-label"
                                                                       style="padding-right: 0px;">
                                                                    Odometer Reset:</label>
                                                                <span class="help"></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper" style="display: none;">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <input type="checkbox"
                                                                               class="input-with-feedback form-check-input bold"
                                                                               disabled
                                                                               name="odometerReset"
                                                                               id="odometerReset"
                                                                               v-model="chassisDetails.odometerReset"
                                                                               placeholder=""
                                                                               data-doctype="ChassisDetails"/>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    </tbody>
                                                </table>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row"
                                             v-if="documents && documents.insurance && documents.certificate">
                                            <fieldset class="border p-3">
                                                <legend style="width: inherit;">
                                                    <h4 class="pt-2">Documents</h4>
                                                </legend>
                                                <table class="">
                                                    <thead>
                                                    <tr class="bg-dark">
                                                        <th>Document Type</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tr>
                                                        <td>Motor Vehicle Certificate</td>
                                                        {{-- <td>@{{ documents.certificate?.originalDocumentName }}</td>--}}
                                                        <td>
                                                            <button data-zfm-view-file="certificate"
                                                                    type="button"
                                                                    :data-document-url="'/storage'+documents.certificate?.path"
                                                                    class="btn btn-sm btn-success">
                                                                <i class="fa fa-paperclip"></i>
                                                                View File
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Insurance Cover Note</td>
                                                        {{-- <td>@{{ documents.insurance?.originalDocumentName }}</td>--}}
                                                        <td>
                                                            <button data-zfm-view-file="insurance"
                                                                    type="button"
                                                                    :data-document-url="'/storage'+documents.insurance?.path"
                                                                    class="btn btn-sm btn-success">
                                                                <i class="fa fa-paperclip"></i>
                                                                View File
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-10">
                                    <div class="col-6">
                                        <form id="tms_engine_details_form"
                                              name="engineDetailsForm"
                                              class="form"
                                              action="{{route('vehicle.engine.detail')}}">
                                            <input type="hidden" name="doctype" value="EngineDetails"/>
                                            <input type="hidden" name="headerId" value="{{$reference}}"/>
                                            <input type="hidden" name="engineDetailsId"
                                                   value="{{$vehicle->engineDetailsId ?? 0}}"/>

                                            <x-error-view/>
                                            <fieldset class="border p-3">
                                                <legend style="width: inherit;">
                                                    <h4 class="pt-2">Engine Details</h4>
                                                </legend>
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <table class="align-middle gs-0 gy-3 my-0">
                                                        <tbody>
                                                        <tr>
                                                            <td class="frappe-control ">
                                                                <label for="numberOfCylinders"
                                                                       class="control-label reqd"
                                                                       style="padding-right: 0px;">
                                                                    Number Of Cylinders:
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div>
                                                                                <input type="number"
                                                                                       max="16"
                                                                                       min="2"
                                                                                       required
                                                                                       id="numberOfCylinders"
                                                                                       name="numberOfCylinders"
                                                                                       class="input-with-feedback form-control bold number_input view_mode"
                                                                                       data-fieldtype="Link"
                                                                                       data-fieldname="company"
                                                                                       data-doctype="EngineDetails"
                                                                                       autocomplete="off"/>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="frappe-control">
                                                                <div class="clearfix">
                                                                    <label for="engineCapacity"
                                                                           class="control-label reqd"
                                                                           style="padding-right: 0px;">Engine Capacity
                                                                        (cc)
                                                                        :</label>
                                                                    <span class="help"></span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div class="input-group">
                                                                                <input type="number"
                                                                                       class="input-with-feedback form-control bold number_input view_mode"
                                                                                       max="10000"
                                                                                       required
                                                                                       data-fieldtype="Link"
                                                                                       data-fieldname="company"
                                                                                       id="engineCapacity"
                                                                                       name="engineCapacity"
                                                                                       placeholder=""
                                                                                       data-doctype="EngineDetails"/>
                                                                                {{--<div
                                                                                        class="input-group-addon align-self-center pl-3 pr-3">

                                                                                </div>--}}
                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="frappe-control">
                                                                <div class="clearfix">
                                                                    <label for="actualEnginePower"
                                                                           class="control-label reqd"
                                                                           style="padding-right: 0px;">
                                                                        Engine Horse Power (hp):
                                                                    </label>
                                                                    <span class="help"></span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div class="input-group">
                                                                                {{--v-model="engineDetails.actualEnginePower"--}}
                                                                                <input type="number"
                                                                                       required
                                                                                       class="input-with-feedback form-control bold number_input view_mode"
                                                                                       maxlength="140"
                                                                                       name="actualEnginePower"
                                                                                       id="actualEnginePower"
                                                                                       placeholder=""
                                                                                       data-doctype="EngineDetails"
                                                                                       autocomplete="off">
                                                                                {{--  <div
                                                                                          class="input-group-append pl-3 pr-3 align-self-center">

                                                                                  </div>--}}
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <p class="help-box small text-muted"></p>
                                                                </div>
                                                            </td>

                                                            <td style="display: none;" class="frappe-control ">
                                                                <label for="claimedEnginePower"
                                                                       class="control-label reqd"
                                                                       style="padding-right: 0px;">
                                                                    Horse Power:
                                                                </label>
                                                            </td>
                                                            <td style="display: none;">
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div class="input-group">
                                                                                {{--v-model="engineDetails.claimedEnginePower"--}}
                                                                                <input type="number"
                                                                                       required
                                                                                       class="input-with-feedback form-control bold number_input view_mode"
                                                                                       maxlength="140"
                                                                                       value="0"
                                                                                       data-fieldname="company"
                                                                                       id="claimedEnginePower"
                                                                                       name="claimedEnginePower"
                                                                                       placeholder=""
                                                                                       data-doctype="EngineDetails"
                                                                                       data-target="Company"
                                                                                       autocomplete="off"/>
                                                                                <div
                                                                                    class="input-group-append pl-3 pr-3 align-self-center">
                                                                                    hp
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="frappe-control">
                                                                <div class="clearfix">
                                                                    <label for="fuelTypes" class="control-label reqd"
                                                                           style="padding-right: 0px;">
                                                                        Fuel Type:
                                                                    </label>
                                                                    <span class="help"></span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div>
                                                                                {{--v-model="engineDetails.fuelTypes"--}}
                                                                                <select
                                                                                    required
                                                                                    class="input-with-feedback form-control bold view_mode"
                                                                                    id="fuelTypes"
                                                                                    name="fuelTypes"
                                                                                    data-doctype="EngineDetails">
                                                                                    <option
                                                                                        v-for="fuelType in fuelTypes"
                                                                                        :value="fuelType.code_article"
                                                                                        :key="fuelType.code_article">
                                                                                        @{{ fuelType.description }}
                                                                                    </option>
                                                                                </select>
                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </td>

                                                            <td class="frappe-control " style="display: none">
                                                                <label for="engineBrand" class="control-label reqd"
                                                                       style="padding-right: 0px;">
                                                                    Engine Brand:
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <input type="hidden"
                                                                                   data-fieldtype="Link"
                                                                                   data-fieldname="company"
                                                                                   id="engineBrand"
                                                                                   name="engineBrand"
                                                                                   value="N/A"
                                                                                   data-doctype="EngineDetails"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="frappe-control ">
                                                                <label for="engineType"
                                                                       class="control-label reqd"
                                                                       style="padding-right: 0px;">
                                                                    Engine Code:
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <input
                                                                                required
                                                                                class="input-with-feedback form-control bold view_mode"
                                                                                data-fieldtype="Link"
                                                                                data-fieldname="company"
                                                                                placeholder="e.g 1NZ"
                                                                                id="engineType"
                                                                                name="engineType"
                                                                                v-model="engineDetails.engineType"
                                                                                data-doctype="EngineDetails"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <label for="transmission_type"
                                                                       class="control-label reqd"
                                                                       style="padding-right: 0px;">
                                                                    Transmission Type:
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <select
                                                                                required
                                                                                id="transmission_type"
                                                                                name="transmission_type"
                                                                                class="form-control form-control-sm view_mode"
                                                                                v-model="engineDetails.transmissionType"
                                                                                data-doctype="EngineDetails"
                                                                                @change="transmissionTypeChanged">
                                                                                {{--<option value="">--Select Transmission--</option>--}}
                                                                                <option
                                                                                    v-for="transType in transmissionTypes"
                                                                                    :value="transType.code">
                                                                                    @{{ transType.name }}
                                                                                </option>
                                                                            </select>
                                                                            <input type="hidden" required/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>


                                                        <tr>
                                                            <td class="frappe-control">
                                                                <div class="clearfix">
                                                                    <label for="fuelConsumption"
                                                                           class="control-label reqd"
                                                                           style="padding-right: 0px;">
                                                                        Fuel Consumption (Km/Ltr):
                                                                    </label>
                                                                    <span class="help"></span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div title="Number of kilometers per litre"
                                                                     class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div class="input-group">
                                                                                {{--v-model="engineDetails.fuelConsumption"--}}
                                                                                <input type="text"
                                                                                       required
                                                                                       class="input-with-feedback form-control bold view_mode"
                                                                                       maxlength="4"
                                                                                       max="25"
                                                                                       name="fuelConsumption"
                                                                                       id="fuelConsumption"
                                                                                       placeholder=""
                                                                                       data-doctype="EngineDetails"
                                                                                       autocomplete="off">
                                                                                {{--<div
                                                                                        class="input-group-append pl-3 pr-3 align-self-center">

                                                                                </div>--}}
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <p class="help-box small text-muted"></p>
                                                                </div>
                                                            </td>

                                                            <td class="frappe-control ">
                                                                {{--<label for="fuelAllocation"
                                                                       class="control-label reqd"
                                                                       style="padding-right: 0px;">
                                                                    Fuel Allocation:
                                                                </label>--}}
                                                            </td>
                                                            <td>
                                                                {{-- <div class="control-input-wrapper">
                                                                     <div class="control-input">
                                                                         <div class="link-field ui-front" style="position: relative;">
                                                                             <div class="input-group">
                                                                                 <input type="text"
                                                                                        required
                                                                                        class="input-with-feedback form-control bold"
                                                                                        maxlength="140"
                                                                                        v-model="engineDetails.fuelAllocation"
                                                                                        name="fuelAllocation"
                                                                                        id="fuelAllocation"
                                                                                        placeholder=""
                                                                                        autocomplete="off">
                                                                                 <div
                                                                                     class="input-group-append pl-3 pr-3 align-self-center">
                                                                                     Ltrs-Daily
                                                                                 </div>
                                                                             </div>
                                                                         </div>
                                                                     </div>
                                                                 </div>--}}
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="frappe-control">
                                                                <div class="clearfix">
                                                                    <label for="tank_capacity"
                                                                           class="control-label reqd"
                                                                           style="padding-right: 0px;">
                                                                        Main Tank Capacity (Ltr):
                                                                    </label>
                                                                    <span class="help"></span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div class="input-group">
                                                                                <input type="number"
                                                                                       class="input-with-feedback number_input form-control bold view_mode"
                                                                                       maxlength="4"
                                                                                       required
                                                                                       name="tank_capacity"
                                                                                       id="tank_capacity"
                                                                                       placeholder=""
                                                                                       autocomplete="off">
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="clearfix">
                                                                    <label for="sub_tank_capacity" class="control-label"
                                                                           style="padding-right: 0px;">
                                                                        Sub Tank Capacity <small>(If Any)</small> (Ltr):
                                                                    </label>
                                                                    <span class="help"></span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div class="input-group">
                                                                                <input type="number"
                                                                                       maxlength="4"
                                                                                       class="input-with-feedback number_input form-control bold view_mode"
                                                                                       name="sub_tank_capacity"
                                                                                       id="sub_tank_capacity"
                                                                                       placeholder=""
                                                                                       autocomplete="off">
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </fieldset>

                                            <fieldset class="border p-3">
                                                <legend style="width: inherit;">
                                                    <h4 class="pt-2">Tyres</h4>
                                                </legend>
                                                <div class="col-xs-12 col-sm-8 col-md-8">
                                                    <table class="align-middle gs-0 gy-3 my-0">
                                                        <tbody>
                                                        <tr>
                                                            <td class="frappe-control ">
                                                                <label for="numberOfTyres" class="control-label reqd"
                                                                       style="padding-right: 0px;">
                                                                    Total Number:
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div class="fv-row">
                                                                                {{--v-model="otherDetails.numberOfTyres"--}}
                                                                                <input type="number"
                                                                                       title="The number of tyres the vehicle has"
                                                                                       id="numberOfTyres"
                                                                                       name="numberOfTyres"
                                                                                       class="input-with-feedback form-control bold number_input view_mode"
                                                                                       maxlength="140"
                                                                                       placeholder=""
                                                                                       data-doctype="EngineDetails"
                                                                                       autocomplete="off"/>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="frappe-control">
                                                                <div class="clearfix">
                                                                    <div class="clearfix">
                                                                        <label for="tyreBrand"
                                                                               class="control-label reqd"
                                                                               style="padding-right: 0px;">Brand
                                                                            :</label>
                                                                        <span class="help"></span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            {{-- v-model="otherDetails.tyreBrand"--}}
                                                                            <input type="text"
                                                                                   title="The tyre make e.g Good Year"
                                                                                   class="form-control view_mode"
                                                                                   maxlength="140"
                                                                                   id="tyreBrand"
                                                                                   name="tyreBrand"
                                                                                   placeholder="e.g Good Year"/>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="frappe-control ">
                                                                <div class="clearfix">
                                                                    <label for="frontTyreSize"
                                                                           class="control-label reqd"
                                                                           style="padding-right: 0px;">
                                                                        Front Tyre Size:
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div>
                                                                                <select type="text"
                                                                                        class="input-with-feedback form-control bold tyre-size view_mode"
                                                                                        required
                                                                                        id="frontTyreSize"
                                                                                        name="frontTyreSize"
                                                                                        autocomplete="off"></select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="frappe-control">
                                                                <div class="clearfix">
                                                                    <label for="rearTyreSize" class="control-label reqd"
                                                                           style="padding-right: 0px;">
                                                                        Rear Tyre Size:
                                                                    </label>
                                                                    <span class="help"></span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div>
                                                                                <select type="text"
                                                                                        class="input-with-feedback form-control bold tyre-size view_mode"
                                                                                        name="rearTyreSize"
                                                                                        id="rearTyreSize"
                                                                                        data-doctype="Work Order"
                                                                                        autocomplete="off"></select>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <p class="help-box small text-muted"></p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </fieldset>

                                            <fieldset class="border p-3">
                                                <legend style="width: inherit;">
                                                    <h4 class="pt-2">Battery</h4>
                                                </legend>
                                                <div class="col-xs-12 col-sm-8 col-md-8">
                                                    <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                                                        <tbody>
                                                        <tr>
                                                            <td class="frappe-control ">
                                                                <label for="batteryBrand" class="control-label reqd"
                                                                       style="padding-right: 0px;">
                                                                    Brand:
                                                                </label>
                                                            </td>
                                                            <td style="width: 25%;">
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div>
                                                                                {{--v-model="otherDetails.batteryBrand"--}}
                                                                                <input type="text"
                                                                                       id="batteryBrand"
                                                                                       name="batteryBrand"
                                                                                       class="input-with-feedback form-control bold view_mode"
                                                                                       data-fieldtype="Link"
                                                                                       data-fieldname="company"
                                                                                       data-doctype="OtherDetails"
                                                                                       autocomplete="off"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="frappe-control">
                                                                <div class="clearfix">
                                                                    <label for="batterySize" class="control-label reqd"
                                                                           style="padding-right: 0px;">Size :</label>
                                                                    <span class="help"></span>
                                                                </div>
                                                            </td>
                                                            <td style="width: 25%;">
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div>
                                                                                <select
                                                                                    class="form-control input-with-feedback view_mode"
                                                                                    data-fieldtype="Link"
                                                                                    data-fieldname="company"
                                                                                    id="batterySize"
                                                                                    name="batterySize"
                                                                                    data-doctype="OtherDetails"></select>
                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="frappe-control ">
                                                                <label for="batteryPower" class="control-label reqd"
                                                                       style="padding-right: 0px;">
                                                                    Power (Volts):
                                                                </label>
                                                            </td>
                                                            <td>
                                                                {{--v-model="otherDetails.batteryPower"--}}
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div class="input-group ">
                                                                                <select type="number"
                                                                                        class="form-control view_mode"
                                                                                        data-fieldtype="Link"
                                                                                        data-fieldname="company"
                                                                                        id="batteryPower"
                                                                                        name="batteryPower"
                                                                                        data-target="Company">
                                                                                    <option value="12">12</option>
                                                                                    <option value="24">24</option>
                                                                                </select>
                                                                                {{--<div
                                                                                        class="input-group-addon align-self-center pr-3 pl-3">

                                                                                </div>--}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>

                                                            <td class="frappe-control"></td>
                                                            <td></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </fieldset>

                                            <div class="mt-5 create_mode">
                                                <button type="submit" id="tms_save_engine"
                                                        class="btn btn-success btn-sm">
                                                    <i class="fas fa-paper-plane"></i> Save
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-6 row">
                                        <div class="col-md-6" v-if="images && images.frontView">
                                            <div class="card text-center my-2">
                                                <div class="form-group">
                                                    <div class="imagePreview"
                                                         :style='{backgroundImage: "url(/storage" + images.frontView.path + ")",}'>
                                                        <p class="img_title fs-2x fw-bold mb-10 text-white">
                                                            Front View
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6" v-if="images && images.rearView">
                                            <div class="card-px text-center my-2">
                                                <div class="form-group">
                                                    <div class="imagePreview"
                                                         :style='{backgroundImage: "url(/storage" + images.rearView.path + ")",}'>
                                                        <p class="img_title fs-2x fw-bold mb-10 text-white">
                                                            Rear View
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6" v-if="images && images.rightView">
                                            <div class="card text-center my-2">

                                                <div class="form-group">
                                                    <div class="imagePreview"
                                                         :style='{backgroundImage: "url(/storage" + images.rightView.path + ")",}'>
                                                        <p class="img_title fs-2x fw-bold mb-10 text-white">
                                                            Right
                                                            View</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6" v-if="images && images.leftView">
                                            <div class="card text-center my-2">

                                                <div class="form-group">
                                                    <div class="imagePreview"
                                                         :style='{backgroundImage: "url(/storage" + images.leftView.path + ")",}'>
                                                        <p class="img_title fs-2x fw-bold mb-10 text-white">
                                                            Left
                                                            View</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <fieldset class="border p-3">
                                <legend style="width: inherit;">
                                    <h4 class="pt-2">Dimensions</h4>
                                </legend>
                                <div id="tms_body_weight_form"
                                     class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                     data-action="{{route('vehicle.body.detail')}}">
                                    <input type="hidden" name="doctype" value="BodyDetails"/>
                                    <input type="hidden" name="headerId" value="{{$reference}}"/>
                                    <input type="hidden" name="weightDetailsId"
                                           value="{{$vehicle->weightDetailsId ?? 0}}"/>
                                    <x-error-view/>
                                    <div class="col-6">
                                        <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                                            <tbody>
                                            <tr>
                                                <td class="frappe-control ">
                                                    <label for="vehicleHeight" class="control-label reqd"
                                                           style="padding-right: 0px;">
                                                        Height (m):
                                                    </label>
                                                </td>
                                                <td colspan="1">
                                                    <div class="control-input-wrapper">
                                                        <div class="control-input">
                                                            <div class="link-field ui-front"
                                                                 style="position: relative;">
                                                                <div>
                                                                    <input type="text"
                                                                           class="input-with-feedback number_input form-control bold view_mode"
                                                                           maxlength="4"
                                                                           data-fieldtype="Link"
                                                                           data-fieldname="company"
                                                                           id="vehicleHeight"
                                                                           name="height"
                                                                           v-model="bodyDetails.height"
                                                                           placeholder=""
                                                                           data-doctype="BodyDetails"/>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="frappe-control">
                                                    <label for="length" class="control-label reqd"
                                                           style="padding-right: 0px;">
                                                        Length (m):
                                                    </label>
                                                </td>
                                                <td>
                                                    <input type="text"
                                                           class="input-with-feedback number_input form-control bold view_mode"
                                                           maxlength="140"
                                                           required
                                                           data-fieldtype="Link"
                                                           data-fieldname="company"
                                                           id="length"
                                                           name="length"
                                                           v-model="bodyDetails.length"
                                                           placeholder=""
                                                           data-doctype="BodyDetails"/>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="frappe-control ">
                                                    <label for="width" class="control-label reqd"
                                                           style="padding-right: 0px;">
                                                        Width (m):
                                                    </label>
                                                </td>
                                                <td colspan="1">
                                                    <div class="control-input-wrapper">
                                                        <div class="control-input">
                                                            <div class="link-field ui-front"
                                                                 style="position: relative;">
                                                                <div>
                                                                    <input type="text"
                                                                           class="input-with-feedback number_input form-control bold view_mode"
                                                                           maxlength="140"
                                                                           required
                                                                           data-fieldtype="Link"
                                                                           data-fieldname="company"
                                                                           id="width"
                                                                           name="width"
                                                                           v-model="bodyDetails.width"
                                                                           placeholder=""
                                                                           data-doctype="BodyDetails"/>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="frappe-control"></td>
                                                <td></td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                    <h4>Interior</h4>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="frappe-control ">
                                                    <label for="vehicleWidth" class="control-label reqd"
                                                           style="padding-right: 0px;">
                                                        Seat Capacity:
                                                    </label>
                                                </td>
                                                <td>
                                                    <div class="control-input-wrapper">
                                                        <div class="control-input">
                                                            <div class="link-field ui-front"
                                                                 style="position: relative;">
                                                                <div>
                                                                    <input type="text"
                                                                           class="input-with-feedback form-control bold view_mode"
                                                                           maxlength="15"
                                                                           id="seatCapFront"
                                                                           name="seatCapFront"
                                                                           v-model="bodyDetails.seatCapFront"
                                                                           placeholder=""
                                                                           data-doctype="BodyDetails"
                                                                           autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="frappe-control">
                                                    {{--<div class="clearfix" style="display: none;">
                                                        <label for="seatCapRear" class="control-label reqd"
                                                               style="padding-right: 0px;">
                                                            Seat Cap/Rear:
                                                        </label>
                                                        <span class="help"></span>
                                                    </div>--}}
                                                </td>
                                                <td>
                                                    {{--<div class="control-input-wrapper" style="display: none;">
                                                        <div class="control-input">
                                                            <div class="link-field ui-front" style="position: relative;">
                                                                <div>
                                                                    <input type="number"
                                                                           class="input-with-feedback form-control bold"
                                                                           value="0"
                                                                           v-model="bodyDetails.seatCapRear"
                                                                           name="seatCapRear"
                                                                           id="seatCapRear"
                                                                           data-doctype="BodyDetails"
                                                                           autocomplete="off">
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <p class="help-box small text-muted"></p>
                                                    </div>--}}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="frappe-control">
                                                    {{-- <label for="volumeOfBootTanker" class="control-label reqd" style="display: none;"
                                                            style="padding-right: 0px;">
                                                         Vol. Boot/Tanker:
                                                     </label>--}}
                                                </td>
                                                <td>
                                                    {{--<div class="control-input-wrapper" style="display: none;">
                                                        <div class="control-input">
                                                            <div class="link-field ui-front" style="position: relative;">
                                                                <div>
                                                                    <input type="text"
                                                                           v-model="bodyDetails.volumeOfBootTanker"
                                                                           class="input-with-feedback form-control bold"
                                                                           value="300"
                                                                           id="volumeOfBootTanker"
                                                                           name="volumeOfBootTanker"
                                                                           placeholder=""
                                                                           data-doctype="BodyDetails"
                                                                           autocomplete="off"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>--}}
                                                </td>
                                                <td class="frappe-control">
                                                    {{--<div class="clearfix" style="display: none;">
                                                        <label for="numberOfSeats" class="control-label reqd"
                                                               style="padding-right: 0px;">
                                                            No. Of Seats :
                                                        </label>
                                                        <span class="help"></span>
                                                    </div>--}}
                                                </td>
                                                <td>
                                                    {{-- <div class="control-input-wrapper">
                                                         <div class="control-input">
                                                             <div class="link-field ui-front" style="position: relative;">
                                                                 <div>
                                                                     <input type="text"
                                                                            class="input-with-feedback form-control bold"
                                                                            value="0"
                                                                            data-fieldtype="Link"
                                                                            data-fieldname="company"
                                                                            id="numberOfSeats"
                                                                            name="numberOfSeats"
                                                                            data-doctype="BodyDetails"
                                                                            v-model="bodyDetails.numberOfSeats"
                                                                            placeholder=""/>
                                                                 </div>

                                                             </div>
                                                         </div>
                                                     </div>--}}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                    <h4>Exterior</h4>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="frappe-control ">
                                                    <label for="distanceAxle1" class="control-label"
                                                           style="padding-right: 0px;">
                                                        Dist Axle 1:
                                                    </label>
                                                </td>
                                                <td>
                                                    <div class="control-input-wrapper">
                                                        <div class="control-input">
                                                            <div class="link-field ui-front"
                                                                 style="position: relative;">
                                                                <input type="text"
                                                                       class="input-with-feedback form-control bold view_mode"
                                                                       maxlength="140"
                                                                       id="distanceAxle1"
                                                                       name="distanceAxle1"
                                                                       data-doctype="BodyDetails"
                                                                       v-model="bodyDetails.distanceAxle1"
                                                                       placeholder=""
                                                                       data-target="Company">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="frappe-control ">
                                                    <label for="distanceAxle2" class="control-label"
                                                           style="padding-right: 0px;">
                                                        Dist Axle 2:
                                                    </label>
                                                </td>
                                                <td>
                                                    <div class="control-input-wrapper">
                                                        <div class="control-input">
                                                            <div class="link-field ui-front"
                                                                 style="position: relative;">
                                                                <input type="text"
                                                                       class="input-with-feedback form-control bold view_mode"
                                                                       maxlength="140"
                                                                       id="distanceAxle2"
                                                                       name="distanceAxle2"
                                                                       data-doctype="BodyDetails"
                                                                       v-model="bodyDetails.distanceAxle2"
                                                                       placeholder=""/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td class="frappe-control ">
                                                    <label for="distanceAxle3" class="control-label"
                                                           style="padding-right: 0px;">
                                                        Dist Axle 3:
                                                    </label>
                                                </td>
                                                <td>
                                                    <div class="control-input-wrapper">
                                                        <div class="control-input">
                                                            <div class="link-field ui-front"
                                                                 style="position: relative;">
                                                                <input type="text"
                                                                       class="input-with-feedback form-control bold view_mode"
                                                                       maxlength="140"
                                                                       id="distanceAxle3"
                                                                       name="distanceAxle3"
                                                                       v-model="bodyDetails.distanceAxle3"
                                                                       placeholder=""
                                                                       data-doctype="BodyDetails"
                                                                       data-target="Company">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="frappe-control ">
                                                    <label for="distanceAxle5" class="control-label"
                                                           style="padding-right: 0px;">
                                                        Dist Axle 4 Rda/Ult:
                                                    </label>
                                                </td>
                                                <td>
                                                    <div class="control-input-wrapper">
                                                        <div class="control-input">
                                                            <div class="link-field ui-front"
                                                                 style="position: relative;">
                                                                <input type="text"
                                                                       class="input-with-feedback form-control bold view_mode"
                                                                       maxlength="140"
                                                                       id="distanceAxle4"
                                                                       name="distanceAxle4"
                                                                       v-model="bodyDetails.distanceAxle4"
                                                                       placeholder=""/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                    <h4>Weight</h4>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="frappe-control ">
                                                    <label for="tareWeight" class="control-label reqd"
                                                           style="padding-right: 0px;">
                                                        Net Weight (kg):
                                                    </label>
                                                </td>
                                                <td colspan="1">
                                                    <div class="control-input-wrapper">
                                                        <div class="control-input">
                                                            <div class="link-field ui-front"
                                                                 style="position: relative;">
                                                                <div>
                                                                    <input type="text"
                                                                           required
                                                                           class="input-with-feedback form-control bold view_mode weight_control"
                                                                           maxlength="140"
                                                                           data-fieldtype="Link"
                                                                           data-fieldname="company"
                                                                           id="tareWeight"
                                                                           name="tareWeight"
                                                                           v-model="weightDetails.tareWeight"
                                                                           placeholder=""
                                                                           data-doctype="WeightDetails"/>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="frappe-control">
                                                    <label for="grossWeight" class="control-label reqd"
                                                           style="padding-right: 0px;">
                                                        Gross Weight (kg):
                                                    </label>
                                                </td>
                                                <td>
                                                    <input type="text"
                                                           class="input-with-feedback form-control bold view_mode weight_control"
                                                           maxlength="140"
                                                           required
                                                           data-fieldtype="Link"
                                                           data-fieldname="company"
                                                           id="grossWeight"
                                                           name="grossWeight"
                                                           v-model="weightDetails.grossWeight"
                                                           placeholder=""
                                                           data-doctype="WeightDetails"/>
                                                </td>
                                            </tr>

                                            {{--  <tr>
                                                  <td class="frappe-control ">
                                                      <label for="trailerWeight2" class="control-label reqd"
                                                             style="padding-right: 0px;">
                                                          Trailer Weight 2:
                                                      </label>
                                                  </td>
                                                  <td>
                                                      <div class="control-input-wrapper">
                                                          <div class="control-input">
                                                              <div class="link-field ui-front" style="position: relative;">
                                                                  <div>
                                                                      <input type="text"
                                                                             class="input-with-feedback form-control bold"
                                                                             maxlength="15"
                                                                             id="trailerWeight2"
                                                                             name="trailerWeight2"
                                                                             v-model="weightDetails.trailerWeight2"
                                                                             placeholder=""
                                                                             autocomplete="off">
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </td>
                                                  <td class="frappe-control">
                                                      <div class="clearfix">
                                                          <label for="trailerWeight3" class="control-label reqd"
                                                                 style="padding-right: 0px;">
                                                              Trailer Weight 3:
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
                                                                             class="input-with-feedback form-control bold"
                                                                             maxlength="15"
                                                                             placeholder=""
                                                                             v-model="weightDetails.trailerWeight3"
                                                                             name="trailerWeight3"
                                                                             id="trailerWeight3"
                                                                             data-doctype="WeightDetails"
                                                                             autocomplete="off">
                                                                  </div>

                                                              </div>
                                                          </div>
                                                          <p class="help-box small text-muted"></p>
                                                      </div>
                                                  </td>
                                              </tr>

                                              <tr>
                                                  <td class="frappe-control ">
                                                      <label for="trailerWeight4" class="control-label reqd"
                                                             style="padding-right: 0px;">
                                                          Trailer Weight 4:
                                                      </label>
                                                  </td>
                                                  <td colspan="1">
                                                      <div class="control-input-wrapper">
                                                          <div class="control-input">
                                                              <div class="link-field ui-front" style="position: relative;">
                                                                  <div>
                                                                      <input type="text"
                                                                             class="input-with-feedback form-control bold"
                                                                             maxlength="140"
                                                                             data-fieldtype="Link"
                                                                             data-fieldname="company"
                                                                             id="trailerWeight4"
                                                                             name="trailerWeight4"
                                                                             v-model="weightDetails.trailerWeight4"
                                                                             placeholder=""
                                                                             data-doctype="WeightDetails"/>
                                                                  </div>

                                                              </div>
                                                          </div>
                                                      </div>
                                                  </td>
                                                  <td class="frappe-control"></td>
                                                  <td></td>
                                              </tr>--}}

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </fieldset>
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
                                    <table class="">
                                        <tbody>
                                        <tr>
                                            <td class="frappe-control d-none">
                                                <label class="app-field-label reqd"
                                                       for="staff_no"> :
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
                                                                <select class="form-control form-control-sm view_mode"
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
                                                                                class="form-control form-control-sm"
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
                                                                                class="form-control form-control-sm"
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
                                            <th v-if="documents && documents.purchase_order">Document No.</th>
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

                        <div class="tab-pane fade" id="accessoriesTab" role="tabpanel">
                            <div class="container-fluid pl-0 mt-5">
                                <div id="tms_accessories_form"
                                     name="tms_accessories_form"
                                     class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                     action="{{route('vehicle.accessories.save')}}">
                                    <input type="hidden" name="doctype" value="CostingDetails"/>
                                    <input type="hidden" name="headerId" value="{{$reference}}"/>
                                    <input type="hidden" name="accessoryHeaderId"
                                           value="{{$vehicle->accessoryHeaderId ?? 0}}"/>

                                    <x-error-view/>
                                    <div class="d-flex justify-content-end">
                                        <div class="create_mode">
                                            <button type="submit" id="saveVehicleAccessories"
                                                    class="btn btn-success btn-sm">
                                                <i class="fas fa-paper-plane"></i>
                                                <span class="indicator-label">
                Save
            </span>
                                                <span class="indicator-progress">
                Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="container-fluid mt-5">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="row">

                                                    <div class="col">
                                                        <table
                                                            class="table table-row-dashed align-middle gs-0 table-bordered">
                                                            <thead>
                                                            <tr class="bg-dark">
                                                                <th class="pl-2">Item</th>
                                                                <th>Present</th>
                                                                <th class="pr-2">Not Present</th>
                                                                <th class="pr-2">Remarks</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($accessories as $key => $accessory)
                                                                @if(($key%2) == 0)
                                                                    <tr>
                                                                        <td class="pl-2"
                                                                            style="width: 35%;">{{$accessory->name}}</td>
                                                                        <td><input type="radio" value="YES" required
                                                                                   name="{{str_replace(' ','', $accessory->code)}}">
                                                                        </td>
                                                                        <td><input type="radio" value="NO" required
                                                                                   name="{{str_replace(' ','', $accessory->code)}}">
                                                                        </td>
                                                                        <td style="width: 45%;">
                                                                            <input typeof="text"
                                                                                   name="COMMENT_{{str_replace(' ','', $accessory->code)}}"
                                                                                   class="form-control form-control-sm"/>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="col">
                                                        <table
                                                            class="table table-row-dashed align-middle gs-0 table-bordered">
                                                            <thead>
                                                            <tr class="bg-dark">
                                                                <th class="pl-2">Item</th>
                                                                <th>Present</th>
                                                                <th class="pr-2">Not Present</th>
                                                                <th class="pr-2">Remarks</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($accessories as $key => $accessory)
                                                                @if(($key%2) != 0)
                                                                    <tr>
                                                                        <td class="pl-2" style="width: 35%;">
                                                                            {{$accessory->name}}
                                                                        </td>
                                                                        <td><input type="radio" required value="YES"
                                                                                   name="{{str_replace(' ','', $accessory->code)}}">
                                                                        </td>
                                                                        <td><input type="radio" required value="NO"
                                                                                   name="{{str_replace(' ','', $accessory->code)}}">
                                                                        </td>
                                                                        <td style="width: 45%;">
                                                                            <input typeof="text"
                                                                                   name="COMMENT_{{str_replace(' ','', $accessory->code)}}"
                                                                                   class="form-control form-control-sm">
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach

                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                <form class=""
                                      name="newFleetEntryMovementForm"
                                      action=""
                                      id="newFleetEntryMovementForm"
                                      method="post">
                                    @csrf
                                    <input type="hidden" name="relatedReference" id="relatedReference"
                                           value="{{$relatedReference ?? ''}}"/>
                                    <div class="errorTxt"></div>
                                    <x-error-view></x-error-view>

                                    <label class="app-required-marker"></label>

                                    <div class="row">
                                        <div class="col-6">
                                            <fieldset style="" class="form-group border p-3">
                                                <legend>General Information:</legend>
                                                <table class="app_form_table table">
                                                    <tr>
                                                        <td>
                                                            <label class="app-field-label">
                                                                Registration No.
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="app-field-input">
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                           id="vehicleRegistration"
                                                                           required
                                                                           autocomplete="off"
                                                                           name="vehicleRegistration"
                                                                           class="form-control form-control-sm"/>
                                                                    <div class="input-group-append">
                                                                        <button type="button"
                                                                                id="vehicleDetailsBtn"
                                                                                class="btn btn-sm btn-success">
                                                                            <i class="fas fa-search"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="pl-5">
                                                            <label class="app-field-label">
                                                                Machinery Type.
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <select disabled name="machineryType"
                                                                    class="form-select form-select-sm">
                                                                <option selected value="VEHICLE">VEHICLE</option>
                                                                <option value="PLANT EQUIPMENT">PLANT EQUIPMENT</option>
                                                                <option value="BOAT">BOAT</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="app-field-label">
                                                                Meter Date :
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                        </td>
                                                        <div class="app-field-input">
                                                            <div class="input-group">
                                                                <input type="text"
                                                                       readonly
                                                                       value="{{Carbon::now()->format('d/m/Y')}}"
                                                                       name="vehicleRegistration"
                                                                       class="form-control form-control-sm"/>
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text">
                                                                        <i class="fas fa-calender"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </tr>
                                                </table>
                                            </fieldset>

                                        </div>
                                        <div class="col-6">
                                            <fieldset style="" class="form-group border p-3">
                                                <legend>Odometer Information:</legend>
                                                <table class="app_form_table table" id="vehicleTable">
                                                    <tr>
                                                        <td>
                                                            <label class="app-field-label">
                                                                Opening Reading (Km)
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="app-field-input">
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                           id="vehOpeningReading"
                                                                           required
                                                                           name="vehOpeningReading"
                                                                           class="form-control"/>
                                                                    <div class="input-group-addon">
                                                                        <div>
                                                                            <i class="fas fa-dashboard"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="app-field-label">
                                                                Current Reading (Km)
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="app-field-input">
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                           id="vehClosingReading"
                                                                           required
                                                                           name="vehClosingReading"
                                                                           class="form-control"/>
                                                                    <div class="input-group-addon">
                                                                        <div>
                                                                            <i class="fas fa-dashboard"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="app-field-label">
                                                                Difference (Km)
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="app-field-input">
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                           id="vehDifference"
                                                                           required
                                                                           name="vehDifference"
                                                                           class="form-control"/>
                                                                    <div class="input-group-addon">
                                                                        <div>
                                                                            <i class="fas fa-dashboard"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <table class="app_form_table table" id="OtherMachineryTable"
                                                       style="display: none">
                                                    <tr>
                                                        <td>
                                                            <label class="app-field-label">
                                                                Start Hour (Hrs)
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="app-field-input">
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                           id="openingReading"
                                                                           required
                                                                           name="openingReading"
                                                                           class="form-control"/>
                                                                    <div class="input-group-addon">
                                                                        <div>
                                                                            <i class="fas fa-dashboard"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="app-field-label">
                                                                Current Reading (Hrs)
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="app-field-input">
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                           id="closingReading"
                                                                           required
                                                                           name="closingReading"
                                                                           class="form-control"/>
                                                                    <div class="input-group-addon">
                                                                        <div>
                                                                            <i class="fas fa-dashboard"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="app-field-label">
                                                                Difference (Hrs)
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="app-field-input">
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                           id="difference"
                                                                           required
                                                                           name="difference"
                                                                           class="form-control"/>
                                                                    <div class="input-group-addon">
                                                                        <div>
                                                                            <i class="fas fa-dashboard"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </fieldset>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <table class="app_form_table table">
                                            <thead>
                                            <tr>
                                                <th>Start Odometer</th>
                                                <th>Closing Odometer</th>
                                                <th>Km Done</th>
                                                <th>Place From</th>
                                                <th>Place To</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th>
                                                    <input class="form-control"/>
                                                </th>
                                                <th>Closing Odometer</th>
                                                <th>Km Done</th>
                                                <th>Place From</th>
                                                <th>Place To</th>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <table class="app_form_table table">
                                            <tr>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <label class="app-field-label" data-field="typeia">
                                                        Comments
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background: none;" colspan="4">
                                                    <div class="app-field-input">
                                                    <textarea name="comments" id="comments"
                                                              class="form-control"></textarea>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                </form>
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
        window.vehicle = `{!! $vehicle !!}`;
    </script>

    <script type="text/javascript" src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script type="text/javascript" src="{{asset('libs/handsontable/handsontable.full.min.js')}}"></script>

    <script
        src="{{asset('application/modules/vehicleManagement/assets/js/vehicle_over_view.js').'?v='.Carbon::now()->format('his')}}"></script>
    <script
        src="{{asset('application/modules/userManagement/employee.search.js').'?v='.Carbon::now()->format('his')}}"></script>
    <script>
        $(document).ready(function () {
            let elements = document.querySelectorAll('.view_mode');
            let elementsOnCreate = document.querySelectorAll('.create_mode');

            elements.forEach(function (element) {
                element.setAttribute('disabled', 'disabled');
            });

            elementsOnCreate.forEach(function (element) {
                element.style.display = 'none';
            });

            setInterval(function () {
                $("#vehicleLocation").attr('disabled', true);

                const registrationNumber = document.querySelector('#registrationNumber');
                if (registrationNumber && registrationNumber.value) {
                    registrationNumber.setAttribute('disabled', 'disabled');
                    $('#vehicleRegistration').val(registrationNumber.value).attr('readonly', true);
                }
            }, 600)

        });

    </script>
@endpush
