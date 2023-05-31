@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@push('styles')
    <link href="{{asset("assets/plugins/select2/css/select2.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css")}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset("libs/steps/jquery-steps.css")}}" rel="stylesheet" type="text/css"/>
    <style>
        th {
            white-space: nowrap;
        }
    </style>
@endpush
@section('content')

    <x-content-header
        :activeCrumb="'New Job Card'"
        :linkText="'Job Card'"
        :pageTitle="'Workshop Management'"/>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h4>Workshop Job Card</h4>
                    <span class="ml-2 indicator-pill whitespace-nowrap orange"><span>Not Saved</span></span>
                </div>
            </div>

            <div class="card-body pb-4 min-h-600px pt-0">

                <x-error-view/>

                <label class="app-required-marker"></label>
                <form name="jobCardForm"
                      id="jobCardForm"
                      action="{{route('save.workshop.requisition')}}"
                      method="post">
                    @csrf
                    <h1>Job Card Details</h1>
                    <div>
                        @include('modules.requisitions.maintenance.tabs.job_card_header')
                    </div>

                    <h1>Accessories Checkin & Movement</h1>
                    <div>
                        @include('modules.requisitions.maintenance.tabs.accessories')
                    </div>

                    <h1>Defects</h1>
                    <div> 3
                        {{-- <h2>Driver Details</h2>--}}
                        {{--<div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="staffNo">Staff Number:</label>
                                    <div class="regInput">
                                        <input name="staffNumber" type="text" class="form-control required" id="staffNo" placeholder="Enter Staff Number" required>
                                        --}}{{--                                <button id="staffQuery" class="btn btn-outline-success">Query</button>--}}{{--
                                        --}}{{--                                <button id="staffClear" class="btn btn-outline-success">Clear</button>--}}{{--
                                    </div>
                                </div>
                                @error('staffNumber')
                                <p>{{$message}}</p>

                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label for="driverName">Name:</label>
                                    <input name="driverName" type="text" class="form-control required" id="driverName" placeholder="Enter Driver Name" >
                                </div>
                                @error('driverName')
                                <p>{{$message}}</p>

                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="driverEmail">Email</label>
                                    <input name="driverEmail" type="text" class="form-control required" id="driverEmail" placeholder="Enter Driver Email" required>
                                </div>
                                @error('driverEmail')
                                <p>{{$message}}</p>

                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label for="phoneNo">Phone No:</label>
                                    <input name="phoneNo" type="text" class="form-control required" id="phoneNo" placeholder="Enter Phone No" required>
                                </div>
                                @error('phoneNo')
                                <p>{{$message}}</p>

                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="driverAge">Age*:</label>
                                    <input name="age" type="text" class="form-control required" id="driverAge" placeholder="Enter Driver Age" required>
                                </div>
                                @error('driverAge')
                                <p>{{$message}}</p>

                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="driverPosition">Position*:</label>
                                    <input name="driverPosition" type="text" class="form-control required" id="driverPosition" placeholder="Enter Driver Position" required>
                                </div>
                            </div>
                            @error('driverPosition')
                            <p>{{$message}}</p>

                            @enderror

                        </div>--}}
                    </div>
                    <h1>Parts Selection</h1>
                    <div>Test</div>

                </form>

                <input type="hidden" value="{{ route('user.search') }}" id="newUserSearchUrl">
                <input type="hidden" value="{{route('search.project')}}" id="projects_url">
                <input type="hidden" value="{{route('all.workshop.list')}}" id="workshopsUrl">
                <input type="hidden" value="{{route('fuels.levels')}}" id="fuelLevelsUrl">
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset("libs/steps/jquery.steps.js")}}"></script>
    <script>
        (function (tmsApp, $) {
            let form = $('#jobCardForm').show();
            window.goToNext = false;
            //window.formWizard = form;
            let bodyTag = "fieldset";

            function initializeFormWizard() {
                /*formWizard.on("click", function (e) {
                    e.stopPropagation();
                    $(this).remove();
               let jobCardForm = formWizard.show();*/

                /* function postData() {
                     let form = $(this);

                     let formData = {
                         accidentNature: document.getElementById("accidentNature").value,
                         accidentType: document.getElementById("accidentType").value,
                         peopleInvolved: document.getElementById("peopleInvolved").value,
                         date: document.getElementById("date").value,
                         time: document.getElementById("time").value,
                         description: document.getElementById("description").value,
                         policeNotified: $('input[name="policeNotified"]:checked').val(),
                         staffNumber: document.getElementById("staffNo").value,
                         driverName: document.getElementById("driverName").value,
                         driverEmail: document.getElementById("driverEmail").value,
                         phoneNo: document.getElementById("phoneNo").value,
                         age: document.getElementById("driverAge").value,
                         driverPosition: document.getElementById("driverPosition").value,
                         registrationNo: document.getElementById("registrationNo").value,
                         modelNo: document.getElementById("modelNo").value,
                         vehicleMake: document.getElementById("vehicleMake").value,
                         chassisNo: document.getElementById("chassisNo").value
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

                             if (done) {
                                 location.reload()
                             }

                             modal.hide();
                         });
                     }
                 }*/

                function postData(formElements, submitForm) {
                    window.loaderMessage = "Posting Data... please wait";
                    let $table = $(formElements);

                    let formSel = $(formElements);

                    let formData = {
                        modelName: formSel.data('modelName'),
                        submitForm: submitForm
                    };

                    //.find("tbody").children().map(function (index, row) {});
                    let obj = {};
                    $($table).find('input[name], select[name]').each(function (i, item) {
                        let val = item.value.replace(/,/g, '');

                        if (item.name === 'endDate' || item.name === 'startDate' || item.name === 'invoiceDate') {
                            let dateField = val;
                            //if (item.name !== 'invoiceDate') {
                            //dateField = DateFormatter.format(new Date(moment(val, 'DD/MM/yyyy')), DateFormatter.ISO);
                            //}
                            obj[item.name] = dateField;
                        } else {
                            /*if (item.name === 'unitPrice') {
                                obj[item.name] = tmsApp.getFloat("0.00")
                            }*/

                            /*if ($.isNumeric(parseFloat(val))) {
                                obj[item.name] = tmsApp.getFloat(item.value)
                            }*/
                            obj[item.name] = item.value || 0;

                        }
                    });

                    //arr.push(obj);

                    formData = {
                        ...obj,
                        ...formData
                    }

                    formSel.find('input[name], select[name]').each(function (i, item) {
                        //let map = csvUploader.getMapperValue(item.name, item.value);
                        formData[item.name] = item.value;
                    });

                    //formData['sales'] = arr;

                    $.ajax({
                        type: "POST",
                        url: formSel.data('formUrl'),
                        data: JSON.stringify(formData),
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                    }).done(function (response) {
                        window.loaderMessage = "Loading... please wait";
                        if (response.hasOwnProperty("referenceNumber")) {
                            swal({
                                title: "Return Submitted",
                                text: "The Return filed successfully with reference number: " + response.referenceNumber,
                                type: "success",
                                showCancelButton: false,
                                confirmButtonClass: "btn-primary",
                                confirmButtonText: "View Receipt",
                                cancelButtonText: "Close",
                                closeOnConfirm: true,
                                closeOnCancel: true
                            }, function (isConfirm) {
                                if (isConfirm) {
                                    //getNotice(response);
                                } else {
                                    window.location.href = $("#context-path").val() + 'Returns/acknowledgeReturn';
                                }
                            });
                        } else if (!Util.isEmpty(response.errors)) {
                            if (response.errors) {
                                //csvUploader.showErrors(response.errors);
                            } else {
                                swal('Error', response.message, 'error');
                            }
                        } else if (!Util.isEmpty(response.message)) {
                            swal('Return Submission', response.message, 'error');
                        } else {
                            window.oneScheduleSubmitted = true;
                            goToNext = true;
                            form.steps("next");
                        }
                    })
                }

                form.steps({
                    headerTag: "h1",
                    bodyTag: "div",
                    transitionEffect: "slideLeft",
                    autoFocus: true,
                    saveState: true,
                    startIndex: 0,
                    labels: {
                        finish: 'Submit'
                    },
                    onStepChanging: function (event, currentIndex, newIndex) {
                        // Allways allow previous action even if the current form is not valid!
                        /*  if (currentIndex > newIndex) {
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
                          return true;//form.valid();*/

                        // Always allow previous action even if the current form is not valid!
                        if (currentIndex > newIndex) {
                            return true;
                        }

                        // Needed in some cases if the user went back (clean up)
                        if (currentIndex < newIndex) {
                            // To remove error styles
                            form.find(".body:eq(" + newIndex + ") label.error").remove();
                            form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                        }

                        form.validate().settings.ignore = ":disabled,:hidden";
                        //make inputs required

                        if (form.valid() && !window.goToNext) {
                            postData(form.find('[data-model-name]').get(currentIndex), false);
                        }

                        let tmp = window.goToNext;
                        window.goToNext = false;
                        return tmp;

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

                        window.global_currentIndex = currentIndex;
                        window.goToNext = false;

                    },
                    onFinishing: function (event, currentIndex) {
                        form.validate().settings.ignore = ":disabled";
                        return form.valid();

                    },
                    onFinished: function () {
                        //postData.call(this);
                        //$('a[role="#finish"]').disableBtn();

                        if (form.valid()) {

                            let returnTotal = $(document).find('input[name="returnTotal"]');
                            if (returnTotal) {
                                if (parseFloat(returnTotal.val()) === parseFloat("0.00")) {
                                    swal("Error !", "You have not provided any data for the return", "error");
                                    return;
                                }
                            }
                            if (!window['oneScheduleSubmitted']) {
                                swal({
                                    title: "Return Submission",
                                    text: "At least one schedule must be submitted, Kindly review your data",
                                    type: "success",
                                    showCancelButton: false,
                                    confirmButtonClass: "btn-primary",
                                    confirmButtonText: "View Receipt",
                                    cancelButtonText: "Close",
                                    closeOnConfirm: true,
                                    closeOnCancel: true
                                }, function (isConfirm) {
                                    if (isConfirm) {
                                        return false;
                                    }
                                });
                            } else {
                                postData($(form.find(bodyTag).get(currentIndex)).find('[data-model-name]').get(0), true);
                            }
                        } else {
                            $('a[role="#finish"]').enableBtn();
                            swal("Error !", "You may have some missing data for the return, Kindly review your submission", "error");
                        }

                    },

                }).validate({
                    errorClass: "error-class",
                    validClass: "valid-class",
                    errorElement: 'div',
                    errorPlacement: function (error, element) {
                        if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    onError: function () {
                        $('.input-group.error-class').find('.help-block.form-error').each(function () {
                            $(this).closest('.form-group').addClass('error-class').append($(this));
                        });
                    },
                    /*errorPlacement: function errorPlacement(error, element) {
                        error.insertAfter(element);
                    },*/
                    rules: {},
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
                //});
                // })
            }

            initializeFormWizard();

            function getWorkshops() {
                fetch(document.querySelector('#workshopsUrl').value)
                    .then(response => response.json())
                    .then(response => {
                        let selectElem = $('select[name="workshop"]');
                        // Populate results
                        if (response.state === 'failure') {
                            //show errors
                            toastr.error('Connection error, no data found')
                            return;
                        }

                        let workshops = response['payload'];
                        tmsApp.populateDropDownList(selectElem, workshops, "workshop_code", ["workshop_name", "area_code"], "=>");

                        /*let location = selectElem.attr('data-value');
                        console.log(location);
                        if (location) {
                            selectElem.val(location);
                            selectElem.trigger('change');
                        }*/

                    })
                    .catch(function (error) {
                        // notify of error
                        toastr.error(
                            'Connection error. Could not retrieve data, some feature might not work.')
                    });
            }

            function getFuelLevels() {
                fetch(document.querySelector('#fuelLevelsUrl').value)
                    .then(response => response.json())
                    .then(response => {
                        let selectElem = $('select[name="fuel_level"]');
                        // Populate results
                        if (response.state === 'failure') {
                            //show errors
                            toastr.error('Connection error, no data found')
                            return;
                        }

                        let fuelLevels = response['payload'];
                        tmsApp.populateDropDownList(selectElem, fuelLevels, "code", ["name"], "");

                    })
                    .catch(function (error) {
                        // notify of error
                        toastr.error(
                            'Connection error. Could not retrieve data, some feature might not work.')
                    });
            }

            function removeSubmissionAndDetailsOptions() {
                let elements = document.querySelectorAll('.when_valid');
                elements.forEach(function (element) {
                    element.setAttribute('disabled', 'disabled');
                });

                document.querySelector('#image_view').style.display = 'none';

                $('tbody#vehicleDetails').html('');

                //$("#material_description").text(tmsApp.formatMoney('0', 2));
                //$('input[name="material_description"]').val(tmsApp.formatMoney('0', 2));
            }

            function enableWebUIControls() {

                let elements = document.querySelectorAll('.when_valid');

                elements.forEach(function (element) {
                    element.removeAttribute('disabled');
                });

                document.querySelector('#vehicleDetailsContainer').style.display = null;
                //document.querySelector('#materialDetailsContainer').style.display = null;
                document.querySelector('#image_view').style.display = null;
            }

            function populateVehicleDetails(payload) {
                let vehicle = payload['vehicle'];
                let article = payload['article'];
                let images = payload['images'];
                let vehicle_state = payload['vehicle_state'];

                if (!vehicle || !vehicle.brand_name) {
                    return;
                }

                /*if (typeof vehicle.fuel_allocation === 'undefined' || vehicle.fuel_allocation == null || vehicle.fuel_allocation === "0") {

                    tmsApp.showSystemMessage("Vehicle Details Incomplete",
                        'Vehicle has no Fuel Allocation, Request System Administrator to assign allocation', () => {
                        }, "error")

                    return;
                }*/

                // BAD 1010
                if (vehicle['on_boarding_status'] != '030') {
                    tmsApp.showSystemMessage("Incomplete Vehicle Details",
                        `The vehicle ${vehicle['registration_number']} is ${vehicle_state}. Please Contact Fleet Master
                            System Administrator on 3309,3350,3351,3306, fleetmaster@zesco.com`,
                        () => {
                        },
                        "error");
                    return;
                }

                let vLabel = vehicle['body_type_name'] + ' ' + vehicle['brand_name'] + ' ' + vehicle['model_name'] + ' ' + vehicle['model_code'];
                $("#vehicle_description").val(vLabel);
                let row = `<tr><th>Make</th><td id="make">${vehicle.brand_name}</td></tr>
                               <tr>
                                    <th>Model</th><td id="model">${vehicle.model_name} ${vehicle.model_code}</td>
                               </tr>
                               <tr style="">
                                     <th>Type</th><td id="registration">${vehicle['body_type_name']}</td>
                                </tr>`;

                $('tbody#vehicleDetails').html(row);

                /*if (vehicle.fuel_allocation) {
                    let perWeekAllocation = vehicle.fuel_allocation * 7;
                    document.querySelector('[name="fuel_allocation"]').value = perWeekAllocation ?? 0;
                    document.querySelector('[name="material_quantity"]').value = perWeekAllocation ?? 0;
                    document.querySelector('[name="material_quantity"]').setAttribute('max', perWeekAllocation);
                    $('#totalQty').text(tmsApp.numberFormat(perWeekAllocation));
                }*/

                enableWebUIControls();

                /*if (article) {

                    $("#material_description").text(article['name']);
                    $('input[name="material_description"]').val(article['name']);
                    $('input[name="material_article_code"]').val(article['code']);

                    $("#unit_of_measure").text(article['short_name']);
                    $('input[name="unit_of_measure"]').val(article['short_name']);

                    $("#material_price").text(tmsApp.formatMoney(article['price'], 2));
                    $('input[name="material_price"]').val(article['price']).change();
                }*/

                if (images && images.length > 0) {
                    let frontViewImages = images.filter((image) => {
                        return image['file_type'] === 'Front View';
                    })
                    let imagePath = frontViewImages[0]?.path;
                    document.querySelector(".imagePreview").style.backgroundImage = "url(/storage" + imagePath + ")";
                }

            }

            function findVehicle() {
                const numberPlate = document.querySelector('#vehicle_registration').value
                let formData = new FormData();
                formData.append('vehicle_registration', numberPlate);

                tmsApp.asyncGetFormData(
                    $('#vehicle_registration').attr('data-action') + '?vehicle_registration=' + numberPlate,
                    formData,
                    function (response_data) {
                        if (response_data.success === 'true' || response_data.success === true) {
                            populateVehicleDetails(response_data.payload);
                        } else {
                            removeSubmissionAndDetailsOptions();
                            tmsApp.systemError(
                                'Vehicle',
                                'Vehicle with Registration No.' + numberPlate
                                + ' was not found, Check your input and try again',
                                function () {
                                });
                        }
                    },
                    function (xhr) {
                        tmsApp.systemError(
                            'System Message',
                            'We could not complete processing your request, please try again later',
                            function () {
                            });
                    }
                )
            }

            function eventHandler(element, e) {
                let $table = $('#materialDetailsTable');

                switch (element.name) {
                    case 'material_price':
                        // line total = new material price multiplied by quantity value
                        let totalAmount = tmsApp.getFloat(element.value) * tmsApp.getFloat($(element).closest("tr").find("input[name=material_quantity]").val());
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
                        let lineAmountTotal = tmsApp.getFloat(element.value) * tmsApp.getFloat($(element).closest("tr").find("input[name=material_price]").val());
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
                    default:
                        break;
                }
            }

            $('#vehicle_registration').on('keyup paste enter', function () {
                if (!this.value || this.value.replace('_', '').length < 8) {
                    return;
                }
                setTimeout(function () {
                    removeSubmissionAndDetailsOptions();
                    findVehicle();
                }, 300);
            });

            $('#vehicleSearchBtn').on('click', function () {
                if (document.querySelector('#vehicle_registration').value && document.querySelector('#vehicle_registration') < 8) {
                    return;
                }
                removeSubmissionAndDetailsOptions();
                findVehicle();
            });

            $(document).on('keypress', '.number_input', function (event) {
                tmsApp.numberOnly(event);
            })

            Inputmask({
                "mask": "AAA 9999"
            }).mask("#vehicle_registration");

            /* tmsApp.appFormValidator('form[name="fuelRequisitionForm"]',
                 {
                     'requisition_type': {
                         required: true,
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
                     }
                 }
             );*/

            $('#submitRequisitionBtn').on('click', function () {
                let $form = document.forms['fuelRequisitionForm'];
                if (!$($form).valid()) {
                    return;
                }

                $('.print-error-msg').css('display', 'none');
                let formData = new FormData($form);
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

                                if (asyncResponse.hasOwnProperty('success') && asyncResponse['success']) {
                                    setTimeout(function () {
                                        tmsApp.showSystemMessage(
                                            'Fuel Requisition',
                                            asyncResponse['message'],
                                            function () {
                                                window.location.href = asyncResponse["redirectUrl"]
                                                //window.location.reload();
                                            },
                                            'success'
                                        );
                                    }, 300);
                                } else {
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
                                console.log(errorThrown)
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
                                        'We could not complete processing your request, please try again later');
                                }, 300)
                            }
                        )
                    },
                    function () {
                    }
                );
            })

            $('#repairTypeDropdownList').on('change', function () {
                if (this.value === '001') {
                    document.querySelector("#accidentRecordNo").classList.remove('d-none');
                } else {
                    document.querySelector("#accidentRecordNo").classList.add('d-none');
                }
            });


            $('#materialDetailsTable').on('change', 'select,input', function (e) {
                eventHandler(this, e);
            }).on('keyup', 'select,input,textarea', function (e) {
                eventHandler(this, e);
            }).on('blur', 'input', function (e) {
                if (this.name === 'quantity') {
                    $(this).val(tmsApp.numberFormat(this.value));
                }

            });

            getWorkshops();

            getFuelLevels();
        })(window.tmsApp || {}, jQuery)
    </script>
    <script src="{{asset('assets/js/system/project_code.js').'?v='.Carbon::now()->format('his')}}"></script>
@endpush
