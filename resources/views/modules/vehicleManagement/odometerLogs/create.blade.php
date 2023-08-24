@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@push('styles')
    <style>
        th {
            white-space: nowrap;
        }

        table.dataTable.nowrap th,
        table.dataTable.nowrap td {
            white-space: nowrap;
        }
    </style>
@endpush
@section('content')
    <x-content-header :pageTitle="'Odometer Log Entry'"/>
    <section class="content">
        <div class="card mt-10">
            <div class="card-header">
                <div class="card-title">
                    <h4>Odometer Log</h4>
                </div>
            </div>
            <form name="newOdometerLogForm"
                  id="newOdometerLogForm"
                  action="{{route('save.odometer.log')}}"
                  method="post">
                @csrf
                <div class="card-block">
                    <div class="col-md-12">
                        <div class="wizard">
                            <x-error-view></x-error-view>

                            <label class="app-required-marker"></label>

                            <div class="row">
                                <div class="col-6">
                                    <fieldset style="" class="form-group border p-3">
                                        {{-- <legend class="text-bold">General Information:</legend>--}}
                                        <table class="app_form_table table">
                                            <tr>
                                                <td>
                                                    <label class="app-field-label field-required">
                                                        Reg No.
                                                    </label>
                                                </td>
                                                <td>
                                                    <div class="app-field-input">
                                                        <div class="input-group">
                                                            <input type="text"
                                                                   id="vehicleRegistration"
                                                                   required
                                                                   data-action="{{route('odometer.log.vehicle.details')}}"
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
                                                    <label class="app-field-label field-required">
                                                        Machinery Type.
                                                    </label>
                                                </td>
                                                <td>
                                                    <select name="machineryType"
                                                            class="form-select form-select-sm">
                                                        <option selected value="MV">VEHICLE</option>
                                                        {{--<option value="PLANT EQUIPMENT">PLANT EQUIPMENT</option>
                                                        <option value="BOAT">BOAT</option>--}}
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="app-field-label field-required">
                                                        Business Area
                                                    </label>
                                                </td>
                                                <td>
                                                    <input type="text" readonly id="businessArea"
                                                           class="form-control form-control-sm"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="app-field-label field-required">
                                                        User Unit
                                                    </label>
                                                </td>
                                                <td>
                                                    <input type="text" readonly id="userUnit"
                                                           class="form-control form-control-sm"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="app-field-label">
                                                        Date :
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <input type="text"
                                                           readonly
                                                           value="{{Carbon::now()->format('d/m/Y')}}"
                                                           class="form-control form-control-sm"/>
                                                </td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                    <fieldset class="form-group border p-3">
                                        <legend>Date/Period</legend>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="input-group">
                                                    <input
                                                        type="date"
                                                        name="dateFrom"
                                                        max="{{date('Y-m-d', strtotime(Carbon::now()))}}"
                                                        class="form-control form-control-sm"/>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="input-group">
                                                    <input
                                                        max="{{date('Y-m-d', strtotime(Carbon::now()))}}"
                                                        name="dateTo"
                                                        type="date"
                                                        class="form-control form-control-sm "/>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-6">
                                    <fieldset style="" class="form-group border p-3">
                                        {{-- <legend class="text-bold">Odometer Information:</legend>--}}
                                        <table class="app_form_table table" id="vehicleTable">
                                            <tr>
                                                <td>
                                                    <label class="app-field-label field-required">
                                                        Start Odometer (Km)
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
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-dashboard"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="app-field-label field-required">
                                                        End Odometer (Km)
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
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-dashboard"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
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
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-dashboard"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </table>
                                        <table class="app_form_table table d-none" id="OtherMachineryTable"
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
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
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
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
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
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
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
                                {{--<div class="container-fluid">--}}
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="logsTable" class="app_form_table table table-bordered">
                                            <thead>
                                            <tr class="bg-success">
                                                <th style="width: 20%;">Date From - To</th>
                                                <th style="width: 5%;">Fuel Issued (Ltr)</th>
                                                <th style="width: 8%;">Start Odometer</th>
                                                <th style="width: 10%;">Closing Odometer</th>
                                                <th style="width: 10%;">Total Distance</th>
                                                <th style="width: 20%">Place From - To</th>
                                                <th>Authorised By</th>
                                                <th>Authorisation Date</th>
                                                <th>Driver</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="input-group">
                                                                <input
                                                                    type="date"
                                                                    name="dateFrom"
                                                                    max="{{date('Y-m-d', strtotime(Carbon::now()))}}"
                                                                    class="form-control form-control-sm"/>
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text">
                                                                        <i class="fas fa-calendar"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="input-group">
                                                                <input
                                                                    max="{{date('Y-m-d', strtotime(Carbon::now()))}}"
                                                                    name="dateTo"
                                                                    type="date"
                                                                    class="form-control form-control-sm "/>
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text">
                                                                        <i class="fas fa-calendar"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    <input
                                                        name="fuelIssued"
                                                        type="number"
                                                        class="form-control form-control-sm odometer_entry"/>
                                                </td>

                                                <td>
                                                    <input
                                                        name="startOdometer"
                                                        type="text"
                                                        class="form-control form-control-sm odometer_entry"/>
                                                </td>

                                                <td>
                                                    <input
                                                        name="endOdometer"
                                                        type="text"
                                                        class="form-control form-control-sm odometer_entry"/>
                                                </td>
                                                <td>
                                                    <input
                                                        readonly
                                                        name="difference"
                                                        type="text"
                                                        class="form-control form-control-sm"/>
                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <input
                                                                type="text"
                                                                class="form-control form-control-sm"/>
                                                        </div>
                                                        <div class="col-6">
                                                            <input
                                                                type="text"
                                                                class="form-control form-control-sm"/>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input
                                                        readonly
                                                        name="authorisedBy"
                                                        type="text"
                                                        class="form-control form-control-sm"/>
                                                    <span id="authorisedByName"></span>
                                                </td>
                                                <td>
                                                    <input
                                                        max="{{date('Y-m-d', strtotime(Carbon::now()))}}"
                                                        name="authorizationDate"
                                                        type="date"
                                                        class="form-control form-control-sm "/>
                                                </td>
                                                <td>
                                                    <input
                                                        readonly
                                                        name="driver"
                                                        type="text"
                                                        class="form-control form-control-sm"/>
                                                    <span id="driverName"></span>
                                                </td>
                                                <td>
                                                    <button type="button"
                                                            data-value="0"
                                                            value="deleteRow"
                                                            class="btn btn-danger p-2">
                                                        <i class="fas fa-trash m-0"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <button type="button"
                                                data-table-id="logsTable"
                                                class="btn btn-sm btn-primary add pull-right"
                                                value="addRow">
                                            <i class="fa fa-plus"></i>
                                            Add Row
                                        </button>
                                    </div>
                                    {{--</div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="card-toolbar card-toolbar justify-content-end">
                        <button type="button" id="submitDataBtn" name="submitDataBtn"
                                class="btn btn-success btn-sm mr-3">
                            <i class="fas fa-paper-plane"></i> Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <x-employee-search-modal/>
    </section>
@endsection
@push('scripts')
    <script>
        (function (tmsApp, $) {

            $(document).on('change', 'input', function (e) {
                eventHandler(this, e);
            }).on('keyup', 'input,textarea', function (e) {
                eventHandler(this, e);
            });

            // AutoNumeric.multiple('.odometer_entry > input');
            function addTableRow(tableId) {
                function reinitializeSelect2($_defect_sel) {
                    if ($_defect_sel) {
                        $($_defect_sel).removeClass('select2-hidden-accessible');
                        $($_defect_sel).select2({
                            theme: "bootstrap4",
                            width: "resolve",
                        });
                    }
                }

                Table.addRow($('table#' + tableId));
                let lastRow = $('table#' + tableId).find('tbody tr').eq((0 + 1) * -1);

                lastRow.find('button[value="deleteRow"]').attr('data-value', 0);

                $(lastRow).find('.ui_datepicker').datepicker({
                    maxDate: new Date(),
                    dateFormat: 'dd/mm/yy',
                });
            }

            $(document).on('keypress', '.ui_datepicker', () => {
                return false;
            });

            Inputmask({
                "mask": "99/9999"
            }).mask("#expiryDate");

            Inputmask({
                "mask": "A{2,3} 9{1,4}"
            }).mask("#vehicleRegistration");

            tmsApp.appFormValidator('form[name="newOdometerLogForm"]',
                {},
                {}
            );

            $(document).off('click', 'button[value="addRow"][data-table-id]')
                .on('click', 'button[value="addRow"][data-table-id]', function () {
                    let tableId = $(this).data('tableId');
                    addTableRow(tableId);
                });

            $(document).on('click', "#submitDataBtn", function () {
                let $form = document.forms['newOdometerLogForm'];
                if (!$($form).valid()) {
                    return;
                }

                $('.print-error-msg').css('display', 'none');
                let formData = new FormData($form);
                tmsApp.confirm(
                    'Odometer Log Entry',
                    'Are you sure you want to onboard the data ?',
                    'Yes',
                    'No',
                    function () {
                        window.top.tmsApp.asyncPostFormData(
                            $form.action,
                            formData,
                            function (asyncResponse) {

                                if (asyncResponse.hasOwnProperty('state') && asyncResponse['state'] === 'success') {
                                    setTimeout(function () {
                                        tmsApp.showSystemMessage(
                                            'eToll Card Saved',
                                            asyncResponse['message'],
                                            function () {
                                                window.location.reload();
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
                                            'eToll Card onboarding',
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
                                                'eToll Card onboarding',
                                                xhr.responseJSON['message']
                                            );
                                        }
                                        return;
                                    }

                                    tmsApp.systemError(
                                        'Odometer Log Entry',
                                        'We could not complete processing your request, please try again later');
                                }, 300)
                            }
                        )
                    },
                    function () {
                    }
                );
            });

            $('.ui_datepicker').datepicker({
                maxDate: new Date(),
                dateFormat: 'dd/mm/yy',
            });

            $(document).on('click', '#vehicleSearchBtn', function () {
                if (!document.querySelector('#vehicleRegistration').value || document.querySelector('#vehicleRegistration') < 8) {
                    return;
                }
                // removeSubmissionAndDetailsOptions();
                findVehicle();
            });

            $(document).on('keyup paste enter', '#vehicleRegistration', function () {
                if (!this.value || this.value.replace('_', '').length < 8) {
                    return;
                }
                setTimeout(function () {
                    // removeSubmissionAndDetailsOptions();
                    findVehicle();
                }, 300);
            });

            function populateVehicleDetails(payload) {
                let vehicle = payload['vehicle'];
                let images = payload['images'];
                let vehicle_state = payload['vehicle_state'];

                if (!vehicle || !vehicle.brand_name) {
                    return;
                }

                let vLabel = vehicle['body_type_name'] + ' ' + vehicle['brand_name'] + ' ' + vehicle['model_name'] + ' ' + vehicle['model_code'];
                $("#vehicle_description").val(vLabel);
                let row = `<tr><th>Make</th><td id="make">${vehicle['brand_name']}</td></tr>
                               <tr>
                                    <th>Model</th><td id="model">${vehicle['model_name']} ${vehicle['model_code']}</td>
                               </tr>
                               <tr style="">
                                     <th>Type</th><td id="registration">${vehicle['body_type_name']}</td>
                                </tr>
                                <tr style="">
                                     <th>State:</th><td id="registration">${vehicle['status_name']}</td>
                                </tr>`;

                $('tbody#vehicleDetails').html(row);

                $('#businessArea').val(vehicle['business_unit_code'] + ' ' + vehicle['business_unit_name']);

                $('#userUnit').val(vehicle['business_area_code'] + ' ' + vehicle['business_area_name']);

                $('[name="machineryType"]').val(vehicle['registration_type']).attr('disabled', true);
            }

            function eventHandler(element, e) {
                console.log('Change From ' + element.name)
                switch (element.name) {
                    case 'vehOpeningReading':
                    case 'vehClosingReading':
                        let vehOpeningReading = $("input[name=vehOpeningReading]").val();
                        let vehClosingReading = $("input[name=vehClosingReading]").val();
                        if (!vehClosingReading) {
                            return;
                        }

                        let lineAmountTotal =
                            tmsApp.getRawNumber(vehClosingReading) - tmsApp.getRawNumber(vehOpeningReading);

                        $('#vehDifference').val(lineAmountTotal);
                        break;
                    case 'startOdometer':

                        let endOdometer =  $(element).closest("tr").find("input[name=endOdometer]").val();
                        if(!endOdometer){
                            return;
                        }

                        let diff = tmsApp.getRawNumber(endOdometer) - tmsApp.getRawNumber(element.value);
                        $(element).closest("tr").find("input[name=difference]").val(diff);
                        break;
                    case 'endOdometer':
                        let startOdometer =  $(element).closest("tr").find("input[name=startOdometer]").val();
                        if(!startOdometer){
                            return;
                        }

                        let totalDifference = tmsApp.getRawNumber(element.value) - tmsApp.getRawNumber(startOdometer);
                        $(element).closest("tr").find("input[name=difference]").val(serviceLineAmountTotal);


                       /* $(element).closest("table").find("input[name=service_quantity]").each(function (i, it) {
                            serviceSummaryTotalQty += Util.getFloat(it.value);
                        });*/

                        // set value in footer
                        /*$('#serviceQuantityTotal').text(tmsApp.getRawNumber(serviceSummaryTotalQty));

                        let serviceLineAmountTotal = tmsApp.getFloat(element.value) * tmsApp.getFloat($(element).closest("tr").find("input[name=service_unit_price]").val());
                        $(element).closest("tr").find("input[name=service_total_price]").val(serviceLineAmountTotal);//.change();
                        $(element).closest("tr").find("#total_price").text(tmsApp.numberFormat(serviceLineAmountTotal));*/
                        break;

                    default:
                        break;
                }
            }

            function findVehicle() {
                const numberPlate = document.querySelector('#vehicleRegistration').value
                let formData = new FormData();
                formData.append('vehicle_registration', numberPlate);

                tmsApp.asyncGetFormData(
                    $('#vehicleRegistration').attr('data-action') + '?vehicle_registration=' + numberPlate,
                    formData,
                    function (response_data) {
                        if (response_data.success === 'true' || response_data.success === true) {
                            populateVehicleDetails(response_data.payload);
                        } else {
                            //removeSubmissionAndDetailsOptions();
                            let $message = response_data['message'] ? response_data['message'] : ' No Vehicle Found, Check your input and try again';
                            tmsApp.systemError('Vehicle', $message);
                        }
                    },
                    function (xhr) {
                        tmsApp.systemError('System Message', 'We could not complete processing your request, please try again later');
                    }
                )
            }


        })(window.tmsApp, jQuery);

        let app = new Vue({
            'el': '#kt_app_main', components: {}, data() {
                return {
                    isHeaderSaved: true,
                    assignmentDetails: {},
                    assignmentDetailsForm: null,
                    bodyDetails: {
                        numberOfSeats: 0, volumeOfBootTanker: 0, seatCapRear: 0
                    },
                    bodyDetailsForm: null,
                    bodyTypes: [],
                    businessAreas: [],
                    businessUnits: [],
                    chassisDetails: {
                        stickerRegistrationNumber: null, status: 'active'
                    },
                    chassisDetailsForm: null,
                    chassisDetailsFormValidator: null,
                    configuredModels: [],
                    costCenters: [],
                    costingAndValuation: {},
                    costingDetailsForm: null,
                    dataStatus: 0,
                    directorates: [],
                    document_validity: {
                        state: null, message: null
                    },
                    documents: {},
                    engineBrands: [],
                    engineDetails: {},
                    engineDetailsForm: null,
                    engineDetailsFormValidator: null,
                    fuelTypes: [],
                    images: {
                        frontView: null, rearView: null, leftView: null, rightView: null,
                    },
                    licenseTypes: [],
                    organizationalUnits: [],
                    otherDetails: {}, /*  regNumberValidity: {
                  state: null,
                  message: null
              },*/
                    registrationTypes: [],
                    searchedEmployeesList: [],
                    selectedBrandModels: [], // forms
                    selectedModelCodes: [],
                    supplierList: [],
                    transmissionTypes: [],
                    validators: [],
                    vehicleBrands: [],
                    vehicleHeader: {
                        model: {},
                        isHeaderSaved: false,
                        registration_type: null
                    }, // validators
                    vehicleHeaderForm: null,
                    vehicleHeaderFormValidator: null,
                    vehicleHeaderId: null,
                    vehicle_brand_placeholder: 'Select Vehicle Brand',
                    vehicle_model_placeholder: 'Select Model',
                    weightDetails: {
                        trailerWeight2: 0
                    }
                }
            },

            created() {
                this.getBusinessUnits();
                this.getDirectorates();
                this.getBusinessAreas();
                this.getFuelTypes();
                this.loadRegistrationTypes();
                this.loadLicenceClasses();
                this.getTransmissionTypes();
            },

            filters: {
                trimSpaces: function (val) {
                    if (!val) return "";
                    if (typeof val === 'number') return val;
                    return val?.trim();
                },
                formatStatus: function (value) {
                    if (!value) return 'Saved';
                    if (value === '100') {
                        return 'Pending General Data Entry';
                    } else if (value === '101') {
                        return 'Pending Technical Data Entry';
                    } else if (value === "102") {
                        return 'Pending Accessories Checkin';
                    } else if (value === "103") {
                        return 'Pending Costing Data Entry';
                    } else if (value === "104") {
                        return 'Pending Assignment';
                    }
                }
            },

            mounted() {
                console.log("%c✔ ZESCO Fleet Master Running", "color: #148f32");
                console.log("%c✔ Vehicle OnBoarding Process", "color: #148f32");

                this.vehicleHeaderForm = document.querySelector('#tms_vehicle_header_form');
                this.chassisDetailsForm = document.querySelector('#tms_chassis_details_form');
                this.engineDetailsForm = document.querySelector('#tms_engine_details_form');
                this.costingDetailsForm = document.querySelector('#tms_costing_valuation_form');
                this.bodyDetailsForm = document.querySelector('#tms_body_weight_form');
                this.assignmentDetailsForm = document.querySelector('#tms_assignment_tab_form');

                let input = document.getElementById("userUnit");

                if (this.vehicleHeader && this.vehicleHeader.id) {
                    this.vehicleHeader.isHeaderSaved = true;
                }
            },

            methods: {

                bodyTypeChanged: function (selectedBody) {
                    app['vehicleHeader'].body_type_code = selectedBody?.code;
                    document.querySelector('#bodyType').value = selectedBody?.code;
                },

                checkChassisNumberValidity: function () {
                    fetch(document.querySelector('#documentValidationUrl').value + '?method=chassis&key=' + app['chassisDetails']['chassisNumber'])
                        .then(response => response.json())
                        .then(response => {
                            // Populate results
                            if (response.state === 'failure') {
                                //show errors
                                toastr.error('Chassis validity not verified', 'Connection error');
                                return;
                            }

                            app['document_validity'].state = response['payload'].validity;
                            app['document_validity'].message = response['payload'].message;
                        })
                        .catch(function (error) {
                            // notify of error
                            toastr.error('Could not retrieve data, some feature might not work.', 'Connection error')
                        });
                },

                checkValueChange(element) {
                },

                formatBookValueAsMoney: function (event) {
                    setTimeout(function () {
                        let formatted = accounting.formatMoney(event.target.value, '');
                        console.log('%c' + formatted, "color: #148f32");
                        app['costingAndValuation'].bookValue = formatted;
                    }, 300);
                },

                formatCostPriceAsMoney: function (event) {
                    setTimeout(function () {
                        let formatted = accounting.formatMoney(event.target.value, '');
                        app['costingAndValuation'].costPrice = formatted;
                    }, 300);
                },

                // web UI event
                formatMoney: function (event) {
                    setTimeout(function () {
                        //ZMW
                        let formatted = accounting.formatMoney(event.target.value, '');
                        app['chassisDetails'].chargeOutRate = formatted;
                    }, 300);
                },

                getBusinessAreas: function () {
                    fetch(document.querySelector('#businessAreaEndpoint').value)
                        .then(response => response.json())
                        .then(response => {
                            // Populate results
                            if (response.state === 'failure') {
                                //show errors
                                toastr.error('Connection error, no data found')
                                return;
                            }

                            app.businessAreas = response['payload'];
                        })
                        .catch(function (error) {
                            // notify of error
                            toastr.error('Connection error. Could not retrieve data, some feature might not work.')
                        });
                },

                getBusinessUnits: function () {
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
                            app.businessUnits = response['payload'];
                        })
                        .catch(function (error) {
                            // notify of error
                            toastr.error('Connection error. Could not retrieve data, some feature might not work.')
                        });
                },

                getDirectorates: function () {
                    fetch(document.querySelector('#directoratesEndpoint').value)
                        .then(response => response.json())
                        .then(function (response) {
                            // Populate results
                            if (response.state === 'failure') {
                                //show errors
                                toastr.error('Connection error, no data found')
                                return;
                            }

                            app.directorates = response['payload'];
                        })
                        .catch(function (error) {
                            // notify of error
                            toastr.error('Connection error. Could not retrieve data, some feature might not work.')
                        });
                },

                getFuelTypes: function () {
                    fetch(document.querySelector('#fuelTypesUrl').value)
                        .then(response => response.json())
                        .then(response => {
                            // Populate results
                            if (response.state === 'failure') {
                                //show errors
                                toastr.error('Connection error, no data found')
                                return;
                            }
                            app.fuelTypes = response.payload;
                        })
                        .catch(function (error) {
                            // notify of error
                            toastr.error('Connection error. Could not retrieve data, some feature might not work.')
                        });
                },

                getModelLabel: function (val) {
                    if (typeof val === 'object' && !Array.isArray(val)) {
                        return val['model_name'] + '=>' + val['model_code'];
                    }
                },

                getTransmissionTypes: function () {
                    fetch(document.querySelector('#transmissionTypeUrl').value)
                        .then(response => response.json())
                        .then(response => {
                            // Populate results
                            if (response.state === 'failure') {
                                //show errors
                                toastr.error('Connection error, no data found')
                                return;
                            }
                            app.transmissionTypes = response.payload;
                        })
                        .catch(function (error) {
                            toastr.error('Connection error. Could not retrieve VEHICLE TRANSMISSION  data, some feature might not work.')
                        });
                },

                getUserUnitLabel: function (val) {
                    if (typeof val === 'object') {
                        return val['code_unit'] + '=>' + val.description;
                    }
                },

                loadLicenceClasses: function () {
                    fetch(document.querySelector('#licenseClassEndpoint').value)
                        .then(response => response.json())
                        .then(response => {
                            // Populate results
                            if (response.state === 'failure') {
                                //show errors
                                toastr.error('Connection error, no data found')
                                return;
                            }

                            app.licenseTypes = response['payload'];
                        })
                        .catch(function (error) {
                            // notify of error
                            toastr.error(
                                'Connection error. Could not retrieve license category data, some feature might not work.')
                        });
                },

                loadRegistrationTypes: function () {
                    fetch(document.querySelector('#registrationTypesEndpoint').value)
                        .then(response => response.json())
                        .then(response => {
                            // Populate results
                            if (response.state === 'failure') {
                                //show errors
                                toastr.error('Connection error, no data found')
                                return;
                            }

                            app.registrationTypes = response['payload'];
                        })
                        .catch(function (error) {
                            console.log(error);
                            toastr.error(
                                'Connection error. Could not retrieve license category data, some feature might not work.', 'Registration Types')
                        });
                },

                postRequest(data, url, successCallBack, errorCallBack) {
                    axios.post(url, data, {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'content-type': 'text/json'
                        }
                    }).then(function (response) {
                        successCallBack(response);
                    }).catch(function (error) {
                        errorCallBack(error);
                    });
                },

                postVehicleHeaderData() {
                    if (!this.validators) {
                        return alert('No Validator Configured');
                    }
                    this.vehicleHeaderFormValidator.validate().then(function (status) {
                        console.log('validated!');
                        if (status !== 'Valid') {
                            toastr.warning("Sorry, the data did not pass validation check, check the data and try again.");
                            return;
                        }

                        let el = document.querySelector('#tms_save_vehicle');
                        el.setAttribute('data-kt-indicator', 'on');
                        el.disabled = true;

                        app.postRequest(new FormData($(app.vehicleHeaderForm)[0]), app.vehicleHeaderForm.action, function (response) {
                            let el = document.querySelector('#tms_save_vehicle');
                            let label = el.querySelector(".indicator-label");

                            setTimeout(function () {
                                el.removeAttribute('data-kt-indicator');
                                el.disabled = false;
                            }, 300)

                            if (response.data.state != 'success') {
                                toastr.error(response.data.message);
                                return;
                            }

                            app.vehicleHeaderId = response.data.payload.id;
                            toastr.success(response.data.message);

                            setTimeout(function () {
                                app['vehicleHeader'].isHeaderSaved = true;
                            }, 500)

                            if (el.classList.contains("btn-light-primary")) {
                                el.classList.remove("btn-light-primary");
                                el.classList.add("btn-light");
                                label.innerHTML = "Saved";
                            } else { // follow
                                el.classList.add("btn-light-primary");
                                el.classList.remove("btn-light");
                                app['vehicleHeader'].isHeaderSaved = true;
                                label.innerHTML = "Saved";
                            }

                        }, function (error) {
                            let el = document.querySelector('#tms_save_vehicle');
                            let label = el.querySelector(".indicator-label");
                            label.innerHTML = "Submit";
                            el.removeAttribute('data-kt-indicator');
                            el.disabled = false;

                            toastr.error(error.message);

                        });
                    });

                },

                postVehicleImages() {
                    let completionForm = $('#completeRegistrationForm');
                    $.ajax({
                        'url': $(completionForm).attr('action'),
                        'type': 'POST',
                        data: new FormData($(completionForm)[0]),
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'content-type': 'text/json'
                        }
                    }).done(function (response) {

                        Swal.fire({
                            text: "Vehicle Registration Completed Successfully," + "You will be refreshed",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        }).then(function () {
                            window.location.reload();
                        });

                    })
                },

                preview(event) {
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
                },

                registrationTypeChanged(selectedType) {
                    console.log(selectedType)
                },

                switchTabs() {
                    let tabs = document.querySelectorAll('a[role="tab"]');
                    let activeIndex = 0;
                    $.each(tabs, function (index, element) {
                        if ($(element).hasClass('active')) {
                            activeIndex = index;
                            return;
                        }
                    });
                    let nextIndex = activeIndex < tabs.length - 1 ? activeIndex + 1 : activeIndex;

                    if (nextIndex === activeIndex) {
                        return;
                    }
                    $(tabs[activeIndex]).removeClass('active');
                    $(tabs[nextIndex]).addClass('active');

                    // switch visible content
                    let tabContent = document.querySelector('#myTabContent').children;
                    $(tabContent[nextIndex]).addClass('active').addClass('show');
                    $(tabContent[activeIndex]).removeClass('active').removeClass('show')
                },

                transmissionTypeChanged: function (transmissionType) {
                    document.querySelector('#transmission_type').value = transmissionType?.code + ':' + transmissionType?.name;
                },

                /*vehicleBrandChanged(selectedValue) {
                    this.vehicleHeader.brand_code = selectedValue?.id?.toString().trim();
                    this.selectedBrandModels = [];

                    app.selectedBrandModels = app['configuredModels'].filter(function (model) {
                        return model.brand_code?.toString()?.trim() === app?.vehicleHeader.brand_code?.toString().trim();
                    });
                },*/
            }
        });
    </script>
@endpush
