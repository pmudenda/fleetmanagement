<div class="container-fluid">
    <div class="row" data-form-url="{{route("job_card.accessories.checkin")}}" data-model-name="Accessories">
        <input type="hidden" value="{{$details->job_card_no ?? 0}}" name="job_card_voucher"/>
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

            <div class="row h-2 bg-success"></div>

            <div class="row mb-1 mt-4">
                <div class="col-lg-2 col-sm-12">
                    <label>Driver Acknowledgement:</label>
                </div>
                <div class="col-lg-3 col-sm-12">
                    <input type="text"
                           name="claimant_name"
                           class="form-control"
                           value="{{$details->driver_in ?? ''}}"
                           readonly required></div>

                @if(!empty($details->driver_acknowledged))
                    <div class="col-lg-2 col-sm-12 text-left">
                        <label>eSignature:</label>
                    </div>
                    <div class="col-lg-1 col-sm-12">
                        <input type="text"
                               name="sig_of_claimant"
                               class="form-control"
                               {{--value="{{Auth::user()->staff_no}}"--}}
                               readonly
                               required/>
                    </div>

                    <div class="col-lg-2 col-sm-12 text-left"><label>Date Acknowledged:</label></div>

                    <div class="col-lg-2 col-sm-12">
                        <input type="Date"
                               name="date_claimant"
                               class="form-control"
                               value="{{date('Y-m-d')}}"
                               readonly
                               required/>
                    </div>
                @else
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

            <div class="row mt-10">
                <div class="form-group">
                    <label
                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 pl-0"
                            for="accessoriesRemarks">
                        General Comment (optional):
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
        </div>
    </div>
</div>

<div class="modal fade" id="eSignature-modal" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" id="approvalModalTitle">
                    <i class="fa fa-pencil-square-o"></i> Approval
                </div>
            </div>
            <div class="modal-body">
                <div>
                    <div id="approvalDialogSign">
                        <div style="float:left;">
                            <span id="spanMessage" style="color: #f00;" class="errorMessage"></span>
                            <label id="newApproveLblMessage" class="mediumMessage"></label>
                        </div>
                        <div style="float:right; padding-left:30px; display: none;">
                            <input class="small" type="checkbox" id="approveChkSignAs"/>
                            Sign As Different User...
                        </div>
                        {{--   <div id="approveAanDisapprove" style="display: none">
                               <label class="app-label">Approve / Disapprove</label>
                               <div>
                                   <span id="spanApproveBtn" class="mr-3">
                                       <input type="radio"
                                              name="optApprove"
                                              value="approve"
                                              checked="checked"
                                              id="approveSelectedPass"/>
                                       Approve
                                   </span>
                                   <span id="spanDisapproveBtn">
                                       <input type="radio"
                                              name="optApprove"
                                              value="reject"
                                              id="approveSelectedFail"/>
                                       Disapprove
                                   </span>
                                   <span id="spanSendBackBtn">
                                       <input type="radio"
                                              name="optApprove"
                                              value="send_back"
                                              id="approveSendBack"/>
                                       Send Back
                                   </span>
                                   <br/>
                                   <br/>
                               </div>
                           </div>--}}

                        <div class="signAsElement">
                            <label class="app-label field-required app-field-null">Login ID</label>
                            <div>
                                <input class="zqEditMode form-control"
                                       type="text"
                                       id="loginIdInput"
                                       size="25" maxlength="25"/><br/>
                            </div>
                        </div>
                       {{-- <div >
                            <label class="app-label field-required app-field-null">Login Password</label>
                            <div>
                                <input type="password" id="loginPasswordInput"
                                       class="form-control"
                                       size="25" maxlength="25"/><br/>
                            </div>
                        </div>--}}
                        <div class="signAsElement">
                            <label class="app-label field-required app-field-null">eSignature Password</label>
                            <div>
                                <input type="password"
                                       class="form-control"
                                       id="eSignaturePasswordInput" size="25" maxlength="25"/>
                            </div>
                        </div>
                        <div style="clear:both;">
                            <label class="app-label" id="remarksTitle">Comments</label>
                            <div>
                            <textarea id="newApproval_Remarks"
                                      class="form-control" cols="35"
                                      rows="4" maxlength="1000"></textarea>
                                <br/>
                                <br/>
                            </div>
                        </div>
                    </div>
                    <div id="newApproval_DIVWait" style="visibility: hidden; display: none">
                        <table border="0" style="width:100%; height:100%;">
                            <tr>
                                <td align="center">
                                    Please wait . . .
                                    <br/>
                                    <br/>
                                    Signature being verified.
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button id="btnNewApprovalSign" class="btn btn-sm btn-success mr-3">
                    <i class="fas fa-save"></i>
                    Save
                </button>
                <button class="btn btn-sm btn-danger"
                        data-bs-target="#approval-modal"
                        data-bs-toggle="modal"
                        data-bs-dismiss="modal">
                    <i class="fas fa-undo"></i>
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
