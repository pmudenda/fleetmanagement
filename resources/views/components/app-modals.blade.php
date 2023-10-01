@php use Carbon\Carbon;use Illuminate\Support\Facades\Auth; @endphp
<div>
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container position-fixed top-0 end-0 p-3">

            <div id="liveToast" class="toast align-items-center text-bg-primary border-0"
                 role="alert"
                 aria-live="assertive" aria-atomic="true">
                <div class="toast-body bg-white">
                    Hello, world! This is a toast message.
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modal-auditTrail">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">
                        Document Audit Trail
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form name="documentAuditTrail"
                      action="{{route('document.audit.trail')}}">
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row">
                                    <label for="documentType" class="col-4 form-label">Document Type:</label>
                                    <div class="col-8">
                                        <select class="form-select" id="documentType" name="documentType">
                                            <option></option>
                                            <option value="08">STORE REQUISITION</option>
                                            <option value="09">STORE RESERVATION</option>
                                            <option value="11">PURCHASE PROCESS</option>
                                            <option value="12">PURCHASE REQUISITION</option>
                                            <option value="13">TENDER</option>
                                            <option value="14">PURCHASE ORDER</option>
                                            <option value="15">GOODS RECEIPT</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row">
                                    <label for="documentNumber" class="col-4 form-label">Document No.</label>
                                    <div class="col-8">
                                        <input class="form-control uppercase" name="documentNumber"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row">
                                    <label class="form-label col-4">Document Status</label>
                                    <div class="col-8">
                                        <select class="form-select" id="documentType" name="documentType">
                                            <option value="">--Select--</option>
                                            <option value="userUnit">User Unit</option>
                                            <option value="workshopSection">Section</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row">
                                    <label class="form-label col-4">Responsible No.</label>
                                    <div class="col-8">
                                        <input class="form-control" name="operator"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6"></div>
                        </div>
                        <div class="row mb-2">
                            <label class="form-label col-4">Period</label>
                            <div class="col-8 row pr-0">
                                <div class="col-6">
                                    <label class="form-label">From:</label>
                                    <div class="input-group">
                                        <input class="form-control"
                                               type="date"
                                               max="{{date('Y-m-d', strtotime(Carbon::now()))}}"
                                               name="periodFrom"/>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-6 pr-0">
                                    <label class="form-label">To.</label>
                                    <div class="input-group">
                                        <input class="form-control"
                                               max="{{date('Y-m-d', strtotime(Carbon::now()))}}"
                                               type="date" name="periodTo"/>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button"
                                class="btn btn-sm btn-success"
                                value="applyAuditTrailFilter">
                            <i class="fas fa-hand-grab-o"></i>
                            Get
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div class="modal" id="modal-followUp">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">
                        Document Follow Up
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="documentFollowUpForm"
                      name="documentFollowUpForm"
                      action="{{route('document.followup')}}">
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row">
                                    <label for="documentType" class="col-4 form-label">Document Type:</label>
                                    <div class="col-8">
                                        <select class="form-select" id="documentType" name="documentType">
                                            <option></option>
                                            <option value="08">STORE REQUISITION</option>
                                            <option value="09">STORE RESERVATION</option>
                                            <option value="11">PURCHASE PROCESS</option>
                                            <option value="12">PURCHASE REQUISITION</option>
                                            <option value="13">TENDER</option>
                                            <option value="14">PURCHASE ORDER</option>
                                            <option value="15">GOODS RECEIPT</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row">
                                    <label for="documentNumber" class="col-4 form-label">Document No.</label>
                                    <div class="col-8">
                                        <input class="form-control uppercase" name="documentNumber"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label class="form-label col-4">Period</label>
                            <div class="col-8 row pr-0">
                                <div class="col-6">
                                    <label class="form-label">From.</label>
                                    <div class="input-group">
                                        <input class="form-control periodFrom"
                                               type="date"
                                               max="{{date('Y-m-d', strtotime(Carbon::now()))}}"
                                               name="periodFrom"/>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-6 pr-0">
                                    <label class="form-label">To.</label>
                                    <div class="input-group">
                                        <input class="form-control periodTo"
                                               max="{{date('Y-m-d', strtotime(Carbon::now()))}}"
                                               type="date" name="periodTo"/>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button"
                                class="btn btn-sm btn-success"
                                value="documentFollowUpFilter">
                            <i class="fas fa-hand-grab-o"></i>
                            Get
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div class="modal" id="modal-taskFollowUp">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">
                        Document Task Tracking
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div id="filterProperty"
                         class="table">
                        <div class="row">
                            <div class="col">
                                <label>
                                    <select class="form-select" name="operator">
                                        <option value="=">Is</option>
                                        <option value="<>">Is not</option>
                                        <option value=">">Is After</option>
                                        <option value="<">Is Before</option>
                                    </select>
                                </label>
                            </div>
                            <div class="col">
                                <select class="form-select" name="filterValue">
                                </select>
                            </div>
                        </div>

                    </div>
                    <button type="button"
                            data-table-id="filterProperty"
                            class="btn btn-sm btn-primary add pull-left"
                            value="addRow">
                        <i class="fa fa-plus"></i> Add Property
                    </button>
                    <div class="clearfix"></div>
                </div>

                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button"
                            class="btn btn-sm btn-success"
                            value="applyFilter"> Apply Filter
                    </button>
                </div>

            </div>

        </div>
    </div>

    <div class="modal" id="modalSimulateUser">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        User Simulation
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form action="{{route('user.simulation.start')}}"
                      method="POST"
                      enctype="application/x-www-form-urlencoded"
                      name="startUserSimulationForm"
                      id="startUserSimulationForm">

                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label for="userIdentifier" class="app-field-label">
                                    User
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       required
                                       id="userIdentifier"
                                       data-action="{{route('get.user')}}"
                                       class="form-control form-control-sm"
                                       name="userIdentifier"
                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="app-field-label">
                                    Name
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       required
                                       readonly
                                       id="userNameIdentifier"
                                       class="form-control form-control-sm"
                                       name="userNameIdentifier"
                                />

                                <input type="hidden"
                                       id="staffNumberIdentifier"
                                       class="form-control form-control-sm"
                                       name="staffNumberIdentifier"
                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="app-field-label">
                                    Justification
                                </label>
                                <textarea
                                        id="simulationJustification"
                                        style="height: 129px;"
                                        required
                                        minlength="20"
                                        maxlength="255"
                                        class="form-control comments form-control-sm"
                                        name="simulationJustification"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-end">
                        <button type="button"
                                class="btn btn-default"
                                data-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit"
                                id="startSimulationBtn"
                                name="startSimulationBtn"
                                class="btn btn-sm btn-success">
                            Submit
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div class="modal fade" id="documentFollowUp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Document Follow Up</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="documentFollowUpContent">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>
