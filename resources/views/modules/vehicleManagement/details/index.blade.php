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
                    <h4>Vehicle Details</h4>
                </div>
                <div id="actionButtonsContainer" class="card-toolbar justify-content-end">
                </div>
            </div>

            <div class="card-body pb-4 min-h-600px pt-0 pl-2">

                <x-error-view/>

                <form name="fuelRequisitionForm" id="fuelRequisitionForm" action="{{route('save.fuel.requisition')}}"
                      method="post">
                    @csrf
                    <div class="card-body user-data pl-0">
                        <div class="container-fluid mt-2">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-3 app-field-label field-required"
                                                                for="staff_no">Registration #:
                                                            </label>
                                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                           data-action="{{route('vehicle.details')}}"
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                       {{-- <div class="container-fluid">
                            <div id="materialDetailsContainer" class="table-responsive mt-3" style="display: none;">
                                <table id="materialDetailsTable" class="table table-bordered">
                                    <thead>
                                    <tr class="bg-dark">
                                        <th>Material Description</th>
                                        <th>Project Number</th>
                                        <th>Qty</th>
                                        <th>Unit Of Measure</th>
                                        <th>Price</th>
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
                                        <td>
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
                                </table>
                            </div>
                        </div>--}}
                    </div>
                </form>

                <input type="hidden" value="{{ route('user.search') }}" id="newUserSearchUrl">
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <script>

        (function (tmsApp, $) {

            function populateVehicleDetails(payload) {
                let vehicle = payload['vehicle'];
                let article = payload['article'];

                if (vehicle && vehicle.brand_name) {

                    if (typeof vehicle.fuel_allocation === 'undefined' || vehicle.fuel_allocation == null || vehicle.fuel_allocation === "0") {

                        tmsApp.showSystemMessage("Vehicle Details Incomplete", 'Vehicle has no Fuel Allocation, Request System Administrator to assign allocation', () => {
                        }, "error")

                        return;
                    }

                    if (vehicle['on_boarding_status'] != '030') {
                        tmsApp.showSystemMessage("Vehicle Details Incomplete", 'Vehicle did not complete onboarding process, You can not proceed with requisition', () => {
                        }, "error")

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
                               <tr>
                                     <th>Registration</th><td id="registration">${vehicle['registration_number']}</td>
                                </tr>`;

                    $('tbody#vehicleDetails').html(row);

                    if (vehicle.fuel_allocation) {
                        let perWeekAllocation = vehicle.fuel_allocation * 7;
                        document.querySelector('[name="fuel_allocation"]').value = perWeekAllocation ?? 0;
                        document.querySelector('[name="material_quantity"]').value = perWeekAllocation ?? 0;
                        document.querySelector('[name="material_quantity"]').setAttribute('max', perWeekAllocation);

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
                            //removeSubmissionAndDetailsOptions();
                            //document.querySelector('#materialDetailsContainer').style.display = 'none';
                            tmsApp.showToast('No Vehicle Found, Check your input and try again', 'error');
                        }
                    },
                    function (xhr) {
                        console.log(xhr);
                        tmsApp.showToast('We could not complete processing your request, please try again later')
                    }
                )
            }

           /* $('#vehicle_registration').keyup(function () {
                this.value = this.value.toLocaleUpperCase();
            });*/

            Inputmask({
               "mask": "AAA 9999"
           }).mask("#vehicle_registration");

            $('#vehicle_registration').on('change paste keyup', function () {
                if (this.value && this.value.length < 6) {
                    return;
                }
                //removeSubmissionAndDetailsOptions();
                //document.querySelector('#vehicleDetailsContainer').style.display = 'none';
                //$('tbody#vehicleDetails').html('');
                findVehicle();
            });



            $('#vehicleSearchBtn').on('click', function () {
                if (document.querySelector('#vehicle_registration').value && document.querySelector('#vehicle_registration') < 6) {
                    return;
                }
                //removeSubmissionAndDetailsOptions();
                findVehicle();
            });

            /*$('#materialDetailsTable').on('change', 'select,input', function (e) {
                eventHandler(this, e);
            }).on('keyup', 'select,input,textarea', function (e) {
                eventHandler(this, e);
            }).on('blur', 'input', function (e) {
                if (this.name === 'quantity') {
                    $(this).val(tmsApp.numberFormat(this.value));
                }

            });*/

        })(window.tmsApp || {}, jQuery)
    </script>
@endpush
