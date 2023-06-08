@php use App\Enums\RequisitionItemTypes;use App\Models\reference\PurchaseOffice;use Carbon\Carbon;
@endphp
<div class="container-fluid">
    <input type="hidden"
           id="suppliersList"
           value="{{route('suppliers.list')}}"/>
    <div class="row">
        <div class="col-12">

            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="container-fluid pl-0">
                        <div class="row">
                            <div class="form-group row">
                                <label
                                    class="col-xs-12 col-sm-6 col-md-5 col-lg-4 app-field-label field-required"
                                    for="staff_no">Item Type:
                                </label>
                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                    <select
                                        data-value="{{''}}"
                                        required
                                        class="form-select form-select-sm"
                                        name="itemType"
                                        id="itemType">
                                        <option></option>
                                        <option value="01">STOCK ITEM</option>
                                        <option value="02">NON STOCK ITEM</option>
                                        <option value="03">SERVICE</option>
                                    </select>
                                    <input type="hidden" value="{{$details->job_card_no ?? 0}}" name="job_card_number"/>
                                    <input type="hidden" value="{{RequisitionItemTypes::StockItemCode}}"
                                           id="stockItemCode" name="stockItemCode"/>
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

                                        <option value="{{$officeDetails->purchase_office_area ?? ''}}">
                                            {{$officeDetails->purchase_office ?? ''}}
                                        </option>
                                        {{--@foreach(PurchaseOffice::get() as $purchaseOffice)
                                            @if($purchaseOffice->purchase_office_code) @endif
                                            <option value="{{$purchaseOffice->code_office}}">
                                                {{$purchaseOffice->description}}
                                            </option>
                                        @endforeach--}}
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
                                           value="{{$officeDetails->workshop_no ?? 0}}"/>
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
                                     <input type="text"
                                            class="form-control form-control-sm"
                                            id="request_date"
                                            readonly
                                            value="@if($details) {{Carbon::parse($details->date_in)->format('d/m/Y')}} @else {{ date('Y-m-d', strtotime(Carbon::now()))}} @endif"
                                            name="request_date"
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
                                        data-value=""
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
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="container-fluid pl-0">
                        <div class="row">
                            <div class="form-group row">
                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
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
        </div>
        <hr/>
        <div class="col-xs-12 col-sm-12 col-md-12 px-0">
            <div class="row">
                <div class="table-responsive" style="max-height:500px; overflow-x: auto;">
                    <table id="material_table" data-form-url="{{route("process.job_card")}}"
                           data-model-name="PartsHeader"
                           class="table dataTable table-row-dashed align-middle gs-0 nowrap">
                        <thead>
                        <tr class="bg-default">
                            <th style="width: 10%;" class="pl-2">Reg. No</th>
                            <th style="width: 25%;">Article</th>
                            <th style="width: 15%;">Article Code</th>
                            <th style="width: 25%;">Specification</th>
                            <th style="width: 25%;">Qty.</th>
                            <th style="width: 5%;">UOM</th>
                            <th style="width: 25%;">Unit Price</th>
                            <th style="width: 25%;">Total</th>
                            <th style="width: 25%;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($materials && $materials->isNotEmpty())
                            @foreach($materials as $material)
                                <tr class="increment">
                                    <td class="showNumber">
                                        <input
                                            name="registration"
                                            required
                                            value="{{$details->veh_reg ?? ''}}"
                                            class="form-control form-control-sm registration"/>
                                    </td>
                                    <td>
                                        <select
                                            name="articles"
                                            required
                                            data-value="{{$material->material_code ?? ''}}"
                                            class="form-select form-select-sm articlesDropDownList">
                                            <option></option>
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
                                        <input
                                            name="technical_specification"
                                            required
                                            value="{{$material->specifications ?? ''}}"
                                            class="form-control form-control-sm technical_specification"/>
                                    </td>

                                    <td>
                                        <input
                                            name="quantity"
                                            required
                                            value="{{$material->quantity ?? ''}}"
                                            class="form-control form-control-sm quantity"/>
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
                                        <input name="total_price"
                                               required
                                               value="{{$material->amount ?? ''}}"
                                               readonly
                                               class="form-control form-control-sm total_price"/>
                                    </td>

                                    <td class="view-mode">
                                        <button type="button"
                                                data-value="{{$material->id ?? '0'}}"
                                                value="deleteRow"
                                                class="btn btn-danger p-2">
                                            <i class="fas fa-trash m-0"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="increment">
                                <td class="showNumber">
                                    <input
                                        name="registration"
                                        required
                                        value="{{$details->veh_reg ?? ''}}"
                                        class="form-control form-control-sm registration"/>
                                </td>
                                <td>
                                    <select
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

                                <td>
                                    <input
                                        name="quantity"
                                        required
                                        class="form-control form-control-sm quantity"/>
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
                                            data-value="{{$defect->id ?? '0'}}"
                                            value="deleteRow"
                                            class="btn btn-danger p-2">
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
                            <td>Total</td>
                            <td><b id="quantityTotal" class="input-number">0.00</b></td>
                            <td></td>
                            <td>Total</td>
                            <td><b id="itemsTotal" class="input-number">0.00</b></td>
                            <td></td>
                        </tr>
                        </tfoot>

                    </table>
                    <button type="button"
                            data-table-id="material_table"
                            class="btn btn-sm btn-primary add pull-right"
                            value="addRow">
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
                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 pl-0"
                        for="remarks">
                        Comments (optional):
                    </label>
                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8 pl-0">
                        @if(!empty($comments))
                            <textarea type="text"
                                      id="remarks"
                                      name="remarks"
                                      style="height: 129px;"
                                      class="form-control form-control-sm">{{$comments->where('type','=','DEF')->first()->remarks ??''}}</textarea>
                        @else
                            <textarea type="text"
                                      id="remarks"
                                      name="remarks"
                                      style="height: 129px;"
                                      class="form-control form-control-sm"></textarea>
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
        </div>
    </div>
</div>
