(function (tmsApp, $) {
    $("#resetUserFormBtn").on('click', function () {
        document.forms['tms_user_definition'].reset();
        document.querySelector('#actionButtonsContainer').style.display = 'none';
    });

    $("#submitUserBtn").on('click', function () {

        // validate data here

        let $form = document.forms['tms_user_definition'];
        if (!$($form).valid()) {
            toastr.warning('Data failed validation checks');
            return;
        }

        window.tmsApp.confirm(
            'Create User',
            'Are you sure you want to add user ?',
            'Yes',
            'No, Cancel',
            function () {
                tmsApp.asyncPostFormData(
                    $form.action,
                    new FormData($form),
                    function (asyncResponse) {
                        if (asyncResponse.hasOwnProperty('success') && asyncResponse['success']) {
                            setTimeout(function () {
                                tmsApp.showSystemMessage(
                                    'User Creation',
                                    asyncResponse['message'],
                                    function () {
                                        document.forms['tms_user_definition'].reset();
                                        window.location.reload();
                                    }, 'success');
                            }, 300);
                            return;

                        }

                        if (asyncResponse.hasOwnProperty('errors')) {
                            tmsApp.printErrorMsg(asyncResponse.errors);
                            return
                        }

                        tmsApp.systemError(
                            'User Creation',
                            asyncResponse['message'],
                            function () {
                            });
                    },
                    function (xhr, settings, errorThrown) {
                        console.log(errorThrown);
                        if (errorThrown.hasOwnProperty('errors')) {
                            tmsApp.printErrorMsg(errorThrown.errors);
                            return
                        }
                        setTimeout(function () {
                            tmsApp.systemError(
                                'User Creation',
                                'We could not complete processing your request, please try again later',
                                function () {
                                })
                        }, 300)

                    }
                )
            },
            function () {
            },
        )
    });

    tmsApp.appFormValidator('form[name="tms_user_definition"]',
        {
            name: {
                required: true,
            },
            staff_number: {
                required: true
            },
            login_name: {
                required: true
            },
            grade: {
                required: true
            },
            job_title: {
                required: true
            },
            staff_email: {
                required: true
            },
            mobile_no: {
                required: true
            },
            directorate: {
                required: true
            },
            user_unit: {
                required: true
            },
            bu_code: {
                required: true
            },
            cc_code: {
                required: true
            },
            password: {
                required: true
            },
            staff_supervisor: {
                required: true
            },
            user_profile: {
                required: true
            },
            business_area: {
                required: true
            }
        },
        {
            bu_code: {
                required: "User unit required"
            },
            cc_code: {
                required: "Cost center is required"
            },
            password: {
                required: "User default password is required"
            },
            staff_supervisor: {
                required: "Employee supervisor is required"
            },
            user_profile: {
                required: "User profile must be assigned"
            },
            business_area: {
                required: "Select business area user is attached to"
            }
        }
    );

    function getBusinessAreas() {
        fetch(document.querySelector('#businessAreaEndpoint').value)
            .then(response => response.json())
            .then(response => {
                let selectElem = $('select[name="business_area"]');
                // Populate results
                if (response.state === 'failure') {
                    //show errors
                    toastr.error('Connection error, business area data not found')
                    return;
                }

                let locations = response['payload'];
                tmsApp.populateDropDownList(selectElem, locations, "area", ["area", "description"], " - ")
            })
            .catch(function (error) {
                // notify of error
                toastr.error(
                    'Connection error. Could not retrieve data, some feature might not work.')
            });
    }

    getBusinessAreas();
})(window.tmsApp || {}, jQuery);
