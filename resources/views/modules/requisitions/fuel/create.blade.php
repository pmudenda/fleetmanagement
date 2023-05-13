@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@push('styles')
    <link href="{{asset("assets/plugins/select2/css/select2.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css")}}" rel="stylesheet"
          type="text/css"/>
@endpush
@section('content')

    <x-content-header/>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h4>New Fuel Requisition</h4>
                </div>
                <div id="actionButtonsContainer" class="card-toolbar justify-content-end">
                    <button type="button" id="submitRequisitionBtn" class="btn btn-success btn-sm mr-3 when_valid"
                            disabled>
                        <i class="fas fa-save"></i> Submit
                    </button>
                    <button type="button" id="resetRequisitionBtn" class="btn btn-danger btn-sm mr-3">
                        <i class="fas fa-undo"></i> Cancel
                    </button>

                </div>
            </div>

            <div class="card-body pb-4 min-h-600px pt-0">

                <x-error-view/>

                <form name="fuelRequisitionForm" id="fuelRequisitionForm" action="{{route('save.fuel.requisition')}}"
                      method="post">
                    @csrf
                    <div class="card-body user-data">
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
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 app-field-label field-required"
                                                            for="staff_no">Registration #:
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <div class="input-group">
                                                                <input type="text"
                                                                       data-action="{{route('requisition.vehicle.details')}}"
                                                                       class="form-control form-control-sm"
                                                                       autocapitalize="characters"
                                                                       id="vehicle_registration"
                                                                       placeholder="Vehicle Reg e.g AAB 6757"
                                                                       name="vehicle_registration" required>
                                                                <div class="input-group-addon">
                                                                    <button type="button" id="vehicleSearchBtn"
                                                                            name="vehicleSearchBtn"
                                                                            class="btn btn-success btn-sm border-radius-0">
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
                                                        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                                            <input type="hidden"
                                                                   class="form-control form-control-sm"
                                                                   id="vehicle_description"
                                                                   name="vehicle_description"
                                                                   required readonly>
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
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 control-input-wrapper">
                                                            <div class="control-input">
                                                                <div class="link-field ui-front"
                                                                     style="position: relative;">
                                                                    <label class="form-check-inline">
                                                                        <input type="radio"
                                                                               id="costOnCostCentre"
                                                                               class="list-row-checkbox bold mr-3 when_valid"
                                                                               name="CostAssignedTo"
                                                                               value="CostCenterBasedRequisition"
                                                                               checked>
                                                                        Cost Center
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   id="cost_centre_code"
                                                                   value="{{$costCenter->code_cost_center ?? ''}}"
                                                                   name="cost_centre_code"
                                                                   required readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-10">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   id="cost_center_name"
                                                                   value="{{$costCenter->description ?? ''}}"
                                                                   name="cost_center_name"
                                                                   required readonly>
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
                                                            class=" col-xs-12 col-sm-6 col-md-5 col-lg-4 control-input-wrapper">
                                                            <div class="control-input">
                                                                <div class="link-field ui-front"
                                                                     style="position: relative;">
                                                                    <label class="form-check-inline">
                                                                        <input type="radio"
                                                                               id="projectInput"
                                                                               class="list-row-checkbox bold mr-3 when_valid"
                                                                               autocomplete="off"
                                                                               name="CostAssignedTo"
                                                                               value="ProjectBasedRequisition">
                                                                        Project
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <select disabled type="text" name="project_code"
                                                                    class="form-select mt-1 project-code-ajax"
                                                                    id="project_code">
                                                            </select>
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
                                                            for="staff_name">
                                                            Requisition Type:
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <select name="requisition_type" id="requisition_type"
                                                                    disabled
                                                                    class="form-control form-select-sm when_valid"
                                                                    required>
                                                                <option value=""> --Select--</option>
                                                                @foreach ($requisitionTypes as $requisitionType)
                                                                    <option
                                                                        value="{{$requisitionType->code}}">{{$requisitionType->name}}</option>
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
                                                            for="staff_name">
                                                            Current Odometer Reading :
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <input type="text"
                                                                   data-url="{{route('fuel.odometer.validation')}}"
                                                                   data-validation-method="fuelRequisitionOdometerReading"
                                                                   data-params="[odometerNumber, vehicleRegistration]"
                                                                   class="form-control form-control-sm when_valid"
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

                                    <div class="row d-none" id="outOfTown">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="mobile_no">Departure Date:</label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <input type="date" class="form-control form-control-sm"
                                                                   id="departure_date"
                                                                   min="{{ date('Y-m-d', strtotime(Carbon::now())) }}"
                                                                   name="departure_date"/>
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
                                                            for="request_date">Return Date:</label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <input type="date" class="form-control form-control-sm"
                                                                   id="return_date"
                                                                   min="{{ date('Y-m-d', strtotime(Carbon::now())) }}"
                                                                   name="return_date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{--<div class="row d-none">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="mobile_no">Departure Date:</label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <input type="date" class="form-control form-control-sm"
                                                                   id="departure_date"
                                                                   min="{{ date('Y-m-d', strtotime(\Carbon\Carbon::now())) }}"
                                                                   name="departure_date"/>
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
                                                            for="request_date">Return Date:</label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <input type="date" class="form-control form-control-sm"
                                                                   id="return_date"
                                                                   min="{{ date('Y-m-d', strtotime(\Carbon\Carbon::now())) }}"
                                                                   name="return_date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>--}}

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="mobile_no">Allocation Per Week:</label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control form-control-sm"
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
                                                            for="request_date">Request Date:</label>
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
                                            <div class="container-fluid pl-0">
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
                                                                   value="{{Carbon::now()->add('days', $daysToNextRefuel)->format('d/m/Y')}}"
                                                                   name="next_fuel_date"
                                                                   readonly required>
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
                                                            for="mobile_no">Purpose:</label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8">
                                                        <textarea type="text"
                                                                  id="justification"
                                                                  name="justification"
                                                                  disabled
                                                                  style="height: 129px;"
                                                                  class="form-control form-control-sm when_valid"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div id="vehicleDetailsContainer" style="display: none;"
                                         class="col-xs-12 col-sm-12 col-md-12">
                                        <h1>Vehicle Details</h1>
                                        <table class="table">
                                            <tbody id="vehicleDetails" class="vehicleDetails">
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="image_view" class="card text-center py-5 my-2" style="display: none;">
                                        <h2 class="fs-2x fw-bold mb-10">Front View</h2>
                                        <div class="form-group">
                                            <div class="imagePreview"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div id="materialDetailsContainer" class="table-responsive mt-3">
                                <table id="materialDetailsTable" class="table table-bordered">
                                    <thead>
                                    <tr class="bg-dark">
                                        <th>Material Description</th>
                                        <th class="project_view_item d-none">Project Number</th>
                                        <th>Qty</th>
                                        <th>Unit Of Measure</th>
                                        <th>Price (ZMW)</th>
                                        <th>Amount(ZMW)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <span data-material-input="material_description"
                                                  id="material_description"></span>
                                            <input type="hidden" name="material_description">
                                            <input type="hidden" name="material_article_code">
                                        </td>
                                        <td class="project_view_item d-none">
                                            <input type="text" name="projectCode" readonly value="000000"
                                                   class="form-control form-control-sm border-0"/>
                                        </td>
                                        <td>
                                            <input type="number" name="material_quantity"
                                                   max=""
                                                   disabled
                                                   id="material_quantity"
                                                   class="form-control form-control-sm when_valid"/>
                                        </td>
                                        <td>
                                            <span data-material-input="unit_of_measure" id="unit_of_measure"></span>
                                            <input type="hidden" name="unit_of_measure">
                                        </td>
                                        <td>
                                            <span data-material-input="material_price" id="material_price"></span>
                                            <input type="hidden" name="material_price" value="12">
                                        </td>
                                        <td>
                                            <span data-material-input="material_amount" id="material_amount"></span>
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
                                            <span class="text-bold text-right" id="totalQty"></span></td>
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

                <input type="hidden" value="{{ route('user.search') }}" id="newUserSearchUrl">
                <input type="hidden" value="{{route('search.project')}}" id="projects_url">
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <script>
        (function (tmsApp, $) {
            function removeSubmissionAndDetailsOptions() {
                let elements = document.querySelectorAll('.when_valid');
                elements.forEach(function (element) {
                    element.setAttribute('disabled', 'disabled');
                });

                document.querySelector('#vehicleDetailsContainer').style.display = 'none';
                //document.querySelector('#materialDetailsContainer').style.display = 'none';
                document.querySelector('#image_view').style.display = 'none';

                $('tbody#vehicleDetails').html('');
                document.querySelector('[name="fuel_allocation"]').value = '';

                $("#material_description").text(tmsApp.formatMoney('0', 2));
                $('input[name="material_description"]').val(tmsApp.formatMoney('0', 2));
            }

            function enableSubmissionAndDetailsOptions() {

                let elements = document.querySelectorAll('.when_valid');

                elements.forEach(function (element) {
                    element.removeAttribute('disabled');
                });

                document.querySelector('#vehicleDetailsContainer').style.display = null;
                //document.querySelector('#materialDetailsContainer').style.display = null;
                document.querySelector('#image_view').style.display = null;
            }

            function populateVehicleDetails(payload) {
                let vehicle = payload['vehicle'];
                let article = payload['article'];
                let images = payload['images'];
                let vehicle_state = payload['vehicle_state'];

                if (!vehicle || !vehicle.brand_name) {
                    return;
                }

                if (typeof vehicle.fuel_allocation === 'undefined' || vehicle.fuel_allocation == null || vehicle.fuel_allocation === "0") {

                    tmsApp.showSystemMessage("Vehicle Details Incomplete",
                        'Vehicle has no Fuel Allocation, Request System Administrator to assign allocation', () => {
                        }, "error")

                    return;
                }

                // BAD 1010
                if (vehicle['on_boarding_status'] != '030') {
                    tmsApp.showSystemMessage("Incomplete Vehicle Details",
                        `The vehicle ${vehicle['registration_number']} is ${vehicle_state}. Please Contact Fleet Master
                            System Administrator on 3309,3350,3351,3306, fleetmaster@zesco.com`,
                        () => {
                        },
                        "error");
                    return;
                }

                let vLabel = vehicle['body_type_name'] + ' ' + vehicle['brand_name'] + ' ' + vehicle['model_name'] + ' ' + vehicle['model_code'];
                $("#vehicle_description").val(vLabel);
                let row = `<tr>
                                    <th>Make</th><td id="make">${vehicle.brand_name}</td>
                               </tr>
                               <tr>
                                    <th>Model</th><td id="model">${vehicle.model_name} ${vehicle.model_code}</td>
                               </tr>
                               <tr style="">
                                     <th>Type</th><td id="registration">${vehicle['body_type_name']}</td>
                                </tr>`;

                $('tbody#vehicleDetails').html(row);

                if (vehicle.fuel_allocation) {
                    let perWeekAllocation = vehicle.fuel_allocation * 7;
                    document.querySelector('[name="fuel_allocation"]').value = perWeekAllocation ?? 0;
                    document.querySelector('[name="material_quantity"]').value = perWeekAllocation ?? 0;
                    document.querySelector('[name="material_quantity"]').setAttribute('max', perWeekAllocation);
                    $('#totalQty').text(tmsApp.numberFormat(perWeekAllocation));
                }

                enableSubmissionAndDetailsOptions();

                if (article) {

                    /* Material Description and name */
                    $("#material_description").text(article['name']);
                    $('input[name="material_description"]').val(article['name']);
                    $('input[name="material_article_code"]').val(article['code']);

                    /* Unit Of Measure */
                    $("#unit_of_measure").text(article['short_name']);
                    $('input[name="unit_of_measure"]').val(article['short_name']);


                    //$("#material_amount").text(tmsApp.formatMoney('', 2));
                    //$('input[name="material_amount"]').val(tmsApp.formatMoney('', 2)).trigger('change');

                    /* Material Price*/
                    $("#material_price").text(tmsApp.formatMoney(article['price'], 2));
                    $('input[name="material_price"]').val(article['price']).change();
                }

                if (images && images.length > 0) {
                    let frontViewImages = images.filter((image) => {
                        return image.file_type === 'Front View';
                    })
                    let imagePath = frontViewImages[0]?.path;
                    document.querySelector(".imagePreview").style.backgroundImage = "url(/storage" + imagePath + ")";
                }

            }

            function findVehicle() {
                const numberPlate = document.querySelector('#vehicle_registration').value
                let formData = new FormData();
                formData.append('vehicle_registration', numberPlate);

                tmsApp.asyncGetFormData(
                    $('#vehicle_registration').attr('data-action') + '?vehicle_registration=' + numberPlate,
                    formData,
                    function (response_data) {
                        if (response_data.success === 'true' || response_data.success === true) {
                            populateVehicleDetails(response_data.payload);
                        } else {
                            removeSubmissionAndDetailsOptions();
                            tmsApp.showToast('No Vehicle Found, Check your input and try again', 'error');
                        }
                    },
                    function (xhr) {
                        tmsApp.showToast('We could not complete processing your request, please try again later')
                    }
                )
            }

            function eventHandler(element, e) {
                let $table = $('#materialDetailsTable');

                switch (element.name) {
                    case 'material_price':
                        // line total = new material price multiplied by quantity value
                        let totalAmount = tmsApp.getFloat(element.value) * tmsApp.getFloat($(element).closest("tr").find("input[name=material_quantity]").val());
                        $(element).closest("tr").find("input[name=material_amount]").val(totalAmount).change();
                        $(element).closest("tr").find("#material_amount").text(tmsApp.numberFormat(totalAmount));
                        break;
                    case 'material_quantity':

                        let summaryTotalQty = 0;
                        $table.find("input[name=material_quantity]").each(function (i, it) {
                            summaryTotalQty += tmsApp.getFloat(it.value);
                        });

                        $('#totalQty').text(tmsApp.numberFormat(summaryTotalQty));
                        // line total = new quantity value multiplied by material price
                        let lineAmountTotal = tmsApp.getFloat(element.value) * tmsApp.getFloat($(element).closest("tr").find("input[name=material_price]").val());
                        $(element).closest("tr").find("input[name=material_amount]").val(lineAmountTotal).change();
                        $(element).closest("tr").find("#material_amount").text(tmsApp.numberFormat(lineAmountTotal));
                        break;
                    case 'material_amount':
                        // calculate new footer total
                        let summaryTotal = 0;
                        $table.find("input[name=material_amount]").each(function (i, it) {
                            summaryTotal += tmsApp.getFloat(it.value);
                        });
                        $('#totalAmount').text(tmsApp.numberFormat(summaryTotal, 2));
                    default:
                        break;
                }
            }

            $('#vehicle_registration').on('keyup paste enter', function () {
                if (this.value && this.value.replace('_', '').length < 8) {
                    return;
                }
                setTimeout(function () {
                    removeSubmissionAndDetailsOptions();
                    findVehicle();
                }, 300);
            });

            $('#vehicleSearchBtn').on('click', function () {
                if (document.querySelector('#vehicle_registration').value && document.querySelector('#vehicle_registration') < 8) {
                    return;
                }
                removeSubmissionAndDetailsOptions();
                findVehicle();
            });


            Inputmask({
                "mask": "AAA 9999"
            }).mask("#vehicle_registration");

            tmsApp.appFormValidator('form[name="fuelRequisitionForm"]',
                {
                    'requisition_type': {
                        required: true,
                    },
                    fuel_allocation: {
                        required: true
                    },
                    project_code: {
                        required: '#projectInput:checked'
                    },
                    'cost_centre_code': {
                        required: '#costOnCostCentre:checked'
                    },
                    justification: {
                        required: true,
                        minlength: 15,
                        maxlength: 255
                    },
                    projectCode: {
                        required: true
                    },
                    material_quantity: {
                        required: true
                    }
                },
                {
                    'requisition_type': {
                        required: "You have not declared the type of requisition"
                    },
                    'fuel_allocation': {
                        required: "The vehicle does not have a valida fuel allocation"
                    },
                    'dateOpened': {
                        required: "You must specify date task was opened"
                    },
                    'justification': {
                        required: "Purpose for requisition is mandatory",
                        minlength: "The reason needs to be at least {0} characters!",
                        maxlength: "The reason must not be more than 255 characters"
                    },
                    projectCode: {
                        required: 'Missing Project Code'
                    },
                    material_quantity: {
                        required: 'You have not declared the quantity being requested for'
                    },
                    project_code: {
                        required: 'Project Code is missing'
                    },
                    odometer_reading: {
                        required: 'You must declare the odometer reading'
                    }
                }
            );

            $('#submitRequisitionBtn').on('click', function () {
                let $form = document.forms['fuelRequisitionForm'];
                if (!$($form).valid()) {
                    return;
                }

                $('.print-error-msg').css('display', 'none');
                let formData = new FormData($form);
                tmsApp.confirm(
                    'Fuel Requisition',
                    'Are you sure you want to submit this request ?',
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
                                            'Fuel Requisition',
                                            asyncResponse['message'],
                                            function () {
                                                window.location.href = asyncResponse["redirectUrl"]
                                                //window.location.reload();
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
                                            'Fuel Requisition',
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
                                                'Fuel Requisition',
                                                xhr.responseJSON['message']
                                            );
                                        }
                                        return;
                                    }

                                    tmsApp.systemError(
                                        'Fuel Requisition',
                                        'We could not complete processing your request, please try again later');
                                }, 300)
                            }
                        )
                    },
                    function () {
                    }
                );
            })

            $('#resetRequisitionBtn').on('click', function () {
                document.forms['fuelRequisitionForm'].reset();
                removeSubmissionAndDetailsOptions();
            });

            $('select[name="requisition_type"]').on('change', function () {
                if (this.value === '011') {
                    $("#outOfTown").removeClass('d-none');
                } else {
                    $("#outOfTown").addClass('d-none');
                }
            });

            // cost allocation view
            $('input[name="CostAssignedTo"]').on('change', function () {

                const $projectCodeCtrl = document.querySelector('#project_code');
                const $costCentreCodeCtrl = document.querySelector('#cost_centre_code');
                const $costCentreNameCtrl = document.querySelector('#cost_center_name');

                if (this.value === 'CostCenterBasedRequisition') {
                    $($projectCodeCtrl).prop('disabled', true);
                    $($projectCodeCtrl).prop('required', false);
                    $($costCentreCodeCtrl).prop('required', true);
                    $costCentreCodeCtrl.style.display = null;
                    $($costCentreNameCtrl).prop('required', true);
                    $costCentreNameCtrl.style.display = null;
                    $('.project_view_item').addClass('d-none');
                } else if (this.value === 'ProjectBasedRequisition') {
                    $($projectCodeCtrl).prop('disabled', false);
                    $($projectCodeCtrl).prop('required', true);
                    $($costCentreCodeCtrl).prop('required', false);
                    $costCentreCodeCtrl.style.display = 'none';
                    $($costCentreNameCtrl).prop('required', false);
                    $costCentreNameCtrl.style.display = 'none';
                    $('.project_view_item').removeClass('d-none');
                }
            });

            $("[name='odometer_reading']").on('change', function () {
                //setTimeout
                const odometerReading = document.querySelector('#odometer_reading').value;
                const numberPlate = document.querySelector('#odometer_reading').value;
                let formData = new FormData();
                formData.append('odometer_reading', odometerReading);
                formData.append('vehicle_registration', numberPlate);

                const dataSet = document.querySelector('#odometer_reading').dataset;
                tmsApp.asyncPostFormData(
                    ['url'],
                    formData,
                    function (response) {
                        if (!response.success) {
                            removeSubmissionAndDetailsOptions();
                            tmsApp.showToast(response['message'], 'error');
                        }
                    },
                    function () {
                    }
                );

                //tmsApp.showToast('We could not complete processing your request, please try again later')

            });

            $('#materialDetailsTable').on('change', 'select,input', function (e) {
                eventHandler(this, e);
            }).on('keyup', 'select,input,textarea', function (e) {
                eventHandler(this, e);
            }).on('blur', 'input', function (e) {
                if (this.name === 'quantity') {
                    $(this).val(tmsApp.numberFormat(this.value));
                }

            });
        })(window.tmsApp || {}, jQuery)
    </script>
    <script src="{{asset('assets/js/system/project_code.js').'?v='.Carbon::now()->format('his')}}"></script>
@endpush
