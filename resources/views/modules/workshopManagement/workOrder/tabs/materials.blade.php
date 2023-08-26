@php
    use App\Enums\RequisitionItemTypes;use App\Helpers\StatusHelper;use Carbon\Carbon;
@endphp
<div class="row pt-5">
    <div class="col-12">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="container-fluid pl-0">
                    <div class="row">
                        <input type="hidden" value="{{$materialsHeader->id ?? 0 }}" name="materialHeaderId">
                        <div class="form-group row">
                            <label
                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4 app-field-label field-required"
                                for="staff_no">Item Type:
                            </label>
                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                @if(!empty($materialsHeader))
                                    <select
                                        data-value="{{$materialsHeader->item_type_code ?? ''}}"
                                        readonly="readonly"
                                        class="form-select form-select-sm"
                                        name="itemType"
                                        id="itemType">
                                        <option></option>
                                        <option
                                            @if($materialsHeader->item_type_code == RequisitionItemTypes::STOCK_ITEM_CODE) selected
                                            @endif value="01">STOCK ITEM
                                        </option>
                                        <option
                                            @if($materialsHeader->item_type_code == RequisitionItemTypes::NON_STOCK_ITEM_CODE) selected
                                            @endif value="02">NON STOCK ITEM
                                        </option>
                                        {{--   <option
                                               @if($materialsHeader->item_type_code ==  RequisitionItemTypes::ServiceItemCode) selected
                                               @endif value="03">SERVICE
                                           </option>--}}
                                    </select>
                                @else
                                    <select
                                        required
                                        class="form-select form-select-sm"
                                        name="itemType"
                                        id="itemType">
                                        <option></option>
                                        <option value="{{RequisitionItemTypes::STOCK_ITEM_CODE}}">
                                            STOCK ITEM
                                        </option>
                                        <option value="{{RequisitionItemTypes::NON_STOCK_ITEM_CODE}}">
                                            NON STOCK ITEM
                                        </option>
                                        {{-- <option value="{{RequisitionItemTypes::ServiceItemCode}}">
                                             SERVICE
                                         </option>--}}
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
                                for="staff_no">Purchase Office:
                            </label>
                            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                <select
                                    data-value=""
                                    required
                                    class="form-select form-select-sm"
                                    name="purchase_office"
                                    id="purchase_office">
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
                                        <label for="workshop_code" class="form-check-inline field-required">
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
                                       name="workshop_code"
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
                                           id="request_date"
                                           readonly
                                           value="{{Carbon::parse($details->date_in)->format('d/m/Y')}}"
                                           name="request_date"
                                           required>
                                @else
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           id="request_date"
                                           readonly
                                           value="{{Carbon::parse(Carbon::now())->format('d/m/Y')}}"
                                           name="request_date"
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

                        <div id="supplierContainer" style="display: none;" class="form-group row">
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
                                    name="supplier"
                                    autocomplete="off"
                                    id="supplier">
                                </select>
                            </div>
                        </div>

                        <div id="storeContainer" style="display: none;" class="form-group row">
                            <label
                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                for="staff_name">
                                Store:
                            </label>
                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                <input type="hidden"
                                       id="store_code"
                                       value="{{$officeDetails->store_code ?? ''}}"
                                       name="store_code"/>
                                <input type="text"
                                       class="form-control form-control-sm"
                                       readonly
                                       id="store_name"
                                       value="{{$officeDetails->store_code ?? ''}}:{{$officeDetails->store_name ?? ''}}"
                                       placeholder=""
                                       name="store_name"/>
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
                                for="staff_no">Collection Date:
                            </label>
                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                @if($materialsHeader)
                                    <input type="date"
                                           class="form-control form-control-sm"
                                           id="date_expected"
                                           min="{{date('Y-m-d', strtotime(Carbon::now()))}}"
                                           value="{{date('Y-m-d', strtotime(Carbon::parse($materialsHeader->collection_date)->format('Y-m-d')))}}"
                                           name="date_expected"
                                    />

                                @else
                                    <input type="date"
                                           class="form-control form-control-sm"
                                           id="date_expected"
                                           min="{{date('Y-m-d', strtotime(Carbon::now()))}}"
                                           value="{{date('Y-m-d', strtotime(Carbon::now()->addDays(7)))}}"
                                           name="date_expected"
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
    <div class="col-xs-12 col-sm-12 col-md-12 px-0">
        <div class="row">
            <div style="max-height:500px; overflow-x: auto;">
                <table id="material_table"
                       data-form-url="{{route("process.requisition")}}"
                       data-model-name="PartsHeader"
                       class="table dataTable table-row-dashed align-middle gs-0 nowrap">
                    <thead>
                    <tr class="bg-success-subtle">
                        <th scope="row" style="width: 6%;" class="pl-2">Reg. No</th>
                        <th scope="row" style="width: 25%;">Article</th>
                        <th scope="row">Article Code</th>
                        <th scope="row" style="width: 25%;">Tech. Specification</th>
                        <th scope="row">SPMS Ref.</th>
                        <th scope="row" style="width: 4%; max-width: 4%;">Qty.</th>
                        <th scope="row">UOM</th>
                        <th scope="row">Unit Price</th>
                        <th scope="row">Total</th>
                        <th scope="row"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($materials && $materials->isNotEmpty())
                        @foreach($materials as $material)
                            <tr class="increment" data-record-id="{{$material->id}}">
                                <td class="showNumber">
                                    <input
                                        readonly
                                        name="registration"
                                        required
                                        value="{{$material->veh_reg_no ?? $details->reg_no ?? ''}}"
                                        class="form-control form-control-sm registration"/>
                                </td>
                                <td>
                                    <select readonly
                                            name="articles"
                                            required
                                            data-text="{{$material->specifications ?? ''}}"
                                            data-value="{{$material->mat_code ?? ''}}"
                                            class="form-control form-control-sm DropDownList">
                                        <option data-toggle="tooltip"
                                                title="{{$material->specifications ?? ''}}"
                                                value="{{$material->mat_code ?? ''}}">
                                            {{$material->mat_code ?? ''}}:{{$material->specifications ?? ''}}
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <input
                                        name="articleCode"
                                        value="{{$material->mat_code ?? ''}}"
                                        required
                                        readonly
                                        class="form-control form-control-sm articleCode"/>
                                </td>
                                <td>
                                    <input type="text"
                                           data-toggle="tooltip"
                                           maxlength="300"
                                           name="technical_specification"
                                           required
                                           readonly
                                           title="{{$material->article_specification ?? ''}}"
                                           value="{{$material->article_specification ?? ''}}"
                                           class="form-control form-control-sm technical_specification"/>
                                </td>
                                <th>
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           readonly
                                           value="{{$material->st_pur ?? $material->stf_number ?? ''}}">
                                </th>
                                <td>
                                    <input type="text"
                                           min="1"
                                           name="quantity"
                                           required
                                           readonly
                                           value="{{$material->quantity ?? ''}}"
                                           class="form-control form-control-sm quantity number_input"/>
                                </td>

                                <td>
                                    <input
                                        name="unit_of_measure"
                                        required
                                        value="{{$material->unit_of_measure ?? ''}}"
                                        readonly
                                        class="form-control form-control-sm unit_of_measure"/>
                                </td>

                                <td>
                                    <input name="unit_price"
                                           required
                                           value="{{$material->price ?? ''}}"
                                           readonly
                                           class="form-control form-control-sm unit_price"/>
                                </td>

                                <td>
                                    <span id="total_price">{{$material->amount ?? ''}}</span>
                                    <input name="total_price"
                                           type="hidden"
                                           required
                                           value="{{$material->amount ?? ''}}"
                                           readonly
                                           class="form-control form-control-sm total_price"/>
                                </td>

                                <td class="view-mode">
                                    @if(StatusHelper::authorised() == $material->status??'')
                                        <button type="button"
                                                @if(StatusHelper::authorised() == $material->status??'') disabled @endif
                                                data-value="0"
                                                value="deleteRow"
                                                class="btn btn-danger p-2">
                                            <i class="fas fa-trash m-0"></i>
                                        </button>
                                    @else
                                        <button type="button"
                                                disabled
                                                data-value="{{$material->id ?? '0'}}"
                                                value="deleteRow"
                                                class="btn btn-danger btn-sm p-2">
                                            <i class="fas fa-trash m-0"></i>
                                        </button>
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="increment" data-record-id="0">
                            <td class="showNumber">
                                <input
                                    readonly="readonly"
                                    name="registration"
                                    required
                                    value="{{$details->reg_no ?? ''}}"
                                    class="form-control form-control-sm registration"/>
                            </td>
                            <td>
                                <select disabled
                                        name="articles"
                                        required
                                        data-value=""
                                        class="form-control form-control-sm articlesDropDownList">
                                    <option></option>
                                </select>
                            </td>
                            <td>
                                <input
                                    name="articleCode"
                                    required
                                    readonly
                                    class="form-control form-control-sm articleCode"/>
                            </td>
                            <td>
                                <input
                                    name="technical_specification"
                                    required
                                    class="form-control form-control-sm technical_specification"/>
                            </td>
                            <td></td>
                            <td>
                                <input
                                    type="text"
                                    min="1"
                                    name="quantity"
                                    required
                                    class="form-control form-control-sm quantity number_input"/>
                            </td>

                            <td>
                                <input
                                    name="unit_of_measure"
                                    required
                                    readonly
                                    class="form-control form-control-sm unit_of_measure"/>
                            </td>

                            <td>
                                <input name="unit_price"
                                       required
                                       readonly
                                       class="form-control form-control-sm unit_price"/>
                            </td>

                            <td>
                                <input name="total_price"
                                       required
                                       readonly
                                       class="form-control form-control-sm total_price"/>
                            </td>

                            <td class="view-mode">
                                <button type="button"
                                        data-value="0"
                                        value="deleteRow"
                                        class="btn btn-danger btn-sm p-2">
                                    <i class="fas fa-trash m-0"></i>
                                </button>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    <tr>
                        <td class="pl-2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right"><strong>TOTAL</strong></td>
                        <td class="text-right"><b id="quantityTotal" class="input-number">0</b></td>
                        <td></td>
                        <td class="text-right"><strong>TOTAL</strong></td>
                        <td class="text-right"><b id="itemsTotal" class="input-number">0.00</b></td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
                <button type="button"
                        data-table-id="material_table"
                        class="btn btn-sm btn-primary add pull-right"
                        value="insertRow">
                    <i class="fa fa-plus"></i>
                    Add Row
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-10"></div>
            <div class="col-2">
                <div class="row">
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="form-group">
                <label
                    class="col-xs-12 col-sm-6 col-md-5 col-lg-4 pl-0 field-required"
                    for="remarks">
                    Comments <small>Will be used as justification for Requisition</small>:
                </label>
                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8 pl-0">
                    @if(!empty($comments))
                        <textarea type="text"
                                  id="comments"
                                  minlength="20"
                                  maxlength="255"
                                  required
                                  name="comments"
                                  style="height: 129px;"
                                  class="form-control comments form-control-sm">{{$comments->where('type','=','REQ')->first()->remarks ??''}}</textarea>
                    @else
                        <textarea type="text"
                                  id="comments"
                                  minlength="20"
                                  maxlength="255"
                                  required
                                  name="comments"
                                  style="height: 129px;"
                                  class="form-control comments form-control-sm"></textarea>
                    @endif
                </div>
            </div>
        </div>
        <table class="mt-10">
            <tbody>
            <tr>
                <td class="text-right">
                    <strong id="srfTotal" class="input-number">Prepared By:</strong>
                </td>
                <td>
                    <b id="section" class="input-number">RECEPTION</b>
                </td>
                <td></td>
            </tr>
            </tbody>
        </table>
        <div class="col-12 text-right">
            <div>
                <button type="button"
                        id="saveMaterials"
                        style="background: #f59d33; color: #fff;"
                        data-table-id="material_table"
                        class="btn btn-sm btn-success add pull-right">
                    <i class="fa fa-save"></i>
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
