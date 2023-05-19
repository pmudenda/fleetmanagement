@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@push('styles')
    <link href="{{asset("assets/plugins/select2/css/select2.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css")}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset("libs/steps/jquery-steps.css")}}" rel="stylesheet" type="text/css"/>
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
                        <div class="container-fluid mt-2">
                            <div class="row">
                                <div class="col-9">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 app-field-label field-required"
                                                            for="staff_no">Registration #:
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                                            <div class="input-group">
                                                                <input type="text"
                                                                       data-action="{{route('requisition.vehicle.details')}}"
                                                                       class="form-control form-control-sm"
                                                                       autocapitalize="characters"
                                                                       id="vehicle_registration"
                                                                       placeholder="Vehicle Reg e.g AAB 6757"
                                                                       name="vehicle_registration" required>
                                                                <div class="input-group-addon">
                                                                    <button type="button" id="vehicleSearchBtn"
                                                                            name="vehicleSearchBtn"
                                                                            class="btn btn-success btn-sm border-radius-0">
                                                                        <i class="fas fa-search"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{--<div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                                            <input type="text"
                                                                   class="form-control form-control-sm"
                                                                   id="vehicle_description"
                                                                   name="vehicle_description"
                                                                   required readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>--}}

                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                        class="col-xs-12 col-sm-6 col-md-5 col-lg-4 app-field-label field-required"
                                                        for="staff_no">Date In :
                                                    </label>
                                                        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                                            <input type="text"
                                                                   class="form-control form-control-sm"
                                                                   id="date_of_req"
                                                                   readonly
                                                                   value="{{ Carbon::now()->format('d/m/Y') }}"
                                                                   name="date_of_req"
                                                                   required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <div
                                                            class=" col-xs-12 col-sm-6 col-md-5 col-lg-4 control-input-wrapper">
                                                            <div class="control-input">
                                                                <div class="link-field ui-front"
                                                                     style="position: relative;">
                                                                    <label class="form-check-inline field-required">
                                                                        Workshop
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                                            <select
                                                                required
                                                                class="form-select form-select-sm"
                                                                name="workshop"
                                                                autocomplete="off"
                                                                id="workshop">
                                                                <option></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-7 col-lg-4"
                                                            for="job_card_no">
                                                            Time In:
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                                            <input type="text"
                                                                   min="1"
                                                                   readonly
                                                                   value="{{ Carbon::now()->format('H:i:s') }}"
                                                                   class="form-control form-control-sm when_valid number_input"
                                                                   id="timeIn"
                                                                   name="timeIn"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="staff_name">
                                                            Repair Type:
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                                            <select name="repairType"
                                                                    id="repairTypeDropdownList"
                                                                    class="form-select form-select-sm when_valid"
                                                                    required>
                                                                <option value=""> --Select--</option>
                                                                @foreach ($repairTypes as $repairType)
                                                                    <option
                                                                        value="{{$repairType->code}}">{{$repairType->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="staff_name">
                                                            Service Advisor:
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                                            <input type="text"
                                                                   readonly
                                                                   data-url="{{route('fuel.odometer.validation')}}"
                                                                   data-validation-method="fuelRequisitionOdometerReading"
                                                                   data-params="[odometerNumber, vehicleRegistration]"
                                                                   class="form-control form-control-sm when_valid number_input"
                                                                   id="odometer_reading"
                                                                   value="{{ auth()->user()->name }} | RECEPTION"
                                                                   disabled
                                                                   required
                                                                   readonly
                                                                   name="service_advisor"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="accidentRecordNo" class="row d-none">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="staff_name">
                                                            Accident No:
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                                            <select name="requisition_type" id="requisition_type"
                                                                    class="form-control form-select-sm when_valid"
                                                                    required>
                                                                <option value=""> --Select--</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                                            for="request_date">Odometer:</label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   id="request_date"name="request_date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="container-fluid pl-0">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xs-12 col-sm-12 col-md-5 col-lg-4 field-required"
                                                            for="next_fuel_date">
                                                            Fuel Level :
                                                        </label>
                                                        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                                            <select name="requisition_type" id="requisition_type"
                                                                    disabled
                                                                    class="form-control form-select-sm when_valid"
                                                                    required>
                                                                <option value=""> --Select--</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div id="vehicleDetailsContainer" style="display: none;"
                                         class="col-xs-12 col-sm-12 col-md-12 pl-0">
                                        <h1>Vehicle Details</h1>
                                        <table class="table table-striped">
                                            <tbody id="vehicleDetails" class="vehicleDetails">
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="image_view" class="card text-center py-5 my-2" style="display: none;">
                                        <h2 class="fs-2x fw-bold mb-10">Front View</h2>
                                        <div class="form-group">
                                            <div class="imagePreview"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <h1>Accessories Checkin & Movement</h1>
                    <div>
                        <div class="container-fluid mt-5">
                            <div class="row">
                                <div class="col-xs-12 col-sm-9 col-md-8">
                               {{--     <form id="tms_accessories_form"
                                          name="tms_accessories_form"
                                          class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                          action="{{route('vehicle.accessories.save')}}">--}}
                                    {{--<div class="d-flex justify-content-end">
                                        {{-- class="create_mode">
                                            <button type="submit" id="saveVehicleAccessories"
                                                    class="btn btn-success btn-sm">
                                                <i class="fas fa-paper-plane"></i>
                                                <span class="indicator-label">
                                                    Save
                                                </span>
                                                span class="indicator-progress">
                                                    Please wait...
                                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>--}}
                                        <div class="container-fluid mt-5">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <div class="row">

                                                        <div class="col">
                                                            <table class="table table-row-dashed align-middle gs-0 table-bordered">
                                                                <thead>
                                                                <tr class="bg-dark">
                                                                    <th class="pl-2">Item</th>
                                                                    <th>Present</th>
                                                                    <th class="pr-2">Not Present</th>
                                                                    <th class="pr-2">Remarks</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($accessories as $key => $accessory)
                                                                    @if(($key%2) == 0)
                                                                        <tr>
                                                                            <td class="pl-2" style="width: 35%;">{{$accessory->name}}</td>
                                                                            <td><input type="radio" value="YES" required name="{{str_replace(' ','', $accessory->code)}}"></td>
                                                                            <td><input type="radio" value="NO" required name="{{str_replace(' ','', $accessory->code)}}"></td>
                                                                            <td style="width: 45%;">
                                                                                <input typeof="text" name="COMMENT_{{str_replace(' ','', $accessory->code)}}"
                                                                                       class="form-control form-control-sm"/>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col">
                                                            <table class="table table-row-dashed align-middle gs-0 table-bordered">
                                                                <thead>
                                                                <tr class="bg-dark">
                                                                    <th class="pl-2">Item</th>
                                                                    <th>Present</th>
                                                                    <th class="pr-2">Not Present</th>
                                                                    <th class="pr-2">Remarks</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($accessories as $key => $accessory)
                                                                    @if(($key%2) != 0)
                                                                        <tr>
                                                                            <td class="pl-2" style="width: 35%;">
                                                                                {{$accessory->name}}
                                                                            </td>
                                                                            <td><input type="radio" required value="YES" name="{{str_replace(' ','', $accessory->code)}}"></td>
                                                                            <td><input type="radio" required value="NO" name="{{str_replace(' ','', $accessory->code)}}"></td>
                                                                            <td style="width: 45%;">
                                                                                <input typeof="text" name="COMMENT_{{str_replace(' ','', $accessory->code)}}"
                                                                                       class="form-control form-control-sm">
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                @endforeach

                                                                </tbody>
                                                            </table>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
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
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset("libs/steps/jquery.steps.js")}}"></script>
    <script>
        (function (tmsApp, $) {

            function initializeFormWizard() {
                let form = $('#jobCardForm');
                // formWizard.on("click", function (e) {
                //     e.stopPropagation();
                //     $(this).remove();

                //let jobCardForm = formWizard.show();

                function postData() {
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
                        return true ;//form.valid();
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
                    onFinished: function () {
                        postData.call(this);
                    },

                }).validate({
                    errorPlacement: function errorPlacement(error, element) {
                        error.insertAfter(element);
                    },
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

                        let locations = response['payload'];
                        tmsApp.populateDropDownList(selectElem, locations, "location", ["location"], "");

                        let location = selectElem.attr('data-value');
                        console.log(location);
                        if (location) {
                            selectElem.val(location);
                            selectElem.trigger('change');
                        }

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
                if(this.value === '001'){
                    document.querySelector("#accidentRecordNo").classList.remove('d-none');
                }else{
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
        })(window.tmsApp || {}, jQuery)
    </script>
    <script src="{{asset('assets/js/system/project_code.js').'?v='.Carbon::now()->format('his')}}"></script>
@endpush
