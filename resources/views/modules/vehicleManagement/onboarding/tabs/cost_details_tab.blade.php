<!--begin::Form-->
<form id="tms_costing_valuation_form"
      name="tms_costing_valuation_form"
      class="form fv-plugins-bootstrap5 fv-plugins-framework"
      action="{{route('vehicle.cost.detail')}}">
    <input type="hidden" name="doctype" value="CostingDetails"/>
    <input type="hidden" name="headerId" value="{{$reference}}"/>
    <input type="hidden" name="costAndValuationId" value="{{$vehicle->costAndValuationId ?? 0}}"/>

    <x-error-view/>
    <div class="col-8">
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
                               class="form-control form-control-sm view_mode"
                               id="purchase_order_number"
                               placeholder=""
                               name="purchase_order_number">
                        <div class="input-group-addon">
                            <button type="button" id="poSearchBtn"
                                    name="poSearchBtn"
                                    class="btn btn-primary btn-sm border-radius-0 view_mode">
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
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-addon">
                                            <select name="bookValueCurrency" class="form-select form-select-sm"
                                                    style="height: 2.5em; border-radius: 0;">
                                                <option value="001">ZMW</option>
                                                <option value="002">USD</option>
                                            </select>
                                        </div>
                                    </div>
                                    {{--v-model="costingAndValuation.costPrice"onchange="formatCostPriceAsMoney(this)"--}}
                                    <input type="text"
                                           class="input-with-feedback form-control bold view_mode"
                                           maxlength="15"
                                           data-a-sign="ZMW "
                                           id="costPrice"
                                           name="costPrice"
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
                                <div class="input-group">
                                    <input type="number" min="1990" max="{{date('Y')}}" step="1"
                                           class="input-with-feedback form-control bold number_input view_mode"
                                           maxlength="4"
                                           name="yearOfPurchase"
                                           id="yearOfPurchase"
                                           data-doctype="CostingDetails"
                                           autocomplete="off">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fas fa-calender"></i>
                                        </div>
                                    </div>
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
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-addon">
                                            <select name="bookValueCurrency" class="form-select form-select-sm"
                                                    style="height: 2.5em; border-radius: 0;">
                                                <option value="001">ZMW</option>
                                                <option value="002">USD</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="text"
                                           class="input-with-feedback form-control bold view_mode"
                                           id="bookValue"
                                           data-a-sign="ZMW "
                                           name="bookValue"
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
                                           class="input-with-feedback form-control bold view_mode"
                                           maxlength="140"
                                           data-fieldtype="Link"
                                           data-fieldname="company"
                                           id="assetNumber"
                                           name="assetNumber"
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
                    {{--v-model="costingAndValuation.costOfLicense"--}}
                    <div class="control-input-wrapper">
                        <div class="control-input">
                            <div class="link-field ui-front" style="position: relative;">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-addon">
                                            <select name="bookValueCurrency" class="form-select form-select-sm"
                                                    style="height: 2.5em; border-radius: 0;">
                                                <option value="001">ZMW</option>
                                                <option value="002">USD</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="text"
                                           class="input-with-feedback form-control bold number_input view_mode"
                                           id="costOfLicense"
                                           data-a-sign="ZMW"
                                           name="costOfLicense"
                                           placeholder=""
                                           data-target="Company">
                                </div>

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
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-addon">
                                            <select name="bookValueCurrency" class="form-select form-select-sm"
                                                    style="height: 2.5em; border-radius: 0;">
                                                <option value="001">ZMW</option>
                                                <option value="002">USD</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="text"
                                           class="input-with-feedback form-control bold view_mode"
                                           maxlength="140"
                                           id="premium"
                                           name="premium"
                                           placeholder=""/>
                                </div>
                            </div>
                            <small>10% of Cost Price</small>
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
                            <input type="file"
                                   accept="image/*,.pdf"
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
        <table v-if="documents && documents.purchase_order"
               class="table align-middle table-row-dashed dataTable no-footer">
            <thead>
            <tr class="bg-dark">
                <th>Document Type</th>
                <th>File Name</th>
                <th></th>
            </tr>
            </thead>
            <tr>
                <td>Purchase Order</td>
                <td>@{{ documents.purchase_order?.originalDocumentName }}</td>
                <td>
                    <button data-zfm-view-file="insurance"
                            type="button" :data-document-url="'/storage'+documents.purchase_order?.path"
                            class="btn btn-sm btn-success">View File
                    </button>
                </td>
            </tr>
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
    </div>

</form>
<!--end::Form-->
