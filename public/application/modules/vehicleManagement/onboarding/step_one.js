Vue.component('v-select', VueSelect.VueSelect);
window.removeSpaces = function (value) {
    if (!value) return;
    return value.replace(/\s/g, '');
}

let app = new Vue({
    'el': '#kt_app_main',
    components: {},
    data() {
        return {
            vehicle_brand_placeholder: 'Select Vehicle Brand',
            vehicle_model_placeholder: 'Select Model',
            dataStatus: 0,
            vehicleHeaderId: null,
            isHeaderSaved: false,
            vehicleBrands: [],
            engineBrands: [],
            organizationalUnits: [],
            businessUnits: [],
            directorates: [],
            costCenters: [],
            businessAreas: [],
            fuelTypes: [],
            licenseTypes: [],
            registrationTypes: [],
            configuredModels: [],
            selectedBrandModels: [],
            bodyTypes: [],
            chassisDetails: {
                stickerRegistrationNumber: null,
                status: 'active'
            },
            transmissionTypes: [
                {
                    'name': 'Automatic',
                    'code': 'AT'
                },
                {
                    'name': 'Manual',
                    'code': 'MT'
                }
            ],
            vehicleHeader: {model: {}},
            engineDetails: {},
            otherDetails: {},
            costingAndValuation: {},
            bodyDetails: {
                numberOfSeats: 0,
                volumeOfBootTanker:
                    0,
                seatCapRear:
                    0
            },
            weightDetails: {
                trailerWeight2: 0
            },
            assignmentDetails: {},
            validators: [],
            selectedModelCodes: [],
            searchedEmployeesList: [],

            // forms
            vehicleHeaderForm: null,
            chassisDetailsForm: null,
            engineDetailsForm: null,
            costingDetailsForm: null,
            bodyDetailsForm: null,
            assignmentDetailsForm: null,
            // validators
            vehicleHeaderFormValidator: null,
            chassisDetailsFormValidator: null,
            engineDetailsFormValidator: null,
            document_validity: {
                state: null,
                message: null
            },
            regNumberValidity: {
                state: null,
                message: null
            }
        }
    },

    created() {
        this.getVehicleBrands();
        this.getConfiguredModels();
        this.getBodyTypes();
        this.getOrganizationalUnits();
        this.loadRegistrationTypes()
        this.licenseTypes = [
            {'code': 'A', 'name': 'Class A'},
            {'code': 'B', 'name': 'Class B'},
            {'code': 'C', 'name': 'Class C'},
            {'code': 'E', 'name': 'Class E'},
        ];
    },

    mounted() {
        console.log("%c✔ Vehicle OnBoarding Running", "color: #148f32");

        this.vehicleHeaderForm = document.querySelector('#tms_vehicle_header_form');

        $(document).on('keyup', '#registrationNumber', function () {
            //this.value = this.value.toLocaleUpperCase();
        });

        $(document).on('keyup', '#vehicleLocation', function () {
            if(!this.value){
                this.focus();
            }
            this.value = this.value.toLocaleUpperCase();
        });

        //this.chassisDetailsForm = document.querySelector('#tms_chassis_details_form');
        //this.engineDetailsForm = document.querySelector('#tms_engine_details_form');
        //this.costingDetailsForm = document.querySelector('#tms_costing_valuation_form');
        //this.bodyDetailsForm = document.querySelector('#tms_body_weight_form');
        //this.assignmentDetailsForm = document.querySelector('#tms_assignment_tab_form');

        //let input = document.getElementById("userUnit");

        //this.initDropzone();
        if (this.vehicleHeader && this.vehicleHeader.id) {
            this.isHeaderSaved = true;
        }

        //this.initValidators();

        Inputmask({
            "mask": "AAA 9999"
        }).mask("#registrationNumber");

        /*Inputmask({
            "mask": "999/99/A99"
        }).mask(".tyre-size");

        Inputmask("decimal", {
            "rightAlignNumerics": false
        }).mask("#chargeOutRate");*/

        //this.vueCreateSelect2();

        $(document).on('click', '[data-select="file"]', function () {
            let fileInput = $(this).closest('p').find('input[type="file"]');
            $(fileInput).trigger('click');
        });

        let fileSelects = [].slice.call(document.querySelectorAll('.fileElem'));
        fileSelects.map(function (fileSelect) {
            fileSelect.addEventListener(
                "change",
                (e) => {
                    app.preview(e);
                },
                false
            );
        });

        $(document).on('click', '.clearImage', function (event) {
            let btn = this;
            Swal.fire({
                text: "Are you sure you would like to remove the image?"
                , icon: "warning"
                , showCancelButton: true
                , buttonsStyling: false
                , confirmButtonText: "Yes, remove it!"
                , cancelButtonText: "No, return"
                , customClass: {
                    confirmButton: "btn btn-primary"
                    , cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    $(btn).parent().css({
                        "background-image": "",
                        'display': 'none'
                    });
                    // find the upload btn and make visible
                    $(btn).parent().parent().find('p').removeClass('d-none');
                }
            });

        });

        $(document).on('change', '[data-emp="staff_number"]', function (e) {
                let input = e.target;
                let value = input.value;

                let names = app['searchedEmployeesList'].filter(function (user) {
                    return user['staff_number'] === value;
                });

                if (names.length === 0) return;

                $(input).closest('tr').find('input[data-emp="name"]').val(names[0].name)

            }
        )
    },

    methods: {

        loadRegistrationTypes: function () {
            this.registrationTypes = [
                {
                    "label": 'Motor Vehicle',
                    'code': 'MV'
                },
                {
                    "label": 'Boat',
                    'code': 'BT'
                },
                {
                    "label": 'Trailer',
                    'code': 'TR'
                },
            ]
        },

        // web UI event
        bodyTypeChanged: function (selectedBody) {

            app['vehicleHeader'].body_type_guid = selectedBody?.id;
            document.querySelector('#bodyType').value = selectedBody?.id;
        },

        formatMoney: function (event) {

            setTimeout(function () {
                let formatted = accounting.formatMoney(event.target.value, 'ZMW ');
                //tmsApp.formatMoney(event.target.value);
                console.log('%c' + formatted, "color: #148f32");
                app['chassisDetails'].chargeOutRate = formatted;
                //document.querySelector('#'+event.target.id).value = formatted;
            }, 300);
        },

        getBodyTypes: function () {
            fetch(document.querySelector('#bodyTypesEndpoint').value)
                .then(response => response.json())
                .then(response => {
                    // Populate results
                    if (response.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    app.bodyTypes = response.payload;
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
                .then(function (response) {
                    // Populate results
                    if (response.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    app.businessUnits = response.data['payload'];
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error(
                        'Connection error. Could not retrieve data, some feature might not work.')
                });
        },

        getConfiguredModels: function () {
            fetch(document.querySelector('#modelEndpoint').value)
                .then(response => response.json())
                .then(response => {
                    // Populate results
                    if (response.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }
                    app.configuredModels = response['payload'];
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error('Connection error. Could not retrieve data, some feature might not work.')
                });
        },

        getModelLabel: function (val) {
            if (typeof val === 'object') {
                return val.model_name + '=>' + val.model_code;
            }
        },

        getOrganizationalUnits: function () {
            fetch(document.querySelector('#orgUnitsEndpoint').value)
                .then(response => response.json())
                .then(response => {
                    // Populate results
                    if (response.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    app.organizationalUnits = response['payload'];
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error(
                        'Connection error. Could not retrieve data, some feature might not work.')
                });
        },

        getUserUnitLabel: function (val) {
            if (typeof val === 'object') {
                return val['code_unit'] + '=>' + val.description;
            }
        },

        getVehicleBrands: function () {
            fetch(document.querySelector('#brands-api').value)
                .then(response => response.json())
                .then(response => {
                    // Populate results
                    if (response.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    app.vehicleBrands = response['payload'];
                    app.engineBrands = response['payload'];
                }).catch(function (error) {
                // notify of error
                toastr.error(
                    'Connection error. Could not retrieve data, some feature might not work.')
            });
        },

        modelChanged(model) {
            this.vehicleHeader.model_guid = model?.model_guid;
            this.vehicleHeader.model_code = model?.model_code;
            document.querySelector('#model').value = model?.model_guid;
            document.querySelector('#model_code').value = model?.model_code;
        },

        userUnitChanged: function (user_unit) {

            app.vehicleHeader.user_unit_code = user_unit?.code_unit;
            document.querySelector('[name="user_unit"]').value = user_unit?.code_unit;
            let cost_center_code = user_unit?.cc_code
            let business_unit_code = user_unit?.bu_code


            let filteredCostCenters = app.costCenters.filter(function (cost_center) {
                return cost_center.code_cost_center?.trim() === cost_center_code?.trim();
            });


            if (filteredCostCenters.length !== 0) {
                let costCentreOfInterest = filteredCostCenters[0];

                this.assignmentDetails.costCenter = costCentreOfInterest['code_cost_center'] + ':' + costCentreOfInterest['description'];
                $('[name="costCenter"]').val(costCentreOfInterest['code_cost_center'] + ':' + costCentreOfInterest['description']);
            }


            console.log(business_unit_code);

            let filteredBusinessUnits = app.businessUnits.filter(function (bu) {
                return bu.code_bu?.trim() === business_unit_code?.trim();
            });


            if (filteredBusinessUnits.length == 0) return;

            let businessUnitOfInterest = filteredBusinessUnits[0];

            const val = businessUnitOfInterest['code_bu'] + ':' + businessUnitOfInterest['description'];
            $('[name="businessUnit"]').val(val);
            this.assignmentDetails.businessUnit = val;
        },

        validateRegistrationNumber: function () {
            let ref = app['vehicleHeader']['registration_number'] ?? document.querySelector('#registrationNumber').value
            fetch(document.querySelector('#documentValidationUrl').value +
                '?method=registration_number&key=' + ref)
                .then(response => response.json())
                .then(response => {
                    // Populate results
                    if (response.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, chassis number could not be verified')
                        return;
                    }

                    if (response['payload'].validity) {
                        console.log(response['payload'].validity);
                        //response.data.payload.message
                        let assetNumberInput = document.querySelector("#assetNumber");
                        if (assetNumberInput) {
                            assetNumberInput.value = window.removeSpaces(document.querySelector('#registrationNumber').value);
                        }
                    } else {
                        toastr.error('Duplicate registration number, vehicle already registered')
                    }
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error(
                        'Connection error. Could not retrieve data, some feature might not work.')
                });
        },

        vehicleBrandChanged(selectedValue) {
            this.vehicleHeader.brand_guid = selectedValue?.id?.toString().trim();
            this.selectedBrandModels = [];
            app.selectedBrandModels = app.configuredModels.filter(function (model) {
                return model.brand_guid?.toString()?.trim() === app?.vehicleHeader.brand_guid?.toString().trim();
            });
        },

        registrationTypeChanged(selectedType) {
            console.log('Vehicle Type Changed', selectedType)
        },
    }
});

(function (tmsApp, $) {

    function postVehicleHeaderData() {
        $('.print-error-msg').css('display', 'none');
        // validate all required information
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
                if ('state' in asyncResponse && asyncResponse.state != 'success') {
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
                        asyncResponse.data.message
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
                console.log(errorThrown)
                setTimeout(function () {
                    tmsApp.showErrorMessages(xhr, 'Vehicle On-Boarding');
                }, 300)
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

    $("#submitBtn").on('click', function () {
        postVehicleHeaderData();
    });

    let saveVehicleHeaderInformation = function (e) {
        // Prevent default button action
        e.preventDefault();
        this.postVehicleHeaderData();
    }

})(window.tmsApp || {}, jQuery);
