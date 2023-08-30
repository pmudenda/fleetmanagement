@php use App\Helpers\StatusHelper;use Carbon\Carbon; @endphp
<div class="row pt-5">
    <div class="table-responsive">
        <table id="labour_table"
               aria-label="Labour Table"
               data-model-name="SummaryHeader"
               class="table dataTable table-row-dashed align-middle gs-0 nowrap mt-10">
            <thead>
            <tr class="bg-success-subtle">
                <th>Defect</th>
                <th style="width: 6%;">Mechanic</th>
                <th style="width: 15%;"></th>
                <th style="width:10%;">Date</th>
                <th>Section</th>
                <th style="width: 11%;">Shift Type</th>
                <th style="width: 5%;">Hours</th>
                <th style="width: 5%;">Rate</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @if($labour->isNotEmpty())
                @foreach($labour as $labourItem)
                    <tr class="increment" data-record-id="{{$labourItem->id}}">
                        <td>
                            <input class="form-control form-control-sm"
                                   readonly
                            value="{{$labourItem->defect_name}}"/>
                            <input name="assignedDefectId"
                                   type="text"
                                   style="display: none;"
                                   value="{{$labourItem->defect_id}}"
                                   class="form-control-sm defect"/>
                            <input name="assignedDefect"
                                   type="text"
                                   style="display: none;"
                                   value="{{$labourItem->def_no}}"
                                   data-value="{{$labourItem->def_no}}"
                                   class="form-control-sm defect"/>
                        </td>
                        <td class="showNumber">
                            <input type="text"
                                   readonly
                                   data-value="{{$labourItem->mechanic}}"
                                   value="{{$labourItem->mechanic ?? ''}}"
                                   class="form-control form-control-sm mechanicStaffNumber"
                                   autocapitalize="characters"
                                   list="mechanics"
                                   id="mechanic"
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
                            <div class="input-group date">
                                <input type="text"
                                       required
                                       readonly
                                       name="dateOfWork"
                                       value="{{Carbon::parse($labourItem->date_lab)->format('d/m/Y') ?? ''}}"
                                       id="dateOfWork"
                                       class="form-control"
                                />
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <select name="workshopSection"
                                    required
                                    class="form-select form-select-sm workshopSection">
                                <option></option>
                                @foreach($workshop_sections as $workshop_section)
                                    @if($labourItem->section == $workshop_section->code)
                                        <option selected
                                                value="{{$workshop_section->code}}">{{$workshop_section->name}}</option>
                                    @else
                                        <option
                                                value="{{$workshop_section->code}}">{{$workshop_section->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="hidden"
                                   id="postCode"
                                   name="postCode"
                                   required
                                   class=""/>
                            <select name="shiftType"
                                    data-value="{{$labourItem->type_of_hour ?? ''}}"
                                    disabled
                                    required
                                    class="form-select form-select-sm shiftType">
                                <option selected value="" disabled></option>
                                <option value="1">Normal Shift</option>
                                <option value="2">Normal Over-Time</option>
                                <option value="4">Holiday Over-Time</option>
                            </select>
                        </td>
                        <td>
                            <input
                                    readonly
                                    value="{{$labourItem->hours_worked ?? ''}}"
                                    id="hoursWorked"
                                    readonly
                                    name="hoursWorked"
                                    required
                                    class="form-control form-control-sm"/>
                        </td>
                        <td>
                            <input
                                    id="ratePerHour"
                                    name="ratePerHour"
                                    required
                                    value="{{$labourItem->rate}}"
                                    readonly
                                    class="form-control form-control-sm"/>
                        </td>

                        <td>
                            <input name="totalAmount"
                                   required
                                   value="{{$labourItem->total_amount}}"
                                   readonly
                                   class="form-control form-control-sm labour_total_price"/>
                        </td>

                    </tr>
                @endforeach
            @else
                @if($defects && $defects->isNotEmpty())
                    @foreach($defects as $defect)
                        <tr class="increment">
                            <td>
                                <div class="d-none">
                                    <select name="vehicleSystem"
                                            style="display: none;"
                                            required
                                            disabled
                                            data-value="{{$defect->veh_sys}}"
                                            class="form-select form-select-sm select_2_control vehicleSystem">
                                        <option></option>
                                    </select>
                                    <select name="defectCategory"
                                            required
                                            style="display: none;"
                                            disabled
                                            data-value="{{$defect->defect_category_code}}"
                                            class="form-select form-select-sm select_2_control defectCategory">
                                        <option></option>
                                    </select>
                                </div>
                                <select name="assignedDefect"
                                        required
                                        disabled
                                        data-value="{{$defect->defect_code}}"
                                        class="form-select form-select-sm select_2_control defect">
                                    <option></option>
                                </select>
                            </td>
                            <td class="showNumber">
                                <input type="text"
                                       class="form-control form-control-sm"
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
                                <div class="input-group date">
                                    <input type="date"
                                           required
                                           name="dateOfWork"
                                           id="dateOfWork"
                                           class="form-control"
                                    />
                                    {{--<div class="input-group-append"
                                         data-target="#dateIssued"
                                         data-action="openDatePicker">
                                        <div type="button"
                                             data-action="openDatePicker"
                                             class="input-group-text ui-datepicker-trigger">
                                            <i data-action="openDatePicker"
                                               class="fa fa-calendar"></i>
                                        </div>
                                    </div>--}}
                                </div>
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
                                <input type="hidden"
                                       id="postCode"
                                       name="postCode"
                                       required
                                       class=""/>
                                <select name="shiftType"
                                        disabled
                                        required
                                        class="form-select form-select-sm shiftType">
                                    <option selected value="" disabled></option>
                                    <option value="1">Normal Shift</option>
                                    <option value="2">Normal Over-Time</option>
                                    <option value="4">Holiday Over-Time</option>
                                </select>
                            </td>
                            <td>
                                <input
                                        readonly
                                        id="hoursWorked"
                                        name="hoursWorked"
                                        required
                                        class="form-control form-control-sm"/>
                            </td>
                            <td>
                                <input
                                        id="ratePerHour"
                                        name="ratePerHour"
                                        required
                                        readonly
                                        class="form-control form-control-sm"/>
                            </td>

                            <td>
                                <input name="totalAmount"
                                       required
                                       readonly
                                       class="form-control form-control-sm labour_total_price"/>
                            </td>

                        </tr>
                    @endforeach
                @endif
            @endif
            </tbody>
            <tfoot>
            <tr>
                <td class="pl-2"></td>
                <td></td>
                <td></td>
                <td class="text-right"><strong></strong></td>
                <td class="text-right">
                    {{--<b id="serviceQuantityTotal"
                                          class="input-number">0</b>--}}
                </td>
                <td></td>
                <td></td>
                <td class="text-right"><strong>TOTAL</strong></td>
                <td class="text-right"><b id="labourTotalPrice"
                                          class="input-number">0.00</b></td>
            </tr>
            </tfoot>
        </table>
        <hr>
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
            @if($details->status == StatusHelper::new())
                <div>
                    <button type="button"
                            name="saveJobCardExit"
                            id="saveJobCardExit"
                            style="background: #f59d33; color: #fff;"
                            class="btn btn-sm btn-success add pull-right">
                        <i class="fa fa-save"></i>
                        Save
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
