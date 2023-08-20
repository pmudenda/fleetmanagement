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
        /*th {
            white-space: nowrap;
        }

        !**===NO WRAP ON TABLE =====**!
        table.dataTable.nowrap th,
        table.dataTable.nowrap td {
            white-space: nowrap;
        }*/

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

        .wizard > .steps > ul > li {
            width: 20% !important;
        }

        .error {
            color: orangered;
        }
    </style>
@endpush
@section('content')

    <x-content-header
        :activeCrumb="'New Job Card'"
        :linkText="'Job Card'"
        :pageTitle="'Workshop Management'"/>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h4>Workshop Job Card</h4>
                    @if(!empty($details) && !empty($details->job_card_no))
                        <span class="ml-2 indicator-pill whitespace-nowrap green">
                            <span>Saved</span>
                        </span>
                    @else
                        <span class="ml-2 indicator-pill whitespace-nowrap orange"><span>Not Saved</span></span>
                    @endif
                </div>
                @if(!empty($details) && !empty($details->job_card_no))
                    <div class="card-toolbar justify-content-end">
                        JOB CARD NUMBER: <span class="text-orange">{{ $details->job_card_no ?? '' }}</span>
                    </div>
                @endif

            </div>

            <div class="card-body pb-4 min-h-600px pt-0">

                <x-error-view/>

                <label class="app-required-marker"></label>
                <form name="jobCardForm"
                      id="jobCardForm"
                      {{--action="{{route('save.workshop.requisition')}}"--}}
                      method="post">
                    @csrf
                    <h1>DETAILS</h1>
                    <section>
                        @include('modules.workshopManagement.workOrder.tabs.job_card_header')
                    </section>

                    <h1>ASSESSMENTS</h1>
                    <section>
                        <div class="container-fluid">
                            <div class="row" data-form-url="{{route("job_card.accessories.checkin")}}"
                                 data-model-name="Accessories">
                                <input type="hidden" value="{{$details->job_card_no ?? 0}}" name="job_card_voucher"/>
                                <input type="hidden" value="{{$details->driver_acknowledged ?? 'N'}}"
                                       id="driverAcknowledged"
                                       name="driverAcknowledged"/>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="alert alert-danger">
                                        CUSTOMER IS REQUIRED TO REMOVE ALL PERSONAL EFFECTS FROM THE VEHICLE
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <table
                                                class="table table-row-dashed align-middle gs-0 table-bordered">
                                                <thead>
                                                <tr class="bg-dark-subtle">
                                                    <th class="pl-2">Item</th>
                                                    <th>Present</th>
                                                    <th class="pr-2">Not Present</th>
                                                    <th class="pr-2">Remarks</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($accessories as $key => $accessory)
                                                    @if(($key%2) == 0)
                                                        <tr>
                                                            <td class="pl-2"
                                                                style="width: 35%;">{{$accessory->name}}</td>
                                                            <td><input type="radio" value="YES" required
                                                                       name="field_{{str_replace(' ','', $accessory->code)}}">
                                                            </td>
                                                            <td><input type="radio" value="NO" required
                                                                       name="field_{{str_replace(' ','', $accessory->code)}}">
                                                            </td>
                                                            <td style="width: 45%;">
                                                                <input typeof="text"
                                                                       name="comment_{{str_replace(' ','', $accessory->code)}}"
                                                                       class="form-control form-control-sm"/>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col">
                                            <table
                                                class="table table-row-dashed align-middle gs-0 table-bordered">
                                                <thead>
                                                <tr class="bg-dark-subtle">
                                                    <th class="pl-2">Item</th>
                                                    <th>Present</th>
                                                    <th class="pr-2">Not Present</th>
                                                    <th class="pr-2">Remarks</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($accessories as $key => $accessory)
                                                    @if(($key%2) != 0)
                                                        <tr>
                                                            <td class="pl-2" style="width: 35%;">
                                                                {{$accessory->name}}
                                                            </td>
                                                            <td><input type="radio" required value="YES"
                                                                       name="field_{{str_replace(' ','', $accessory->code)}}">
                                                            </td>
                                                            <td><input type="radio" required value="NO"
                                                                       name="field_{{str_replace(' ','', $accessory->code)}}">
                                                            </td>
                                                            <td style="width: 45%;">
                                                                <input typeof="text"
                                                                       name="comment_{{str_replace(' ','', $accessory->code)}}"
                                                                       class="form-control form-control-sm">
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row mt-10">
                                        <div class="col">
                                            <div class="form-group">
                                                <label
                                                    class="col-xs-12 col-sm-6 col-md-5 col-lg-4 pl-0"
                                                    for="accessoriesRemarks">
                                                    General Comments and Observation (Damages):
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8 pl-0">
                                                    @if(!empty($comments))
                                                        <textarea type="text"
                                                                  id="accessoriesRemarks"
                                                                  name="accessoriesRemarks"
                                                                  style="height: 129px;"
                                                                  class="form-control form-control-sm">{{$comments->where('type','=','ACC')->first()->remarks ??''}}</textarea>
                                                    @else
                                                        <textarea type="text"
                                                                  id="accessoriesRemarks"
                                                                  name="accessoriesRemarks"
                                                                  style="height: 129px;"
                                                                  class="form-control form-control-sm"></textarea>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <div class="table-responsive" style="max-height:500px;">
                                                    <table class="table table-bordered" id="observations">
                                                        <thead>
                                                        <tr class="bg-default-dark">
                                                            <th>Observation</th>
                                                            <th>Attachment</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <button type="button"
                                                                        class="btn btn-primary btn-sm selectAttachment">
                                                                    <i class="fas fa-paperclip"></i>
                                                                </button>
                                                                <input type="file" name="attachment[]"
                                                                       class="form-control d-none file">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="observation[]"
                                                                       class="form-control">
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <button type="button"
                                                            data-table-id="observations"
                                                            class="btn btn-sm btn-primary add pull-right"
                                                            value="addRow">
                                                        <i class="fa fa-plus"></i> Add Row
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-10">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1 mt-4">
                            <div class="row">
                                <div class="col-lg-2 col-sm-12">
                                    <label>Assessment Acknowledgement: <small class="text-danger">(To Be Performed By
                                            Driver)</small></label>
                                </div>
                                @if(!empty($details->driver_acknowledged))
                                    <div class="col-lg-3 col-sm-12">
                                        <span class="btn btn-sm btn-success">Acknowledged</span>
                                    </div>
                                @else
                                    <div class="col-lg-3 col-sm-12">
                                        <span class="btn btn-sm btn-success">Awaiting Acknowledgement</span>
                                    </div>
                                @endif
                                @if(!empty($details->driver_acknowledged))
                                    <div class="col-lg-2 col-sm-12 text-left">
                                        <label>eSignature:</label>
                                    </div>
                                    <div class="col-lg-1 col-sm-12">
                                        <input type="text"
                                               name="sig_of_claimant"
                                               class="form-control"
                                               value="{{$details->driver_in}}"
                                               readonly
                                        />
                                    </div>

                                    <div class="col-lg-2 col-sm-12 text-left"><label>Date Acknowledged:</label></div>

                                    <div class="col-lg-2 col-sm-12">
                                        <input type="text"
                                               name="date_claimant"
                                               class="form-control"
                                               value="{{Carbon::parse($details->date_acknowledged)->format('d/m/Y')}}"
                                               readonly
                                        />
                                    </div>
                                @else
                                    <div class="col-lg-2 col-sm-12 text-left">
                                        <label>eSignature:</label>
                                    </div>
                                    <div class="col-lg-1 col-sm-12">
                                        <input type="text"
                                               name="sig_of_claimant"
                                               class="form-control"
                                               value=""
                                               readonly
                                        />
                                    </div>

                                    <div class="col-lg-2 col-sm-12 text-right">
                                        <button type="button"
                                                class="btn btn-sm btn-success"
                                                data-bs-toggle="modal"
                                                data-bs-target="#eSignatureModal">
                                            <i class="fas fa-signature"></i>
                                            Sign
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    @if(!empty($details->driver_acknowledged))
                        <h1>DEFECTS</h1>
                        <section>
                            @include('modules.workshopManagement.workOrder.tabs.defects')
                        </section>

                        <h1>LABOUR & ASSIGNMENTS</h1>
                        <section>
                            {{-- @include('modules.workshopManagement.workOrder.tabs.labour')--}}
                            @include('modules.workshopManagement.workOrder.tabs.labourAssignments')
                        </section>

                        <h1>SPARES & SERVICES</h1>
                        <section>
                            @include('modules.workshopManagement.workOrder.tabs.partsSelection')
                        </section>
                    @endif
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

                <input type="hidden" value="{{route('delete.service.record')}}" name="deleteServiceUrl"
                       id="deleteServiceUrl"/>

                <input type="hidden" value="{{route('mechanic.search')}}" id="mechanicDetails"/>
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

    <input type="hidden"
           id="suppliersList"
           value="{{route('suppliers.list')}}"/>

    <div class="modal fade" id="eSignatureModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-pencil-square-o"></i> eSignature
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('sign.assessment')}}" name="eSignDocument">
                    <input type="hidden" name="reference" value="{{$details->job_card_no ?? 0}}">
                    <div class="modal-body">
                        <div>
                            <div id="approvalDialogSign">
                                <div style="float:left;">
                                    <span id="spanMessage" style="color: #f00;" class="errorMessage"></span>
                                    <label id="newApproveLblMessage" class="mediumMessage"></label>
                                </div>
                                <div style="float:right; padding-left:30px; display: none;">
                                    <input class="small" type="checkbox" id="approveChkSignAs"/>
                                    Sign As Different User...
                                </div>
                                <div class="signAsElement">
                                    <label class="app-label field-required app-field-null">Login ID</label>
                                    <div>
                                        <input class="zqEditMode form-control"
                                               name="loginId"
                                               type="text"
                                               required
                                               id="loginIdInput"
                                               size="25" maxlength="25"/><br/>
                                    </div>
                                </div>
                                {{-- <div >
                                     <label class="app-label field-required app-field-null">Login Password</label>
                                     <div>
                                         <input type="password" id="loginPasswordInput"
                                                class="form-control"
                                                size="25" maxlength="25"/><br/>
                                     </div>
                                 </div>--}}
                                <div class="signAsElement">
                                    <label class="app-label field-required app-field-null">eSignature Password</label>
                                    <div>
                                        <input type="password"
                                               required
                                               name="password"
                                               class="form-control"
                                               id="eSignaturePasswordInput" size="25" maxlength="25"/>
                                    </div>
                                </div>
                                <div style="clear:both;">
                                    <div class="mt-10">
                                        <div class="row">
                                            <div class="col-1">
                                                <input
                                                    required
                                                    id="acceptance"
                                                    name="acceptance"
                                                    type="checkbox"
                                                    class="checkbox">
                                            </div>
                                            <div class="col-10">
                                                <p id="newApproval_Remarks">
                                                    I hereby, acknowledge that the assessment has been done truthfully
                                                    and fairly
                                                </p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div id="newApproval_DIVWait" style="visibility: hidden; display: none">
                                <table border="0" style="width:100%; height:100%;">
                                    <tr>
                                        <td align="center">
                                            Please wait . . .
                                            <br/>
                                            <br/>
                                            Signature being verified.
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button id="btnSign" type="submit"
                                class="btn btn-sm btn-success mr-3">
                            <i class="fas fa-save"></i>
                            Sign
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reassignMechanicModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-pencil-square-o"></i> Task Reassignment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{route('save.job.reassignment')}}" name="saveReassignmentForm">
                    <input type="hidden" name="reassignmentReference" value="">
                    <input type="hidden" name="reassignmentDefect" value="">
                    <input type="hidden" name="reassignmentDefectId" value="">

                    <div class="modal-body">
                        <div class="row">
                            <div class="row">
                                <div class="form-group">
                                    <label class="app-field-label">
                                        Defect Description
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" readonly
                                           class="form-control form-control-sm"
                                           name="reassignmentDefectDefectName"
                                           value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="app-field-label">
                                        Mechanic
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           required
                                           class="form-control form-control-sm"
                                           name="reassignTo"
                                           list="mechanics"/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <label class="app-field-label">
                                        Section
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select  form-select-sm" name="reassignmentDefectSection">
                                        <option></option>
                                        @foreach($workshop_sections as $workshop_section)
                                            <option
                                                value="{{$workshop_section->code}}">{{$workshop_section->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-10">
                                <textarea id="reassignmentJustification"
                                          style="height: 129px;"
                                          required
                                          class="form-control comments form-control-sm"
                                          name="reassignmentJustification"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">Close
                        </button>
                        <button id="saveReassignment"
                                type="submit"
                                class="btn btn-sm btn-success mr-3">
                            <i class="fas fa-save"></i>
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        window.selectedAccessories = {!! json_encode($accessories_checked_in) !!};
        window.defects = {!! json_encode($defects) !!};
        window.materials = {!! json_encode($materials) !!};
        window.step_id = {!! $step !!};
    </script>
    <script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset("libs/steps/jquery.steps.js")}}"></script>
    <script>
        'use strict';
        const materialTableRowTemplate = `<tr class="increment">
            <td class="showNumber">
                <input
                    name="registration"
                    required
                    value=""
                    class="form-control form-control-sm vehicle_registration"/>
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
                        class="btn btn-danger p-2">
                    <i class="fas fa-trash m-0"></i>
                </button>
            </td>
        </tr>`;
        const serviceTableRowTemplate = ` <tr class="increment">
            <td class="showNumber">
                <input
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
        const defectTableRowTemplate = ` <tr class="increment">
                                <td class="showNumber">
                                    <select name="vehicleSystem"
                                            class="form-select form-select-sm select_2_control vehicleSystem">
                                        <option></option>
                                    </select>
                                </td>
                                <td>
                                    <select name="defectCategory"
                                            class="form-select form-select-sm select_2_control defectCategory">
                                        <option></option>
                                    </select>
                                </td>
                                <td>
                                    <select name="defect"
                                            class="form-select form-select-sm select_2_control defect">
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
        <input name="date_def"
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
                            </tr>`;

        (function (tmsApp, $) {
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
                        setSelectedAccessories();
                    }

                    if (window['defects']) {
                        dataFiler();
                    }

                    if (window['materials']) {
                        prefillSelectedMaterials();
                        $('[name="quantity"]').change();
                    }

                    findDriver();

                    findVehicle("InWorkshop");

                }, 600);

                const $labourTable = $('#labour_table');

                $labourTable.on('change paste', '[name="mechanic"]', function () {
                    const $row = $(this).closest('tr');
                    if (!this.value || this.value.length < 5) {
                        return;
                    }
                    findMechanic($row, this.value);
                });

                $('[name="selectDefectToAssign"]').on('change', function () {
                    let checked = $(this).is(':checked');
                    let checkBoxes = document.querySelector('#labour_table').querySelectorAll('[name="selectDefectToAssign"]');
                    let count = 0;
                    for (const ele of checkBoxes) {
                        if ($(ele).is(':checked')) {
                            count += 1;
                        }
                    }

                    if (checked && count >= 2) {
                        // selects()
                        $('[value="assignMultiple"]').removeClass('d-none');
                    } else if (count < 2) {
                        // deSelect();
                        $('[value="assignMultiple"]').addClass('d-none');
                    }
                });

                $(document).on('change', '.imprestArticles', function () {
                    let selectedArticle = this.value;
                    const $row = $(this).closest('tr');
                    let itemType = document.querySelector('#pettyCashBuyItemType').value
                    let dataToFilter = window[itemType];
                    let filteredArray = dataToFilter.filter(function (article) {
                        return article.code_article === selectedArticle;
                    });

                    if (filteredArray.length === 0) {
                        return;
                    }
                    let selectedArticleObject = filteredArray[0];

                    if (document.querySelector('[name="stockItemCode"]').value === itemType) {

                        if (!selectedArticleObject?.price) {
                            const description = selectedArticleObject?.technical_specifications ? selectedArticleObject?.technical_specifications : "";
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'The Article '
                                    + selectedArticleObject?.code_article
                                    + ' - ' + description + ' has no price. ' +
                                    ' Please Contact Fleet Master System Administrator on 3309,3350,3351,3306, fleetmaster@zesco.co.com'
                            });
                            return;
                        }
                        console.log(parseInt(selectedArticleObject?.quantity_in_store));
                        if (parseInt(selectedArticleObject?.quantity_in_store) !== 0) {
                            const description = selectedArticleObject?.technical_specifications ? selectedArticleObject?.technical_specifications : "";
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'The Article '
                                    + selectedArticleObject?.code_article
                                    + ' - ' + description + ' is available in '
                                    + $("#pettyCashStoreName").val()
                                    + '.' +
                                    'You can proceed to request from stores'
                            });
                            return;
                        }
                    }

                    //$(row).find('[name="quantity"]').attr('max', article['quantity_in_store']);
                    $($row).find('[name="imprestArticleCode"]').val(selectedArticleObject['code_article']);
                    $($row).find('[name="imprestItemUnitPrice"]').val(selectedArticleObject['price']);
                    $($row).find('[name="imprestArticleDescription"]').val(selectedArticleObject['technical_specifications']);
                    $($row).find('[name="imprestItemUnitOfMeasure"]').val(selectedArticleObject['unit_measure_name']);

                });

                $('[name="selectAll"]').on('change', function () {

                    function selects() {
                        let ele = document.querySelector('#labour_table').querySelectorAll('[name="selectDefectToAssign"]');
                        for (let i = 0; i < ele.length; i++) {
                            if (ele[i].type === 'checkbox')
                                ele[i].checked = true;
                            $(ele[i]).change();
                        }
                    }

                    function deSelect() {
                        let ele = document.querySelector('#labour_table').querySelectorAll('[name="selectDefectToAssign"]');
                        for (let i = 0; i < ele.length; i++) {
                            if (ele[i].type === 'checkbox')
                                ele[i].checked = false;
                            $(ele[i]).change();
                        }
                    }

                    if ($(this).is(':checked')) {
                        selects()
                    } else {
                        deSelect();
                    }
                });

                $($labourTable).find('[data-record-id]').find('.mechanicStaffNumber').change();

                initArticleSelector($('.articlesDropDownList'));

                initServiceArticleSelector($('.servicesArticlesDropDownList'));

                setTimeout(function () {
                    disableControls();
                    $("#labour_table").find('[data-record-id]').find('.mechanicStaffNumber').change();
                }, 1000);

                Inputmask({
                    "mask": "AAA 9{1,4}"
                }).mask('[name="vehicle_registration"]');

                $.fn.disableBtn = function () {
                    return this.each(function () {
                        $(this).addClass("disabled").attr("disabled", true)
                    });
                }

                $.fn.enableBtn = function () {
                    return this.each(function () {
                        let $this = $(this);
                        $this.removeClass("disabled").attr("disabled", false)
                    });
                }

                $(document).on('click', '.reassignMechanic', function () {
                    let item = JSON.parse($(this).attr('data-labour-item'));

                    $('[name="reassignmentReference"]').val(item.id)
                    $('[name="reassignmentDefect"]').val(item.def_no)
                    $('[name="reassignmentDefectId"]').val(item.defect_id)
                    $('[name="reassignmentDefectDefectName"]').val(item.defect_name);
                    $('[name="reassignmentDefectSection"]').val(item.section).change();
                    $("#reassignMechanicModal").modal('show');
                });

                $(document).on('submit', '[name="saveReassignmentForm"]', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    let $form = document.forms['saveReassignmentForm'];
                    if (!$($form).valid()) {
                        return;
                    }

                    let formData = new FormData($form)

                    tmsApp.confirm(
                        'Reassign Task',
                        'Are you sure you want to reassign this task ?',
                        'Yes',
                        'No',
                        function () {
                            $("#reassignMechanicModal").modal('hide');

                            $.ajax({
                                type: "POST",
                                url: $form.action,
                                data: formData,
                                dataType: 'json',
                                contentType: false,
                                processData: false
                            }).done(function (asyncResponse) {
                                if (asyncResponse.hasOwnProperty('success') && asyncResponse['success']) {
                                    setTimeout(function () {
                                        tmsApp.showSystemMessage(
                                            'Reassign Task',
                                            asyncResponse['message'],
                                            function () {
                                                window.location.reload();
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
                                            'Reassign Task',
                                            asyncResponse['message'],
                                            function () {
                                            }, 'error');
                                    }, 300);
                                }
                            }).fail(function (xhr, settings, errorThrown) {
                                console.log(errorThrown)
                                setTimeout(function () {
                                    if ('responseJSON' in xhr) {
                                        if (xhr.responseJSON.hasOwnProperty('errors')) {
                                            tmsApp.printErrorMsg(xhr.responseJSON.errors);
                                        }
                                        if (xhr.responseJSON.hasOwnProperty('message')) {
                                            tmsApp.systemError(
                                                'Reassign Task',
                                                xhr.responseJSON['message']
                                            );
                                        }
                                        return;
                                    }
                                    tmsApp.systemError(
                                        'Reassign Task',
                                        'We could not complete processing your request, please try again later');
                                }, 300)
                            });
                        }
                    );
                })

                $(document).on('click', '.saveAssignment', function () {

                    let formSel = $('#labour_table');
                    let formData = {
                        modelName: formSel.data('modelName'),
                        submitForm: true,
                        workshopReference: $('[name="workshop_reference"]').val(),
                        jobCardNumber: $('[name="job_card_number"]').val()
                    };

                    let arr = [];
                    let obj = {};

                    $(formSel).find("tbody").children().map(function (index, row) {
                        let obj = {};

                        if ($(row).attr('data-record-id') && $(row).attr('data-record-id') !== "0") {
                            console.log("Record with " + $(row).attr('data-record-id'));
                        } else {
                            $(row).find('input[name][type!=hidden], select[name],textarea[name]').each(function (i, item) {
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
                        }

                    });

                    formData['items'] = arr;

                    formData = {
                        ...obj,
                        ...formData
                    }

                    $('.print-error-msg').css('display', 'none');

                    tmsApp.confirm(
                        'Assign Task',
                        'Are you sure you want to close this work order ?',
                        'Yes',
                        'No',
                        function () {
                            $.ajax({
                                type: "POST",
                                url: formSel.data('formUrl'),
                                data: JSON.stringify(formData),
                                dataType: "json",
                                contentType: "application/json; charset=utf-8",
                            }).done(function (asyncResponse) {

                                if (asyncResponse.hasOwnProperty('success') && asyncResponse['success']) {
                                    setTimeout(function () {
                                        tmsApp.showSystemMessage(
                                            'Assign Task',
                                            asyncResponse['message'],
                                            function () {
                                                // window.location.href = asyncResponse["redirectUrl"]
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
                                            'Assign Task',
                                            asyncResponse['message'],
                                            function () {
                                            }, 'error');
                                    }, 300);
                                }
                            }).fail(function (xhr, settings, errorThrown) {
                                console.log(errorThrown)
                                setTimeout(function () {
                                    if ('responseJSON' in xhr) {
                                        if (xhr.responseJSON.hasOwnProperty('errors')) {
                                            tmsApp.printErrorMsg(xhr.responseJSON.errors);
                                        }
                                        if (xhr.responseJSON.hasOwnProperty('message')) {
                                            tmsApp.systemError(
                                                'Assign Task',
                                                xhr.responseJSON['message']
                                            );
                                        }
                                        return;
                                    }

                                    tmsApp.systemError(
                                        'Assign Task',
                                        'We could not complete processing your request, please try again later');
                                }, 300)
                            });
                        }
                    );
                });

                $(document).on('click', '#btnSubmitImprestBuyForm', function () {

                    let formSel = $('#pettyCashItemsTable');
                    let formData = {
                        modelName: formSel.data('modelName'),
                        submitForm: true,
                        workshopReference: $('[name="workshop_reference"]').val(),
                        jobCardNumber: $('[name="job_card_number"]').val()
                    };

                    let arr = [];
                    let obj = {};

                    $(formSel).find("tbody").children().map(function (index, row) {
                        let obj = {};

                        if ($(row).attr('data-record-id') && $(row).attr('data-record-id') !== "0") {
                            console.log("Record with " + $(row).attr('data-record-id'));
                        } else {
                            $(row).find('input[name][type!=hidden], select[name],textarea[name]').each(function (i, item) {
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
                        }

                    });

                    formData['items'] = arr;

                    formData = {
                        ...obj,
                        ...formData
                    }

                    $('.print-error-msg').css('display', 'none');

                    tmsApp.confirm(
                        'Assign Task',
                        'Are you sure you want to close this work order ?',
                        'Yes',
                        'No',
                        function () {
                            $.ajax({
                                type: "POST",
                                url: formSel.data('formUrl'),
                                data: JSON.stringify(formData),
                                dataType: "json",
                                contentType: "application/json; charset=utf-8",
                            }).done(function (asyncResponse) {

                                if (asyncResponse.hasOwnProperty('success') && asyncResponse['success']) {
                                    setTimeout(function () {
                                        tmsApp.showSystemMessage(
                                            'Assign Task',
                                            asyncResponse['message'],
                                            function () {
                                                // window.location.href = asyncResponse["redirectUrl"]
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
                                            'Assign Task',
                                            asyncResponse['message'],
                                            function () {
                                            }, 'error');
                                    }, 300);
                                }
                            }).fail(function (xhr, settings, errorThrown) {
                                console.log(errorThrown)
                                setTimeout(function () {
                                    if ('responseJSON' in xhr) {
                                        if (xhr.responseJSON.hasOwnProperty('errors')) {
                                            tmsApp.printErrorMsg(xhr.responseJSON.errors);
                                        }
                                        if (xhr.responseJSON.hasOwnProperty('message')) {
                                            tmsApp.systemError(
                                                'Assign Task',
                                                xhr.responseJSON['message']
                                            );
                                        }
                                        return;
                                    }

                                    tmsApp.systemError(
                                        'Assign Task',
                                        'We could not complete processing your request, please try again later');
                                }, 300)
                            });
                        }
                    );
                });

                //first hide the buttons
                $('#submit_possible').hide();

                $('#submit_not_possible').hide();

                $("#divSubmit_hide").hide();

                //disable the submit button
                $("#btnSubmit").on('click', function () {
                    $("#create_form").submit(function (e) {
                        e.preventDefault()
                        //do something here
                        $("#divSubmit_show").hide();
                        $("#divSubmit_hide").show();
                        //continue submitting
                        e.currentTarget.submit();
                    });
                });

                $(document).on('click', '.addItemRow', function () {
                    const tableId = $(this).attr('data-table-id');
                    insertNewTableRow(tableId)
                });

                $(document).on('click', '.deleteTaleRow', function () {
                    const tableID = $(this).attr('data-table-id');
                    try {
                        const table = document.getElementById(tableID);
                        let rowCount = table.rows.length;

                        for (let i = 0; i < rowCount; i++) {
                            const row = table.rows[i];
                            const chkbox = row.cells[0].childNodes[0];
                            if (null != chkbox && true == chkbox.checked) {
                                if (rowCount <= 1) {
                                    alert("Cannot delete all the rows.");
                                    break;
                                }
                                table.deleteRow(i);
                                rowCount--;
                                i--;
                            }
                        }
                        $('[name="imprestItemUnitPrice"]').change();
                    } catch (e) {
                        alert(e);
                    }
                });
            });

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
                    $(row).find('[name="quantity"]').attr('max', article['quantity_in_store']);
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
                    })
                    .catch(function (error) {
                        // notify of error
                        console.log(error);
                        toastr.error('Connection error. Could not retrieve data, some feature might not work.')
                    });
            }

            function getArticlesForSelectedItemType(selectedItemType) {

                if (window[selectedItemType]) {
                    const data = window[selectedItemType];
                    let articlesCtrl = $(".pettyCashItemsTable").find('.imprestArticles');
                    tmsApp.populateDropDownList(articlesCtrl, data, 'code_article', ['code_article', 'description'], ':');
                    return;
                }

                $.ajax({
                    delay: 250,
                    beforeSend: function () {
                        window.showLoaderModal(true);
                        window.loaderVisible = false;
                    },
                    url: $('#getArticlesUrl').val(),
                    dataType: 'json',
                    data: {
                        type_article: document.querySelector('#pettyCashBuyItemType').value,
                        store_code: document.querySelector('#pettyCashStoreCode').value,
                    },
                    success: function (data) {
                        if (data.success) {
                            window[selectedItemType] = data.items;
                            let articlesCtrl = $(".pettyCashItemsTable").find('.imprestArticles');
                            tmsApp.populateDropDownList(articlesCtrl, data.items, 'code_article', ['code_article', 'description'], ':')
                        }
                    },
                    cache: true
                });
            }

            function findMechanic($row, mechanic) {
                if (!mechanic) {
                    return;
                }

                fetch(
                    $('#mechanicDetails').val() + '?staff_no=' + mechanic,
                    {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify({staff_no: mechanic}),
                        referrer: window.baseUrl,
                        mode: 'cors',
                        credentials: 'same-origin',
                    }
                )
                    .then((response) => {
                        if (!response.ok) {
                            tmsApp.systemError(
                                'System Message',
                                'We could not complete Mechanic state checks',
                                function () {
                                });
                            return;
                        }

                        return response.json();
                    })
                    .then(response => {

                        if (response?.state === 'success') {
                            const $documentStatusCtl = $('[name="documentStatus"]');
                            const documentStatus = $documentStatusCtl.val()
                            const newDocumentStatus = $('[name="newDocumentStatus"]').attr('data-value');

                            if (
                                documentStatus === newDocumentStatus
                                || documentStatus === null
                                || documentStatus === ""
                                || documentStatus === 'undefined'
                            ) {
                                //populateVehicleDetails(response.payload, "");
                                //$($row).find('[name="hoursWorked"]').attr('readonly', false);
                                //$($row).find('[name="shiftType"]').attr('disabled', false);
                            }

                            $($row).find('[name="mechanicName"]').val(response?.payload['mechanic'].name);
                            $($row).find('[name="postCode"]').val(response?.payload['employee']['job_code']);
                            $($row).find('[name="workshopSection"]').val(response?.payload['mechanic']['section_code']).change();
                        } else {
                            //removeSubmissionAndDetailsOptions();
                            tmsApp.systemError(
                                'Mechanic',
                                'Mechanic with Staff No.' + mechanic
                                + ' was not found, Check your input and try again',
                                function () {
                                });
                        }
                    })
                    .catch(function (error) {
                        tmsApp.systemError(
                            'System Message',
                            'We could not complete Mechanic state checks',
                            function () {
                            });
                    });
            }

            function disableControls() {

                let fuel_level = document.querySelector('select[name="fuel_level"]');
                if (!fuel_level.hasAttribute('data-select2-id="fuel_level"')) {
                    $(fuel_level).select2({"disabled": true});
                }

                let sub_fuel_level = document.querySelector('select[name="sub_fuel_level"]');
                if (!sub_fuel_level.hasAttribute('data-select2-id="sub_fuel_level"')) {
                    $(sub_fuel_level).select2({"disabled": true});
                }

                let workshop = document.querySelector('select[name="workshop"]');
                if (!workshop.hasAttribute('data-select2-id="workshop"')) {
                    $(workshop).select2({"disabled": true});
                }

                $('select[name="repairType"]').attr("disabled", true);
                $('[name="vehicleSearchBtn"]').attr("disabled", true);
                $('[name="employeeSearchBtn"]').attr("disabled", true);
                $('[data-table-id="observations"]').attr('disabled', true);
                $('[name="driver_staff_number"]').attr('disabled', true);
                $('.selectAttachment').attr('disabled', true);

                $('[name="current_odometer"]').attr('readonly', true);
                $('[name="observation[]"]').attr('readonly', true);
                $('[name="accessoriesRemarks"]').attr('readonly', true);

            }

            /*****************************Function Handlers************************************/
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
                    || formSel.data('modelName') === 'SummaryHeader'
                ) {
                    $(formElements).find("tbody").children().map(function (index, row) {
                        let obj = {};
                        /*$('#part8').find("tbody").children().map(function (index, row) {*/

                        if ($(row).attr('data-record-id') && $(row).attr('data-record-id') !== "0") {
                            console.log("Record with " + $(row).attr('data-record-id'));
                        } else {
                            $(row).find('input[name][type!=hidden], select[name],textarea[name]').each(function (i, item) {
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
                        }
                    });

                    if (arr.length === 0) {
                        tmsApp.systemError("Request Submission", 'No New Records Available for Saving');
                    }

                    obj['workshop_reference'] = $('input[name="workshop_reference"]').val();

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
                        obj['store_name'] = $('[name="store_name"]').val();
                        obj['remarks'] = $('#service_comments').val();
                        obj['total_amount'] = $('#serviceTotalPrice').text();
                        obj['vehicle_registration'] = $('input[name="vehicle_registration"]').val();
                    }
                } else {
                    $($container).find('input[name], select[name]').each(function (i, item) {
                        // let val = item.value.replace(/,/g, '');
                        // if($(item).parent())
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
                                    window.location.reload();// = response['redirectUrl'];
                                    return;
                                }

                                if (window.global_currentIndex === 2) {
                                    window.goToNext = true;
                                    form.steps("next");
                                } else {
                                    window.location.reload();// = response['redirectUrl'];
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

            function initializeFormWizard() {
                //let stepId = oldIndex;
                //window.global_currentIndex = stepId - 1;

                const index = 'step';
                //  Define friendly data store name
                const dataStore = window.sessionStorage;
                let stepId = window.step_id;
                try {
                    // getter: Fetch previous value
                    stepId = dataStore.getItem(index) ? dataStore.getItem(index) : window.step_id;
                } catch (e) {
                    // getter: Always default to first tab in error state
                    stepId = 0;
                }

                form.steps({
                    showStepURLhash: true,
                    headerTag: "h1",
                    bodyTag: "section",
                    transitionEffect: "slideLeft",
                    autoFocus: true,
                    saveState: true,
                    startIndex: parseInt(stepId),
                    enableFinishButton: false,
                    labels: {
                        finish: 'Submit'
                    },
                    onInit: function () {
                        console.log('Wizard Initializing')
                    },
                    onStepChanging: function (event, currentIndex, newIndex) {

                        if (currentIndex > newIndex) {
                            dataStore.setItem(index, newIndex);
                            return true;
                        }

                        const driverAcknowledged = $('#driverAcknowledged').val();

                        if (currentIndex === 1 && driverAcknowledged === 'Y') {
                            return true;
                        }

                        if (currentIndex === 0 || currentIndex === 2 && $('[name="job_card_number"]').val()) {
                            dataStore.setItem(index, newIndex);
                            return true;
                        }

                        if (currentIndex === 3 && $('[name="job_card_number"]').val()) {
                            dataStore.setItem(index, newIndex);
                            return true;
                        }

                        if (currentIndex < newIndex) {
                            // To remove error styles
                            form.find(".body:eq(" + newIndex + ") label.error").remove();
                            form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                        }

                        form.validate().settings.ignore = ":disabled,:hidden";
                        window.global_currentIndex = currentIndex;
                        /* if (form.valid() && !window.goToNext) {
                             tmsApp.confirm('Confirm', 'Do you want to save the changes ?', 'Yes', 'No', function () {
                                 postData(form.find('[data-model-name]').get(currentIndex), false);
                             }, function () {
                             });
                         }

                         let tmp = window.goToNext;
                         window.goToNext = false;
                         return tmp;*/
                    },
                    onStepChanged: function (event, currentIndex, priorIndex) {

                        if (currentIndex === 2 && priorIndex === 3) {
                            //form.steps("previous");
                            $('ul[aria-label="Pagination"]').find('a[href="#finish"]').removeClass('d-none');
                        }

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
                            },
                            function () {
                            });
                    }
                });

                $(document).on('click', '#saveDefects', function () {
                    if (form.valid()) {
                        tmsApp.confirm('Confirm', 'Do you want to save the changes ?', 'Yes', 'No', function () {
                            postData(
                                $('#part8'),
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

            function getFuelLevels() {
                fetch(document.querySelector('#fuelLevelsUrl').value)
                    .then(response => response.json())
                    .then(response => {
                        let $mainTankFuelLevel = $('select[name="fuel_level"]');
                        let $subTankFuelLevelCtl = $('select[name="sub_fuel_level"]');

                        if (response.state === 'failure') {
                            toastr.error('Connection error, no fuel data found')
                            return;
                        }

                        let fuelLevels = response['payload'];
                        tmsApp.populateDropDownList($mainTankFuelLevel, fuelLevels, "code", ["name"], "");
                        tmsApp.populateDropDownList($subTankFuelLevelCtl, fuelLevels, "code", ["name"], "");

                        let mainTankLevel = $mainTankFuelLevel.attr('data-value');

                        if (location) {
                            $mainTankFuelLevel.val(mainTankLevel);
                            $mainTankFuelLevel.trigger('change');
                        }

                        let subTankFuelLevel = $subTankFuelLevelCtl.attr('data-value');

                        if (location) {
                            $subTankFuelLevelCtl.val(subTankFuelLevel);
                            $subTankFuelLevelCtl.trigger('change');
                        }
                    })
                    .catch(function (error) {
                        // notify of error
                        toastr.error(
                            'Connection error. Could not retrieve data, some feature might not work.')
                    });
            }

            function loadDefectDropdownLists(data, selectElem) {
                tmsApp.populateDropDownList(selectElem, data, "code", ["description"], "");

                let location = selectElem.attr('data-value');

                if (location) {
                    selectElem.val(location);
                    selectElem.trigger('change');
                }
            }

            function loadData(key, url, selectElem) {
                fetch(url)
                    .then(response => response.json())
                    .then(response => {

                        if (response.state === 'failure') {
                            toastr.error('Connection error, no data found')
                            return;
                        }

                        let data = response['payload'];
                        window[key] = response['payload'];
                        loadDefectDropdownLists(data, selectElem);
                    })
                    .catch(function (error) {
                        toastr.error(
                            'Connection error. Could not retrieve data, some feature might not work.')
                    });
            }

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

            function findDriver() {
                const staff_number = document.querySelector('#driver_staff_number').value;
                if (!staff_number) {
                    return;
                }

                let formData = new FormData();
                formData.append('searchCriteria', staff_number);

                fetch(
                    document.querySelector("#driver_staff_number").getAttribute('data-action'),
                    {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: formData,
                        referrer: window.baseUrl,
                        mode: 'cors',
                        credentials: 'same-origin',
                    }
                )
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }

                        return response.json();
                    })
                    .then(response => {

                        if (!response.success || response.payload.length == 0) {
                            tmsApp.systemError('Driver Verification', response['message']);
                            return;
                        }

                        let optionListStr = '';
                        if (Array.isArray(response.payload)) {
                            response.payload.forEach(function (item) {
                                optionListStr += `<option value="${item['con_per_no']}">${item['con_per_no']} =>${item.name}</option>`;
                            })

                            $('#employee_list').html(optionListStr);
                            return;
                        }

                        document.querySelector('#driver_name').value = response.payload.name;
                    })
                    .catch(function (xhr, settings, error) {
                        tmsApp.showErrorMessages(xhr, 'Driver Validation');
                    });
            }

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
                    case 'imprestItemQty':
                        let imprestSummaryTotalQty = 0;
                        $(element).closest("table").find("input[name=imprestItemQty]").each(function (i, it) {
                            imprestSummaryTotalQty += Util.getFloat(it.value);
                        });

                        // set value in footer
                        // $('#serviceQuantityTotal').text(tmsApp.getRawNumber(imprestSummaryTotalQty));

                        let imprestLineAmountTotal = tmsApp.getFloat(element.value)
                            * tmsApp.getFloat($(element).closest("tr").find("input[name=imprestItemUnitPrice]").val());
                        $(element).closest("tr").find("input[name=imprestItemTotalPrice]").val(tmsApp.formatMoney(imprestLineAmountTotal, 2));//.change();

                        // $(element).closest("tr").find("#imprestItemTotalPrice").text(tmsApp.numberFormat(imprestLineAmountTotal));

                        const inps = document.getElementsByName('imprestItemTotalPrice');
                        let total = 0;
                        for (let i = 0; i < inps.length; i++) {
                            const inp = inps[i];
                            total = total + tmsApp.getFloat(inp.value || 0);
                        }
                        total = total.round(2);

                        if (!isNaN(total)) {
                            //check if petty cash is below 2000
                            if (total > 2000) {
                                $('#submit_possible').hide();
                                $('#submit_not_possible').show();
                            } else if (total === 0) {
                                $('#submit_not_possible').hide();
                                $('#submit_possible').hide();
                            } else {
                                $('#submit_not_possible').hide();
                                $('#submit_possible').show();
                            }
                            //set value
                            document.getElementById('total-payment').value = tmsApp.formatMoney(total, 2);
                        }
                        break;
                    case 'imprestItemUnitPrice':
                        break;
                    default:
                        break;
                }
            }

            function setSelectedAccessories() {
                $.each(selectedAccessories, function (index, element) {
                    $("input[name=field_" + element?.code + "][value=" + element?.is_present + "]")
                        .prop('checked', true)
                        .attr('disabled', true);
                    $("input[name=comment_" + element.code + "]")
                        .val(element?.remarks)
                        .attr('disabled', true);
                });
            }

            function getVehicleDefectCategory(selectedValue, selectElem) {
                if (!selectedValue) return;
                loadData(
                    'WCT',
                    document.querySelector('#defectCategoryUrl').value + '?key=' + selectedValue,
                    selectElem
                );
            }

            function getVehicleDefects(selectedValue, selectElem) {
                if (!selectedValue) return;
                loadData(
                    'WDF',
                    document.querySelector('#defectUrl').value + '?key=' + selectedValue,
                    selectElem
                );
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

            function tableHasItems() {
                let inputs = $("#material_table > tbody").find('.articleCode');
                for (const input of inputs) {
                    if (input.value > "") {
                        return true;
                    }
                }
                return false;
            }

            function pettyCashTableHasItems() {
                let inputs = $(".pettyCashItemsTable > tbody").find('[name="imprestArticleCode"]');
                for (const input of inputs) {
                    if (input.value > "") {
                        return true;
                    }
                }
                return false;
            }

            function changePettyCashRequestType(selectedItemType) {

                if (document.querySelector('[name="stockItemCode"]').value == selectedItemType) {
                    // showStockItemControls();
                    document.querySelector('#pettyCashSupplierContainer').style.display = 'none';
                    //document.querySelector('[name="supplier"]').removeAttribute('required');
                    document.querySelector('[name="imprestBuySupplier"]').removeAttribute('required');

                    document.querySelector('#pettyCashStoreContainer').style.display = null;
                    //document.querySelector('[name="store_code"]').setAttribute('required', 'required');
                    $('.quantity').attr('readonly', false);


                } else if (selectedItemType == document.querySelector('[name="serviceItemCode"]').value) {
                    document.querySelector('#pettyCashSupplierContainer').style.display = null;
                    //document.querySelector('[name="supplier"]').setAttribute('required', 'required');
                    document.querySelector('[name="imprestBuySupplier"]').setAttribute('required', 'required');

                    document.querySelector('#pettyCashStoreContainer').style.display = 'none';
                    //document.querySelector('[name="store_code"]').removeAttribute('required');
                    $('.quantity').attr('readonly', 'readonly').val(1);
                } else if (selectedItemType == document.querySelector('[name="nonStockItemCode"]').value) {
                    document.querySelector('#pettyCashSupplierContainer').style.display = null;
                    document.querySelector('[name="imprestBuySupplier"]').setAttribute('required', 'required');
                    //document.querySelector('[name="supplier"]').setAttribute('required', 'required');

                    document.querySelector('#storeContainer').style.display = 'none';
                    //document.querySelector('[name="store_code"]').removeAttribute('required');

                    $('.quantity').attr('readonly', false);
                    $('[name="unit_price"]').attr('readonly', false);

                } else {
                    document.querySelector('#pettyCashSupplierContainer').style.display = null;
                    document.querySelector('[name="imprestBuySupplier"]').setAttribute('required', 'required');
                    document.querySelector('#pettyCashStoreContainer').style.display = 'none';

                    $('.quantity').attr('readonly', false);
                }

                if (selectedItemType) {
                    enableArticleSelectionWebUIControls();
                }

                getArticlesForSelectedItemType(selectedItemType);
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

            function addTableRow(tableId) {
                Table.addRow($('table#' + tableId));
                let lastRow = $('table#' + tableId).find('tbody tr').eq((0 + 1) * -1);

                lastRow.find('button[value="deleteRow"]').attr('data-value', 0);
                lastRow.attr('data-record-id', 0);

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

            function reinitializeSelect2($_defect_sel) {
                if ($_defect_sel) {
                    $($_defect_sel).removeClass('select2-hidden-accessible');
                    $($_defect_sel).select2({
                        theme: "bootstrap4",
                        width: "resolve",
                    });
                }
            }

            function insertNewTableRow(tableId) {
                const $table = $('table#' + tableId);
                Table.addRow($table);
                let lastRow = $table.find('tbody tr').eq((0 + 1) * -1);

                $(lastRow).find('.select2-container').remove();

                $(lastRow).find('.articlesDropDownList').removeClass('select2-hidden-accessible');

                let $articleSelectElement = $table.find(".imprestArticles");
                reinitializeSelect2($articleSelectElement)

                /*const rowCount = table.rows.length;
                const row = table.insertRow(rowCount);

                const colCount = table.rows[0].cells.length;

                for (let i = 0; i < colCount; i++) {

                    const newCell = row.insertCell(i);

                    newCell.innerHTML = table.rows[0].cells[i].innerHTML;

                    switch (newCell.childNodes[0].type) {
                        case "text":
                            newCell.childNodes[0].value = "";
                            break;
                        case "checkbox":
                            newCell.childNodes[0].checked = false;
                            break;
                        case "select-one":
                            newCell.childNodes[0].selectedIndex = 0;
                            break;
                    }
                }*/
            }

            function insertTableRow(tableId) {
                const $table = $('table#' + tableId);
                if (tableId === "material_table" || tableId === "services_table") {
                    const itemType = document.querySelector('[name="itemType"]').value;
                    // check if item type has been selected
                    if (!itemType) {
                        Swal.fire({
                            text: "Select Item Type",
                            icon: "warning",
                            showCancelButton: false,
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                                cancelButton: "btn fw-bold btn-active-light-primary"
                            }
                        });
                        return;
                    }

                    // if supplier has been selected for service and non-stock
                    if (document.querySelector('[name="stockItemCode"]').value === itemType) {
                        // check that supplier is selected
                        if (!document.querySelector('[name="workshop_code"]').value) {
                            Swal.fire({
                                text: "Select a Workshop",
                                icon: "warning",
                                showCancelButton: false,
                                buttonsStyling: false,
                                confirmButtonText: "Ok",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                    cancelButton: "btn fw-bold btn-active-light-primary"
                                }
                            });
                            return;
                        }
                    } else {
                        // check that supplier is selected
                        if (!document.querySelector('[name="supplier"]').value) {
                            Swal.fire({
                                text: "Select a Supplier",
                                icon: "warning",
                                showCancelButton: false,
                                buttonsStyling: false,
                                confirmButtonText: "Ok",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                    cancelButton: "btn fw-bold btn-active-light-primary"
                                }
                            });
                            return;
                        }
                    }
                }

                if (tableId === "material_table") {
                    const vehicleReg = $table.find('tbody').find('[name="registration"]').val();
                    $table.find('tbody').append(materialTableRowTemplate);
                    $table.find('tbody').find('[name="registration"]').val(vehicleReg).attr('readonly');
                } else if (tableId === "services_table") {
                    const vehicleReg = $table.find('tbody').find('[name="vehicle_registration"]').val();
                    $table.find('tbody').append(serviceTableRowTemplate);
                    $table.find('tbody').find('[name="vehicle_registration"]').val(vehicleReg).attr('readonly');

                } else if (tableId === "part8") {
                    $table.find('tbody').append(defectTableRowTemplate);
                    /*if (tableId === "part8") {
                        if ($('.select_2_control').data('select2')) {
                            $('.select_2_control').select2('destroy');
                        }
                    }*/
                }

                let lastRow = $table.find('tbody tr').eq((0 + 1) * -1);

                lastRow.find('button[value="deleteRow"]').attr('data-value', 0);
                lastRow.attr('data-record-id', 0);

                if (tableId === "material_table") {
                    lastRow.find('[name="technical_specification"]').val('').attr('readonly', false);
                    if (itemType === document.querySelector('[name="stockItemCode"]').value) {
                        lastRow.find('[name="quantity"]').val('').attr('readonly', false);
                        lastRow.find('[name="unit_price"]').val('').attr('readonly', true);
                    } else {
                        lastRow.find('[name="quantity"]').val('').attr('readonly', false);
                        lastRow.find('[name="unit_price"]').val('').attr('readonly', false);
                    }

                    lastRow.find('[name="articles"]').attr('readonly', false);
                    lastRow.find('[name="unit_of_measure"]').val('');
                    lastRow.find('[name="total_price"]').val('');
                    lastRow.find('#unit_price').text('');
                }

                if (tableId === "services_table") {
                    $(lastRow).find('.select2-container').remove();
                    $(lastRow).find('.servicesArticlesDropDownList').removeClass('select2-hidden-accessible');
                    lastRow.find('[name="service_article"]').val('');
                    lastRow.find('[name="serviceArticleCode"]').val('');
                    lastRow.find('[name="service_technical_specification"]').val('');
                    lastRow.find('[name="service_unit_price"]').val('');
                    lastRow.find('[name="service_unit_of_measure"]').val('');
                    lastRow.find('[name="service_total_price"]').val('');
                    let $_defect_sel_ = $(lastRow).find(".servicesArticlesDropDownList");
                    initServiceArticleSelector($_defect_sel_);
                }

                if (tableId === "material_table") {
                    let row = lastRow[0];
                    $(row).find('.select2-container').remove();
                    $(row).find('.articlesDropDownList').removeClass('select2-hidden-accessible');

                    let article = $(row).find('input.articleCode').val();

                    let $_defect_sel = $(row).find(".articlesDropDownList");
                    let $_defect_sel_ = $(row).find(".DropDownList");
                    initArticleSelector($_defect_sel);
                    initArticleSelector($_defect_sel_);
                }

                if (tableId === "part8") {
                    let row = lastRow[0];
                    const $vehicleSystem = $(row).find('[name="vehicleSystem"]');
                    $vehicleSystem.attr('disabled', false)
                    /*loadData('',
                        document.querySelector('#systemsUrl').value + '?key=VEH_SYS',
                        );*/
                    loadDefectDropdownLists(window['VEH_SYS'], $vehicleSystem);

                    $(row).find('[name="defectCategory"]').attr('disabled', false)
                    $(row).find('[name="defect"]').attr('disabled', false)
                    $(row).find('[name="workshopSection"]').attr('disabled', false)

                    $(row).find('.select2-container').remove();
                    let $_defect_sel = $(row).find(".select_2_control");
                    reinitializeSelect2($_defect_sel);
                }
            }

            function deleteTableRow(eventSource) {

                let btnEl = $(eventSource);
                let tableId = $(btnEl).closest('table').attr('id');
                let valueId = $(btnEl).attr('data-value');
                let tableRow = btnEl.closest('tr');
                let table = btnEl.closest('table');

                tmsApp.confirm(
                    "Are you sure ?",
                    "The data entered on this line will be cleared out, if not saved already, you will not be able to recover it",
                    "Yes",
                    "No",
                    function () {

                        $(tableRow).remove();
                        //Table.deleteRow();

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
                        } else if (tableId === "material_table") {
                            dataUrl = document.querySelector('[name="deleteMaterialUrl"]').value;
                        } else {
                            dataUrl = document.querySelector('[name="deleteServiceUrl"]').value;
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

                $("#pettyCashBuyItemType").on('change', function () {
                    const selectedItemType = this.value;

                    if (pettyCashTableHasItems()) {
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
                                changePettyCashRequestType(selectedItemType);
                            }
                        });
                        return;
                    }

                    changePettyCashRequestType(selectedItemType);
                });

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

                $(document).on('change', 'select[name="vehicleSystem"]', function () {
                    if (!this.value) return;
                    const tr = $(this).closest('tr');
                    let selectElem = tr.find('select[name="defectCategory"]');
                    getVehicleDefectCategory(this.value, selectElem);
                });

                $(document).on('change', 'select[name="defectCategory"]', function () {
                    if (!this.value) return;
                    const tr = $(this).closest('tr');
                    let selectElem = tr.find('select[name="defect"]');
                    getVehicleDefects(this.value, selectElem);
                })

                $(document).on('click', '#closeSignatureModal', function () {
                    let modal = '';
                    let myModalEl = document.querySelector('#eSignatureModal')
                    if (myModalEl) {
                        //if (bootstrap) {
                        modal = bootstrap.Modal.getOrCreateInstance(myModalEl);
                        // }
                    }

                    if (modal) {
                        modal.hide();
                    }
                })

                $(document).on('keyup paste', '[name="vehicle_registration"]', function () {
                    if (!this.value || this.value.replace('_', '').length < 4) {
                        return;
                    }

                    removeSubmissionAndDetailsOptions();
                    findVehicle();
                });

                $(document).on('submit', 'form[name="eSignDocument"]', function (e) {
                    let modal = '';
                    let myModalEl = document.querySelector('#eSignatureModal')
                    if (myModalEl) {
                        if (bootstrap) {
                            modal = bootstrap.Modal.getOrCreateInstance(myModalEl);
                        }
                    }

                    e.preventDefault();
                    e.stopPropagation();
                    let $form = document.forms['eSignDocument'];

                    if (!$($form).valid()) {
                        toastr.warning("Sorry, the data did not pass validation check, check the data and try again.");
                        return;
                    }

                    let formData = new FormData($form);
                    tmsApp.play_alert('sound-submit');
                    tmsApp.asyncPostFormData(
                        $form.action,
                        formData,
                        function (response) {
                            window.loaderMessage = "Loading... please wait";
                            if (response.hasOwnProperty("success") && response.success) {
                                const message = response.message > ""
                                    ? response.message
                                    : "Assessment Signed successfully, Click 'Ok' to Proceed";

                                tmsApp.showSystemMessage(
                                    "Assessment Acknowledgement",
                                    message,
                                    function () {
                                        // window.location.reload();
                                        if (modal) {
                                            modal.hide();
                                            //window.loaderVisible = false;
                                        }
                                    },
                                    "success"
                                );
                            } else {
                                tmsApp.play_alert('sound-error');
                                if (!Util.isEmpty(response.errors)) {
                                    if (response.errors) {
                                        tmsApp.printErrorMsg(response.errors);
                                    }
                                } else if (!Util.isEmpty(response.message)) {
                                    tmsApp.systemError("Assessment Acknowledgement", response.message);
                                }
                            }
                        },
                        function (xhr) {
                            tmsApp.play_alert('sound-error');
                            tmsApp.showErrorMessages(xhr, "Assessment Acknowledgement",);
                        },
                        'POST'
                    );
                });

                $(document).on('click', '#vehicleSearchBtn', function () {
                    if (!document.querySelector('[name="vehicle_registration"]').value) {
                        return;
                    }
                    removeSubmissionAndDetailsOptions();
                    findVehicle();
                });

                setTimeout(function () {
                    $(document).on('keyup paste', '#driver_staff_number', function () {
                        if (!this.value) {
                            return;
                        }
                        if (this.value.length < 5) {
                            return;
                        }

                        findDriver();
                    });
                }, 300);

                setTimeout(function () {
                    $(document).on('click', '#employeeSearchBtn', function () {
                        if (!document.querySelector("#driver_staff_number").value
                            || document.querySelector("#driver_staff_number").value.length < 5) {
                            toastr.warning('Invalid Employee Id Number')
                            return;
                        }

                        findDriver();
                    });
                }, 300);

                /*****************************Event Handlers*****************************************/

                $(document).on('keypress', '.number_input', function (event) {
                    tmsApp.numberOnly(event);
                });

                $(document).on('input', '.comments', function (event) {
                    this.value = this.value.toUpperCase();
                });

                $(document).on('input', '[name="jobCardInstruction"]', function (event) {
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

                $(document).on('change', '#repairTypeDropdownList', function () {
                    if (this.value === document.querySelector('[name="accidentRepairs"]').value) {
                        document.querySelector("#accidentRecordNo").classList.remove('d-none');
                    } else {
                        document.querySelector("#accidentRecordNo").classList.add('d-none');
                    }
                });

                $(document).on('change', 'input', function (e) {
                    eventHandler(this, e);
                }).on('keyup', 'input,textarea', function (e) {
                    eventHandler(this, e);
                });

                /*$(document).off('click', 'button[value="addRow"][data-table-id]')
                    .on('click', 'button[value="addRow"][data-table-id]', function () {
                        let tableId = $(this).data('tableId');
                        //addTableRow(tableId);
                    });*/

                $(document).on('click', 'button[value="insertRow"][data-table-id]', function () {
                    let tableId = $(this).data('tableId');
                    insertTableRow(tableId);
                });

                $(document).on('click', 'button[value="deleteRow"]', function (e) {
                    deleteTableRow(this);
                    return false;
                });
            }

            function getSuppliers() {
                fetch(document.querySelector('#suppliersList').value)
                    .then(response => response.json())
                    .then(function (response) {
                        let imprestBuySupplierElem = $('select[name="imprestBuySupplier"]');
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

                        tmsApp.populateDropDownList(imprestBuySupplierElem, suppliers,
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

                        let imprestSupplier = imprestBuySupplierElem.attr('data-value');
                        if (imprestSupplier) {
                            imprestBuySupplierElem.val(imprestSupplier);
                            imprestBuySupplierElem.trigger('change');
                        }
                    }).catch(function (error) {
                    toastr.error('Could not Retrieve Data, some feature might not work.', 'Connection error');
                });
            }

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

            initializeFormWizard();

            getWorkshops();

            getFuelLevels();

            loadData('VEH_SYS', document.querySelector('#systemsUrl').value + '?key=VEH_SYS', $('select[name="vehicleSystem"]'));

            initEventHandlers();

            getSuppliers();

        })(window.tmsApp || {}, jQuery)
    </script>

    <!--  -->
    <script type="text/javascript">

        Number.prototype.round = function (places) {
            return +(Math.round(this + "e+" + places) + "e-" + places);
        }

    </script>

@endpush
