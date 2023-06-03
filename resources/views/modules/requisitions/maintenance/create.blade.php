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
                    @if(!empty($document_reference))
                        <span class="ml-2 indicator-pill whitespace-nowrap green">
                            <span>Saved</span>
                        </span>
                    @else
                        <span class="ml-2 indicator-pill whitespace-nowrap orange"><span>Not Saved</span></span>
                    @endif
                </div>
                @if(!empty($document_reference))
                    <div class="card-toolbar justify-content-end">
                        JOB CARD NUMBER: <span class="text-orange">{{ $document_reference ?? '' }}</span>
                    </div>
                @endif

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

                    </div>

                    <h1>Defects</h1>
                    <div> 3
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
            let bodyTag = "fieldset";

            function initializeFormWizard() {
                function postData(formElements, submitForm) {
                    window.loaderMessage = "Posting Data... please wait";
                    let $container = $(formElements);

                    let formSel = $(formElements);

                    let formData = {
                        modelName: formSel.data('modelName'),
                        submitForm: submitForm
                    };

                    let obj = {};
                    $($container).find('input[name], select[name]').each(function (i, item) {
                        let val = item.value.replace(/,/g, '');

                        if (item.name === 'endDate' || item.name === 'startDate' || item.name === 'invoiceDate') {
                            let dateField = val;
                            obj[item.name] = dateField;
                        } else {
                            obj[item.name] = item.value || 0;
                        }
                    });

                    formData = {
                        ...obj,
                        ...formData
                    }

                    formSel.find('input[name], select[name]').each(function (i, item) {
                        formData[item.name] = item.value;
                    });

                    $.ajax({
                        type: "POST",
                        url: formSel.data('formUrl'),
                        data: JSON.stringify(formData),
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                    }).done(function (response) {
                        window.loaderMessage = "Loading... please wait";
                        if (response.hasOwnProperty("success") && response.success) {
                            tmsApp.showSystemMessage(
                                "Job Card Details Submitted",
                                "Request submitted successfully, Click 'Ok' proceed to provide information for other sections",
                                function () {
                                    window.location.href = response['redirectUrl'];
                                },
                                "success"
                            );
                        } else {
                            if (!Util.isEmpty(response.errors)) {
                                if (response.errors) {
                                    tmsApp.printErrorMsg(response.errors);
                                }
                            } else if (!Util.isEmpty(response.message)) {
                                tmsApp.systemError("Job Card Details Submitted", response.message);
                            }
                        }
                    }).fail(function (xhr) {
                        tmsApp.showErrorMessages(xhr,  "Job Card Details Submitted");
                    })
                }

                form.steps({
                    showStepURLhash: true,
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
                            postData($(form.find(bodyTag).get(currentIndex)).find('[data-model-name]').get(0), true);

                        } else {
                            //$('a[role="#finish"]').enableBtn();
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
                    rules: {
                        vehicle_registration: {
                            required: true
                        },
                        workshop: {
                            required: true
                        }
                    },
                    messages: {
                        workshop: {
                            required: "Select the workshop vehicle is being checked-into"
                        },
                        vehicle_registration: {
                            required: "Vehicle Registration is required"
                        },

                        current_odometer: {
                            required: "Enter current odometer reading"
                        },
                        repairType: {
                            required: "Select type of repair"
                        }
                    }
                });
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
                //let article = payload['article'];
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

            function findDriver() {
                const staff_number = document.querySelector('#driver_staff_number').value
                let formData = new FormData();
                formData.append('searchCriteria', staff_number);

                fetch(
                    document.querySelector("#driver_staff_number").getAttribute('data-action'),
                    {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: formData,
                        referrer: window.baseUrl,
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
                        //c
                        console.log(response);

                        if (!response.success || response.payload.length == 0) {
                            tmsApp.systemError('Driver Verification', response['message']);
                            return;
                        }

                        let optionListStr = '';
                        if (Array.isArray(response.payload)) {
                            response.payload.forEach(function (item) {
                                optionListStr += `<option value="${item['con_per_no']}">${item['con_per_no']} =>${item.name}</option>`;
                            })

                            $('#employee_list').html(optionListStr);
                            return;
                        }

                        document.querySelector('#driver_name').value = response.payload.name;
                    })
                    .catch(function (xhr, settings, error) {
                        tmsApp.showErrorMessages(xhr, 'Driver Validation');
                    });
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

            $('#driver_staff_number').on('keyup paste enter', function () {
                if (!this.value || this.value.length < 5) {
                    return;
                }
                setTimeout(function () {
                    findDriver();
                }, 300);
            });

            $('#employeeSearchBtn').on('click', function () {
                if (!document.querySelector("#driver_staff_number").value
                    || document.querySelector("#driver_staff_number").value.length < 5) {
                    toastr.warning('Invalid Employee Id Number')
                    return;
                }
                setTimeout(function () {
                    findDriver();
                }, 300);
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
