<section class="third-section">
    <div class="row">
        <div class="col-8">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="container-fluid pl-0">
                        <div class="row">
                            <div class="form-group row">
                                <label
                                    class="col-xs-12 col-sm-6 col-md-5
                                                        col-lg-4 app-field-label field-required"
                                    for="staff_no">
                                    Staff Number:
                                </label>
                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
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
                                            <button type="button" id="driverSearchBtn"
                                                    name="driverSearchBtn"
                                                    class="btn btn-success btn-sm border-radius-0">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                        <datalist id="employee_list">
                                        </datalist>
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
                                    class="col-xs-12 col-sm-6 col-md-5
                                                        col-lg-4 app-field-label field-required"
                                    for="driver_name">
                                    Name:
                                </label>
                                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           id="driver_name"
                                           readonly
                                           name="driver_name"
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
                                    class="col-xs-12 col-sm-6 col-md-5
                                                        col-lg-4 app-field-label field-required"
                                    for="job_title">
                                    Position:
                                </label>
                                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control form-control-sm"
                                               id="job_title"
                                               readonly
                                               value=""
                                               name="job_title"
                                               required>
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
                                    class="col-xs-12 col-sm-6 col-md-5
                                                        col-lg-4 app-field-label field-required"
                                    for="experience">
                                    Experience(Years):
                                </label>
                                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control form-control-sm"
                                               id="experience"
                                               value=""
                                               name="experience"
                                               required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</section>
