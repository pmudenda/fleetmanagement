@php use App\Enums\RequisitionTypes;use Carbon\Carbon; @endphp
@extends('layouts.app')
@push('styles')
    <link href="{{asset("assets/plugins/select2/css/select2.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css")}}" rel="stylesheet"
          type="text/css"/>
@endpush
@section('content')

    <x-content-header/>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h4>Approve Fuel Requisition</h4>
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

                        <table border="1" width="100%" data-height="100px" cellspacing="0" cellpadding="0"
                               align="Centre"
                               class="border-0">
                            <thead>
                            <tr class="border-0">
                                <th width="33%" colspan="4" style="border:none;" class="text-left">
                                    @if(!empty($requestDetails)  && !empty($requestDetails->proc_ref))
                                        REQUISITION NUMBER: <span
                                            class="text-orange">{{ $requestDetails->proc_ref }}</span>
                                    @endif
                                </th>
                                <th width="33%" colspan="4" style="border:none;" class="text-center"></th>
                                <th width="34%" colspan="1" style="border:none; text-align:right;"
                                    class="p-3 text-right">
                                    DOCUMENT REFERENCE NUMBER: <span
                                        class="text-orange">{{ $requestDetails->req_no }}</span>
                                </th>
                            </tr>
                            </thead>
                        </table>
                        <table border="1" width="100%" data-height="100px" cellspacing="0" cellpadding="0"
                               align="Centre"
                               class="mb-4 ">
                            <thead>
                            <tr class="border-success">
                                <th width="33%" class="text-center"><a href="#">
                                        <img src="{{ asset('assets/dist/img/zesco1.png') }}"
                                             title="ZESCO" alt="ZESCO"
                                             width="30%">
                                    </a>
                                </th>
                                <th width="33%" colspan="4" class="text-center">
                                    FUEL REQUISITION
                                </th>
                                <th width="34%" colspan="1" class="p-3">Doc Number:<br>XX.YYYYY.DOC_TYPE.NUMBER<br>Version:
                                    5
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
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 app-field-label field-required"
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
                                                                       name="vehicle_registration" required>
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
                                                                   required readonly>
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
                                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4 control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <label class="form-check-inline">
                                                                            <input type="radio"
                                                                                   id="costOnCostCentre"
                                                                                   class="list-row-checkbox bold mr-3"
                                                                                   name="CostAssignedTo"
                                                                                   value="CostCenterBasedRequisition"
                                                                                   @if($requestDetails->cost_assigned_to =='CostCenter')
                                                                                       checked
                                                                                @endif
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
                                        {{--@if($requestDetails->cost_assigned_to =='Project')--}}
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
                                                                        <label class="form-check-inline">
                                                                            <input type="radio"
                                                                                   id="projectInput"
                                                                                   class="list-row-checkbox bold mr-3"
                                                                                   autocomplete="off"
                                                                                   name="CostAssignedTo"
                                                                                   @if($requestDetails->cost_assigned_to =='Project')
                                                                                       checked
                                                                                   @endif
                                                                                   value="ProjectBasedRequisition">
                                                                            Project
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                <input type="form-control" readonly
                                                                       value="{{$requestDetails->project_code}}"/>
                                                                {{--<select type="text" name="project_code"
                                                                        class="form-select mt-1 project-code-ajax"
                                                                        id="project_code">
                                                                </select>--}}
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
                                                                    @if($requestDetails->requisition_type == $requisitionType->code)
                                                                        <option selected
                                                                                value="{{$requisitionType->code}}">{{$requisitionType->name}}</option>
                                                                    @else
                                                                        <option
                                                                            value="{{$requisitionType->code}}">{{$requisitionType->name}}</option>
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
                                                            for="staff_name">
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
                                                                for="mobile_no">Departure Date:</label>
                                                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                <input type="date" class="form-control form-control-sm"
                                                                       id="departure_date"
                                                                       readonly
                                                                       value="{{ date('Y-m-d', strtotime($requestDetails->valid_date_from)) }}"
                                                                       name="departure_date"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- max="{{  }}"--}}
                                            <div class="col-xs-12 col-sm-6 col-md-6">
                                                <div class="container-fluid pl-0">
                                                    <div class="row">
                                                        <div class="form-group row">
                                                            <label
                                                                class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                                for="request_date">Return Date:</label>
                                                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                                {{--max="{{ }}"--}}
                                                                <input type="date" class="form-control form-control-sm"
                                                                       id="return_date"
                                                                       readonly
                                                                       value="{{date('Y-m-d', strtotime($requestDetails->valid_date_to))  }}"
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
                                                                   value="{{date('Y-m-d', strtotime($requestDetails->valid_date_from))}}"
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
                                                                       value="{{date('Y-m-d', strtotime($requestDetails->valid_date_to))}}"
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
                                                                   value="{{$requestDetails->requested_by}}"
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
                                                            for="mobile_no">Purpose:</label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8">
                                                        <textarea type="text"
                                                                  readonly
                                                                  id="justification"
                                                                  name="justification"
                                                                  style="height: 129px;"
                                                                  class="form-control form-control-sm">{{$requestDetails->comments}}</textarea>
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
                                    <div id="vehicleDetailsContainer" style="display: none;"
                                         class="col-xs-12 col-sm-12 col-md-12">
                                        <h1>Vehicle Details</h1>
                                        <table class="table">
                                            <tbody id="vehicleDetails" class="vehicleDetails">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div id="materialDetailsContainer" class="table-responsive mt-3">
                                <table id="materialDetailsTable" class="table table-bordered">
                                    <thead>
                                    <tr class="bg-dark">
                                        <th>Material Description</th>
                                        @if($requestDetails->cost_assigned_to !='CostCenter')
                                            <th>Project Number</th>
                                        @endif
                                        <th>Qty</th>
                                        <th>Unit Of Measure</th>
                                        <th>Price</th>
                                        <th>Amount(ZMW)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <span data-material-input="material_description"
                                                  id="material_description">{{$requestDetails->specifications}}</span>
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
                                                  id="material_quantity">{{$requestDetails->quantity}}</span>
                                        </td>
                                        <td>
                                            <span data-material-input="unit_of_measure"
                                                  id="unit_of_measure">{{$requestDetails->unit_of_measure}}</span>
                                        </td>
                                        <td>

                                            <span data-material-input="material_price"
                                                  id="material_price">{{number_format($requestDetails->price, 2)}}</span>
                                        </td>
                                        <td>
                                            <span data-material-input="material_amount"
                                                  id="material_amount">{{number_format($requestDetails->amount, 2)}}</span>
                                            <input type="hidden" name="material_amount">
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td></td>
                                        @if($requestDetails->cost_assigned_to !='CostCenter')
                                            <td></td>
                                        @endif
                                        <td class="text-right"><strong>Total Quantity</strong>
                                            <span class="text-bold"
                                                  id="totalQty">{{number_format($requestDetails->price, 2)}}</span>
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
                        @if(auth()->user()->staff_no == $workflowTask->assigned_user && empty($workflowTask->date_ended))
                            <div class="card-footer">
                                <div id="actionButtonsContainer" class="card-toolbar justify-content-end">
                                    <button type="button" id="approveRequisitionBtn" class="btn btn-success btn-sm mr-3">
                                        <i class="fas fa-thumbs-up"></i> Approve
                                    </button>
                                    <button type="button"
                                            id="declineRequisitionBtn"
                                            class="btn btn-danger btn-sm mr-3">
                                        <i class="fas fa-thumbs-down"></i> Reject
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endif
                </form>

                <input type="hidden" value="{{ route('workflow.approve') }}" id="approvalUrl">
                <input type="hidden" value="{{ $requestDetails->req_no }}" id="taskReference">
            </div>
        </div>

        <x-fuel-workflow-approvers :request="$requestDetails"/>
        <x-workflow-approval-history :approvals="$approvalHistory" :request="$requestDetails"/>
    </section>
@endsection
@push('scripts')
    <script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <script>
        (function (appInstance, $) {
            $('#approveRequisitionBtn').on('click', function () {
                appInstance.approval.dialog(
                    {
                        options: {
                            'recordId': document.querySelector("#taskReference").value,
                            'documentType': 'FuelRequisition',
                            'action': 'approve'
                        }
                    },
                    'fuelRequisition',
                    document.querySelector('#approvalUrl').value,
                    function (ajaxResponse) {
                        if (ajaxResponse.success) {

                            setTimeout(function () {
                                appInstance.showSystemMessage(
                                    'Approval',
                                    ajaxResponse.message,
                                    function () {
                                        setTimeout(function () {
                                            window.location.href = ajaxResponse['redirectUrl'];
                                        },
                                            300);
                                    },
                                    'success');
                            }, 300);
                        } else {
                            setTimeout(function () {
                                appInstance.systemError('Requisition Approval', ajaxResponse.message);
                            }, 300);
                        }
                    },
                );
            });

            $('#declineRequisitionBtn').on('click', function () {
                appInstance.approval.dialog({
                        options: {
                            'recordId': document.querySelector("#taskReference").value,
                            'documentType': 'FuelRequisition',
                            'action': 'reject'
                        }
                    },
                    'fuelRequisition',
                    document.querySelector('#approvalUrl').value,
                    function (ajaxResponse) {
                        if (ajaxResponse.success) {
                            setTimeout(function () {
                                appInstance.showSystemMessage(
                                    'Rejection',
                                    ajaxResponse.message,
                                    function () {
                                        setTimeout(function () {
                                            window.location.href = ajaxResponse['redirectUrl'];
                                        }, 300);
                                    },
                                    'success');
                            }, 300);
                        } else {
                            setTimeout(function () {
                                appInstance.systemError('Requisition Approval', ajaxResponse.message);
                            }, 300);
                        }
                    },
                );
            });

        })(window.tmsApp || {}, jQuery)
    </script>
@endpush
