(function (appInstance, $) {
    let modal = '';
    let myModalEl = document.querySelector('#approval-modal')
    if (myModalEl) {
        if (bootstrap) {
            modal = new bootstrap.Modal(myModalEl,
                {
                    backdrop: 'static', keyboard: false
                });
        }
    }

    function launchDialog(dialogContent) {
        let enforceSignAsToApprove = dialogContent.data("forceSignAs");
        let canFail = dialogContent.data("canFail");
        let requireRemarks = dialogContent.data("requireComments");

        if (enforceSignAsToApprove) {
            dialogContent.find("#approveChkSignAs").prop("disabled", true).prop("checked", true);
            dialogContent.find(".signAsElement").show();
        } else {
            dialogContent.find("#approveChkSignAs").on("change", function () {
                $("#eSignaturePasswordInput, #loginPasswordInput, #newapproval_txtLoginID").val("").trigger("change");
                $("#spanMessage").empty();
                if (this.checked) {
                    dialogContent.find(".signAsElement").show();
                } else {
                    dialogContent.find(".signAsElement").hide();
                }
            });
        }

        canFail &&
        $("#approveCandIsapprove", dialogContent).show();

        $("input[type=text], input[type=password]", dialogContent).on("keydown", function (event) {
            event.keyCode === 13 && $("#btnNewApprovalSign").trigger("click")
        });

        let commentsBox = $("#newApproval_Remarks");

        $("#approveSelectedFail").on("change", function () {
            commentsBox.parent().prev().addClass("field-required");
            commentsBox.trigger("change")
        });

        $("#approveSelectedPass").on("change", function () {
            requireRemarks || commentsBox.parent().prev().removeClass("field-required").removeClass("app-field-null")
        });

        commentsBox.on("change", function () {
            if (requireRemarks || document.getElementById("approveSelectedFail").checked && (this.value > "")) {
                commentsBox.parent().prev().removeClass("app-field-null");
            } else {
                commentsBox.parent().prev().addClass("app-field-null");
            }
        });

        let r;
        requireRemarks && (r = commentsBox, r.parent().prev().addClass("field-required"), r.trigger("change"));

        $("#eSignaturePasswordInput, #loginPasswordInput, #loginIdInput").on("change", function () {
            if (this.value > "") {
                $(this).parent().prev().removeClass("app-field-null")
            } else {
                $(this).parent().prev().addClass("app-field-null")
            }
        });

        if (modal) {
            modal.show();
        }
    }

    function openApprovalDialog(settings, approvalType, cancelCallBack, successCallBack, approvalGuid, signAsUser, postUrl) {
        let requireRemarks = true;
        let workflowReference = settings.options.recordId || "0";
        let docType = settings.options['documentType'];
        let description = "Sign";
        let canFail = false;

        if (settings['approvals']) {
            description = settings.approvals.approvalName(approvalType);
            canFail = settings.approvals.canFail(approvalType);
            requireRemarks = settings.approvals.requireComments(approvalType);
        }

        let approvalModeApproveGuid = signAsUser, approvalUserGuid;
        //let templateId = settings.options.templateId;

        let approvalResponse = {
            result: false,
            approvalId: null,
            comments: "",
            stateId: "",
            stateDescription: "",
            signAsGuid: "",
            signAsName: "",
            ruleExecuted: false,
            dataIsland: "",
            approvalStructure: ""
        };
        let customAttributes = (approvalUserGuid > "" && approvalUserGuid !== appInstance.loggedUser.guid() ? 'data-forceSignAs="true"' : "");
        customAttributes += ' data-requireComments="' + requireRemarks + '" data-canFail="' + canFail;
        //customAttributes

        myModalEl.addEventListener('hidden.bs.modal', function (event) {
            //$("input[type=text]", this).off("keydown");
            //$(this).dialog("destroy").remove();
            //cancelCallBack(approvalResponse)
        })

        $("#btnNewApprovalSign").off('click').on('click', function () {
            let signature = document.getElementById("eSignaturePasswordInput").value;
            let remarks = document.getElementById("newApproval_Remarks").value;
            let loginId = document.getElementById("loginIdInput").value;
            let password = document.getElementById("loginPasswordInput").value;
            let signOnBehalf = document.getElementById("approveChkSignAs").checked;
            let requestApproved = document.querySelector('[name="optApprove"]:checked').value;

            let $approvalMessage = $("#spanMessage");

            if (!postUrl) {
                toastr.warning('No Url has been configured for this type of request');
                return;
            }

            if (requestApproved === 'false') {
                if (remarks.trim() === "") {
                    $approvalMessage.empty().append("You must supply comments when declining.");
                }
                return false;
            } else if (requestApproved === 'true' && requireRemarks && remarks.trim() === "") {
                $approvalMessage.empty().append("You must supply comments when signing this item.");
                return false;
            }

            // re-introduce when signatures are present
            if (signature === "") {
                //$approvalMessage.empty().append("Please complete required fields.");
                //document.getElementById("eSignaturePasswordInput").focus();
                //return
            }

            if (signOnBehalf) {
                if (loginId === "") {
                    $approvalMessage.empty().append("Please complete required fields.");
                    document.getElementById("loginIdInput").focus();
                    return
                }
                if (password === "") {
                    $approvalMessage.empty().append("Please complete required fields.");
                    document.getElementById("loginPasswordInput").focus();
                    return
                }
            }

            window.loaderMessage = '"Submitting Approval Please wait..."';
            modal.hide();
            appInstance.asyncPostJson(
                postUrl,
                {
                    reference: workflowReference,
                    ApprovalType: approvalType,
                    Description: description,
                    Comments: remarks,
                    Approved: requestApproved,
                    sig: signature,
                    approvalGuid: approvalGuid || "",
                    signAs: signAsUser,
                    loginId: loginId,
                    loginPass: password,
                    ApprovalModeApproveGUID: approvalModeApproveGuid,
                    ApprovalUserGUID: approvalUserGuid,
                    docType: docType
                },
                function (ajaxResponse) {
                    successCallBack(ajaxResponse, modal);
                },
                function (xhr, settings, errorThrown) {
                    cancelCallBack(xhr, settings, errorThrown);
                });
        })

        launchDialog($(myModalEl));
    }

    appInstance.approval = {

        dialog: function (options, approvalType, postUrl, successCallBack) {
            let defaultOptions = {
                approvals: {
                    'approvalName': function (approvalType) {
                        return 'Sign';
                    },
                    'canFail': function (approvalType) {
                        return true;
                    },
                    'requireComments': function (approvalType) {
                        return true;
                    },
                },
            };

            let settings = {
                ...options,
                ...defaultOptions
            }

            if (!successCallBack || typeof successCallBack != 'function') {
                // assign call back function if non has been specified
                successCallBack = function (ajaxResponse, modal) {
                    let parsedResponse = appInstance.processAsyncResponse(ajaxResponse);

                    if (parsedResponse.result) {
                        modal.hide();
                        appInstance.showSystemMessage('Approval', parsedResponse.message, () => {
                            setTimeout(function () {
                                window.location.reload();
                            }, 300);
                        }, 'success');
                    } else {
                        window.top.tmsApp.systemError('', parsedResponse);
                    }
                }
            }

            openApprovalDialog(
                settings,
                approvalType,
                (...args) => {
                    console.log(args);
                },
                successCallBack,
                "",
                "",
                postUrl
            );
        }
    }

})(window.tmsApp || {}, jQuery);

//<img src="assets/gif/Eclipse_loading.gif" width="201" height="22">
