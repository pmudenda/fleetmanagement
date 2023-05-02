<div class="modal fade" id="approval-modal" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
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
                        <div style="clear:both;">
                            <label class="app-label">Approval Comments</label>
                            <div>
                            <textarea id="newApproval_Remarks"
                                      class="form-control" cols="35"
                                      rows="4" maxlength="1000"></textarea>
                                <br/>
                                <br/>
                            </div>
                        </div>
                        <div id="approveAanDisapprove" style="">
                            <label class="app-label">Approve / Disapprove</label>
                            <div>
                                <span id="spanApproveBtn" class="mr-3">
                                    <input type="radio"
                                           name="optApprove"
                                           value="true"
                                           checked="checked"
                                           id="approveSelectedPass"/>
                                    Approve
                                </span>
                                <span id="spanDisapproveBtn">
                                    <input type="radio"
                                           name="optApprove"
                                           value="false"
                                           id="approveSelectedFail"/>
                                    Disapprove
                                </span>
                                <br/>
                                <br/>
                            </div>
                        </div>

                        <div class="signAsElement" style="display:none;">
                            <label class="app-label field-required app-field-null">Login ID</label>
                            <div>
                                <input class="zqEditMode form-control"
                                       type="text"
                                       id="loginIdInput"
                                       size="25" maxlength="25"/><br/>
                            </div>
                        </div>
                        <div class="signAsElement" style="display:none;">
                            <label class="app-label field-required app-field-null">Login Password</label>
                            <div>
                                <input type="password" id="loginPasswordInput"
                                       class="form-control"
                                       size="25" maxlength="25"/><br/>
                            </div>
                        </div>
                        <div style="display: none;">
                            <label class="app-label field-required app-field-null">eSignature Password</label>
                            <div>
                                <input type="password"
                                       class="form-control"
                                       id="eSignaturePasswordInput" size="25" maxlength="25"/>
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

