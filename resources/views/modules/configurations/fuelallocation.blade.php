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

            <!--BEGIN:::VEHICLE HEADER -->
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
                                                                           id="vehicle_registration"
                                                                           placeholder="Vehicle Reg e.g AAB 6757"
                                                                           name="vehicle_registration"
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

            <!--END:::VEHICLE HEADER -->

            <!--END:::DETAILS  -->
            <input type="hidden" id="brands-api" value="{{ route('brands.get') }}">
            <input type="hidden" id="modelEndpoint" name="modelEndpoint" value="{{ route('models.get') }}">
            <input type="hidden" id="bodyTypesEndpoint" name="bodyTypesEndpoint" value="{{ route('body_type.get') }}">
        </div>
    </section>
    <x-employee-search-modal/>
@endsection

@push('scripts')

    <script>
        (function (tmsApp, $) {

            function formatMoney(event) {
                setTimeout(function () {
                    //ZMW
                    let formatted = accounting.formatMoney(event.target.value, '');
                    //app['chassisDetails'].chargeOutRate = formatted;
                }, 300);
            }

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
                    return model.brand_code?.toString().trim() === brandId?.toString().trim();
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

                if (!$('form[name="vehicleHeaderForm"]').valid()) {
                    toastr.warning(
                        "Sorry, the data did not pass validation check, check the data and try again."
                    );
                    return;
                }

                let $form = document.forms['vehicleHeaderForm'];

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
                                    'Vehicle On-Boarding',
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
                            'Vehicle OnBoarding',
                            asyncResponse.message,
                            function () {
                                setTimeout(
                                    function () {
                                        window.location.href = asyncResponse['redirectUrl']
                                    }, 500
                                );
                            }, 'success');
                    },
                    function (xhr, settings, errorThrown) {
                        setTimeout(function () {
                            tmsApp.showErrorMessages(xhr, 'Vehicle On-Boarding');
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


            tmsApp.appFormValidator('form[name="vehicleHeaderForm"]',
                {
                    'brand': {
                        required: true
                    },
                    'registrationNumber': {
                        required: true
                    },
                    'model': {
                        required: true
                    },
                    'vehicleLocation': {
                        required: true
                    },
                    'model_code': {
                        required: true
                    },
                    'bodyType': {
                        required: true
                    },
                    'userUnit': {
                        required: true
                    }
                },
                {
                    'brand': {
                        required: "Vehicle brand is required"
                    },
                    'registrationNumber': {
                        required: "Registration number is required"
                    },
                    'model': {
                        required: "You must declare vehicle model"
                    },
                    'vehicleLocation': {
                        required: "Vehicle location is mandatory"
                    },
                    'model_code': {
                        required: "Vehicle Model code is required"
                    },
                    'bodyType': {
                        required: "Body type is required"
                    },
                    'userUnit': {
                        required: "Select the user unit responsible for the vehicle"
                    }
                }
            );


// vehicleWeightValidations
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
                    assignmentDetails: {},
                    assignmentDetailsForm: null,
                    bodyDetails: {
                        numberOfSeats: 0,
                        volumeOfBootTanker:
                            0,
                        seatCapRear:
                            0
                    },
                    bodyDetailsForm: null,
                    bodyTypes: [],
                    businessAreas: [],
                    businessUnits: [],
                    chassisDetails: {
                        stickerRegistrationNumber: null,
                        status: 'active'
                    },
                    chassisDetailsForm: null,
                    chassisDetailsFormValidator: null,
                    configuredModels: [],
                    costCenters: [],
                    costingAndValuation: {},
                    costingDetailsForm: null,
                    dataStatus: 0,
                    directorates: [],
                    document_validity: {
                        state: null,
                        message: null
                    },
                    documents: {},
                    engineBrands: [],
                    engineDetails: {},
                    engineDetailsForm: null,
                    engineDetailsFormValidator: null,
                    fuelTypes: [],
                    images: {
                        frontView: null,
                        rearView: null,
                        leftView: null,
                        rightView: null,
                    },
                    licenseTypes: [],
                    organizationalUnits: [],
                    otherDetails: {},
                    /*  regNumberValidity: {
                          state: null,
                          message: null
                      },*/
                    registrationTypes: [],
                    searchedEmployeesList: [],
                    selectedBrandModels: [],
                    // forms
                    selectedModelCodes: [],
                    supplierList: [],
                    transmissionTypes: [],
                    validators: [],
                    vehicleBrands: [],
                    vehicleHeader:
                        {
                            model: {},
                            isHeaderSaved: false,
                            registration_type: 'MV'
                        },
                    // validators
                    vehicleHeaderForm: null,
                    vehicleHeaderFormValidator: null,
                    vehicleHeaderId: null,
                    vehicle_brand_placeholder: 'Select Vehicle Brand',
                    vehicle_model_placeholder: 'Select Model',
                    weightDetails: {
                        trailerWeight2: 0
                    }
                }
            },

            created() {
                //this.getVehicleBrands();
                //this.getConfiguredModels();
                //this.getBodyTypes();
                //this.getBusinessUnits();
                //this.getOrganizationalUnits();
                // this.getDirectorates();
                //this.getCostCenters();
                // this.getBusinessAreas();
                // this.getFuelTypes();
                // this.loadRegistrationTypes();
                // this.loadLicenceClasses();
                // this.getTransmissionTypes();
            },

            filters: {
                trimSpaces: function (val) {
                    if (!val) return "";
                    if (typeof val === 'number') return val;
                    return val?.trim();
                },
                formatStatus: function (value) {
                    if (!value) return 'Saved';
                    if (value == '100') {
                        return 'Pending General Data Entry';
                    } else if (value == '101') {
                        return 'Pending Technical Data Entry';
                    } else if (value == "102") {
                        return 'Pending Accessories Checkin';
                    } else if (value == "103") {
                        return 'Pending Costing Data Entry';
                    } else if (value == "104") {
                        return 'Pending Assignment';
                    }
                }
            },

            mounted() {
                console.log("%c✔ ZESCO Fleet Master Running", "color: #148f32");
                console.log("%c✔ Vehicle OnBoarding Process", "color: #148f32");

                this.vehicleHeaderForm = document.querySelector('#tms_vehicle_header_form');
                this.chassisDetailsForm = document.querySelector('#tms_chassis_details_form');
                this.engineDetailsForm = document.querySelector('#tms_engine_details_form');
                this.costingDetailsForm = document.querySelector('#tms_costing_valuation_form');
                this.bodyDetailsForm = document.querySelector('#tms_body_weight_form');
                this.assignmentDetailsForm = document.querySelector('#tms_assignment_tab_form');

                let input = document.getElementById("userUnit");

                if (this.vehicleHeader && this.vehicleHeader.id) {
                    this.vehicleHeader.isHeaderSaved = true;
                }

                $(document).on('keyup paste', '#chassisNumber', function () {
                    this.value = this.value.toLocaleUpperCase();
                });

                $(document).on('keyup paste', '[name="whiteBookSerial"]', function () {
                    this.value = this.value.toLocaleUpperCase();
                });
                $(document).on('keyup paste', '[name="engineType"]', function () {
                    this.value = this.value.toLocaleUpperCase();
                });


                $(document).on('keyup paste', '#tyreBrand', function () {
                    this.value = this.value.toLocaleUpperCase();
                });

                $(document).on('keyup paste', '#batteryBrand', function () {
                    this.value = this.value.toLocaleUpperCase();
                });

                $(document).on('keyup paste', '#engineNumber', function () {
                    this.value = this.value.toLocaleUpperCase();
                });

                /*$(document).on('keyup', '#vehicleLocation', function () {
                    if (!this.value) {
                        this.focus();
                    }
                    this.value = this.value.toLocaleUpperCase();
                });*/

                /*$(document).on('keyup', '#vehicleLocation', function () {
                    if (!this.value) {
                        this.focus();
                    }
                    this.value = this.value.toLocaleUpperCase();
                });*/

                Inputmask({
                    "mask": "AAA 9999"
                }).mask("#registrationNumber");

                Inputmask({
                    "mask": "999/99/A99"
                }).mask(".tyre-size");

                Inputmask({
                    "mask": "99.9"
                }).mask("#fuelConsumption");

                /*Inputmask("decimal", {
                    "rightAlignNumerics": false
                }).mask("#chargeOutRate");*/

                $(document).on('click', '[data-select="file"]', function () {
                    let fileInput = $(this).closest('p').find('input[type="file"]');
                    $(fileInput).trigger('click');
                });


            },

            methods: {

                bodyTypeChanged: function (selectedBody) {
                    app['vehicleHeader'].body_type_guid = selectedBody?.guid;
                    document.querySelector('#bodyType').value = selectedBody?.guid;
                },

                checkChassisNumberValidity: function () {
                    fetch(document.querySelector('#documentValidationUrl').value
                        + '?method=chassis&key=' + app['chassisDetails']['chassisNumber'])
                        .then(response => response.json())
                        .then(response => {
                            // Populate results
                            if (response.state === 'failure') {
                                //show errors
                                toastr.error('Chassis validity not verified', 'Connection error');
                                return;
                            }

                            app['document_validity'].state = response['payload'].validity;
                            app['document_validity'].message = response['payload'].message;
                        })
                        .catch(function (error) {
                            // notify of error
                            toastr.error(
                                'Could not retrieve data, some feature might not work.', 'Connection error')
                        });
                },

                checkValueChange(element) {
                },

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

                // web UI event
                formatMoney: function (event) {
                    setTimeout(function () {
                        //ZMW
                        let formatted = accounting.formatMoney(event.target.value, '');
                        app['chassisDetails'].chargeOutRate = formatted;
                    }, 300);
                },

                getBusinessAreas: function () {
                    fetch(document.querySelector('#businessAreaEndpoint').value)
                        .then(response => response.json())
                        .then(response => {
                            // Populate results
                            if (response.state === 'failure') {
                                //show errors
                                toastr.error('Connection error, no data found')
                                return;
                            }

                            app.businessAreas = response['payload'];
                        })
                        .catch(function (error) {
                            // notify of error
                            toastr.error(
                                'Connection error. Could not retrieve data, some feature might not work.')
                        });
                },

                getBusinessUnits: function () {
                    fetch(document.querySelector('#businessUnitsEndpoint').value)
                        .then(response => response.json())
                        .then(response => {
                            // Populate results
                            if (response.state === 'failure') {
                                //show errors
                                toastr.error('Connection error, no data found')
                                return;
                            }

                            window.businessUnits = response['payload'];
                            app.businessUnits = response['payload'];
                        })
                        .catch(function (error) {
                            // notify of error
                            toastr.error(
                                'Connection error. Could not retrieve data, some feature might not work.')
                        });
                },

                getDirectorates: function () {
                    fetch(document.querySelector('#directoratesEndpoint').value)
                        .then(response => response.json())
                        .then(function (response) {
                            // Populate results
                            if (response.state === 'failure') {
                                //show errors
                                toastr.error('Connection error, no data found')
                                return;
                            }

                            app.directorates = response['payload'];
                        })
                        .catch(function (error) {
                            // notify of error
                            toastr.error(
                                'Connection error. Could not retrieve data, some feature might not work.')
                        });
                },

                getFuelTypes: function () {
                    fetch(document.querySelector('#fuelTypesUrl').value)
                        .then(response => response.json())
                        .then(response => {
                            // Populate results
                            if (response.state === 'failure') {
                                //show errors
                                toastr.error('Connection error, no data found')
                                return;
                            }
                            this.fuelTypes = response.payload;
                        })
                        .catch(function (error) {
                            // notify of error
                            toastr.error(
                                'Connection error. Could not retrieve data, some feature might not work.')
                        });
                },

                getModelLabel: function (val) {
                    if (typeof val === 'object' && !Array.isArray(val)) {
                        return val['model_name'] + '=>' + val['model_code'];
                    }
                },

                /*getUserUnitLabel: function (val) {
                   if (typeof val === 'object') {
                       return val['code_unit'] + '=>' + val.description;
                   }
               },*/

                getTransmissionTypes: function () {
                    this.transmissionTypes = [
                        {
                            'name': 'AUTOMATIC',
                            'code': 'AT'
                        },
                        {
                            'name': 'MANUAL',
                            'code': 'MT'
                        }
                    ]
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
