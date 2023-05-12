<div class="card mb-xl-10">
    <div id="card_header" class="card-header min-h-2px">
        <div class="card-title">
            <h2> Vehicle On-Boarding</h2>
            <span v-if="!isHeaderSaved" class="ml-2 indicator-pill whitespace-nowrap orange"><span>Not Saved</span></span>
            <span v-else class="ml-2 indicator-pill whitespace-nowrap green"><span>@{{ vehicleHeader.on_boarding_status || 'Saved' }}</span></span>
        </div>

        <div id="actionButtonsContainer" class="card-toolbar justify-content-end">
            <button type="button" id="submitBtn" class="btn btn-success btn-sm mr-3">
                <i class="fas fa-paper-plane"></i> Submit
            </button>
            <button type="button" id="resetFormBtn" class="btn btn-danger btn-sm mr-3">
                <i class="fas fa-undo"></i> Cancel
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
                        <label for="brand" class="fs-6 fw-semibold form-label mt-3 col-md-3">
                            <span class="required">Registration Type</span>
                        </label>
                        <div class="col-md-9 fv-row">
                            <div class="col-md-9">
                                <div class="w-100 fv-row">
                                    <select class="form-select form-select-sm"
                                            name="registration_type"
                                            @input="registrationTypeChanged"
                                            v-model="vehicleHeader.vehicle_type">
                                        <option>--Select Type--</option>
                                        <option v-for="regType in registrationTypes" :key="regType.code"
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
                                    <v-select class="vue-select2"
                                              :placeholder="vehicle_brand_placeholder"
                                              :options="vehicleBrands"
                                              @input="vehicleBrandChanged"
                                              label="name"
                                    >
                                    </v-select>
                                    <input type="hidden"
                                           name="brand"
                                           v-model="vehicleHeader.brand_guid"
                                           id="brand"
                                           required/>
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
                                    <v-select class="vue-select2"
                                              required
                                              :placeholder="vehicle_model_placeholder"
                                              :get-option-label="getModelLabel"
                                              :options="selectedBrandModels"
                                              @input="modelChanged"
                                              label="model_name"
                                    >
                                    </v-select>
                                    <input
                                        type="hidden"
                                        name="model"
                                        id="model"
                                        v-model="vehicleHeader.model_guid"
                                        required/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="modelCode" class="fs-6 fw-semibold form-label col-md-3">
                            <span class="required">Model Code</span>
                        </label>

                        <div class="col-md-9 fv-row">
                            <div class="col-md-9">
                                <div class="w-100">
                                    <input class="form-control form-control-solid"
                                           name="model_code"
                                           readonly
                                           id="model_code"
                                           :value="vehicleHeader.model_code"
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
                                    <v-select class="vue-select2"
                                              required
                                              :placeholder="'Pick Body Type'"
                                              :options="bodyTypes"
                                              @input="bodyTypeChanged"
                                              label="body_type_name"
                                    >
                                    </v-select>
                                    <input type="hidden"
                                           id="bodyType"
                                           name="bodyType"
                                           :value="vehicleHeader.body_type_guid"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="userUnit" class="fs-6 fw-semibold form-label col-md-3">
                            <span class="required">User Unit</span>
                        </label>

                        <div class="col-md-9 fv-row ">
                            <div class="col-sm-12 col-md-12">
                                <div class="control-input-wrapper">
                                    <div class="control-input">
                                        <div class="link-field ui-front" style="position: relative;">

                                            <v-select class="" required
                                                      :placeholder="'Select User Unit'"
                                                      :get-option-label="getUserUnitLabel"
                                                      :options="organizationalUnits"
                                                      label="description"
                                                      @input="userUnitChanged"
                                                      data-doctype="vehicleHeader"
                                                      v-model="vehicleHeader.user_unit">
                                            </v-select>
                                            <input type="hidden" class="form-control form-control-solid"
                                                   name="user_unit"
                                                   id="user_unit"
                                                   :value="vehicleHeader.user_unit_code"
                                            />


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
                                       v-on:change="validateRegistrationNumber"
                                       required
                                />
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="vehicleLocation" class="fs-6 fw-semibold form-label col-md-3">
                            <span class="required">Location</span>
                        </label>
                        <div class="col-md-9 fv-row">
                            <div class="col-md-9">
                                <input type="text"
                                       required
                                       class="form-control"
                                       name="vehicleLocation"
                                       autocomplete="off"
                                       id="vehicleLocation"
                                       v-model="vehicleHeader.location_code"
                                       />
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </form>
    </div>
</div>


{{--<div class="card mb-xl-10">
    <div class="card-header border-0 min-h-2px">
        <div class="card-title">
            <h2> New Vehicle Registration</h2>
            <span v-if="!isHeaderSaved" class="ml-2 indicator-pill whitespace-nowrap orange">
                        <span>Not Saved</span>
                    </span>

            <span v-else class="ml-2 indicator-pill whitespace-nowrap green">
                        <span>Saved</span>
                    </span>
        </div>
        <div class="d-flex my-4">

        </div>
    </div>

    <!--begin::Card body-->
    <div class="card-body">
        <form id="tms_vehicle_header_form" v-on:submit.prevent="saveVehicleHeaderInformation"
              class="form fv-plugins-bootstrap5 fv-plugins-framework"
              action="{{route('api.vehicle.new')}}">
            <input type="hidden" name="doctype" value="VehicleHeader"/>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="brand" class="fs-6 fw-semibold form-label mt-3 col-md-3">
                            <span class="required">Registration Type</span>
                        </label>
                        <div class="col-md-9 fv-row">
                            <div class="col-md-9">
                                <div class="w-100 fv-row">
                                    <v-select class=""
                                              :options="registrationTypes"
                                              @change="vehicleTypeChanged"
                                              v-model="vehicleHeader.vehicle_type">
                                    </v-select>
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
                                    <v-select class="vue-select2"
                                              :placeholder="vehicle_brand_placeholder"
                                              :options="vehicleBrands"
                                              @input="vehicleBrandChanged"
                                              label="name"
                                    >
                                    </v-select>
                                    <input type="hidden"
                                           name="brand"
                                           v-model="vehicleHeader.brand_guid"
                                           id="brand"
                                           required/>
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
                                    <v-select class="vue-select2"
                                              required
                                              :placeholder="vehicle_model_placeholder"
                                              :get-option-label="getModelLabel"
                                              :options="selectedBrandModels"
                                              @input="modelChanged"
                                              label="model_name"
                                    >
                                    </v-select>
                                    <input
                                        type="hidden"
                                        name="model"
                                        id="model"
                                        v-model="vehicleHeader.model_guid"
                                        required/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="modelCode" class="fs-6 fw-semibold form-label col-md-3">
                            <span class="required">Model Code</span>
                        </label>

                        <div class="col-md-9 fv-row">
                            <div class="col-md-9">
                                <div class="w-100">
                                    <input class="form-control form-control-solid"
                                           name="model_code"
                                           readonly
                                           id="model_code"
                                           :value="vehicleHeader.model_code"
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
                                    <v-select class="vue-select2"
                                              required
                                              :placeholder="'Pick Body Type'"
                                              :options="bodyTypes"
                                              @input="bodyTypeChanged"
                                              label="body_type_name"
                                    >
                                    </v-select>
                                    <input type="hidden" class="form-control form-control-solid"
                                           id="bodyType"
                                           name="bodyType"
                                           :value="vehicleHeader.body_type_guid"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="userUnit" class="fs-6 fw-semibold form-label col-md-3">
                            <span class="required">User Unit</span>
                        </label>

                        <div class="col-md-9 fv-row ">
                            <div class="col-sm-12 col-md-12">
                                <div class="control-input-wrapper">
                                    <div class="control-input">
                                        <div class="link-field ui-front" style="position: relative;">

                                            <v-select class="" required
                                                      :placeholder="'Select User Unit'"
                                                      :get-option-label="getUserUnitLabel"
                                                      :options="organizationalUnits"
                                                      label="description"
                                                      @input="userUnitChanged"
                                                      data-doctype="vehicleHeader"
                                                      v-model="vehicleHeader.user_unit">
                                            </v-select>
                                            <input type="hidden" class="form-control form-control-solid"
                                                   name="user_unit"
                                                   id="user_unit"
                                                   :value="vehicleHeader.user_unit_code"
                                            />
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
                                <input type="text"
                                       v-model="vehicleHeader.registration_number"
                                       class="form-control form-control-solid"
                                       name="registrationNumber"
                                       onpaste="return false"
                                       id="registrationNumber"
                                       autocomplete="off"
                                       v-on:change="validateRegistrationNumber"
                                       required
                                />
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="vehicleLocation" class="fs-6 fw-semibold form-label col-md-3">
                            <span class="required">Location</span>
                        </label>
                        <div class="col-md-9 fv-row">
                            <div class="col-md-9">
                                <input type="text"
                                       required
                                       class="form-control form-control-solid"
                                       name="vehicleLocation"
                                       id="vehicleLocation"
                                       onpaste="return false;"
                                       v-model="vehicleHeader.location_code"
                                       value=""/>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    --}}{{-- v-show="!isHeaderSaved"--}}{{--
                    <button type="submit"
                            class="btn btn-sm btn-success me-2" id="tms_save_vehicle">
                        <span class="indicator-label">Save</span>
                        <span class="indicator-progress">Please wait...<span
                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>--}}
