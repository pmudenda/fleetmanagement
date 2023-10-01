(function (tmsApp, $) {
    let $vehicleRegistrationCtl = window['vehicleRegistrationCtl'];

    tmsApp.populateVehicleDetails = function (payload) {
        let vehicle = payload['vehicle'];
        let article = payload['article'];
        let images = payload['images'];
        const hasValidFitness = payload['hasValidFitness'];
        const hasValidRoadTax = payload['hasValidRoadTax'];
        const hasValidInsurance = payload['hasValidInsurance'];
        let vehicle_state = payload['vehicle_state'];
        let vehicle_tom_card_message = payload['vehicle_tom_card_message'];
        let insuranceMessage = payload['insuranceMessage'];
        let roadTaxMessage = payload['roadTaxMessage'];
        let fitnessMessage = payload['fitnessMessage'];

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
                insuranceMessage,
                () => {
                },
                "error"
            );
            return;
        }

        if (!hasValidFitness) {
            tmsApp.confirm(
                "Vehicle Has Expired Fitness",
                fitnessMessage,
                'Yes',
                'No, Cancel',
                () => {
                },
                () => {
                    window.location.reload();
                },
                "error"
            );
        }

        if (!hasValidRoadTax) {
            tmsApp.confirm(
                "Vehicle Has Expired Road Tax",
                roadTaxMessage,
                'Yes',
                'No, Cancel',
                () => {
                },
                () => {
                    window.location.reload();
                },
                "error"
            );
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

        // findLatestRequisition();
    }

    tmsApp.findVehicle = function ($vehicleRegistrationCtl) {
        const numberPlate = $vehicleRegistrationCtl.val();
        let formData = new FormData();
        formData.append('vehicle_registration', numberPlate);

        tmsApp.asyncGetFormData(
            $vehicleRegistrationCtl.attr('data-action') + '?vehicle_registration=' + numberPlate,
            formData,
            function (response_data) {
                if (response_data.success === 'true' || response_data.success === true) {
                    tmsApp.populateVehicleDetails(response_data.payload, response_data['message']);

                    let $odometerCtrl = $('[data-validation="fuelRequisitionOdometerReading"]');
                    if ($odometerCtrl.val()) {
                        $odometerCtrl.trigger('change');
                    }
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
})(window.tmsApp || {}, jQuery);

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
        'If your trip is more than 7 days, you will have to create a second trip ',
    profileDelegationTitle: 'Profile Delegation',
    selfDelegation: 'You can not delegate a profile to the owner.'
};

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



