@php
    use App\Enums\RepairTypes;use App\Enums\RequisitionItemTypes;use App\Helpers\StatusHelper;use Carbon\Carbon;
@endphp
@extends('layouts.app')
@push('styles')
    <link href="{{asset("assets/plugins/select2/css/select2.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css")}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset("libs/steps/jquery-steps.css")}}" rel="stylesheet" type="text/css"/>
    <style>
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
                      method="post">
                    @csrf
                    <h1>DETAILS</h1>
                    <section>
                        @include('modules.workshopManagement.workOrder.tabs.job_card_header')
                    </section>

                    <h1>ASSESSMENTS</h1>
                    <section>
                        @include('modules.workshopManagement.workOrder.tabs.accessories')
                        <div class="row mb-1 mt-4">
                            <div class="row">
                                <div class="col-lg-2 col-sm-12">
                                    <label>Assessment Acknowledgement:</label>
                                    <br/>
                                    <small class="text-danger">(Performed By Driver)</small>
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

                        @if(RepairTypes::ContractedService->value != $details->repair_type ?? '')
                            <h1>LABOUR & ASSIGNMENTS</h1>
                            <section>
                                @include('modules.workshopManagement.workOrder.tabs.labourAssignments')
                            </section>
                        @endif

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
                <input type="hidden" value="{{route('load.article.details')}}"
                       id="articleDetailsUrl"/>
                <input type="hidden" value="{{$details->job_card_no ?? ''}}"
                       id="job_card_number"/>
                <input type="hidden" value="{{$details->veh_reg ?? ''}}"
                       name="vehicle_registration"
                       id="vehicle_registration"/>
                <input type="hidden" value="{{$details->veh_reg ?? ''}}"
                       name="vehicle_reg_no"
                       id="vehicle_reg_no"/>
                <input type="hidden" value="{{$details->wshp_act_code ?? ''}}"
                       name="workshop_reference"
                       id="workshop_reference"/>

                <input type="hidden" value="{{route('delete.defect.record')}}"
                       name="deleteDefectUrl"
                       id="deleteDefectUrl"/>

                <input type="hidden" value="{{route('delete.pettyCashItem.record')}}"
                       name="deletePettyCashItemUrl"
                       id="deletePettyCashItemUrl"/>

                <input type="hidden" value="{{route('delete.material.record')}}"
                       name="deleteMaterialUrl"
                       id="deleteMaterialUrl"/>

                <input type="hidden" value="{{route('delete.service.record')}}"
                       name="deleteServiceUrl"
                       id="deleteServiceUrl"/>

                <input type="hidden" value="{{route('mechanic.search')}}" id="mechanicDetails"/>
            </div>
        </div>
        <input type="hidden"
               name="onboarding_status"
               id="onboarding_status"
               value="{{StatusHelper::onboardingComplete()}}">
    </section>

    <input type="hidden"
           value="{{StatusHelper::onboardingComplete()}}"
           name="incompleteOnBoarding"
           id="incompleteOnBoarding"/>
    <input type="hidden" value="{{StatusHelper::vehicleInWorkshop()}}"
           name="vehicleInWorkshop"
           id="vehicleInWorkshop"/>
    <input type="hidden" value="{{StatusHelper::active()}}"
           name="vehicleActive"
           id="vehicleActive"/>

    <input type="hidden"
           value="{{RequisitionItemTypes::STOCK_ITEM_CODE}}"
           id="stockItemCode"
           name="stockItemCode"/>

    <input type="hidden"
           value="{{RequisitionItemTypes::SERVICE_ITEM_CODE}}"
           id="serviceItemCode"
           name="serviceItemCode"/>

    <input type="hidden"
           value="{{RequisitionItemTypes::NON_STOCK_ITEM_CODE}}"
           id="nonStockItemCode"
           name="nonStockItemCode"/>

    <input type="hidden"
           value="{{RepairTypes::ContractedService->value}}"
           id="contractedServiceRepair"
           name="contractedServiceRepair"/>

    <input type="hidden"
           value="{{RepairTypes::AccidentRepair->value}}"
           id="accidentRepairs"
           name="accidentRepairs"/>

    <input type="hidden"
           id="suppliersList"
           value="{{route('suppliers.list')}}"/>

    <input type="hidden"
           value="{{route('load.reservations')}}"
           name="reservationsUrl"
           id="reservationsUrl"/>

    <div class="modal fade"
         id="eSignatureModal"
         data-bs-backdrop="static"
         data-bs-keyboard="false"
         tabindex="-1"
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
                                <div style="width:100%; height:100%; border:none;">
                                    <div class="row">
                                        <div style="text-align: center">
                                            Please wait . . .
                                            <br/>
                                            <br/>
                                            Signature being verified.
                                        </div>
                                    </div>
                                </div>
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

    <div class="modal fade"
         id="reassignMechanicModal"
         data-bs-backdrop="static"
         data-bs-keyboard="false"
         tabindex="-1"
         aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-pencil-square-o"></i>
                        Task Reassignment
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
                                                value="{{$workshop_section->code}}">
                                                {{$workshop_section->name}}
                                            </option>
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

    <div class="modal fade" id="reservations" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="exampleModalLabel">Job Card Has Reserved Materials</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="reservedMaterialsContent">
                    <table class="table table-bordered"
                           aria-label="Reserved Articles"
                           id="reservedMaterialsTable"
                           data-form-url="{{route('attach.reservations.card')}}">
                        <thead>
                        <tr style="text-wrap: nowrap;">
                            <th><input type='checkbox'
                                       name='reservedMaterials'
                                       value='' class="checkbox"/></th>
                            <th>SPMS Ref.</th>
                            <th>Item Type</th>
                            <th>Reference No.</th>
                            <th style="width: 10%;">Article Code</th>
                            <th>UOM</th>
                            <th>Specifications</th>
                            <th>Reg No.</th>
                            <th>Quantity</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" name="attachArticlesToJobCard" class="btn btn-sm btn-success pull-right">
                        <i class="fas fa-check"></i>
                        Confirm
                    </button>
                </div>
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
        window.workshopSections = {!! json_encode($workshop_sections) !!};
        window.jobCardProcessData = {
            articleNoPrice: 'The Article @articleNumber - @description has no price. ' +
                ' Please Contact Fleet Master System Administrator on 3309,3350,3351,3306,' +
                ' fleetmaster@zesco.co.com',
            storeHasNoStock: 'The Store @store '
                + ' does not have  @articleNumber'
                + ' - description  in stock. ' +
                'You may have to wait until the item is ' +
                'received before your request can be processed. ' +
                'Alternatively, You may use Imprest Buys',
            articleInStore: 'The Article  @articleNumber - @description  is available in  @store'
                + ' You can proceed to request from stores'
        }
        const defectTableRowTemplate = `
                                <tr class="increment">
                                    <td class="showNumber">
                                        <select name="vehicleSystem"
                                                 required
                                                class="form-select form-select-sm select_2_control vehicleSystem">
                                            <option></option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="defectCategory"
                                                required
                                                class="form-select form-select-sm select_2_control defectCategory">
                                            <option></option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="defect"
                                                 required
                                                class="form-select form-select-sm select_2_control defect">
                                            <option></option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="workshopSection"
                                        required class="form-select form-select-sm workshopSection">
                                            <option></option>
                                        </select>
                                    </td>
                                    <td>
                                        <input name="date_def"
                                               readonly="readonly"
                                               value='{{date('Y-m-d H:i:s', strtotime(Carbon::now()))}}'
                                               class="tabledit-input form-control input-sm input-number"
                                               type="text" />
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
    </script>
    <script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset("libs/steps/jquery.steps.js")}}"></script>
    <script src="{{asset('modules/workshopManagement/new.job_card.js')}}"></script>
@endpush
