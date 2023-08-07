@php use Carbon\Carbon; @endphp
<div class="row pt-5">
    <div class="table-responsive">
        <table id="services_table mt-10"
               data-model-name="ServicesHeader"
               class="table dataTable table-row-dashed align-middle gs-0 nowrap">
            <thead>
            <tr class="bg-success-subtle">
                <th>Defect</th>
                <th style="width: 6%;">Mechanic</th>
                <th style="width: 15%;">Name</th>
                <th>Date</th>
                <th style="width: 25%;">Shift Type</th>
                <th style="width: 25%;">Hours</th>
                <th style="width: 4%; max-width: 4%;"></th>
                <th>Rate</th>
                <th>Unit Price</th>
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
                            <div class="input-group">
                                <input type="text"
                                       data-action="{{route('driver.search')}}"
                                       class="form-control form-control-sm"
                                       autocapitalize="characters"
                                       id="mechanic"
                                       name="mechanic"/>
                                <div class="input-group-addon">
                                    <button type="button" id="mechanicSearchBtn"
                                            name="mechanicSearchBtn"
                                            class="btn btn-success btn-sm border-radius-0">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type="text"
                                   class="form-control form-control-sm"
                                   id="mechanic"
                                   name="mechanic"
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
                            <select name="shiftType"
                                    required
                                    class="form-select form-select-sm">
                                <option>Normal Shift</option>
                                <option>Normal OT</option>
                                <option>Saturday/Sunday OT</option>
                            </select>
                        </td>
                        <td>
                            <input name="ratePerHour"
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
                    </tr>
                @endforeach
            @endif
            </tbody>
            <tfoot>
            <tr>
                <td class="pl-2"></td>
                <td></td>
                <td></td>
                <td class="text-right"><strong>TOTAL</strong></td>
                <td class="text-right"><b id="serviceQuantityTotal"
                                          class="input-number">0</b></td>
                <td></td>
                <td class="text-right"><strong>TOTAL</strong></td>
                <td class="text-right"><b id="serviceTotalPrice"
                                          class="input-number">0.00</b></td>
            </tr>
            </tfoot>

        </table>
    </div>
</div>
