@php use Carbon\Carbon; @endphp

@extends('layouts.app')
@push('styles')
    <link href="{{asset("libs/steps/jquery-steps.css")}}" rel="stylesheet" type="text/css"/>
    <style>
        .error {
            color: red;
        }

        .nav-tabs .nav-link.active, .nav-tabs .nav-item.show .nav-link {
            border-color: orange;
        }

        .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link {
            color: #495057;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
        }
    </style>
@endpush
@section('content')
    <x-content-header
        :activeCrumb="'New Accident'"
        :linkText="'Report'"
        :pageTitle="'Accident Reporting'"/>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h4>Accident Record</h4>
                    <span class="ml-2 indicator-pill whitespace-nowrap orange">
                        <span>Not Saved</span>
                    </span>
                </div>
            </div>

            <div class="card-body pb-4 min-h-600px pt-0">
                <x-error-view/>
                <label class="app-required-marker"></label>
                <form name="saveRecord" id="my-form" class="form-wrapper"
                      action="{{route('accident.store')}}"
                      method="POST">
                    @csrf

                    <h3 class="step-top step1-top">Vehicle Details</h3>
                    @include('modules.accidentReporting.tabs.vehicleDetails')

                    <h3 class="step-top step3-top">Driver Details</h3>
                    @include('modules.accidentReporting.tabs.driverDetail')

                    <h3 class="step-top step3-top">Accident Details</h3>
                    @include('modules.accidentReporting.tabs.accidentDetail')

                    <h3 class="step-top step4-top">Attachments</h3>
                    @include('modules.accidentReporting.tabs.attachments')

                </form>

                <input type="hidden"
                       name="vehicle_details"
                       id="vehicle_details"
                       value="{{route('requisition.vehicle.details')}}">
            </div>
        </div>
        <x-employee-search-modal/>

    </section>
    <input type="hidden" value="{{route('accident.types')}}" id="accident_types_endpoint">
    <input type="hidden" value="{{route('accident.natures')}}" id="accident_natures_endpoint">
    @push('scripts')
        <script src="{{asset("libs/steps/jquery.steps.min.js")}}"></script>
        <script src="{{asset('libs/imageUpload/imageUpload.js')}}"></script>
        <script>
            const observationRowTemplate = `<tr>
                                    <td>
                                        <p>
                                            <button type="button" title="Select Image"
                                                    data-toggle="tooltip"
                                                    data-select="file"
                                                    class="btn btn-primary btn-sm selectAttachment">
                                                <i class="fas fa-paperclip"></i>
                                            </button>
                                            <input type="file"
                                                   accept="image/*"
                                                   style="display: none;"
                                                   class="fileElem d-none"
                                                   id="attachment"
                                                   name="attachment[]"/>
                                        </p>
                                        <div class="imagePreview"
                                             style="display: none; min-height: 100px !important;">
                                            <button type="button"
                                                    class="btn btn-xs clearImage"
                                                    style="top: 1px;
                                                                                                    position: relative;
                                                                                                    right: 1px;
                                                                                                    float: right;
                                                                                                    padding: 2px;">
                                                <i class="fa fa-window-close" style="font-size: 20px;"></i>
                                            </button>
                                        </div>
                                    </td>

                                    <td>
                                        <button type="button"
                                                data-table-id="observations"
                                                class="btn btn-sm btn-danger"
                                                value="deleteRow">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>`;
            (function (tmsApp, $) {

                //new tmsApp.fileUploader().makeSingleFileUploader();

                new ImageUpload().initRow();

                function initializeFormWizard() {
                    let formWizard = $('#my-form');

                    let form = formWizard.show();

                    form.steps({
                        headerTag: "h3",
                        bodyTag: "section",
                        transitionEffect: "slideLeft",
                        autoFocus: true,
                        labels: {
                            finish: 'Submit'
                        },
                        onStepChanging: function (event, currentIndex, newIndex) {
                            if (currentIndex > newIndex) {
                                return true;
                            }

                            if (currentIndex < newIndex) {
                                // To remove error styles
                                form.find(".body:eq(" + newIndex + ") label.error").remove();
                                form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                            }

                            form.validate().settings.ignore = ":disabled,:hidden";
                            return form.valid();
                        },
                        onStepChanged: function (event, currentIndex, priorIndex) {

                            if (currentIndex === 2 && priorIndex === 3) {
                                form.steps("previous");
                            }
                        },
                        onFinishing: function (event, currentIndex) {
                            form.validate().settings.ignore = ":disabled";
                            return form.valid();
                        },
                        onFinished: function () {
                            let formData = new FormData(formWizard[0]);

                            tmsApp.asyncPostFormData(formWizard.attr('action'), formData,
                                function (response) {
                                    if (response.state === 'success') {

                                        tmsApp.showSystemMessage('Accident Recording', response.message, function () {
                                            window.location.href = response['redirectUrl'];
                                        }, 'success')
                                    } else {
                                        tmsApp.showSystemMessage('Accident Recording', response.message, null, 'error');
                                    }

                                }, function (jqXHR, textStatus, errorThrown) {
                                    tmsApp.showErrorMessages(jqXHR, 'Accident Recording');
                                }, 'POST');
                        },
                    })
                        .validate({
                            errorPlacement: function (error, element) {
                                if (element.parent('.input-group').length) {
                                    error.insertAfter(element.parent());
                                } else {
                                    error.insertAfter(element);
                                }
                            },
                            rules: {},
                            messages: {
                                accidentType: {
                                    required: "Accident Type is required when reporting"
                                },
                                registrationNo: {
                                    required: "Vehicle Registration is required"
                                },
                                vehicleMake: {
                                    required: "Vehicle Make is required"
                                },
                                vehicleModel: {
                                    required: "Vehicle Model is required"
                                }
                            }
                        });
                }

                $(function () {
                    initializeFormWizard()
                });

                function insertTableRow(tableId) {

                    const $table = $('table#' + tableId);
                    if (tableId === "observations") {
                        //const materialTableRowTemplate = document.querySelector('#materialTableRowTemplate');
                        $table.find('tbody').append(observationRowTemplate);
                    }
                    let lastRow = $table.find('tbody tr').eq((0 + 1) * -1);

                    lastRow.find('button[value="deleteRow"]').attr('data-value', 0);
                }


                function populateInsuranceDetails(payload) {
                    let hasValid = payload['hasValidInsurance'];
                    let insurance = payload['insurance'];
                    let certificate_number = insurance?.certificate_number;
                    let policy_no = insurance?.policy_no;
                    let period_from = insurance?.period_from;
                    let period_to = insurance?.period_to;
                    let insurancePeriod = new Date(period_from).toLocaleString()
                        + ' ' + new Date(period_to).toLocaleString()
                    let status = '';
                    if (hasValid) {
                        status = '<span class="badge badge-success" style="height: 30px; width: 30px;"></span> Valid';
                    } else {
                        status = '<span class="badge badge-danger" style="height: 30px; width: 30px;"></span> Expired'
                    }

                    const $insuranceDetails = `<h2>Insurance Details</h2>
                    <div class="col-4 col-xs-12">
                        <div role="table" aria-label="Vehicle Details" class="table table-striped">
                            <div id="vehicleDetails" class="vehicleDetails">
                                <div class="row">
                                    <div class="col">
                                        <strong>Status:</strong>
                                    </div>
                                    <div class="col">
                                        ${status}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <strong>Insurance Period</strong>
                                    </div>
                                    <div class="col">
                                        ${insurancePeriod}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <strong>Policy</strong>
                                    </div>
                                    <div class="col" id="registration">
                                        ${policy_no}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>`;

                    $('.insurance_container').html($insuranceDetails);
                }

                function displayVehicleDetails(payload) {
                    let vehicle = payload['vehicle'];
                    let images = payload['images'];

                    if (!vehicle || !vehicle.brand_name) {
                        return;
                    }

                    let vLabel = `${vehicle['body_type_name']}
                          ${vehicle['brand_name']}
                          ${vehicle['model_name']}
                          ${vehicle['model_code']}`;
                    let row = `<div class="row">
                               <div class="col">
                                    <strong>Make:</strong>
                               </div>
                               <div class="col" id="make">
                                    ${vehicle['brand_name']}
                               </div>
                               </div>
                               <div class="row">
                                    <div class="col">
                                        <strong>Model</strong>
                                    </div>
                                    <div class="col" id="model">
                                        ${vehicle['model_name']}
                                        ${vehicle['model_code']}
                                    </div>
                               </div>
                               <div class="row">
                                    <div class="col-6">
                                        <strong>Type</strong>
                                    </div>
                                    <div class="col-6" id="registration">
                                       ${vehicle['body_type_name']}
                                    </div>
                                </div>
                               <div class="row">
                                     <div class="col">
                                        <strong>Status</strong>
                                    </div>
                                    <div class="col" id="registration">
                                    ${vehicle['status_name']}
                                    </div>
                                </div>`;

                    $('[name="mileage"]').val(vehicle?.mileage).attr('min', vehicle?.mileage);
                    $('[name="type_brand_model"]').val(vLabel);
                    $('[name="assignedTo"]').val(vehicle['business_unit_code']);
                    document.querySelector('[name="assignedToDescription"]')
                        .value = vehicle['business_unit_code']
                        + ' : ' + vehicle['business_unit_name'];

                    $('#vehicleDetails').html(row);
                    document.querySelector('#vehicleDetailsContainer').style.display = null;

                    if (images && images.length > 0) {
                        let frontViewImages = images.filter((image) => {
                            return image['file_type'] === 'Front View';
                        })
                        let imagePath = frontViewImages[0]?.path;
                        document.querySelector(".imagePreview")
                            .style.backgroundImage = "url(/storage" + imagePath + ")";
                        document.querySelector('#image_view')
                            .style.display = null;
                    }


                    populateInsuranceDetails(payload);
                }

                function fetchVehicleDetails(reg) {
                    $.ajax({
                        url: $('[name="vehicle_details"]').val(),
                        data: {
                            'vehicle_registration': reg
                        },
                        method: 'GET',
                        success: function (response) {

                            if (!response.success) {
                                tmsApp.showSystemMessage('Vehicle Details', response.message, function () {
                                }, 'error');
                                return;
                            }

                            displayVehicleDetails(response.payload);
                            tmsApp.showToast(response.message, 'success', null);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            tmsApp.showSystemMessage(
                                'Vehicle Details',
                                response['message'],
                                null,
                                'error'
                            );
                        }
                    });
                }

                function getYearsDifferenceFromNow(licenseIssuedDate) {
                    if (!licenseIssuedDate) {
                        return 0;
                    }
                    // Create a Date object for the specific target date
                    const targetDateTime = new Date(licenseIssuedDate);

                    // Get the current date and time
                    const now = new Date();

                    // Calculate the difference in milliseconds
                    const difference_ms = targetDateTime.getTime() - now.getTime();

                    // Convert the difference to years
                    const yearsDifference = difference_ms / (1000 * 60 * 60 * 24 * 365.25);

                    return Math.floor(yearsDifference) * -1;
                }

                function fetchDriverDetails(searchCriteria, url) {
                    $('[name="job_title"]').val('');
                    $("#driver_name").val('');
                    $('[name="experience"]').val('');
                    let formData = new FormData();
                    formData.append('searchCriteria', searchCriteria);

                    tmsApp.asyncPostFormData(
                        url,
                        formData,
                        function (response) {
                            if (response.success === 'true' || response.success) {
                                const driverDetails = response.payload;

                                $('[name="job_title"]').val(driverDetails?.job_title);

                                $("#driver_name").val(driverDetails.name);

                                $('[name="experience"]')
                                    .val(getYearsDifferenceFromNow(driverDetails?.license_date_issued));
                                tmsApp.showSystemMessage('Driver Search', response.message, null, 'success')

                            } else {
                                tmsApp.showSystemMessage('Driver Search', response.message, null, 'error')
                            }

                        },
                        function (jqXHR, textStatus, errorThrown) {
                            // Code to execute when the AJAX request fails
                        },
                        'POST'
                    );
                }

                function getAccidentTypes() {
                    fetch(document.querySelector('#accident_types_endpoint').value)
                        .then(response => response.json())
                        .then(response => {
                            // Populate results
                            let selectElem = $('#accidentType');

                            if (response.state === 'failure') {
                                //show errors
                                toastr.error('Connection error, no data found')
                                return;
                            }


                            let userUnits = response['payload'];


                            window.organizationUnits = userUnits;
                            tmsApp.populateDropDownList(selectElem, userUnits, "code", ['name']);

                            let userUnitId = selectElem.attr('data-value');
                            if (userUnitId) {
                                selectElem.val(userUnitId);
                                selectElem.trigger('change');
                            }
                        })
                        .catch(function (error) {
                            // notify of error
                            console.log(error)
                            toastr.error(
                                'Could not retrieve Organizational units data, some feature might not work.',
                                'Connection error.');
                        });
                }

                function getAccidentNatures() {
                    fetch(document.querySelector('#accident_natures_endpoint').value)
                        .then(response => response.json())
                        .then(response => {
                            // Populate results
                            let selectElem = $('#accidentNature');

                            if (response.state === 'failure') {
                                //show errors
                                toastr.error('Connection error, no data found')
                                return;
                            }

                            let userUnits = response['payload'];


                            window.organizationUnits = userUnits;
                            tmsApp.populateDropDownList(selectElem, userUnits, "code", ['name']);

                            let userUnitId = selectElem.attr('data-value');
                            if (userUnitId) {
                                selectElem.val(userUnitId);
                                selectElem.trigger('change');
                            }
                        })
                        .catch(function (error) {
                            toastr.error(
                                'Could not retrieve Organizational units data, some feature might not work.',
                                'Connection error.')
                        });
                }

                $(document).ready(function () {
                    getAccidentNatures();

                    getAccidentTypes();

                    $(document).on('click', '#driverSearchBtn', function (event) {
                        let $driverCtrl = $('#driver_staff_number');
                        fetchDriverDetails($driverCtrl.val(), $driverCtrl.attr('data-action'))
                    });

                    Inputmask({
                        "mask": "A{2,3} 9{1,4}"
                    }).mask("#registrationNo");

                    $(document).on('keypress', '.numberOnly', function (e) {
                        tmsApp.numberOnly(e);
                    })

                    $(document).on('click', 'button[value="insertRow"][data-table-id]', function () {
                        let tableId = $(this).data('tableId');
                        insertTableRow(tableId);
                    });

                    $(document).on('click', 'button[value="deleteRow"]', function (e) {
                        tmsApp.confirm(
                            "Are you sure ?",
                            "The data entered on this line will be cleared out",
                            "Yes",
                            "No",
                            function () {

                                let btnEl = $(e.target);
                                let tableId = $(btnEl).closest('table').attr('id');
                                let valueId = $(btnEl).attr('data-value');
                                let tableRow = btnEl.closest('tr');
                                let table = btnEl.closest('table');

                                $(tableRow).remove();
                                return;

                                let dataUrl = "";
                                dataUrl = document.querySelector('[name="deleteDefectUrl"]').value;
                                let formData = new FormData();
                                formData.append('record_id', valueId);

                                tmsApp.asyncPostFormData(
                                    dataUrl,
                                    formData,
                                    function (asyncResponse) {
                                        if ('success' in asyncResponse && !asyncResponse.success) {
                                            if (asyncResponse.hasOwnProperty('errors')) {
                                                toastr.error(
                                                    asyncResponse.message
                                                );
                                                tmsApp.printErrorMsg(asyncResponse.errors);
                                                return
                                            }

                                            setTimeout(function () {
                                                    tmsApp.systemError(
                                                        'System Configuration',
                                                        asyncResponse['message'],
                                                        function () {
                                                        }, 'error');
                                                },
                                                300);
                                            return;
                                        }

                                        if (asyncResponse.success) {
                                            const entry = asyncResponse.payload;
                                            tmsApp.showSystemMessage(
                                                'System Configuration',
                                                asyncResponse['message'],
                                                function () {
                                                    clearRows(table);
                                                },
                                                'success'
                                            );
                                        }
                                    },
                                    function (xhr, settings, error) {
                                        setTimeout(
                                            function () {
                                                tmsApp.showErrorMessages(xhr, 'System Configuration');
                                            },
                                            300);
                                    },
                                    'POST',
                                );
                            });
                        return false;
                    });

                    $(document).on('click', "#vehicleClear", function () {

                        let vehicleModel = document.getElementById("modelNo")
                        let vehicleMake = document.getElementById("vehicleMake")
                        let chassisNo = document.getElementById("chassisNo")

                        vehicleModel.removeAttribute("disabled")
                        vehicleMake.removeAttribute("disabled")
                        chassisNo.removeAttribute("disabled")

                        vehicleModel.value = ""
                        vehicleMake.value = ""
                        chassisNo.value = ""
                    })

                    $('#vehicleSearchBtn').on('click enter', function () {
                        const reg = $('#registrationNo').val();
                        if (!reg || reg.replaceAll('_', '').replaceAll(" ", '').length < 4) {
                            return;
                        }
                        fetchVehicleDetails(reg);
                    });

                    $('#registrationNo').on('paste enter', function () {
                        if (!this.value || this.value.replaceAll('_', '').replaceAll(" ", '').length < 4) {
                            return;
                        }

                        const query = $(this).val();
                        fetchVehicleDetails(query);
                    });
                })
            })(window.tmsApp, jQuery);
        </script>
    @endpush

@endsection
