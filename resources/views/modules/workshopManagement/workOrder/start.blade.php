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
                      data-model-name="PostJobCard"
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
                    </section>

                    <h1>DRIVER SIGN OFF</h1>
                    <section>
                        <div class="row mb-1 mt-4">
                            <div class="row">
                                <div class="col-lg-2 col-sm-12">
                                    <label>Assessment Acknowledgement:</label>
                                    <br/>
                                    <small class="text-danger">(To Be Performed By Driver)</small>
                                </div>
                                @if(!empty($details->driver_acknowledged))
                                    <div class="col-lg-2 col-sm-12">
                                        <span class="btn btn-sm btn-success">Acknowledged</span>
                                    </div>
                                @else
                                    <div class="col-lg-2 col-sm-12">
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
                                               required
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

                                @if(!empty($details->driver_acknowledged))
                                    <div class="row mt-10">
                                        <div class="col">
                                            <div class="form-group">
                                                <label
                                                    class="col-xs-12 col-sm-6 col-md-5 col-lg-4 pl-0 field-required"
                                                    for="commentsToSupervisor">
                                                    Comments To Workshop Supervisor:
                                                </label>
                                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8 pl-0">
                                                 <textarea type="text"
                                                           required
                                                           minlength="20"
                                                           maxlength="255"
                                                           id="commentsToSupervisor"
                                                           name="commentsToSupervisor"
                                                           style="height: 129px;"
                                                           class="form-control form-control-sm comments"></textarea>
                                                    {{--@if(!empty($comments))
                                                             <textarea type="text"
                                                                       id="accessoriesRemarks"
                                                                       name="accessoriesRemarks"
                                                                       style="height: 129px;"
                                                                       class="form-control form-control-sm">{{$comments->where('type','=','ACC')->first()->remarks ??''}}</textarea>
                                                         @else
                                                         @endif--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

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
           value="{{RequisitionItemTypes::STOCK_ITEM_CODE}}"
           id="stockItemCode"
           name="stockItemCode"/>

    <input type="hidden"
           value="{{RequisitionItemTypes::SERVICE_ITEM_CODE}}"
           id="serviceItemCode" name="serviceItemCode"/>

    <input type="hidden"
           value="{{RequisitionItemTypes::NON_STOCK_ITEM_CODE}}"
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
                    <h5 class="modal-title" id="staticBackdropLabel">
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
@endsection
@push('scripts')
    <script>
        window.selectedAccessories = {!! json_encode($accessories_checked_in) !!};
        window.step_id = {!! $step !!};
    </script>
    <script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset("libs/steps/jquery.steps.js")}}"></script>
    <script src="{{asset('libs/imageUpload/imageUpload.js')}}"></script>
    <script src="{{asset('modules/workshopManagement/start.js')}}"></script>
@endpush
