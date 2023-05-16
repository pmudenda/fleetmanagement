<!--begin::Form-->
<form id="tms_costing_valuation_form"
      name="tms_costing_valuation_form"
      class="form fv-plugins-bootstrap5 fv-plugins-framework"
      action="{{route('vehicle.cost.detail')}}">
    <input type="hidden" name="doctype" value="CostingDetails"/>
    <input type="hidden" name="headerId" value="{{$reference}}"/>
    <input type="hidden" name="costAndValuationId" value="{{$vehicle->costAndValuationId ?? 0}}"/>

    <x-error-view />
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-5">
                <div class="container-fluid pl-0">
                 {{--   <div class="row">
                        <div class="form-group row pl-0">

                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">

                            </div>
                        </div>
                    </div>--}}

                    {{--<div class="row">
                        <div class="form-group row">
                            <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                   for="staff_name">
                                Name:
                            </label>
                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                <input type="text" class="form-control form-control-sm"
                                       id="nane"
                                       name="name"
                                       required readonly>
                            </div>
                        </div>
                    </div>--}}
                </div>
            </div>

            {{--<div class="col-xs-12 col-sm-6 col-md-5">
                <div class="container-fluid pl-0">
                    <div class="row">
                        <div class="form-group row">
                            <label class="col-xs-12 col-sm-6 col-md-5 col-lg-3 field-required"
                                   for="staff_email"> Last Name:
                            </label>
                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                <input type="text" class="form-control form-control-sm"
                                       id="last_name"
                                       name="last_name"
                                       required readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>--}}

        </div>
    </div>
    <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
        <tbody>
        <tr>
            <td class="frappe-control ">
                <label class="app-field-label reqd"
                       for="staff_no">Purchase Order Number :
                </label>
            </td>
            <td>
                <div class="input-group">
                    <input type="text"
                           data-action="{{route('verify.purchase.order')}}"
                           class="form-control form-control-sm"
                           id="purchase_order_number"
                           placeholder=""
                           name="purchase_order_number">
                    <div class="input-group-addon">
                        <button type="button" id="poSearchBtn"
                                name="poSearchBtn"
                                class="btn btn-primary btn-sm border-radius-0">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </td>
            <td class="frappe-control"></td>
            <td></td>
        </tr>
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
                                {{--v-model="costingAndValuation.supplierName"--}}
                                <select class="form-select form-control-sm view_mode"
                                        data-doctype="CostingDetails"
                                        data-value=""
                                        id="supplierName"
                                        name="supplierName">
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
                    Cost Of License (Road Tax):
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
        <tr>
            <td class="frappe-control ">
                <label for="premium" class="control-label"
                       style="padding-right: 0px;">
                    Purchase Order:
                </label>
            </td>
            <td>
                <div class="col-md-7 fv-row pl-0">
                    <div class="col-md-9 pl-0">
                        <input type="file" accept="image/*,.pdf"
                               class="filer_input"
                               name="purchaseOrderDocument"/>
                    </div>
                </div>
            </td>
            <td></td>
            <td></td>
        </tr>
        </tbody>
    </table>
    <div class="create_mode">
        <button type="submit" id="tms_save_costing"
                class="btn btn-success btn-sm">
            <i class="fas fa-paper-plane"></i>
            <span class="indicator-label">
                Save
            </span>
            <span class="indicator-progress">
                Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>
</form>
<!--end::Form-->
