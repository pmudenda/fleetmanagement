@php use Carbon\Carbon; @endphp
<div class="container-fluid">
    <div class="row" data-form-url="{{route("save.job.card")}}" data-model-name="JobCardHeader">
        <div class="col-9">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="container-fluid pl-0">
                        <div class="row">
                            <div class="form-group row">
                                <label
                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 app-field-label field-required"
                                        for="staff_no">Registration #:
                                </label>
                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                    <div class="input-group">
                                        <input type="text"
                                               @if(!empty($details->reg_no)) readonly="readonly" @endif
                                               data-action="{{route('requisition.vehicle.details')}}"
                                               class="form-control form-control-sm"
                                               value=""
                                               id="vehicle_registration"
                                               placeholder="Vehicle Reg e.g AAB 6757"
                                               name="vehicle_registration" required/>
                                        <div class="input-group-addon">
                                            <button type="button"
                                                    id="vehicleSearchBtn"
                                                    name="vehicleSearchBtn"
                                                    class="btn btn-success btn-sm border-radius-0">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <input type="hidden" value="{{$details->job_card_no ?? 0}}" name="job_card_number"/>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="container-fluid pl-0">
                        <div class="row">
                            <div class="form-group row">
                                <label
                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 app-field-label field-required"
                                        for="staff_no">Date In :
                                </label>
                                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           id="date_of_req"
                                           readonly
                                           value="@if($details) {{Carbon::parse($details->date_in)->format('d/m/Y')}} @else {{ date('Y-m-d', strtotime(Carbon::now()))}} @endif"
                                           name="date_of_req"
                                           required>
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
                                        class=" col-xs-12 col-sm-6 col-md-5 col-lg-4 control-input-wrapper">
                                    <div class="control-input">
                                        <div class="link-field ui-front"
                                             style="position: relative;">
                                            <label class="form-check-inline field-required">
                                                Workshop
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                    <select
                                            data-value="{{$details->workshop_code ?? ''}}"
                                            required
                                            class="form-select form-select-sm"
                                            name="workshop"
                                            autocomplete="off"
                                            id="workshop">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="container-fluid pl-0">
                        <div class="row">
                            <div class="form-group row">
                                <label
                                        class="col-xs-12 col-sm-6 col-md-7 col-lg-4"
                                        for="job_card_no">
                                    Time In:
                                </label>
                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                    <input type="text"
                                           readonly
                                           value="@if($details){{Carbon::parse($details->time_in)->format('H:i:s')}}@else{{Carbon::now()->format('H:i:s')}}@endif"
                                           class="form-control form-control-sm when_valid number_input"
                                           id="timeIn"
                                           name="timeIn"
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
                                <label
                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                        for="staff_name">
                                    Repair Type:
                                </label>
                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                    <select name="repairType"
                                            @if(!empty($details->reg_no)) readonly="readonly" @endif
                                            id="repairTypeDropdownList"
                                            data-value="{{$details->repair_type ?? ''}}"
                                            class="form-select form-select-sm when_valid"
                                            required>
                                        <option></option>
                                        @foreach ($repairTypes as $repairType)
                                            @if(!empty($details))
                                                @if($details->repair_type == $repairType->code)
                                                    <option selected
                                                            value="{{$repairType->code}}">{{$repairType->name}}</option>
                                                @else
                                                    <option disabled
                                                            value="{{$repairType->code}}">{{$repairType->name}}</option>
                                                @endif
                                            @else
                                                <option value="{{$repairType->code}}">{{$repairType->name}}</option>
                                            @endif

                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="container-fluid pl-0">
                        <div class="row">
                            <div class="form-group row">
                                <label
                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                        for="staff_name">
                                    Service Advisor:
                                </label>
                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                    @if($details && $details->service_advisor)
                                        <input type="text"
                                               readonly
                                               class="form-control form-control-sm when_valid number_input"
                                               id="service_advisor"
                                               value="{{ $details->service_advisor .' | '. $details->section_in_name}}"
                                               required
                                               name="service_advisor"
                                        />
                                    @else
                                        <input type="text"
                                               readonly
                                               class="form-control form-control-sm when_valid number_input"
                                               id="service_advisor"
                                               value="{{auth()->user()->name . ' | RECEPTION' }}"
                                               required
                                               name="service_advisor"
                                        />
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="accidentRecordNo" class="row d-none">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="container-fluid pl-0">
                        <div class="row">
                            <div class="form-group row">
                                <label
                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                        for="staff_name">
                                    Accident No:
                                </label>
                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                    <input name="accident_number"
                                           id="accident_number"
                                           class="form-control form-control-sm when_valid"
                                           required/>
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
                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                        for="current_odometer">Odometer value:</label>
                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                    <div class="input-group">
                                        <input type="number"
                                               min="1"
                                               class="form-control form-control-sm"
                                               id="current_odometer"
                                               value="{{$details->millage_in ?? ''}}"
                                               name="current_odometer" required/>
                                        <div class="input-group-text">
                                            Km
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="container-fluid pl-0">
                        <div class="row" style="display: none;">
                            <div class="form-group row">
                                <label
                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 app-field-label field-required"
                                        for="staff_no">Date Expected Out:
                                </label>
                                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           id="date_expected_out"
                                           value="@if($details){{date('Y-m-d', strtotime(Carbon::parse($details->date_in)->format('Y-m-d')))}}@else{{date('Y-m-d', strtotime(Carbon::now()))}}@endif"
                                           name="date_of_req"
                                    >
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
                                        class="col-xs-12 col-sm-12 col-md-5 col-lg-4 field-required"
                                        for="fuel_level">
                                    Fuel Level :
                                </label>
                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                    <select name="fuel_level"
                                            data-value="{{$details->fuel_level_in ?? ''}}"
                                            id="fuel_level"
                                            class="form-select form-select-sm when_valid"
                                            required>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="container-fluid pl-0">
                        <div class="row">
                            <div class="form-group row">
                                <label
                                        class="col-xs-12 col-sm-12 col-md-5 col-lg-4"
                                        for="sub_fuel_level">
                                    Sub-Tank Fuel Level :
                                </label>
                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                    <select name="sub_fuel_level"
                                            data-value="{{$details->sub_fuel_level_in ?? ''}}"
                                            id="sub_fuel_level"
                                            class="form-select form-select-sm when_valid">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="container-fluid pl-0">
                        <div class="row">
                            <div class="form-group row">
                                <label
                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                        for="staff_name">
                                    Driver:
                                </label>
                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                    <div class="input-group">
                                        <input type="text"
                                               list="employee_list"
                                               data-action="{{route('driver.search')}}"
                                               class="form-control form-control-sm"
                                               autocapitalize="characters"
                                               id="driver_staff_number"
                                               value="{{$details->driver_in ?? ''}}"
                                               placeholder=""
                                               name="driver_staff_number"/>
                                        <div class="input-group-addon">
                                            <button type="button" id="employeeSearchBtn"
                                                    name="employeeSearchBtn"
                                                    class="btn btn-success btn-sm border-radius-0">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                        <datalist id="employee_list">
                                        </datalist>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="container-fluid pl-0">
                        <div class="row">
                            <div class="form-group row">
                                <div class="col-xs-12 col-sm-12 col-md-10 col-lg-11">
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           id="driver_name"
                                           name="driver_name"
                                           readonly/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div id="vehicleDetailsContainer" style="display: none;"
                 class="col-xs-12 col-sm-12 col-md-12 pl-0">
                <h1>Vehicle Details</h1>
                <table class="table table-striped">
                    <tbody id="vehicleDetails" class="vehicleDetails">
                    </tbody>
                </table>
            </div>

            <div id="image_view" class="card text-center py-5 my-2" style="display: none;">
                <div class="form-group">
                    <div class="imagePreview"></div>
                </div>
            </div>

        </div>
    </div>
</div>
