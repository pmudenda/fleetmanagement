@extends('layouts.app')
@push('styles')
    <link href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}"/>
@endpush
@section('content')

    <x-content-header :pageTitle="'Charge out rate'" :activeCrumb="'Charge Out Rate'" :link="'home'"
                      :linkText="'Charge Out Rate'"/>
    <section class="content">
        <div class="row g-12 g-xl-12" id="kt_app_main">
            <div class="card mb-xl-10">
                <div class="card-header">
                    <div class="card-title">
                        <h2> Charge out rate</h2>
                        <span class="ml-2 indicator-pill
                                whitespace-nowrap orange">
                                    <span>Not Saved</span>
                            </span>
                    </div>
                    <div id="actionButtonsContainer"
                         class="card-toolbar justify-content-end">
                        <button type="submit" id="submitSaveChargeRateBtn"
                                class="btn btn-success btn-sm mr-3">
                            <i class="fas fa-save"></i>
                            Submit
                        </button>

                        <button type="reset" id="resetRequisitionBtn"
                                class="btn btn-danger btn-sm mr-3">
                            <i class="fas fa-undo"></i>
                            Clear Data
                        </button>

                    </div>
                </div>

                <!--begin::Card body-->
                <div class="card-body">
                    <x-error-view/>
                    <form name="charge_out_form"
                          id="charge_out_form"
                          class="form"
                          action="{{route('save.charge.out.rate')}}">
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
                                                <select class="form-control view_mode"
                                                        name="brand"
                                                        id="brand">
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
                                                <select class="form-select form-select-sm view_mode"
                                                        required
                                                        name="model"
                                                        id="model">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="model_code"
                                           class="fs-6 fw-semibold form-label col-md-3">
                                        <span class="required">Model Code</span>
                                    </label>

                                    <div class="col-md-9 fv-row">
                                        <div class="col-md-9">
                                            <div class="w-100">
                                                <input
                                                        class="form-control form-control-solid"
                                                        name="model_code"
                                                        readonly id="model_code"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="bodyType"
                                           class="fs-6 fw-semibold form-label col-md-3">
                                        <span class="required">Body Type</span>
                                    </label>

                                    <div class="col-md-9 fv-row ">
                                        <div class="col-md-9">
                                            <div class="w-100">
                                                <select class="form-select form-select-sm view_mode"
                                                        required
                                                        id="bodyType"
                                                        name="bodyType">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--- RIGHT -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="rate"
                                           class="fs-6 fw-semibold form-label col-md-3">
                                        <span class="required">Charge</span>
                                    </label>
                                    <div class="col-md-9 fv-row">
                                        <div class="col-md-9">
                                            <div class="w-100 fv-row">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            ZMW
                                                        </div>
                                                    </div>
                                                    <input class="form-control form-control-solid"
                                                           name="rate"
                                                           id="rate"
                                                           placeholder="Enter charge rate"
                                                           type="text"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr class="mt-10">
                    <div class="table-responsive">
                        <table role="table"
                               aria-label="charge out rates"
                               class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                               id="chargeOutRateTable">
                            <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="list-row-checkbox" type="checkbox" data-kt-check="true"
                                               data-kt-check-target="#form-check-input" value="all"/>
                                    </div>
                                </th>

                                <th>
                                    Specification
                                </th>

                                <th>
                                    Description
                                </th>
                                <th>
                                    Charge
                                </th>

                                <th>
                                    Date Registered
                                </th>

                                <th>
                                    Actions
                                </th>
                            </tr>
                            </thead>

                            <tbody class="text-gray-600 fw-semibold">
                            @foreach($chargeOutRateList as $chargeOutRate)
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="list-row-checkbox" type="checkbox" value="item.guid"/>
                                        </div>
                                    </td>

                                    <td>
                                        <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                            {{$chargeOutRate->vehicle_specification}}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                            {{$chargeOutRate->vehicle_description}}
                                        </a>
                                    </td>

                                    <td>
                                        <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                            {{$chargeOutRate->charge}}
                                        </a>
                                    </td>

                                    <td>
                                        {{Carbon\Carbon::parse($chargeOutRate->created_at)->format('d/m/y')}}
                                    </td>

                                    <td class="text-start">
                                        <div class="dropdown">
                                            <button
                                                    class="btn btn-light btn-active-light-primary btn-sm dropdown-toggle"
                                                    type="button"
                                                    id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li>
                                                    <a class="dropdown-item" data-kt-action="edit"
                                                       href="#">
                                                        Edit
                                                    </a>
                                                </li>

                                                <li>
                                                    <a class="dropdown-item" data-kt-action="edit"
                                                       href="">
                                                        View
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!--END:::VEHICLE HEADER -->
            <input type="hidden"
                   id="brands-api"
                   value="{{ route('brands.get') }}"/>
            <input type="hidden"
                   id="modelEndpoint"
                   name="modelEndpoint"
                   value="{{ route('models.get') }}"/>
            <input type="hidden"
                   id="bodyTypesEndpoint"
                   name="bodyTypesEndpoint"
                   value="{{ route('body_type.get') }}"/>
        </div>
    </section>
@endsection

@push('scripts')

    <script>

        (function (tmsApp, $) {
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

                        let vehicleBrands = response['payload'];
                        tmsApp.populateDropDownList(selectElem, vehicleBrands, "code", ["name"], "");

                        let brand_id = selectElem.attr('data-value');

                        if (brand_id) {
                            selectElem.val(brand_id);
                            selectElem.trigger('change');
                        }
                    })
                    .catch(function (error) {
                        toastr.error(
                            'Connection error. Could not retrieve data, some feature might not work.')
                    });
            }

            function nativeVehicleBrandChanged() {
                const brandId = $('select[name="brand"]').val()?.toString().trim();

                if (!brandId) {
                    return;
                }

                let filteredResults = window.VehicleModels.filter(function (model) {
                    return model.brand_code?.toString().trim() === brandId?.toString().trim();
                });

                if (filteredResults.length === 0) {
                    getConfiguredModels();
                }

                let selectElem = $('select[name="model"]');
                tmsApp.populateDropDownList(selectElem,
                    filteredResults, "code",
                    ["model_name", "model_code"], " => ");

                let model = selectElem.attr('data-value');

                if (model) {
                    selectElem.val(model);
                    selectElem.trigger('change');
                }
            }

            function postVehicleHeaderData() {
                $('.print-error-msg').css('display', 'none');

                if (!$('form[name="charge_out_form"]').valid()) {
                    toastr.warning(
                        "Sorry, the data did not pass validation check, check the data and try again."
                    );
                    return;
                }

                let $form = document.forms['charge_out_form'];

                tmsApp.asyncPostFormData(
                    $form.action,
                    new FormData($form),
                    function (asyncResponse) {
                        if (!asyncResponse.success) {
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
                        tmsApp.populateDropDownList(selectElem, bodyTypes, "code", ["name"], "");

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

            console.log("%c✔ ZESCO FLEET MASTER RUNNING", "color: #148f32");
            console.log("%c✔ CHARGE OUT RATE CONFIGURATION", "color: #148f32");

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

            $(document).on('input', '#rate', function (e) {
                tmsApp.numberOnly(e);
            });

            $(document).on('change', 'select[name="brand"]', function () {
                nativeVehicleBrandChanged();
            });

            $(document).on('change', '#name', function () {
                let formatted = accounting.formatMoney(this.value, '');
                console.log('%c' + formatted, "color: #148f32");
                this.value = formatted;
            });

            $(document).on('change', 'select[name="model"]', function () {
                const modelCode = $(this).val()?.toString().trim();
                const modelName = $('#model option:selected').text()?.split('=>')[1]?.trim();
                if (!modelCode) {
                    return;
                }
                console.log(modelName);
                const brandCode = $('select[name="brand"]').val();
                let filteredResults = window.VehicleModels.filter(function (model) {
                    return (model.code?.toString().trim()
                        === modelCode && model?.brand_code
                        === brandCode && model.model_code
                        === modelName);
                });

                if (filteredResults.length > 0) {
                    console.log(filteredResults[0]);
                    $('#model_code').val(filteredResults[0]?.model_code);
                    /*$('#bodyType').val(filteredResults[0]?.body_type_code)
                        .attr('disabled', true).change();*/
                }
            });

            $('[name="charge_out_form"]').on('submit', function (e) {
                e.preventDefault();
                e.stopPropagation();
                postVehicleHeaderData();
            });

            getConfiguredModels();

            getVehicleBrands();

            getBodyTypes();

        })(window.tmsApp || {}, jQuery);
    </script>

@endpush
