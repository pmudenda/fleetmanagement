@php use Carbon\Carbon; @endphp
<div class="row pt-5">
    <div class="table-responsive">
        <table id="labour_table"
               data-model-name="Labour"
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
                            <select name="defect"
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
                                   name="mechanic"/>
                            {{--<div class="input-group">
                                <div class="input-group-addon">
                                    <button type="button" id="mechanicSearchBtn"
                                            name="mechanicSearchBtn"
                                            class="btn btn-success btn-sm border-radius-0">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>--}}
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
                                       name="reminderDueDate"
                                       id="reminderDueDate"
                                       class="form-control datePicker"
                                />
                                <div class="input-group-append"
                                     data-target="#dateIssued"
                                     data-action="openDatePicker">
                                    <div type="button"
                                         data-action="openDatePicker"
                                         class="input-group-text ui-datepicker-trigger">
                                        <i data-action="openDatePicker"
                                           class="fa fa-calendar"></i>
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
                                   class="form-control form-control-sm service_total_price"/>
                        </td>

                    </tr>
                @endforeach
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
    </div>
</div>
