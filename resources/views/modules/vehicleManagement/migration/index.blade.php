@extends('layouts.app')
@push('styles')
    <link href="{{ asset('application/modules/vehicleManagement/assets/css/vehicle_migration.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
@endpush
@section('content')
    <x-content-header :pageTitle="'Data Migration'"/>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h4>Data Migration</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-10">
                    <div class="wizard">
                        <div class="wizard-inner">
                            <!-- <div class="connecting-line"></div> -->
                            <ul class="nav nav-tabs steps" role="tablist">
                                <li role="presentation" data-index="0" class="active st1">
                                    <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab"
                                       aria-expanded="true">
                                        <i>Vehicle Details</i>
                                        <span class="round-tab">1</span>
                                    </a>
                                </li>
                                <li role="presentation" data-index="1" class="disabled st2">
                                    <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab"
                                       aria-expanded="false">
                                        <span class="round-tab">2</span>
                                        <i>Vehicle Details</i>
                                    </a>
                                </li>
                                <li role="presentation" data-index="2" class="disabled st3">
                                    <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab">
                                        <span class="round-tab">3</span>
                                        <i>Images</i>
                                    </a>
                                </li>

                            </ul>
                        </div>

                        <form role="form" method="post" class="">
                            @csrf
                            <div class="tab-content px-5" id="main_form">
                                <div class="tab-pane active step" role="tabpanel" id="step1">
                                    <h3 class="text-center">Vehicle Details</h3>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="registrationNumber" class="field-required">
                                                    Registration Number
                                                </label>
                                                <div class="input-group">
                                                    <input name="registrationNumber"
                                                           type="text"
                                                           value="{{$registration ?? ''}}"
                                                           data-action="{{route('cleanup.vehicle.find')}}"
                                                           class="form-control form-control-sm required"
                                                           id="registrationNumber"
                                                           placeholder=""
                                                           required/>
                                                    <div class="input-group-addon">
                                                        <button type="button"
                                                                id="vehicleSearchBtn"
                                                                name="vehicleSearchBtn"
                                                                class="btn btn-success btn-sm border-radius-0">
                                                            <i class="fas fa-search"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group ">
                                                <label class="required-field" for="vehicleType">Make :</label>
                                                <select name="make"
                                                        onchange="loadModelByMaker(this, '/ajax/maker_to_model_upper', '#model');"
                                                        class="form-select make required" id="make" required>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="vehicleType">Model*:</label>
                                                <select name="model" class="form-control required" id="modelNo" required
                                                        disabled>
                                                    <option>Select Model</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group ">
                                                <label for="ownerName">Model Code:</label>
                                                <input name="model_code" type="text" class="form-control required"
                                                       id="ownerName" placeholder="Enter owner name" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="ownerAddress">Engine No*:</label>
                                                <input name="engineNo" type="text" class="form-control required"
                                                       id="ownerAddress" placeholder="2AX-XXXXXXXXX" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group ">
                                                <label for="ownerAddress">Chassis No*:</label>
                                                <input name="chassisNo" type="text" class="form-control required"
                                                       id="ownerAddress" placeholder="SVXX-XXXXXXX" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group ">
                                                <label for="vehicleType">Color:</label>
                                                <select name="vehicleColor" class="form-control make" id="color">
                                                    <option value="black">Black</option>
                                                    <option value="red">Red</option>
                                                    <option value="blue">Blue</option>
                                                    <option value="white">White</option>
                                                    <option value="gray">Gray</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group ">

                                                <label class="test">Branded:</label>

                                                <label class="inline-check">
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" name="isBranded" value="yes">
                                                        <label for="poolVariance-yes">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" name="isBranded" value="no">
                                                        <label for="poolVariance-no">No</label>
                                                    </div>
                                                </label>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group ">
                                                <label for="transmission">Transmission:</label>
                                                <select name="transmission" class="form-control make" id="transmission">
                                                    <option>Select transmission</option>
                                                    <option value="automatic">Automatic</option>
                                                    <option value="manual">Manual</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4"></div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="ownerAddress">Current Odometer:</label>
                                                <input name="odometer" type="text" class="form-control"
                                                       id="ownerAddress" placeholder="Current Odometer" required>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="list-inline pull-right">
                                        <li>
                                            <button type="button" class="btn btn-success btn-sm next-step">
                                                Continue to next step
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-pane step" role="tabpanel" id="step2">
                                    <h4 class="text-center">Assignment Details</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                <label for="vehicleType">Directorate*:</label>
                                                <select name="directorate" class="form-control make" id="directorate"
                                                        required>

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                <label for="vehicleType">Business Unit*:</label>
                                                <select name="businessUnit" class="form-control make" id="businessUnit"
                                                        required>

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                <label for="vehicleType">Cost Center*:</label>
                                                <select name="costCenter" class="form-control make" id="costCenter"
                                                        required>

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12 options">
                                            <p class="test">Pool Vehicle: </p>
                                            <div class="options-inner">
                                                <input type="radio" id="poolVehicle-yes" name="options"
                                                       value="poolVehicle-yes">
                                                <label for="poolVehicle-yes">Yes</label>
                                            </div>
                                            <div class="options-inner">
                                                <input type="radio" id="poolVehicle-no" name="options"
                                                       value="poolVehicle-no">
                                                <label for="poolVehicle-no">No</label>
                                            </div>

                                        </div>


                                        <div class="col-md-6 workWhenChecked" id="responsibleUserNumber">
                                            <div class="form-group">
                                                <label for="ownerAddress">Responsible User:</label>
                                                <input name="responsible_userNumber" type="text" class="form-control"
                                                       id="ownerAddress" placeholder="Staff Number" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6 workWhenChecked" id="responsibleUserName">
                                            <div class="form-group ">
                                                <label> `</label>
                                                <input name="responsible_userName" type="text" class="form-control"
                                                       id="ownerAddress" placeholder="Staff Name" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6 workWhenChecked" id="supervisorNumber">
                                            <div class="form-group">
                                                <label for="ownerAddress">Supervisor:</label>
                                                <input name="supervisor_" type="text" class="form-control"
                                                       id="ownerAddress" placeholder="Number" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6 workWhenChecked" id="supervisorName">
                                            <div class="form-group ">
                                                <label> `</label>
                                                <input name="supervisor_" type="text" class="form-control"
                                                       id="ownerAddress" placeholder="Name" required>
                                            </div>
                                        </div>


                                        <div class="col-md-6 workWhenChecked" id="operatorNumber">
                                            <div class="form-group">
                                                <label for="ownerAddress">Operator:</label>
                                                <input name="operator_address" type="text" class="form-control"
                                                       id="ownerAddress" placeholder="Number" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6 workWhenChecked" id="operatorName">
                                            <div class="form-group ">
                                                <label></label>
                                                <input name="operator_address" type="text" class="form-control"
                                                       id="ownerAddress" placeholder="Name" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6 workWhenChecked" id="assignedToNumber">
                                            <div class="form-group">
                                                <label for="ownerAddress">Assigned To:</label>
                                                <input name="operator_address" type="text" class="form-control"
                                                       id="ownerAddress" placeholder="Number" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6 workWhenChecked" id="assignedToName">
                                            <div class="form-group ">
                                                <label> `</label>
                                                <input name="operator_address" type="text" class="form-control"
                                                       id="ownerAddress" placeholder="Name" required>
                                            </div>
                                        </div>


                                        <div class="col-md-12 condition">
                                            <p class="test">Remarks On Current Condition: </p>
                                            <textarea class="textarea" cols="80" rows="5"></textarea>
                                        </div>
                                        <div class="col-md-12 options  mobile">
                                            <p class="test">Mobile: </p>
                                            <div class="options-inner">
                                                <input type="radio" id="poolVariance-yes" name="options"
                                                       value="poolVariance-yes">
                                                <label for="poolVariance-yes">Yes</label>
                                            </div>
                                            <div class="options-inner">
                                                <input type="radio" id="poolVariance-no" name="options"
                                                       value="poolVariance-no">
                                                <label for="poolVariance-no">No</label>
                                            </div>

                                        </div>

                                    </div>


                                    <ul class="list-inline pull-right">
                                        <li>
                                            <button type="button" class="btn btn-success btn-sm prev-step">Previous
                                            </button>
                                        </li>
                                        {{--<li>
                                                <button type="button" class="btn btn-success next-step skip-btn">Skip</button>
                                            </li>--}}
                                        <li>
                                            <button type="button" class="btn btn-success btn-sm next-step">Continue
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-pane step" role="tabpanel" id="step3">
                                    <h4 class="text-center">Vehicle Images</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Front</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="customFile">
                                                    <label class="custom-file-label" for="customFile">Select
                                                        file</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Rear</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="customFile">
                                                    <label class="custom-file-label" for="customFile">Select
                                                        file</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Right</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="customFile">
                                                    <label class="custom-file-label" for="customFile">Select
                                                        file</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Left</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="customFile">
                                                    <label class="custom-file-label" for="customFile">Select
                                                        file</label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <ul class="list-inline pull-right">
                                        <li>
                                            <button type="button" class="default-btn prev-step">Previous</button>
                                        </li>
                                        <li>
                                            <button type="button" class="default-btn next-step skip-btn">Skip</button>
                                        </li>
                                        <li>
                                            <button type="button" class="default-btn next-step">Finish</button>
                                        </li>
                                    </ul>
                                </div>


                            </div>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
@push('scripts')
    {{-- <script src="{{ asset('assets/js/migration/index2.js') }}"></script> --}}
    <script>
        (function (tmsApp, $) {

            setTimeout(function(){
                if(document.querySelector('#registrationNumber').value > ""){
                    document.querySelector("#vehicleSearchBtn").trigger('click');
                }
            }, 300);

            $("#vehicleSearchBtn").on('click', function () {
                let registrationNumber = document.querySelector('#registrationNumber').value;
                let formData = new FormData();
                formData.append('reg_num', registrationNumber);
                tmsApp.asyncPostFormData(
                    $('#registrationNumber').attr('data-action') + '?vehicle_cleanup=true',
                    formData,
                    function (response_data) {
                        if (response_data.success === 'true' || response_data.success === true) {
                            //populateVehicleDetails(response_data.payload);
                        } else {
                            //removeSubmissionAndDetailsOptions();
                            let $message = response_data['message'] ? response_data['message'] : ' No Vehicle Found, Check your input and try again';
                            tmsApp.systemError('Vehicle', $message);
                        }
                    },
                    function (xhr) {
                        tmsApp.systemError('System Message', 'We could not complete processing your request, please try again later');
                    }
                )
            });

            const make = document.getElementById("make");
            const model = document.getElementById("modelNo");

            //////////////////////////////////////////////  Station Details ////////////////////////////////////////////////

            const options = {
                'Select Make': []
                , 'Benz': [
                    'C200'
                    , 'E300'
                    , '5921'
                ]
                , 'Toyota': [
                    '1652'
                    , '65372'
                    , 'gdha'
                ]
                , 'Honda': [
                    '6821'
                    , '6371'
                    , 'gajd'
                ]
                , 'Kaya': [
                    'Yellow'
                ]
            };

            // Populate make dropdown
            Object.keys(options).forEach((key) => {
                const option = document.createElement('option');
                option.value = key;
                option.textContent = key;
                make.appendChild(option);
            });

            make.addEventListener("change", () => {
                model.removeAttribute("disabled")
                const selectedMake = make.value;
                const modelOptions = options[selectedMake];

                // Clear previous model options
                model.innerHTML = "";

                // Add new model options
                modelOptions.forEach((modelOption) => {
                    const option = document.createElement('option');
                    option.value = modelOption;
                    option.textContent = modelOption;
                    model.appendChild(option);
                });

                if (selectedMake === "Select Make") {
                    const sub = document.createElement('option')
                    sub.value = "Select Model No"
                    sub.textContent = "Select Model"
                    model.appendChild(sub)
                    model.setAttribute("disabled", true)
                }


            });

            //////////////////////////////////////////////  Station Details //////////////////////////////////////////////////////
            //////////////////////////////////////////////  Assignment Details  //////////////////////////////////////////////////


            const dummyOptions = ["One", "Two", "Three"]

            const optionCreation = (options, name) => {

                const optionElement = document.getElementById(name)

                options.forEach((item) => {
                    const option = document.createElement("option")
                    option.value = item
                    option.textContent = item;
                    optionElement.appendChild(option)
                })
            }

            optionCreation(dummyOptions, "businessUnit");
            optionCreation(dummyOptions, "costCenter");
            optionCreation(dummyOptions, "directorate");


            //////////////////////////////////////////////  Assignment Details  //////////////////////////////////////////////////
            //////////////////////////////////////////////  Vehicle Images  //////////////////////////////////////////////////////
            //////////////////////////////////////////////  Vehicle Images  //////////////////////////////////////////////////////

            const loadModelByMaker = (el, url, type) => {
                // console.log(el.value)
                // console.log(url)
                // console.log(type)
            }


            ////////////////////////////////////////////// Create Items //////////////////////////////////////////////////////////

            const getVehicleRadioYes = document.getElementById("poolVehicle-yes")
            const getVehicleRadioNo = document.getElementById("poolVehicle-no")
            const responsibleUserName = document.getElementById("responsibleUserName")
            const responsibleUserNumber = document.getElementById("responsibleUserNumber")
            const operatorName = document.getElementById("operatorName")
            const operatorNumber = document.getElementById("operatorNumber")
            const supervisorName = document.getElementById("supervisorName")
            const supervisorNumber = document.getElementById("supervisorNumber")

            const assignedToName = document.getElementById("assignedToName")
            const assignedToNumber = document.getElementById("assignedToNumber")


            getVehicleRadioYes.addEventListener("change", function () {
                if (getVehicleRadioYes.checked) {
                    responsibleUserName.style.display = "block"
                    responsibleUserNumber.style.display = "block"
                    operatorName.style.display = "block"
                    operatorNumber.style.display = "block"
                    supervisorName.style.display = "block"
                    supervisorNumber.style.display = "block"
                }
            })

            getVehicleRadioNo.addEventListener("change", function () {
                if (getVehicleRadioNo.checked) {

                    responsibleUserName.style.display = "none"
                    responsibleUserNumber.style.display = "none"
                    operatorName.style.display = "none"
                    operatorNumber.style.display = "none"
                    supervisorName.style.display = "none"
                    supervisorNumber.style.display = "none"

                    assignedToName.style.display = "block"
                    assignedToNumber.style.display = "block"
                }

            });


            'use strict';
            let currentIndex = 0;
            let numSteps = 0;
            let tryValid;


            $(document).ready(function () {
                initFormWizard()
            });

            function initFormWizard() {
                const stepsList = document.getElementsByClassName("steps")[0];
                numSteps = stepsList.children.length;

                $('.nav-tabs > li a[title]').tooltip();

                //Wizard
                $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

                    var target = $(e.target);

                    if (target.parent().hasClass('disabled')) {
                        return false;
                    }
                });

                $(".next-step").on('click', function (e) {
                    let active = $('.wizard .nav-tabs li.active');
                    let indexOfActiveElement = 0;

                    $.each(stepsList.children, function (index, li) {
                        if ($(li).hasClass('active')) {
                            indexOfActiveElement = index;
                        }
                    })

                    console.log('Current tab ', indexOfActiveElement);
                    currentIndex = indexOfActiveElement + 1;

                    if (indexOfActiveElement) {
                    }

                    active.next().removeClass('disabled');
                    active.addClass('done');
                    active.removeClass('active');
                    nextTab(active);
                });
                $(".prev-step").click(function (e) {

                    var active = $('.wizard .nav-tabs li.active');
                    prevTab(active);

                });
            }


            function nextTab(elem) {
                let isValid = false;
                if (true) {
                    $(elem).next().find('a[data-toggle="tab"]').click();
                }


                if (currentIndex == numSteps - 1) {
                    $('.skip-btn').addClass('d-none')
                } else {
                    $('.skip-btn').removeClass('d-none')
                }


            }

            function prevTab(elem) {
                $(elem).prev().find('a[data-toggle="tab"]').click();
                const stepsList = document.getElementsByClassName("steps")[0];
                let numSteps = stepsList.children.length;

                if (currentIndex > 0 || currentIndex < numSteps - 1) {
                    $('.skip-btn').removeClass('d-none')
                }
            }

            $('.nav-tabs').on('click', 'li', function () {
                $('.nav-tabs li.active').removeClass('active');
                $(this).addClass('active');
            });


            function validateStep(stepIndex) {
                let isValid;
                // const requiredValue = document.querySelectorAll("input, textarea")
                const steps = document.querySelectorAll(".step")


                return isValid
            }

            validateStep(1)

            //////// Try Functions ///////////

            function doSomething() {
                tryValid = validateStep(1)
            }
        })(window.tmsApp || {}, jQuery);

    </script>
@endpush

