@php use Carbon\Carbon; @endphp
<div class="container-fluid">
    <div class="row" data-form-url="{{route("job_card.accessories.checkin")}}" data-model-name="Accessories">
        <input type="hidden" value="{{$details->job_card_no ?? 0}}" name="job_card_voucher"/>
        <input type="hidden" value="{{$details->driver_acknowledged ?? 'N'}}" id="driverAcknowledged"
               name="driverAcknowledged"/>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="row">
                <div class="col">
                    <table
                            class="table table-row-dashed align-middle gs-0 table-bordered">
                        <thead>
                        <tr class="bg-dark-subtle">
                            <th class="pl-2">Item</th>
                            <th>Present</th>
                            <th class="pr-2">Not Present</th>
                            <th class="pr-2">Remarks</th>
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
                <div class="col-9">
                    <table
                            class="table table-row-dashed align-middle gs-0 table-bordered">
                        <thead>
                        <tr class="bg-dark-subtle">
                            <th class="pl-2">Item</th>
                            <th>Present</th>
                            <th class="pr-2">Not Present</th>
                            <th class="pr-2">Remarks</th>
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
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="row">
                        <div class="table-responsive" style="max-height:500px;">
                            <table class="table" id="observations">
                                <thead>
                                <tr class="bg-success">
                                    <th>Observation</th>
                                    <th>Attachment</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <input type="text" name="observation[]" class="form-control">
                                    </td>
                                    <td>
                                        <input type="file" name="attachment[]" class="form-control">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <button type="button"
                                    data-table-id="observations"
                                    class="btn btn-sm btn-primary add pull-right"
                                    value="addRow">
                                <i class="fa fa-plus"></i> Add Row
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-10">
                <div class="form-group">
                    <label
                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 pl-0"
                            for="accessoriesRemarks">
                        General Comments and Observation (optional):
                    </label>
                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8 pl-0">
                        @if(!empty($comments))
                            <textarea type="text"
                                      id="accessoriesRemarks"
                                      name="accessoriesRemarks"
                                      style="height: 129px;"
                                      class="form-control form-control-sm">{{$comments->where('type','=','ACC')->first()->remarks ??''}}</textarea>
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

            <div class="row bg-success" style="height: 30px;">
                <div class="col-12 text-white">
                    <h5 class="text-white">Acknowledgment of Assessment Finding</h5>
                </div>
            </div>

            <div class="row mb-1 mt-4">
                <div class="col-8">
                    <div class="col-lg-2 col-sm-12">
                        <label>Driver Acknowledgement: <small class="text-danger">(To Be Performed By
                                Driver)</small></label>
                    </div>
                    @if(!empty($details->driver_acknowledged))
                        <div class="col-lg-3 col-sm-12">
                            <span class="btn btn-sm btn-success">Acknowledged</span>
                        </div>
                    @else
                        <div class="col-lg-3 col-sm-12">
                            <span class="btn btn-sm btn-success">Awaiting Acknowledgement</span>
                        </div>
                    @endif
                    @if(!empty($details->driver_acknowledged))
                        <div class="col-lg-2 col-sm-12 text-left">
                            <label>eSignature:</label>
                        </div>
                        <div class="col-lg-1 col-sm-12">
                            <input type="text"
                                   name="sig_of_claimant"
                                   class="form-control"
                                   value="{{$details->driver_in}}"
                                   readonly
                                   required/>
                        </div>

                        <div class="col-lg-2 col-sm-12 text-left"><label>Date Acknowledged:</label></div>

                        <div class="col-lg-2 col-sm-12">
                            <input type="text"
                                   name="date_claimant"
                                   class="form-control"
                                   value="{{Carbon::parse($details->date_acknowledged)->format('d/m/Y')}}"
                                   readonly
                                   required/>
                        </div>
                    @else
                        <div class="col-lg-2 col-sm-12 text-left">
                            <label>eSignature:</label>
                        </div>
                        <div class="col-lg-1 col-sm-12">
                            <input type="text"
                                   name="sig_of_claimant"
                                   class="form-control"
                                   value=""
                                   readonly
                                   required/>
                        </div>

                        <div class="col-lg-2 col-sm-12 text-right">
                            <button type="button"
                                    class="btn btn-sm btn-success"
                                    data-toggle="modal"
                                    data-target="#eSignature-modal">
                                <i class="fas fa-signature"></i>
                                Sign
                            </button>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
