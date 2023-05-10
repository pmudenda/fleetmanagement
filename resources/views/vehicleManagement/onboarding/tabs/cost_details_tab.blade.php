<!--begin::Form-->
<form id="tms_costing_valuation_form"
      name="tms_costing_valuation_form"
      class="form fv-plugins-bootstrap5 fv-plugins-framework"
      action="{{route('vehicle.cost.detail')}}">
    <input type="hidden" name="doctype" value="CostingDetails"/>
    <input type="hidden" name="headerId" value="{{$reference}}"/>
    <input type="hidden" name="costAndValuationId" value="{{$vehicle->costAndValuationId ?? 0}}"/>

    <x-error-view />
    <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
        <tbody>
        <tr>
            <td class="frappe-control ">
                <label for="supplierName" class="control-label reqd"
                       style="padding-right: 0px;">
                    Supplier Name:
                </label>
            </td>
            <td colspan="1">
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <div>
                                <select class="form-select form-control-sm view_mode"
                                        data-doctype="CostingDetails"
                                        v-model="costingAndValuation.supplierName"
                                        id="supplierName"
                                        name="supplierName">
                                    <option value>--Supplier--</option>
                                    <option v-for="supplier in supplierList"
                                            :key="supplier.code"
                                            :value="supplier.code">
                                        @{{ supplier.name }}
                                    </option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
            </td>
            <td class="frappe-control"></td>
            <td></td>
        </tr>

        <tr>
            <td class="frappe-control ">
                <label for="costPrice" class="control-label reqd"
                       style="padding-right: 0px;">
                    Cost Price:
                </label>
            </td>
            <td>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <div>
                                <input type="text"
                                       class="input-with-feedback form-control bold view_mode"
                                       maxlength="15"
                                       data-a-sign="ZMW "
                                       id="costPrice"
                                       name="costPrice"
                                       v-model="costingAndValuation.costPrice"
                                       placeholder=""
                                       v-on:change="formatCostPriceAsMoney($event)"
                                       autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            <td class="frappe-control">
                <div class="clearfix">
                    <label for="yearOfPurchase" class="control-label reqd"
                           style="padding-right: 0px;">
                        Year Purchased:
                    </label>
                    <span class="help"></span>
                </div>
            </td>
            <td>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <div>
                                <input type="number" min="1990" max="{{date('Y')}}" step="1"
                                       class="input-with-feedback form-control bold number_input view_mode"
                                       maxlength="4"
                                       v-model="costingAndValuation.yearOfPurchase"
                                       name="yearOfPurchase"
                                       id="yearOfPurchase"
                                       data-doctype="CostingDetails"
                                       autocomplete="off">
                            </div>

                        </div>
                    </div>
                    <p class="help-box small text-muted"></p>
                </div>
            </td>
        </tr>

        <tr>
            <td class="frappe-control ">
                <label for="bookValue" class="control-label reqd"
                       style="padding-right: 0px;">
                    Book Value:
                </label>
            </td>
            <td>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <div>
                                <input type="text"
                                       class="input-with-feedback form-control bold view_mode"
                                       id="bookValue"
                                       data-a-sign="ZMW "
                                       name="bookValue"
                                       v-model="costingAndValuation.bookValue"
                                       placeholder=""
                                       v-on:change="formatBookValueAsMoney($event)"
                                       data-doctype="CostingDetails"
                                       autocomplete="off"/>

                            </div>
                        </div>
                    </div>
                </div>
            </td>
            <td class="frappe-control">
                <div class="clearfix">
                    <label for="assetNumber" class="control-label reqd"
                           style="padding-right: 0px;">
                        Asset No. :
                    </label>
                    <span class="help"></span>
                </div>
            </td>
            <td>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <div>
                                <input type="text"
                                       class="input-with-feedback form-control bold view_mode"
                                       maxlength="140"
                                       data-fieldtype="Link"
                                       data-fieldname="company"
                                       id="assetNumber"
                                       name="assetNumber"
                                       v-model="costingAndValuation.assetNumber"
                                       placeholder=""/>
                            </div>

                        </div>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td class="frappe-control ">
                <label for="costOfLicense" class="control-label reqd"
                       style="padding-right: 0px;">
                    Cost Of License (Road Tax + Fitness):
                </label>
            </td>
            <td>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <input type="text"
                                   class="input-with-feedback form-control bold number_input view_mode"
                                   id="costOfLicense"
                                   data-a-sign="ZMW"
                                   name="costOfLicense"
                                   v-model="costingAndValuation.costOfLicense"
                                   placeholder=""
                                   data-target="Company">
                        </div>
                    </div>
                </div>
            </td>

            <td class="frappe-control ">
                <label for="premium" class="control-label reqd"
                       style="padding-right: 0px;">
                    Insurance Premium:
                </label>
            </td>
            <td>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <input type="text"
                                   class="input-with-feedback form-control bold view_mode"
                                   maxlength="140"
                                   id="premium"
                                   name="premium"
                                   v-model="costingAndValuation.premium"
                                   placeholder=""/>
                        </div>
                    </div>
                </div>
            </td>

        </tr>
        </tbody>
    </table>
    <div class="create_mode">
        <button type="submit" id="tms_save_costing" class="btn btn-success btn-sm">
            <i class="fas fa-paper-plane"></i>
            <span class="indicator-label">Save</span>
            <span class="indicator-progress">Please wait...<span
                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
        </button>
    </div>
</form>
<!--end::Form-->
