<div class="card mb-xl-10">
    <div id="card_header" class="card-header min-h-2px">
        <div class="card-title">
            <h2> Vehicle On-Boarding</h2>
            <span v-if="!vehicleHeader.isHeaderSaved"
                  class="ml-2 indicator-pill whitespace-nowrap orange"><span>Not Saved</span></span>
            <span v-else class="ml-2 indicator-pill whitespace-nowrap green">
                <span>
                    @{{ vehicleHeader.on_boarding_status | formatStatus }}
                </span>
            </span>
        </div>

        <div v-if="!vehicleHeader.isHeaderSaved" id="actionButtonsContainer" class="card-toolbar justify-content-end">
            <button type="button" id="submitBtn" disabled class="btn btn-success btn-sm mr-3">
                <i class="fas fa-paper-plane"></i> Submit
            </button>
            <button type="button" id="resetFormBtn" class="btn btn-danger btn-sm mr-3">
                <i class="fas fa-undo"></i> Cancel
            </button>
        </div>
        <div class="card-toolbar justify-content-end" v-if="vehicleHeader.isHeaderSaved">
            <button type="button" data-bs-target="#vehicleDisk" data-bs-toggle="modal" class="btn btn-default btn-sm mr-3">
                <i class="fas fa-print"></i> Print Disk
            </button>
        </div>
    </div>

    <!--begin::Card body-->
    <div class="card-body">
        <x-error-view/>
        <form name="vehicleHeaderForm" id="tms_vehicle_header_form"
              class="form"
              action="{{route('new.vehicle.header')}}">
            <input type="hidden" name="doctype" value="VehicleHeader"/>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="registration_type" class="fs-6 fw-semibold form-label mt-3 col-md-3">
                            <span class="required">Registration Type</span>
                        </label>
                        <div class="col-md-9 fv-row">
                            <div class="col-md-9">
                                <div class="w-100 fv-row">
                                    <select class="form-select form-select-sm"
                                            id="registration_type"
                                            name="registration_type"
                                            @input="registrationTypeChanged"
                                            v-model="vehicleHeader.registration_type">
                                        <option v-for="regType in registrationTypes"
                                                :key="regType.code"
                                                :value="regType.code">
                                            @{{ regType.label }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                    <select class="form-control view_mode"
                                            name="brand"
                                            id="brand">
                                        <option>--Select Brand--</option>
                                        <option v-for="brand in vehicleBrands"
                                                :key="brand.id"
                                                :value="brand.id | trimSpaces">
                                            @{{brand.name}}
                                        </option>
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
                        <label for="model_code" class="fs-6 fw-semibold form-label col-md-3">
                            <span class="required">Model Code</span>
                        </label>

                        <div class="col-md-9 fv-row">
                            <div class="col-md-9">
                                <div class="w-100">
                                    {{-- :value="vehicleHeader.model_code"--}}
                                    <input class="form-control form-control-solid"
                                           name="model_code"
                                           readonly
                                           id="model_code"
                                    />
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
                                    <select class="form-select form-select-sm view_mode"
                                            required
                                            id="bodyType"
                                            name="bodyType">
                                    </select>
                                    <input type="hidden"
                                           id="bodyType_holder"
                                           name="bodyType_holder"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="user_unit" class="fs-6 fw-semibold form-label col-md-3">
                            <span class="required">User Unit</span>
                        </label>

                        <div class="col-md-9 fv-row ">
                            <div class="col-sm-12 col-md-12">
                                <div class="control-input-wrapper">
                                    <div class="control-input">
                                        <div class="link-field ui-front" style="position: relative;">
                                            <select class="form-control"
                                                    required
                                                    name="user_unit"
                                                    id="user_unit"
                                                    data-doctype="vehicleHeader"></select>
                                        </div>
                                    </div>
                                    <div class="control-value like-disabled-input bold"
                                         style="display: none;"></div>
                                    <p class="help-box small text-muted"></p>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="registrationNumber" class="fs-6 fw-semibold form-label col-md-3">
                            <span class="required">Registration #.</span>
                        </label>
                        <div class="col-md-9 fv-row">
                            <div class="col-md-9">
                                {{--v-model="vehicleHeader.registration_number"--}}
                                <input type="text"
                                       class="form-control"
                                       name="registrationNumber"
                                       id="registrationNumber"
                                       autocomplete="off"
                                       required
                                />
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="vehicleLocation" class="fs-6 fw-semibold form-label col-md-3">
                            <span class="required">Location</span> <i class="ion ion-location ion-solid mr-1" style="font-size: 16px; color: green;"></i>
                        </label>
                        <div class="col-md-9 fv-row">
                            <div class="col-md-9">
                                {{--v-model="vehicleHeader.location_code"--}}
                                <select
                                    required
                                    class="form-control"
                                    name="vehicleLocation"
                                    autocomplete="off"
                                    id="vehicleLocation">
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                        {{--@if($vehicle && !$empty($vehicle->barcode)) @endif--}}
                        <div class="form-group row mt-10 d-none" id="barcodeContainer">
                            <label for="barcode" class="fs-6 fw-semibold form-label col-md-3">
                                <span class="required">Vehicle Badge</span>
                            </label>
                            <div class="col-md-9 fv-row">
                                <div class="col-md-9">
                                    {{--<img id="barcode" alt="vehicle barcode" src="">--}}
                                    <div id="qrcode"></div>
                                </div>
                            </div>
                        </div>

                </div>
            </div>
        </form>
    </div>
</div>
