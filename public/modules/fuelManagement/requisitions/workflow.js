'use strict';
(function (tmsApp, $) {
    const processData = {
        documentType: 'FuelRequisition',
        approvalType: 'fuelRequisition',
        actions: {
            'approve': 'approve',
            'reject': 'reject',
            'sendBack': 'send_back',
            'resubmit': 'resubmit',
            'cancel': 'cancel'
        }
    };

    function workflowSuccessCallBack(ajaxResponse, modal, options) {
        if (ajaxResponse.success) {
            setTimeout(function () {
                tmsApp.showSystemMessage(
                    options.mainTitle,
                    ajaxResponse.message,
                    function () {
                        setTimeout(function () {
                            window.location.href = ajaxResponse['redirectUrl'];
                        }, 300);
                    },
                    'success'
                );
            }, 300);
        } else {
            setTimeout(function () {
                tmsApp.systemError(
                    options.title,
                    ajaxResponse.message
                );
            }, 300);
        }
    }

    $('#approveRequisitionBtn').on('click', function () {
        tmsApp.approval.dialog(
            {
                options: {
                    recordId: document.querySelector("#taskReference").value,
                    documentType: processData.documentType,
                    action: processData.actions.approve,
                    title: 'Requisition Approval',
                    mainTitle: 'Approval',
                    extraPayload: {}
                }
            },
            processData.approvalType,
            document.querySelector('#approvalUrl').value,
            workflowSuccessCallBack,
        );
    });

    $('#declineRequisitionBtn').on('click', function () {
        tmsApp.approval.dialog({
                options: {
                    recordId: document.querySelector("#taskReference").value,
                    documentType: processData.documentType,
                    action: processData.actions.reject,
                    title: 'Requisition Rejection',
                    mainTitle: 'Rejection',
                    extraPayload: {}
                }
            },
            processData.approvalType,
            document.querySelector('#approvalUrl').value,
            workflowSuccessCallBack
        );
    });

    $('#sendBackRequisitionBtn').on('click', function () {
        tmsApp.approval.dialog({
                options: {
                    recordId: document.querySelector("#taskReference").value,
                    documentType: processData.documentType,
                    action: processData.actions.sendBack,
                    title: 'Requisition Send Back',
                    mainTitle: 'Send Back',
                    extraPayload: {}
                }
            },
            processData.approvalType,
            document.querySelector('#approvalUrl').value,
            workflowSuccessCallBack,
        );
    });

    $('#resubmitRequisitionBtn').on('click', function () {
        let payload = {}
        let formData = new FormData(document.forms['fuelRequisitionForm']);
        formData.forEach((value, key) => payload[key] = value);

        tmsApp.approval.dialog({
                options: {
                    recordId: document.querySelector("#taskReference").value,
                    documentType: processData.documentType,
                    action: processData.actions.resubmit,
                    title: 'Resubmit Requisition',
                    mainTitle: 'Resubmit',
                    extraPayload: payload || {}
                }
            },
            processData.approvalType,
            document.querySelector('#approvalUrl').value,
            workflowSuccessCallBack,
        );
    });

    $('#cancelRequisitionBtn').on('click', function () {
        tmsApp.approval.dialog({
                options: {
                    recordId: document.querySelector("#taskReference").value,
                    documentType: processData.documentType,
                    action: processData.actions.cancel,
                    title: 'Requisition Cancellation',
                    mainTitle: 'Cancel',
                    extraPayload: {}
                }
            },
            processData.approvalType,
            document.querySelector('#approvalUrl').value,
            workflowSuccessCallBack,
        );
    });

})(window.tmsApp || {}, jQuery)
