@php use App\Helpers\StatusHelper;use Carbon\Carbon; @endphp
<div class="row pt-5">
    <div class="table-responsive">
        <button type="button"
                class="btn btn-sm btn-primary d-none pull-right mb-5"
                value="reassignMultiple">
            <i class="fa fa-plus"></i>
            Reassign Selected
        </button>
        <button type="button"
                class="btn btn-sm btn-success d-none pull-right mb-5"
                value="assignMultiple">
            <i class="fa fa-plus"></i>
            Assign Selected
        </button>
        <table id="labour_table"
               data-model-name="SummaryHeader"
               data-form-url="{{route("save.job.assignment")}}"
               class="table dataTable table-row-dashed align-middle nowrap mt-10">
            <thead>
            <tr class="bg-success-subtle">
                <th>
                    <input name="selectAll"
                           title="Select All Defects"
                           class="checkbox" data-toggle="tooltip" type="checkbox">ALL
                </th>
                <th style="width: 16%;">DEFECT</th>
                <th style="width: 6%;">MECHANIC</th>
                <th style="width: 15%;">MECHANIC NAME</th>
                <th style="width: 15%;">SECTION</th>
                <th style="width: 29%;">JOB INSTRUCTION</th>
                <th style="width: 10%;">ACTION</th>
            </tr>
            </thead>
            <tbody>
            @if($labour->isNotEmpty())
                @foreach($labour as $labourItem)
                    <tr class="increment" data-record-id="{{$labourItem->id}}">
                        <td>
                            <input
                                readonly
                                name="selectDefectToAssign"
                                class="checkbox"
                                type="checkbox">
                        </td>
                        <td>
                            {{$labourItem->defect_name}}
                            <input name="assignedDefectId"
                                   type="text"
                                   style="display: none;"
                                   required
                                   value="{{$labourItem->defect_id}}"
                                   class="form-control-sm defect"/>
                            <input name="assignedDefect"
                                   type="text"
                                   style="display: none;"
                                   required
                                   value="{{$labourItem->def_no}}"
                                   data-value="{{$labourItem->def_no}}"
                                   class="form-control-sm defect"/>
                        </td>
                        <td class="showNumber">
                            <input type="text"
                                   class="form-control form-control-sm mechanicStaffNumber"
                                   autocapitalize="characters"
                                   id="mechanic"
                                   readonly
                                   data-value="{{$labourItem->mechanic}}"
                                   value="{{$labourItem->mechanic ?? ''}}"
                                   name="mechanic"/>
                        </td>
                        <td>
                            <input type="text"
                                   class="form-control form-control-sm"
                                   id="mechanicName"
                                   name="mechanicName"
                                   readonly/>
                        </td>
                        <td>
                            <select name="workshopSection"
                                    required
                                    disabled
                                    class="form-select form-select-sm workshopSection">
                                <option></option>
                                @foreach($workshop_sections as $workshop_section)
                                    @if($labourItem->section == $workshop_section->code)
                                        <option selected
                                                value="{{$workshop_section->code}}">
                                            {{$workshop_section->name}}
                                        </option>
                                    @else
                                        <option value="{{$workshop_section->code}}">
                                            {{$workshop_section->name}}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                        <td>
                             <textarea type="text"
                                       id="jobCardInstruction"
                                       minlength="20"
                                       maxlength="255"
                                       required
                                       readonly
                                       name="jobCardInstruction"
                                       style="height: 129px;"
                                       class="form-control comments form-control-sm">{{$labourItem->job_card_instruction}}</textarea>
                        </td>

                        <td>
                            <div class="dropdown">
                                <button class="btn btn-light btn-active-light-primary btn-sm dropdown-toggle"
                                        type="button"
                                        id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li>
                                        <button type="button"
                                                data-labour-item="{{json_encode($labourItem)}}"
                                                style="background: #f59d33; color: #fff;"
                                                class="btn btn-sm btn-success reassignMechanic col pull-right dropdown-item">
                                            <i class="fa fa-history"></i>
                                            Reassign
                                        </button>
                                    </li>

                                    <li>
                                    <li>
                                        <a class="dropdown-item" data-kt-action="edit"
                                           href="{{URL::signedRoute('print.job.card', ['reference' => $labourItem->id])}}">
                                            <i class="fa fa-print"></i>
                                            Print Job Card
                                        </a>
                                    </li>
                                    {{--<button type="button"
                                            title="Still Thinking Of Way To Make This"
                                            data-toggle="tooltip"
                                            data-labour-item="{{json_encode($labourItem)}}"
                                            class="btn btn-sm btn-success col mr-3 dropdown-item">
                                    </button>--}}
                                    </li>
                                </ul>
                            </div>
                        </td>

                    </tr>
                @endforeach
            @else
                @if($defects && $defects->isNotEmpty())
                    @foreach($defects as $defect)
                        <tr class="increment">
                            <td>
                                <input
                                    name="selectDefectToAssign"
                                    class="checkbox"
                                    type="checkbox">
                            </td>
                            <td>
                                {{$defect->defect_name}}
                                <input name="assignedDefectId"
                                       type="text"
                                       style="display: none;"
                                       required
                                       value="{{$defect->defect_id}}"
                                       class="form-control-sm defect"/>
                                <input name="assignedDefect"
                                       type="text"
                                       style="display: none;"
                                       required
                                       value="{{$defect->defect_code}}"
                                       data-value="{{$defect->defect_code}}"
                                       class="form-control-sm defect"/>
                            </td>
                            <td class="showNumber">
                                <input type="text"
                                       class="form-control form-control-sm mechanicStaffNumber"
                                       autocapitalize="characters"
                                       id="mechanic"
                                       list="mechanics"
                                       name="mechanic"/>
                            </td>
                            <td>
                                <input type="text"
                                       class="form-control form-control-sm"
                                       id="mechanicName"
                                       name="mechanicName"
                                       readonly/>
                            </td>
                            <td>
                                <select name="workshopSection"
                                        required
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
                                 <textarea type="text"
                                           id="jobCardInstruction"
                                           minlength="20"
                                           maxlength="255"
                                           required
                                           name="jobCardInstruction"
                                           style="height: 129px;"
                                           class="form-control comments form-control-sm"></textarea>
                            </td>
                            <td>
                                <button type="button"
                                        style="background: #f59d33; color: #fff;"
                                        class="btn btn-sm btn-success saveAssignment pull-right">
                                    <i class="fa fa-save"></i>
                                    Save Assignment
                                </button>
                            </td>

                        </tr>
                    @endforeach
                @endif
            @endif
            </tbody>
        </table>
        <hr>
        <datalist id="mechanics">
            @if(!empty($mechanics))
                @foreach($mechanics as $mechanic)
                    <option value="{{$mechanic->staff_no}}">{{$mechanic->staff_no}} {{$mechanic->name}}</option>
                @endforeach
            @endif
        </datalist>
        <div class="row">
            <div class="form-group">
                <label class="col-xs-12 col-sm-6 col-md-5 col-lg-4 pl-0 field-required"
                       for="remarks">
                    Comment:
                </label>
                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8 pl-0">
                    @if(!empty($taskHeader) && !empty($taskHeader->long_description))
                        <textarea type="text"
                                  id="closureRemarks"
                                  minlength="20"
                                  readonly
                                  maxlength="255"
                                  required
                                  name="closureRemarks"
                                  style="height: 129px;"
                                  class="form-control comments form-control-sm">{{$taskHeader->long_description ??''}}</textarea>
                    @else
                        @if($details->status === StatusHelper::pendingApproval())
                            <textarea type="text"
                                      id="closureRemarks"
                                      minlength="20"
                                      maxlength="255"
                                      readonly
                                      required
                                      name="closureRemarks"
                                      style="height: 129px;"
                                      class="form-control comments form-control-sm"></textarea>
                        @else
                            <textarea type="text"
                                      id="closureRemarks"
                                      minlength="20"
                                      maxlength="255"
                                      required
                                      name="closureRemarks"
                                      style="height: 129px;"
                                      class="form-control comments form-control-sm"></textarea>
                        @endif
                    @endif
                </div>
            </div>

        </div>

        <div class="col-12 text-right">
            <div>
                <button type="button"
                        id="saveAllAssignments"
                        style="background: #f59d33; color: #fff;"
                        data-table-id="labour_table"
                        class="btn btn-sm btn-success pull-right">
                    <i class="fa fa-save"></i>
                    Save All Assignments
                </button>
            </div>
        </div>
    </div>
</div>
