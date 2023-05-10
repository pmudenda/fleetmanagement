{{dd($vehicle->first())}}
<div class="card mb-xl-10">
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
        <div id="actionButtonsContainer" class="card-toolbar justify-content-end create_mode">
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
        <form id="tms_vehicle_header_form"
              class="form fv-plugins-bootstrap5 fv-plugins-framework"
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
                                    <select class="form-select form-control-sm view_mode"
                                            name="registration_type"
                                            v-on:change="vehicleTypeChanged"
                                            v-model="vehicleHeader.vehicle_type">
                                        <option>--Registration Type--</option>
                                        <option v-for="registrationType in registrationTypes"
                                                :key="registrationType.code"
                                                :value="registrationType.code">
                                            @{{ registrationType.label }}
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
                                    <select class="form-select form-select-sm view_mode"
                                            :placeholder="vehicle_brand_placeholder"
                                            v-on:change="vehicleBrandChanged"
                                            name="brand"
                                            id="brand"
                                            v-model="vehicleHeader.brand_guid">
                                        <option>--Select Brand--</option>
                                        <option v-for="brand in vehicleBrands"
                                                :key="brand.id"
                                                :value="brand.id | trimSpaces"
                                        >@{{brand.name}}
                                        </option>
                                    </select>
                                    <input type="hidden" required/>
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
                                    <select class="form-select form-select-sm d-none view_mode"
                                            required
                                            :placeholder="vehicle_model_placeholder"
                                            :get-option-label="getModelLabel"
                                            @change="modelChanged"
                                            name="model"
                                            id="model"
                                            v-model="vehicleHeader.model_guid">
                                        <option v-for="model in selectedBrandModels"
                                                :value="model.id | trimSpaces">
                                            @{{model.model_name}}
                                        </option>
                                    </select>
                                    <input class="form-control view_mode"
                                           id="model_holder"
                                           value="{{$vehicle->model_name ?? ''}}"
                                           type="text"
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
                                    <input class="form-control form-control-solid view_mode"
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
                                    <select class="form-select form-select-sm view_mode"
                                            required
                                            :placeholder="'Pick Body Type'"
                                            @input="bodyTypeChanged"
                                            label="body_type_name"
                                            :value="vehicleHeader.body_type_guid"
                                            id="bodyType"
                                            name="bodyType">
                                        <option v-for="bodyType in bodyTypes" :value="bodyType.id">
                                            @{{bodyType.name}}
                                        </option>
                                    </select>
                                    <input type="hidden" class="form-control form-control-solid"/>
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
                                            <select class="form-select form-select-2 view_mode"
                                                    required
                                                    name="user_unit"
                                                    id="user_unit"
                                                    onchange="userUnitChanged(this)"
                                                    data-doctype="vehicleHeader"
                                                    v-model="vehicleHeader.user_unit">
                                                <option>--Select User Unit--</option>
                                                <option v-for="ogUnit in organizationalUnits"
                                                        :value="ogUnit.code_unit" :key="ogUnit.code_unit">
                                                    @{{ ogUnit.description }}
                                                </option>
                                            </select>
                                            <input type="hidden" class="form-control form-control-solid"/>
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
                                       class="form-control view_mode"
                                       name="registrationNumber"
                                       value="{{$vehicle->registration_number ?? ''}}"
                                       onpaste="return false"
                                       readonly
                                       id="registrationNumber"
                                       autocomplete="off"
                                       v-on:change="validateRegistrationNumber"
                                       required
                                />
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
                                       class="form-control view_mode"
                                       name="vehicleLocation"
                                       id="vehicleLocation"
                                       value="{{$vehicle->location_name ?? ''}}"
                                       onpaste="return false;"/>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </form>
    </div>
</div>
