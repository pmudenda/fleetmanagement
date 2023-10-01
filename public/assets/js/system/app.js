$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});

$(document).ready(function (event) {


    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });

    const queryModalEl = document.querySelector('#modal-followUp');

    queryModalEl.addEventListener('hide.bs.modal', function (event) {
        $("#documentFollowUpForm").reset();
    });

    const resultsModalEl = document.querySelector('#documentFollowUp');
    resultsModalEl.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        const button = event.relatedTarget
        $("#documentFollowUpTable").DataTable({
            'order': [],
            "pageLength": 10,
            "responsive": true,
            "searchable": true,
            "lengthChange": false,
            "autoWidth": false,
            'columnDefs': [],
            "buttons": []
        })
    });
});

(function (tmsApp, $) {
    const mainProcessMetaData = {};

    window.addEventListener('message', (event) => {
        tmsApp.showToast(event.detail, "success")
    });

    window.addEventListener('modal-close', () => {
        $('.modal').modal('hide');
    });


    $(document).on('keypress', '.number_input', function (event) {
        tmsApp.numberOnly(event);
    });

    $(document).on('input', '.uppercase', function (event) {
        this.value = this.value.toUpperCase();
    });

    $(document).on('input', '[name="simulationJustification"]', function (event) {
        this.value = this.value.toUpperCase();
    });

    $(document).on("click", 'button[value="documentFollowUpFilter"]', function (event) {
        const form = document.querySelector('form[name="documentFollowUpForm"]');
        const formData = new FormData(form);
        let postData = {};
        for (const keyValuePair of formData.entries()) {
            postData[keyValuePair[0]] = keyValuePair[1];
        }

        $.ajax({
            url: form.action, headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, type: 'GET', dataType: 'html', data: postData
        }).done(function (response) {
            showDocumentFollowUpResults(response);
        }).fail(function (xhr) {
            tmsApp.showErrorMessages(xhr, 'Document Follow-up')
        });
    });

    $(document).on("click", 'button[value="applyAuditTrailFilter"]', function (event) {
        const form = document.querySelector('form[name="documentAuditTrail"]');
        const formData = new FormData(form);
        let postData = {};
        for (const keyValuePair of formData.entries()) {
            postData[keyValuePair[0]] = keyValuePair[1];
        }

        const settings = {
            url: form.action, headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, type: 'GET', dataType: 'json', data: postData
        };

        $.ajax(settings).done(function (response) {
            showDocumentAuditTrailResults(response);
        }).fail(function (xhr) {
            tmsApp.showErrorMessages(xhr, 'Document Audit Trail');
        });
    });

    $(document).on('change', '#simulationUsers', function () {
        const  user = this.options[this.selectedIndex]
        console.log(user);
    })

    $(document).on('change', '[name="userIdentifier"]', function () {
        let searchTerm = this.value;

        function checkUserData(searchTerm) {
            if (!searchTerm) {
                return;
            }
            const url = $('#userIdentifier').attr('data-action') + '?searchCriteria=' + searchTerm;
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({searchCriteria: searchTerm}),
                referrer: window.baseUrl,
                mode: 'cors',
                credentials: 'same-origin',
            })
                .then((response) => {
                    if (!response.ok) {
                        tmsApp.systemError('User Verification', 'We could not user search', function () {
                        });
                        return;
                    }

                    return response.json();
                })
                .then(response => {
                    console.log(response);
                    if (response.success === 'true' || response.success === true) {
                        const obj = response.payload;
                        let rows = '';
                        if (Array.isArray(obj)) {
                            for (let i = 0; i < obj.length; i++) {
                                rows += `<option data-name="${obj[i].staff_no}}"
                                            value="${obj[i].name}">
                                            ${obj[i].staff_no} ${obj[i].name}
                                        </option>`;
                            }
                            $("#simulationUsers").html(rows);
                            //const name = obj[0].name;
                            //$('#userIdentifier').val(obj?.con_per_no ?? obj?.staff_no);
                            $('#userNameIdentifier').attr('readonly', false);
                            //.val(name);
                        } else {
                            const name = obj?.name;
                            $('#userIdentifier').val(obj?.con_per_no ?? obj?.staff_no);
                            $('#userNameIdentifier').val(name);
                        }
                    } else {
                        tmsApp.systemError('User Verification',
                            'User with Staff No.' + searchTerm
                            + ' was not found, Check your input and try again',
                            function () {
                            });
                    }
                })
                .catch(function (error) {
                    tmsApp.systemError('User Verification',
                        'We could not user search', function () {
                        });
                });
        }

        if (searchTerm === $("#currentUser").val()) {
            $('#startSimulationBtn').attr('disabled', true);
            tmsApp.systemError('User Simulation',
                "You can not simulate yourself", function () {
                });
            return;
        } else {
            $('#startSimulationBtn').attr('disabled', false);
        }

        checkUserData(searchTerm);
    });

    $(document).on('submit', '[name="startUserSimulationForm"]', function (e) {
        if (!$(this).valid()) {
            return;
        }
        e.preventDefault();
        e.stopPropagation();
        let formData = new FormData(this);

        $("#modalSimulateUser").modal('hide');

        tmsApp.asyncPostFormData(this.action, formData, function (response_data) {
            if (response_data.success === 'true' || response_data.success === true) {
                if (response_data['payload'].length === 0) {
                    tmsApp.systemError('User Simulation', 'Could Not Start User Simulation');
                }
                tmsApp.showSystemMessage('User Simulation', 'User Session Started Successfully', function () {
                    window.location.reload()
                }, 'success')

            } else {
                tmsApp.play_alert('sound-error');
                tmsApp.systemError('User Simulation', 'Could Not Start User Simulation');
            }
        }, function (xhr, settings, errorThrown) {
            tmsApp.play_alert('sound-error');
            console.log(xhr);
            tmsApp.systemError('User Simulation', 'We could not complete processing your request, please try again later')
        });
    });

    $(document).on('click', '[data-action="endSimulation"]', function () {
        let formData = new FormData();
        tmsApp.asyncPostFormData($(this).data('formUrl'), formData, function (response_data) {
            if (response_data.success === 'true' || response_data.success === true) {
                if (response_data['payload'].length === 0) {
                    tmsApp.systemError('End User Simulation', 'Could Not Start User Simulation');
                }
                tmsApp.showSystemMessage('End User Simulation', 'User Simulation Ended Successfully', function () {
                    window.location.reload()
                }, 'success')

            } else {
                tmsApp.play_alert('sound-error');
                tmsApp.systemError('End User Simulation', 'Could Not End User Simulation');
            }
        }, function (xhr, settings, errorThrown) {
            tmsApp.play_alert('sound-error');
            console.log(xhr);
            tmsApp.systemError('End User Simulation', 'We could not complete processing your request, please try again later')
        });
    });

    function showDocumentAuditTrailResults(results) {
        const modalEl = document.querySelector('#modal-followUp');
        const resultsModalEl = document.querySelector('#documentFollowUp');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.hide();
        setTimeout(() => {
            $("#documentFollowUpContent").html(results);
            let resultsModal = bootstrap.Modal.getOrCreateInstance(resultsModalEl, {
                'backdrop': true, 'keyboard': false
            });
            resultsModal.show();
        }, 300);
    }

    function showDocumentFollowUpResults(results) {
        const modalEl = document.querySelector('#modal-auditTrail');
        const resultsModalEl = document.querySelector('#documentFollowUp');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.hide();
        setTimeout(() => {
            $("#documentFollowUpContent").html(results);
            let resultsModal = bootstrap.Modal.getOrCreateInstance(resultsModalEl, {
                'backdrop': true, 'keyboard': false
            });
            resultsModal.show();
        }, 300);
    }
}(window.tmsApp || {}, jQuery));
