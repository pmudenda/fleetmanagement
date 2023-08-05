@php use Carbon\Carbon; @endphp
<table id="services_table mt-10"
       data-model-name="ServicesHeader"
       class="table dataTable table-row-dashed align-middle gs-0 nowrap">
    <thead>
    <tr class="bg-success-subtle">
        <th style="width: 6%;" class="pl-2">Defect</th>
        <th style="width: 25%;">Mechanic</th>
        <th style="width: 25%;">Name</th>
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
                <td class="showNumber">
                    <select name="vehicleSystem"
                            required
                            disabled
                            data-value="{{$defect->veh_sys}}"
                            class="form-select form-select-sm select_2_control vehicleSystem">
                        <option></option>
                    </select>
                </td>
                <td>
                    <select name="defectCategory"
                            required
                            disabled
                            data-value="{{$defect->defect_category_code}}"
                            class="form-select form-select-sm select_2_control defectCategory">
                        <option></option>
                    </select>
                </td>
                <td>
                    <select name="defect"
                            required
                            disabled
                            data-value="{{$defect->defect_code}}"
                            class="form-select form-select-sm select_2_control defect">
                        <option></option>
                    </select>
                </td>
                <td>
                    <select name="workshopSection"
                            disabled
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
                    <input name="date_def"
                           readonly="readonly"
                           value="@if($defect){{date('Y-m-d',strtotime(Carbon::parse($defect->date_def)->format('Y-m-d H:i:s')))}}@else{{date('Y-m-d H:i:s', strtotime(Carbon::now()))}}@endif"
                           class="tabledit-input form-control input-sm input-number"
                           type="text">
                </td>
            </tr>
        @endforeach
    @endif
    @if($services->isNotEmpty())

        <tr class="increment">
            <td>
                @if($defects && $defects->isNotEmpty())
                    @foreach($defects as $defect)
                        <select name="defect"
                                required
                                data-value="{{$defect->defect_code}}"
                                class="form-select form-select-sm select_2_control defect">
                            <option></option>
                        </select>
                    @endforeach
                @endif
            </td>
            <td>
                <input
                        name="serviceArticleCode"
                        required
                        value="{{$service->material_code ?? ''}}"
                        readonly
                        class="form-control form-control-sm serviceArticleCode"/>
            </td>
            <td>
                <input
                        name="service_technical_specification"
                        required
                        value="{{$service->specification ?? ''}}"
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
                <select name="shiftType"
                        required
                        disabled
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
        </tr>

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
