@php use Carbon\Carbon; @endphp
<div class="container-fluid">
    <div class="row">
        <input type="hidden" value="{{$details->job_card_no ?? 0}}" name="job_card_voucher"/>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="row">
                <div class="table-responsive" style="max-height:500px;">
                    <table id="part8" data-form-url="{{route("defects.job_card")}}" data-model-name="Defects"
                           class="table table-row-dashed align-middle gs-0">
                        <thead>
                        <tr class="bg-dark">
                            <th style="width: 25%;" class="pl-2">System</th>
                            <th style="width: 25%;">Category</th>
                            <th style="width: 25%;" class="pr-2">Defect</th>
                            <th style="width: 25%;" class="pr-2">Service Section</th>
                            <th style="width: 25%;" class="pr-2">Date/Time Detected</th>
                            <th style="width: 25%;" class="pr-2">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="increment">
                            <td class="showNumber">
                                <select name="vehicleSystem"
                                        class="form-select form-select-sm">
                                    <option></option>
                                </select>
                            </td>
                            <td>
                                <select name="defectCategory" class="form-select form-select-sm">
                                    <option></option>
                                </select>
                            </td>
                            <td>
                                <select name="defect" class="form-select form-select-sm">
                                    <option></option>
                                </select>
                            </td>
                            <td>
                                <select name="type" class="form-select form-select-sm">
                                    <option></option>
                                    @foreach($workshop_sections as $workshop_section)
                                        <option value="{{$workshop_section->code}}">{{$workshop_section->name}}</option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input name="total"
                                       readonly="readonly"
                                       value="@if($details){{date('Y-m-d',strtotime(\Carbon\Carbon::parse($details->date_in)->format('Y-m-d H:i:s')))}}@else{{date('Y-m-d H:i:s', strtotime(Carbon::now()))}}@endif"
                                       class="tabledit-input form-control input-sm input-number"
                                       type="text">
                            </td>

                            <td class="view-mode">
                                <button type="button"
                                        value="deleteRow"
                                        class="btn btn-danger p-2">
                                    <i class="fas fa-trash m-0"></i>
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <button type="button"
                            data-table-id="part8"
                            class="btn btn-sm btn-primary add pull-right"
                            value="addRow">
                        <i class="fa fa-plus"></i> Add Row
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-10"></div>
                <div class="col-2">
                    <div class="row">
                        {{--<button type="button" data-table-id="part8" class="btn btn-sm btn-danger waves-effect waves-light add" value="clearUpload">
                            <i class="fa fa-trash"></i> Clear Rows
                        </button>--}}
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="form-group">
                    <label
                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 pl-0"
                        for="remarks">Comments (optional):
                    </label>
                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8 pl-0">
                        <textarea type="text"
                                  id="remarks"
                                  name="remarks"
                                  style="height: 129px;"
                                  class="form-control form-control-sm">{{$details->comments??''}}</textarea>
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
                        <b id="section" class="input-number">RECEPTION</b>
                    </td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
