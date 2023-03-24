<!--begin::Form-->
<form id="tms_costing_valuation_form"
      v-on:submit.prevent="submitCostValuationDetails"
      class="form fv-plugins-bootstrap5 fv-plugins-framework"
      action="{{route('api.vehicle.new')}}">
    <input type="hidden" name="doctype" value="CostingDetails"/>
    <input type="hidden" name="headerId" v-model="vehicleHeaderId"/>
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
                                <input type="text"
                                       class="input-with-feedback form-control bold"
                                       maxlength="140"
                                       data-fieldtype="Link"
                                       data-fieldname="supplier"
                                       id="supplierName"
                                       name="supplierName"
                                       v-model="costingAndValuation.supplierName"
                                       placeholder=""
                                       data-doctype="CostingDetails"/>
                            </div>

                        </div>
                    </div>
                </div>
            </td>
            <td class="frappe-control">

            </td>
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
                                       class="input-with-feedback form-control bold"
                                       maxlength="15"
                                       data-a-sign="ZMW "
                                       id="costPrice"
                                       name="costPrice"
                                       v-model="costingAndValuation.costPrice"
                                       placeholder=""
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
                                <input type="text"
                                       class="input-with-feedback form-control bold number_input"
                                       maxlength="4"
                                       placeholder=""
                                       v-model="costingAndValuation.yearOfPurchase"
                                       name="yearOfPurchase"
                                       id="yearOfPurchase"
                                       data-doctype="Work Order"
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
                                       class="input-with-feedback form-control bold"
                                       id="bookValue"
                                       data-a-sign="ZMW "
                                       name="bookValue"
                                       v-model="costingAndValuation.bookValue"
                                       placeholder=""
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
                                       class="input-with-feedback form-control bold"
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
                    Cost Of License (Road Tax):
                </label>
            </td>
            <td>
                <div class="control-input-wrapper">
                    <div class="control-input">
                        <div class="link-field ui-front" style="position: relative;">
                            <input type="text"
                                   class="input-with-feedback form-control bold number_input"
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
                                   class="input-with-feedback form-control bold"
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
    <div>
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
