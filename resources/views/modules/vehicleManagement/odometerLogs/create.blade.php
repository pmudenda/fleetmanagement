@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@push('styles')
@endpush
@section('content')
    <x-content-header :pageTitle="'Odometer Log Entry'"/>
    <section class="content">
        <div class="card mt-10">
            <div class="card-header">
                <div class="card-title">
                    <h4>Odometer Log</h4>
                </div>
                <div class="card-toolbar card-toolbar justify-content-end">
                    <button type="button" name="submitDataBtn" class="btn btn-success btn-sm mr-3">
                        <i class="fas fa-paper-plane"></i> Submit
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-10">
                    <div class="wizard">

                        <div class="clearfix"></div>

                        <form name="newOdometerLogForm"
                              id="newOdometerLogForm"
                              action="{{route('save.odometer.log')}}"
                              method="post">
                            @csrf

                            <div class="errorTxt"></div>
                            <x-error-view></x-error-view>

                            <label class="app-required-marker"></label>

                            <div class="row">
                                <div class="col-6">
                                    <fieldset style="" class="form-group border p-3">
                                        <legend class="text-bold">General Information:</legend>
                                        <table class="app_form_table table">
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
                                                    <label class="app-field-label">
                                                        Machinery Type.
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <select name="machineryType"
                                                            class="form-select form-select-sm">
                                                        <option selected value="VEHICLE">VEHICLE</option>
                                                        <option value="PLANT EQUIPMENT">PLANT EQUIPMENT</option>
                                                        <option value="BOAT">BOAT</option>
                                                    </select>
                                                </td>
                                            </tr>

                                        </table>
                                    </fieldset>
                                </div>
                                <div class="col-6">
                                    <fieldset style="" class="form-group border p-3">
                                        <legend class="text-bold">Odometer Information:</legend>
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
                                                            <div class="input-group-addon">
                                                                <div>
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
                                                            <div class="input-group-addon">
                                                                <div>
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
                                                            <div class="input-group-addon">
                                                                <div>
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
                                <div class="container-fluid">
                                    <div class="col-12">
                                        <table class="app_form_table table table-bordered">
                                            <thead>
                                            <tr class="bg-success">
                                                <th>Date From| Date To</th>
                                                <th>Start Odometer</th>
                                                <th>Closing Odometer</th>
                                                <th>Total Distance</th>
                                                <th>Place From | Place To</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <input
                                                        type="text"
                                                        class="form-control form-control-sm datepicker"/>
                                                    |
                                                    <input
                                                        type="text"
                                                        class="form-control form-control-sm datepicker"/>
                                                </td>
                                                <td>
                                                    <input
                                                        type="text"
                                                        class="form-control form-control-sm"/>
                                                </td>
                                                <td>
                                                    <input
                                                        type="text"
                                                        class="form-control form-control-sm"/>
                                                </td>
                                                <td><input
                                                        type="text"
                                                        class="form-control form-control-sm"/></td>
                                                <td>
                                                    <input
                                                        type="text"
                                                        class="form-control form-control-sm"/>
                                                </td>
                                                <td></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
        <x-employee-search-modal/>
    </section>
@endsection
@push('scripts')
    <script>
        (function (tmsApp, $) {

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

            $("#submitDataBtn").on('click', function () {
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

            $('.datepicker').datepicker({
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


            function findVehicle() {
                const numberPlate = document.querySelector('#vehicleRegistration').value
                let formData = new FormData();
                formData.append('vehicle_registration', numberPlate);

                tmsApp.asyncGetFormData(
                    $('#vehicleRegistration').attr('data-action'),
                    formData,
                    function (response_data) {
                        if (response_data.success === 'true' || response_data.success === true) {
                            // populateVehicleDetails(response_data.payload, response_data['message']);
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
