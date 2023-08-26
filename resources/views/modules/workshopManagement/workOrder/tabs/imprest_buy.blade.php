@php
    use App\Enums\RequisitionItemTypes;use Carbon\Carbon;use Illuminate\Support\Facades\Auth;
@endphp
<div class="row pt-5">
    <div class="table-responsive">
        <table id="pettyCashSelectedItemsTable"
               class="table">
            <thead>
            <tr class="bg-green">
                <th style="width: 6%;" class="pl-2">Reg. No</th>
                <th style="width: 25%;">Article</th>
                <th style="width: 10%;">Article Code</th>
                <th style="width: 25%;">Tech. Specification</th>
                <th style="width: 4%; max-width: 4%;">Qty.</th>
                <th style="width: 5%;">UOM</th>
                <th style="width: 6%;">Unit Price</th>
                <th style="width: 8%;">Total</th>
                <th style="width: 3%;"></th>
            </tr>
            </thead>
            <tbody>
            @if($pettyCashItems->isNotEmpty())
                @foreach($pettyCashItems as $pettyCashItem)
                    <tr>
                        <td class="showNumber">
                            <input
                                readonly
                                name=""
                                required
                                value=""
                                class="form-control form-control-sm"/>
                        </td>
                        <td>
                            <select readonly
                                    name=""
                                    required
                                    class="form-control form-control-sm">
                                <option value=""></option>
                            </select>
                        </td>
                        <td>
                            <input name=""
                                   value=""
                                   required
                                   readonly
                                   class="form-control form-control-sm"/>
                        </td>
                        <td>
                                <textarea rows="4"
                                          type="text"
                                          class="form-control"
                                          placeholder="Item Details / Description"
                                          required></textarea>
                        </td>
                        <td>
                            <input type="text"
                                   step="1"
                                   class="form-control amount number_input"
                                   placeholder="Amount [ZMW]">
                        </td>
                        <td>
                            <input
                                name=""
                                required
                                value=""
                                readonly
                                class="form-control form-control-sm"/>
                        </td>
                        <td>
                            <input name=""
                                   required
                                   value=""
                                   readonly
                                   class="form-control form-control-sm"/>
                        </td>
                        <td>
                            <input name=""
                                   type="text"
                                   readonly
                                   required
                                   value=""
                                   class="form-control form-control-sm total_price"/>
                        </td>
                        <td>
                            <button type="button"
                                    data-value="0"
                                    value="deleteRow"
                                    class="btn btn-sm btn-danger p-2">
                                <i class="fas fa-trash m-0"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
            <tfoot>
            <tr>
                <th>
                    <button type="button" class="btn btn-sm btn-success" data-bs-target="#pettyCashModal"
                            data-bs-toggle="modal">
                        <i class="fas fa-plus"></i>
                        Insert
                    </button>
                </th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>


<div class="modal fade" id="pettyCashModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Imprest Buy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
                                    <div class="col-lg-12 col-sm-12 col-sm-12"><label>Project Number (Optional):</label>
                                    </div>
                                    <div class="col-lg-12 col-sm-12 col-sm-12">
                                        <input name="imprestProjectNumber" value="{{$details->job_card_no}}"
                                               class="form-control"/>
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
                                        <input type="hidden" value="{{$materialsHeader->id ?? 0 }}"
                                               name="materialHeaderId">
                                        <div class="form-group row">
                                            <label
                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4 app-field-label field-required"
                                                for="pettyCashBuyItemType">
                                                Item Type:
                                            </label>
                                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                                @if(!empty($materialsHeader))
                                                    <select
                                                        data-value="{{$materialsHeader->item_type_code ?? ''}}"
                                                        class="form-select form-select-sm"
                                                        name="pettyCashBuyItemType"
                                                        id="pettyCashBuyItemType">
                                                        <option></option>
                                                        <option
                                                            @if($materialsHeader->item_type_code ==
                                                                RequisitionItemTypes::STOCK_ITEM_CODE)
                                                                selected
                                                            @endif value="01">STOCK ITEM
                                                        </option>
                                                        <option
                                                            @if($materialsHeader->item_type_code ==
                                                                RequisitionItemTypes::NON_STOCK_ITEM_CODE)
                                                                selected
                                                            @endif value="02">NON STOCK ITEM
                                                        </option>
                                                        <option
                                                            @if($materialsHeader->item_type_code ==
                                                                 RequisitionItemTypes::SERVICE_ITEM_CODE)
                                                                selected
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
                                                        <option value="{{RequisitionItemTypes::STOCK_ITEM_CODE}}">
                                                            STOCK ITEM
                                                        </option>
                                                        <option value="{{RequisitionItemTypes::NON_STOCK_ITEM_CODE}}">
                                                            NON STOCK ITEM
                                                        </option>
                                                        <option value="{{RequisitionItemTypes::SERVICE_ITEM_CODE}}">
                                                            SERVICE
                                                        </option>
                                                    </select>
                                                @endif

                                                <input type="hidden"
                                                       value="{{$details->job_card_no ?? 0}}"
                                                       name="job_card_number"/>
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
                                                        <label for="imprestWorkshopCode"
                                                               class="form-check-inline field-required">
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

                                        <div id="pettyCashSupplierContainer" style="display: none;"
                                             class="form-group row">
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
                        <div class="row">
                            <div class="table-responsive">
                                <table id="pettyCashItemsTable"
                                       data-model-name="PettyCash"
                                       data-form-url="{{route('petty.cash.store')}}"
                                       class="table pettyCashItemsTable">
                                    <thead>
                                    <tr class="bg-green">
                                        <th style="width: 6%;" class="pl-2">Reg. No</th>
                                        <th style="width: 25%;">Article</th>
                                        <th style="width: 10%;">Article Code</th>
                                        <th style="width: 25%;">Tech. Specification</th>
                                        <th style="width: 4%; max-width: 4%;">Qty.</th>
                                        <th style="width: 5%;">UOM</th>
                                        <th style="width: 6%;">Unit Price</th>
                                        <th style="width: 8%;">Total</th>
                                        <th style="width: 3%;"></th>
                                    </tr>
                                    </thead>
                                    {{--<tr>
                                        <th></th>
                                        <th>DETAILS OF PAYMENT</th>
                                        <th>AMOUNT</th>
                                    </tr>
                                     class="table table-striped"
                                    </thead>--}}
                                    <tbody>
                                    <tr>
                                        {{-- <td>
                                             <input type="checkbox" name="chk"/>
                                         </td>--}}
                                        <td class="showNumber">
                                            <input
                                                readonly
                                                name="imprestVehicleRegistration"
                                                required
                                                value="{{$details->reg_no ?? ''}}"
                                                class="form-control form-control-sm imprestVehicleRegistration"/>
                                        </td>
                                        <td>
                                            <select readonly
                                                    name="imprestArticles"
                                                    required
                                                    class="form-control form-control-sm imprestArticles">
                                                <option value=""></option>
                                            </select>
                                        </td>
                                        <td>
                                            <input name="imprestArticleCode"
                                                   value=""
                                                   required
                                                   readonly
                                                   class="form-control form-control-sm articleCode"/>
                                        </td>
                                        <td>
                                <textarea rows="4"
                                          type="text"
                                          name="imprestArticleDescription"
                                          class="form-control amount"
                                          placeholder="Item Details / Description"
                                          id="imprestArticleDescription"
                                          required></textarea>
                                        </td>
                                        <td>
                                            <input type="text"
                                                   step="1"
                                                   id="imprestItemQty"
                                                   name="imprestItemQty"
                                                   class="form-control amount number_input"
                                                   placeholder="Amount [ZMW]">
                                        </td>
                                        <td>
                                            <input
                                                name="imprestItemUnitOfMeasure"
                                                required
                                                value=""
                                                readonly
                                                class="form-control form-control-sm unit_of_measure"/>
                                        </td>
                                        <td>
                                            <input name="imprestItemUnitPrice"
                                                   required
                                                   value=""
                                                   readonly
                                                   class="form-control form-control-sm unit_price"/>
                                        </td>
                                        <td>
                                            <input name="imprestItemTotalPrice"
                                                   type="text"
                                                   readonly
                                                   required
                                                   value=""
                                                   class="form-control form-control-sm total_price"/>
                                        </td>
                                        <td>
                                            <button type="button"
                                                    data-value="0"
                                                    value="deleteRow"
                                                    class="btn btn-sm btn-danger p-2">
                                                <i class="fas fa-trash m-0"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-lg-12 col-sm-12 mb-3 ">
                                <button type="button"
                                        class="btn btn-success btn-sm addItemRow pull-right"
                                        data-table-id="pettyCashItemsTable">
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
                                            <input type="file" class="form-control" multiple name="quotation[]"
                                                   id="receipt"
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
                        <div class="col-lg-3 col-sm-12"><input type="text" name="accountant" readonly
                                                               class="form-control">
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
                                    <i class="fas fa-close"></i>
                                </button>
                                <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                                Sorry, You can not submit <strong>petty cash above K2000</strong>
                            </div>
                        </div>

                        <div id="submit_possible" class="col-lg-12 col-sm-12 text-center">
                            <div id="divSubmit_show">
                                <button class="btn btn-sm btn-success" type="button"
                                        value="submit" id="btnSubmitImprestBuyForm"
                                        name="submit_form">
                                    <i class="fas fa-paper-plane"></i> Submit
                                </button>
                            </div>
                            <div id="divSubmit_hide">
                                <input class="btn btn-sm btn-success"
                                       value="Submitting. Please wait..."
                                       disabled
                                       name="submit_form">
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>





