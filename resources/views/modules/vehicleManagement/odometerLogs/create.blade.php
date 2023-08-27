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
        <div id="app" class="card mt-10">
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
                                        <table aria-label="Trip Entry Logs"
                                               role="none"
                                               class="app_form_table table">
                                            <tr>
                                                <td>
                                                    <label for="vehicleRegistration" class="app-field-label field-required">
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
                                                        <option selected value=""></option>
                                                        @foreach($registrationTypes as $registrationType)
                                                            <option value="{{$registrationType->code}}">{{$registrationType->name}}</option>
                                                        @endforeach

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
                                                        name="periodFrom"
                                                        id="periodFrom"
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
                                                        id="periodTo"
                                                        name="periodTo"
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

                        let endOdometer = $(element).closest("tr").find("input[name=endOdometer]").val();
                        if (!endOdometer) {
                            return;
                        }

                        let diff = tmsApp.getRawNumber(endOdometer) - tmsApp.getRawNumber(element.value);
                        $(element).closest("tr").find("input[name=difference]").val(diff);
                        break;
                    case 'endOdometer':
                        let startOdometer = $(element).closest("tr").find("input[name=startOdometer]").val();
                        if (!startOdometer) {
                            return;
                        }

                        let totalDifference = tmsApp.getRawNumber(element.value) - tmsApp.getRawNumber(startOdometer);
                        $(element).closest("tr").find("input[name=difference]").val(totalDifference);
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

            function deleteTableRow(eventSource) {

                let btnEl = $(eventSource);
                let tableRow = btnEl.closest('tr');

                tmsApp.confirm(
                    "Are you sure ?",
                    "The data entered on this line will be cleared out, " +
                    "if not saved already, you will not be able to recover it",
                    "Yes",
                    "No",
                    function () {
                        $(tableRow).remove();
                    });
            }

            Inputmask({
                "mask": "99/9999"
            }).mask("#expiryDate");

            tmsApp.appFormValidator('form[name="newOdometerLogForm"]',
                {},
                {}
            );

            $(document).off('click', 'button[value="addRow"][data-table-id]')
                .on('click', 'button[value="addRow"][data-table-id]', function () {
                    let tableId = $(this).data('tableId');
                    addTableRow(tableId);
                });

            $(document).on('click', 'button[value="deleteRow"]', function (e) {
                deleteTableRow(this);
                return false;
            });

            $(document).on('click', "#submitDataBtn", function () {
                let $form = document.forms['newOdometerLogForm'];
                if (!$($form).valid()) {
                    return;
                }

                $('.print-error-msg').css('display', 'none');
                // let formData = new FormData($form);

                let formData = {};

                let arr = [];
                let obj = {};

                $("#logsTable").find("tbody").children().map(function (index, row) {
                    let obj = {};
                    $(row).find('input[name][type!=hidden], select[name],textarea[name]')
                        .each(function (i, item) {
                            let val = item.value.replace(/,/g, '');

                            if (item.name === 'endDate'
                                || item.name === 'startDate'
                                || item.name === 'invoiceDate') {
                                let dateField = val;
                                dateField = DateFormatter.format(new Date(
                                        moment(val, 'DD/MM/yyyy')),
                                    DateFormatter.ISO);

                                obj[item.name] = dateField;
                            } else {
                                obj[item.name] = item.value;
                            }
                        });
                    arr.push(obj);
                });

                $($form).find('input[name], select[name]').each(function (i, item) {
                    if (item.type === 'radio') {
                        obj[item.name] = $('[name="' + item.name + '"]:checked').val();
                    } else {
                        obj[item.name] = item.value;
                    }
                });

                formData['items'] = arr;

                formData = {
                    ...obj,
                    ...formData
                }

                tmsApp.confirm(
                    'Trip Entry Log',
                    'Are you sure you want to submit the data ?',
                    'Yes',
                    'No',
                    function () {
                        $.ajax({
                            type: "POST",
                            url: $form.action,
                            data: JSON.stringify(formData),
                            dataType: "json",
                            contentType: "application/json; charset=utf-8",
                        })
                            .done(function (asyncResponse) {

                                if (asyncResponse.hasOwnProperty('state') && asyncResponse['state'] === 'success') {
                                    setTimeout(function () {
                                        tmsApp.showSystemMessage(
                                            'Trip Entry Log',
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
                                            'Trip Entry Log',
                                            asyncResponse['message'],
                                            function () {
                                            }, 'error');
                                    }, 300);
                                }
                            })
                            .fail(function (xhr, settings, errorThrown) {
                                console.log(errorThrown)
                                setTimeout(function () {
                                    if ('responseJSON' in xhr) {
                                        if (xhr.responseJSON.hasOwnProperty('errors')) {
                                            tmsApp.printErrorMsg(xhr.responseJSON.errors);
                                        }
                                        if (xhr.responseJSON.hasOwnProperty('message')) {
                                            tmsApp.systemError(
                                                'Trip Entry Log',
                                                xhr.responseJSON['message']
                                            );
                                        }
                                        return;
                                    }

                                    tmsApp.systemError(
                                        'Trip Entry Log',
                                        'We could not complete processing your request, please try again later');
                                }, 300)
                            });
                    },
                    function () {
                    }
                );
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

        })(window.tmsApp, jQuery);

        let app = new Vue({
            'el': '#app',
            components: {},
            data() {
                return {}
            },

            created() {

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
                Inputmask({
                    "mask": "A{2,3} 9{1,4}"
                }).mask("#vehicleRegistration");

            },

            methods: {}
        });
    </script>
@endpush
