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

    <x-content-header :pageTitle="'Charge out rate'" :activeCrumb="'Charge Out Rate'" :link="'home'"
                      :linkText="'Charge Out Rate'"/>
    <section class="content">
        <div class="row g-12 g-xl-12" id="kt_app_main">
            <form name="tms_charge_out_form"
                  id="tms_charge_out_form"
                  class="form"
                  action="{{route('save.charge.out.rate')}}">
                <!--BEGIN:::VEHICLE HEADER -->
                <div class="card mb-xl-10">
                    <div id="card_header" class="card-header min-h-2px">
                        <div class="card-header">
                            <div class="card-title">
                                <h2> Charge out rate</h2>
                                <span
                                    class="ml-2 indicator-pill whitespace-nowrap orange">
                                    <span>Not Saved</span>
                                </span>
                            </div>
                            <div id="actionButtonsContainer" class="card-toolbar justify-content-end">

                                <button type="button" id="submitSaveChargeRateBtn"
                                        class="btn btn-success btn-sm mr-3 when_odo_valid">
                                    <i class="fas fa-save"></i>
                                    Submit
                                </button>

                                <button type="reset" id="resetRequisitionBtn" class="btn btn-danger btn-sm mr-3">
                                    <i class="fas fa-undo"></i>
                                    Clear Data
                                </button>

                            </div>
                        </div>
                        <!--begin::Card body-->
                        <div class="card-body">
                            <x-error-view/>

                            <div class="row  mt-5">
                                <!--- LEFT -->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="brand" class="fs-6 fw-semibold form-label col-md-3">
                                            <span class="required">Brand/Make</span>
                                        </label>
                                        <div class="col-md-9 fv-row">
                                            <div class="col-md-9">
                                                <div class="w-100 fv-row">
                                                    <select class="form-control view_mode" name="brand" id="brand">
                                                        <option>--Select Brand--</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="model" class="fs-6 fw-semibold form-label col-md-3">
                                            <span class="required">Model</span>
                                        </label>
                                        <div class="col-md-9 fv-row ">
                                            <div class="col-md-9">
                                                <div class="w-100">
                                                    <select class="form-select form-select-sm view_mode" required
                                                            name="model" id="model">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="model_code" class="fs-6 fw-semibold form-label col-md-3">
                                            <span class="required">Model Code</span>
                                        </label>

                                        <div class="col-md-9 fv-row">
                                            <div class="col-md-9">
                                                <div class="w-100">

                                                    <input class="form-control form-control-solid" name="model_code"
                                                           readonly id="model_code"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="brand" class="fs-6 fw-semibold form-label col-md-3">
                                            <span class="required">Body Type</span>
                                        </label>

                                        <div class="col-md-9 fv-row ">
                                            <div class="col-md-9">
                                                <div class="w-100">
                                                    <select class="form-select form-select-sm view_mode" required
                                                            id="bodyType" name="bodyType">
                                                    </select>
                                                    <input type="hidden" id="bodyType_holder" name="bodyType_holder"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--- RIGHT -->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="brand" class="fs-6 fw-semibold form-label col-md-3">
                                            <span class="required">Charge</span>
                                        </label>
                                        <div class="col-md-9 fv-row">
                                            <div class="col-md-9">
                                                <div class="w-100 fv-row">
                                                    <input class="form-control form-control-solid" name="rate"
                                                           placeholder="Enter charge rate"
                                                           type="text"

                                                           id="rate"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!--END:::VEHICLE HEADER -->
            <input type="hidden"
                   id="brands-api"
                   value="{{ route('brands.get') }}">
            <input type="hidden"
                   id="modelEndpoint"
                   name="modelEndpoint"
                   value="{{ route('models.get') }}">
            <input type="hidden"
                   id="bodyTypesEndpoint"
                   name="bodyTypesEndpoint"
                   value="{{ route('body_type.get') }}">
        </div>
    </section>
@endsection

@push('scripts')

    <script>

        (function (tmsApp, $) {

            console.log("%c✔ ZESCO Fleet Master Running", "color: #148f32");
            console.log("%c✔ Vehicle OnBoarding Process", "color: #148f32");

            function getVehicleBrands() {
                fetch(document.querySelector('#brands-api').value)
                    .then(response => response.json())
                    .then(response => {
                        let selectElem = $('select[name="brand"]');
                        // Populate results
                        if (response.state === 'failure') {
                            //show errors
                            toastr.error('Connection error, no data found')
                            return;
                        }

                        //app.vehicleBrands = response['payload'];
                        //app.engineBrands = response['payload'];
                        let vehicleBrands = response['payload'];
                        tmsApp.populateDropDownList(selectElem, vehicleBrands, "id", ["name"], "");

                        let brand_id = selectElem.attr('data-value');
                        console.log(brand_id);
                        if (brand_id) {
                            selectElem.val(brand_id);
                            selectElem.trigger('change');
                        }
                    })
                    .catch(function (error) {
                        // notify of error
                        toastr.error(
                            'Connection error. Could not retrieve data, some feature might not work.')
                    });
            }

            function nativeVehicleBrandChanged() {
                const brandId = $('select[name="brand"]').val()?.toString().trim();

                console.log('Brand Value ' + brandId);

                if (!brandId) {
                    return;
                }

                let filteredResults = window.VehicleModels.filter(function (model) {
                    console.log(model);
                    return model.brand_guid?.toString().trim() === brandId?.toString().trim();
                });

                if (filteredResults.length === 0) {
                    //toastr.warning('No Models Found for the selected models', 'Models')
                    getConfiguredModels();
                }

                let selectElem = $('select[name="model"]');
                tmsApp.populateDropDownList(selectElem, filteredResults, "id", ["model_name", "model_code"], " => ");

                let model = selectElem.attr('data-value');

                console.log('Model Id', model);
                if (model) {
                    selectElem.val(model);
                    selectElem.trigger('change');
                }
            }

            function postVehicleHeaderData() {
                $('.print-error-msg').css('display', 'none');

                if (!$('form[name="tms_charge_out_form"]').valid()) {
                    toastr.warning(
                        "Sorry, the data did not pass validation check, check the data and try again."
                    );
                    return;
                }

                let $form = document.forms['tms_charge_out_form'];

                tmsApp.asyncPostFormData(
                    $form.action,
                    new FormData($form),
                    function (asyncResponse) {
                        if (asyncResponse.hasOwnProperty('state') && asyncResponse.state != 'success') {
                            if (asyncResponse.hasOwnProperty('errors')) {
                                tmsApp.printErrorMsg(asyncResponse.errors);
                                return
                            }

                            setTimeout(function () {
                                tmsApp.systemError(
                                    'Vehicle Charge-Out Rate',
                                    asyncResponse['message'],
                                    function () {
                                    }, 'error');
                            }, 300);
                            toastr.error(
                                asyncResponse.message
                            );
                            return;
                        }

                        tmsApp.showSystemMessage(
                            'Vehicle Charge-Out Rate',
                            asyncResponse.message,
                            function () {
                                setTimeout(
                                    function () {
                                        window.location.reload()
                                    }, 500
                                );
                            }, 'success');
                    },
                    function (xhr, settings, errorThrown) {
                        setTimeout(function () {
                            tmsApp.showErrorMessages(xhr, 'Vehicle Charge-Out Rate');
                        }, 300)
                    });
            }

            function getConfiguredModels() {
                fetch(document.querySelector('#modelEndpoint').value)
                    .then(response => response.json())
                    .then(response => {
                        // Populate results
                        if (response.state === 'failure') {
                            //show errors
                            toastr.error('Connection error, no data found')
                            return;
                        }
                        window.VehicleModels = response['payload'];
                    })
                    .catch(function (error) {
                        // notify of error
                        toastr.error('Connection error. Could not retrieve data, some feature might not work.')
                    });
            }

            function getBodyTypes() {
                fetch(document.querySelector('#bodyTypesEndpoint').value)
                    .then(response => response.json())
                    .then(response => {

                        let selectElem = $('select[name="bodyType"]');
                        // Populate results
                        if (response.state === 'failure') {
                            //show errors
                            toastr.error('Failed to get Vehicle Body Types', 'Connection error');
                            return;
                        }

                        let bodyTypes = response['payload'];
                        tmsApp.populateDropDownList(selectElem, bodyTypes, "id", ["body_type_name"], "");

                        let bodyTypeId = selectElem.attr('data-value');
                        if (bodyTypeId) {
                            selectElem.val(bodyTypeId);
                            selectElem.trigger('change');
                        }
                    })
                    .catch(function (error) {
                        // notify of error
                        toastr.error(
                            'Connection error. Could not retrieve data, some feature might not work.')
                    });
            }

            tmsApp.appFormValidator('form[name="tms_charge_out_form"]',
                {
                    'brand': {
                        required: true
                    },
                    'model': {
                        required: true
                    },
                    'model_code': {
                        required: true
                    },
                    'bodyType': {
                        required: true
                    }
                },
                {
                    'brand': {
                        required: "Vehicle brand is required"
                    },
                    'model': {
                        required: "You must declare vehicle model"
                    },
                    'model_code': {
                        required: "Vehicle Model code is required"
                    },
                    'bodyType': {
                        required: "Body type is required"
                    }
                }
            );


            $(document).on('change', 'select[name="brand"]', function () {
                nativeVehicleBrandChanged();
            });


            $(document).on('change', 'select[name="model"]', function () {
                const modelId = $(this).val()?.toString().trim();
                if (!modelId) {
                    return;
                }
                console.log(modelId);
                let filteredResults = window.VehicleModels.filter(function (model) {
                    return model.id?.toString().trim() === modelId;
                });

                if (filteredResults.length > 0) {
                    document.querySelector('#model_code').value = filteredResults[0]?.model_code;
                }
                console.log(filteredResults);
            });

            $("#submitSaveChargeRateBtn").on('submit', function (e) {
                //e.stopPropagation();
                //e.preventDefault();
                postVehicleHeaderData();
            });

            getConfiguredModels();

            getVehicleBrands();

            getBodyTypes();

        })(window.tmsApp || {}, jQuery);

        let app = new Vue({
            'el': '#kt_app_main',
            components: {},
            data() {
                return {
                    isHeaderSaved: true,
                    seTypes: [],
                    organizationalUnits: [],
                    vehicle_brand_placeholder: 'Select Vehicle Brand',
                    vehicle_model_placeholder: 'Select Model',
                    weightDetails: {
                        trailerWeight2: 0
                    }
                }
            },
            methods: {

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

                postVehicleHeaderData() {
                    if (!this.validators) {
                        return alert('No Validator Configured');
                    }
                    this.vehicleHeaderFormValidator.validate().then(function (status) {
                        console.log('validated!');
                        if (status !== 'Valid') {
                            toastr.warning(
                                "Sorry, the data did not pass validation check, check the data and try again."
                            );
                            return;
                        }

                        let el = document.querySelector('#tms_save_vehicle');
                        el.setAttribute('data-kt-indicator', 'on');
                        el.disabled = true;

                        app.postRequest(
                            new FormData($(app.vehicleHeaderForm)[0]),
                            app.vehicleHeaderForm.action,
                            function (response) {
                                let el = document.querySelector('#tms_save_vehicle');
                                let label = el.querySelector(".indicator-label");

                                setTimeout(function () {
                                    el.removeAttribute('data-kt-indicator');
                                    el.disabled = false;
                                }, 300)

                                if (response.data.state != 'success') {
                                    toastr.error(
                                        response.data.message
                                    );
                                    return;
                                }

                                app.vehicleHeaderId = response.data.payload.id;
                                toastr.success(
                                    response.data.message
                                );

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

                                toastr.error(
                                    error.message
                                );

                            });
                    });

                },


                registrationTypeChanged(selectedType) {
                    console.log(selectedType)
                },

                transmissionTypeChanged: function (transmissionType) {
                    document.querySelector('#transmission_type').value = transmissionType?.code + ':' + transmissionType?.name;
                },

            }
        });
    </script>

@endpush
