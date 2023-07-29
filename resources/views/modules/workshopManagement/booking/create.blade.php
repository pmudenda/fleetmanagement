@php
    use App\Enums\RepairTypes;use App\Helpers\StatusHelper;
    use Carbon\Carbon;
    use App\Enums\RequisitionItemTypes;

@endphp
@extends('layouts.app')
@push('styles')
    <link href="{{asset("assets/plugins/select2/css/select2.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css")}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset("libs/steps/jquery-steps.css")}}" rel="stylesheet" type="text/css"/>
    <style>
        th {
            white-space: nowrap;
        }

        /**===NO WRAP ON TABLE =====**/
        table.dataTable.nowrap th,
        table.dataTable.nowrap td {
            white-space: nowrap;
        }

        .select2 {
            width: 100% !important;
        }

        .nav-tabs .nav-link.active, .nav-tabs .nav-item.show .nav-link {
            border-color: orange;
        }

        .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link {
            color: #495057;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
        }
    </style>
@endpush
@section('content')

    <x-content-header
        :activeCrumb="'New Reservation'"
        :linkText="'Booking'"
        :pageTitle="'New Reservation'"/>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h4>New Reservation</h4>
                    @if(!empty($details))
                        <span class="ml-2 indicator-pill whitespace-nowrap green">
                            <span>Saved</span>
                        </span>
                    @else
                        <span class="ml-2 indicator-pill whitespace-nowrap orange"><span>Not Saved</span></span>
                    @endif
                </div>
                @if(!empty($details))
                    <div class="card-toolbar justify-content-end">
                        RESERVATION NUMBER: <span class="text-orange">{{ $details->job_card_no ?? '' }}</span>
                    </div>
                @endif

                <div class="card-toolbar justify-content-end">
                    RESERVATION NUMBER: <span class="text-orange">ZFMREF9999999999999</span>
                </div>
            </div>

            <div class="card-body pb-4 min-h-600px pt-0">

                <x-error-view/>

                <label class="app-required-marker"></label>
                <form name="jobCardForm"
                      id="jobCardForm"
                      action="{{route('save.workshop.requisition')}}"
                      method="post">
                    @csrf

                    <div class="col-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="container-fluid pl-0">
                                    <div class="row">
                                        <div class="form-group row">
                                            <label
                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4 app-field-label field-required"
                                                for="staff_no">Registration No.:
                                            </label>
                                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                                <div class="input-group">
                                                    <input type="text"
                                                           @if(!empty($details->reg_no)) readonly="readonly" @endif
                                                           data-action="{{route('requisition.vehicle.details')}}"
                                                           class="form-control form-control-sm"
                                                           value="{{$details->reg_no ?? ''}}"
                                                           id="vehicle_registration"
                                                           placeholder="Vehicle Reg e.g AAB 6757"
                                                           name="vehicle_registration" required/>
                                                    <div class="input-group-addon">
                                                        <button type="button"
                                                                id="vehicleSearchBtn"
                                                                name="vehicleSearchBtn"
                                                                class="btn btn-success btn-sm border-radius-0">
                                                            <i class="fas fa-search"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <input type="hidden" value="{{$details->job_card_no ?? 0}}"
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
                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4 app-field-label field-required">
                                                Store Reservation No.:
                                            </label>
                                            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                                <span class="text-orange">J02LR26889</span>
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
                                        <input type="hidden" value="{{$materialsHeader->id ?? 0 }}"
                                               name="materialHeaderId">
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
                                                        <option @if($materialsHeader->item_type_code == '01') selected
                                                                @endif value="01">STOCK ITEM
                                                        </option>
                                                        <option @if($materialsHeader->item_type_code == '02') selected
                                                                @endif value="02">NON STOCK ITEM
                                                        </option>
                                                        <option @if($materialsHeader->item_type_code == '03') selected
                                                                @endif value="03">SERVICE
                                                        </option>
                                                    </select>
                                                @else
                                                    <select
                                                        required
                                                        class="form-select form-select-sm"
                                                        name="itemType"
                                                        id="itemType">
                                                        <option></option>
                                                        <option value="01">STOCK ITEM</option>
                                                        <option value="02">NON STOCK ITEM</option>
                                                        <option value="03">SERVICE</option>
                                                    </select>
                                                @endif

                                                <input type="hidden" value="{{$details->job_card_no ?? 0}}"
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
                                                        <label for="workshop_code"
                                                               class="form-check-inline field-required">
                                                            Workshop:
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                                <select
                                                    data-value="{{$details->workshop_code ?? ''}}"
                                                    required
                                                    class="form-select form-select-sm"
                                                    name="workshop"
                                                    autocomplete="off"
                                                    id="workshop">
                                                </select>
                                                {{--<input type="text"
                                                       readonly
                                                       value="{{$officeDetails->workshop_name ?? 0}}"
                                                       class="form-control form-control-sm"/>
                                                <input type="hidden"
                                                       name="workshop_code"
                                                       value="{{$officeDetails->workshop_code ?? 0}}"/>--}}
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

                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" style="list-style: none; width: 178px;">
                            <a class="nav-link active" data-toggle="tab" href="#spares" role="tab">Spares</a>
                        </li>

                        <li class="nav-item" style="list-style: none; width: 178px;">
                            <a class="nav-link"
                               data-toggle="tab" href="#services" role="tab">Services</a>
                        </li>
                    </ul><!-- Tab panes -->
                    <div class="tab-content">

                        <div class="tab-pane active" id="spares" role="tabpanel">
                            {{--@include('modules.workshopManagement.jobCard.tabs.materials')--}}
                            <div class="row pt-5">
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
                                                <tbody>
                                                @if($materials && $materials->isNotEmpty())
                                                    @foreach($materials as $material)
                                                        <tr class="increment">
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
                                                                <input type="text"
                                                                       maxlength="300"
                                                                       name="technical_specification"
                                                                       required
                                                                       readonly
                                                                       value="{{$material->specifications ?? ''}}"
                                                                       class="form-control form-control-sm technical_specification"/>
                                                            </td>

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
                                                                <span
                                                                    id="total_price">{{$material->amount ?? ''}}</span>
                                                                <input name="total_price"
                                                                       type="hidden"
                                                                       required
                                                                       value="{{$material->amount ?? ''}}"
                                                                       readonly
                                                                       class="form-control form-control-sm total_price"/>
                                                            </td>

                                                            <td class="view-mode">
                                                                <button type="button"
                                                                        @if($material->status == StatusHelper::authorised()) disabled
                                                                        @endif
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
                                                    <td class="text-right"><strong>TOTAL</strong></td>
                                                    <td class="text-right"><b id="quantityTotal"
                                                                              class="input-number">0</b></td>
                                                    <td></td>
                                                    <td class="text-right"><strong>TOTAL</strong></td>
                                                    <td class="text-right"><b id="itemsTotal"
                                                                              class="input-number">0.00</b></td>
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
                        </div>

                        <div
                            class="tab-pane"
                            id="services" role="tabpanel">
                            {{--@include('modules.workshopManagement.jobCard.tabs.services')--}}
                            <div class="row pt-5">
                                <hr style="color: orange;"/>
                                <div class="col-xs-12 col-sm-12 col-md-12 px-0">
                                    <div class="row">
                                        <div style="max-height:500px; overflow-x: auto;">
                                            <table id="services_table"
                                                   data-form-url="{{route("process.service.requisition")}}"
                                                   data-model-name="ServicesHeader"
                                                   class="table dataTable table-row-dashed align-middle gs-0 nowrap">
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
                                                <tbody>
                                                @if($services->isNotEmpty())
                                                    @foreach($services as $service)
                                                        <tr class="increment">
                                                            <td class="showNumber">
                                                                <input
                                                                    readonly="readonly"
                                                                    name="vehicle_registration"
                                                                    required
                                                                    value="{{$details->reg_no ?? ''}}"
                                                                    class="form-control form-control-sm vehicle_registration"/>
                                                            </td>
                                                            <td>
                                                                <select
                                                                    name="service_article"
                                                                    required
                                                                    value="{{$service->material_code ?? ''}}"
                                                                    data-value="{{$service->material_code ?? ''}}"
                                                                    class="form-control form-control-sm servicesArticlesDropDownList">
                                                                    <option value="{{$service->material_code ?? ''}}">

                                                                    </option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input
                                                                    name="serviceArticleCode"
                                                                    required
                                                                    value="{{$service->material_code ?? ''}}"
                                                                    readonly
                                                                    class="form-control form-control-sm serviceArticleCode"/>
                                                            </td>
                                                            <td>
                                                                <input
                                                                    name="service_technical_specification"
                                                                    required
                                                                    value="{{$service->specification ?? ''}}"
                                                                    class="form-control form-control-sm service_technical_specification"/>
                                                            </td>

                                                            <td>
                                                                <input
                                                                    readonly
                                                                    type="text"
                                                                    min="1"
                                                                    value="1"
                                                                    max="1"
                                                                    name="service_quantity"
                                                                    required
                                                                    class="form-control form-control-sm service_quantity number_input"/>
                                                            </td>

                                                            <td>
                                                                <input
                                                                    name="service_unit_of_measure"
                                                                    required
                                                                    readonly
                                                                    class="form-control form-control-sm unit_of_measure"/>
                                                            </td>

                                                            <td>
                                                                <input name="service_unit_price"
                                                                       required
                                                                       readonly
                                                                       class="form-control form-control-sm service_unit_price"/>
                                                            </td>

                                                            <td>
                                                                <input name="service_total_price"
                                                                       required
                                                                       readonly
                                                                       class="form-control form-control-sm service_total_price"/>
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
                                                            <input
                                                                readonly="readonly"
                                                                name="vehicle_registration"
                                                                required
                                                                value="{{$details->reg_no ?? ''}}"
                                                                class="form-control form-control-sm vehicle_registration"/>
                                                        </td>
                                                        <td>
                                                            <select
                                                                name="service_article"
                                                                required
                                                                data-value=""
                                                                class="form-control form-control-sm servicesArticlesDropDownList">
                                                                <option></option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input
                                                                name="serviceArticleCode"
                                                                required
                                                                readonly
                                                                class="form-control form-control-sm serviceArticleCode"/>
                                                        </td>
                                                        <td>
                                                            <input
                                                                name="service_technical_specification"
                                                                required
                                                                class="form-control form-control-sm service_technical_specification"/>
                                                        </td>

                                                        <td>
                                                            <input
                                                                readonly
                                                                type="text"
                                                                min="1"
                                                                value="1"
                                                                max="1"
                                                                name="service_quantity"
                                                                required
                                                                class="form-control form-control-sm service_quantity number_input"/>
                                                        </td>

                                                        <td>
                                                            <input
                                                                name="service_unit_of_measure"
                                                                required
                                                                readonly
                                                                class="form-control form-control-sm unit_of_measure"/>
                                                        </td>

                                                        <td>
                                                            <input name="service_unit_price"
                                                                   required
                                                                   class="form-control form-control-sm service_unit_price"/>
                                                        </td>

                                                        <td>
                                                            <input name="service_total_price"
                                                                   required
                                                                   readonly
                                                                   class="form-control form-control-sm service_total_price"/>
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
                                                    <td class="text-right"><strong>TOTAL</strong></td>
                                                    <td class="text-right">
                                                        <b id="serviceQuantityTotal" class="input-number">
                                                            0
                                                        </b>
                                                    </td>
                                                    <td></td>
                                                    <td class="text-right"><strong>TOTAL</strong></td>
                                                    <td class="text-right"><b id="serviceTotalPrice"
                                                                              class="input-number">0.00</b></td>
                                                    <td></td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                            <button type="button"
                                                    data-table-id="services_table"
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
                                                              id="service_comments"
                                                              minlength="20"
                                                              maxlength="255"
                                                              required
                                                              name="service_comments"
                                                              style="height: 129px;"
                                                              class="form-control comments form-control-sm">{{$comments->where('type','=','SREQ')->first()->remarks ??''}}</textarea>
                                                @else
                                                    <textarea type="text"
                                                              id="service_comments"
                                                              minlength="20"
                                                              maxlength="255"
                                                              required
                                                              name="service_comments"
                                                              style="height: 129px;"
                                                              class="form-control comments form-control-sm"></textarea>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    {{--<table class="mt-10">
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
                                    </table>--}}
                                    <div class="col-12 text-right">
                                        <div>
                                            <button type="button"
                                                    id="saveServices"
                                                    style="background: #f59d33; color: #fff;"
                                                    data-table-id="services_table"
                                                    class="btn btn-sm btn-success add pull-right">
                                                <i class="fa fa-save"></i>
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>

                <input type="hidden" value="{{ route('user.search') }}" id="newUserSearchUrl"/>
                <input type="hidden" value="{{route('search.project')}}" id="projects_url"/>
                <input type="hidden" value="{{route('all.workshop.list')}}" id="workshopsUrl"/>
                <input type="hidden" value="{{route('fuels.levels')}}" id="fuelLevelsUrl"/>
                <input type="hidden" value="{{route('load.vehicle.systems')}}" id="systemsUrl"/>
                <input type="hidden" value="{{route('load.defects.category')}}" id="defectCategoryUrl"/>
                <input type="hidden" value="{{route('load.defects')}}" id="defectUrl"/>
                <input type="hidden" value="{{route('load.workshop.section')}}" id="workShopSectionsUrl"/>
                <input type="hidden" value="{{route('load.articles')}}" id="articlesUrl"/>
                <input type="hidden" value="{{route('get.articles')}}" id="getArticlesUrl"/>
                <input type="hidden" value="{{route('load.article.details')}}" id="articleDetailsUrl"/>
                <input type="hidden" value="{{$details->job_card_no ?? ''}}" id="job_card_number"/>
                <input type="hidden" value="{{$details->veh_reg ?? ''}}" name="vehicle_registration"
                       id="vehicle_registration"/>
                <input type="hidden" value="{{$details->veh_reg ?? ''}}" name="vehicle_reg_no"
                       id="vehicle_reg_no"/>
                <input type="hidden" value="{{$details->wshp_act_code ?? ''}}" name="workshop_reference"
                       id="workshop_reference"/>
                <input type="hidden" value="{{route('delete.defect.record')}}" name="deleteDefectUrl"
                       id="deleteDefectUrl"/>
                <input type="hidden" value="{{route('delete.material.record')}}" name="deleteMaterialUrl"
                       id="deleteMaterialUrl"/>
            </div>
        </div>
        <input type="hidden" name="onboarding_status" id="onboarding_status"
               value="{{StatusHelper::onboardingComplete()}}">
    </section>
    <input type="hidden" value="{{StatusHelper::onboardingComplete()}}" name="incompleteOnBoarding"
           id="incompleteOnBoarding"/>
    <input type="hidden" value="{{StatusHelper::vehicleInWorkshop()}}" name="vehicleInWorkshop" id="vehicleInWorkshop"/>
    <input type="hidden" value="{{StatusHelper::active()}}" name="vehicleActive" id="vehicleActive"/>

    <input type="hidden"
           value="{{RequisitionItemTypes::StockItemCode}}"
           id="stockItemCode"
           name="stockItemCode"/>

    <input type="hidden"
           value="{{RequisitionItemTypes::ServiceItemCode}}"
           id="serviceItemCode" name="serviceItemCode"/>

    <input type="hidden"
           value="{{RequisitionItemTypes::NonStockItemCode}}"
           id="nonStockItemCode" name="nonStockItemCode"/>

    <input type="hidden"
           value="{{RepairTypes::ContractedService->value}}"
           id="contractedServiceRepair" name="contractedServiceRepair"/>

    <input type="hidden"
           value="{{RepairTypes::AccidentRepair->value}}"
           id="accidentRepairs" name="accidentRepairs"/>
@endsection
@push('scripts')
    <script>
        window.selectedAccessories = [];
        window.defects = [];
        window.materials = {!! json_encode($materials) !!};
        window.step_id = 0;
    </script>
    <script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <script>
        'use strict';

        function initArticleSelector(element) {
            const dataUrl = document.querySelector('#articlesUrl').value;

            // don't re-initialize
            if (!element || element.length === 0) {
                return;
            }
            let hasAttribute = element[0].hasAttribute('data-select2-id="1"');
            console.log(hasAttribute);
            if (hasAttribute) {
                return;
            }

            element.select2({
                selectOnClose: true,
                multiple: false,
                quietMillis: 100,
                id: function (project) {
                    return project['code_article'];
                },
                theme: 'bootstrap4',
                ajax: {
                    delay: 250,
                    beforeSend: function () {
                        window.showLoaderModal(false);
                        window.loaderVisible = false;
                    },
                    url: dataUrl,
                    dataType: 'json',
                    data: function (params) {
                        return {
                            search: params.term, // search term
                            type_article: document.querySelector('#itemType').value,
                            store_code: document.querySelector('#store_code').value,
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;

                        return {
                            results: formatResults(data.items),
                            pagination: {
                                more: (params.page * 30) < data['total_count']
                            }
                        };
                    },
                    cache: true
                },
                placeholder: 'Enter Article name or Code',
                minimumInputLength: 3,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection
            }).off('select2:select').on('select2:select', function (e) {
                let article = e.params['data'];
                const row = $(e.currentTarget).closest('tr');
                if (document.querySelector('[name="stockItemCode"]').value == $("#itemType").val()) {

                    if (!article?.price_map) {
                        const description = article?.technical_specifications ? article?.technical_specifications : "";
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'The Article '
                                + article?.id
                                + ' - ' + description + ' has no price. ' +
                                ' Please Contact Fleet Master System Administrator on 3309,3350,3351,3306, fleetmaster@zesco.co.com'
                        });
                        return;
                    }

                    if (article?.quantity_in_store === "0" || article?.quantity_in_store === 0) {
                        const description = article?.technical_specifications ? article?.technical_specifications : "";
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'The Store '
                                + $("#store_name").val()
                                + ' does not have '
                                + article?.id
                                + ' - ' + description + ' in stock. ' +
                                'You may have to wait until the stock is received before your request can be processed'
                        });
                    }
                }
                //$(row).find('[name="quantity"]').attr('max', article['quantity_in_store']);
                $(row).find('[name="articleCode"]').val(article['id']);
                $(row).find('[name="unit_price"]').val(article['price_map']);
                $(row).find('[name="technical_specification"]').val(article['technical_specifications']);
                $(row).find('[name="unit_of_measure"]').val(article['unit_measure_name']);
            });
        }

        function initServiceArticleSelector(element) {
            const dataUrl = document.querySelector('#articlesUrl').value;

            // don't re-initialize
            if (element.length === 0) {
                return;
            }
            let hasAttribute = element[0].hasAttribute('data-select2-id="1"');
            console.log(hasAttribute);
            if (hasAttribute) {
                return;
            }

            element.select2({
                selectOnClose: true,
                multiple: false,
                quietMillis: 100,
                id: function (project) {
                    return project['code_article'];
                },
                theme: 'bootstrap4',
                ajax: {
                    delay: 250,
                    beforeSend: function () {
                        window.showLoaderModal(false);
                        window.loaderVisible = false;
                    },
                    url: dataUrl,
                    dataType: 'json',
                    data: function (params) {
                        return {
                            search: params.term, // search term
                            type_article: document.querySelector('#serviceItemType').value,
                            supplier_code: document.querySelector('#service_supplier').value,
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;

                        return {
                            results: formatResults(data.items),
                            pagination: {
                                more: (params.page * 30) < data['total_count']
                            }
                        };
                    },
                    cache: true
                },
                placeholder: 'Enter Article name or Code',
                minimumInputLength: 3,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection
            }).off('select2:select').on('select2:select', function (e) {
                let article = e.params['data'];
                const row = $(e.currentTarget).closest('tr');

                $(row).find('[name="serviceArticleCode"]').val(article['id']);
                $(row).find('[name="service_unit_price"]').val(article['price_map']);
                $(row).find('[name="service_technical_specification"]').val(article['technical_specifications']);
                $(row).find('[name="service_unit_of_measure"]').val(article['unit_measure_name']);
            });
        }

        function formatRepo(project) {
            if (project.loading)
                return project.text;
            return $('<option value="' + project['id'] + '">' + project['text'] + '</option>');
        }

        function formatRepoSelection(project) {
            if (!project['id']) {
                return project['text'];
            }
            return project['description'];
        }

        function formatResults(items) {
            return $.map(items, function (obj) {
                return {
                    "id": obj['code_article'],
                    "text": obj['code_article'] + ':' + obj.description,
                    'code_article': obj?.code_article,
                    'description': obj?.description,
                    'price_map': obj?.price,
                    'technical_specifications': obj?.technical_specifications,
                    'unit_measure': obj?.unit_measure,
                    'unit_measure_code': obj?.unit_measure,
                    'unit_measure_name': obj?.unit_measure_name,
                    'quantity_in_store': obj?.quantity_in_store
                };
            });
        }

        function getArticleDetails(code_article, selectElem) {

            fetch(document.querySelector('#articleDetailsUrl').value + "?code_article=" + code_article)
                .then(response => response.json())
                .then(response => {
                    let result = response['payload'];
                    if (result.success === 'failure') {
                        // show errors
                        toastr.error('Connection error, no data found')
                        return;
                    }

                    console.log(result);

                    let data = {
                        "id": result['code_article'],
                        "text": result['code_article'] + ':' + result.description,
                        'code_article': result?.code_article,
                        'description': result?.description,
                        'price_map': result?.price,
                        'technical_specifications': result?.technical_specifications,
                        'unit_measure': result?.unit_measure,
                        'unit_measure_name': result?.unit_measure_name
                    };

                    let option = new Option(data.text, data.id, true, true);
                    selectElem.append(option).trigger('change');

                    // manually trigger the `select2:select` event
                    selectElem.trigger({
                        type: 'select2:select',
                        params: {
                            data: data
                        }
                    });

                    /*
                    let data = {
                        "id": id,
                        "text": text,
                        'code_article': response?.code_article,
                        'description': response?.description,
                        'price_map': response?.price,
                        'technical_specifications': response?.technical_specifications,
                        'unit_measure': response?.unit_measure,
                        'unit_measure_name': response?.unit_measure_name
                    };


                    // $(selectElem).closest('tr').find('')

                    let option = new Option(text, id, true, true);
                    selectElem[0].append(option);
                    $(selectElem).trigger('change');

                    // manually trigger the `select2:select` event
                    $(selectElem).trigger({
                        type: 'select2:select',
                        params: {
                            data: data
                        }
                    });

                    $(selectElem).val(id).trigger('change')
                    */

                    /*
                    let workshops = response['payload'];
                    tmsApp.populateDropDownList(selectElem, workshops, "code", ["name"]);

                    let location = selectElem.attr('data-value');
                    console.log(location);
                    if (location) {
                        selectElem.val(location);
                        selectElem.trigger('change');
                    }
                    */
                })
                .catch(function (error) {
                    // notify of error
                    console.log(error);
                    toastr.error('Connection error. Could not retrieve data, some feature might not work.')
                });
        }

        $(document).ready(function () {

            initArticleSelector($('.articlesDropDownList'));

            initServiceArticleSelector($('.servicesArticlesDropDownList'));

            Inputmask({
                "mask": "AAA 9{1,4}"
            }).mask('[name="vehicle_registration"]');

            $.fn.disableBtn = function () {
                return this.each(function () {
                    $(this).addClass("disabled").attr("disabled", true)
                })
            }

            $.fn.enableBtn = function () {
                return this.each(function () {
                    let $this = $(this);
                    $this.removeClass("disabled").attr("disabled", false)
                })
            }
        });

        (function (tmsApp, $) {

            function adjustIframeHeight() {
                /* var $body   = $('body'),
                     $iframe = $body.data('iframe.fv');
                 if ($iframe) {
                     // Adjust the height of iframe
                     $iframe.height($body.height());
                 }*/
            }

            let form = $('#jobCardForm').show();
            window.goToNext = false;
            let bodyTag = "section";

            $(document).ready(function () {
                setTimeout(function () {
                    let job_card_number = $('[name="job_card_number"]').val();

                    if (job_card_number) {
                        const elem = $("#repairTypeDropdownList");
                        let val = elem.attr('data-value');
                        if (val) {
                            elem.val(val);
                            elem.trigger('change');
                        }
                    }

                    if (window['selectedAccessories']) {
                        // setSelectedAccessories();
                    }

                    if (window['defects']) {
                        // dataFiler();
                    }

                    if (window['materials']) {
                        prefillSelectedMaterials();
                        $('[name="quantity"]').change();
                    }

                    //findDriver();

                    findVehicle("InWorkshop");

                }, 600);
            });

            /*****************************Function Handlers************************************/
            function initializeFormWizard() {

                function postData(formElements, submitForm) {
                    window.loaderMessage = "Posting Data... please wait";
                    let $container = $(formElements);

                    let formSel = $(formElements);

                    let formData = {
                        modelName: formSel.data('modelName'),
                        submitForm: submitForm
                    };

                    let arr = [];
                    let obj = {};

                    if (
                        formSel.data('modelName') === 'Defects'
                        || formSel.data('modelName') === 'PartsHeader'
                        || formSel.data('modelName') === 'ServicesHeader'
                    ) {
                        $(formElements).find("tbody").children().map(function (index, row) {
                            let obj = {};
                            $(row).find('input[name], select[name]').each(function (i, item) {
                                let val = item.value.replace(/,/g, '');

                                if (item.name === 'endDate' || item.name === 'startDate' || item.name === 'invoiceDate') {
                                    let dateField = val;
                                    dateField = DateFormatter.format(new Date(moment(val, 'DD/MM/yyyy')), DateFormatter.ISO);

                                    obj[item.name] = dateField;
                                } else {
                                    obj[item.name] = item.value;
                                }
                            });

                            arr.push(obj);
                        });

                        obj['workshop_reference'] = $('input[name="workshop_reference"]').val();
                        // obj['workshop_reference'] = $('input[name="workshop_reference"]').val();
                        // obj['workshop_reference'] = $('input[name="workshop_reference"]').val();

                        if (formSel.data('modelName') === 'Defects') {
                            obj['job_card_no'] = $('input[name="job_card_voucher"]').val();
                            obj['vehicle_registration'] = $('input[name="vehicle_registration"]').val();
                            obj['remarks'] = $('#remarks').val();
                        } else if (formSel.data('modelName') === 'PartsHeader') {
                            obj['itemType'] = $('[name="itemType"]').val();
                            obj['job_card_no'] = $('[name="job_card_number"]').val();
                            obj['purchase_office'] = $('[name="purchase_office"]').val();
                            obj['workshop_code'] = $('[name="workshop_code"]').val();
                            obj['request_date'] = $('[name="request_date"]').val()?.trim();
                            obj['date_expected'] = $('[name="date_expected"]').val()?.trim();
                            obj['supplier'] = $('[name="supplier"]').val();
                            obj['store_code'] = $('[name="store_code"]').val();
                            obj['store_name'] = $('[name="store_name"]').val();
                            obj['remarks'] = $('#comments').val();
                            obj['total_amount'] = $('#itemsTotal').text();
                            obj['vehicle_registration'] = $('input[name="vehicle_registration"]').val();
                        } else if (formSel.data('modelName') === 'ServicesHeader') {
                            obj['itemType'] = $('[name="serviceItemType"]').val();
                            obj['job_card_no'] = $('[name="job_card_number"]').val();
                            obj['purchase_office'] = $('[name="purchase_office"]').val();
                            obj['workshop_code'] = $('[name="workshop_code"]').val();
                            obj['request_date'] = $('[name="request_date"]').val()?.trim();
                            obj['date_expected'] = $('[name="date_expected"]').val()?.trim();
                            obj['supplier'] = $('[name="service_supplier"]').val();
                            obj['store_code'] = '';
                            // $('[name="store_code"]').val();
                            obj['store_name'] = $('[name="store_name"]').val();
                            obj['remarks'] = $('#service_comments').val();
                            obj['total_amount'] = $('#serviceTotalPrice').text();
                            obj['vehicle_registration'] = $('input[name="vehicle_registration"]').val();
                        }
                    } else {
                        $($container).find('input[name], select[name]').each(function (i, item) {
                            // let val = item.value.replace(/,/g, '');

                            if (item.type === 'radio') {
                                obj[item.name] = $('[name="' + item.name + '"]:checked').val();
                            } else {
                                obj[item.name] = item.value;
                            }
                        });
                    }

                    formData['items'] = arr;

                    formData = {
                        ...obj,
                        ...formData
                    }

                    $.ajax({
                        type: "POST",
                        url: formSel.data('formUrl'),
                        data: JSON.stringify(formData),
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                    }).done(function (response) {
                        window.loaderMessage = "Loading... please wait";
                        if (response.hasOwnProperty("success") && response.success) {
                            const message = response.message > ""
                                ? response.message
                                : "Request submitted successfully, Click 'Ok' Proceed to provide information for other sections";

                            tmsApp.showSystemMessage(
                                "Request Submission",
                                message,
                                function () {
                                    if (submitForm) {
                                        window.location.href = response['redirectUrl'];
                                        return;
                                    }

                                    if (window.global_currentIndex === 2) {
                                        window.goToNext = true;
                                        form.steps("next");
                                    } else {
                                        window.location.href = response['redirectUrl'];
                                    }
                                },
                                "success"
                            );
                        } else {
                            if (!Util.isEmpty(response.errors)) {
                                if (response.errors) {
                                    tmsApp.printErrorMsg(response.errors);
                                }
                            } else if (!Util.isEmpty(response.message)) {
                                tmsApp.systemError("Request Submission", response.message);
                            }
                        }
                    }).fail(function (xhr) {
                        tmsApp.showErrorMessages(xhr, "Request Submission");
                    })
                }

                let stepId = window.step_id || 1;
                window.global_currentIndex = stepId - 1;
                form.steps({
                    showStepURLhash: true,
                    headerTag: "h1",
                    bodyTag: "section",
                    transitionEffect: "slideLeft",
                    autoFocus: true,
                    saveState: true,
                    startIndex: stepId - 1,
                    labels: {
                        finish: 'Submit'
                    },
                    onInit: function () {
                        console.log('Wizard Initializing')
                    },
                    onStepChanging: function (event, currentIndex, newIndex) {

                        if (currentIndex > newIndex) {
                            return true;
                        }

                        if (currentIndex < newIndex) {
                            // To remove error styles
                            form.find(".body:eq(" + newIndex + ") label.error").remove();
                            form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                        }

                        form.validate().settings.ignore = ":disabled,:hidden";
                        window.global_currentIndex = currentIndex;
                        if (form.valid() && !window.goToNext) {
                            tmsApp.confirm('Confirm', 'Do you want to save the changes ?', 'Yes', 'No', function () {
                                postData(form.find('[data-model-name]').get(currentIndex), false);
                            }, function () {
                            });
                        }

                        let tmp = window.goToNext;
                        window.goToNext = false;
                        return tmp;

                    },
                    onStepChanged: function (event, currentIndex, priorIndex) {

                        if (currentIndex === 2 && priorIndex === 3) {
                            //form.steps("previous");
                            $('ul[aria-label="Pagination"]').find('a[href="#finish"]').removeClass('d-none');
                        }
                        adjustIframeHeight();
                        //$('ul[aria-label="Pagination"]').find('a[data-action="skip"]').removeClass('d-none');
                        window.global_currentIndex = currentIndex;
                        if (currentIndex === 3) {
                            $('ul[aria-label="Pagination"]').find('a[href="#finish"]').addClass('d-none');
                        }
                        window.goToNext = false;

                    },
                    onFinishing: function (event, currentIndex) {
                        form.validate().settings.ignore = ":disabled,:hidden";
                        return form.valid();
                    },
                    onFinished: function () {

                        $('a[href="#finish"]').disableBtn();

                        if (form.valid()) {
                            tmsApp.confirm(
                                'Confirm',
                                'Do you want to save the changes ?',
                                'Yes',
                                'No',
                                function () {
                                    postData(
                                        $(form.find(bodyTag).get(window.global_currentIndex))
                                            .find('[data-model-name]').get(0),
                                        true
                                    );
                                },
                                function () {
                                }
                            );
                        } else {
                            //$('a[role="#finish"]').enableBtn();
                            //swal("Error !", "You may have some missing data for the return, Kindly review your submission", "error");
                        }

                    },
                }).validate(
                    {
                        errorClass: "error-class",
                        validClass: "valid-class",
                        errorElement: 'div',
                        errorPlacement: function (error, element) {
                            if (element.parent('.input-group').length) {
                                error.insertAfter(element.parent());
                            } else {
                                error.insertAfter(element);
                            }
                        },
                        onError: function () {
                            $('.input-group.error-class').find('.help-block.form-error').each(function () {
                                $(this).closest('.form-group').addClass('error-class').append($(this));
                            });
                        },
                        rules: {
                            vehicle_registration: {
                                required: true
                            },
                            workshop: {
                                required: true
                            }
                        },
                        messages: {
                            workshop: {
                                required: "Select the workshop vehicle is being checked-into"
                            },
                            vehicle_registration: {
                                required: "Vehicle Registration is required"
                            },

                            current_odometer: {
                                required: "Enter current odometer reading"
                            },
                            repairType: {
                                required: "Select type of repair"
                            },
                            driver_staff_number: {
                                required: "Driver details are required"
                            }
                        }
                    }
                );

                $(document).on('click', '#saveMaterials', function () {
                    // $('a[href="#finish"]').disableBtn();
                    if (form.valid()) {
                        tmsApp.confirm('Confirm', 'Do you want to save the changes ?', 'Yes', 'No', function () {
                            postData(
                                $('#material_table'),
                                true
                            );
                        }, function () {
                        });
                    }
                });

                $(document).on('click', '#saveServices', function () {
                    // $('a[href="#finish"]').disableBtn();
                    if (form.valid()) {
                        tmsApp.confirm('Confirm', 'Do you want to save the changes ?', 'Yes', 'No', function () {

                            postData(
                                $('#services_table'),
                                true
                            );

                        }, function () {
                        });
                    }
                });
            }

            function getWorkshops() {
                fetch(document.querySelector('#workshopsUrl').value)
                    .then(response => response.json())
                    .then(response => {
                        let selectElem = $('select[name="workshop"]');
                        // Populate results
                        if (response.state === 'failure') {
                            //show errors
                            toastr.error('Connection error, no data found')
                            return;
                        }

                        let workshops = response['payload'];
                        tmsApp.populateDropDownList(selectElem, workshops, "workshop_code", ["workshop_name"], "");

                        let location = selectElem.attr('data-value');

                        if (location) {
                            selectElem.val(location);
                            selectElem.trigger('change');
                        }

                    })
                    .catch(function (error) {
                        // notify of error
                        toastr.error(
                            'Connection error. Could not retrieve data, some feature might not work.')
                    });
            }

            /*function getWorkshopSections() {
                fetch(document.querySelector('#workShopSectionsUrl').value)
                    .then(response => response.json())
                    .then(response => {
                        let selectElem = $('select[name="workshopSection"]');
                        // Populate results
                        if (response.state === 'failure') {
                            //show errors
                            toastr.error('Connection error, no data found')
                            return;
                        }

                        let workshops = response['payload'];
                        tmsApp.populateDropDownList(selectElem, workshops, "code", ["name"]);

                        let location = selectElem.attr('data-value');
                        console.log(location);
                        if (location) {
                            selectElem.val(location);
                            selectElem.trigger('change');
                        }

                    })
                    .catch(function (error) {
                        // notify of error
                        toastr.error(
                            'Connection error. Could not retrieve data, some feature might not work.')
                    });
            }*/

            function getFuelLevels() {
                fetch(document.querySelector('#fuelLevelsUrl').value)
                    .then(response => response.json())
                    .then(response => {
                        let selectElem = $('select[name="fuel_level"]');

                        if (response.state === 'failure') {
                            //show errors
                            toastr.error('Connection error, no data found')
                            return;
                        }

                        let fuelLevels = response['payload'];
                        tmsApp.populateDropDownList(selectElem, fuelLevels, "code", ["name"], "");

                        let location = selectElem.attr('data-value');

                        if (location) {
                            selectElem.val(location);
                            selectElem.trigger('change');
                        }
                    })
                    .catch(function (error) {
                        // notify of error
                        toastr.error(
                            'Connection error. Could not retrieve data, some feature might not work.')
                    });
            }

            /*function loadData(key, url, selectElem) {
                fetch(url)
                    .then(response => response.json())
                    .then(response => {

                        if (response.state === 'failure') {
                            toastr.error('Connection error, no data found')
                            return;
                        }

                        let fuelLevels = response['payload'];
                        tmsApp.populateDropDownList(selectElem, fuelLevels, "code", ["description"], "");

                        let location = selectElem.attr('data-value');

                        if (location) {
                            selectElem.val(location);
                            selectElem.trigger('change');
                        }
                    })
                    .catch(function (error) {
                        toastr.error(
                            'Connection error. Could not retrieve data, some feature might not work.')
                    });
            }*/

            function removeSubmissionAndDetailsOptions() {
                let elements = document.querySelectorAll('.when_valid');
                elements.forEach(function (element) {
                    element.setAttribute('disabled', 'disabled');
                });

                document.querySelector('#image_view').style.display = 'none';

                $('tbody#vehicleDetails').html('');
            }

            function enableWebUIControls() {

                let elements = document.querySelectorAll('.when_valid');

                elements.forEach(function (element) {
                    element.removeAttribute('disabled');
                });

                document.querySelector('#vehicleDetailsContainer').style.display = null;
                document.querySelector('#image_view').style.display = null;
            }

            function enableArticleSelectionWebUIControls() {
                let elements = document.querySelectorAll('.articlesDropDownList');
                elements.forEach(function (element) {
                    element.removeAttribute('disabled');
                });
            }

            function populateVehicleDetails(payload, state) {
                let vehicle = payload['vehicle'];
                let images = payload['images'];
                let vehicle_state = payload['vehicle_state'];

                if (!vehicle || !vehicle.brand_name) {
                    return;
                }

                // BAD 1010
                if (state !== 'InWorkshop') {
                    if (vehicle['status'] !== document.querySelector('[name="vehicleActive"]').value) {
                        tmsApp.showSystemMessage("Vehicle State",
                            vehicle_state,
                            () => {
                            },
                            "error");
                        return;
                    }
                }

                let vLabel = vehicle['body_type_name'] + ' ' + vehicle['brand_name'] + ' ' + vehicle['model_name'] + ' ' + vehicle['model_code'];
                $("#vehicle_description").val(vLabel);
                let row = `<tr><th>Make</th><td id="make">${vehicle['brand_name']}</td></tr>
                               <tr>
                                    <th>Model</th><td id="model">${vehicle['model_name']} ${vehicle['model_code']}</td>
                               </tr>
                               <tr style="">
                                     <th>Type</th><td id="registration">${vehicle['body_type_name']}</td>
                                </tr>
                                <tr style="">
                                     <th>State:</th><td id="registration">${vehicle['status_name']}</td>
                                </tr>`;

                $('tbody#vehicleDetails').html(row);

                enableWebUIControls();

                if (images && images.length > 0) {
                    let frontViewImages = images.filter((image) => {
                        return image['file_type'] === 'Front View';
                    })
                    let imagePath = frontViewImages[0]?.path;
                    document.querySelector(".imagePreview").style.backgroundImage = "url(/storage" + imagePath + ")";
                }

            }

            function findVehicle(stage) {
                const numberPlate = document.querySelector('#vehicle_registration').value;
                if (!numberPlate) {
                    return;
                }

                let formData = new FormData();
                formData.append('vehicle_registration', numberPlate);

                tmsApp.asyncGetFormData(
                    $('#vehicle_registration').attr('data-action') + '?vehicle_registration=' + numberPlate,
                    formData,
                    function (response_data) {
                        if (response_data.success === 'true' || response_data.success === true) {
                            populateVehicleDetails(response_data.payload, stage);
                        } else {
                            removeSubmissionAndDetailsOptions();
                            tmsApp.systemError(
                                'Vehicle',
                                'Vehicle with Registration No.' + numberPlate
                                + ' was not found, Check your input and try again',
                                function () {
                                });
                        }
                    },
                    function (xhr) {
                        tmsApp.systemError(
                            'System Message',
                            'We could not complete processing your request, please try again later',
                            function () {
                            });
                    }
                )
            }

            /*Find driver*/
            function eventHandler(element, e) {

                switch (element.name) {
                    case 'quantity':
                        let summaryTotalQty = 0;
                        $(element).closest("table").find("input[name=quantity]").each(function (i, it) {
                            summaryTotalQty += Util.getFloat(it.value);
                        });

                        // set value in footer
                        $('#quantityTotal').text(tmsApp.getRawNumber(summaryTotalQty));

                        let lineAmountTotal = tmsApp.getFloat(element.value) * tmsApp.getFloat($(element).closest("tr").find("input[name=unit_price]").val());
                        $(element).closest("tr").find("input[name=total_price]").val(lineAmountTotal).change();
                        $(element).closest("tr").find("#total_price").text(tmsApp.numberFormat(lineAmountTotal));
                        break;

                    case 'service_quantity':
                        let serviceSummaryTotalQty = 0;
                        $(element).closest("table").find("input[name=service_quantity]").each(function (i, it) {
                            serviceSummaryTotalQty += Util.getFloat(it.value);
                        });

                        // set value in footer
                        $('#serviceQuantityTotal').text(tmsApp.getRawNumber(serviceSummaryTotalQty));

                        let serviceLineAmountTotal = tmsApp.getFloat(element.value) * tmsApp.getFloat($(element).closest("tr").find("input[name=service_unit_price]").val());
                        $(element).closest("tr").find("input[name=service_total_price]").val(serviceLineAmountTotal);//.change();
                        $(element).closest("tr").find("#total_price").text(tmsApp.numberFormat(serviceLineAmountTotal));
                        break;

                    case 'unit_price':
                        // line total = new material price multiplied by quantity value
                        let totalAmount = tmsApp.getFloat(element.value) * tmsApp.getFloat($(element).closest("tr").find("input[name=quantity]").val());
                        $(element).closest("tr").find("input[name=total_price]").val(totalAmount).change();
                        $(element).closest("tr").find("#total_price").text(tmsApp.numberFormat(totalAmount));
                        break;

                    case 'service_unit_price':
                        let serviceTotalAmount = tmsApp.getFloat(element.value) * tmsApp.getFloat($(element).closest("tr").find("input[name=service_quantity]").val());
                        $(element).closest("tr").find("input[name=service_quantity]").change();
                        $(element).closest("tr").find("input[name=service_total_price]").val(serviceTotalAmount).change();
                        $(element).closest("tr").find("#service_total_price").text(tmsApp.numberFormat(serviceTotalAmount));
                        break;

                    case 'total_price':
                        // calculate new footer total
                        let summaryTotal = 0;
                        $(element).closest("table").find("input[name=total_price]").each(function (i, it) {
                            summaryTotal += tmsApp.getFloat(it.value);
                        });
                        $('#itemsTotal').text(tmsApp.numberFormat(summaryTotal, 2));
                        break;

                    case 'service_total_price':
                        // calculate new footer total
                        let serviceSummaryTotal = 0;
                        $(element).closest("table").find("input[name=service_total_price]").each(function (i, it) {
                            serviceSummaryTotal += tmsApp.getFloat(it.value);
                        });
                        $('#serviceTotalPrice').text(tmsApp.numberFormat(serviceSummaryTotal, 2));
                        break;

                    default:
                        break;
                }
            }

            function tableHasItems() {
                let inputs = $("#material_table > tbody").find('.articleCode');
                for (const input of inputs) {
                    if (input.value > "") {
                        return true;
                    }
                }
                return false;
            }

            function showSupplierControls() {
                document.querySelector('#supplierContainer').style.display = null;
                document.querySelector('[name="supplier"]').setAttribute('required', 'required');

                document.querySelector('#storeContainer').style.display = 'none';
                document.querySelector('[name="store_code"]').removeAttribute('required');
            }

            function showStockItemControls() {
                document.querySelector('#supplierContainer').style.display = 'none';
                document.querySelector('[name="supplier"]').removeAttribute('required');

                document.querySelector('#storeContainer').style.display = null;
                document.querySelector('[name="store_code"]').setAttribute('required', 'required');
            }

            function changeRequestType(selectedItemType) {

                if (document.querySelector('[name="stockItemCode"]').value == selectedItemType) {
                    showStockItemControls();
                    $('.quantity').attr('readonly', false);
                } else if (selectedItemType == document.querySelector('[name="serviceItemCode"]').value) {
                    showSupplierControls();
                    $('.quantity').attr('readonly', 'readonly');
                    $('.quantity').val(1);
                } else if (selectedItemType == document.querySelector('[name="nonStockItemCode"]').value) {
                    showSupplierControls();
                    $('.quantity').attr('readonly', false);
                    $('[name="unit_price"]').attr('readonly', false);
                } else {
                    showSupplierControls();
                    $('.quantity').attr('readonly', false);
                }

                if (selectedItemType) {
                    enableArticleSelectionWebUIControls();
                }
            }

            function clearRows(table) {
                if (table.attr('id') === 'services_table') {
                    const regNo = $('[name="vehicle_reg_no"]').val();
                    $(table).find('[name="vehicle_registration"]').val(regNo);
                }
            }

            function insertTableRow(tableId) {
                function reinitializeSelect2($_defect_sel) {
                    if ($_defect_sel) {
                        $($_defect_sel).removeClass('select2-hidden-accessible');
                        $($_defect_sel).select2({
                            theme: "bootstrap4",
                            width: "resolve",
                        });
                    }
                }

                if (tableId === "part8") {
                    if ($('.select_2_control').data('select2')) {
                        $('.select_2_control').select2('destroy');
                    }
                }

                //Table.addRow();

                $('table#' + tableId).find('tbody').append(table);

                let lastRow = $('table#' + tableId).find('tbody tr').eq((0 + 1) * -1);

                lastRow.find('button[value="deleteRow"]').attr('data-value', 0);

                if (tableId === "material_table") {
                    lastRow.find('[name="technical_specification"]').val('').attr('readonly', false);
                    lastRow.find('[name="quantity"]').val('').attr('readonly', false);
                    lastRow.find('[name="articles"]').attr('readonly', false);
                    lastRow.find('[name="unit_of_measure"]').val('');
                    lastRow.find('[name="unit_price"]').val('');
                    lastRow.find('[name="total_price"]').val('');

                    lastRow.find('#unit_price').text('');
                }

                if (tableId === "services_table") {
                    // let row = lastRow[0];
                    // $(row).find('.select2-container').remove();
                    // $(row).find('.articlesDropDownList').removeClass('select2-hidden-accessible');

                    lastRow.find('[name="service_article"]').val('');
                    // lastRow.find('[name="service_article"]')
                    lastRow.find('[name="serviceArticleCode"]').val('');
                    lastRow.find('[name="service_technical_specification"]').val('');
                    lastRow.find('[name="service_unit_price"]').val('');
                    lastRow.find('[name="service_unit_of_measure"]').val('');
                    lastRow.find('[name="service_total_price"]').val('');
                    // initServiceArticleSelector('.')
                } else {

                }

                if (tableId === "part8") {
                    let row = lastRow[0];
                    $(row).find('.select2-container').remove();
                    let $_defect_sel = $(".select_2_control");
                    reinitializeSelect2($_defect_sel);
                }

                if (tableId === "material_table") {
                    let row = lastRow[0];
                    $(row).find('.select2-container').remove();
                    $(row).find('.articlesDropDownList').removeClass('select2-hidden-accessible');

                    let article = $(row).find('input.articleCode').val();
                    console.log('Article on line', article)
                    let $_defect_sel = $(row).find(".articlesDropDownList");
                    let $_defect_sel_ = $(row).find(".DropDownList");
                    initArticleSelector($_defect_sel);
                    initArticleSelector($_defect_sel_);
                    //getArticleDetails(article, $_defect_sel);
                }
            }

            function addTableRow(tableId) {
                function reinitializeSelect2($_defect_sel) {
                    if ($_defect_sel) {
                        $($_defect_sel).removeClass('select2-hidden-accessible');
                        $($_defect_sel).select2({
                            theme: "bootstrap4",
                            width: "resolve",
                        });
                    }
                }

                if (tableId === "part8") {
                    if ($('.select_2_control').data('select2')) {
                        $('.select_2_control').select2('destroy');
                    }
                }

                Table.addRow($('table#' + tableId));
                let lastRow = $('table#' + tableId).find('tbody tr').eq((0 + 1) * -1);

                lastRow.find('button[value="deleteRow"]').attr('data-value', 0);

                if (tableId === "material_table") {
                    lastRow.find('[name="technical_specification"]').val('').attr('readonly', false);
                    lastRow.find('[name="quantity"]').val('').attr('readonly', false);
                    lastRow.find('[name="articles"]').attr('readonly', false);
                    lastRow.find('[name="unit_of_measure"]').val('');
                    lastRow.find('[name="unit_price"]').val('');
                    lastRow.find('[name="total_price"]').val('');

                    lastRow.find('#unit_price').text('');
                }

                if (tableId === "services_table") {
                    // let row = lastRow[0];
                    // $(row).find('.select2-container').remove();
                    // $(row).find('.articlesDropDownList').removeClass('select2-hidden-accessible');

                    lastRow.find('[name="service_article"]').val('');
                    // lastRow.find('[name="service_article"]')
                    lastRow.find('[name="serviceArticleCode"]').val('');
                    lastRow.find('[name="service_technical_specification"]').val('');
                    lastRow.find('[name="service_unit_price"]').val('');
                    lastRow.find('[name="service_unit_of_measure"]').val('');
                    lastRow.find('[name="service_total_price"]').val('');
                    // initServiceArticleSelector('.')
                } else {

                }

                if (tableId === "part8") {
                    let row = lastRow[0];
                    $(row).find('.select2-container').remove();
                    let $_defect_sel = $(".select_2_control");
                    reinitializeSelect2($_defect_sel);
                }

                if (tableId === "material_table") {
                    let row = lastRow[0];
                    $(row).find('.select2-container').remove();
                    $(row).find('.articlesDropDownList').removeClass('select2-hidden-accessible');

                    let article = $(row).find('input.articleCode').val();
                    console.log('Article on line', article)
                    let $_defect_sel = $(row).find(".articlesDropDownList");
                    let $_defect_sel_ = $(row).find(".DropDownList");
                    initArticleSelector($_defect_sel);
                    initArticleSelector($_defect_sel_);
                    //getArticleDetails(article, $_defect_sel);
                }
            }

            function deleteTableRow(eventSource) {

                let btnEl = $(eventSource);
                let tableId = $(this).closest('table').attr('id');
                let valueId = $(this).attr('data-value');
                let tableRow = btnEl.closest('tr');
                let table = btnEl.closest('table');

                tmsApp.confirm(
                    "Are you sure ?",
                    "The data entered on this line will be cleared out, if not saved already, you will not be able to recover it",
                    "Yes",
                    "No",
                    function () {

                        Table.deleteRow(tableRow);

                        if (!valueId || valueId === "0") {
                            // clear first row
                            if (tableId === 'services_table') {
                                const regNo = $('[name="vehicle_reg_no"]').val();
                                $(table).find('[name="vehicle_registration"]').val(regNo);
                            }

                            return;
                        }


                        let dataUrl = "";
                        if (tableId === 'part8') {
                            dataUrl = document.querySelector('[name="deleteDefectUrl"]').value;
                        } else {
                            dataUrl = document.querySelector('[name="deleteMaterialUrl"]').value;
                        }

                        let formData = new FormData();
                        formData.append('record_id', valueId);

                        tmsApp.asyncPostFormData(
                            dataUrl,
                            formData,
                            function (asyncResponse) {
                                if ('success' in asyncResponse && !asyncResponse.success) {
                                    if (asyncResponse.hasOwnProperty('errors')) {
                                        toastr.error(
                                            asyncResponse.message
                                        );
                                        tmsApp.printErrorMsg(asyncResponse.errors);
                                        return
                                    }

                                    setTimeout(function () {
                                            tmsApp.systemError(
                                                'System Configuration',
                                                asyncResponse['message'],
                                                function () {
                                                }, 'error');
                                        },
                                        300);
                                    return;
                                }

                                if (asyncResponse.success) {
                                    const entry = asyncResponse.payload;
                                    tmsApp.showSystemMessage(
                                        'System Configuration',
                                        asyncResponse['message'],
                                        function () {
                                            clearRows(table);
                                        },
                                        'success'
                                    );
                                }
                            },
                            function (xhr, settings, error) {
                                setTimeout(
                                    function () {
                                        tmsApp.showErrorMessages(xhr, 'System Configuration');
                                    },
                                    300);
                            },
                            'POST',
                        )
                    });
            }

            function initEventHandlers() {

                $("#itemType").on('change', function () {
                    const selectedItemType = this.value;

                    if (tableHasItems()) {
                        Swal.fire({
                            title: 'Change Requisition Item Type',
                            text: "Changing Item Type will clear the items you've selected already." +
                                " Would you like to proceed ?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // clear things here
                                changeRequestType(selectedItemType);
                            }
                        });
                        return;
                    }

                    changeRequestType(selectedItemType);
                });

                /*$(document).on('change', 'select[name="vehicleSystem"]', function () {
                    if (!this.value) return;
                    const tr = $(this).closest('tr');
                    let selectElem = tr.find('select[name="defectCategory"]');
                    getVehicleDefectCategory(this.value, selectElem);
                });*/

                /*$(document).on('change', 'select[name="defectCategory"]', function () {
                    if (!this.value) return;
                    const tr = $(this).closest('tr');
                    let selectElem = tr.find('select[name="defect"]');
                    getVehicleDefects(this.value, selectElem);
                })*/

                $(document).on('keyup paste', '[name="vehicle_registration"]', function () {
                    if (!this.value || this.value.replace('_', '').length < 4) {
                        return;
                    }
                    removeSubmissionAndDetailsOptions();
                    findVehicle();
                });

                $(document).on('click', '#vehicleSearchBtn', function () {
                    if (!document.querySelector('[name="vehicle_registration"]').value) {
                        return;
                    }
                    removeSubmissionAndDetailsOptions();
                    findVehicle();
                });

                /*setTimeout(function () {
                    $(document).on('keyup paste', '#driver_staff_number', function () {
                        if (!this.value) {
                            return;
                        }
                        if (this.value.length < 5) {
                            return;
                        }

                        findDriver();
                    });
                }, 300);*/

                /*setTimeout(function () {
                    $(document).on('click', '#employeeSearchBtn', function () {
                        if (!document.querySelector("#driver_staff_number").value
                            || document.querySelector("#driver_staff_number").value.length < 5) {
                            toastr.warning('Invalid Employee Id Number')
                            return;
                        }

                        findDriver();
                    });
                }, 300);*/

                /*****************************Event Handlers*****************************************/

                $(document).on('keypress', '.number_input', function (event) {
                    tmsApp.numberOnly(event);
                });

                $(document).on('keyup', '.comments', function (event) {
                    this.value = this.value.toUpperCase();
                });

                $(document).on('keyup', '[name="remarks"]', function (event) {
                    this.value = this.value.toUpperCase();
                });

                $(document).on('keyup', '.technical_specification', function (event) {
                    this.value = this.value.toUpperCase();
                });

                $(document).on('click', '#submitRequisitionBtn', function () {
                    let $form = document.forms['fuelRequisitionForm'];
                    if (!$($form).valid()) {
                        return;
                    }

                    $('.print-error-msg').css('display', 'none');
                    let formData = new FormData($form);
                    tmsApp.confirm(
                        'Fuel Requisition',
                        'Are you sure you want to submit this request ?',
                        'Yes',
                        'No',
                        function () {
                            window.top.tmsApp.asyncPostFormData(
                                $form.action,
                                formData,
                                function (asyncResponse) {

                                    if (asyncResponse.hasOwnProperty('success') && asyncResponse['success']) {
                                        setTimeout(function () {
                                            tmsApp.showSystemMessage(
                                                'Fuel Requisition',
                                                asyncResponse['message'],
                                                function () {
                                                    window.location.href = asyncResponse["redirectUrl"]
                                                },
                                                'success'
                                            );
                                        }, 300);
                                    } else {
                                        if (asyncResponse.hasOwnProperty('errors')) {
                                            tmsApp.printErrorMsg(asyncResponse.errors);
                                            return
                                        }
                                        setTimeout(function () {
                                            tmsApp.systemError(
                                                'Fuel Requisition',
                                                asyncResponse['message'],
                                                function () {
                                                }, 'error');
                                        }, 300);
                                    }
                                },
                                function (xhr, settings, errorThrown) {
                                    console.log(errorThrown)
                                    setTimeout(function () {
                                        if ('responseJSON' in xhr) {
                                            if (xhr.responseJSON.hasOwnProperty('errors')) {
                                                tmsApp.printErrorMsg(xhr.responseJSON.errors);
                                            }
                                            if (xhr.responseJSON.hasOwnProperty('message')) {
                                                tmsApp.systemError(
                                                    'Fuel Requisition',
                                                    xhr.responseJSON['message']
                                                );
                                            }
                                            return;
                                        }

                                        tmsApp.systemError(
                                            'Fuel Requisition',
                                            'We could not complete processing your request, please try again later');
                                    }, 300)
                                }
                            )
                        }
                    );
                })

                /*$(document).on('change', '#repairTypeDropdownList', function () {
                    if (this.value === document.querySelector('[name="accidentRepairs"]').value) {
                        document.querySelector("#accidentRecordNo").classList.remove('d-none');
                    } else {
                        document.querySelector("#accidentRecordNo").classList.add('d-none');
                    }
                });*/

                $(document).on('change', 'input', function (e) {
                    eventHandler(this, e);
                }).on('keyup', 'input,textarea', function (e) {
                    eventHandler(this, e);
                });

                let tableRowTemplate = `<tr class="increment">
                                                        <td class="showNumber">
                                                            <input
                                                                readonly="readonly"
                                                                name="vehicle_registration"
                                                                required
                                                                value=""
                                                                class="form-control form-control-sm vehicle_registration"/>
                                                        </td>
                                                        <td>
                                                            <select
                                                                name="service_article"
                                                                required
                                                                data-value=""
                                                                class="form-control form-control-sm servicesArticlesDropDownList">
                                                                <option></option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input
                                                                name="serviceArticleCode"
                                                                required
                                                                readonly
                                                                class="form-control form-control-sm serviceArticleCode"/>
                                                        </td>
                                                        <td>
                                                            <input
                                                                name="service_technical_specification"
                                                                required
                                                                class="form-control form-control-sm service_technical_specification"/>
                                                        </td>

                                                        <td>
                                                            <input
                                                                readonly
                                                                type="text"
                                                                min="1"
                                                                value="1"
                                                                max="1"
                                                                name="service_quantity"
                                                                required
                                                                class="form-control form-control-sm service_quantity number_input"/>
                                                        </td>

                                                        <td>
                                                            <input
                                                                name="service_unit_of_measure"
                                                                required
                                                                readonly
                                                                class="form-control form-control-sm unit_of_measure"/>
                                                        </td>

                                                        <td>
                                                            <input name="service_unit_price"
                                                                   required
                                                                   class="form-control form-control-sm service_unit_price"/>
                                                        </td>

                                                        <td>
                                                            <input name="service_total_price"
                                                                   required
                                                                   readonly
                                                                   class="form-control form-control-sm service_total_price"/>
                                                        </td>

                                                        <td class="view-mode">
                                                            <button type="button"
                                                                    data-value="0"
                                                                    value="deleteRow"
                                                                    class="btn btn-danger p-2">
                                                                <i class="fas fa-trash m-0"></i>
                                                            </button>
                                                        </td>
                                                    </tr>`;

                $(document).off('click', 'button[value="addRow"][data-table-id]')
                    .on('click', 'button[value="addRow"][data-table-id]', function () {
                        let tableId = $(this).data('tableId');
                        addTableRow(tableId);
                    });

                $(document).off('click', 'button[value="addRow"][data-table-id]')
                    .on('click', 'button[value="addRow"][data-table-id]', function () {
                        let tableId = $(this).data('tableId');
                        addTableRow(tableId);
                    });

                $(document).on('click', 'button[value="deleteRow"]', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    deleteTableRow(this);
                    return false;
                });
            }

            function getSuppliers() {
                fetch(document.querySelector('#suppliersList').value)
                    .then(response => response.json())
                    .then(function (response) {
                        let selectElem = $('select[name="supplier"]');
                        let serviceSupplierElem = $('select[name="service_supplier"]');

                        if (response.state === 'failure') {

                            toastr.error('Failed to retrieve Supplier Records', 'Connection Error');
                            return;
                        }

                        let suppliers = response['payload'];
                        tmsApp.populateDropDownList(selectElem, suppliers,
                            "code_supplier", ["code_supplier", "name_of_supplier"],
                            " ==> ", '--Select Supplier--');

                        tmsApp.populateDropDownList(serviceSupplierElem, suppliers,
                            "code_supplier", ["code_supplier", "name_of_supplier"],
                            " ==> ", '--Select Supplier--');

                        let supplier = selectElem.attr('data-value');
                        if (supplier) {
                            selectElem.val(supplier);
                            selectElem.trigger('change');
                        }

                        let service_supplier = serviceSupplierElem.attr('data-value');
                        if (service_supplier) {
                            serviceSupplierElem.val(service_supplier);
                            serviceSupplierElem.trigger('change');
                        }
                    }).catch(function (error) {
                    toastr.error('Could not Retrieve Data, some feature might not work.', 'Connection error');
                });
            }

            getWorkshops();

            getFuelLevels();

            // loadData('VEH_SYS', document.querySelector('#systemsUrl').value + '?key=VEH_SYS', $('select[name="vehicleSystem"]'));

            initEventHandlers();

            function dataFiler() {

                $(document).find('.vehicleSystem').map(function (index, item) {
                    const value = item.getAttribute('data-value');
                    if (!value) {
                        return;
                    }
                    $(item).val(value).trigger('change')
                });
            }

            function prefillSelectedMaterials() {

                $(document).find('.articlesDropDownList').map(function (index, selectElem) {
                    const id = selectElem.getAttribute('data-value');
                    const text = selectElem.getAttribute('data-text');
                    if (!id) {
                        return;
                    }

                    getArticleDetails(id, selectElem);
                });
            }

            getSuppliers();

        })(window.tmsApp || {}, jQuery)
    </script>

@endpush

