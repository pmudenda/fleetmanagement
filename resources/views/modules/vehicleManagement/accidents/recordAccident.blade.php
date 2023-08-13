@extends('layouts.app')
@push('styles')
    <style>
        .error {
            color: red;
        }
    </style>
@endpush
@section('content')

    <div class="container">

        <form name="saveRecord"
              id="my-form"
              class="form-wrapper"
              action="{{route('accident.record')}}"
              method="POST">
            @csrf
            <h3 class="step-top step1-top">Vehicle Details</h3>
            <section class="section first-section mx-auto">
                <h2>Vehicle Details</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="registrationNo">Registration No*:</label>
                            <div class="regInput">
                                <input name="registrationNo" type="text"
                                       class="form-control required"
                                       id="registrationNo"
                                       placeholder=""
                                       required>
                            </div>

                            @error('registrationNo')
                            <p>{{$message}}</p>
                            @enderror


                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group ">
                            <label for="modelNo">Model*:</label>
                            <input name="modelNo" type="text" class="form-control disableVehicle" id="modelNo"
                                   placeholder="Enter Model Number" required>
                            @error('modelNo')
                            <p>{{$message}}</p>

                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="vehicleMake">Make:</label>
                            <input name="vehicleMake" type="text" class="form-control disableVehicle" id="vehicleMake"
                                   placeholder="Enter Vehicle Make" required>
                            @error('vehicleMake')
                            <p>{{$message}}</p>

                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group ">
                            <label for="chassisNo">Chassis No*:</label>
                            <input name="chassisNo" type="text" class="form-control disableVehicle" id="chassisNo"
                                   placeholder="Enter Chassis No" required>
                            @error('chassisNo')
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
                                <option value="one">One</option>
                                <option value="two">two</option>
                                <option value="three">two</option>
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
                            <option value="one">One</option>
                            <option value="two">two</option>
                            <option value="three">two</option>
                        </select>
                        @error('accidentNature')
                        <p>{{$message}}</p>

                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="peopleInvolved">Number of people involved:</label>
                            <input name="peopleInvolved" type="number" class="form-control required" id="peopleInvolved"
                                   placeholder="Enter Number of people Involved" required>
                        </div>
                        @error('peopleInvolved')
                        <p>{{$message}}</p>

                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-group ">
                            <label for="date">Date*:</label>
                            <input name="date" type="date" class="form-control required" id="date"
                                   placeholder="00/00/0000" required>
                        </div>
                        @error('date')
                        <p>{{$message}}</p>

                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-group ">
                            <label for="time">Time*:</label>
                            <input name="time" type="time" class="form-control required" id="time" placeholder="00:00"
                                   required>
                        </div>
                        @error('time')
                        <p>{{$message}}</p>

                        @enderror
                    </div>

                    <div class="col-md-12">
                        <div class="form-group ">
                            <label for="accidentDescription">Description Of Accident:</label>
                            <textarea class="form-control" id="description" name="description" rows="5"
                                      cols="20"></textarea>
                            @error('description')
                            <p>{{$message}}</p>

                            @enderror

                        </div>
                    </div>

                    <div class="col-md-6 options policeNotification">
                        <p class="test">Police Notified: </p>
                        <div class="options-inner policeNotification-options-inner">
                            <input type="radio" id="policeNotification-yes" name="policeNotified" value="yes">
                            <label for="policeNotification-yes">Yes</label>
                        </div>
                        <div class="options-inner policeNotification-options-inner">
                            <input type="radio" id="policeNotification-no" name="policeNotified" value="no">
                            <label for="policeNotification-no">No</label>
                        </div>
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
                                <input name="staffNumber"
                                       type="text"
                                       class="form-control required"
                                       id="staffNo"
                                       placeholder="Enter Staff Number"
                                       required>
                            </div>
                        </div>
                        @error('staffNumber')
                        <p>{{$message}}</p>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-group ">
                            <label for="driverName">Name:</label>
                            <input name="driverName" type="text" class="form-control required" id="driverName"
                                   placeholder="Enter Driver Name">
                        </div>
                        @error('driverName')
                        <p>{{$message}}</p>

                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="driverEmail">Email</label>
                            <input name="driverEmail" type="text" class="form-control required" id="driverEmail"
                                   placeholder="Enter Driver Email" required>
                        </div>
                        @error('driverEmail')
                        <p>{{$message}}</p>

                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-group ">
                            <label for="phoneNo">Phone No:</label>
                            <input name="phoneNo" type="text" class="form-control required" id="phoneNo"
                                   placeholder="Enter Phone No" required>
                        </div>
                        @error('phoneNo')
                        <p>{{$message}}</p>

                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="driverAge">Age*:</label>
                            <input name="age" type="text" class="form-control required" id="driverAge"
                                   placeholder="Enter Driver Age" required>
                        </div>
                        @error('driverAge')
                        <p>{{$message}}</p>

                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="driverPosition">Position*:</label>
                            <input name="driverPosition" type="text" class="form-control required" id="driverPosition"
                                   placeholder="Enter Driver Position" required>
                        </div>
                    </div>
                    @error('driverPosition')
                    <p>{{$message}}</p>

                    @enderror

                </div>
            </section>

        </form>
    </div>
@endsection
@push('scripts')
    <script src="{{asset("libs/steps/jquery.steps.min.js")}}"></script>
    <script src="{{asset("application/modules/vehicleManagement/accidentRecording.formWizard.js")}}"></script>
@endpush
