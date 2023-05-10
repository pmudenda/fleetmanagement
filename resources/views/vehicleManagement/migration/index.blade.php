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
                                        <span class="round-tab">1</span>
                                        <i>Vehicle Details</i>
                                    </a>
                                </li>
                                <li role="presentation" data-index="1" class="disabled st2">
                                    <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab"
                                       aria-expanded="false">
                                        <span class="round-tab">2</span>
                                        <i>Step 2</i>
                                    </a>
                                </li>
                                <li role="presentation" data-index="2" class="disabled st3">
                                    <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab">
                                        <span class="round-tab">3</span>
                                        <i>Step 3</i>
                                    </a>
                                </li>

                            </ul>
                        </div>

                        <form role="form" method="post" class="">
                            @csrf
                            <div class="tab-content px-5" id="main_form">
                                <div class="tab-pane active step" role="tabpanel" id="step1">
                                    <h3 class="text-center">Vehicle Details</h3>
                                    <div class="row px-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vehicleNumber">Registration No*:</label>
                                                <input name="regNo" type="text" class="form-control required"
                                                       id="vehicleNumber" placeholder="BAC 1111" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                <label for="vehicleType">Make*:</label>
                                                <select name="make"
                                                        onchange="loadModelByMaker(this, '/ajax/maker_to_model_upper', '#model');"
                                                        class="form-control make  required" id="make" required></select>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vehicleType">Model*:</label>
                                                <select name="model" class="form-control required" id="modelNo" required
                                                        disabled>
                                                    <option>Select Model</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                <label for="ownerName">Model Code:</label>
                                                <input name="model_code" type="text" class="form-control required"
                                                       id="ownerName" placeholder="Enter owner name" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="ownerAddress">Engine No*:</label>
                                                <input name="engineNo" type="text" class="form-control required"
                                                       id="ownerAddress" placeholder="2AX-XXXXXXXXX" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                <label for="ownerAddress">Chassis No*:</label>
                                                <input name="chassisNo" type="text" class="form-control required"
                                                       id="ownerAddress" placeholder="SVXX-XXXXXXX" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                <label for="vehicleType">Color:</label>
                                                <select name="costCenter" class="form-control make" id="color">
                                                    <option>Select Color</option>
                                                    <option value="red">Red</option>
                                                    <option value="blue">Blue</option>

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                <label for="transmission">Transmission:</label>
                                                <select name="transmission" class="form-control make" id="transmission">
                                                    <option>Select transmission</option>
                                                    <option value="automatic">Automatic</option>
                                                    <option value="manual">Manual</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6 options branded-options">
                                            <p class="test">Branded: </p>
                                            <div class="options-inner branded-options-inner">
                                                <input type="radio" id="poolVariance-yes" name="options"
                                                       value="poolVariance-yes">
                                                <label for="poolVariance-yes">Yes</label>
                                            </div>
                                            <div class="options-inner branded-options-inner">
                                                <input type="radio" id="poolVariance-no" name="options"
                                                       value="poolVariance-no">
                                                <label for="poolVariance-no">No</label>
                                            </div>

                                        </div>

                                        <div class="col-md-6">
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
                                                <label> `</label>
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
                                            <button type="button" class="btn btn-success btn-sm prev-step">Back</button>
                                        </li>
                                        {{--<li>
                                            <button type="button" class="btn btn-success next-step skip-btn">Skip</button>
                                        </li>--}}
                                        <li>
                                            <button type="button" class="btn btn-success btn-sm next-step">Continue</button>
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
                                            <button type="button" class="default-btn prev-step">Back</button>
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
    <script src="{{ asset('assets/js/migration/index2.js') }}"></script>
    <script src="{{ asset('assets/js/migration/index.js') }}"></script>
@endpush
