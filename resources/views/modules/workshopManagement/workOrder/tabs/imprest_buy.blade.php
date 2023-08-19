@php
    use App\Enums\RequisitionItemTypes;use Carbon\Carbon;use Illuminate\Support\Facades\Auth;
@endphp
{{--<section class="content">
    <div class="card"></div>
</section>--}}
<form id="create_form"
      name="pettyCash"
      action="{{route('petty.cash.store')}}"
      method="post"
      enctype="multipart/form-data">
    @csrf
    {{-- <div class="card-body"></div>
     <div class="card-footer mb-4"></div>--}}
    <table border="1" width="100%" cellspacing="0" cellpadding="0" align="Centre"
           class="mt-2 mb-4">
        <thead>
        <tr>
            <th width="33%" colspan="1"
                class="text-center">
                <a href="#">
                    <img src="{{ asset('assets/dist/img/zesco1.png')}}"
                         title="ZESCO" alt="ZESCO"
                         width="25%"/>
                </a>
            </th>
            <th width="33%" colspan="4" class="text-center">Petty Cash Voucher</th>
            <th width="34%" colspan="1" class="p-3">Doc Number:<br>CO.14900.FORM.00165<br>Version: 3
            </th>
        </tr>
        </thead>
    </table>

    <div class="row">
        <div class="row mt-2 mb-4">
            <div class="col-lg-3 col-sm-12">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-sm-12">
                        <label class="field-required">Date:</label>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-sm-12">
                        <input value="{{ date('Y-m-d H:i:s') }}"
                               type="text" name="date"
                               readonly class="form-control"></div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-12">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 ">
                        <label class="field-required">Cost Center:</label></div>
                    <div class="col-lg-12 col-sm-12 col-sm-12">
                        <input type="text" name="cost_center"
                               class="form-control"
                               value="{{Auth::user()->cc_code}}"
                               readonly
                               required>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-12">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-sm-12"><label>ZQMS No (Optional):</label>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-sm-12">
                        <input type="text" name="imprestZQMSReference"
                               placeholder="Enter Your ZQMS Number (optional)"
                               class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-12">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-sm-12"><label>Project Number (Optional):</label></div>
                    <div class="col-lg-12 col-sm-12 col-sm-12">
                        <input name="imprestProjectNumber" value="{{$details->job_card_no}}" class="form-control"/>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <div class="row mt-10">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="container-fluid pl-0">
                    <div class="row">
                        <input type="hidden" value="{{$materialsHeader->id ?? 0 }}" name="materialHeaderId">
                        <div class="form-group row">
                            <label class="col-xs-12 col-sm-6 col-md-5 col-lg-4 app-field-label field-required"
                                   for="pettyCashBuyItemType">
                                Item Type:
                            </label>
                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                @if(!empty($materialsHeader))
                                    <select
                                            data-value="{{$materialsHeader->item_type_code ?? ''}}"
                                            readonly="readonly"
                                            class="form-select form-select-sm"
                                            name="pettyCashBuyItemType"
                                            id="pettyCashBuyItemType">
                                        <option></option>
                                        <option
                                                @if($materialsHeader->item_type_code == RequisitionItemTypes::StockItemCode) selected
                                                @endif value="01">STOCK ITEM
                                        </option>
                                        <option
                                                @if($materialsHeader->item_type_code == RequisitionItemTypes::NonStockItemCode) selected
                                                @endif value="02">NON STOCK ITEM
                                        </option>
                                        <option
                                                @if($materialsHeader->item_type_code ==  RequisitionItemTypes::ServiceItemCode) selected
                                                @endif value="03">SERVICE
                                        </option>
                                    </select>
                                @else
                                    <select
                                            required
                                            class="form-select form-select-sm"
                                            name="pettyCashBuyItemType"
                                            id="pettyCashBuyItemType">
                                        <option></option>
                                        <option value="{{RequisitionItemTypes::StockItemCode}}">STOCK
                                            ITEM
                                        </option>
                                        <option value="{{RequisitionItemTypes::NonStockItemCode}}">NON
                                            STOCK ITEM
                                        </option>
                                        <option value="{{RequisitionItemTypes::ServiceItemCode}}">
                                            SERVICE
                                        </option>
                                    </select>
                                @endif

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
                                    for="imprestPurchaseOffice">
                                Purchase Office:
                            </label>
                            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                <select
                                        data-value=""
                                        required
                                        class="form-select form-select-sm"
                                        name="imprestPurchaseOffice"
                                        id="imprestPurchaseOffice">
                                    <option value="{{$officeDetails->purchase_office_code ?? ''}}">
                                        {{$officeDetails->purchase_office ?? ''}}
                                    </option>
                                </select>
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
                                        <label for="imprestWorkshopCode" class="form-check-inline field-required">
                                            Workshop:
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                <input type="text"
                                       readonly
                                       value="{{$officeDetails->workshop_name ?? 0}}"
                                       class="form-control form-control-sm"/>
                                <input type="hidden"
                                       name="imprestWorkshopCode"
                                       value="{{$officeDetails->workshop_code ?? 0}}"/>
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
                                Request Date:
                            </label>
                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                @if($details)
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           id="imprestRequestDate"
                                           readonly
                                           value="{{Carbon::parse($details->date_in)->format('d/m/Y')}}"
                                           name="imprestRequestDate"
                                           required>
                                @else
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           id="imprestRequestDate"
                                           readonly
                                           value="{{Carbon::parse(Carbon::now())->format('d/m/Y')}}"
                                           name="imprestRequestDate"
                                           required>
                                @endif

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

                        <div id="pettyCashSupplierContainer" style="display: none;" class="form-group row">
                            <div
                                    class=" col-xs-12 col-sm-6 col-md-5 col-lg-4 control-input-wrapper">
                                <div class="control-input">
                                    <div class="link-field ui-front"
                                         style="position: relative;">
                                        <label class="form-check-inline field-required">
                                            Suppliers
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                <select
                                        data-value="{{$materialsHeader->supplier_code ?? ''}}"
                                        class="form-select form-select-sm"
                                        name="imprestBuySupplier"
                                        autocomplete="off"
                                        id="imprestBuySupplier">
                                </select>
                            </div>
                        </div>

                        <div id="pettyCashStoreContainer" style="display: none;" class="form-group row">
                            <label
                                    class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                    for="staff_name">
                                Store:
                            </label>
                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                <input type="hidden"
                                       id="pettyCashStoreCode"
                                       value="{{$officeDetails->store_code ?? ''}}"
                                       name="pettyCashStoreCode"/>
                                <input type="text"
                                       class="form-control form-control-sm"
                                       readonly
                                       id="pettyCashStoreName"
                                       value="{{$officeDetails->store_code ?? ''}}:{{$officeDetails->store_name ?? ''}}"
                                       placeholder=""
                                       name="pettyCashStoreName"/>
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
                                    for="staff_no">
                                Collection Date:
                            </label>
                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                @if($materialsHeader)
                                    <input type="date"
                                           class="form-control form-control-sm"
                                           id="imprest_date_expected"
                                           min="{{date('Y-m-d', strtotime(Carbon::now()))}}"
                                           value="{{date('Y-m-d', strtotime(Carbon::parse($materialsHeader->collection_date)->format('Y-m-d')))}}"
                                           name="imprets_date_expected"
                                    />

                                @else
                                    <input type="date"
                                           class="form-control form-control-sm"
                                           id="imprest_date_expected"
                                           min="{{date('Y-m-d', strtotime(Carbon::now()))}}"
                                           value="{{date('Y-m-d', strtotime(Carbon::now()->addDays(7)))}}"
                                           name="imprest_date_expected"
                                    />
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr style="color: orange;"/>

    <div class="grid-margin stretch-card">
        <div class="table-responsive">
            <div class="row">
                <table class="table bg-green">
                    <thead>
                    <tr class="bg-success-subtle">
                        <th style="width: 6%;" class="pl-2">Reg. No</th>
                        <th style="width: 25%;">Article</th>
                        <th>Article Code</th>
                        <th style="width: 25%;">Tech. Specification</th>
                        <th style="width: 4%; max-width: 4%;">Qty.</th>
                        <th>UOM</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                    </thead>
                    {{--<tr>
                        <th></th>
                        <th>DETAILS OF PAYMENT</th>
                        <th>AMOUNT</th>
                    </tr>
                    </thead>--}}
                </table>
            </div>
            <div class="col-lg-12  col-sm-12 ">
                <div class="row">
                    <table id="dataTable" class="table table-striped pettyCashItemsTable">
                        <tr>
                            <td>
                                <input type="checkbox" name="chk"/>
                            </td>
                            <td class="showNumber">
                                <input
                                        readonly
                                        name="registration"
                                        required
                                        value="{{$details->reg_no ?? ''}}"
                                        class="form-control form-control-sm registration"/>
                            </td>
                            <td>
                                <select readonly
                                        name="articles"
                                        required
                                        data-text="{{$material->material_code ?? ''}} : {{$material->specifications ?? ''}}"
                                        data-value="{{$material->material_code ?? ''}}"
                                        class="form-control form-control-sm DropDownList">
                                    <option
                                            value="{{$material->material_code ?? ''}}">{{$material->material_code ?? ''}}
                                        : {{$material->specifications ?? ''}}</option>
                                </select>
                            </td>
                            <td>
                                <input
                                        name="articleCode"
                                        value="{{$material->material_code ?? ''}}"
                                        required
                                        readonly
                                        class="form-control form-control-sm articleCode"/>
                            </td>
                            <td>
                                <textarea rows="4"
                                          type="text"
                                          name="name[]"
                                          class="form-control amount"
                                          placeholder="Item Details / Description"
                                          id="name"
                                          required></textarea>
                            </td>
                            <td>
                                <input type="number"
                                       step="any"
                                       id="amount"
                                       name="amount[]"
                                       onchange="getvalues()"
                                       class="form-control amount"
                                       placeholder="Amount [ZMW]">
                            </td>
                            <td>
                                <button type="button"
                                        class="btn btn-danger btn-sm deleteTaleRow"
                                        value="Delete Row"
                                        data-table-id="dataTable"
                                ><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-lg-12 col-sm-12 mb-3 ">
                <button type="button"
                        class="btn btn-success btn-sm addItemRow"
                        data-table-id="dataTable">
                    <i class="fas fa-plus"></i> Add Row
                </button>
            </div>

            <div class="col-lg-6 col-sm-12 mb-3 ">
                <div class="row">
                    <div class="col-lg-3  col-sm-12">
                        <label class="form-control-label">TOTAL PAYMENT </label>
                    </div>
                    <div class="col-lg-9 col-sm-12">
                        <input type="text"
                               class="form-control text-bold"
                               readonly
                               id="total-payment"
                               name="total_payment"
                               value="">
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-sm-12 mb-4">
                <div class="row">
                    <div class="col-lg-2 col-sm-12 ">
                        <label class="form-control-label">Attach Quotation Files (optional)</label>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="input-group">
                            <input type="file" class="form-control" multiple name="quotation[]" id="receipt"
                                   title="Upload Quotation Files (Optional)">
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        </div>
    </div>


    <div class="row mb-1 mt-4">
        <div class="col-lg-2 col-sm-12">
            <label>Name of Claimant:</label>
        </div>
        <div class="col-lg-3 col-sm-12">
            <input type="text" name="claimant_name" class="form-control"
                   value="{{Auth::user()->name}}" readonly required></div>

        <div class="col-lg-2 col-sm-12 text-left">
            <label>Signature:</label>
        </div>
        <div class="col-lg-1 col-sm-12">
            <input type="text"
                   name="sig_of_claimant"
                   class="form-control"
                   value="{{Auth::user()->staff_no}}"
                   readonly
                   required/>
        </div>
        <div class="col-lg-2 col-sm-12 text-left"><label>Date:</label></div>

        <div class="col-lg-2 col-sm-12">
            <input type="Date"
                   name="date_claimant"
                   class="form-control"
                   value="{{date('Y-m-d')}}"
                   readonly
                   required/>
        </div>
    </div>

    <div class="row mb-1">
        <div class="col-lg-2 col-sm-12">
            <label>Claim Authorised by:</label>
        </div>
        <div class="col-lg-3 col-sm-12">
            <input type="text"
                   name="claim_authorised_by"
                   readonly
                   class="form-control"
            />
        </div>
        <div class="col-lg-2 col-sm-12">
            <label>Signature:</label>
        </div>
        <div class="col-lg-1 col-sm-12">
            <input type="text"
                   name="sig_of_authorised"
                   readonly
                   class="form-control"/>
        </div>
        <div class="col-lg-2 col-sm-12">
            <label>Date:</label>
        </div>
        <div class="col-lg-2 col-sm-12">
            <input type="text"
                   name="authorised_date"
                   readonly
                   class="form-control"/>
        </div>
    </div>
    <div class="row mb-1">
        <div class="col-lg-2 col-sm-12">
            <label>HR/Station Manager:</label>
        </div>
        <div class="col-lg-3 col-sm-12">
            <input type="text"
                   name="station_manager"
                   readonly
                   class="form-control"/>
        </div>
        <div class="col-lg-2 col-sm-12 "><label>Signature:</label></div>
        <div class="col-lg-1 col-sm-12"><input type="text" name="sig_of_station_manager" readonly
                                               class="form-control"></div>
        <div class="col-lg-2 col-sm-12 "><label>Date:</label></div>
        <div class="col-lg-2 col-sm-12"><input type="text" name="manager_date" readonly
                                               class="form-control"></div>
    </div>
    <div class="row mb-4">
        <div class="col-lg-2 col-sm-12"><label>Accountant:</label></div>
        <div class="col-lg-3 col-sm-12"><input type="text" name="accountant" readonly class="form-control">
        </div>
        <div class="col-lg-2 col-sm-12 "><label>Signature:</label></div>
        <div class="col-lg-1 col-sm-12"><input type="text" name="sig_of_accountant" readonly
                                               class="form-control">
        </div>
        <div class="col-lg-2 col-sm-12 "><label>Date:</label></div>
        <div class="col-lg-2 col-sm-12"><input type="text" name="accountant_date" readonly
                                               class="form-control">
        </div>
    </div>
    <p><b>Note:</b> The system reference number is mandatory and is from
        any of the systems at ZESCO such as a work request number from PEMS, Task
        number from HQMS, Meeting Number from HQMS, Incident number from IMS etc.
        giving rise to the expenditure
    </p>
    <div class="row">
        <div id="submit_not_possible" class="col-lg-12 col-sm-12 text-center">
            <div class="alert alert-danger ">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    &times;
                </button>
                <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                Sorry, You can not submit <strong>petty cash above K2000</strong>
            </div>
        </div>
        <div id="submit_possible" class="col-lg-12 col-sm-12 text-center">
            <div id="divSubmit_show">
                <input class="btn btn-lg btn-success" type="submit"
                       value="submit" id="btnSubmit"
                       name="submit_form">
            </div>
            <div id="divSubmit_hide">
                <input class="btn btn-lg btn-success"
                       value="Submitting. Please wait..."
                       disabled
                       name="submit_form">
            </div>
        </div>
    </div>

</form>


