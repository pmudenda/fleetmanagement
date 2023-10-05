@extends('layouts.app')
@php
    use App\Enums\RepairTypes;use App\Enums\RequisitionItemTypes;use App\Helpers\StatusHelper;use App\Helpers\VehicleStatus;use Carbon\Carbon;
@endphp
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
    <link href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}"/>
@endpush
@section('content')
    <x-content-header/>
    <section class="content">
        <div class="row g-12 g-xl-12" id="kt_app_main">
            <div class="card mb-xl-10">
                <div id="card_header" class="card-header min-h-2px">
                    <div class="card-header pl-0">
                        <div class="card-title">
                            <h4>Vehicle Status Change</h4>
                        </div>
                        <div id="actionButtonsContainer"
                             class="card-toolbar justify-content-end">
                            <button type="button" id="submitFuelAllocationBtn"
                                    class="btn btn-success btn-sm mr-3 when_odo_valid">
                                <i class="fas fa-save"></i>
                                Submit
                            </button>
                            <button type="button" id="resetRequisitionBtn"
                                    class="btn btn-danger btn-sm mr-3">
                                <i class="fas fa-undo"></i>
                                Clear
                            </button>

                        </div>
                    </div>
                    <div class="card-title">
                        <h2>Vehicle Status Change Form</h2>
                        <span class="ml-2 indicator-pill whitespace-nowrap orange"><span>Not Saved</span></span>
                    </div>

                    <form name="fuelAllocationForm"
                          id="fuelAllocationForm"
                          action="{{route('vehicle.status.change.save')}}" method="post">
                        @csrf
                        <div class="card-body user-data pl-1">
                            <label class="app-required-marker"></label>
                            <div class="container-fluid mt-2">
                                <div class="row">
                                    <div class="col-9">
                                        <div class="row pl-0">
                                            <div class="col-9">
                                                <div class="col-xs-12 col-sm-8 col-md-8">
                                                    <div class="container-fluid pl-0">
                                                        <div class="row">
                                                            <div class="form-group row">
                                                                <label class="col-xs-12 col-sm-6
                                                                col-md-5 col-lg-4 field-required pl-0"
                                                                       for="vehicle_registration">
                                                                    Registration #:
                                                                </label>
                                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8">
                                                                    <div class="input-group">
                                                                        <input type="text"
                                                                               data-action="{{
                                                                            route('requisition.vehicle.details')
                                                                            }}"
                                                                               class="form-control form-control-sm"
                                                                               autocapitalize="characters"
                                                                               id="vehicleRegistration"
                                                                               placeholder="Vehicle Reg e.g AAB 6757"
                                                                               name="vehicleRegistration"
                                                                               required>
                                                                        <div class="input-group-addon">
                                                                            <button type="button" id="vehicleSearchBtn"
                                                                                    name="vehicleSearchBtn"
                                                                                    class="btn btn-success btn-sm
                                                                                border-radius-0">
                                                                                <i class="fas fa-search"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-8 col-md-8">
                                                    <div class="container-fluid pl-0">
                                                        <div class="row">
                                                            <div class="form-group row">
                                                                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                                                    <input type="hidden"
                                                                           class="form-control form-control-sm"
                                                                           id="vehicle_description"
                                                                           name="vehicle_description"
                                                                           required
                                                                           readonly
                                                                    />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-9">
                                                <div class="row mt-5">
                                                    <div class="col-xs-12 col-sm-8 col-md-8">
                                                        <div class="container-fluid pl-0">
                                                            <div class="row">
                                                                <div class="form-group row">
                                                                    <label
                                                                        class="col-xs-12 col-sm-6 col-md-5
                                                                            col-lg-4 field-required"
                                                                        for="status">
                                                                        Status:
                                                                    </label>
                                                                    <div
                                                                        class="col-xs-12 col-sm-6
                                                                            col-md-7 col-lg-8">
                                                                        <select
                                                                            id="status"
                                                                            name="status"
                                                                            class="form-select form-select-sm">
                                                                            @foreach($vehicleStatuses as $status)
                                                                                <option value="{{$status->code}}">
                                                                                    {{$status->name}}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label
                                                                class="col-xs-12 col-sm-6
                                                                col-md-5 col-lg-4 pl-0 field-required"
                                                                for="remarks">
                                                                Remarks :
                                                            </label>
                                                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8 pl-0">
                                                    <textarea type="text"
                                                              id="remarks"
                                                              minlength="10"
                                                              maxlength="255"
                                                              required
                                                              name="remarks"
                                                              style="height: 129px;"
                                                              class="form-control comments form-control-sm"
                                                    ></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer justify-content-between">
                                            <button type="submit"
                                                    id="fuelAllocationSubmissionBtn"
                                                    class="btn btn-sm btn-success">
                                                <i class="fas fa-paper-plane"></i>
                                                Submit
                                            </button>
                                        </div>

                                    </div>
                                    <div class="col-3">
                                        <div id="vehicleDetailsContainer" style="display: none;"
                                             class="col-xs-12 col-sm-12 col-md-12">
                                            <table role="table"
                                                   aria-label="vehicle details"
                                                   class="table">
                                                <thead class="d-none">
                                                <tr>
                                                    <th scope="row"></th>
                                                </tr>
                                                </thead>
                                                <tbody id="vehicleDetails" class="vehicleDetails">
                                                </tbody>
                                            </table>
                                        </div>

                                        <div id="image_view" class="card text-center py-5 my-2" style="display: none;">
                                            <div class="form-group">
                                                <div class="imagePreview"></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!--begin::Card body-->
                    <div class="card-body">
                        <x-error-view/>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <x-employee-search-modal/>
    <input type="hidden" value="{{StatusHelper::active()}}"
           name="vehicleActive"
           id="vehicleActive"/>
    <input type="hidden"
           value="{{StatusHelper::onboardingComplete()}}"
           name="incompleteOnBoarding"
           id="incompleteOnBoarding"/>
    <input type="hidden" value="{{VehicleStatus::vehicleInWorkshop()}}"
           name="vehicleInWorkshop"
           id="vehicleInWorkshop"/>

    <input type="hidden" value=""
           name="material_quantity"
           id="material_quantity"/>

    <input type="hidden" value=""
           name="fuel_allocation"
           id="fuel_allocation"/>
@endsection

@push('scripts')

    <script>
        const appMessages = {
            permissionAlertWindowTitle: "Permission Assignment",
            validationFailureMessage: "Sorry, the data did not pass validation check," +
                "check the data and try again.",
            permissionsAttachedDefaultMessage: "Permission Assigned Successfully",
            noFuelAllocation: 'Vehicle has no not been assigned Fuel Allocation, '
                + 'Request System Administrator to assign allocation',
            inactiveEmployee: 'Employee with Staff_no @staff is not active',
            vehicleNotFound: ' No Vehicle Found, Check your input and try again',
            generalError: 'We could not complete processing your request, please try again later',
            invalidTripPeriod: 'You have selected more than the 7 Days Limit' +
                'If your trip is more than 7 days, you will have to create a second trip ',
            profileDelegationTitle: 'Profile Delegation',
            selfDelegation: 'You can not delegate a profile to the owner.'
        };

        function removeSubmissionAndDetailsOptions() {
            let elements = document.querySelectorAll('.when_valid');
            elements.forEach(function (element) {
                element.setAttribute('disabled', 'disabled');
            });

            document.querySelector('#vehicleDetailsContainer').style.display = 'none';
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
            document.querySelector('#image_view').style.display = null;
        }

        (function (tmsApp, $) {
            window['vehicleRegistrationCtl'] = $('#vehicleRegistration');
            let $vehicleRegistrationCtl = window['vehicleRegistrationCtl'];

            Inputmask({
                "mask": "A{1,3} 9{1,4}A{0,1}"
            }).mask("#vehicleRegistration");

            $vehicleRegistrationCtl.on('paste', function () {
                if (!this.value || this.value.indexOf('_') > -1) {
                    return;
                }
                setTimeout(function () {
                    findVehicle($vehicleRegistrationCtl);
                }, 300);
            });

            $('#vehicleSearchBtn').on('click', function () {
                if (!document.querySelector('#vehicleRegistration').value
                    || document.querySelector('#vehicleRegistration').value.indexOf('_') > -1) {
                    return;
                }

                findVehicle($vehicleRegistrationCtl);
            });

            $(document).on('submit', 'form[name="fuelAllocationForm"]', function (e) {
                e.preventDefault();
                e.stopPropagation();
                const form = this;
                const url = form.action;
                let formData = new FormData(form);

                tmsApp.confirm(
                    'Are you sure ?',
                    'You want to change the status of this vehicle',
                    'Yes',
                    'No, Cancel',
                    function () {
                        tmsApp.asyncPostFormData(
                            url,
                            formData,
                            function (asyncResponse) {
                                if (asyncResponse.hasOwnProperty('success')
                                    && asyncResponse['success']) {
                                    setTimeout(function () {
                                        tmsApp.showSystemMessage(
                                            'Status Change',
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
                                            'Status Change',
                                            asyncResponse['message'],
                                            function () {
                                            }, 'error');
                                    }, 300);
                                }
                            },
                            function (xhr, settings, errorThrown) {
                                setTimeout(function () {
                                    tmsApp.showErrorMessages(xhr, 'Status Change');
                                }, 300);
                            }
                        );
                    },
                    () => {

                    },
                    255,
                    true
                );

            });

            let populateVehicleDetails = function (payload) {
                let vehicle = payload['vehicle'];
                let images = payload['images'];
                let vehicle_state = payload['vehicle_state'];

                if (!vehicle || !vehicle.brand_name) {
                    return;
                }

                if (vehicle['status'] === $('[name="vehicleInWorkshop"]').val()) {
                    tmsApp.showSystemMessage("Vehicle State",
                        vehicle_state,
                        () => {
                        },
                        "error"
                    );
                    return;
                }

                let vLabel = vehicle['body_type_name'] ? vehicle['body_type_name'] : ''
                    + ' ' + vehicle['brand_name']
                    + ' ' + vehicle['model_name']
                    + ' ' + vehicle['model_code'];
                $("#vehicle_description").val(vLabel);
                $("#vehicle_status").text(vehicle['status_name']);

                enableSubmissionAndDetailsOptions();

                if (images && images.length > 0) {
                    let frontViewImages = images.filter((image) => {
                        return image['file_type'] === 'Front View';
                    })
                    let imagePath = frontViewImages[0]?.path;
                    document.querySelector(".imagePreview")
                        .style
                        .backgroundImage = "url(/storage" + imagePath + ")";
                }

            }

            let findVehicle = function ($vehicleRegistrationCtl) {
                const numberPlate = $vehicleRegistrationCtl.val();
                let formData = new FormData();
                formData.append('vehicle_registration', numberPlate);

                tmsApp.asyncGetFormData(
                    $vehicleRegistrationCtl.attr('data-action') + '?vehicle_registration=' + numberPlate,
                    formData,
                    function (response_data) {
                        if (response_data.success === 'true'
                            || response_data.success === true) {
                            populateVehicleDetails(response_data.payload, response_data['message']);

                            let $odometerCtrl = $('[data-validation="fuelRequisitionOdometerReading"]');
                            if ($odometerCtrl.val()) {
                                $odometerCtrl.trigger('change');
                            }
                        } else {
                            removeSubmissionAndDetailsOptions();
                            let $message = response_data['message']
                                ? response_data['message']
                                : appMessages.vehicleNotFound;
                            tmsApp.systemError('Vehicle', $message);
                        }
                    },
                    function (xhr) {
                        tmsApp.systemError('System Message',
                            appMessages.generalError);
                    }
                )
            }

        })(window.tmsApp || {}, jQuery);


        (function(tmsApp, zesco){
            zesco(document).ready();


        })(window.tmsApp, jQuery)
    </script>

@endpush
