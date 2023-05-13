@php use App\Models\general\CostCenters;use Carbon\Carbon; @endphp
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
                   <span class="badge pl-2 badge-{{$requestDetails->color_code}}">
                       {{$requestDetails->status_name}}
                   </span>
                </div>
            </div>

            <div class="card-bod pb-4 min-h-600px pt-0">

                <x-error-view/>

                <form name="fuelRequisitionForm" id="fuelRequisitionForm" action="{{route('save.fuel.requisition')}}"
                      method="post">
                    @csrf
                    <div class="card-body user-data">

                        <table border="1" width="100%" data-height="100px" cellspacing="0" cellpadding="0"
                               align="Centre"
                               class="border-0">
                            <thead>
                            <tr class="border-0">
                                <th width="33%" colspan="4" style="border:none;" class="text-left">
                                    REQUISITION NUMBER: <span
                                        class="text-orange">{{ $requestDetails->proc_ref }}</span>
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
                                <th width="34%" colspan="1" class="p-3">Doc Number:<br>CO.14900.FORM.00040<br>Version:
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
                                                                                @endif/>
                                                                            Cost Center
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

                                    @if($requestDetails->requisition_type == '011')
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
                                                                       max="{{ date('Y-m-d', strtotime(Carbon::now())) }}"
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
                                                                {{--max="{{ date('Y-m-d', strtotime(Carbon::now())) }}"--}}
                                                                <input type="date" class="form-control form-control-sm"
                                                                       id="return_date"
                                                                       value="{{Carbon::parse($requestDetails->valid_date_to)->format('d/m/Y')}}"
                                                                       name="return_date">
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
                                                                   value="{{Carbon::parse($requestDetails->valid_date_from)->format('d/m/Y')}}"
                                                                   name="request_date">
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
                                                            class="col-xs-12 col-sm-12 col-md-5 col-lg-4 field-required"
                                                            for="next_fuel_date">
                                                            Next Refueling Date :
                                                        </label>
                                                        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-6">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   id="next_fuel_date"
                                                                   value="{{Carbon::parse($requestDetails->valid_date_to)->format('d/m/Y')}}"
                                                                   name="next_fuel_date"
                                                                   readonly required>
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
                                        <th>Project Number</th>
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
                                        <td>
                                            <input type="text" name="projectCode" readonly
                                                   value="{{$requestDetails->project_code}}"
                                                   class="form-control form-control-sm border-0"/>
                                        </td>
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
                                        <td class="text-right"><strong>Total Quantity</strong></td>
                                        <td><span class="text-bold" id="totalQty"></span></td>
                                        <td></td>
                                        <td class="text-right"><strong>Total Amount</strong></td>
                                        <td><span class="text-bold" id="totalAmount"></span></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <x-workflow-approval-history :approvals="$approvalHistory" :request="$requestDetails"/>
                        </div>
                    </div>

                    @if(auth()->user() != $requestDetails->created_by)
                        <div class="card-footer">
                            <div id="actionButtonsContainer" class="card-toolbar justify-content-end">
                                <button type="button" id="approveRequisitionBtn" class="btn btn-success btn-sm mr-3">
                                    <i class="fas fa-paper-plane"></i> Assent
                                </button>
                                <button style="display: none;" type="button" id="cancelRequisitionBtn"
                                        class="btn btn-danger btn-sm mr-3">
                                    <i class="fas fa-thumbs-down"></i> Reject
                                </button>
                            </div>
                        </div>
                    @endif
                </form>

                <input type="hidden" value="{{ route('workflow.approve') }}" id="approvalUrl">
                <input type="hidden" value="{{ $requestDetails->req_no }}" id="taskReference">
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <script>
        (function (appInstance, $) {
            $('#approveRequisitionBtn').on('click', function () {
                appInstance.approval.dialog({
                        options: {
                            'recordId': document.querySelector("#taskReference").value,
                            'documentType': 'FuelRequisition'
                        }
                    },
                    'fuelRequisition',
                    document.querySelector('#approvalUrl').value,
                    function (ajaxResponse, ...args) {
                        if (ajaxResponse.success) {
                            // window.top.toastr.success(response.message);
                            setTimeout(function () {
                                appInstance.showSystemMessage(
                                    'Approval',
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

            $('#submitRequisitionBtn').on('click', function () {
                let $form = document.forms['fuelRequisitionForm'];
                if (!$($form).valid()) {
                    return;
                }
                $('print-error-msg').css('display', 'none');
                let formData = new FormData($form);
                appInstance.confirm(
                    'Fuel Requisition',
                    'Are you sure you want to submit this request ?',
                    'Yes',
                    'No',
                    function () {
                        window.top.tmsApp.asyncPostFormData(
                            $form.action,
                            formData,
                            function (asyncResponse) {
                                if ('success' in asyncResponse && asyncResponse['success']) {
                                    setTimeout(function () {
                                        appInstance.showSystemMessage(
                                            'Fuel Requisition',
                                            asyncResponse['message'],
                                            function () {
                                            },
                                            'success'
                                        );
                                    }, 300);
                                } else {
                                    if (asyncResponse.hasOwnProperty('errors')) {
                                        appInstance.printErrorMsg(asyncResponse.errors);
                                        return
                                    }
                                    setTimeout(function () {
                                        appInstance.systemError(
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
                                            appInstance.printErrorMsg(xhr.responseJSON.errors);
                                        }
                                        if (xhr.responseJSON.hasOwnProperty('message')) {
                                            appInstance.systemError(
                                                'Fuel Requisition',
                                                xhr.responseJSON.hasOwnProperty('message')
                                            );
                                        }
                                        return;
                                    }

                                    appInstance.systemError(
                                        'Fuel Requisition',
                                        'We could not complete processing your request, please try again later');
                                }, 300)
                            }
                        )
                    },
                    function () {
                    }
                );
            })

        })(window.tmsApp || {}, jQuery)
    </script>
@endpush
