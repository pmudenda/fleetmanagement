@extends('layouts.app')
@push('styles')
@endpush
@section('content')

    <x-content-header/>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h4>New Fuel Requisition</h4>
                </div>
                <div id="actionButtonsContainer" class="card-toolbar justify-content-end" style="display: none;">
                    <button type="button" id="submitRequisitionBtn" class="btn btn-success btn-sm mr-3">
                        <i class="fas fa-save"></i> Submit
                    </button>
                    <button type="button" id="resetRequisitionBtn" class="btn btn-danger btn-sm mr-3">
                        <i class="fas fa-undo"></i> Cancel
                    </button>

                </div>
            </div>

            <div class="card-bod pb-4 min-h-600px pt-0">

                <x-error-view/>

                <form name="fuelRequisitionForm" id="fuelRequisitionForm" action="{{route('save.fuel.requisition')}}"
                      method="post">
                    @csrf
                    <div class="card-body user-data">
                        <div class="container-fluid">
                            <div class="table-responsive mt-3">
                                <table class="table">
                                    <thead>
                                    <tr class="">
                                        <th>Make</th>
                                        <th>Model</th>
                                        <th>Registration</th>
                                        <th>User Unit Assigned</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td id="make"></td>
                                        <td id="model"></td>
                                        <td id="registration"></td>
                                        <td id="assigned"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <label class="app-required-marker"></label>
                        <div class="container-fluid mt-2">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-5">
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
                                                               data-action="{{route('api.vehicle')}}"
                                                               class="form-control form-control-sm"
                                                               id="vehicle_registration"
                                                               placeholder="Vehicle Registration e.g AAB 6757"
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

                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                                    <input type="hidden" class="form-control form-control-sm"
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
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <div
                                                    class="col-xs-12 col-sm-6 col-md-5 col-lg-4 control-input-wrapper">
                                                    <div class="control-input">
                                                        <div class="link-field ui-front" style="position: relative;">
                                                            <label class="form-check-inline">
                                                                <input type="radio"
                                                                       class="list-row-checkbox bold mr-3"
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
                                                           value="{{$costCenter->code_cost_center}}"
                                                           name="cost_centre_code"
                                                           required readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-10">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="cost_center_name"
                                                           value="{{$costCenter->description}}"
                                                           name="cost_center_name"
                                                           required readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-5">
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
                                                                       class="list-row-checkbox bold mr-3"
                                                                       autocomplete="off"
                                                                       name="CostAssignedTo"
                                                                       value="ProjectBasedRequisition">
                                                                Project
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="project_code"
                                                           name="project_code"
                                                           required readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                       for="staff_name">
                                                    Requisition Type:
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <select name="requisition_type" id="requisition_type"
                                                            class="form-control form-select-sm"
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

                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-4"
                                                       for="staff_name">
                                                    Odometer Reading :
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="odometer_reading"
                                                           name="odometer_reading"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row d-none" id="outOfTown">
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                       for="mobile_no">Departure Date:</label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="date" class="form-control form-control-sm"
                                                           id="departure_date"
                                                           max="{{ date('Y-m-d', strtotime(\Carbon\Carbon::now())) }}"
                                                           name="departure_date"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                       for="request_date">Return Date:</label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="return_date"
                                                           readonly
                                                           max="{{ date('Y-m-d', strtotime(\Carbon\Carbon::now())) }}"
                                                           name="return_date">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
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
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                       for="request_date">Request Date:</label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="request_date"
                                                           readonly
                                                           value="{{\Carbon\Carbon::now()->format('d/m/y')}}"
                                                           name="request_date">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-12 col-md-5 col-lg-4 field-required"
                                                       for="next_fuel_date">
                                                    Next Refueling Date :
                                                </label>
                                                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-6">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="next_fuel_date"
                                                           value="{{\Carbon\Carbon::now()->add('days', $daysToNextRefuel)->format('d/m/y')}}"
                                                           name="next_fuel_date"
                                                           readonly required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                       for="mobile_no">Purpose:</label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8">
                                                        <textarea type="text"
                                                                  id="justification"
                                                                  name="justification"
                                                                  style="height: 129px;"
                                                                  class="form-control form-control-sm"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-5">
                                    <div class="container-fluid pl-0">
                                        <div class="row">
                                            <div class="form-group row">
                                                {{--<label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                                       for="user_type_id">Group :</label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                    <select name="user_role_id" id="user_role_id"
                                                            class="form-control form-select-sm"
                                                            required>
                                                        <option value=""> --Choose Group--</option>

                                                    </select>
                                                </div>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr class="bg-dark">
                                        <th>Material Description</th>
                                        <th>Project Number</th>
                                        <th>Qty</th>
                                        <th>Unit Of Measure</th>
                                        <th>Price</th>
                                        <th>Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Material Description</td>
                                        <td>
                                            <input type="text" name="projectCode" readonly value="000000"
                                                   class="form-contol form-control-sm border-0"/>
                                        </td>
                                        <td><span>0</span></td>
                                        <td>Unit Of Measure</td>
                                        <td>Price</td>
                                        <td>Amount(ZMW)</td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td></td>
                                        <td class="text-right"><strong>Total Quantity</strong></td>
                                        <td><span class="text-bold" id="totalQty">0.00</span></td>
                                        <td></td>
                                        <td class="text-right"><strong>Total Amount</strong></td>
                                        <td><span class="text-bold" id="totalAmount">0.00</span></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>

                <input type="hidden" value="{{ route('user.search') }}" id="newUserSearchUrl">
            </div>
        </div>
    </section>
@endsection
@push('scripts')

    <script>
        function populateVehicleDetails(vehicle) {

            if (vehicle) {
                let vLabel = vehicle['body_type_name'] + ' ' + vehicle['brand_name'] + ' ' + vehicle['model_name'] + ' ' + vehicle['model_code'];
                $("#vehicle_description").val(vLabel);
                document.querySelector('#actionButtonsContainer').style.display = null;
            }
        }

        function findVehicle(numberPlate) {

            let formData = new FormData();
            formData.append('vehicle_registration', numberPlate);

            tmsApp.tmsUtility.asyncGetFormData(
                $('#vehicle_registration').attr('data-action'),
                formData,
                function (response_data) {
                    if (response_data.success === 'true' || response_data.success === true) {
                        populateVehicleDetails(response_data.payload);
                    } else {
                        alert('No Vehicle Found, Check your input and try again')
                    }
                },
                function (xhr) {
                    console.log(xhr);
                    alert('We could not complete processing your request, please try again later')
                }
            )
        }

        $(document).ready(function () {
            $('#vehicle_registration').on('change', function () {
                if (this.value && this.value.length < 6) {
                    return;
                }

                findVehicle(this.value);
            });

            $('#vehicleSearchBtn').on('change', function () {
                if (document.querySelector('#vehicle_registration').value && document.querySelector('#vehicle_registration') < 6) {
                    return;
                }
                //findVehicle(document.querySelector('#vehicle_registration').value);
            });

            $('#submitRequisitionBtn').on('click', function () {
                let $form = document.forms['fuelRequisitionForm'];
                let formData = new FormData($form);
                tmsApp.confirm(
                    'Fuel Requisition',
                    'Are you sure you want to submit this request ?',
                    'Yes',
                    'No',
                    function () {
                        tmsApp.tmsUtility.asyncPostFormData(
                            $form.action,
                            formData,
                            function (asyncResponse) {
                                if ('success' in asyncResponse && asyncResponse['success']) {
                                    setTimeout(function () {
                                        tmsApp.showSystemMessage(
                                            'Fuel Requisition',
                                            asyncResponse['message'],
                                            function () {
                                            },
                                            'success'
                                        );
                                    }, 300);
                                } else {
                                    if (asyncResponse.hasOwnProperty('errors')) {
                                        tmsApp.tmsUtility.printErrorMsg(asyncResponse.errors);
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
                                console.log(xhr, errorThrown);
                                setTimeout(function () {
                                    tmsApp.systemError(
                                        'Fuel Requisition',
                                        'We could not complete processing your request, please try again later',
                                        function () {
                                        });
                                }, 300)
                            }
                        )
                    },
                    function () {

                    }
                )


            })

            $('#resetRequisitionBtn').on('click', function () {
                document.forms['fuelRequisitionForm'].reset();
                document.querySelector('#actionButtonsContainer').style.display = 'none';
            });

            $('select[name="requisition_type"]').on('change', function () {
                if (this.value === '011') {
                    $("#outOfTown").removeClass('d-none');
                } else {
                    $("#outOfTown").addClass('d-none');
                }
            });

            $('input[name="CostAssignedTo"]').on('change', function () {
                console.log(this.value)
                if (this.value === 'CostCenterBasedRequisition') {
                    $('#project_code').prop('readonly', true)
                } else if (this.value === 'ProjectBasedRequisition') {
                    $('#project_code').prop('readonly', false);
                    $('#cost_centre_code').prop('required', false);
                    $('#cost_center_name').prop('required', false);
                }
            });
        })
    </script>
    {{--  <script src="{{asset('application/modules/userManagement/users/add_user.js')}}"></script>
      <script src="{{asset('application/modules/userManagement/users/table.js')}}"></script>
      <script src="{{asset('application/modules/userManagement/users/users-search.js')}}"></script>--}}
@endpush
