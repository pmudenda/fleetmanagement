@php use App\Enums\RequisitionTypes;use Carbon\Carbon; @endphp
@extends('layouts.app')
@push('styles')
    <link href="{{asset("assets/plugins/select2/css/select2.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css")}}" rel="stylesheet"
          type="text/css"/>
    <style>
        .card .card-body {
            padding: 2rem 1rem !important;
        }
    </style>
@endpush
@section('content')

    <x-content-header/>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h4>APPROVE STORES REQUISITION</h4>
                </div>
                <div class="card-toolbar justify-content-end">
                    @if(!empty($requestDetails))
                        <span class="badge pl-2 {{$requestDetails->color_code ?? ''}}">
                       {{$requestDetails->status_name ?? ''}}
                   </span>
                    @endif
                </div>
            </div>

            <div class="card-body pb-4 min-h-600px pt-0">

                <x-error-view/>

                <form name="fuelRequisitionForm" id="fuelRequisitionForm" action="{{route('save.fuel.requisition')}}"
                      method="post">
                    @csrf
                    {{-- <div class="card-body user-data">--}}

                    <table
                        aria-label="header"
                        role="table"
                        style="width: 100%;
                        border: 1px;
                        text-align: center"
                        data-height="100px"
                        class="border-0">
                        <thead>
                        <tr class="border-0">
                            <th style="width:33%; border:none;" colspan="4" class="text-left">
                                @if(!empty($requestDetails)  && !empty($requestDetails->proc_ref))
                                    REQUISITION NUMBER: <span
                                        class="text-orange">
                                        {{ $requestDetails->proc_ref }}
                                    </span>
                                @endif
                            </th>
                            <th style="width:33%; border:none;"
                                colspan="4"
                                class="text-center">
                            </th>
                            <th colspan="1"
                                style="width:34%; border:none; text-align:right;"
                                class="p-3 text-right">
                                DOCUMENT REFERENCE NUMBER: <span
                                    class="text-orange">
                                    {{ $requestDetails->req_no }}
                                </span>
                            </th>
                        </tr>
                        </thead>
                    </table>
                    <table
                        aria-label="sub header"
                        role="table"
                        data-height="100px"
                        style="width: 100%;
                        border: 1px;
                        text-align: center"
                        class="mb-4 ">
                        <thead>
                        <tr class="border-success" style="border-width: 1px;">
                            <th style="width: 33%;" class="text-center"><a href="#">
                                    {{--<img src="{{ asset('assets/dist/img/zesco1.png') }}"
                                         title="ZESCO" alt="ZESCO"
                                         width="30%">--}}
                                </a>
                            </th>
                            <th style="width: 33%; font-size: 26px;" colspan="4" class="text-center">
                                FUEL REQUISITION
                            </th>
                            <th style="width: 34%;" colspan="1" class="p-3">
                            </th>
                        </tr>
                        </thead>
                    </table>

                    <label class="app-required-marker"></label>
                    <div class="container-fluid mt-2">
                        <div class="row">
                            <div class="col-9">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="container-fluid pl-0">
                                            <div class="row">
                                                <div class="form-group row">
                                                    <label
                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                        for="staff_no">Registration #:
                                                    </label>
                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                        <div class="input-group">
                                                            <input type="text"
                                                                   readonly
                                                                   class="form-control form-control-sm"
                                                                   value="{{$requestDetails->reg_no}}"
                                                                   autocapitalize="characters"
                                                                   id="vehicle_registration"
                                                                   placeholder=""
                                                                   name="vehicle_registration"
                                                                   required/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="container-fluid pl-0">
                                            <div class="row">
                                                <div class="form-group row">
                                                    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                                        <input type="hidden" class="form-control form-control-sm"
                                                               id="vehicle_description"
                                                               value=""
                                                               name="vehicle_description"
                                                               required
                                                               readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($requestDetails->cost_assigned_to =='CostCenter')
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <div
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4
                                                            control-input-wrapper">
                                                            <div class="control-input">
                                                                <div class="link-field ui-front"
                                                                     style="position: relative;">
                                                                    <label class="form-check-inline">
                                                                        <input type="radio"
                                                                               id="costOnCostCentre"
                                                                               class="list-row-checkbox bold mr-3"
                                                                               name="CostAssignedTo"
                                                                               value="CostCenterBasedRequisition"
                                                                               checked
                                                                        />
                                                                        User Department
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   id="cost_centre_code"
                                                                   value="{{$requestDetails->cost_centre}}"
                                                                   name="cost_centre_code"
                                                                   required readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-10">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   id="cost_center_name"
                                                                   value="{{$requestDetails->cost_centre_name}}"
                                                                   name="cost_center_name"
                                                                   required readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <div
                                                            class=" col-xs-12 col-sm-6 col-md-5 col-lg-4
                                                            control-input-wrapper">
                                                            <div class="control-input">
                                                                <div class="link-field ui-front"
                                                                     style="position: relative;">
                                                                    <label class="form-check-inline">
                                                                        <input type="radio"
                                                                               id="projectInput"
                                                                               disabled
                                                                               class="list-row-checkbox bold mr-3"
                                                                               autocomplete="off"
                                                                               name="CostAssignedTo"
                                                                               checked
                                                                               value="ProjectBasedRequisition">
                                                                        Project
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   readonly
                                                                   value="{{$requestDetails->project_code}}"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   readonly
                                                                   value="{{$requestDetails->project_name}}"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="container-fluid pl-0">
                                            <div class="row">
                                                <div class="form-group row">
                                                    <label
                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                        for="staff_name">
                                                        Requisition Type:
                                                    </label>
                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                        <select name="requisition_type" id="requisition_type"
                                                                class="form-control form-select-sm"
                                                                disabled
                                                                required>
                                                            <option value=""> --Select--</option>
                                                            @foreach ($requisitionTypes as $requisitionType)
                                                                @if($requestDetails->requisition_type
                                                                    == $requisitionType->code)
                                                                    <option selected
                                                                            value="{{$requisitionType->code}}">
                                                                        {{$requisitionType->name}}
                                                                    </option>
                                                                @else
                                                                    <option
                                                                        value="{{$requisitionType->code}}">
                                                                        {{$requisitionType->name}}
                                                                    </option>
                                                                @endif
                                                            @endforeach
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
                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                        for="odometer_reading">
                                                        Odometer Reading :
                                                    </label>
                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                        <input type="text" class="form-control form-control-sm"
                                                               id="odometer_reading"
                                                               value="{{$requestDetails->odometer}}"
                                                               readonly
                                                               required
                                                               name="odometer_reading"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($requestDetails->requisition_type == RequisitionTypes::OutOfTown->value)
                                    <div class="row" id="outOfTown">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="departure_date">Departure Date:</label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <input type="date" class="form-control form-control-sm"
                                                                   id="departure_date"
                                                                   readonly
                                                                   value="{{ date('Y-m-d',
                                                                    strtotime($requestDetails->valid_date_from))
                                                                    }}"
                                                                   name="departure_date"/>
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
                                                            for="request_date">Return Date:</label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">

                                                            <input type="date" class="form-control form-control-sm"
                                                                   id="return_date"
                                                                   readonly
                                                                   value="{{date('Y-m-d',
                                                                   strtotime($requestDetails->valid_date_to))
                                                                   }}"
                                                                   name="return_date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row outOfTown">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="mobile_no">Departure Town:</label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <input id="departureTown" name="departureTown"
                                                                   readonly
                                                                   value="{{$requestDetails->town_from}}"
                                                                   class="form-control"/>
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
                                                            for="request_date">Destination Town:</label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <input id="destinationTown"
                                                                   readonly
                                                                   value="{{$requestDetails->town_to}}"
                                                                   name="destinationTown"
                                                                   class="form-control"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif


                                <div class="row">
                                    @if($requestDetails->requisition_type != RequisitionTypes::OutOfTown->value)
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="mobile_no">Allocation Per Week:</label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control form-control-sm"
                                                                       id="fuel_allocation"
                                                                       value="{{$requestDetails->max_allowed}}"
                                                                       name="fuel_allocation"
                                                                       readonly
                                                                />
                                                                <div class="input-group-text">
                                                                    Ltr
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="container-fluid pl-0">
                                            <div class="row">
                                                <div class="form-group row">
                                                    <label
                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                        for="request_date">Request Date:</label>
                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                        <input type="text" class="form-control form-control-sm"
                                                               id="request_date"
                                                               readonly
                                                               value="{{Carbon::parse($requestDetails->valid_date_from)
                                                                ->format('d/m/Y')}}"
                                                               data-value="{{date('Y-m-d',
                                                                strtotime($requestDetails->valid_date_from))
                                                                }}"
                                                               name="request_date">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    @if($requestDetails->requisition_type != RequisitionTypes::OutOfTown->value)
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-12 col-md-5 col-lg-4 field-required"
                                                            for="next_fuel_date">
                                                            Next Refueling Date :
                                                        </label>
                                                        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-6">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   id="next_fuel_date"
                                                                   value="{{date('Y-m-d',
                                                                   strtotime($requestDetails->valid_date_to))}}"
                                                                   name="next_fuel_date"
                                                                   readonly required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="container-fluid pl-0">
                                            <div class="row">
                                                <div class="form-group row">
                                                    <label
                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                        for="requester">Request Originator:</label>
                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                        <input type="text" class="form-control form-control-sm"
                                                               id="requester"
                                                               readonly
                                                               value="{{$requestDetails->originator}}"
                                                               name="requester">
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
                                                        for="justification">
                                                        Purpose:
                                                    </label>
                                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8">
                                                        <textarea type="text"
                                                                  readonly
                                                                  id="justification"
                                                                  name="justification"
                                                                  style="height: 129px;"
                                                                  class="form-control form-control-sm"
                                                        >{{$requestDetails->comments}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="container-fluid pl-0">
                                            <div class="row">
                                                <div class="form-group row">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                @if(!empty($supportingDocument))
                                    <iframe id="{{ $supportingDocument->id }}"
                                            src="{{ asset('storage/Attachments/' . $supportingDocument->name) }}"
                                            style="width:100%; height: 500px "
                                            title="{{ $supportingDocument->originaldocumentname }}">
                                    </iframe>
                                    <span>Size: {{ number_format($supportingDocument->file_size, 2) }}
                                        MB Name: {{ $supportingDocument->originaldocumentname }}
                                    </span>
                                    <span> | </span>
                                    <a target="_blank"
                                       rel="noopener"
                                       style="cursor: pointer;"
                                       href="{{ asset('storage/Attachments/' . $supportingDocument->name) }}">
                                        View
                                    </a>
                                @else
                                    <div id="vehicleDetailsContainer" style="display: none;"
                                         class="col-xs-12 col-sm-12 col-md-12">
                                        <h1>Vehicle Details</h1>
                                        <table aria-label="vehicle details"
                                               role="table"
                                               class="table">
                                            <thead class="d-none">
                                            <tr>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody id="vehicleDetails" class="vehicleDetails">
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div id="materialDetailsContainer" class="table-responsive mt-3">
                            <table aria-label="Material Table"
                                   role="table"
                                   id="materialDetailsTable" class="table table-bordered">
                                <thead>
                                <tr class="bg-dark">
                                    <th>Material Description</th>
                                    @if($requestDetails->cost_assigned_to !='CostCenter')
                                        <th style="width:10%;">Project Number</th>
                                    @endif
                                    <th style="width:15%;">Quantity</th>
                                    <th>Unit Of Measure</th>
                                    <th>Price</th>
                                    <th>Amount(ZMW)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                            <span data-material-input="material_description"
                                                  id="material_description">
                                                {{$requestDetails->specifications}}
                                            </span>
                                    </td>
                                    @if($requestDetails->cost_assigned_to !='CostCenter')
                                        <td>
                                            <input type="text" name="projectCode" readonly
                                                   value="{{$requestDetails->project_code}}"
                                                   class="form-control form-control-sm border-0"/>
                                        </td>
                                    @endif
                                    <td>
                                            <span name="material_quantity"
                                                  id="material_quantity">
                                                {{number_format($requestDetails->quantity)}}
                                            </span>
                                    </td>
                                    <td>
                                            <span data-material-input="unit_of_measure"
                                                  id="unit_of_measure">
                                                {{$requestDetails->unit_of_measure}}
                                            </span>
                                    </td>
                                    <td>
                                            <span data-material-input="material_price"
                                                  id="material_price">
                                                {{number_format($requestDetails->price, 2)}}
                                            </span>
                                    </td>
                                    <td>
                                            <span data-material-input="material_amount"
                                                  id="material_amount">
                                                {{number_format($requestDetails->amount, 2)}}
                                            </span>
                                        <input type="hidden" name="material_amount">
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td class="text-right">
                                        @if($requestDetails->cost_assigned_to =='CostCenter')
                                            <strong>Total Quantity</strong>
                                        @endif
                                    </td>
                                    @if($requestDetails->cost_assigned_to !='CostCenter')
                                        <td class="text-right">
                                            <strong>Total Quantity</strong>
                                        </td>
                                    @endif
                                    <td class="text-left">
                                        <span class="text-bold"
                                              id="totalQty">{{number_format($requestDetails->quantity)}}</span>
                                    </td>
                                    <td></td>
                                    <td class="text-right"><strong>Total Amount</strong></td>
                                    <td><span class="text-bold"
                                              id="totalAmount">{{number_format($requestDetails->amount, 2)}}</span>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    {{--</div>--}}

                    @if(!empty($workflowTask->assigned_user))
                        @if( (auth()->user()->staff_no == $workflowTask->assigned_user
                            && empty($workflowTask->date_ended))
                            ||
                            (auth()->user()->hasRole('final_authoriser')&& empty($workflowTask->date_ended))
                            )
                            <div class="card-footer">
                                <div id="actionButtonsContainer" class="card-toolbar justify-content-end">
                                    <button type="button" id="approveRequisitionBtn"
                                            class="btn btn-success btn-sm mr-3">
                                        <i class="fas fa-thumbs-up"></i> Approve
                                    </button>
                                    <button type="button"
                                            id="declineRequisitionBtn"
                                            class="btn btn-danger btn-sm mr-3">
                                        <i class="fas fa-thumbs-down"></i> Reject
                                    </button>

                                    <button type="button"
                                            id="sendBackRequisitionBtn"
                                            class="btn btn-primary btn-sm mr-3">
                                        <i class="fas fa-arrow-left"></i>
                                        Send Back To Originator
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endif
                </form>

                <input type="hidden" value="{{ route('workflow.fuel.approve') }}" id="approvalUrl">
                <input type="hidden" value="{{ $requestDetails->req_no }}" id="taskReference">
            </div>
        </div>

        <x-fuel-workflow-approvers :task="$workflowTask" :request="$requestDetails"/>

        <x-workflow-approval-history :approvals="$approvalHistory" :request="$requestDetails"/>
    </section>
@endsection
@push('scripts')
    <script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('modules/fuelManagement/requisitions/workflow.js')}}"></script>
@endpush
