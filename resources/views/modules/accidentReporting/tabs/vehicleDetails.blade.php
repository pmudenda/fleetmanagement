@php use Carbon\Carbon; @endphp
<section class="section first-section mx-auto">
    <div class="row">
        <div style="border-right: 1px solid rgb(128,128,128);" class="col-7">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="container-fluid pl-0">
                        <div class="row">
                            <div class="form-group row">
                                <label
                                    class="col-xs-12 col-sm-6 col-md-5 col-lg-4 field-required"
                                    for="staff_no">Registration #:
                                </label>
                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                    <div class="input-group">
                                        <input name="registrationNo"
                                               type="text"
                                               value="{{$registration ?? ''}}"
                                               data-action=""
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6 d-none">
                    <div class="container-fluid pl-0">
                        <div class="row">
                            <div class="form-group row">
                                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           id="type_brand_model"
                                           readonly
                                           name="type_brand_model"
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
                                <label
                                    class="col-xs-12 col-sm-6
                                                            col-md-5 col-lg-4 field-required"
                                    for="staff_no">
                                    Assigned To :
                                </label>

                                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control form-control-sm"
                                               id="assignedTo"
                                               readonly
                                               value=""
                                               name="assignedTo"
                                               required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="container-fluid pl-0">
                        <div class="row">
                            <div class="form-group row">
                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                    <div class="input-group">
                                        <input type="text"
                                               required
                                               readonly
                                               name="assignedToDescription"
                                               class="form-control form-control-sm"/>
                                    </div>
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
                                    for="mileage">
                                    Odometer :
                                </label>
                                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                    <div class="input-group">
                                        <input name="mileage"
                                               type="text"
                                               class="form-control numberOnly"
                                               id="mileage"
                                               placeholder="Enter Current Odometer Reading"
                                               required>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fa fa-dashboard"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="insured" value="Y"/>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="container-fluid pl-0">
                        <div class="row">
                            <div class="form-group row">
                                <label
                                    class="col-xs-12 col-sm-6 col-md-5
                                                        col-lg-4 field-required"
                                    for="staff_no">Date Reported:
                                </label>
                                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control form-control-sm"
                                               id="date_of_req"
                                               readonly
                                               value="{{ date('Y-m-d', strtotime(Carbon::now()))}}"
                                               name="date_of_req"
                                               required>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
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
                                    class="col-xs-12 col-sm-6 col-md-5 col-lg-4"
                                    for="job_card_no">
                                    Time Reported:
                                </label>
                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                    <div class="input-group">
                                        <input type="text"
                                               readonly
                                               value="{{Carbon::now()->format('H:i:s')}}"
                                               class="form-control
                                                               form-control-sm when_valid number_input"
                                               id="timeIn"
                                               name="timeIn"
                                        />
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-4">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <div id="vehicleDetailsContainer" style="display: none;"
                                 class="col-xs-12 col-sm-12 col-md-12 pl-0">
                                <h1>Vehicle Details</h1>
                                <div role="table"
                                     aria-label="Vehicle Details"
                                     class="table table-striped">
                                    <div id="vehicleDetails" class="vehicleDetails">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div id="image_view"
                                 class="card text-center py-5 my-2"
                                 style="display: none;">
                                <div class="form-group">
                                    <div class="imagePreview"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row insurance_container">

    </div>
</section>
