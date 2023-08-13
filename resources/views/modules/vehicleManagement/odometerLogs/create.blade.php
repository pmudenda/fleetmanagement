@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@push('styles')
    <style>
        th {
            white-space: nowrap;
        }

        /**===NO WRAP ON TABLE =====**/
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
                                                        name="startOdometer"
                                                        type="text"
                                                        class="form-control form-control-sm odometer_entry"/>
                                                </td>
                                                <td>
                                                    <input
                                                        name="fuelIssued"
                                                        type="number"
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

                // BAD 1010
                /*if (state !== 'InWorkshop') {
                    if (vehicle['status'] !== document.querySelector('[name="vehicleActive"]').value) {
                        tmsApp.showSystemMessage("Vehicle State",
                            vehicle_state,
                            () => {
                            },
                            "error");
                        return;
                    }
                }*/

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
                        let vehClosingReading = $("input[name=vehClosingReading]").val()
                        let lineAmountTotal = tmsApp.getRawNumber(vehClosingReading)
                            - tmsApp.getRawNumber(vehOpeningReading);

                        $('#vehDifference').val(lineAmountTotal);
                        /*let summaryTotalQty = 0;
                        $(element).closest("table").find("input[name=quantity]").each(function (i, it) {
                            summaryTotalQty += Util.getFloat(it.value);
                        });



                        let lineAmountTotal = tmsApp.getFloat(element.value) * tmsApp.getFloat($(element).closest("tr").find("input[name=unit_price]").val());
                        $(element).closest("tr").find("input[name=total_price]").val(lineAmountTotal).change();
                        $(element).closest("tr").find("#total_price").text(tmsApp.numberFormat(lineAmountTotal));*/
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
                            // registration_type
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
    </script>
@endpush
