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
<link href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}" />
@endpush
@section('content')
<x-content-header />
<section class="content">
    <div class="row g-12 g-xl-12" id="kt_app_main">

        <!--BEGIN:::VEHICLE HEADER -->
        <div class="card mb-xl-10">
            <div id="card_header" class="card-header min-h-2px">
                <div class="card-title">
                    <h2> Vehicle On-Boarding</h2>
                    <span 
                        class="ml-2 indicator-pill whitespace-nowrap orange"><span>Not Saved</span></span>
                </div>


                <!--begin::Card body-->
                <div class="card-body">
                    <x-error-view />
                    <form name="vehicleHeaderForm" id="tms_vehicle_header_form" class="form"
                        action="{{route('new.vehicle.header')}}">
                        <input type="hidden" name="doctype" value="VehicleHeader" />
                        <div class="row">
                            <div class="col-md-6">

                            </div>
                            <div class="col-md-6"></div>
                        </div>

                        <div class="row  mt-5">
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
                                                    readonly id="model_code" />
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
                                                <input type="hidden" id="bodyType_holder" name="bodyType_holder" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                </div>
                </form>
            </div>
        </div>

        <!--END:::VEHICLE HEADER -->

        <!--END:::DETAILS  -->
        {{-- @include('vehicleManagement.partial.data_end_point') --}}
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
<x-employee-search-modal />
@endsection

@push('scripts')

<script>
    
(function (tmsApp, $) {

// web UI event
function formatMoney(event) {
    setTimeout(function () {
        //ZMW
        let formatted = accounting.formatMoney(event.target.value, '');
        //app['chassisDetails'].chargeOutRate = formatted;
    }, 300);
}

function nativeUserUnitChanged(user_unit) {

    //Vue.set(app['vehicleHeader'], 'user_unit_code', user_unit);
    document.querySelector('[name="user_unit"]').value = user_unit;

    let filteredUserUnits = window.organizationUnits.filter(function (userUnit) {
        return userUnit['code_unit']?.trim() === user_unit?.trim();
    });

    let cost_center_code = '';
    let business_unit_code = '';
    if (filteredUserUnits.length !== 0) {
        let userUnit = filteredUserUnits[0];
        cost_center_code = userUnit?.cc_code
        business_unit_code = userUnit?.bu_code
    }

    if (cost_center_code == '' || business_unit_code == '') {
        return;
    }

    let filteredCostCenters = window.costCenters.filter(function (cost_center) {
        return cost_center['code_cost_center']?.trim() === cost_center_code?.trim();
    });

    if (filteredCostCenters.length !== 0) {
        let costCentreOfInterest = filteredCostCenters[0];

        console.log(costCentreOfInterest);

        //this.assignmentDetails.costCenter = costCentreOfInterest['code_cost_center'] + ':' + costCentreOfInterest['description'];
        $('[name="costCenter"]').val(costCentreOfInterest['code_cost_center'] + ':' + costCentreOfInterest['description']);
    }

    let filteredBusinessUnits = window.businessUnits.filter(function (bu) {
        return bu.code_bu?.trim() === business_unit_code?.trim();
    });

    if (filteredBusinessUnits.length === 0) return;

    let businessUnitOfInterest = filteredBusinessUnits[0];

    const val = businessUnitOfInterest['code_bu'] + ':' + businessUnitOfInterest['description'];
    $('[name="businessUnit"]').val(val);
    Vue.set(app['assignmentDetails'], 'businessUnit,', val);
}

function submitChassisDetails($form) {
    $('.print-error-msg').css('display', 'none');

    if (document.querySelector('[name="front_view"]').files.length == 0) {
        toastr.error('You have not attached the vehicle Front View Image', 'Validation Failure')
        return;
    }
    if (document.querySelector('[name="rear_view"]').files.length == 0) {
        toastr.error('You have not attached the vehicle Back View Image', 'Validation Failure')
        return;
    }
    if (document.querySelector('[name="right_view"]').files.length == 0) {
        toastr.error('You have not attached the vehicle Right View Image', 'Validation Failure')
        return;
    }
    if (document.querySelector('[name="left_view"]').files.length == 0) {
        toastr.error('You have not attached the vehicle Left View Image', 'Validation Failure')
        return;
    }

    /*let fileUploads = [].slice.call(document.querySelectorAll('input[type="file"]'));
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
    }*/

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
    formData.set('premium', tmsApp.getFloat(formData.get('premium')).toString());
    tmsApp.play_alert('sound-submit');
    tmsApp.asyncPostFormData(
        form.action,
        formData,
        function (asyncResponse) {
            if ('state' in asyncResponse && asyncResponse.state !== 'success') {
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
            if ('state' in asyncResponse && asyncResponse.state !== 'success') {
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
            if ('state' in asyncResponse && asyncResponse.state !== 'success') {
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
                    if ('state' in asyncResponse && asyncResponse.state !== 'success') {
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

function submitAccessoriesDetails() {
    $('.print-error-msg').css('display', 'none');

    let $form = document.forms['tms_accessories_form'];
    const isValid = $($form).valid();

    if (!isValid) {
        toastr.warning(
            "Sorry, the data did not pass validation check, for details, check the indicated fields",
            'Validation'
        );
        return;
    }

    tmsApp.asyncPostFormData(
        $form.action,
        new FormData($form),
        function (asyncResponse) {
            if ('state' in asyncResponse && asyncResponse.state !== 'success') {
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
                if (['CLOSED', 'ISSUED'].indexOf(supplierData['po_status_description']) < 0) {
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

                let price = supplierData['price'];
                let costPriceInput = document.querySelector('[name="costPrice"]');
                costPriceInput.value = tmsApp.formatMoney(price, 2);
                costPriceInput.setAttribute('readonly', 'readonly');

                let bookValueInput = document.querySelector('[name="bookValue"]');
                bookValueInput.value = tmsApp.formatMoney(price, 2);
                bookValueInput.setAttribute('readonly', 'readonly');

                document.querySelector('#purchase_order_number').value = supplierData['document_no'];

                calculateInsurancePremium(price);
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

function calculateInsurancePremium(currentValue) {
    let insurancePremium = tmsApp.formatMoney(((10 / 100) * currentValue), 2);
    let insurancePremium_Ctl = document.querySelector('#premium')
    insurancePremium_Ctl.value = insurancePremium;
    insurancePremium_Ctl.setAttribute('readonly', 'readonly');

}

function getCostCenters() {
    fetch(document.querySelector('#costCenterEndpoint').value)
        .then(response => response.json())
        .then(function (response) {
            // Populate results
            if (response.state === 'failure') {
                //show errors
                toastr.error('Connection error, no data found')
                return;
            }

            window.costCenters = response['payload'];
            //app.costCenters = response['payload'];
        })
        .catch(function (error) {
            // notify of error
            toastr.error(
                'Connection error. Could not retrieve data, some feature might not work.')
        });
}

function validateRegistrationNumber() {
    let ref = document.querySelector('#registrationNumber').value
    fetch(document.querySelector('#documentValidationUrl').value +
        '?method=registration_number&key=' + ref)
        .then(response => response.json())
        .then(response => {
            // Populate results
            if (response.state === 'failure') {
                //show errors
                toastr.error('Vehicle registration number could not be verified', 'Connection error')
                return;
            }

            if (response['payload'].validity) {

                toastr.success(response['payload'].message, 'Registration Number Validation')
                let assetNumberInput = document.querySelector("#assetNumber");
                if (assetNumberInput) {
                    assetNumberInput.value = window.removeSpaces(document.querySelector('#registrationNumber').value);
                }
                document.querySelector("#submitBtn").removeAttribute('disabled')
            } else {
                document.querySelector("#submitBtn").setAttribute('disabled', 'disabled')
                tmsApp.systemError(
                    'Registration Number Validation',
                    'Duplicate registration number, vehicle already with registration number ' +
                    ref + ' already exists'
                );
            }
        })
        .catch(function (error) {
            // notify of error
            toastr.error(
                'Connection error. Could not retrieve data, some feature might not work.', 'Invalid Registration')
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

/*getConfiguredModels: function () {
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
},*/

function getTyresBrands() {
    fetch(document.querySelector('#tyreUrl').value)
        .then(response => response.json())
        .then(response => {

            let frontTyreElem = $('select[name="frontTyreSize"]');
            let rearTyreSizeElem = $('select[name="rearTyreSize"]');
            // Populate results
            if (response.state === 'failure') {
                //show errors
                toastr.error('Connection error, no tyre brand information found')
                return;
            }

            let tyreSizes = response['payload'];

            tmsApp.populateDropDownList(frontTyreElem, tyreSizes, "description", ["description"], "");

            tmsApp.populateDropDownList(rearTyreSizeElem, tyreSizes, "description", ["description"], "");

              let frontSize = frontTyreElem.attr('data-value');
              console.log(frontSize);
              if (frontSize) {
                  frontTyreElem.val(frontSize);
                  frontTyreElem.trigger('change');
              }

            let rearTyreSize = rearTyreSizeElem.attr('data-value');
            console.log(rearTyreSize);
            if (rearTyreSize) {
                rearTyreSizeElem.val(frontSize);
                rearTyreSizeElem.trigger('change');
            }
        })
        .catch(function (error) {
            // notify of error
            toastr.error('Connection error. Could not retrieve tyre information, some feature might not work.')
        });
}

function getBatterySizes() {
    fetch(document.querySelector('#batteryUrl').value)
        .then(response => response.json())
        .then(response => {

            let selectElem = $('select[name="batterySize"]');
            // Populate results
            if (response.state === 'failure') {
                //show errors
                toastr.error('Connection error, no tyre brand information found')
                return;
            }

            let tyreBrands = response['payload'];
            tmsApp.populateDropDownList(selectElem, tyreBrands, "description", ["description"], "");

            let location = selectElem.attr('data-value');
            console.log(location);
            if (location) {
                selectElem.val(location);
                selectElem.trigger('change');
            }
        })
        .catch(function (error) {
            // notify of error
            toastr.error('Connection error. Could not retrieve tyre information.', 'Connection error')
        });
}

function checkWhiteBookSerialValidity() {
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
                toastr.error('Connection error, white book number could not be verified')
                return;
            }

            if (response['payload'].validity) {
                console.log(response['payload'].validity);
                document.querySelector("#tms_save_chassis").removeAttribute('disabled');
                toastr.success('White Book number valid', 'White Book Number Validation');
            } else {
                toastr.error('Duplicate White book Serial Number', 'Invalid White book serial')
                document.querySelector("#tms_save_chassis").setAttribute('disabled', 'disabled')
                return;
            }
        })
        .catch(function (error) {
            // notify of error
            toastr.error(
                'Connection error. Could not retrieve data, some feature might not work.')
        });
}

function checkChassisNumberValidity() {
    let chassisNumber = document.querySelector('[name="chassisNumber"]').value;
    fetch(document.querySelector('#documentValidationUrl').value
        + '?method=chassis&key=' + chassisNumber)
        .then(response => response.json())
        .then(response => {
            // Populate results
            if (response.state === 'failure') {
                //show errors
                toastr.error('Vehicle Identification number verification failed', 'Connection error');
                tmsApp.systemError(
                    'Chassis Number Validation',
                    'Duplicate Chassis number, vehicle already with chassis number ' +
                    chassisNumber + ' already exists'
                );
                document.querySelector("#tms_save_chassis").setAttribute('disabled', 'disabled')
                return;
            } else {
                document.querySelector("#tms_save_chassis").removeAttribute('disabled');
                toastr.success('Chassis number valid', 'Chassis Number Validation');
            }
        })
        .catch(function (error) {
            // notify of error
            toastr.error(
                'Could not retrieve data, some feature might not work.', 'Connection error')
        });
}

function checkEngineNumberValidity() {
    let engineNumber = document.querySelector('[name="engineNumber"]').value;
    fetch(document.querySelector('#documentValidationUrl').value
        + '?method=engine_number&key=' + engineNumber)
        .then(response => response.json())
        .then(response => {
            // Populate results
            if (response.state === 'failure') {
                //show errors
                toastr.error('Vehicle Engine number verification failed', 'Connection error');
                tmsApp.systemError(
                    'Chassis Number Validation',
                    'Duplicate Engine number, vehicle already with Engine number number ' +
                    engineNumber + ' already exists'
                );
                document.querySelector("#tms_save_chassis").setAttribute('disabled', 'disabled')
                return;
            } else {
                document.querySelector("#tms_save_chassis").removeAttribute('disabled');
                toastr.success('Engine Number number valid', 'Engine Number Validation');
            }
        })
        .catch(function (error) {
            // notify of error
            toastr.error(
                'Could not retrieve data, some feature might not work.', 'Connection error')
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

function getOrganizationalUnits() {
    fetch(document.querySelector('#orgUnitsEndpoint').value)
        .then(response => response.json())
        .then(response => {
            // Populate results
            let selectElem = $('select[name="user_unit"]');

            if (response.state === 'failure') {
                //show errors
                toastr.error('Connection error, no data found')
                return;
            }

            let userUnits = response['payload'];
            window.organizationUnits = userUnits;
            tmsApp.populateDropDownList(selectElem, userUnits, "code_unit", ['code_unit', "description"], " => ");

            let userUnitId = selectElem.attr('data-value');
            if (userUnitId) {
                selectElem.val(userUnitId);
                selectElem.trigger('change');
            }
        })
        .catch(function (error) {
            // notify of error
            toastr.error(
                'Connection error. Could not retrieve data, some feature might not work.')
        });
}

getCostCenters();

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

$('#registrationNumber').on('keyup paste enter', function () {
    if (!this.value || this.value.replace('_', '').length < 8) {
        return;
    }
    setTimeout(function () {
        validateRegistrationNumber();
    }, 300);
});

let saveVehicleHeaderInformation = function (e) {
    e.preventDefault();
    this.postVehicleHeaderData();
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

        motor_vehicle_certificate: {
            required: true
        },
        insurance_cover_note: {
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
        chassisNumber: {
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
            required: "provide the vehicles initial odometer value"
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
        inspectionDate: {
            required: "Your have not provided the date the vehicle was inspected"
        },
        motor_vehicle_certificate: {
            required: "Motor Vehicle Certificate is required"
        },
        insurance_cover_note: {
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


$('[name="chassisNumber"]').on('change paste', function () {
    checkChassisNumberValidity();
});

$('[name="engineNumber"]').on('change paste', function () {
    checkEngineNumberValidity();
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


// vehicleWeightValidations
$(document).on('change', 'select[name="brand"]', function () {
    nativeVehicleBrandChanged();
});

$(document).on('change', 'select[name="user_unit"]', function () {
    let user_unit = $(this).val();
    nativeUserUnitChanged(user_unit);
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

$(document).on('change', '.weight_control', function () {
    vehicleWeightValidations(this)
});

$(document).on('change paste', '[name="whiteBookSerial"]', function () {
    checkWhiteBookSerialValidity()
});

$(document).on('click', 'button[data-zfm-view-file]', function () {
    console.log(this);
    $("#documentView").attr('src', $(this).attr('data-document-url'));
    let fileViewModal = bootstrap.Modal.getOrCreateInstance(document.querySelector('#fileViewModal'))
    fileViewModal.show();
})

$(document).on('click', '.card-toolbar .btn', function () {
    console.log(this.id);
    switch (this.id) {
        case "editRecordBtn":
            $('.card-header').removeClass('view_mode').addClass('edit_mode')
            document.querySelector('#model_holder').style.display = 'none';
            let $locationHolder = document.querySelector('#locationHolder');
            $locationHolder.style.display = 'none';
            //$('#vehicleLocation').val($locationHolder.value);
            //$('#model_holder').addClass('d-none');
            //$('#model').removeClass('d-none');
            //$('#vehicleLocation').removeClass('d-none');
            //$('#brand').change();
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
        case "printDisk":

            break;
        default:
            break;
    }
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