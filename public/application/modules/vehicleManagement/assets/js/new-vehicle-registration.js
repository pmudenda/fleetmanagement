/*Vue.component('v-select', VueSelect.VueSelect);*/
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
            businessUnits: [],
            directorates: [],
            costCenters: [],
            organizationalUnits: [],
            businessAreas: [],
            fuelTypes: [],
            licenseTypes: [
                'A', 'B', 'C', 'E'
            ],
            vehicleTypes: [
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
            ],
            configuredModels: [],
            selectedBrandModels: [],
            bodyTypes: [],
            images: {
                frontView: null,
                rearView: null,
                leftView: null,
                rightView: null,
            }
            ,
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
            vehicleHeader:
                {
                    model: {}
                }
            ,
            engineDetails: {}
            ,
            otherDetails: {}
            ,
            costingAndValuation: {}
            ,
            bodyDetails: {
                numberOfSeats: 0,
                volumeOfBootTanker:
                    0,
                seatCapRear:
                    0
            }
            ,
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
    computed: {
        modelLabel: function (configuredModel) {
            //return configuredModel.model_name + '=>' + configuredModel.model_code;
        },
        allImagesUploaded: function () {
            return this.images.frontView &&
                this.images.rearView &&
                this.images.leftView &&
                this.images.rightView;
        },
        assetNumber: function () {

        }
    },

    created() {
        this.getVehicleBrands();
        this.getConfiguredModels();
        this.getBodyTypes();
        this.getBusinessUnits();
        this.getOrganizationalUnits();
        this.getDirectorates();
        this.getCostCenters();
        this.getBusinessAreas();
        this.getFuelTypes();
    },

    mounted() {
        console.log("%c✔ ZFM Running", "color: #148f32");

        this.vehicleHeaderForm = document.querySelector('#tms_vehicle_header_form');
        this.chassisDetailsForm = document.querySelector('#tms_chassis_details_form');
        this.engineDetailsForm = document.querySelector('#tms_engine_details_form');
        this.costingDetailsForm = document.querySelector('#tms_costing_valuation_form');
        this.bodyDetailsForm = document.querySelector('#tms_body_weight_form');
        this.assignmentDetailsForm = document.querySelector('#tms_assignment_tab_form');

        let input = document.getElementById("userUnit");

        //this.initDropzone();
        if (this.vehicleHeader && this.vehicleHeader.id) {
            this.isHeaderSaved = true;
        }

        this.initValidators();

        Inputmask({
            "mask": "AAA 9999"
        }).mask("#registrationNumber");

        Inputmask({
            "mask": "999/99/A99"
        }).mask(".tyre-size");


        Inputmask("decimal", {
            "rightAlignNumerics": false
        }).mask("#chargeOutRate");

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

        /*$(document).on('blur', '[data-emp="staff_number"]', function (e) {
                let input = e.target;

                axios.post(document.querySelector('#userSearchEndpoint').value, {
                    param: $(input).val()
                })
                    .then(function (response) {
                        let result = response.data.payload;
                        //app.searchedEmployeesList = result;
                        if (result.length === 0) {
                            toastr.info('No user found for specified staff number');
                            return;
                        }

                        $(input).closest('tr').find('input[data-emp="name"]').val(result[0].name)

                        console.log(result);
                    })
                    .catch(function (error) {

                    });
            }
        )*/

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

        getFuelTypes: function () {
            this.fuelTypes = [
                'Diesel',
                'Petrol'
            ];
        },

        transmissionTypeChanged: function (transmissionType) {
            document.querySelector('#transmission_type').value = transmissionType?.code + ':' + transmissionType?.name;
        },
        // web UI event
        bodyTypeChanged: function (selectedBody) {
            app['vehicleHeader'].body_type_guid = selectedBody?.guid;
            document.querySelector('#bodyType').value = selectedBody?.guid;
        },
        validateRegistrationNumber: function () {
            let ref = app['vehicleHeader']['registration_number'] ?? document.querySelector('#registrationNumber').value
            axios.get(document.querySelector('#documentValidationUrl').value +
                '?method=registration_number&key=' + ref)
                .then(function (response) {
                    // Populate results
                    if (response.data.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, chassis number could not be verified')
                        return;
                    }

                    if (response.data['payload'].validity) {
                        console.log(response.data['payload'].validity);
                        //response.data.payload.message
                        let assetNumberInput = document.querySelector("#assetNumber");
                        if (assetNumberInput) {
                            assetNumberInput.value = window.removeSpaces(document.querySelector('#registrationNumber').value);
                        }
                    } else {
                        toastr.warning('Invalid registration number, vehicle already registered')
                    }
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error(
                        'Connection error. Could not retrieve data, some feature might not work.')
                });
        },
        checkValueChange(element) {
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

        checkChassisNumberValidity: function () {
            axios.get(document.querySelector('#documentValidationUrl').value + '?method=chassis&key=' + app.chassisDetails.chassisNumber)
                .then(function (response) {
                    // Populate results
                    if (response.data.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, chassis validity not verified')
                        return;
                    }

                    app['document_validity'].state = response.data['payload'].validity;
                    app['document_validity'].message = response.data['payload'].message;
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error(
                        'Connection error. Could not retrieve data, some feature might not work.')
                });
        },

        completeVehicleRegistration() {
            Swal.fire({
                text: "Are you sure you would like to complete the registration of this vehicle?"
                , icon: "warning"
                , showCancelButton: true
                , buttonsStyling: false
                , confirmButtonText: "Yes!"
                , cancelButtonText: "No, return"
                , customClass: {
                    confirmButton: "btn btn-primary"
                    , cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    app.postVehicleImages();
                }
            });

        }
        ,

        getBodyTypes() {
            axios.get(document.querySelector('#bodyTypesEndpoint').value)
                .then(function (response) {
                    // Populate results
                    if (response.data.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    app.bodyTypes = response.data.payload;
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error(
                        'Connection error. Could not retrieve data, some feature might not work.')
                });
        },

        getBusinessAreas() {
            axios.get(document.querySelector('#businessAreaEndpoint').value)
                .then(function (response) {
                    // Populate results
                    if (response.data.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    app.businessAreas = response.data['payload'];
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error(
                        'Connection error. Could not retrieve data, some feature might not work.')
                });
        }
        ,

        getBusinessUnits() {
            axios.get(document.querySelector('#businessUnitsEndpoint').value)
                .then(function (response) {
                    // Populate results
                    if (response.data.state === 'failure') {
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
        }
        ,

        getConfiguredModels() {
            axios.get(document.querySelector('#modelEndpoint').value)
                .then(function (response) {
                    // Populate results
                    if (response.data.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    app.configuredModels = response.data['payload'];
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error(
                        'Connection error. Could not retrieve data, some feature might not work.')
                });
        }
        ,

        getCostCenters() {
            axios.get(document.querySelector('#costCenterEndpoint').value)
                .then(function (response) {
                    // Populate results
                    if (response.data.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    app.costCenters = response.data['payload'];
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error(
                        'Connection error. Could not retrieve data, some feature might not work.')
                });
        }
        ,

        getDirectorates() {
            axios.get(document.querySelector('#directoratesEndpoint').value)
                .then(function (response) {
                    // Populate results
                    if (response.data.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    app.directorates = response.data['payload'];
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error(
                        'Connection error. Could not retrieve data, some feature might not work.')
                });
        }
        ,

        getModelLabel: function (val) {
            if (typeof val === 'object') {
                return val.model_name + '=>' + val.model_code;
            }
        },

        getUserUnitLabel: function (val) {
            if (typeof val === 'object') {
                return val['code_unit'] + '=>' + val.description;
            }
        },

        getOrganizationalUnits() {

            axios.get(document.querySelector('#orgUnitsEndpoint').value)
                .then(function (response) {
                    // Populate results
                    if (response.data.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    app.organizationalUnits = response.data['payload'];
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error(
                        'Connection error. Could not retrieve data, some feature might not work.')
                });
        }
        ,

        getVehicleBrands() {
            axios.get(document.querySelector('#newBrandEndpoint').value)
                .then(function (response) {
                    // Populate results
                    if (response.data.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    app.vehicleBrands = response.data['payload'];
                    app.engineBrands = response.data['payload'];
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error(
                        'Connection error. Could not retrieve data, some feature might not work.')
                });
        }
        ,

        initValidators() {
            this.vehicleHeaderFormValidator = FormValidation.formValidation(
                this.vehicleHeaderForm,
                {
                    fields: {
                        'brand': {
                            validators: {
                                notEmpty: {
                                    message: 'Vehicle make is required'
                                }
                            }
                        },
                        'registrationNumber': {
                            validators: {
                                notEmpty: {
                                    message: 'Vehicle Registration Number is required'
                                }
                            }
                        },
                        'model': {
                            validators: {
                                notEmpty: {
                                    message: 'You must select the model for vehicle being registered'
                                }
                            }
                        },
                        'vehicleLocation': {
                            validators: {
                                notEmpty: {
                                    message: 'You must enter where the vehicle will be located'
                                }
                            }
                        },
                        'model_code': {
                            validators: {
                                notEmpty: {
                                    message: 'Vehicle model code is required'
                                }
                            }
                        },
                        'bodyType': {
                            validators: {
                                notEmpty: {
                                    message: 'You must select the type of body for the vehicle'
                                }
                            }
                        },
                        'userUnit': {
                            validators: {
                                notEmpty: {
                                    message: 'You must select the business unit responsible for the vehicle'
                                }
                            }
                        }
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger({}),
                    },
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row', eleInvalidClass: '', eleValidClass: ''
                    })
                });

            this.chassisDetailsFormValidator = FormValidation.formValidation(
                this.chassisDetailsForm,
                {
                    fields: {
                        'chassisNumber': {
                            validators: {
                                notEmpty: {
                                    message: 'Chassis Number is required'
                                }
                            }
                        },
                        'engineNumber': {
                            validators: {
                                notEmpty: {
                                    message: 'Vehicle Engine Number is required'
                                }
                            }
                        },
                        'whiteBookSerial': {
                            validators: {
                                notEmpty: {
                                    message: 'Motor vehicle registration certificate number being registered'
                                }
                            }
                        },
                        'yearOfManufacture': {
                            validators: {
                                notEmpty: {
                                    message: 'You must enter year the vehicle was manufuctured'
                                }
                            }
                        },
                        'registrationDate': {
                            validators: {
                                notEmpty: {
                                    message: 'Date of first registration is required'
                                }
                            }
                        },
                        'chargeOutRate': {
                            validators: {
                                notEmpty: {
                                    message: 'You must provide the charge-out rate for the vehicle'
                                }
                            }
                        },
                        'requiredMinimumDrivingLicense': {
                            validators: {
                                notEmpty: {
                                    message: 'Select the minimum license class for this vehicle'
                                }
                            }
                        },
                        'initialOdometerReading': {
                            validators: {
                                notEmpty: {
                                    message: 'Initial Odometer Reading is required'
                                }
                            }
                        },
                        'odometerReadingLastService': {
                            validators: {
                                notEmpty: {
                                    message: 'Provide the Odometer Reading at last service'
                                }
                            }
                        },
                        'nextServiceOdometerReading': {
                            validators: {
                                notEmpty: {
                                    message: 'Provide the Odometer Reading for the next service due'
                                }
                            }
                        },
                        'inspectionDate': {
                            validators: {
                                notEmpty: {
                                    message: 'Provide the date when vehicle was inspected'
                                }
                            }
                        },
                        'status': {
                            validators: {
                                notEmpty: {
                                    message: 'Status is required'
                                }
                            }
                        },

                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger({}),
                    },
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row', eleInvalidClass: '', eleValidClass: ''
                    })
                });
            this.engineDetailsFormValidator = FormValidation.formValidation(
                this.engineDetailsForm,
                {
                    fields: {
                        'numberOfCylinders': {
                            validators: {
                                notEmpty: {
                                    message: 'Number of cylinders is required'
                                }
                            }
                        },
                        'engineCapacity': {
                            validators: {
                                notEmpty: {
                                    message: 'Engine capacity is required'
                                }
                            }
                        },
                        'fuelTypes': {
                            validators: {
                                notEmpty: {
                                    message: 'Fuel Type is required'
                                }
                            }
                        }, 'fuelConsumption': {
                            validators: {
                                notEmpty: {
                                    message: 'Fuel Consumption is required'
                                }
                            }
                        },
                        'engineType': {
                            validators: {
                                notEmpty: {
                                    message: 'Engine Type is required'
                                }
                            }
                        },
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger({}),
                    },
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row', eleInvalidClass: '', eleValidClass: ''
                    })
                });
        }
        ,

        modelChanged(model) {
            this.vehicleHeader.model_guid = model?.model_guid;
            this.vehicleHeader.model_code = model?.model_code;
            document.querySelector('#model').value = model?.model_guid;
            document.querySelector('#model_code').value = model?.model_code;
        }
        ,

        postRequest(data, url, successCallBack, errorCallBack) {

            axios.post(url, data, {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    , 'content-type': 'text/json'
                }
            }).then(function (response) {
                successCallBack(response);
            }).catch(function (error) {
                errorCallBack(error);
            });
        }
        ,

        postVehicleHeaderData() {

            // validate all required information
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
                            app.isHeaderSaved = true;
                        }, 500)

                        if (el.classList.contains("btn-light-primary")) {
                            el.classList.remove("btn-light-primary");
                            el.classList.add("btn-light");
                            label.innerHTML = "Saved";
                        } else { // follow
                            el.classList.add("btn-light-primary");
                            el.classList.remove("btn-light");
                            app.isHeaderSaved = true;
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

        }
        ,

        postVehicleImages() {
            let completionForm = $('#completeRegistrationForm');
            $.ajax({
                'url': $(completionForm).attr('action'),
                'type': 'POST',
                data: new FormData($(completionForm)[0]),
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    , 'content-type': 'text/json'
                }
            }).done(function (response) {

                Swal.fire({
                    text: "Vehicle Registration Completed Successfully," +
                        "You will be refreshed"
                    , icon: "success"
                    , buttonsStyling: false
                    , confirmButtonText: "Ok"
                    , customClass: {
                        confirmButton: "btn fw-bold btn-primary"
                        ,
                    }
                }).then(function () {
                    window.location.reload();
                });

            })
        }
        ,

        preview(event) {
            //$('#frame').src = URL.createObjectURL(event.target.files[0]);
            let uploadFile = $(event.target);
            let self = event.target;
            let files = !!self.files ? self.files : [];
            if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

            if (/^image/.test(files[0].type)) { // only image file
                let reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[0]); // read the local file

                reader.onloadend = function () { // set image data as background of div
                    //alert(uploadFile.closest(".upimage").find('.imagePreview').length);
                    uploadFile.closest("div").find('.imagePreview').css({
                        "background-image": "url(" + this.result + ")",
                        'display': 'block'
                    });
                }
            }

            $(uploadFile).closest('div').find('p').addClass('d-none');
        }
        ,
        saveVehicleHeaderInformation: function (e) {
            // Prevent default button action
            e.preventDefault();
            this.postVehicleHeaderData();
        }
        ,
        submitAssignmentDetails() {
            let el = document.querySelector('#tms_save_assignment');
            let label = el.querySelector(".indicator-label");
            el.setAttribute('data-kt-indicator', 'on');
            el.disabled = true;

            app.postRequest(
                new FormData($(app.assignmentDetailsForm)[0]),
                app.assignmentDetailsForm.action,
                function (response) {
                    let el = document.querySelector('#tms_save_assignment');
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

                    toastr.success(
                        response.data.message
                    );

                    app.dataStatus += 1;
                    if (el.classList.contains("btn-light-primary")) {
                        el.classList.remove("btn-light-primary");
                        el.classList.add("btn-light");
                        label.innerHTML = "Saved";
                    } else { // follow
                        el.classList.add("btn-light-primary");
                        el.classList.remove("btn-light");
                        app.isHeaderSaved = true;
                        label.innerHTML = "Saved";
                    }

                }, function (error) {
                    let el = document.querySelector('#tms_save_costing');
                    let label = el.querySelector(".indicator-label");

                    el.removeAttribute('data-kt-indicator');
                    el.disabled = false;

                    toastr.error(
                        error.message
                    );

                });
        }
        ,
        submitBodyDetails() {
            let el = document.querySelector('#tms_save_body');
            let label = el.querySelector(".indicator-label");
            el.setAttribute('data-kt-indicator', 'on');
            el.disabled = true;

            app.postRequest(
                new FormData($(app.bodyDetailsForm)[0]),
                app.bodyDetailsForm.action,
                function (response) {
                    let el = document.querySelector('#tms_save_body');
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

                    toastr.success(
                        response.data.message
                    );

                    app.isHeaderSaved = true;
                    app.dataStatus += 1;

                    if (el.classList.contains("btn-light-primary")) {
                        el.classList.remove("btn-light-primary");
                        el.classList.add("btn-light");
                        label.innerHTML = "Saved";
                    } else { // follow
                        el.classList.add("btn-light-primary");
                        el.classList.remove("btn-light");
                        app.isHeaderSaved = true;
                        label.innerHTML = "Saved";
                    }

                    app.switchTabs();
                },

                function (error) {
                    let el = document.querySelector('#tms_save_body');
                    let label = el.querySelector(".indicator-label");

                    el.removeAttribute('data-kt-indicator');
                    el.disabled = false;

                    toastr.error(
                        error.message
                    );

                });
        }
        ,
        submitChassisDetails() {
            if (!this.validators) {
                return alert('No Validator Configured');
            }

            let fileUploads = [].slice.call(document.querySelectorAll('input[type="file"]'));
            let filesValid = true;
            fileUploads.map(function (fileSelect) {
                if (fileSelect.files.length === 0) {
                    toastr.warning('Submission not accepted, You have not attached all required images')
                    filesValid = false;
                    return;
                }
            });

            if (!filesValid) {
                return;
            }

            this.chassisDetailsFormValidator.validate().then(function (status) {
                console.log('validated!');
                if (status !== 'Valid') {
                    toastr.warning(
                        'Sorry, data failed validation checks, please check your data and try again.'
                    );
                    return;
                }

                let el = document.querySelector('#tms_save_chassis');
                el.setAttribute('data-kt-indicator', 'on');
                el.disabled = true;

                let form = $(app.chassisDetailsForm)[0];

                let formData = new FormData(form);
                let url = app.chassisDetailsForm.action;
                axios.post(url, formData, {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        , 'content-type': 'text/json'
                    }
                }).then(function (asyncResponse) {
                    let response = asyncResponse.data;
                    let el = document.querySelector('#tms_save_chassis');
                    let label = el.querySelector(".indicator-label");

                    setTimeout(function () {
                        el.removeAttribute('data-kt-indicator');
                        el.disabled = false;
                    }, 300)


                    if (response.state != 'success') {
                        toastr.error(
                            response.message
                        );
                        return;
                    }

                    toastr.success(
                        response.message
                    );

                    app.dataStatus += 1;
                    app.switchTabs();

                    if (el.classList.contains("btn-light-primary")) {
                        el.classList.remove("btn-light-primary");
                        el.classList.add("btn-light");
                        label.innerHTML = "Saved";
                    } else { // follow
                        el.classList.add("btn-light-primary");
                        el.classList.remove("btn-light");
                        app.isHeaderSaved = true;
                        label.innerHTML = "Saved";
                    }
                }).catch(function (error) {
                    console.log(error);
                });

                /*  $.ajax({
                      'url': url ,
                      'type': 'POST',
                      data: formData,
                      enctype: 'multipart/form-data',
                      processData: false,  // Important!
                      contentType: false,
                      cache: false,
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                          , 'content-type': 'text/json'
                      }
                  })
                      .done(function (response) {


                  })
                      .fail(function (error) {
                          let el = document.querySelector('#tms_save_chassis');
                          let label = el.querySelector(".indicator-label");

                          el.removeAttribute('data-kt-indicator');
                          el.disabled = false;

                          toastr.error(
                              error.message
                          );

                      });*/

            });
        },

        submitCostValuationDetails: function () {
            let el = document.querySelector('#tms_save_costing');
            el.setAttribute('data-kt-indicator', 'on');
            el.disabled = true;

            this.postRequest(
                new FormData($(app.costingDetailsForm)[0]),
                this.costingDetailsForm.action,
                function (response) {
                    let el = document.querySelector('#tms_save_costing');
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

                    toastr.success(
                        response.data.message
                    );

                    app.dataStatus += 1;

                    if (el.classList.contains("btn-light-primary")) {
                        el.classList.remove("btn-light-primary");
                        el.classList.add("btn-light");
                        label.innerHTML = "Saved";
                    } else { // follow
                        el.classList.add("btn-light-primary");
                        el.classList.remove("btn-light");
                        app.isHeaderSaved = true;
                        label.innerHTML = "Saved";
                    }

                    app.switchTabs();

                }, function (error) {
                    let el = document.querySelector('#tms_save_costing');

                    el.removeAttribute('data-kt-indicator');
                    el.disabled = false;

                    toastr.error(
                        error.message
                    );

                });
        }
        ,

        submitEngineDetails() {
            if (!this.validators) {
                return alert('No Validator Configured');
            }

            this.engineDetailsFormValidator.validate().then(function (status) {

                if (status !== 'Valid') {
                    toastr.warning(
                        'Sorry, looks like there are some errors detected, please try again.'
                    );
                    return;
                }

                let el = document.querySelector('#tms_save_engine');
                let label = el.querySelector(".indicator-label");
                el.setAttribute('data-kt-indicator', 'on');
                el.disabled = true;

                app.postRequest(
                    new FormData($(app.engineDetailsForm)[0]),
                    app.engineDetailsForm.action,
                    function (response) {
                        let el = document.querySelector('#tms_save_engine');
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


                        toastr.success(
                            response.data.message
                        );
                        app.isHeaderSaved = true;
                        app.dataStatus += 1;
                        app.switchTabs();
                        if (el.classList.contains("btn-light-primary")) {
                            el.classList.remove("btn-light-primary");
                            el.classList.add("btn-light");
                            label.innerHTML = "Saved";
                        } else { // follow
                            el.classList.add("btn-light-primary");
                            el.classList.remove("btn-light");
                            app.isHeaderSaved = true;
                            label.innerHTML = "Saved";
                        }

                    }, function (error) {
                        let el = document.querySelector('#tms_save_chassis');
                        let label = el.querySelector(".indicator-label");

                        el.removeAttribute('data-kt-indicator');
                        el.disabled = false;

                        toastr.error(
                            error.message
                        );

                    });
            });
        }
        ,

        switchTabs() {
            let tabs = document.querySelectorAll('a[role="tab"]');
            let activeIndex = 0;
            $.each(tabs, function (index, element) {
                if ($(element).hasClass('active')) {
                    activeIndex = index;
                    return;
                }
            });
            let nextIndex = activeIndex < tabs.length - 1 ? activeIndex + 1 : activeIndex;

            if (nextIndex === activeIndex) {
                return;
            }
            $(tabs[activeIndex]).removeClass('active');
            $(tabs[nextIndex]).addClass('active');

            // switch visible content
            let tabContent = document.querySelector('#myTabContent').children;
            $(tabContent[nextIndex]).addClass('active').addClass('show');
            $(tabContent[activeIndex]).removeClass('active').removeClass('show')

            console.log(activeIndex)

        }
        ,

        userUnitChanged(user_unit) {

            /*let user_unit = this.organizationalUnits.filter(function (userUnit) {
                return userUnit.code_unit.trim() == selectedUserUnit.trim();
            });*/

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
        }
        ,

        vehicleBrandChanged(selectedValue) {
            this.vehicleHeader.brand_guid = selectedValue?.guid;
            this.selectedBrandModels = [];
            app.selectedBrandModels = app.configuredModels.filter(function (model) {
                return model.brand_guid === app?.vehicleHeader.brand_guid;
            });
        },

        vehicleTypeChanged() {
            console.log('Vehicle Type Changed')
        },
    }
})
