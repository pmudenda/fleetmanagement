@extends('layouts.app')
@push('styles')
    <link href="{{asset("libs/steps/jquery-steps.css")}}" rel="stylesheet" type="text/css"/>

    <style>
        .error{
            color: red;
        }
        .nav-tabs .nav-link.active, .nav-tabs .nav-item.show .nav-link {
            border-color: orange;
        }

        .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link {
            color: #495057;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
        }
    </style>
@endpush
@section('content')
    <x-content-header
            :activeCrumb="'New Accident'"
            :linkText="'Report'"
            :pageTitle="'Accident Reporting'"/>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h4>Accident Record</h4>
                  {{--  @if(!empty($details) && !empty($details->job_card_no))
                        <span class="ml-2 indicator-pill whitespace-nowrap green">
                            <span>Saved</span>
                        </span>
                    @else
                        <span class="ml-2 indicator-pill whitespace-nowrap orange"><span>Not Saved</span></span>
                    @endif--}}
                </div>
        {{--        @if(!empty($details) && !empty($details->job_card_no))
                    <div class="card-toolbar justify-content-end">
                        JOB CARD NUMBER: <span class="text-orange">{{ $details->job_card_no ?? '' }}</span>
                    </div>
                @endif
--}}
            </div>

            <div class="card-body pb-4 min-h-600px pt-0">
                <x-error-view/>
                <label class="app-required-marker"></label>
                <form name="saveRecord" id="my-form" class="form-wrapper" action="" method="POST">
                    @csrf
                    <h3 class="step-top step1-top">Vehicle Details</h3>
                    <section class="section first-section mx-auto">
                        <h2>Vehicle Details</h2>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="registrationNo">Registration No*:</label>
                                    <div class="input-group">
                                        <input name="registrationNo"
                                               type="text"
                                               value="{{$registration ?? ''}}"
                                               data-action="{{route('cleanup.vehicle.find')}}"
                                               class="form-control form-control-sm required"
                                               id="registrationNo"
                                               placeholder=""
                                               required/>
                                        <div class="input-group-addon">
                                            <button type="button"
                                                    title="Search Vehicle Button"
                                                    id="vehicleSearchBtn"
                                                    name="vehicleSearchBtn"
                                                    class="btn btn-success btn-sm border-radius-0">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>

                                    @error('registrationNo')
                                    <p>{{$message}}</p>
                                    @enderror


                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label for="modelNo">Model*:</label>
                                    <input  name="modelNo" type="text" class="form-control disableVehicle" id="modelNo" placeholder="Enter Model Number" required>
                                    @error('modelNo')
                                    <p>{{$message}}</p>

                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicleMake">Make:</label>
                                    <input name="vehicleMake" type="text" class="form-control disableVehicle" id="vehicleMake" placeholder="Enter Vehicle Make" required>
                                    @error('vehicleMake')
                                    <p>{{$message}}</p>

                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label for="model_name">Chassis No*:</label>
                                    <input name="model_name" type="text" class="form-control disableVehicle" id="model_name" placeholder="Enter Model Name" required>
                                    @error('model_name')
                                    <p>{{$message}}</p>

                                    @enderror
                                </div>
                            </div>


                        </div>
                    </section>

                    <h3 class="step-top step2-top">Accident Details</h3>
                    <section class="second-section">
                        <h2>Accident Details</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ownerAddress">Type of Accident*:</label>
                                    <select id="accidentType" name="accidentType" class="form-control required">
                                        <option value="none">Select Incident type</option>
                                    </select>
                                    @error('accidentType')
                                    <p>{{$message}}</p>

                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="ownerAddress">Nature of accident*:</label>
                                <select id="accidentNature" name="accidentNature" class="form-control required">
                                    <option value="none">Select Incident Nature</option>
                                </select>
                                @error('accidentNature')
                                <p>{{$message}}</p>

                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="peopleInvolved">Number of people involved:</label>
                                    <input name="peopleInvolved" type="number" class="form-control required" id="peopleInvolved" placeholder="Enter Number of people Involved" required>
                                </div>
                                @error('peopleInvolved')
                                <p>{{$message}}</p>

                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label for="date">Date*:</label>
                                    <div class="input-group">
                                        <input name="date" type="date" class="form-control required" onkeydown="return false" id="accident-date"
                                               placeholder="00/00/0000"
                                               max="{{date('Y-m-d', strtotime( \Carbon\Carbon::now()))}}"
                                               min="{{date('Y-m-d', strtotime($minDate))}}"
                                               required>
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                                @error('date')
                                <p>{{$message}}</p>

                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label for="time">Time*:</label>
                                    <input name="time" type="time" class="form-control required" id="time" placeholder="00:00" required>
                                </div>
                                @error('time')
                                <p>{{$message}}</p>

                                @enderror
                            </div>

                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label for="accidentDescription">Description Of Accident:</label>
                                    <textarea  class="form-control" id="description" name="description" rows="5" cols="20"></textarea>
                                    @error('description')
                                    <p>{{$message}}</p>

                                    @enderror

                                </div>
                            </div>

                            <div class="col-md-6 options policeNotification">
                                <p class="test">Police Notified: </p>
                                <label class="checkbox-inline mr-5">
                                    <input type="radio" id="policeNotification-yes" name="policeNotified" value="yes" >
                                    <label for="policeNotification-yes">Yes</label>
                                </label>
                                <label class="checkbox-inline ml-2">
                                    <input type="radio" id="policeNotification-no" name="policeNotified" value="no" >
                                    <label for="policeNotification-no">No</label>
                                </label>
                                @error('policeNotified')
                                <p>{{$message}}</p>

                                @enderror

                            </div>





                        </div>
                    </section>

                    <h3 class="step-top step3-top">Driver Details</h3>
                    <section class="third-section">
                        <h2>Driver Details</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="staffNo">Staff Number:</label>
                                    <div class="regInput">
                                        <div class="input-group">
                                            <input type="text"
                                                   list="employee_list"
                                                   data-action="{{route('driver.search')}}"
                                                   class="form-control form-control-sm"
                                                   autocapitalize="characters"
                                                   id="driver_staff_number"
                                                   placeholder=""
                                                   name="driver_staff_number"/>
                                            <div class="input-group-addon">
                                                <button type="button" id="employeeSearchBtn"
                                                        name="employeeSearchBtn"
                                                        class="btn btn-success btn-sm border-radius-0">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                            <datalist id="employee_list">
                                            </datalist>
                                        </div>
                                    </div>
                                </div>
                                @error('staffNumber')
                                <p>{{$message}}</p>

                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label for="driver_name">Name:</label>
                                    <input name="driver_name" type="text"
                                           class="form-control required"
                                           readonly
                                           id="driver_name"
                                           placeholder="" >
                                </div>
                                @error('driverName')
                                <p>{{$message}}</p>

                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="driverEmail">Email</label>
                                    <input name="driverEmail" type="text"
                                           readonly
                                           class="form-control required"
                                           id="driverEmail"
                                           placeholder="Enter Driver Email" required>
                                </div>
                                @error('driverEmail')
                                <p>{{$message}}</p>

                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label for="phoneNo">Phone No:</label>
                                    <input name="phoneNo" type="text"
                                           class="form-control required"
                                           id="phoneNo"
                                           placeholder="Enter Phone No" required>
                                </div>
                                @error('phoneNo')
                                <p>{{$message}}</p>

                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="driverAge">Age*:</label>
                                    <input name="age" type="text"
                                           class="form-control required"
                                           id="driverAge"
                                           placeholder="Enter Driver Age"
                                           required>
                                </div>
                                @error('driverAge')
                                <p>{{$message}}</p>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="driverPosition">Position*:</label>
                                    <input name="driverPosition" type="text"
                                           class="form-control required"
                                           readonly
                                           id="driverPosition"
                                           placeholder="Enter Driver Position"
                                           required>
                                </div>
                            </div>
                            @error('driverPosition')
                            <p>{{$message}}</p>

                            @enderror

                        </div>
                    </section>

                </form>


                <input type="hidden" name="vehicle_details" id="vehicle_details" value="{{route('requisition.vehicle.details')}}">

            </div>
        </div>
        <x-employee-search-modal/>

    </section>
    <input type="hidden" value="{{route('accident.types')}}" id="accident_types_endpoint">
    <input type="hidden" value="{{route('accident.natures')}}" id="accident_natures_endpoint">
    @push('scripts')
        <script src="{{asset('application/modules/userManagement/employee.search.js')}}"></script>
        <script src="{{asset("libs/steps/jquery.steps.min.js")}}"></script>
        <script>
            (function(tmsApp, $){

                $(document).off().on('click', '[data-confirm-selection="true"]', function (event) {

                    console.log(event)
                    // let _modal = $("#searchEmployeeModal");
                    //
                    // const {assignmenttype, inputfield, field} = window.supportData;
                    //
                    // let selectedUser = $("input[name='users[]']:checked");
                    // if (!selectedUser || selectedUser.length === 0) {
                    //     _modal.find(".errorMsg").html('<div class="alert alert-danger">You have not selected any user</div>');
                    //     return;
                    // }
                    // let name = '';
                    // let recordId = '';
                    //
                    // $.each(selectedUser, function (index, element) {
                    //     if (name === '') {
                    //         name += element['dataset']['name']
                    //         recordId += element['dataset']['userid'];
                    //     } else {
                    //         name += ',' + element['dataset']['name']
                    //         recordId += ',' + element['dataset']['userid'];
                    //     }
                    // });
                    //
                    // if (assignmenttype === 'multiple') {
                    //     name += ',' + $('input[name="' + inputfield + '"]').val();
                    //     recordId += ',' + $('input[name="' + inputfield + 'Id"]').val();
                    // }
                    //
                    // $('input[name="' + inputfield + '"]').val(name).trigger('change');
                    // $('input[name="' + inputfield + 'Id"]').val(recordId).trigger('change');
                    // _modal.modal('hide');
                });

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
                    //});
                    // })
                }

                $(function () {
                    initializeFormWizard()
                });

                function fetchVehicleDetails(reg) {
                    $.ajax({
                        url: $('[name="vehicle_details"]').val(),
                        data: {
                            'vehicle_registration': reg
                        },
                        method: 'GET',
                        success: function (response) {
                            // Code to execute when the AJAX request succeeds


                            if (response.success === 'true' || response.success) {
                                const vehicleDetails = response.payload.vehicle;

                                console.log(vehicleDetails)
                                let vehicleModel = document.getElementById("modelNo")
                                let vehicleMake = document.getElementById("vehicleMake")
                                let chassisNo = document.getElementById("model_name")

                                vehicleModel.setAttribute("disabled", true)
                                vehicleMake.setAttribute("disabled", true)
                                chassisNo.setAttribute("disabled", true)


                                vehicleModel.value = vehicleDetails.model_code;
                                vehicleMake.value = vehicleDetails.brand_name
                                chassisNo.value = vehicleDetails.model_name
                            } else {
                                launchErrorModal(response.message, "errorDisplay")
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            // Code to execute when the AJAX request fails
                        }
                    });
                }

                $(document).ready(function () {

                    function getAccidentTypes() {
                        fetch(document.querySelector('#accident_types_endpoint').value)
                            .then(response => response.json())
                            .then(response => {
                                // Populate results
                                let selectElem = $('#accidentType');

                                if (response.state === 'failure') {
                                    //show errors
                                    toastr.error('Connection error, no data found')
                                    return;
                                }


                                let userUnits = response['payload'];


                                window.organizationUnits = userUnits;
                                tmsApp.populateDropDownList(selectElem, userUnits, "code", ['name']);

                                let userUnitId = selectElem.attr('data-value');
                                if (userUnitId) {
                                    selectElem.val(userUnitId);
                                    selectElem.trigger('change');
                                }
                            })
                            .catch(function (error) {
                                // notify of error
                                console.log(error)
                                toastr.error('Connection error. Could not retrieve Organizational units data, some feature might not work.')
                            });
                    }

                    function getAccidentNatures() {
                        fetch(document.querySelector('#accident_natures_endpoint').value)
                            .then(response => response.json())
                            .then(response => {
                                // Populate results
                                let selectElem = $('#accidentNature');

                                if (response.state === 'failure') {
                                    //show errors
                                    toastr.error('Connection error, no data found')
                                    return;
                                }

                                let userUnits = response['payload'];


                                window.organizationUnits = userUnits;
                                tmsApp.populateDropDownList(selectElem, userUnits, "code", ['name']);

                                let userUnitId = selectElem.attr('data-value');
                                if (userUnitId) {
                                    selectElem.val(userUnitId);
                                    selectElem.trigger('change');
                                }
                            })
                            .catch(function (error) {
                                // notify of error
                                console.log(error)
                                toastr.error('Connection error. Could not retrieve Organizational units data, some feature might not work.')
                            });
                    }

                    getAccidentNatures();

                    getAccidentTypes()

                    Inputmask({
                        "mask": "A{2,3} 9{1,4}"
                    }).mask("#registrationNo");

                   // $('.actions li a').addClass('btn btn-success')
                   // $(".steps ul").addClass("d-flex justify-content-center")
                   // $(".actions ul").addClass("d-flex justify-content-end")


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

                    $('#vehicleSearchBtn').on('click enter', function () {
                       const reg = $('#registrationNo').val();
                        if (!reg || reg.replaceAll('_', '').replaceAll(" ",'').length < 4) {
                            return;
                        }

                        fetchVehicleDetails(reg);
                    });

                    $('#registrationNo').on('paste enter', function () {
                        if (!this.value || this.value.replaceAll('_', '').replaceAll(" ",'').length < 4) {
                            return;
                        }

                        const query = $(this).val();
                        fetchVehicleDetails(query);
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

                    $('#staffNo').on('keyup paste enter', function () {

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


                    // $("#staffClear").click(function () {
                    //
                    //     let driverName = document.getElementById("driverName")
                    //     let driverEmail = document.getElementById("driverEmail")
                    //     let driverAge = document.getElementById("driverAge")
                    //     let driverPosition = document.getElementById("driverPosition")
                    //     let phoneNo = document.getElementById("phoneNo")
                    //
                    //     driverName.removeAttribute("disabled")
                    //     driverEmail.removeAttribute("disabled")
                    //     driverAge.removeAttribute("disabled")
                    //     driverPosition.removeAttribute("disabled")
                    //     phoneNo.removeAttribute("disabled")
                    //
                    //
                    //     driverName.value = ""
                    //     driverEmail.value = ""
                    //     driverAge.value = ""
                    //     driverPosition.value = ""
                    //     phoneNo.value = ""
                    // })



                    // Need to create a post request for the data ---> ended here
                    //
                    // Submit form
                    //
                    // $('#my-form').on('submit', function (e) {
                    //     e.preventDefault()
                    //     e.stopPropagation()
                    //
                    //
                    //     let formData = {
                    //         driverName: document.getElementById("driverName"),
                    //         driverEmail: document.getElementById("driverEmail"),
                    //         age: document.getElementById("driverAge"),
                    //         driverPosition: document.getElementById("driverPosition"),
                    //         phoneNo: document.getElementById("phoneNo"),
                    //         vehicleModel: document.getElementById("modelNo"),
                    //         vehicleMake: document.getElementById("vehicleMake"),
                    //         chassisNo: document.getElementById("chassisNo"),
                    //         accidentType: document.getElementById("accidentType"),
                    //         accidentNature: document.getElementById("accidentNature"),
                    //         peopleInvolved: document.getElementById("peopleInvolved"),
                    //         date: document.getElementById("date"),
                    //         time: document.getElementById("time"),
                    //         description: document.getElementById("description"),
                    //         policeNotified: document.getElementById("policeNotified")
                    //     }
                    //
                    //     console.log(document.getElementById("saveRecord").getAttribute())
                    //     $.ajax({
                    //         url: document.getElementById("saveRecord").getAttribute(),
                    //         type: 'POST',
                    //         data: formData,
                    //         headers: {
                    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    //         },
                    //         success: function (response) {
                    //             console.log(response)
                    //
                    //             if (response.status === 'success') {
                    //                 console.log(response)
                    //                 launchErrorModal(response.message, "errorDisplay")
                    //             } else {
                    //                 launchErrorModal(response.message, "errorDisplay")
                    //             }
                    //
                    //         },
                    //         error: function () {
                    //
                    //         },
                    //
                    //     })
                    //
                    // })


                })


            })(window.tmsApp, jQuery);
        </script>
    @endpush

@endsection
