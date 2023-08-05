<table id="services_table"
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
    @if($services->isNotEmpty())

            <tr class="increment">
                <td>
                    <select
                            name="service_article"
                            required
                            value="{{$service->material_code ?? ''}}"
                            data-value="{{$service->material_code ?? ''}}"
                            class="form-control form-control-sm servicesArticlesDropDownList">
                        <option value="{{$service->material_code ?? ''}}">

                        </option>
                    </select>
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
                    <input
                            name="service_unit_of_measure"
                            required
                            readonly
                            class="form-control form-control-sm unit_of_measure"/>
                </td>

                <td>
                    <input name="service_unit_price"
                           required
                           readonly
                           class="form-control form-control-sm service_unit_price"/>
                </td>

                <td>
                    <input name="service_total_price"
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
