(function (tmsApp, $) {
    let hasPreviousRequisition = false;
    let $vehicleRegistrationCtl = $('#vehicle_registration');
    const appMessages = {
        permissionAlertWindowTitle: "Permission Assignment",
        validationFailureMessage: "Sorry, the data did not pass validation check," +
            "check the data and try again.",
        permissionsAttachedDefaultMessage: "Permission Assigned Successfully",
        noFuelAllocation: 'Vehicle has no not been assigned Fuel Allocation, '
            + 'Request System Administrator to assign allocation',
        inactiveEmployee: 'Employee with Staff_no @staff is not active',
        vehicleNotFound: ' No Vehicle Found, Check your input and try again',
        generalError: 'We could not complete processing your request, please try again later',
        invalidTripPeriod: 'You have selected more than the 7 Days Limit' +
            'If your trip is more than 7 days, you will have to create a second trip '
    };
    let previousRequisition = {};

    function removeSubmissionAndDetailsOptions() {
        let elements = document.querySelectorAll('.when_valid');
        elements.forEach(function (element) {
            element.setAttribute('disabled', 'disabled');
        });

        document.querySelector('#vehicleDetailsContainer').style.display = 'none';
        document.querySelector('#image_view').style.display = 'none';

        $('tbody#vehicleDetails').html('');
        document.querySelector('[name="fuel_allocation"]').value = '';

        $("#material_description").text(tmsApp.formatMoney('0', 2));
        $('input[name="material_description"]').val(tmsApp.formatMoney('0', 2));
    }

    function enableSubmissionAndDetailsOptions() {

        let elements = document.querySelectorAll('.when_valid');

        elements.forEach(function (element) {
            element.removeAttribute('disabled');
        });

        document.querySelector('#vehicleDetailsContainer').style.display = null;
        document.querySelector('#image_view').style.display = null;
    }

    function populateVehicleDetails(payload) {
        let vehicle = payload['vehicle'];
        let article = payload['article'];
        let images = payload['images'];
        const hasValidInsurance = payload['hasValidInsurance'];
        let vehicle_state = payload['vehicle_state'];
        let vehicle_tom_card_message = payload['vehicle_tom_card_message'];
        let insurance_message = payload['insurance_message'];

        if (!vehicle || !vehicle.brand_name) {
            return;
        }

        if (!vehicle.fuel_allocation) {
            tmsApp.showSystemMessage("Vehicle State",
                appMessages.noFuelAllocation,
                () => {
                },
                "error"
            );
            return;
        }

        if (vehicle['status'] !== $('[name="vehicleActive"]').val()) {
            tmsApp.showSystemMessage("Vehicle State",
                vehicle_state,
                () => {
                },
                "error"
            );
            return;
        }

        if (vehicle['has_tom_card'] === 'Y') {
            tmsApp.showSystemMessage("Vehicle Has A Tom Card",
                vehicle_tom_card_message,
                () => {
                },
                "error"
            );
            return;
        }

        if (!hasValidInsurance) {
            tmsApp.showSystemMessage("Vehicle Has Expired Insurance",
                insurance_message,
                () => {
                },
                "error"
            );
            return;
        }

        let vLabel = vehicle['body_type_name'] ? vehicle['body_type_name'] : ''
            + ' ' + vehicle['brand_name']
            + ' ' + vehicle['model_name']
            + ' ' + vehicle['model_code'];
        $("#vehicle_description").val(vLabel);
        $("#vehicle_status").text(vehicle['status_name']);

        if (vehicle.fuel_allocation) {
            let perWeekAllocation = vehicle.fuel_allocation * 7;
            document.querySelector('[name="fuel_allocation"]').value = perWeekAllocation ?? 0;
            document.querySelector('[name="material_quantity"]').value = perWeekAllocation ?? 0;

            $('[name="material_quantity"]')
                .attr('max', perWeekAllocation?.toString())
                .attr('data-max', perWeekAllocation?.toString())
                .attr('min', vehicle.fuel_allocation);

            $('#totalQty').text(tmsApp.numberFormat(perWeekAllocation));
        }

        enableSubmissionAndDetailsOptions();

        if (article) {

            /* Material Description and name */
            $("#material_description").text(article['name']);
            $('input[name="material_description"]').val(article['name']);
            $('input[name="material_article_code"]').val(article['code']);

            /* Unit Of Measure */
            $("#unit_of_measure").text(article['description']);
            $('input[name="unit_of_measure"]').val(article['description']);

            /* Material Price*/
            $("#material_price").text(tmsApp.formatMoney(article['price'], 2));
            $('input[name="material_price"]').val(article['price']).change();
        }

        if (images && images.length > 0) {
            let frontViewImages = images.filter((image) => {
                return image['file_type'] === 'Front View';
            })
            let imagePath = frontViewImages[0]?.path;
            document.querySelector(".imagePreview")
                .style
                .backgroundImage = "url(/storage" + imagePath + ")";
        }

        findLatestRequisition();
    }

    function findEmployee() {
        const staff_number = document.querySelector('#driver_staff_number').value
        let formData = new FormData();
        formData.append('searchCriteria', staff_number);
        $('#driver_name').val('');
        fetch(
            document.querySelector("#driver_staff_number").getAttribute('data-action'),
            {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: formData,
                referrer: window['baseUrl'],
                mode: 'cors',
                credentials: 'same-origin',
            }
        )
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                return response.json();
            })
            .then(response => {
                if (!response.success || response.payload.length === 0) {
                    tmsApp.systemError('Driver Verification', response['message']);
                    return;
                }

                if (response.payload.hasOwnProperty('con_st_code')
                    && ['01', 'ACT'].indexOf(response.payload['con_st_code']) === -1) {
                    tmsApp.systemError('Driver Verification',
                        appMessages.inactiveEmployee.replace('@staff', staff_number)
                    );
                    return;
                }

                if (response.payload.hasOwnProperty('status')
                    && ['01', 'ACT'].indexOf(response.payload['status']) === -1) {
                    tmsApp.systemError('Driver Verification',
                        appMessages.inactiveEmployee.replace('@staff', staff_number)
                    );
                    return;
                }

                document.querySelector('#driver_name').value = response.payload.name;
            })
            .catch(function (xhr, settings, error) {
                tmsApp.showErrorMessages(xhr, 'Driver Validation');
            });
    }

    function findVehicle() {
        const numberPlate = $vehicleRegistrationCtl.val();
        let formData = new FormData();
        formData.append('vehicle_registration', numberPlate);

        tmsApp.asyncGetFormData(
            $vehicleRegistrationCtl.attr('data-action') + '?vehicle_registration=' + numberPlate,
            formData,
            function (response_data) {
                if (response_data.success === 'true' || response_data.success === true) {
                    populateVehicleDetails(response_data.payload, response_data['message']);

                    let $odometerCtrl = $('[data-validation="fuelRequisitionOdometerReading"]');
                    if ($odometerCtrl.val()) {
                        $odometerCtrl.trigger('change');
                    }

                    findLatestRequisition();
                } else {
                    removeSubmissionAndDetailsOptions();
                    let $message = response_data['message']
                        ? response_data['message']
                        : appMessages.vehicleNotFound;
                    tmsApp.systemError('Vehicle', $message);
                }
            },
            function (xhr) {
                tmsApp.systemError('System Message',
                    appMessages.generalError);
            }
        )
    }

    function findLatestRequisition() {
        const numberPlate = document.querySelector('#vehicle_registration').value
        let formData = new FormData();
        formData.append('vehicle_registration', numberPlate)

        fetch(
            document.querySelector("#previousRequisitionUrl").value,
            {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: formData,
                referrer: window['baseUrl'],
                mode: 'cors',
                credentials: 'same-origin',
            }
        )
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                return response.json();
            })
            .then(response => {
                if (response.message === 'Not Found') {
                    hasPreviousRequisition = false;
                } else {
                    previousRequisition = response.payload;
                    hasPreviousRequisition = true;
                }
            })
            .catch(function (xhr, settings, error) {
                tmsApp.showErrorMessages(xhr,
                    'Previous Requisition Check');
            });
    }

    function eventHandler(element, e) {
        let $table = $('#materialDetailsTable');

        switch (element.name) {
            case 'material_price':
                // line total = new material price multiplied by quantity value
                let totalAmount = tmsApp.getFloat(element.value) *
                    tmsApp.getFloat($(element).closest("tr").find("input[name=material_quantity]").val());
                $(element).closest("tr").find("input[name=material_amount]").val(totalAmount).change();
                $(element).closest("tr").find("#material_amount").text(tmsApp.numberFormat(totalAmount));
                break;

            case 'material_quantity':
                let summaryTotalQty = 0;
                $table.find("input[name=material_quantity]").each(function (i, it) {
                    summaryTotalQty += tmsApp.getFloat(it.value);
                });

                $('#totalQty').text(tmsApp.numberFormat(summaryTotalQty));
                // line total = new quantity value multiplied by material price
                let lineAmountTotal = tmsApp.getFloat(element.value) *
                    tmsApp.getFloat($(element).closest("tr").find("input[name=material_price]").val());
                $(element).closest("tr").find("input[name=material_amount]").val(lineAmountTotal).change();
                $(element).closest("tr").find("#material_amount").text(tmsApp.numberFormat(lineAmountTotal));
                break;
            case 'material_amount':
                // calculate new footer total
                let summaryTotal = 0;
                $table.find("input[name=material_amount]").each(function (i, it) {
                    summaryTotal += tmsApp.getFloat(it.value);
                });
                $('#totalAmount').text(tmsApp.numberFormat(summaryTotal, 2));
                break;
            default:
                break;
        }
    }

    function loadTowns() {
        let selector = document.querySelector('#departureTown');
        if (!selector || selector?.type === "text") {
            return;
        }
        for (const value of window['citiesFrom']) {
            const option = document.createElement("option");
            option.value = value['town_name'];
            option.text = value['town_name'];
            selector.add(option, null);
        }
    }

    function reformatDate(date, format = "ISO") {

        let data = '';
        if (format === 'ISO') {
            let datePart = new Intl.DateTimeFormat('en-GB').format(date);
            console.log(datePart);
            let dateParts = datePart.split('/');
            data = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
        }

        return data

    }

    function setDistance() {
        let departureVal = document.querySelector("[name='departureTown']").value;
        let destination = document.querySelector('[name="destinationTown"]');
        let destinationVal = destination.value;
        if (!destinationVal) {
            return;
        }
        if (!departureVal) {
            return;
        }

        $("#one_way").text(departureVal + ' --> ' + destinationVal);
        let distance = destination.selectedOptions[0].dataset['distance'];
        let $coveredKilometerCtrl = document.querySelector('[name="covered_kilometers"]');

        $("#one_way_distance").text(distance);
        $coveredKilometerCtrl.value = (distance);
        $($coveredKilometerCtrl).change();
    }

    function determineAppropriateEndDate() {
        const startDate = document.getElementById("departure_date").value;
        let date = new Date(startDate);
        // Add 7 Days
        let maxDate = reformatDate(date.setDate(date.getDate() + 7));
        document.querySelector('[name="return_date"]').setAttribute('max', maxDate);
    }

    $('#driver_staff_number').on('keyup paste enter', function () {
        if (!this.value || this.value.length < 5) {
            return;
        }
        setTimeout(function () {
            findEmployee();
        }, 300);
    });

    $vehicleRegistrationCtl.on('change paste', function () {
        if (!this.value || this.value.indexOf('_') > -1) {
            return;
        }
        setTimeout(function () {
            findVehicle();
        }, 300);
    });

    $('#employeeSearchBtn').on('click', function () {
        if (!document.querySelector("#driver_staff_number").value
            || document.querySelector("#driver_staff_number").value.length < 5) {
            toastr.warning('Invalid Employee Id Number')
            return;
        }

        setTimeout(function () {
            findEmployee();
        }, 300);
    });

    $('#vehicleSearchBtn').on('click', function () {
        if (!document.querySelector('#vehicle_registration').value
            || document.querySelector('#vehicle_registration').value.indexOf('_') > -1) {
            return;
        }
        removeSubmissionAndDetailsOptions();
        findVehicle();
    });

    $(document).on('keypress', '.number_input', function (event) {
        tmsApp.numberOnly(event);
    })

    loadTowns();

    $('.select2').select2({
        theme: 'bootstrap4'
    });

    tmsApp.appFormValidator('form[name="fuelRequisitionForm"]',
        {
            'requisition_type': {
                required: true,
            },
            driver_staff_number: {
                required: false
            },
            fuel_allocation: {
                required: true
            },
            project_code: {
                required: '#projectInput:checked'
            },
            'cost_centre_code': {
                required: '#costOnCostCentre:checked'
            },
            justification: {
                required: true,
                minlength: 15,
                maxlength: 255
            },
            projectCode: {
                required: true
            },
            material_quantity: {
                required: true
            }
        },
        {
            'requisition_type': {
                required: "You have not declared the type of requisition"
            },
            'fuel_allocation': {
                required: "The vehicle does not have a valida fuel allocation"
            },
            'dateOpened': {
                required: "You must specify date task was opened"
            },
            'justification': {
                required: "Purpose for requisition is mandatory",
                minlength: "The reason needs to be at least {0} characters!",
                maxlength: "The reason must not be more than 255 characters"
            },
            projectCode: {
                required: 'Missing Project Code'
            },
            material_quantity: {
                required: 'You have not declared the quantity being requested for'
            },
            project_code: {
                required: 'Project Code is missing'
            },
            odometer_reading: {
                required: 'You must declare the odometer reading'
            },
        }
    );

    $('#submitRequisitionBtn').on('click', function () {
        let $form = document.forms['fuelRequisitionForm'];
        if (!$($form).valid()) {
            tmsApp.systemError('Data Validation',
                'One or more input has invalid or missing data',
                null);
            return;
        }

        window.loaderMessage = 'Submitting Fuel Requisition.  Please wait...';

        $('.print-error-msg').css('display', 'none');
        let formData = new FormData($form);

        formData.set('departureTown', $("#departureTown > option:selected").text())
        formData.set('destinationTown', $("#destinationTown > option:selected").text())

        tmsApp.confirm(
            'Fuel Requisition',
            'Are you sure you want to submit this request ?',
            'Yes',
            'No',
            function () {
                window.top.tmsApp.asyncPostFormData(
                    $form.action,
                    formData,
                    function (asyncResponse) {
                        window.loaderMessage = "Please wait...";
                        if (asyncResponse.hasOwnProperty('success') && asyncResponse['success']) {
                            setTimeout(function () {
                                tmsApp.showSystemMessage(
                                    'Fuel Requisition',
                                    asyncResponse['message'],
                                    function () {
                                        window.location.href = asyncResponse["redirectUrl"]
                                    },
                                    'success'
                                );
                            }, 300);
                        } else {
                            window.loaderMessage = "Please wait...";
                            if (asyncResponse.hasOwnProperty('errors')) {
                                tmsApp.printErrorMsg(asyncResponse.errors);
                                return
                            }
                            setTimeout(function () {
                                tmsApp.systemError(
                                    'Fuel Requisition',
                                    asyncResponse['message'],
                                    function () {
                                    }, 'error');
                            }, 300);
                        }
                    },
                    function (xhr, settings, errorThrown) {
                        console.log(errorThrown);
                        window.loaderMessage = "Please wait...";
                        setTimeout(function () {
                            if ('responseJSON' in xhr) {
                                if (xhr.responseJSON.hasOwnProperty('errors')) {
                                    tmsApp.printErrorMsg(xhr.responseJSON.errors);
                                }
                                if (xhr.responseJSON.hasOwnProperty('message')) {
                                    tmsApp.systemError(
                                        'Fuel Requisition',
                                        xhr.responseJSON['message']
                                    );
                                }
                                return;
                            }

                            tmsApp.systemError(
                                'Fuel Requisition',
                                appMessages.generalError
                            );
                        }, 300)
                    }
                )
            },
            function () {
            }
        );
    })

    $('#resetRequisitionBtn').on('click', function () {
        document.forms['fuelRequisitionForm'].reset();
        removeSubmissionAndDetailsOptions();
    });

    $(document).on('change', 'select[name="requisition_type"]', function () {

        if (this.value === document.querySelector("#outOfTownReqCode").value) {
            $(".outOfTown").removeClass('d-none');

            $("#allocationContainer").addClass('d-none');

            $('#authorityToTravelContainer').removeClass('d-none');

            document.querySelector('#authorityToTravel').setAttribute('required', 'required');

            if (!$('#authorityToTravel').parent().hasClass('jFiler')) {
                new tmsApp.fileUploader().makeSingleFileUploader();
            }

            document.querySelector('#departureTown').setAttribute('required', 'required');

            document.querySelector('#destinationTown').setAttribute('required', 'required');

            document.querySelector('#return_date').setAttribute('required', 'required');

            document.querySelector('#departure_date').setAttribute('required', 'required');

            document.querySelector('[name="material_quantity"]').removeAttribute('max');

            $('[name="material_quantity"]').val('').change();

            $('#nextRefuelingDateContainer').addClass('d-none');

        } else {
            $(".outOfTown").addClass('d-none');
            $("#allocationContainer").removeClass('d-none');

            document.querySelector('#departureTown').removeAttribute('required');
            document.querySelector('#destinationTown').removeAttribute('required');
            document.querySelector('#return_date').removeAttribute('required');
            document.querySelector('#departure_date').removeAttribute('required');

            $('#authorityToTravelContainer').addClass('d-none');
            document.querySelector('#authorityToTravel').removeAttribute('required');

            $('#authorityToTravel').trigger("filer.reset")

            document.querySelector('[name="material_quantity"]')
                .setAttribute('max', document.querySelector('[name="material_quantity"]')
                    .getAttribute('data-max'));

            $('#nextRefuelingDateContainer').removeClass('d-none');
        }
    });

    // cost allocation view
    $('input[name="CostAssignedTo"]').on('change', function () {

        const $projectCodeCtrl = document.querySelector('#project_code');
        const $costCentreCodeCtrl = document.querySelector('#cost_centre_code');
        const $costCentreNameCtrl = document.querySelector('#cost_center_name');

        if (this.value === 'CostCenterBasedRequisition') {
            $projectCodeCtrl.setAttribute('disabled', 'disabled');
            $projectCodeCtrl.removeAttribute('required');

            $($projectCodeCtrl).val('').change();

            // make cost center code visible and required
            $costCentreCodeCtrl.setAttribute('required', 'required');
            $costCentreCodeCtrl.style.display = null;

            $costCentreNameCtrl.setAttribute('required', 'required');
            $costCentreNameCtrl.style.display = null;
            $('[name="ProjectName"]').val('');

            $('.project_view_item').addClass('d-none');

        } else if (this.value === 'ProjectBasedRequisition') {

            $projectCodeCtrl.removeAttribute('disabled');
            $projectCodeCtrl.setAttribute('required', 'required');


            $costCentreCodeCtrl.removeAttribute('required');
            $costCentreCodeCtrl.style.display = 'none';

            $costCentreNameCtrl.removeAttribute('required');
            $costCentreNameCtrl.style.display = 'none';

            initProjectSelector('.project-code-ajax');

            $('.project_view_item').removeClass('d-none');
        }
    });

    $("[name='odometer_reading']").on('change', function () {
        //setTimeout
        const odometerReading = document.querySelector('#odometer_reading').value;
        const numberPlate = document.querySelector('#vehicle_registration').value;
        let formData = new FormData();
        formData.append('odometer_reading', odometerReading);
        formData.append('vehicle_registration', numberPlate);

        document.querySelector('#submitRequisitionBtn').setAttribute('disabled', 'disabled');

        const dataSet = document.querySelector('#odometer_reading').dataset;

        window.loaderMessage = "Validating Odometer, Please Wait !";

        tmsApp.asyncPostFormData(
            dataSet['url'],
            formData,
            function (response) {
                window.loaderMessage = "Please wait...";
                if (!response.success) {
                    tmsApp.systemError(
                        'Odometer Validation',
                        response['message']);
                } else {
                    tmsApp.showToast(response['message'], 'success');
                    document.querySelector('#submitRequisitionBtn').removeAttribute('disabled');
                }
            },
            function (xhr) {
                window.loaderMessage = "Please wait...";
            }
        );
    });

    $(document).on('click', '[data-action="open_picker"]', function () {
        const picker = this.getAttribute('data-target');
        let el = document.querySelector('[name="' + picker + '"]');
        if (!el) return;
        el.showPicker()
    });

    $(document).on('paste keydown', '.date_input', function () {
        return false;
    });

    $(".date_input").on('change', function (e) {
        if (this.name === 'departure_date') {
            determineAppropriateEndDate();
        }

        const startDate = document.getElementById("departure_date").value;
        const endDate = document.getElementById("return_date").value;

        let diffInMs = new Date(endDate) - new Date(startDate)
        let diffInDays = diffInMs / (1000 * 60 * 60 * 24);
        if (!startDate || !endDate) {
            return;
        }

        if (diffInDays > window.tripPeriodLimit) {
            new Swal('Day Limit',
                appMessages.invalidTripPeriod,
                'info');

            determineAppropriateEndDate();
        } else if (diffInDays < 0) {
            new Swal('Invalid Dates Selected',
                'Departure date is before Return date or ',
                'info');
        }
    });

    $('[name="justification"]').on("keyup", function () {
        this.value = this.value.toUpperCase();
    });

    $(document).on('change', '#departureTown', function () {
        let selectedTown = $(this).val();
        let selector = document.querySelector('[name="destinationTown"]');
        $('[name="destinationTown"]').empty();

        let otherCities = [];
        for (const [key, value] of Object.entries(window['citiesMap'])) {
            if (selectedTown === value['town_from']) {
                otherCities.push(value);
            }
        }

        for (const value of otherCities) {
            const option = document.createElement("option");
            option.value = value['town_to'];
            option.text = value['town_to'];
            option.dataset.distance = value['distance'];
            selector.add(option, null);
        }
        // enable disabled destination input
        let destinationSelector = document.querySelector('[name="destinationTown"]');
        if (destinationSelector.attributes.getNamedItem('disabled')) {
            destinationSelector.attributes.removeNamedItem('disabled');
        }

        setDistance();
    });

    $('#destinationTown').on('change', function () {
        setDistance();
    });

    $('#materialDetailsTable').on('change', 'select, input', function (e) {
        eventHandler(this, e);
    }).on('keyup', 'select,input,textarea', function (e) {
        eventHandler(this, e);
    }).on('blur', 'input', function (e) {
        if (this.name === 'quantity' || this.name === 'material_quantity') {
            $(this).val(tmsApp.numberFormat(this.value));
        }
    });
})(window.tmsApp || {}, jQuery)

$(document).ready(function () {
    Inputmask({
        "mask": "AAA 9{1,4}"
    }).mask("#vehicle_registration");
});
