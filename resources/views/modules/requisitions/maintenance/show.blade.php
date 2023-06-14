@php use App\Helpers\StatusHelper;use App\Models\reference\Store;use App\Models\Security\User; @endphp
@php @endphp
@extends('layouts.app')
@push('styles')
    <link href="{{asset("assets/plugins/select2/css/select2.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css")}}" rel="stylesheet"
          type="text/css"/>
    <style>
        /*       .corporate > tbody, td, tfoot, th, thead, tr {
                   border-color: inherit;
                   border-style: solid;
                   !*border-width: 1px !important;*!
               }*/
    </style>
@endpush
@section('content')

    <x-content-header/>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h4>APPROVE STORES RESERVATION</h4>
                </div>
                <div class="card-toolbar justify-content-end">
                    @if(!empty($requestDetails))
                        <span class="badge pl-2 {{$requestDetails->color_code ?? ''}}">
                       {{$requestDetails->status_name ?? ''}}
                   </span>
                    @endif
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
                            <tr class="border-0" style="border-style: none;">
                                <th width="33%" colspan="4" style="border:none;" class="text-left">
                                    @if(!empty($requestDetails)  && !empty($requestDetails->st_pur))
                                        REQUISITION NUMBER: <span
                                            class="text-orange">{{ $requestDetails->st_pur }}</span>
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
                        <table class="corporate" border="1" width="100%" data-height="100px" cellspacing="0"
                               cellpadding="0"
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
                                    STORES RESERVATION
                                </th>
                                <th width="34%" colspan="1" class="p-3">
                                    {{--Doc Number:<br>XX.YYYYY.DOC_TYPE.NUMBER<br>Version:
                                    5--}}
                                </th>
                            </tr>
                            </thead>
                        </table>

                        {{--<label class="app-required-marker"></label>--}}

                        <div class="container-fluid mt-2">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 app-field-label field-required"
                                                            for="staff_no">Vehicle Registration #:
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <div class="input-group">
                                                                <input type="text"
                                                                       data-action="{{route('requisition.vehicle.details')}}"
                                                                       readonly
                                                                       class="form-control form-control-sm"
                                                                       value="{{$requestDetails->veh_reg_no ??''}}"
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
                                                        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                                                            <input type="text" class="form-control form-control-sm"
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

                                    <div class="row">

                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="request_date">Store:
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   id="store"
                                                                   readonly
                                                                   value="{{$requestDetails->store}}:{{Store::where('code_store','=',$requestDetails->store)->first()->description}}"
                                                                   name="request_date">
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
                                                            for="mobile_no">Collection Date:</label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   id="fuel_allocation"
                                                                   value=""
                                                                   name="fuel_allocation"
                                                                   readonly
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{--  <div class="row">
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
                                                                     value="{{$requestDetails->odometer ?? ''}}"
                                                                     readonly
                                                                     required
                                                                     name="odometer_reading"
                                                              />
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>--}}


                                    <div class="row">

                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="request_date">Request Date:
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   id="request_date"
                                                                   readonly
                                                                   value="{{date('Y-m-d', strtotime($requestDetails->created_at))}}"
                                                                   name="request_date">
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
                                                            for="mobile_no">Collection Date:</label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   id="fuel_allocation"
                                                                   value=""
                                                                   name="fuel_allocation"
                                                                   readonly
                                                            />
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
                                                            for="requester">Request Originator:</label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-6">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   id="requester"
                                                                   readonly
                                                                   value="{{User::where('staff_no','=', $requestDetails->requested_by)->first()->name}}"
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
                                                                  class="form-control form-control-sm">{{$requestDetails->comments ?? ''}}</textarea>
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
                                <div class="col-3" style="display: none;">
                                    <div id="vehicleDetailsContainer" style="display: none;"
                                         class="col-xs-12 col-sm-12 col-md-12 pl-0">
                                        <h1>Vehicle Details</h1>
                                        <table class="table table-striped">
                                            <tbody id="vehicleDetails" class="vehicleDetails">
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="image_view" class="card text-center py-5 my-2" style="display: none;">
                                        <div class="form-group">
                                            <div class="imagePreview"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div id="materialDetailsContainer" class="table-responsive mt-3">
                                <table id="materialDetailsTable" class="table table-bordered">
                                    <thead>
                                    <tr class="bg-orange">
                                        <th class="text-white">Article</th>
                                        <th class="text-white">Material Description</th>
                                        <th class="text-white">Qty</th>
                                        <th class="text-white">Unit Of Measure</th>
                                        <th class="text-white">Price</th>
                                        <th class="text-white">Amount(ZMW)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $totalCount = 0; $totalAmount   = 0;   @endphp
                                    @foreach($details as $detail)
                                        @php $totalCount +=$detail->quantity; $totalAmount+=$detail->amount  @endphp
                                        <tr>

                                            <td>
                                                 <span data-material-input="material_description"
                                                       id="material_description">{{$detail->material_code}}</span>
                                                <input type="hidden" name="projectCode" readonly
                                                       value="{{$detail->material_code}}"
                                                       class="form-control form-control-sm border-0"/>
                                            </td>

                                            <td>
                                            <span data-material-input="material_description"
                                                  id="material_description">{{$detail->specifications}}</span>
                                            </td>
                                            <td>
                                            <span name="material_quantity"
                                                  id="material_quantity">{{$detail->quantity}}</span>
                                            </td>
                                            <td>
                                            <span data-material-input="unit_of_measure"
                                                  id="unit_of_measure">{{$detail->unit_of_measure}}</span>
                                            </td>
                                            <td>

                                            <span data-material-input="material_price"
                                                  id="material_price">{{number_format($detail->price, 2)}}</span>
                                            </td>
                                            <td>
                                            <span data-material-input="material_amount"
                                                  id="material_amount">{{number_format($detail->amount, 2)}}</span>
                                                <input type="hidden" name="material_amount">
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>

                                        <td class="text-right"><strong>Total Quantity</strong>
                                            <span class="text-bold"
                                                  id="totalQty">{{number_format($totalCount)}}</span>
                                        </td>
                                        <td></td>
                                        <td class="text-right"><strong>Total Amount</strong></td>
                                        <td><span class="text-bold"
                                                  id="totalAmount">{{number_format($totalAmount, 2)}}</span>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <x-workflow-approval-history :approvals="$approvalHistory" :request="$requestDetails"/>
                        </div>
                    </div>

                    @if(empty($requestDetails->proc_ref))
                        @if(auth()->user()->staff_no != $requestDetails->requested_by)
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
                                </div>
                            </div>
                        @endif
                    @endif
                </form>

                <input type="hidden" value="{{ route('stores.requisition.approve') }}" id="approvalUrl">
                <input type="hidden" value="{{ $requestDetails->req_no }}" id="taskReference">
            </div>
        </div>
        <input type="hidden" name="onboarding_status" id="onboarding_status"
               value="{{StatusHelper::onboardingComplete()}}">
    </section>
@endsection
@push('scripts')
    <script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <script>


        (function (tmsApp, $) {
            function populateVehicleDetails(payload) {
                let vehicle = payload['vehicle'];
                let images = payload['images'];
                let vehicle_state = payload['vehicle_state'];

                if (!vehicle || !vehicle.brand_name) {
                    return;
                }

                // BAD 1010
                if (vehicle['on_boarding_status'] != document.querySelector('[name="onboarding_status"]').value) {
                    tmsApp.showSystemMessage("Incomplete Vehicle Details",
                        `The vehicle ${vehicle['registration_number']} is ${vehicle_state}. Please Contact Fleet Master
                            System Administrator on 3309,3350,3351,3306, fleetmaster@zesco.com`,
                        () => {
                        },
                        "error");
                    return;
                }

                let vLabel = vehicle['body_type_name'] + ' ' + vehicle['brand_name'] + ' ' + vehicle['model_name'] + ' ' + vehicle['model_code'];
                $("#vehicle_description").val(vLabel);
                let row = `<tr><th>Make</th><td id="make">${vehicle.brand_name}</td></tr>
                               <tr>
                                    <th>Model</th><td id="model">${vehicle.model_name} ${vehicle.model_code}</td>
                               </tr>
                               <tr style="">
                                     <th>Type</th><td id="registration">${vehicle['body_type_name']}</td>
                                </tr>`;

                $('tbody#vehicleDetails').html(row);

                if (images && images.length > 0) {
                    let frontViewImages = images.filter((image) => {
                        return image['file_type'] === 'Front View';
                    })
                    let imagePath = frontViewImages[0]?.path;
                    document.querySelector(".imagePreview").style.backgroundImage = "url(/storage" + imagePath + ")";
                }

            }

            function findVehicle() {
                const numberPlate = document.querySelector('[name="vehicle_registration"]').value;
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
                            populateVehicleDetails(response_data.payload);
                        } else {
                            //removeSubmissionAndDetailsOptions();
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

            $('#approveRequisitionBtn').on('click', function () {
                tmsApp.approval.dialog(
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


            findVehicle();

        })(window.tmsApp || {}, jQuery)
    </script>
@endpush
