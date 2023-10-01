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

                    {{--vehicle reg number --}}
                    {{--vehicle photos --}}
                    {{--vehicle fuel type --}}
                    {{--vehicle current allocation --}}
                    {{--vehicle daily == / number of days--}}
                    {{--vehicle TOtal == --}}
                    {{--vehicle allocation fuel comment == --}}

                    <form name="fuelRequisitionForm" id="fuelRequisitionForm"
                          action="http://127.0.0.1:8000/requisitions/fuel/save" method="post">
                        <input type="hidden" name="_token" value="oUzo9VdwnBw13JoY5MccxVAWrWPkeRXhypM4fmon">
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
                                                                class="col-xs-12 col-sm-6
                                                                col-md-5 col-lg-4 field-required"
                                                                for="vehicle_registration">Registration #:
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

                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6 col-md-6">
                                                <div class="container-fluid pl-0">
                                                    <div class="row">
                                                        <div class="form-group row">
                                                            <div
                                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4
                                                                control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <label class="form-check-inline">
                                                                            <input type="radio" id="costOnCostCentre"
                                                                                   class="list-row-checkbox
                                                                                   bold mr-3 when_valid"
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
                                                                       id="cost_centre_code" value="14456"
                                                                       name="cost_centre_code" required readonly>
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
                                                                class=" col-xs-12 col-sm-6 col-md-5
                                                                 col-lg-4 control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <label class="form-check-inline">
                                                                            <input type="radio" id="projectInput"
                                                                                   class="list-row-checkbox
                                                                                   bold mr-3 when_valid"
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
                                                                class="col-xs-12 col-sm-6
                                                                col-md-5
                                                                col-lg-4 field-required"
                                                                for="staff_name">
                                                                Requisition Type:
                                                            </label>
                                                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                <select name="requisition_type"
                                                                        id="requisition_type"
                                                                        disabled
                                                                        class="form-control
                                                                        form-select-sm when_valid"
                                                                        required>
                                                                    <option value=""> --Select--</option>
                                                                    <option value="010">Normal</option>
                                                                    <option value="011">Out 0f Town</option>
                                                                    <option value="012">Override</option>
                                                                </select>
                                                            </div>
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
                                            <h2 class="fs-2x fw-bold mb-10">Front View</h2>
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
                    tmsApp.findVehicle();
                }, 300);
            });

            $('#vehicleSearchBtn').on('click', function () {
                if (!document.querySelector('#vehicleRegistration').value
                    || document.querySelector('#vehicleRegistration').value.indexOf('_') > -1) {
                    return;
                }

                findVehicle();
            });

        })(window.tmsApp || {}, jQuery);
    </script>

@endpush
