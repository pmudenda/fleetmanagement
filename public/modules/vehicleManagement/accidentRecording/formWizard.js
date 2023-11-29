function initializeFormWizard() {
    let formWizard = $('#my-form');
    let form = formWizard.show();

    form.steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        autoFocus: true,
        labels: {
            finish: 'Submit'
        },
        onStepChanging: function (event, currentIndex, newIndex) {
            // Allways allow previous action even if the current form is not valid!
            if (currentIndex > newIndex) {
                return true;
            }

            // Forbid next action on "Warning" step if the user is to young
            if (newIndex === 3 && Number($("#age-2").val()) < 18) {
                return false;
            }

            // Needed in some cases if the user went back (clean up)
            if (currentIndex < newIndex) {
                // To remove error styles
                form.find(".body:eq(" + newIndex + ") label.error").remove();
                form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
            }

            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },
        onStepChanged: function (event, currentIndex, priorIndex) {
            // Used to skip the "Warning" step if the user is old enough.
            if (currentIndex === 2 && Number($("#age-2").val()) >= 18) {
                form.steps("next");
            }

            // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
            if (currentIndex === 2 && priorIndex === 3) {
                form.steps("previous");
            }
        },
        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            return form.valid();
        },
        onFinished: function (){
            let form = $(this);

            let formData = {
                accidentNature :document.getElementById("accidentNature").value,
                accidentType :document.getElementById("accidentType").value,
                peopleInvolved :document.getElementById("peopleInvolved").value,
                date :document.getElementById("date").value,
                time :document.getElementById("time").value,
                description :document.getElementById("description").value,
                policeNotified: $('input[name="policeNotified"]:checked').val(),
                staffNumber :document.getElementById("staffNo").value,
                driverName :document.getElementById("driverName").value,
                driverEmail :document.getElementById("driverEmail").value,
                phoneNo :document.getElementById("phoneNo").value,
                age :document.getElementById("driverAge").value,
                driverPosition :document.getElementById("driverPosition").value,
                registrationNo :document.getElementById("registrationNo").value,
                modelNo :document.getElementById("modelNo").value,
                vehicleMake :document.getElementById("vehicleMake").value,
                chassisNo :document.getElementById("chassisNo").value
            }


            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    console.log(response)


                    if (response.status === 'success') {
                        console.log(response)
                        launchErrorModal(response.message, "errorDisplay", true)

                    } else {
                        launchErrorModal(response.message, "errorDisplay")
                    }

                },
                error: function () {

                },

            })


            function launchErrorModal(message, id, done) {
                var modalElement = document.getElementById(id);
                var modal = new bootstrap.Modal(modalElement);
                modal.show();

                var modalBody = modalElement.querySelector(".modal-body");
                modalBody.innerHTML = message;

                var modalButton = modalElement.querySelector(".btn-danger");
                modalButton.addEventListener("click", function () {

                    if (done){
                        location.reload()
                    }

                    modal.hide();
                });
            }
        },

    }).validate({
        errorPlacement: function errorPlacement(error, element) {
            error.insertAfter(element);
        },
        rules: {

        },
        messages: {
            accidentType: {
                required: "Accident Type is required when reporting"
            },
            registrationNo: {
                required: "Vehicle Registration is required"
            },
            vehicleMake: {
                required: "Vehicle Make is required"
            },
            vehicleModel: {
                required: "Vehicle Model is required"
            }
        }
    });
}


$(function () {
    initializeFormWizard()
});

$(document).ready(function () {

    $("#vehicleClear").click(function () {

        let vehicleModel = document.getElementById("modelNo")
        let vehicleMake = document.getElementById("vehicleMake")
        let chassisNo = document.getElementById("chassisNo")

        vehicleModel.removeAttribute("disabled")
        vehicleMake.removeAttribute("disabled")
        chassisNo.removeAttribute("disabled")

        vehicleModel.value = ""
        vehicleMake.value = ""
        chassisNo.value = ""
    })

    $('#registrationNo').on('change', function() {
        var query = $(this).val();
        $.ajax({
            url: '/vehicledetails/' + query,
            method: 'GET',
            success: function(response) {
                // Code to execute when the AJAX request succeeds
                let vehicleData = response.data

                if (response.status === 'success') {
                    let vehicleModel = document.getElementById("modelNo")
                    let vehicleMake = document.getElementById("vehicleMake")
                    let chassisNo = document.getElementById("chassisNo")

                    vehicleModel.setAttribute("disabled", true)
                    vehicleMake.setAttribute("disabled", true)
                    chassisNo.setAttribute("disabled", true)


                    vehicleModel.value = vehicleData.modelNo;
                    vehicleMake.value = vehicleData.vehicleMake
                    chassisNo.value = vehicleData.chassisNo
                } else {

                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Code to execute when the AJAX request fails
            }
        });
    });


    // Staff Number

    $('#staffQuery').click(function () {
        let staffNo = document.getElementById("staffNo").value
        $.ajax({
            url: '/staffData/' + staffNo,
            type: "GET",
            dataType: 'json',
            success: function (response) {
                // console.log(response)




            },
            error: function (xhr, status, error) {

            }

        })
    })

    $('#staffNo').on('change', function() {
        var query = $(this).val();
        $.ajax({
            url: '/staffData/' + query,
            method: 'GET',
            success: function(response) {
                // Code to execute when the AJAX request succeeds
                if (response.status === 'success') {
                    let driverDetails = response.data;

                    let driverName = document.getElementById("driverName")
                    let driverEmail = document.getElementById("driverEmail")
                    let driverAge = document.getElementById("driverAge")
                    let driverPosition = document.getElementById("driverPosition")
                    let phoneNo = document.getElementById("phoneNo")


                    driverName.setAttribute("disabled", true)
                    driverEmail.setAttribute("disabled", true)
                    driverAge.setAttribute("disabled", true)
                    driverPosition.setAttribute("disabled", true)
                    phoneNo.setAttribute("disabled", true)


                    driverName.value = driverDetails.driverName;
                    driverEmail.value = driverDetails.driverEmail
                    driverAge.value = driverDetails.age
                    driverPosition.value = driverDetails.driverPosition
                    phoneNo.value = driverDetails.phoneNo

                } else {
                    launchErrorModal(response.message, "errorDisplay")
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Code to execute when the AJAX request fails
            }
        });
    });

})
