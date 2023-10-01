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
                            <h4>Fuel Allocation</h4>
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
                        <h2> Fuel Allocation form</h2>
                        <span class="ml-2 indicator-pill whitespace-nowrap orange"><span>Not Saved</span></span>
                    </div>

                    <form name="fuelRequisitionForm"
                          id="fuelRequisitionForm"
                          action="{{route('vehicle.fuel.save')}}" method="post">
                        @csrf
                        <div class="card-body user-data">
                            <label class="app-required-marker"></label>
                            <div class="container-fluid mt-2">
                                <div class="row">
                                    <div class="col-9">
                                        <div class="row pl-0">
                                            <div class="col-9">
                                                <div class="col-xs-12 col-sm-6 col-md-6">
                                                    <div class="container-fluid pl-0">
                                                        <div class="row">
                                                            <div class="form-group row">
                                                                <label class="col-xs-12 col-sm-6
                                                                col-md-5 col-lg-4 field-required"
                                                                       for="vehicle_registration">
                                                                    Registration #:
                                                                </label>
                                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
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
                                                <div class="col-xs-12 col-sm-6 col-md-6">
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
                                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                                        <div class="container-fluid pl-0">
                                                            <div class="row">
                                                                <div class="form-group row">
                                                                    <label
                                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                            for="staff_number">Start-Date:
                                                                    </label>
                                                                    <div
                                                                            class="col-xs-12 col-sm-6 col-md-7 col-lg-8">
                                                                        <div class="input-group">
                                                                            <input type="date"
                                                                                   onkeydown="return false"
                                                                                   class="form-control
                                                                   form-control-sm"
                                                                                   id="startDate"
                                                                                   min="{{date('Y-m-d', strtotime(Carbon::now()))}}"
                                                                                   name="startDate"
                                                                                   required
                                                                            />
                                                                            <div class="input-group-append">
                                                                                <div class="input-group-text">
                                                                                    <i class="fas fa-calendar">
                                                                                    </i>
                                                                                </div>
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
                                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4"
                                                                            for="staff_number">End Date:
                                                                    </label>
                                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8">
                                                                        <input type="date"
                                                                               onkeydown="return false"
                                                                               class="form-control
                                                               form-control-sm"
                                                                               id="endDate"
                                                                               min="{{date('Y-m-d', strtotime(Carbon::now()))}}"
                                                                               name="endDate"
                                                                               required
                                                                        />
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
                                                                    class="col-xs-12 col-sm-6 col-md-5 col-lg-4 pl-0 field-required"
                                                                    for="remarks">
                                                                Remarks :
                                                            </label>
                                                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8 pl-0">
                                                    <textarea type="text"
                                                              id="remarks"
                                                              minlength="50"
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
                                                    id="profileDelegationBtn"
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

    <script src="{{asset('modules/common/vehicle.details.js')}}"></script>
    <script>
        (function (tmsApp, $) {
            window['vehicleRegistrationCtl'] = $('#vehicleRegistration');
            let $vehicleRegistrationCtl = window['vehicleRegistrationCtl'];

            Inputmask({
                "mask": "AAA 9999"
            }).mask("#vehicleRegistration");

            $vehicleRegistrationCtl.on('paste', function () {
                if (!this.value || this.value.indexOf('_') > -1) {
                    return;
                }
                setTimeout(function () {
                    tmsApp.findVehicle($vehicleRegistrationCtl);
                }, 300);
            });

            $('#vehicleSearchBtn').on('click', function () {
                if (!document.querySelector('#vehicleRegistration').value
                    || document.querySelector('#vehicleRegistration').value.indexOf('_') > -1) {
                    return;
                }

                tmsApp.findVehicle($vehicleRegistrationCtl);
            });

        })(window.tmsApp || {}, jQuery);
    </script>

@endpush
