<!--begin::Form-->
<form id="tms_engine_details_form"
      name="engineDetailsForm"
      class="form"
      action="{{route('vehicle.engine.detail')}}">
    <input type="hidden" name="doctype" value="EngineDetails"/>
    <input type="hidden" name="headerId" value="{{$reference}}"/>
    <input type="hidden" name="engineDetailsId" value="{{$vehicle->engineDetailsId ?? 0}}"/>

    <x-error-view/>
    <fieldset class="border p-3">
        <legend style="width: inherit;">
            <h4 class="pt-2">Engine</h4>
        </legend>
        <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
            <tbody>
            <tr>
                <td class="frappe-control ">
                    <label for="numberOfCylinders" class="control-label reqd"
                           style="padding-right: 0px;">
                        Number Of Cylinders:
                    </label>
                </td>
                <td>
                    <div class="control-input-wrapper">
                        <div class="control-input">
                            <div class="link-field ui-front" style="position: relative;">
                                <div>
                                    {{--v-model="engineDetails.numberOfCylinders"--}}
                                    <input type="number"
                                           max="16"
                                           min="2"
                                           required
                                           id="numberOfCylinders"
                                           name="numberOfCylinders"
                                           class="input-with-feedback form-control bold number_input view_mode"
                                           data-fieldtype="Link"
                                           data-fieldname="company"
                                           data-doctype="EngineDetails"
                                           autocomplete="off"/>

                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="frappe-control">
                    <div class="clearfix">
                        <label for="engineCapacity" class="control-label reqd"
                               style="padding-right: 0px;">Engine Capacity :</label>
                        <span class="help"></span>
                    </div>
                </td>
                <td>
                    <div class="control-input-wrapper">
                        <div class="control-input">
                            <div class="link-field ui-front" style="position: relative;">
                                <div class="input-group bg-gray-300">
                                    {{--v-model="engineDetails.engineCapacity"--}}
                                    <input type="number"
                                           class="input-with-feedback form-control bold number_input view_mode"
                                           max="10000"
                                           required
                                           data-fieldtype="Link"
                                           data-fieldname="company"
                                           id="engineCapacity"
                                           name="engineCapacity"
                                           placeholder=""
                                           data-doctype="EngineDetails"/>
                                    <div
                                        class="input-group-addon align-self-center pl-3 pr-3">
                                        cc
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </td>
            </tr>
            <tr>
                <td class="frappe-control">
                    <div class="clearfix">
                        <label for="actualEnginePower" class="control-label reqd"
                               style="padding-right: 0px;">
                            Engine Horse Power:
                        </label>
                        <span class="help"></span>
                    </div>
                </td>
                <td>
                    <div class="control-input-wrapper">
                        <div class="control-input">
                            <div class="link-field ui-front" style="position: relative;">
                                <div class="input-group bg-gray-300">
                                  {{--v-model="engineDetails.actualEnginePower"--}}
                                    <input type="number"
                                           required
                                           class="input-with-feedback form-control bold number_input view_mode"
                                           maxlength="140"
                                           name="actualEnginePower"
                                           id="actualEnginePower"
                                           placeholder=""
                                           data-doctype="EngineDetails"
                                           autocomplete="off">
                                    <div
                                        class="input-group-append pl-3 pr-3 align-self-center">
                                        hp
                                    </div>
                                </div>

                            </div>
                        </div>
                        <p class="help-box small text-muted"></p>
                    </div>
                </td>

                <td style="display: none;" class="frappe-control ">
                    <label for="claimedEnginePower"
                           class="control-label reqd"
                           style="padding-right: 0px;">
                        Horse Power:
                    </label>
                </td>
                <td style="display: none;">
                    <div class="control-input-wrapper">
                        <div class="control-input">
                            <div class="link-field ui-front" style="position: relative;">
                                <div class="input-group bg-gray-300">
                                    {{--v-model="engineDetails.claimedEnginePower"--}}
                                    <input type="number"
                                           required
                                           class="input-with-feedback form-control bold number_input view_mode"
                                           maxlength="140"
                                           value="0"
                                           data-fieldname="company"
                                           id="claimedEnginePower"
                                           name="claimedEnginePower"
                                           placeholder=""
                                           data-doctype="EngineDetails"
                                           data-target="Company" autocomplete="off"/>
                                    <div
                                        class="input-group-append pl-3 pr-3 align-self-center">
                                        hp
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td class="frappe-control">
                    <div class="clearfix">
                        <label for="fuelTypes" class="control-label reqd"
                               style="padding-right: 0px;">
                            Fuel Type:
                        </label>
                        <span class="help"></span>
                    </div>
                </td>
                <td>
                    <div class="control-input-wrapper">
                        <div class="control-input">
                            <div class="link-field ui-front" style="position: relative;">
                                <div>
                                    {{--v-model="engineDetails.fuelTypes"--}}
                                    <select
                                        required
                                        class="input-with-feedback form-select bold view_mode"
                                        id="fuelTypes"
                                        name="fuelTypes"
                                        data-doctype="EngineDetails">
                                        <option v-for="fuelType in fuelTypes"
                                                :value="fuelType.code_article" :key="fuelType.code_article">
                                            @{{ fuelType.description }}
                                        </option>
                                    </select>
                                </div>

                            </div>
                        </div>

                    </div>
                </td>

                <td class="frappe-control " style="display: none">
                    <label for="engineBrand" class="control-label reqd"
                           style="padding-right: 0px;">
                        Engine Brand:
                    </label>
                </td>
                <td>
                    <div class="control-input-wrapper">
                        <div class="control-input">
                            <div class="link-field ui-front" style="position: relative;">
                                <input type="hidden"
                                       data-fieldtype="Link"
                                       data-fieldname="company"
                                       id="engineBrand"
                                       name="engineBrand"
                                       value="N/A"
                                       data-doctype="EngineDetails"/>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td class="frappe-control ">
                    <label for="engineType"
                           class="control-label reqd"
                           style="padding-right: 0px;">
                        Engine Code:
                    </label>
                </td>
                <td>
                    <div class="control-input-wrapper">
                        <div class="control-input">
                            <div class="link-field ui-front" style="position: relative;">
                                <input
                                    required
                                    class="input-with-feedback form-control bold view_mode"
                                    data-fieldtype="Link"
                                    data-fieldname="company"
                                    placeholder="e.g 1NZ"
                                    id="engineType"
                                    name="engineType"
                                    v-model="engineDetails.engineType"
                                    data-doctype="EngineDetails"/>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <label for="transmission_type"
                           class="control-label reqd"
                           style="padding-right: 0px;">
                        Transmission Type:
                    </label>
                </td>
                <td>
                    <div class="control-input-wrapper">
                        <div class="control-input">
                            <div class="link-field ui-front" style="position: relative;">
                                <select
                                    required
                                    id="transmission_type"
                                    name="transmission_type"
                                    class="form-select form-select-sm view_mode"
                                    v-model="engineDetails.transmissionType"
                                    data-doctype="EngineDetails"
                                    @change="transmissionTypeChanged">
                                    {{--<option value="">--Select Transmission--</option>--}}
                                    <option v-for="transType in transmissionTypes"
                                            :value="transType.code">
                                        @{{ transType.name }}
                                    </option>
                                </select>
                                <input type="hidden" required/>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>


            <tr>
                <td class="frappe-control">
                    <div class="clearfix">
                        <label for="fuelConsumption" class="control-label reqd"
                               style="padding-right: 0px;">
                            Fuel Consumption:
                        </label>
                        <span class="help"></span>
                    </div>
                </td>
                <td>
                    <div title="Number of kilometers per litre"
                         class="control-input-wrapper">
                        <div class="control-input">
                            <div class="link-field ui-front" style="position: relative;">
                                <div class="input-group bg-gray-300">
                                    {{--v-model="engineDetails.fuelConsumption"--}}
                                    <input type="text"
                                           required
                                           class="input-with-feedback form-control bold view_mode"
                                           maxlength="4"
                                           max="25"
                                           name="fuelConsumption"
                                           id="fuelConsumption"
                                           placeholder=""
                                           data-doctype="EngineDetails"
                                           autocomplete="off">
                                    <div
                                        class="input-group-append pl-3 pr-3 align-self-center">
                                        Km/Ltr
                                    </div>
                                </div>

                            </div>
                        </div>
                        <p class="help-box small text-muted"></p>
                    </div>
                </td>

                <td class="frappe-control ">
                    {{--<label for="fuelAllocation"
                           class="control-label reqd"
                           style="padding-right: 0px;">
                        Fuel Allocation:
                    </label>--}}
                </td>
                <td>
                    {{-- <div class="control-input-wrapper">
                         <div class="control-input">
                             <div class="link-field ui-front" style="position: relative;">
                                 <div class="input-group bg-gray-300">
                                     <input type="text"
                                            required
                                            class="input-with-feedback form-control bold"
                                            maxlength="140"
                                            v-model="engineDetails.fuelAllocation"
                                            name="fuelAllocation"
                                            id="fuelAllocation"
                                            placeholder=""
                                            autocomplete="off">
                                     <div
                                         class="input-group-append pl-3 pr-3 align-self-center">
                                         Ltrs-Daily
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>--}}
                </td>
            </tr>

            <tr>
                <td class="frappe-control">
                    <div class="clearfix">
                        <label for="tank_capacity" class="control-label reqd"
                               style="padding-right: 0px;">
                            Main Tank Capacity:
                        </label>
                        <span class="help"></span>
                    </div>
                </td>
                <td>
                    <div class="control-input-wrapper">
                        <div class="control-input">
                            <div class="link-field ui-front" style="position: relative;">
                                <div class="input-group bg-gray-300">
                                    {{--v-model="engineDetails.tank_capacity"--}}
                                    <input type="number"
                                           class="input-with-feedback number_input form-control bold view_mode"
                                           maxlength="4"
                                           required
                                           name="tank_capacity"
                                           id="tank_capacity"
                                           placeholder=""
                                           autocomplete="off">
                                    <div
                                        class="input-group-append pl-3 pr-3 align-self-center">
                                        Litres
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="clearfix">
                        <label for="sub_tank_capacity" class="control-label"
                               style="padding-right: 0px;">
                            Sub Tank Capacity <small>(If Any)</small>:
                        </label>
                        <span class="help"></span>
                    </div>
                </td>
                <td>
                    <div class="control-input-wrapper">
                        <div class="control-input">
                            <div class="link-field ui-front" style="position: relative;">
                                <div class="input-group bg-gray-300">
                                    {{--v-model="engineDetails.sub_tank_capacity"--}}
                                    <input type="number"
                                           maxlength="4"
                                           class="input-with-feedback number_input form-control bold view_mode"
                                           name="sub_tank_capacity"
                                           id="sub_tank_capacity"
                                           placeholder=""
                                           autocomplete="off">
                                    <div
                                        class="input-group-append pl-3 pr-3 align-self-center">
                                        Litres
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </td>
            </tr>

            </tbody>
        </table>
    </fieldset>

    <fieldset class="border p-3">
        <legend style="width: inherit;">
            <h4 class="pt-2">Tyres</h4>
        </legend>
        <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
            <tbody>
            <tr>
                <td class="frappe-control ">
                    <label for="numberOfTyres" class="control-label reqd"
                           style="padding-right: 0px;">
                        Total Number:
                    </label>
                </td>
                <td>
                    <div class="control-input-wrapper">
                        <div class="control-input">
                            <div class="link-field ui-front" style="position: relative;">
                                <div class="fv-row">
                                    {{--v-model="otherDetails.numberOfTyres"--}}
                                    <input type="number"
                                           title="The number of tyres the vehicle has"
                                           id="numberOfTyres"
                                           name="numberOfTyres"
                                           class="input-with-feedback form-control bold number_input view_mode"
                                           maxlength="140"
                                           placeholder=""
                                           data-doctype="EngineDetails"
                                           autocomplete="off"/>

                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="frappe-control">
                    <div class="clearfix">
                        <div class="clearfix">
                            <label for="tyreBrand" class="control-label reqd"
                                   style="padding-right: 0px;">Brand :</label>
                            <span class="help"></span>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="control-input-wrapper">
                        <div class="control-input">
                            <div class="link-field ui-front" style="position: relative;">
                               {{-- v-model="otherDetails.tyreBrand"--}}
                                <input type="text"
                                       title="The tyre make e.g Good Year"
                                       class="form-control view_mode"
                                       maxlength="140"
                                       id="tyreBrand"
                                       name="tyreBrand"
                                       placeholder="e.g Good Year"/>
                            </div>
                        </div>

                    </div>
                </td>
            </tr>
            <tr>
                <td class="frappe-control ">
                    <div class="clearfix">
                        <label for="frontTyreSize" class="control-label reqd"
                               style="padding-right: 0px;">
                            Front Tyre Size:
                        </label>
                    </div>
                </td>
                <td>
                    <div class="control-input-wrapper">
                        <div class="control-input">
                            <div class="link-field ui-front" style="position: relative;">
                                <div>
                                    <select type="text"
                                            class="input-with-feedback form-select bold tyre-size view_mode"
                                            required
                                            id="frontTyreSize"
                                            name="frontTyreSize"
                                            autocomplete="off"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="frappe-control">
                    <div class="clearfix">
                        <label for="rearTyreSize" class="control-label reqd"
                               style="padding-right: 0px;">
                            Rear Tyre Size:
                        </label>
                        <span class="help"></span>
                    </div>
                </td>
                <td>
                    <div class="control-input-wrapper">
                        <div class="control-input">
                            <div class="link-field ui-front" style="position: relative;">
                                <div>
                                    <select type="text"
                                            class="input-with-feedback form-control bold tyre-size view_mode"
                                            name="rearTyreSize"
                                            id="rearTyreSize"
                                            data-doctype="Work Order"
                                            autocomplete="off"></select>
                                </div>

                            </div>
                        </div>
                        <p class="help-box small text-muted"></p>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </fieldset>

    <fieldset class="border p-3">
        <legend style="width: inherit;">
            <h4 class="pt-2">Battery</h4>
        </legend>
        <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
            <tbody>
            <tr>
                <td class="frappe-control ">
                    <label for="batteryBrand" class="control-label reqd"
                           style="padding-right: 0px;">
                        Brand:
                    </label>
                </td>
                <td style="width: 25%;">
                    <div class="control-input-wrapper">
                        <div class="control-input">
                            <div class="link-field ui-front" style="position: relative;">
                                <div>
                                    {{--v-model="otherDetails.batteryBrand"--}}
                                    <input type="text"
                                           id="batteryBrand"
                                           name="batteryBrand"
                                           class="input-with-feedback form-control bold view_mode"
                                           data-fieldtype="Link"
                                           data-fieldname="company"
                                           data-doctype="OtherDetails"
                                           autocomplete="off"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="frappe-control">
                    <div class="clearfix">
                        <label for="batterySize" class="control-label reqd"
                               style="padding-right: 0px;">Size :</label>
                        <span class="help"></span>
                    </div>
                </td>
                <td style="width: 25%;">
                    <div class="control-input-wrapper">
                        <div class="control-input">
                            <div class="link-field ui-front" style="position: relative;">
                                <div>
                                    {{--v-model="otherDetails.batterySize"--}}
                                    <select class="form-select input-with-feedback form-control  view_mode"
                                            data-fieldtype="Link"
                                            data-fieldname="company"
                                            id="batterySize"
                                            name="batterySize"
                                            data-doctype="OtherDetails"></select>
                                </div>

                            </div>
                        </div>

                    </div>
                </td>
            </tr>
            <tr>
                <td class="frappe-control ">
                    <label for="batteryPower" class="control-label reqd"
                           style="padding-right: 0px;">
                        Power:
                    </label>
                </td>
                <td>
                    {{--v-model="otherDetails.batteryPower"--}}
                    <div class="control-input-wrapper">
                        <div class="control-input">
                            <div class="link-field ui-front" style="position: relative;">
                                <div class="input-group bg-gray-300">
                                    <select type="number"
                                            class="form-select view_mode"
                                            data-fieldtype="Link"
                                            data-fieldname="company"
                                            id="batteryPower"
                                            name="batteryPower"
                                            data-target="Company">
                                        <option value="12">12</option>
                                        <option value="24">24</option>
                                    </select>
                                    <div
                                        class="input-group-addon align-self-center pr-3 pl-3">
                                        V
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>

                <td class="frappe-control"></td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </fieldset>

    <div class="mt-5 create_mode">
        <button type="submit" id="tms_save_engine" class="btn btn-success btn-sm">
            <i class="fas fa-paper-plane"></i> Save
        </button>
    </div>
</form>
<!--end::Form-->
