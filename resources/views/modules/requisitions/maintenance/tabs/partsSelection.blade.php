@php use App\Enums\RequisitionItemTypes;use Carbon\Carbon;
 use App\Models\reference\PurchaseOffice;
@endphp
<div class="container-fluid">
    <input type="hidden"
           id="suppliersList"
           value="{{route('suppliers.list')}}"/>
    <div class="row" data-form-url="{{route("process.job_card")}}" data-model-name="JobCardHeader">
        <div class="col-9">
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
                                    <input type="hidden" value="{{RequisitionItemTypes::NonStockItemCode}}"
                                           id="nonStockItemCode" name="nonStockItemCode"/>
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
                                        <option value=""></option>
                                        @foreach(PurchaseOffice::get() as $purchaseOffice)
                                            <option value="{{$purchaseOffice->code_office}}">
                                                {{$purchaseOffice->description}}
                                            </option>
                                        @endforeach
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
                        <div class="row" id="supplierContainer" style="display: none;">
                            <div class="form-group row">
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
                            <div class="form-group row">
                                <label
                                    class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                    for="staff_name">
                                    Store:
                                </label>
                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           id="store_code"
                                           value=""
                                           placeholder=""
                                           name="store_code"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="row">
            <div class="table-responsive" style="max-height:500px;">
                <table id="part8" data-form-url="{{route("defects.job_card")}}" data-model-name="Defects"
                       class="table table-row-dashed align-middle gs-0">
                    <thead>
                    <tr class="bg-dark">
                        <th style="width: 25%;" class="pl-2">Reg. No</th>
                        <th style="width: 25%;">Article</th>
                        <th style="width: 25%;">Article Code</th>
                        <th style="width: 25%;">Specification</th>
                        <th style="width: 25%;">Qty.</th>
                        <th style="width: 25%;">UOM</th>
                        <th style="width: 25%;">Unit Price</th>
                        <th style="width: 25%;">Total</th>
                        <th style="width: 25%;"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($defects))
                        @foreach($defects as $defect)
                            <tr class="increment">
                                <td class="showNumber">
                                    <input name="registration" required readonly
                                            value="{{$details->veh_reg}}"
                                            class="form-select form-select-sm vehicleSystem" />
                                </td>
                                <td>
                                    <select name="vehicleSystem" required
                                            data-value="{{$defect->veh_sys}}"
                                            class="form-select form-select-sm vehicleSystem">
                                        <option></option>
                                    </select>
                                </td>
                                <td>
                                    <input name="articleCode" required readonly
                                           class="form-select form-select-sm vehicleSystem" />
                                </td>
                                <td>
                                    <select name="workshopSection" required
                                            class="form-select form-select-sm workshopSection">
                                        <option></option>
                                        @foreach($workshop_sections as $workshop_section)
                                            @if($defect->section_code == $workshop_section->code)
                                                <option
                                                    selected
                                                    value="{{$workshop_section->code}}">{{$workshop_section->name}}</option>
                                            @else
                                                <option
                                                    value="{{$workshop_section->code}}">{{$workshop_section->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                    <input name="date_def"
                                           readonly="readonly"
                                           value="@if($defect){{date('Y-m-d',strtotime(Carbon::parse($defect->date_def)->format('Y-m-d H:i:s')))}}@else{{date('Y-m-d H:i:s', strtotime(Carbon::now()))}}@endif"
                                           class="tabledit-input form-control input-sm input-number"
                                           type="text">
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
                        @endforeach
                    @else
                        <tr class="increment">
                            <td class="showNumber">
                                <select name="vehicleSystem"
                                        class="form-select form-select-sm vehicleSystem">
                                    <option></option>
                                </select>
                            </td>
                            <td>
                                <select name="defectCategory"
                                        class="form-select form-select-sm defectCategory">
                                    <option></option>
                                </select>
                            </td>
                            <td>
                                <select name="defect"
                                        class="form-select form-select-sm defect">
                                    <option></option>
                                </select>
                            </td>
                            <td>
                                <select name="workshopSection" class="form-select form-select-sm workshopSection">
                                    <option></option>
                                    @foreach($workshop_sections as $workshop_section)
                                        <option
                                            value="{{$workshop_section->code}}">{{$workshop_section->name}}</option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input name="total"
                                       readonly="readonly"
                                       value="@if($details){{date('Y-m-d',strtotime(Carbon::parse($details->date_in)->format('Y-m-d H:i:s')))}}@else{{date('Y-m-d H:i:s', strtotime(Carbon::now()))}}@endif"
                                       class="tabledit-input form-control input-sm input-number"
                                       type="text">
                            </td>

                            <td class="view-mode">
                                <button type="button"
                                        value="deleteRow"
                                        data-value="0"
                                        class="btn btn-danger p-2">
                                    <i class="fas fa-trash m-0"></i>
                                </button>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                <button type="button"
                        data-table-id="part8"
                        class="btn btn-sm btn-primary add pull-right"
                        value="addRow">
                    <i class="fa fa-plus"></i> Add Row
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
