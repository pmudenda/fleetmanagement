@php @endphp
<div class="container-fluid">
    <div class="row" data-form-url="{{route("job_card.accessories.checkin")}}" data-model-name="Accessories">
        <input type="hidden" value="{{$details->job_card_no ?? 0}}" name="job_card_voucher"/>
        <input type="hidden" value="{{$details->wshp_act_code ?? ''}}" name="workshop_reference"
               id="workshop_reference"/>
        <input type="hidden" value="{{$details->driver_acknowledged ?? 'N'}}" id="driverAcknowledged"
               name="driverAcknowledged"/>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="alert alert-danger alert-dismissable">
                CUSTOMER IS REQUIRED TO REMOVE ALL PERSONAL EFFECTS FROM THE VEHICLE
            </div>
            <div class="row">
                <div class="col">
                    <table aria-label="Accessories Table 2"
                           role="table"
                           class="table table-row-dashed align-middle gs-0 table-bordered">
                        <thead>
                        <tr class="bg-dark-subtle">
                            <th scope="row" class="pl-2">Item</th>
                            <th scope="row">Present</th>
                            <th scope="row" class="pr-2">Not Present</th>
                            <th scope="row" class="pr-2">Remarks</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($accessories as $key => $accessory)
                            @if(($key%2) == 0)
                                <tr>
                                    <td class="pl-2"
                                        style="width: 35%;">{{$accessory->name}}</td>
                                    <td><input type="radio" value="YES" required
                                               name="field_{{str_replace(' ','', $accessory->code)}}">
                                    </td>
                                    <td><input type="radio" value="NO" required
                                               name="field_{{str_replace(' ','', $accessory->code)}}">
                                    </td>
                                    <td style="width: 45%;">
                                        <input typeof="text"
                                               name="comment_{{str_replace(' ','', $accessory->code)}}"
                                               class="form-control form-control-sm"/>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col">
                    <table
                        aria-label="Accessories Table 2"
                        role="table"
                        class="table table-row-dashed align-middle gs-0 table-bordered">
                        <thead>
                        <tr class="bg-dark-subtle">
                            <th scope="row" class="pl-2">Item</th>
                            <th scope="row">Present</th>
                            <th scope="row" class="pr-2">Not Present</th>
                            <th scope="row" class="pr-2">Remarks</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($accessories as $key => $accessory)
                            @if(($key%2) != 0)
                                <tr>
                                    <td class="pl-2" style="width: 35%;">
                                        {{$accessory->name}}
                                    </td>
                                    <td><input type="radio" required value="YES"
                                               name="field_{{str_replace(' ','', $accessory->code)}}">
                                    </td>
                                    <td><input type="radio" required value="NO"
                                               name="field_{{str_replace(' ','', $accessory->code)}}">
                                    </td>
                                    <td style="width: 45%;">
                                        <input typeof="text"
                                               name="comment_{{str_replace(' ','', $accessory->code)}}"
                                               class="form-control form-control-sm">
                                    </td>
                                </tr>
                            @endif
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row mt-10">
                <div class="col">
                    <div class="form-group">
                        <label
                            class="col-xs-12 col-sm-6 col-md-6 col-lg-6 pl-0"
                            for="accessoriesRemarks">
                            General Comments and Observation (Damages):
                        </label>
                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8 pl-0">
                            @if(!empty($comments) && !empty($comments->where('type','=','ACC')->first()->remarks))
                                <textarea type="text"
                                          id="accessoriesRemarks"
                                          name="accessoriesRemarks"
                                          style="height: 129px;"
                                          class="form-control form-control-sm">
                                    {{$comments->where('type','=','ACC')->first()->remarks ??''}}
                                </textarea>
                            @else
                                <textarea type="text"
                                          id="accessoriesRemarks"
                                          name="accessoriesRemarks"
                                          style="height: 129px;"
                                          class="form-control form-control-sm"></textarea>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="row">
                        <div class="table-responsive" style="max-height:500px;">
                            <table data-model-name="Observations"
                                   role="table"
                                   class="table table-striped"
                                   id="observations">
                                <thead>
                                <tr class="bg-success">
                                    <th scope="row" >Attachment</th>
                                    <th scope="row" >Remarks</th>
                                    <th scope="row" ></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($observation->isNotEmpty())
                                    @foreach($observation as $item)
                                        <tr data-record-id="{{$item->id}}">
                                            <td>
                                                <div class="imagePreview"
                                                     style="min-height: 100px !important; background-image: url('/storage{{$item->image_path}}'); display: block; background-size: cover">
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text"
                                                       value="{{$item->remarks}}"
                                                       readonly
                                                       class="form-control">
                                            </td>
                                            <td>
                                                {{--<button type="button"
                                                        data-table-id="observations"
                                                        class="btn btn-sm btn-danger"
                                                        value="deleteRow">
                                                    <i class="fa fa-trash"></i>
                                                </button>--}}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>
                                            <p>
                                                <button type="button" title="Select Image"
                                                        data-toggle="tooltip"
                                                        data-select="file"
                                                        class="btn btn-primary btn-sm selectAttachment">
                                                    <i class="fas fa-paperclip"></i>
                                                </button>
                                                <input type="file"
                                                       accept="image/*"
                                                       style="display: none;"
                                                       class="fileElem"
                                                       id="attachment"
                                                       name="attachment[]"/>
                                            </p>
                                            <div class="imagePreview"
                                                 style="display: none; min-height: 100px !important;">
                                                <button type="button"
                                                        class="btn btn-xs clearImage"
                                                        style="top: 1px;
                                                                                                    position: relative;
                                                                                                    right: 1px;
                                                                                                    float: right;
                                                                                                    padding: 2px;">
                                                    <i class="fa fa-window-close" style="font-size: 20px;"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="observation[]" class="form-control">
                                        </td>
                                        <td>
                                            <button type="button"
                                                    data-table-id="observations"
                                                    class="btn btn-sm btn-danger"
                                                    value="deleteRow">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            @if(empty($details->driver_acknowledged))
                                <button type="button"
                                        data-table-id="observations"
                                        class="btn btn-sm btn-primary add pull-right"
                                        value="insertRow">
                                    <i class="fa fa-plus"></i> Add Row
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-10 d-none">
                <table aria-label="PreparedBy"
                       role="none"
                       class="mt-10 table">
                    <tbody>
                    <tr>
                        <td scope="col" class="text-left">
                            <strong id="srfTotal" class="input-number">Prepared By:</strong>
                            <b id="section" class="input-number">RECEPTION</b>
                        </td>
                        <td>

                        </td>
                        <td class="text-right">
                            <button class="btn btn-sm btn-success pull-right"
                                    id="saveAssessment"
                                    name="saveAssessment"
                                    type="button">
                                <i class="fas fa-save"></i>
                                Save
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
