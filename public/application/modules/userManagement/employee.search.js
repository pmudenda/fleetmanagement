(function (tmsApp, $, supportData) {

    function populateEmployeeDetails(response) {
        let data = response;//.data.employee;
        //let data = response;
        if (!data.con_per_no) {
            tmsApp.showToast('User Not Found', 'error')
            return;
        }
        document.querySelector('[name="name"]').value = data?.name;
        document.querySelector('[name="staff_email"]').value = data?.staff_email;
        document.querySelector('[name="staff_number"]').value = data?.con_per_no;
        document.querySelector('[name="cc_code"]').value = data?.cc_code;
        document.querySelector('[name="bu_code"]').value = data?.bu_code;

        document.querySelector('[name="cost_center_code"]').value = data?.cc_code;
        document.querySelector('[name="business_unit_code"]').value = data?.bu_code;

        document.querySelector('[name="user_unit"]').value = data?.functional_section;
        document.querySelector('[name="job_title"]').value = data?.job_title;
        document.querySelector('[name="login_name"]').value = data?.con_per_no;
        document.querySelector('[name="directorate"]').value = data?.directorate;
        document.querySelector('[name="mobile_no"]').value = data?.mobile_no;
        document.querySelector('[name="grade"]').value = data?.grade;
        document.querySelector('[name="nrc"]').value = data?.nrc;

        document.querySelector('#actionButtonsContainer').style.display = null;
    }

    function findEmployee() {
        const employee_search_criteria = document.querySelector('#employee_search_criteria').value;
        let formData = new FormData();
        formData.append('searchCriteria', employee_search_criteria);
        const postUrl = $('form[name="tms_user_definition"]').attr('data-action');

        tmsApp.asyncPostFormData(
            postUrl,
            formData,
            function (response_data) {
                if (response_data.success === 'true' || response_data.success === true) {
                    populateEmployeeDetails(response_data['payload']);
                } else {
                    tmsApp.play_alert('sound-error');
                    tmsApp.systemError('', 'No User Found, Check your input and try again');
                }
            },
            function (xhr, settings, errorThrown) {
                console.log(xhr);
                tmsApp.systemError('', 'We could not complete processing your request, please try again later')
            }
        );
    }

    $(document).on('click', '#employeeSearchBtn', function (event) {
        findEmployee();
    });

    let $modal = $('#searchEmployeeModal');
    $(document).on('submit', '#userSearch', function (event) {
        document.querySelector(".errorMsg").innerHTML = null;
        const {assignmenttype, inputfield, field} = supportData;
        event.preventDefault();
        event.stopPropagation();

        let form = $(this);
        // $("#loader_inv").removeClass('d-none');

        tmsApp.asyncPostFormData(
            form.attr('action'),
            new FormData(form[0]),
            function (response_data) {
                if (!response_data) {
                    return;
                }

                if (response_data['success'] === false) {
                    $(".errorMsg").html(`<div class='alert alert-danger'>
                            No Users Found
                        </div>`);
                    return;
                }

                if (response_data['payload'].length === 0) {
                    $(".errorMsg").html(`<div class='alert alert-danger'>
                            No Users Found
                        </div>`);
                    return;
                }

                let obj = response_data['payload'];
                let $actionContainer = document.querySelector("#actionBtnContainer");
                let $secondActionContainer = document.querySelector("#secondActionBtnContainer");
                let $userElem = document.querySelector("#users");
                $actionContainer.innerHTML = null;
                $userElem.innerHTML = null;
                let table = `<div class='col-12'>
                            </div>
                            <table border='1' class='table border-transparent '>
                                <thead>
                                    <tr>
                                    <td>#</td>
                                    <td>Staff Number</td>
                                    <td>Name</td>
                                    <td>Email</td>
                                    <td>Job Title</td>
                                    </tr>
                                </thead>
                            <tbody id='myTableUsers'>`;
                let rows = "";

                if (Array.isArray(obj)) {
                    for (let i = 0; i < obj.length; i++) {
                        rows += `<tr>
                                <td>
                                    <div class='form-group clearfix'>
                                        <div class='text-center'>`;
                        rows += `<input type='radio' class="form-check"
                                                            value='${obj[i].con_per_no}'
                                                            data-userid='${obj[i].con_per_no}'
                                                            data-name='${obj[i].name}'
                                                            data-email='${obj[i].staff_email}'
                                                            id='users'
                                                            name='users[]'>`;
                        rows += `</div></div>
                                    </td>
                                     <td>
                                       ${obj[i]['con_per_no']}
                                    </td>
                                    <td>
                                       ${obj[i]['name']}
                                    </td>
                                    <td>
                                        ${obj[i]['staff_email']}
                                    </td>
                                    <td>
                                        ${obj[i]['job_title']}
                                    </td>
                                </tr>`;
                    }
                } else {

                    rows += `<tr>
                                <td>
                                    <div class='form-group clearfix'>
                                        <div class='text-center'>`;
                    rows += `<input type='radio' class="form-check"
                                                            value='${obj['con_per_no']}'
                                                            data-userid='${obj['con_per_no']}'
                                                            data-name='${obj['name']}'
                                                            data-email='${obj['staff_email']}'
                                                            id='users'
                                                            name='users[]'>`;
                    rows += `</div></div>
                                    </td>
                                    <td>
                                       ${obj['con_per_no']}
                                    </td>
                                    <td>
                                       ${obj['name']}
                                    </td>
                                    <td>
                                        ${obj['staff_email']}
                                    </td>
                                    <td>
                                        ${obj['job_title']}
                                    </td>
                                </tr>`;

                }

                /*for (let i = 0; i < obj.length; i++) {
                    rows += `<tr>
                                <td>
                                    <div class='form-group clearfix'>
                                        <div class='icheck-warning d-inline'>`;
                    rows += `<input type='radio'
                                                            value='${obj.con_per_no}'
                                                            data-userid='${obj.con_per_no}'
                                                            data-name='${obj.name}'
                                                            data-email='${obj.email}'
                                                            id='users'
                                                            name='users[]'>`;
                    rows += `</div></div>
                                    </td>
                                    <td>
                                       ${obj.name}
                                    </td>
                                    <td>
                                        ${obj.email}
                                    </td>
                                    <td>
                                        ${obj.job_title}
                                    </td>
                                </tr>`;
                }*/

                let tableClose = `</tbody></table>`;

                $userElem.innerHTML = table + rows + tableClose;
                let $btnElement = `<button
                                                type='button'
                                                data-confirm-selection="true"
                                                class='btn btn-outline-success mr-2 p-2'>
                                                        <i class="fa fa-check"></i> Confirm
                                              </button>`;
                $actionContainer.innerHTML = $btnElement;
                $secondActionContainer.innerHTML = $btnElement;
            },
            function (xhr, settings, errorThrown) {
                console.log(xhr);
                alert('We could not complete processing your request, please try again later')
            }
        );
    });

    $(document).on('click', '#myTableUsers > tr', function () {
        let checkBox = $(this).closest('tr').find('input[type="radio"]');
        $(checkBox).prop('checked', true);
    });

    $(document).on('click', '[data-field="userSelection"]', function () {
        window.supportData = this.dataset;
        $('#searchEmployeeModal').modal('show');
    });

    $modal.on('hidden.bs.modal', function (event) {
        // clear out the modal contents
        document.querySelector("#users").innerHTML = null;
        document.querySelector("#actionBtnContainer").innerHTML = null;
        document.querySelector("#secondActionBtnContainer").innerHTML = null;
        document.querySelector("#searchCriteria").value = null;
        document.querySelector(".errorMsg").innerHTML = null;
    });

    $modal.on('show.bs.modal', function (event) {
        if (event?.relatedTarget?.dataset) {
            window.supportData = event.relatedTarget.dataset;
        }
    });

    $modal.on('shown.bs.modal', function (event) {
        $('#searchCriteria').focus();
    });

    $(document).on('click', '[data-confirm-selection="true"]', function (event) {
        let _modal = $("#searchEmployeeModal");

        const {assignmenttype, inputfield, field} = window.supportData;

        let selectedUser = $("input[name='users[]']:checked");
        if (!selectedUser || selectedUser.length === 0) {
            _modal.find(".errorMsg").html('<div class="alert alert-danger">You have not selected any user</div>');
            return;
        }
        let name = '';
        let recordId = '';

        $.each(selectedUser, function (index, element) {
            if (name === '') {
                name += element['dataset']['name']
                recordId += element['dataset']['userid'];
            } else {
                name += ',' + element['dataset']['name']
                recordId += ',' + element['dataset']['userid'];
            }
        });

        if (assignmenttype === 'multiple') {
            name += ',' + $('input[name="' + inputfield + '"]').val();
            recordId += ',' + $('input[name="' + inputfield + 'Id"]').val();
        }

        $('input[name="' + inputfield + '"]').val(name).trigger('change');
        $('input[name="' + inputfield + 'Id"]').val(recordId).trigger('change');
        _modal.modal('hide');
    });

    tmsApp.findEmployee = findEmployee();
})(window.tmsApp || {}, jQuery, window.supportData || {assignmenttype: 'single', inputfield: '', field: ''});


