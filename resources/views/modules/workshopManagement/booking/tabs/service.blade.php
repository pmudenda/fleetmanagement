<div class="row pt-5">
    <hr style="color: orange;"/>
    <div class="col-xs-12 col-sm-12 col-md-12 px-0">
        <div class="row">
            <div style="max-height:500px; overflow-x: auto;">
                <table id="services_table"
                       data-form-url="{{route("process.service.reservation")}}"
                       data-model-name="ServicesHeader"
                       class="table dataTable table-row-dashed align-middle gs-0 nowrap">
                    <thead>
                    <tr class="bg-success-subtle">
                        <th style="width: 6%;" class="pl-2">Reg. No</th>
                        <th style="width: 25%;">Article</th>
                        <th>Article Code</th>
                        <th style="width: 25%;">Tech. Specification</th>
                        <th style="width: 4%; max-width: 4%;">Qty.</th>
                        <th>UOM</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($services->isNotEmpty())
                        @foreach($services as $service)
                            <tr class="increment">
                                <td class="showNumber">
                                    <input
                                        readonly="readonly"
                                        name="vehicle_registration"
                                        required
                                        value="{{$details->reg_no ?? ''}}"
                                        class="form-control form-control-sm vehicle_registration"/>
                                </td>
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

                                <td class="view-mode">
                                    <button type="button"
                                            data-value="{{$defect->id ?? '0'}}"
                                            value="deleteRow"
                                            class="btn btn-danger p-2">
                                        <i class="fas fa-trash m-0"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="increment">
                            <td class="showNumber">
                                <input
                                    readonly
                                    name="vehicle_registration"
                                    required
                                    value="{{$details->reg_no ?? ''}}"
                                    class="form-control form-control-sm vehicle_registration"/>
                            </td>
                            <td>
                                <select
                                    disabled
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
                                        data-value="{{$defect->id ?? '0'}}"
                                        value="deleteRow"
                                        class="btn btn-danger p-2">
                                    <i class="fas fa-trash m-0"></i>
                                </button>
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
                        <td></td>
                    </tr>
                    </tfoot>

                </table>
                <button type="button"
                        data-table-id="services_table"
                        class="btn btn-sm btn-primary add pull-right"
                        value="addRow">
                    <i class="fa fa-plus"></i>
                    Add Row
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-10"></div>
            <div class="col-2">
                <div class="row">
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="form-group">
                <label
                    class="col-xs-12 col-sm-6 col-md-5 col-lg-4 pl-0 field-required"
                    for="remarks">
                    Comments <small>Will be used as justification for Requisition</small>:
                </label>
                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8 pl-0">
                    @if(!empty($comments))
                        <textarea type="text"
                                  id="service_comments"
                                  minlength="20"
                                  maxlength="255"
                                  required
                                  name="service_comments"
                                  style="height: 129px;"
                                  class="form-control comments form-control-sm">{{$comments->where('type','=','SREQ')->first()->remarks ??''}}</textarea>
                    @else
                        <textarea type="text"
                                  id="service_comments"
                                  minlength="20"
                                  maxlength="255"
                                  required
                                  name="service_comments"
                                  style="height: 129px;"
                                  class="form-control comments form-control-sm"></textarea>
                    @endif
                </div>
            </div>
        </div>
        <table class="mt-10">
            <tbody>
            <tr>
                <td class="text-right">
                    <strong id="srfTotal" class="input-number">Prepared By:</strong>
                </td>
                <td>
                    <b id="section" class="input-number">{{$user->name}}</b>
                </td>
                <td></td>
            </tr>
            </tbody>
        </table>
        <div class="col-12 text-right">
            <div>
                <button type="button"
                        id="saveServices"
                        style="background: #f59d33; color: #fff;"
                        data-table-id="services_table"
                        class="btn btn-sm btn-success add pull-right">
                    <i class="fa fa-save"></i>
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
