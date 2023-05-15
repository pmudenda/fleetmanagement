Vue.component('v-select', VueSelect.VueSelect);

function displayVehicleDetails(asyncResponse) {
    if (!asyncResponse.success) {
        toastr.error(asyncResponse['message'])
    }

    if (!asyncResponse.hasOwnProperty('payload')) {
        return;
    }

    let data = asyncResponse['payload']['vehicle'];
    //console.log(data);
    if (!data || data.length == 0) {
        return;
    }
    Vue.set(app['vehicleHeader'], 'vehicle_type', data['registration_type']);
    Vue.set(app['vehicleHeader'], 'brand_guid', data['brand_guid']);
    Vue.set(app['vehicleHeader'], 'model_guid', data['model_guid']);
    Vue.set(app['vehicleHeader'], 'model_code', data['model_code']);

    //Vue.set(app['vehicleHeader'], 'registrationNumber', );
    const $registrationNumberCtrl = document.querySelector('[name="registrationNumber"]');
    if ($registrationNumberCtrl) {
        $registrationNumberCtrl.value = data['registration_number'];
    }
    Vue.set(app['vehicleHeader'], 'on_boarding_status', data['on_boarding_status']);

    Vue.set(app['vehicleHeader'], 'body_type_guid', data['body_type_guid']);
    Vue.set(app['vehicleHeader'], 'user_unit', data['business_unit_code']);
    Vue.set(app['vehicleHeader'], 'user_unit_code', data['business_unit_code']);
    $("#user_unit").change();
    Vue.set(app['chassisDetails'], 'chassisNumber', data['chassis_number']);
    Vue.set(app['chassisDetails'], 'engineNumber', data['engine_number']);
    Vue.set(app['chassisDetails'], 'whiteBookSerial', data['white_book_serial']);
    Vue.set(app['chassisDetails'], 'stickerRegistrationNumber', data['sticker_registration_number']);
    Vue.set(app['chassisDetails'], 'yearOfManufacture', data['year_of_manufacture']);
    Vue.set(app['chassisDetails'], 'registrationDate', data['registration_date']);
    Vue.set(app['chassisDetails'], 'chargeOutRate', data['vehicle_charge_out_rate']);
    Vue.set(app['chassisDetails'], 'requiredMinimumDrivingLicense', data['min_req_driving_license']);
    Vue.set(app['chassisDetails'], 'initialOdometerReading', data['initial_odometer_reading']);
    Vue.set(app['chassisDetails'], 'currentOdometerReading', data['current_odometer_reading']);
    Vue.set(app['chassisDetails'], 'odometerReadingLastService', data['lst_service_odometer_reading']);
    Vue.set(app['chassisDetails'], 'nextServiceOdometerReading', data['nxt_service_odometer-reading']);
    Vue.set(app['chassisDetails'], 'inspectionDate', data['inspection_date']);


    Vue.set(app['engineDetails'], 'numberOfCylinders', data['number_of_cylinders']);
    Vue.set(app['engineDetails'], 'engineCapacity', data['engine_capacity']);
    Vue.set(app['engineDetails'], 'claimedEnginePower', data['claimed_engine_power']);
    Vue.set(app['engineDetails'], 'actualEnginePower', data['actual_engine_power']);
    Vue.set(app['engineDetails'], 'engineBrand', data['engine_brand']);
    Vue.set(app['engineDetails'], 'fuelTypes', data['fuel_types']);
    Vue.set(app['engineDetails'], 'engineType', data['engine_type']);
    Vue.set(app['engineDetails'], 'transmissionType', data['transmission_type']);
    Vue.set(app['engineDetails'], 'fuelConsumption', data['fuel_consumption']);
    Vue.set(app['engineDetails'], 'tank_capacity', data['tank_capacity']);
    Vue.set(app['engineDetails'], 'sub_tank_capacity', data['sub_tank_capacity']);
    Vue.set(app['engineDetails'], 'sub_tank_capacity', data['sub_tank_capacity']);

    Vue.set(app['otherDetails'], 'numberOfTyres', data['number_of_tyres']);
    Vue.set(app['otherDetails'], 'tyreBrand', data['tyre_brand']);
    const $frontTyreSizeCtrl = document.querySelector('[name="frontTyreSize"]');
    if ($frontTyreSizeCtrl) {
        $frontTyreSizeCtrl.value = data['front_tyre_size'];
    }
    //Vue.set(app['otherDetails'], 'frontTyreSize', );
    const $rearTyreSizeCtrl = document.querySelector('[name="rearTyreSize"]');
    if ($rearTyreSizeCtrl) {
        $rearTyreSizeCtrl.value = data['rear_tyre_size'];
    }
    //Vue.set(app['otherDetails'], 'rearTyreSize', data['']);

    Vue.set(app['otherDetails'], 'batteryBrand', data['battery_brand']);
    Vue.set(app['otherDetails'], 'batterySize', data['battery_size']);
    Vue.set(app['otherDetails'], 'batteryPower', data['battery_power']);

    Vue.set(app['costingAndValuation'], 'supplierName', data['supplierName']);
    Vue.set(app['costingAndValuation'], 'costPrice', data['costPrice']);
    Vue.set(app['costingAndValuation'], 'yearOfPurchase', data['yearOfPurchase']);
    Vue.set(app['costingAndValuation'], 'bookValue', data['bookValue']);
    Vue.set(app['costingAndValuation'], 'assetNumber', data['assetNumber']);

    let assetNumberInput = document.querySelector("#assetNumber");
    if (!data['assetNumber'] && assetNumberInput) {
        const assetNumber = window.removeSpaces(data['registration_number']);
        assetNumberInput.value = assetNumber
        Vue.set(app['costingAndValuation'], 'assetNumber', assetNumber);
    }
    Vue.set(app['costingAndValuation'], 'costOfLicense', data['costOfLicense']);
    Vue.set(app['costingAndValuation'], 'premium', data['premium']);

    Vue.set(app['bodyDetails'], 'height', data['height']);
    Vue.set(app['bodyDetails'], 'length', data['length']);
    Vue.set(app['bodyDetails'], 'width', data['width']);
    Vue.set(app['bodyDetails'], 'seatCapFront', data['seatCapFront']);

    Vue.set(app['bodyDetails'], 'distanceAxle1', data['distanceAxle1']);
    Vue.set(app['bodyDetails'], 'distanceAxle2', data['distanceAxle2']);
    Vue.set(app['bodyDetails'], 'distanceAxle3', data['distanceAxle3']);
    Vue.set(app['bodyDetails'], 'distanceAxle4', data['distanceAxle4']);

    Vue.set(app['weightDetails'], 'tareWeight', data['tareWeight']);
    Vue.set(app['weightDetails'], 'grossWeight', data['grossWeight']);


    Vue.set(app['assignmentDetails'], 'businessArea', data['business_area_code']);
    Vue.set(app['assignmentDetails'], 'directorate', data['directorate']);
    //Vue.set(app['assignmentDetails'], 'businessUnit', data['directorate']);
    Vue.set(app['assignmentDetails'], 'isOperationsVehicle', data['isPoolVehicle']);

    if (asyncResponse['payload'].hasOwnProperty('documents')) {
        let documents = asyncResponse['payload']['documents'];

        Vue.set(app['documents'], 'insurance', window.filterData('Insurance Cover', 'file_type', documents));
        Vue.set(app['documents'], 'certificate', window.filterData('Motor Vehicle Certificate', 'file_type', documents));
        Vue.set(app['images'], 'leftView', window.filterData("Left View", 'file_type', documents));
        Vue.set(app['images'], 'rightView', window.filterData("Right View", 'file_type', documents));
        Vue.set(app['images'], 'rearView', window.filterData("Back View", 'file_type', documents));
        Vue.set(app['images'], 'frontView', window.filterData("Front View", 'file_type', documents));
    }
}

window.getRegistrationDetails = function (requestReference) {
    console.log(requestReference);

    if (!requestReference || typeof requestReference === 'undefined') {
        //console.log('Returning')
        return;
    }
    $.ajax({
        type: "GET",
        url: document.querySelector('[name="vehicle_details"]').value,
        data: {reference: requestReference},
        dataType: 'json',
        success: function (asyncResponse) {
            //console.log('Returning Response', asyncResponse);
            displayVehicleDetails(asyncResponse);
        },
        error: function (xhr, settings, errorThrown) {
            toastr.error(
                'Vehicle details could not be retrieved due to connection error',
                'Vehicle Details'
            )
        }
    })
}

window.filterData = function (niddle, key, hayStack) {
    let result = hayStack.filter(function (document) {
        return document[key] === niddle;
    })

    if (result.length > 0)
        return result[0];
    else
        return null;
}
window.removeSpaces = function (value) {
    if (!value) return;
    return value.replace(/\s/g, '');
}
let app = new Vue({
    'el': '#kt_app_main',
    components: {},
    data() {
        return {
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
            isHeaderSaved: true,
            licenseTypes: [],
            organizationalUnits: [],
            otherDetails: {},
            regNumberValidity: {
                state: null,
                message: null
            },
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
                    model: {}
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
        this.getVehicleBrands();
        this.getConfiguredModels();
        this.getBodyTypes();
        this.getBusinessUnits();
        this.getOrganizationalUnits();
        this.getDirectorates();
        this.getCostCenters();
        this.getBusinessAreas();
        this.getFuelTypes();
        this.loadRegistrationTypes();
        this.loadLicenceClasses();
        this.getTransmissionTypes();
    },

    filters: {
        trimSpaces: function (val) {
            if (!val) return "";
            if (typeof val === 'number') return val;
            return val?.trim();
        },
        formatStatus: function (value) {
            if (!value) return 'Saved';
            if ('021') {
                return 'Pending';
            }
        }
    },

    mounted() {
        console.log("%c✔ ZESCO Fleet Master Running", "color: #148f32");

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

        if (window.reference) {
            window.getRegistrationDetails(window.reference);
        }

        $(document).on('keyup', '#chassisNumber', function () {
            this.value = this.value.toLocaleUpperCase();
        });

        $(document).on('keyup', '#engineNumber', function () {
            this.value = this.value.toLocaleUpperCase();
        });

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

        /*Inputmask("decimal", {
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
                text: "Are you sure you would like to remove the image?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, remove it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
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

        formatMoney: function (event) {
            setTimeout(function () {
                //ZMW
                let formatted = accounting.formatMoney(event.target.value, '');
                app['chassisDetails'].chargeOutRate = formatted;
            }, 300);
        },

        formatCostPriceAsMoney: function (event) {
            setTimeout(function () {
                let formatted = accounting.formatMoney(event.target.value, '');
                app['costingAndValuation'].costPrice = formatted;
            }, 300);
        },

        formatBookValueAsMoney: function (event) {
            setTimeout(function () {
                let formatted = accounting.formatMoney(event.target.value, '');
                console.log('%c' + formatted, "color: #148f32");
                app['costingAndValuation'].bookValue = formatted;
            }, 300);
        },

        // web UI event
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

                    app.businessUnits = response['payload'];
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error(
                        'Connection error. Could not retrieve data, some feature might not work.')
                });
        },

        getCostCenters: function () {
            fetch(document.querySelector('#costCenterEndpoint').value)
                .then(response => response.json())
                .then(function (response) {
                    // Populate results
                    if (response.state === 'failure') {
                        //show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    app.costCenters = response['payload'];
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

        getTransmissionTypes: function () {
            this.transmissionTypes = [
                {
                    'name': 'Automatic',
                    'code': 'AT'
                },
                {
                    'name': 'Manual',
                    'code': 'MT'
                }
            ]
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

        loadLicenceClasses: function () {
            this.licenseTypes = [
                {'code': 'A', 'name': 'Class A'},
                {'code': 'B', 'name': 'Class B'},
                {'code': 'C', 'name': 'Class C'},
                {'code': 'E', 'name': 'Class E'},
            ];
        },

        loadRegistrationTypes: function () {
            this.registrationTypes = [
                {
                    "label": 'Motor Vehicle',
                    'code': 'MV'
                },
                /*{
                    "label": 'Boat',
                    'code': 'BT'
                },
                {
                    "label": 'Trailer',
                    'code': 'TR'
                },*/
            ]
        },

        modelChanged(model) {
            this.vehicleHeader.model_guid = model?.id;
            this.vehicleHeader.model_code = model?.model_code;
            document.querySelector('#model').value = model?.id;
            document.querySelector('#model_code').value = model?.model_code;
            let $holder = document.querySelector('#model_holder');
            if ($holder) {
                $holder.value = model?.model_name;
            }
        },

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

                reader.onloadend = function () {
                    // set image data as background of div
                    uploadFile.closest("div").find('.imagePreview').css({
                        "background-image": "url(" + this.result + ")",
                        'display': 'block'
                    });
                }
            }

            $(uploadFile).closest('div').find('p').addClass('d-none');
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

        transmissionTypeChanged: function (transmissionType) {
            document.querySelector('#transmission_type').value = transmissionType?.code + ':' + transmissionType?.name;
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
                        toastr.warning('Invalid registration number, vehicle already registered')
                    }
                })
                .catch(function (error) {
                    // notify of error
                    toastr.error(
                        'Connection error. Could not retrieve data, some feature might not work.')
                });
        },

        vehicleBrandChanged(selectedValue) {
            $('#model_holder').addClass('d-none');
            $('#model').removeClass('d-none');
            this.selectedBrandModels = [];
            app.selectedBrandModels = app.configuredModels.filter(function (model) {
                return model.brand_guid?.toString().trim() === app?.vehicleHeader.brand_guid?.toString().trim();
            });
        },

        vehicleTypeChanged() {
            console.log('Vehicle Type Changed')
        },
    }
});

function userUnitChanged() {
    setTimeout(function () {
        const user_unit = $('#user_unit').val();

        let user_units = app.$data.organizationalUnits.filter(function (userUnit) {
            return userUnit['code_unit'].trim() === user_unit.trim();
        });

        let cost_center_code = user_units[0]?.cc_code;
        let business_unit_code = user_units[0]?.bu_code;


        let filteredCostCenters = app.$data.costCenters.filter(function (cost_center) {
            return cost_center['code_cost_center'].trim() === cost_center_code?.trim();
        });


        if (filteredCostCenters.length !== 0) {
            let costCentreOfInterest = filteredCostCenters[0];
            const costCenterDescription = costCentreOfInterest['code_cost_center'] + ':' + costCentreOfInterest['description'];
            $('[name="costCenter"]').val(costCenterDescription);
            Vue.set(app['assignmentDetails'], 'costCenter', costCenterDescription);
        }

        let filteredBusinessUnits = app.$data.businessUnits.filter(function (bu) {
            return bu.code_bu?.trim() === business_unit_code?.trim();
        });


        if (filteredBusinessUnits.length == 0) return;

        let businessUnitOfInterest = filteredBusinessUnits[0];

        const val = businessUnitOfInterest['code_bu'] + ':' + businessUnitOfInterest['description'];
        $('[name="businessUnit"]').val(val);
        Vue.set(app['assignmentDetails'], 'businessUnit', val);
    }, 1000);
    return;
}

function checkOnboardingHeaderStatus() {
    const headerId = document.querySelector('[name="headerId"]').value;

    if (headerId && parseInt(headerId) > 0) {
        // hide submit and cancel button
        $('.card-header').addClass('view_mode');
    }
}

(function (tmsApp, $) {
    function submitChassisDetails($form) {
        $('.print-error-msg').css('display', 'none');

        let fileUploads = [].slice.call(document.querySelectorAll('input[type="file"]'));
        let filesValid = true;
        fileUploads.map(function (fileSelect) {
            if (!fileSelect.files || fileSelect.files.length === 0) {
                toastr.error('Submission not accepted, You have not attached all required documents')
                filesValid = false;
                return;
            }
        });

        if (!filesValid) {
            return;
        }

        $form = document.forms['tmsChassisDetailsForm'];

        if (!$($form).valid()) {
            toastr.warning(
                "Sorry, the data did not pass validation check, check the data and try again."
            );
            return;
        }

        tmsApp.play_alert('sound-submit');
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
                            'Vehicle On-Boarding - General Data',
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
                    'Vehicle On-Boarding - General Data',
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

    function getLocations() {
        fetch(document.querySelector('#locationUrl').value)
            .then(response => response.json())
            .then(response => {
                let selectElem = $('select[name="vehicleLocation"]');
                // Populate results
                if (response.state === 'failure') {
                    //show errors
                    toastr.error('Connection error, no data found')
                    return;
                }

                let locations = response['payload'];
                tmsApp.populateDropDownList(selectElem, locations, "location", ["location"], "");

                let location = selectElem.attr('data-value');
                console.log(location);
                if (location) {
                    selectElem.val(location);
                    selectElem.trigger('change');
                }

            })
            .catch(function (error) {
                // notify of error
                toastr.error(
                    'Connection error. Could not retrieve data, some feature might not work.')
            });
    }

    function getSuppliers() {
        fetch(document.querySelector('#suppliersList').value)
            .then(response => response.json())
            .then(function (response) {
                let selectElem = $('select[name="supplierName"]');
                // Populate results
                if (response.state === 'failure') {
                    //show errors
                    toastr.error('Failed to retrieve Supplier Records', 'Connection Error');
                    return;
                }
                /*<option value>--Supplier--</option>
                <option v-for="supplier in supplierList"
                                            :key="supplier.code_supplier"
                    :value="supplier.code_supplier">
                            @{{ supplier.name_of_supplier }}
                    </option>*/

                app.supplierList = response['payload'];

                let suppliers = response['payload'];
                tmsApp.populateDropDownList(selectElem, suppliers, "code_supplier", ["code_supplier", "name_of_supplier"], " ==> ", '--Select Supplier--');

                let supplier = selectElem.attr('data-value');
                if (supplier) {
                    selectElem.val(supplier);
                    selectElem.trigger('change');
                }
            }).catch(function (error) {
            toastr.error(
                'Could not Retrieve Data, some feature might not work.', 'Connection error');
        });
    }

    function submitCostValuationDetails() {
        $('.print-error-msg').css('display', 'none');

        if (!$(document.forms['tms_costing_valuation_form']).valid()) {
            toastr.warning(
                "Sorry, the data did not pass validation check, check the data and try again."
            );
            return;
        }

        let form = document.forms['tms_costing_valuation_form'];
        let formData = new FormData(form);
        formData.set('bookValue', tmsApp.getFloat(formData.get('bookValue')).toString());
        formData.set('costPrice', tmsApp.getFloat(formData.get('costPrice')).toString());
        formData.set('costOfLicense', tmsApp.getFloat(formData.get('costOfLicense')).toString());
        tmsApp.play_alert('sound-submit');
        tmsApp.asyncPostFormData(
            form.action,
            formData,
            function (asyncResponse) {
                if ('state' in asyncResponse && asyncResponse.state != 'success') {
                    if (asyncResponse.hasOwnProperty('errors')) {
                        tmsApp.printErrorMsg(asyncResponse.errors);
                        return
                    }

                    setTimeout(function () {
                        tmsApp.systemError(
                            'Vehicle On-Boarding - Chassis Details',
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
                    'Vehicle On-Boarding - Cost & Valuation Details',
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

    function submitEngineDetails() {
        $('.print-error-msg').css('display', 'none');

        let $form = document.forms['engineDetailsForm'];
        const isValid = $($form).valid();

        if (!isValid) {
            toastr.warning(
                "Sorry, the data did not pass validation check, check the data and try again."
            );
            return;
        }

        let formData = new FormData($form);
        formData.set('engineCapacity', tmsApp.getRawNumber(formData.get('engineCapacity')).toString());
        formData.set('tank_capacity', tmsApp.getRawNumber(formData.get('tank_capacity')).toString());

        tmsApp.asyncPostFormData(
            $form.action,
            formData,
            function (asyncResponse) {
                if ('state' in asyncResponse && asyncResponse.state != 'success') {
                    if (asyncResponse.hasOwnProperty('errors')) {
                        tmsApp.printErrorMsg(asyncResponse.errors);
                        return
                    }

                    setTimeout(function () {
                        tmsApp.systemError(
                            'Vehicle On-Boarding - Technical Data',
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
                    'Vehicle On-Boarding - Technical Data',
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

    function submitBodyDetails() {
        $('.print-error-msg').css('display', 'none');

        let $form = document.forms['tms_body_weight_form'];
        const isValid = $($form).valid();

        if (!isValid) {
            toastr.warning(
                "Sorry, the data did not pass validation check, check the data and try again."
            );
            return;
        }

        let formData = new FormData($form);
        formData.set('grossWeight', tmsApp.getFloat(formData.get('grossWeight')).toString());
        formData.set('tareWeight', tmsApp.getFloat(formData.get('tareWeight')).toString());

        tmsApp.asyncPostFormData(
            $form.action,
            formData,
            function (asyncResponse) {
                if ('state' in asyncResponse && asyncResponse.state != 'success') {
                    if (asyncResponse.hasOwnProperty('errors')) {
                        tmsApp.printErrorMsg(asyncResponse.errors);
                        return
                    }

                    setTimeout(function () {
                        tmsApp.systemError(
                            'Vehicle On-Boarding - Body Details',
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
                    'Vehicle On-Boarding - Body Details',
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
            }
        );
    }

    function submitAssignmentDetails() {
        $('.print-error-msg').css('display', 'none');

        let $form = document.forms['tms_assignment_form'];
        const isValid = $($form).valid();

        if (!isValid) {
            toastr.warning(
                "Sorry, the data did not pass validation check, check the data and try again.",
                'Validation'
            );
            return;
        }

        tmsApp.confirm(
            'Completion Of Onboarding',
            "Are you sure you would like to complete the onboarding of this vehicle?",
            'Yes',
            'No',
            function () {
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
                                    'Vehicle On-Boarding - Assignment',
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
                            'Vehicle On-Boarding - Assignment',
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
                            tmsApp.showErrorMessages(xhr, 'On-Boarding Completion');
                        }, 300)
                    }
                );
            },
            function () {

            }
        );
    }

    function getPurchaseOrderDetails() {
        const purchaseOrder = document.querySelector('#purchase_order_number').value
        let formData = new FormData();
        formData.append('purchase_order_number', purchaseOrder);

        tmsApp.asyncGetFormData(
            $('#purchase_order_number').attr('data-action') + '?document_number=' + purchaseOrder,
            formData,
            function (response_data) {
                if (response_data.state === 'success') {
                    let supplierData = response_data['payload'][0];
                    // document_no "C01CR1000983"
                    if (supplierData['po_status_description'] !== 'ISSUED') {
                        let message = 'The Purchase Order ' + supplierData['document_no']
                            + ' for supplier ' + supplierData['name_of_supplier']
                            + ' can not be used as it is in ' + supplierData['po_status_description']
                            + ' State';
                        tmsApp.showSystemMessage('Purchase Order', message,
                            function () {
                            }, 'error');

                        document.querySelector('#purchase_order_number').value = '';
                    }

                    let selectElem = $('[name="supplierName"]');
                    selectElem.val(supplierData['code_supplier']);
                    selectElem.trigger('change');
                    selectElem.attr('readonly', true).trigger('change');
                    document.querySelector('#purchase_order_number').value = supplierData['document_no'];
                } else {
                    tmsApp.showToast(response_data['message'], 'error');
                }
            },
            function (xhr) {
                console.log(xhr);
                tmsApp.showToast('We could not complete processing your request, please try again later')
            }
        )
    }

    function vehicleWeightValidations(element) {
        const grossWeightCtl = document.querySelector('[name="grossWeight"]');
        const tareWeightCtl = document.querySelector('[name="tareWeight"]');
        switch (element.name) {
            case "tareWeight":
                let grossWeight = grossWeightCtl.value;
                if (grossWeight && typeof parseInt(tmsApp.getFloat(grossWeight)) === 'number') {
                    // if net-weight is a greater than gross weight
                    if (element.value > grossWeight) {
                        tmsApp.showToast('Vehicle net weight can not be more than the gross weight', 'error', 'Validation Error');
                        document.querySelector('#tms_save_body').setAttribute('disabled', 'disabled');
                    } else {
                        document.querySelector('#tms_save_body').removeAttribute('disabled');
                    }
                }
                break;

            case "grossWeight":
                let tareWeight = tareWeightCtl.value;
                if (tareWeight && typeof parseInt(tmsApp.getFloat(tareWeight)) === 'number') {
                    // if net-weight is a greater than gross weight
                    if (element.value < tareWeight) {
                        tmsApp.showToast('Vehicle gross weight can not be less than the net weight', 'error', 'Validation Error');
                        document.querySelector('#tms_save_body').setAttribute('disabled', 'disabled');
                    } else {
                        document.querySelector('#tms_save_body').removeAttribute('disabled');
                    }
                }
                break;
            default:
                break;
        }
    }

    function nativeVehicleBrandChanged() {
        const brandId = $('select[name="brand"]').val()?.toString().trim();
        Vue.set(app['selectedBrandModels'], []);
        let filteredResults = app.$data.configuredModels.filter(function (model) {
            return model.brand_guid?.toString().trim() === brandId;
        });
        console.log(filteredResults);
        Vue.set(app['selectedBrandModels'], filteredResults);
    }

    tmsApp.appFormValidator('form[name="tmsChassisDetailsForm"]',
        {
            'chassisNumber': {
                required: true
            },
            'engineNumber': {
                required: true
            },
            'whiteBookSerial': {
                required: true
            },
            'yearOfManufacture': {
                required: true
            },
            'registrationDate': {
                required: true
            },
            'chargeOutRate': {
                required: true
            },
            'requiredMinimumDrivingLicense': {
                required: true
            },
            'initialOdometerReading': {
                required: true
            },
            'currentOdometerReading': {
                required: true
            },
            'odometerReadingLastService': {
                required: true
            },
            /* 'nextServiceOdometerReading': {
                 required: true
             },*/
            'inspectionDate': {
                required: true
            },

            'motor_vehicle_certificate': {
                required: true
            },
            'insurance_cover_note': {
                required: true
            },
            front_view: {
                required: true
            },
            rear_view: {
                required: true
            },
            right_view: {
                required: true
            },
            left_view: {
                required: true
            }
        },
        {
            'chassisNumber': {
                required: "Chassis number is required"
            },
            'engineNumber': {
                required: "Engine number is required"
            },
            'whiteBookSerial': {
                required: "Provide White Book Serial number"
            },
            'yearOfManufacture': {
                required: "Year vehicle was manufactured is required"
            },
            'registrationDate': {
                required: "Indicate when vehicle was registered with the authority"
            },
            'chargeOutRate': {
                required: "You have not provided charge-out rate"
            },
            'requiredMinimumDrivingLicense': {
                required: "Specify the minimum driver's license class required"
            },
            'initialOdometerReading': {
                required: "Field is required"
            },
            'currentOdometerReading': {
                required: "Provide current odometer reading"
            },
            'odometerReadingLastService': {
                required: "Odometer reading at last service is required"
            },
            'nextServiceOdometerReading': {
                required: "Your must provide the odometer reading when vehicle is next due for service"
            },
            'inspectionDate': {
                required: "Your have not provided the date the vehicle was inspected"
            },
            'motor_vehicle_certificate': {
                required: "Motor Vehicle Certificate is required"
            },
            'insurance_cover_note': {
                required: "Insurance Cover Note must be attached"
            }
        }
    );

    $('[name="tmsChassisDetailsForm"]').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        submitChassisDetails(e.currentTarget);
    });

    $('[name="supplierName"]').on('change', function (e) {
        document.querySelector("#purchase_order_number").value = '';
    });

    tmsApp.appFormValidator('form[name="engineDetailsForm"]',
        {
            'numberOfCylinders': {
                required: true
            },
            'engineCapacity': {
                required: true
            },
            'fuelTypes': {
                required: true
            },
            'fuelConsumption': {
                required: true
            },
            'engineType': {
                required: true
            },


            'claimedEnginePower': {
                required: true
            },
            'actualEnginePower': {
                required: true
            },
            'engineBrand': {
                required: true
            },

            'transmission_type': {
                required: true
            },

            'tank_capacity': {
                required: true
            },

            'numberOfTyres': {
                required: true
            },

            'tyreBrand': {
                required: true
            },

            'frontTyreSize': {
                required: true
            },

            'rearTyreSize': {
                required: true
            },

            'batteryBrand': {
                required: true
            },
            'batterySize': {
                required: true
            },

            'batteryPower': {
                required: true
            },

        },
        {
            'numberOfCylinders': {
                required: 'Number of cylinders is required'
            },
            'engineCapacity': {
                required: 'Engine capacity is required'
            },
            'fuelTypes': {
                required: 'Fuel Type is required'
            },
            'fuelConsumption': {
                required: 'Fuel Consumption is required'
            },
            'engineType': {
                required: 'Engine Code is required'
            },
        }
    );

    $('[name="engineDetailsForm"]').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        submitEngineDetails(e.currentTarget);
    });


    tmsApp.appFormValidator('form[name="tms_costing_valuation_form"]',
        {
            'supplierName': {
                required: true
            },
            'costPrice': {
                required: true
            },
            'yearOfPurchase': {
                required: true
            },
            'bookValue': {
                required: true
            },
            'assetNumber': {
                required: true
            },
            'costOfLicense': {
                required: true
            },
            'premium': {
                required: true
            },
        },
        {
            'supplierName': {
                required: "Vehicle Supplier is required"
            },
            'costPrice': {
                required: "Cost is required"
            },
            'yearOfPurchase': {
                required: "You must declare the year vehicle was purchased"
            },
            'bookValue': {
                required: "Item current book value must be declared"
            },
            'assetNumber': {
                required: "Asset number is mandatory for asset management"
            },
            'costOfLicense': {
                required: "Cost of Road Tax & Fitness"
            },
            'premium': {
                required: "You must declare the insurance premium being paid"
            },
        }
    );

    $('[name="tms_costing_valuation_form"]').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        submitCostValuationDetails();
    });


    tmsApp.appFormValidator('form[name="tms_body_weight_form"]',
        {
            'height': {
                required: true
            },
            'length': {
                required: true
            },
            'width': {
                required: true
            },
            'seatCapFront': {
                required: true
            },
            'tareWeight': {
                required: true
            },
            'grossWeight': {
                required: true
            },
        },
        {
            'height': {
                required: "required"
            },
            'length': {
                required: "required"
            },
            'width': {
                required: "required"
            },
            'seatCapFront': {
                required: "required"
            },
            'tareWeight': {
                required: "required"
            },
            'grossWeight': {
                required: "Vehicle Weight is required"
            },
        }
    );

    $('[name="tms_body_weight_form"]').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        submitBodyDetails();
    });


    tmsApp.appFormValidator('form[name="tms_assignment_form"]',
        {
            businessArea: {
                required: true
            },
            isPoolVehicle: {
                required: true
            },
            directorate: {
                required: true
            },
            businessUnit: {
                required: true
            },
            costCenter: {
                required: true
            },
            isMileageExempt: {
                required: true
            },
            responsibleHOD: {
                required: $("#isPoolVehicle:checked")
            },

            responsibleHODId: {
                required: $("#isPoolVehicle:checked")
            },
            vehicleHolder: {
                required: $("#isNotPoolVehicle:checked")
            },

            vehicleHolderId: {
                required: $("#isNotPoolVehicle:checked")
            },
        },
        {
            businessArea: {
                required: "You must declare the business area"
            },
            isPoolVehicle: {
                required: "You must declare if the vehicle is operational or personal to holder"
            },
            directorate: {
                required: 'Directorate is required'
            },
            businessUnit: {
                required: "Business Unit is required"
            },
            costCenter: {
                required: "Cost Center is required"
            },
            isMileageExempt: {
                required: "Required"
            },
            responsibleHOD: {
                required: "Personnel responsible for vehicles must be declared"
            },

            responsibleHODId: {
                required: "Personnel responsible for vehicles must be declared"
            },
            vehicleHolder: {
                required: "Declare the officer assigned the vehicle"
            },

            vehicleHolderId: {
                required: "Declare the officer assigned the vehicle"
            },
        }
    );

    $('[name="tms_assignment_form"]').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        submitAssignmentDetails();
    });

    $('[name="poSearchBtn"]').on('click', function (e) {
        let  poNumber = $('#purchase_order_number').value;
        if (poNumber && poNumber < 12) {
            toastr.warning('Purchase order number is invalid');
            return;
        }
        getPurchaseOrderDetails();
    });

    $('#purchase_order_number').on('keyup paste', function () {
        if (this.value && this.value.length < 12) {
            return;
        }
        getPurchaseOrderDetails();
    });

    // vehicleWeightValidations
    $(document).on('change', 'select[name="brand"]', function () {
        nativeVehicleBrandChanged();
    });

    $(document).on('change', '.weight_control', function () {
        console.log(this.name);
        vehicleWeightValidations(this)
    });

    $(document).on('change', '[name="whiteBookSerial"]', function () {
        let ref = document.querySelector('#whiteBookSerial').value
        fetch(document.querySelector('#documentValidationUrl').value +
            '?method=motorVehicleCertificate&key=' + ref)
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                return response.json();
            })
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
    });

    checkOnboardingHeaderStatus();

    $(document).on('click', '.card-toolbar .btn', function () {
        console.log(this.id);
        switch (this.id) {
            case "editRecordBtn":
                $('.card-header').removeClass('view_mode').addClass('edit_mode')
                document.querySelector('#model_holder').style.display = 'none';
                let $locationHolder = document.querySelector('#locationHolder');
                $locationHolder.style.display = 'none';

                $('#vehicleLocation').val($locationHolder.value);
                //$('#model_holder').addClass('d-none');
                $('#model').removeClass('d-none');
                $('#vehicleLocation').removeClass('d-none');
                $('#brand').change();
                break;
            case 'cancelEditLink':
                $('.card-header').removeClass('edit_mode').addClass('view_mode')
                document.querySelector('#model_holder').style.display = null;
                document.querySelector('#locationHolder').style.display = null;

                $('#model').addClass('d-none');
                $('#vehicleLocation').addClass('d-none');
                break;
            case "submitBtn":
                break;
            case "resetFormBtn":
                document.forms['tms_vehicle_header_form'].reset();
                break;
            default:
                break;
        }
    });

    getLocations();

    getSuppliers();

})(window.tmsApp || {}, jQuery);

