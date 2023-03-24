@extends('layouts.layout')
@section('content')
    {{--    <div id="add0" class="modal fade bd-example-modal-lg" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <strong>Manage Vehicle</strong>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body">
                        <form action="https://vmsdemo.bdtask-demo.com/vehiclemgt/Vehicle_management/add_vehicle"
                              id="emp_form" class="row" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                            <div class="col-md-12 col-lg-6">
                                <div class="form-group row">
                                    <label for="vehicle_name" class="col-sm-5 col-form-label">Vehicle Name <i
                                            class="text-danger">*</i></label>
                                    <div class="col-sm-7">
                                        <input name="vehicle_name" required="" class="form-control" type="text"
                                               placeholder="Vehicle Name" id="vehicle_name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="vin_sn" class="col-sm-5 col-form-label">Department<i
                                            class="text-danger">*</i></label>
                                    <div class="col-sm-7">
                                        <select class="form-control basic-single select2-hidden-accessible" required=""
                                                name="vin_sn" id="vin_sn" data-select2-id="vin_sn" tabindex="-1"
                                                aria-hidden="true">
                                            <option value="" selected="selected" data-select2-id="2">Please Select One
                                            </option>
                                            <option value="mmkmk">
                                                mmkmk
                                            </option>
                                            <option value="Technical">
                                                Technical
                                            </option>
                                            <option value="Marketing &amp; Sales">
                                                Marketing &amp; Sales
                                            </option>
                                            <option value="Human Resource">
                                                Human Resource
                                            </option>
                                            <option value="Accounting">
                                                Accounting
                                            </option>
                                        </select><span class="select2 select2-container select2-container--default"
                                                       dir="ltr" data-select2-id="1" style="width: auto;"><span
                                                class="selection"><span class="select2-selection select2-selection--single"
                                                                        role="combobox" aria-haspopup="true"
                                                                        aria-expanded="false" tabindex="0"
                                                                        aria-labelledby="select2-vin_sn-container"><span
                                                        class="select2-selection__rendered" id="select2-vin_sn-container"
                                                        role="textbox" aria-readonly="true" title="Please Select One">Please Select One</span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="registration_date" class="col-sm-5 col-form-label">Registration Date <i
                                            class="text-danger">*</i></label>
                                    <div class="col-sm-7">
                                        <input name="registration_date" required="" class="form-control newdatetimepicker"
                                               type="text" placeholder="Registration Date" id="registration_date">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="license_plate" class="col-sm-5 col-form-label">License Plate <i
                                            class="text-danger">*</i></label>
                                    <div class="col-sm-7">
                                        <input name="license_plate" required="" class="form-control" type="number"
                                               placeholder="License Plate" id="license_plate">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="al_cell_no" class="col-sm-5 col-form-label">Alert Cell No <i
                                            class="text-danger">*</i></label>
                                    <div class="col-sm-7">
                                        <input name="al_cell_no" required="" class="form-control" type="number"
                                               placeholder="Alert Cell No" id="al_cell_no">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="al_email" class="col-sm-5 col-form-label">Alert Email <i
                                            class="text-danger">*</i></label>
                                    <div class="col-sm-7">
                                        <input name="al_email" required="" class="form-control" type="email"
                                               placeholder="Alert Email" id="al_email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="ownership" class="col-sm-5 col-form-label">Ownership <i class="text-danger">*</i></label>
                                    <div class="col-sm-7">
                                        <select required="" class="form-control basic-single select2-hidden-accessible"
                                                name="ownership" id="ownership" data-select2-id="ownership" tabindex="-1"
                                                aria-hidden="true">
                                            <option value="" selected="selected" data-select2-id="4">Please Select One
                                            </option>
                                            <option value="Pakistan Railways">
                                                Pakistan Railways
                                            </option>
                                            <option value="Pakistan Railways">
                                                Pakistan Railways
                                            </option>
                                            <option value="Pakistan Railways">
                                                Pakistan Railways
                                            </option>
                                        </select><span class="select2 select2-container select2-container--default"
                                                       dir="ltr" data-select2-id="3" style="width: auto;"><span
                                                class="selection"><span class="select2-selection select2-selection--single"
                                                                        role="combobox" aria-haspopup="true"
                                                                        aria-expanded="false" tabindex="0"
                                                                        aria-labelledby="select2-ownership-container"><span
                                                        class="select2-selection__rendered" id="select2-ownership-container"
                                                        role="textbox" aria-readonly="true" title="Please Select One">Please Select One</span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6">
                                <div class="form-group row">
                                    <label for="vehicle_type" class="col-sm-5 col-form-label">Vehicle Type <i
                                            class="text-danger">*</i></label>
                                    <div class="col-sm-7">
                                        <select class="form-control basic-single select2-hidden-accessible" required=""
                                                name="vehicle_type" id="vehicle_type" data-select2-id="vehicle_type"
                                                tabindex="-1" aria-hidden="true">
                                            <option value="" selected="selected" data-select2-id="6">Please Select One
                                            </option>
                                            <option value="no ac">no ac</option>
                                            <option value="ac">ac</option>
                                            <option value="Pick Up">Pick Up</option>
                                            <option value="Sedan">Sedan</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                                       dir="ltr" data-select2-id="5" style="width: auto;"><span
                                                class="selection"><span class="select2-selection select2-selection--single"
                                                                        role="combobox" aria-haspopup="true"
                                                                        aria-expanded="false" tabindex="0"
                                                                        aria-labelledby="select2-vehicle_type-container"><span
                                                        class="select2-selection__rendered"
                                                        id="select2-vehicle_type-container" role="textbox"
                                                        aria-readonly="true"
                                                        title="Please Select One">Please Select One</span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="vehicle_division" class="col-sm-5 col-form-label">Vehicle Division <i
                                            class="text-danger">*</i></label>
                                    <div class="col-sm-7">
                                        <select class="form-control basic-single select2-hidden-accessible" required=""
                                                name="vehicle_division" id="vehicle_division"
                                                data-select2-id="vehicle_division" tabindex="-1" aria-hidden="true">
                                            <option value="" selected="selected" data-select2-id="8">Please Select One
                                            </option>
                                            <option value="Borisal">
                                                Borisal
                                            </option>
                                            <option value="Rangpur">
                                                Rangpur
                                            </option>
                                            <option value="Sylhet">
                                                Sylhet
                                            </option>
                                            <option value="Chittagong">
                                                Chittagong
                                            </option>
                                            <option value="Khulna">
                                                Khulna
                                            </option>
                                            <option value="Rajshahi">
                                                Rajshahi
                                            </option>
                                            <option value="Dhaka">
                                                Dhaka
                                            </option>
                                        </select><span class="select2 select2-container select2-container--default"
                                                       dir="ltr" data-select2-id="7" style="width: auto;"><span
                                                class="selection"><span class="select2-selection select2-selection--single"
                                                                        role="combobox" aria-haspopup="true"
                                                                        aria-expanded="false" tabindex="0"
                                                                        aria-labelledby="select2-vehicle_division-container"><span
                                                        class="select2-selection__rendered"
                                                        id="select2-vehicle_division-container" role="textbox"
                                                        aria-readonly="true"
                                                        title="Please Select One">Please Select One</span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="brta_office" class="col-sm-5 col-form-label">RTA Circle Office <i
                                            class="text-danger">*</i></label>
                                    <div class="col-sm-7">
                                        <select class="form-control basic-single select2-hidden-accessible" required=""
                                                name="brta_office" id="brta_office" data-select2-id="brta_office"
                                                tabindex="-1" aria-hidden="true">
                                            <option value="" selected="selected" data-select2-id="10">Please Select One
                                            </option>
                                            <option value="Dhanmondi">
                                                Dhanmondi
                                            </option>
                                            <option value="Gulsion Circle">
                                                Gulsion Circle
                                            </option>
                                            <option value="Motijeel">
                                                Motijeel
                                            </option>
                                            <option value="Mirpur 10">
                                                Mirpur 10
                                            </option>
                                        </select><span class="select2 select2-container select2-container--default"
                                                       dir="ltr" data-select2-id="9" style="width: auto;"><span
                                                class="selection"><span class="select2-selection select2-selection--single"
                                                                        role="combobox" aria-haspopup="true"
                                                                        aria-expanded="false" tabindex="0"
                                                                        aria-labelledby="select2-brta_office-container"><span
                                                        class="select2-selection__rendered"
                                                        id="select2-brta_office-container" role="textbox"
                                                        aria-readonly="true"
                                                        title="Please Select One">Please Select One</span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="driver" class="col-sm-5 col-form-label">Driver <i class="text-danger">*</i></label>
                                    <div class="col-sm-7">
                                        <select class="form-control basic-single select2-hidden-accessible" required=""
                                                name="driver" id="driver" data-select2-id="driver" tabindex="-1"
                                                aria-hidden="true">
                                            <option value="" selected="selected" data-select2-id="12">Please Select One
                                            </option>
                                            <option value="Demo driver" data-id="12">Demo driver</option>
                                            <option value="driver name" data-id="11">driver name</option>
                                            <option value="Faris Shafi" data-id="9">Faris Shafi</option>
                                            <option value="Khurram" data-id="8">Khurram</option>
                                            <option value="Musa Karim - Fareed Express" data-id="4">Musa Karim - Fareed
                                                Express
                                            </option>
                                            <option value="Malik - Khyber Express" data-id="3">Malik - Khyber Express
                                            </option>
                                            <option value="Jaman - Shah Latif Express" data-id="2">Jaman - Shah Latif
                                                Express
                                            </option>
                                        </select><span class="select2 select2-container select2-container--default"
                                                       dir="ltr" data-select2-id="11" style="width: auto;"><span
                                                class="selection"><span class="select2-selection select2-selection--single"
                                                                        role="combobox" aria-haspopup="true"
                                                                        aria-expanded="false" tabindex="0"
                                                                        aria-labelledby="select2-driver-container"><span
                                                        class="select2-selection__rendered" id="select2-driver-container"
                                                        role="textbox" aria-readonly="true" title="Please Select One">Please Select One</span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>
                                        <input name="dirverid" id="dirverid" type="hidden" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="vendor" class="col-sm-5 col-form-label">Vendor <i class="text-danger">*</i></label>
                                    <div class="col-sm-7">
                                        <select class="form-control basic-single select2-hidden-accessible" required=""
                                                name="vendor" id="vendor" data-select2-id="vendor" tabindex="-1"
                                                aria-hidden="true">
                                            <option value="" selected="selected" data-select2-id="14">Please Select One
                                            </option>
                                            <option value="Auto Parts">Auto Parts</option>
                                            <option value="Pakistan Railways">Pakistan Railways</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                                       dir="ltr" data-select2-id="13" style="width: auto;"><span
                                                class="selection"><span class="select2-selection select2-selection--single"
                                                                        role="combobox" aria-haspopup="true"
                                                                        aria-expanded="false" tabindex="0"
                                                                        aria-labelledby="select2-vendor-container"><span
                                                        class="select2-selection__rendered" id="select2-vendor-container"
                                                        role="textbox" aria-readonly="true" title="Please Select One">Please Select One</span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="seat_capicity" class="col-sm-5 col-form-label">Seat Capacity (With Driver)
                                        <i class="text-danger">*</i></label>
                                    <div class="col-sm-7">
                                        <input name="seat_capicity" required="" class="form-control" type="number"
                                               placeholder="Seat Capacity (With Driver)" id="seat_capicity">
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <button type="button" class="btn btn-primary w-md m-b-5">Reset</button>
                                    <button type="submit" class="btn btn-success w-md m-b-5">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="edit" class="modal fade bd-example-modal-lg" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <strong>Update Vehicle</strong>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body editinfo">
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card mb-3">
                    <div class="card-header p-2">
                        <h4 class="pl-3">Search Here <small class="float-right">
                                <button type="button" class="btn btn-primary btn-md" data-bs-target="#add0"
                                        data-toggle="modal"><i class="ti-plus" aria-hidden="true"></i>
                                    Add Vehicle
                                </button>
                            </small></h4>
                    </div>
                    <div class="card-body row">
                        <div class="col-sm-12 col-xl-4">
                            <div class="form-group row mb-1">
                                <label for="vehicle" class="col-sm-5 col-form-label justify-content-start text-left">
                                    Department
                                </label>
                                <div class="col-sm-7">
                                    <select class="form-control basic-single select2-hidden-accessible" name="vehicle"
                                            id="vehicle" data-select2-id="vehicle" tabindex="-1" aria-hidden="true">
                                        <option value="" selected="selected" data-select2-id="16">Please Select One</option>
                                        <option value="iuoioio">iuoioio</option>
                                        <option value="Technical">Technical</option>
                                    </select><span class="select2 select2-container select2-container--default" dir="ltr"
                                                   data-select2-id="15" style="width: 286.812px;"><span
                                            class="selection"><span class="select2-selection select2-selection--single"
                                                                    role="combobox" aria-haspopup="true"
                                                                    aria-expanded="false" tabindex="0"
                                                                    aria-labelledby="select2-vehicle-container"><span
                                                    class="select2-selection__rendered" id="select2-vehicle-container"
                                                    role="textbox" aria-readonly="true" title="Please Select One">Please Select One</span><span
                                                    class="select2-selection__arrow" role="presentation"><b
                                                        role="presentation"></b></span></span></span><span
                                            class="dropdown-wrapper" aria-hidden="true"></span></span>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label for="vehicle_typesr" class="col-sm-5 col-form-label justify-content-start text-left">Vehicle
                                    Type <i class="text-danger">*</i></label>
                                <div class="col-sm-7">
                                    <select class="form-control basic-single select2-hidden-accessible"
                                            name="vehicle_typesr" id="vehicle_typesr" data-select2-id="vehicle_typesr"
                                            tabindex="-1" aria-hidden="true">
                                        <option value="" selected="selected" data-select2-id="18">Please Select One</option>
                                        <option value="no ac">no ac</option>
                                        <option value="ac">ac</option>
                                        <option value="Pick Up">Pick Up</option>
                                        <option value="Sedan">Sedan</option>
                                    </select><span class="select2 select2-container select2-container--default" dir="ltr"
                                                   data-select2-id="17" style="width: 286.812px;"><span
                                            class="selection"><span class="select2-selection select2-selection--single"
                                                                    role="combobox" aria-haspopup="true"
                                                                    aria-expanded="false" tabindex="0"
                                                                    aria-labelledby="select2-vehicle_typesr-container"><span
                                                    class="select2-selection__rendered"
                                                    id="select2-vehicle_typesr-container" role="textbox"
                                                    aria-readonly="true"
                                                    title="Please Select One">Please Select One</span><span
                                                    class="select2-selection__arrow" role="presentation"><b
                                                        role="presentation"></b></span></span></span><span
                                            class="dropdown-wrapper" aria-hidden="true"></span></span>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label for="ownershipsr" class="col-sm-5 col-form-label justify-content-start text-left">Ownership
                                    <i class="text-danger">*</i></label>
                                <div class="col-sm-7">
                                    <select class="form-control basic-single select2-hidden-accessible" name="ownershipsr"
                                            id="ownershipsr" data-select2-id="ownershipsr" tabindex="-1" aria-hidden="true">
                                        <option value="" selected="selected" data-select2-id="20">Please Select One</option>
                                        <option value="Pakistan Railways">
                                            Pakistan Railways
                                        </option>
                                        <option value="Pakistan Railways">
                                            Pakistan Railways
                                        </option>
                                        <option value="Pakistan Railways">
                                            Pakistan Railways
                                        </option>
                                    </select><span class="select2 select2-container select2-container--default" dir="ltr"
                                                   data-select2-id="19" style="width: 286.812px;"><span
                                            class="selection"><span class="select2-selection select2-selection--single"
                                                                    role="combobox" aria-haspopup="true"
                                                                    aria-expanded="false" tabindex="0"
                                                                    aria-labelledby="select2-ownershipsr-container"><span
                                                    class="select2-selection__rendered" id="select2-ownershipsr-container"
                                                    role="textbox" aria-readonly="true" title="Please Select One">Please Select One</span><span
                                                    class="select2-selection__arrow" role="presentation"><b
                                                        role="presentation"></b></span></span></span><span
                                            class="dropdown-wrapper" aria-hidden="true"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xl-4">
                            <div class="form-group row mb-1">
                                <label for="registration_date_fr"
                                       class="col-sm-5 col-form-label justify-content-start text-left">Registration Date
                                    From </label>
                                <div class="col-sm-7">
                                    <input name="registration_date_fr" autocomplete="off"
                                           class="form-control newdatetimepicker" type="text"
                                           placeholder="Registration Date From" id="registration_date_fr">
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label for="registration_date_to"
                                       class="col-sm-5 col-form-label justify-content-start text-left">Registration Date
                                    To </label>
                                <div class="col-sm-7">
                                    <input name="registration_date_to" autocomplete="off"
                                           class="form-control newdatetimepicker" type="text"
                                           placeholder="Registration Date To" id="registration_date_to">
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label for="vendorsr" class="col-sm-5 col-form-label justify-content-start text-left">Vendor
                                    <i class="text-danger">*</i></label>
                                <div class="col-sm-7">
                                    <select class="form-control basic-single select2-hidden-accessible" name="vendorsr"
                                            id="vendorsr" data-select2-id="vendorsr" tabindex="-1" aria-hidden="true">
                                        <option value="" selected="selected" data-select2-id="22">Please Select One</option>
                                        <option value="Auto Parts">Auto Parts</option>
                                        <option value="Pakistan Railways">Pakistan Railways</option>
                                    </select><span class="select2 select2-container select2-container--default" dir="ltr"
                                                   data-select2-id="21" style="width: 286.812px;"><span
                                            class="selection"><span class="select2-selection select2-selection--single"
                                                                    role="combobox" aria-haspopup="true"
                                                                    aria-expanded="false" tabindex="0"
                                                                    aria-labelledby="select2-vendorsr-container"><span
                                                    class="select2-selection__rendered" id="select2-vendorsr-container"
                                                    role="textbox" aria-readonly="true" title="Please Select One">Please Select One</span><span
                                                    class="select2-selection__arrow" role="presentation"><b
                                                        role="presentation"></b></span></span></span><span
                                            class="dropdown-wrapper" aria-hidden="true"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xl-4">
                            <div class="form-group row mb-1">
                                <div class="col-sm-8 text-right">
                                    <button type="button" class="btn btn-success" id="btn-filter">Search</button>&nbsp;
                                    <button type="button" class="btn btn-inverse" id="btn-reset">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card mb-3">
                    <div class="card-header p-2">
                        <h4 class="pl-3">Manage Vehicle</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="vehicinfo_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="dt-buttons btn-group">
                                            <button class="btn btn-secondary buttons-copy buttons-html5 btn-success"
                                                    tabindex="0" aria-controls="vehicinfo" type="button"><span>Copy</span>
                                            </button>
                                            <button class="btn btn-secondary buttons-excel buttons-html5 btn-success"
                                                    tabindex="0" aria-controls="vehicinfo" type="button"><span>Excel</span>
                                            </button>
                                            <button class="btn btn-secondary buttons-pdf buttons-html5 btn-success"
                                                    tabindex="0" aria-controls="vehicinfo" type="button"><span>PDF</span>
                                            </button>
                                            <button class="btn btn-secondary buttons-print btn-success" tabindex="0"
                                                    aria-controls="vehicinfo" type="button"><span>Print</span></button>
                                            <div class="btn-group">
                                                <button
                                                    class="btn btn-secondary buttons-collection dropdown-toggle buttons-colvis btn-success"
                                                    tabindex="0" aria-controls="vehicinfo" type="button"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <span>Column visibility</span></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div id="vehicinfo_filter" class="dataTables_filter"><label>Search:<input
                                                    type="search" class="form-control form-control-sm" placeholder=""
                                                    aria-controls="vehicinfo"></label></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="vehicinfo"
                                               class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed"
                                               role="grid" aria-describedby="vehicinfo_info" style="width: 1546px;">
                                            <thead>
                                            <tr role="row">
                                                <th class="sorting_asc" tabindex="0" aria-controls="vehicinfo" rowspan="1"
                                                    colspan="1" style="width: 62px;" aria-sort="ascending"
                                                    aria-label="SL: activate to sort column descending">SL
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="vehicinfo" rowspan="1"
                                                    colspan="1" style="width: 243px;"
                                                    aria-label="Name: activate to sort column ascending">Name
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="vehicinfo" rowspan="1"
                                                    colspan="1" style="width: 171px;"
                                                    aria-label="Type: activate to sort column ascending">Type
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="vehicinfo" rowspan="1"
                                                    colspan="1" style="width: 177px;"
                                                    aria-label="Department: activate to sort column ascending">Department
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="vehicinfo" rowspan="1"
                                                    colspan="1" style="width: 242px;"
                                                    aria-label="Registration Date: activate to sort column ascending">
                                                    Registration Date
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="vehicinfo" rowspan="1"
                                                    colspan="1" style="width: 196px;"
                                                    aria-label="Ownership: activate to sort column ascending">Ownership
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="vehicinfo" rowspan="1"
                                                    colspan="1" style="width: 196px;"
                                                    aria-label="Vendor: activate to sort column ascending">Vendor
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="vehicinfo" rowspan="1"
                                                    colspan="1" style="width: 0px; display: none;"
                                                    aria-label="Action: activate to sort column ascending">Action
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-5">
                                        <div class="dataTables_info" id="vehicinfo_info" role="status" aria-live="polite">
                                            Showing 1 to 7 of 7 entries
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-7">
                                        <div class="dataTables_paginate paging_simple_numbers" id="vehicinfo_paginate">
                                            <ul class="pagination">
                                                <li class="paginate_button page-item previous disabled"
                                                    id="vehicinfo_previous"><a href="#" aria-controls="vehicinfo"
                                                                               data-dt-idx="0" tabindex="0"
                                                                               class="page-link">Previous</a></li>
                                                <li class="paginate_button page-item active"><a href="#"
                                                                                                aria-controls="vehicinfo"
                                                                                                data-dt-idx="1" tabindex="0"
                                                                                                class="page-link">1</a></li>
                                                <li class="paginate_button page-item next disabled" id="vehicinfo_next"><a
                                                        href="#" aria-controls="vehicinfo" data-dt-idx="2" tabindex="0"
                                                        class="page-link">Next</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>--}}


    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                              transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                        <path
                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                            fill="currentColor"></path>
                    </svg>
                </span>
                    <input type="text" data-kt-table-filter="search"
                           class="form-control form-control-solid w-250px ps-14" placeholder="Search">
                </div>
            </div>

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" kt_table-toolbar="base">
                    <!--begin::Filter-->
                    <button type="button" class="btn btn-light-primary me-3 d-none" data-kt-menu-trigger="click"
                            data-kt-menu-placement="bottom-end">
                    <span class="svg-icon svg-icon-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                fill="currentColor"></path>
                        </svg>
                    </span>
                        Filter
                    </button>

                    <!--begin::Menu 1-->
                    <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                        <!--begin::Header-->
                        <div class="px-7 py-5">
                            <div class="fs-5 text-dark fw-bold">Filter Options</div>
                        </div>

                        <div class="separator border-gray-200"></div>

                        <div class="px-7 py-5" data-kt-table-filter="form">
                            <div class="mb-10">
                                <label for="data-kt-table-filter_month"
                                       class="form-label fs-6 fw-semibold">Month:</label>
                                <select id="data-kt-table-filter_month" class="form-select form-select-solid fw-bold"
                                        data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true"
                                        data-kt-table-filter="month" data-hide-search="true"
                                        data-select2-id="select2-data-10-tvzx" tabindex="-1" aria-hidden="true">
                                    <option data-select2-id="select2-data-12-scpe"></option>
                                    <option value="jan">January</option>
                                    <option value="feb">February</option>
                                    <option value="mar">March</option>
                                    <option value="apr">April</option>
                                    <option value="may">May</option>
                                    <option value="jun">June</option>
                                    <option value="jul">July</option>
                                    <option value="aug">August</option>
                                    <option value="sep">September</option>
                                    <option value="oct">October</option>
                                    <option value="nov">November</option>
                                    <option value="dec">December</option>
                                </select>
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="mb-10">
                                <label for="data-kt-table-filter_status"
                                       class="form-label fs-6 fw-semibold">Status:</label>
                                <select id="data-kt-table-filter_status" class="form-select form-select-solid fw-bold"
                                        data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true"
                                        data-kt-table-filter="status" data-hide-search="true"
                                        data-select2-id="select2-data-13-gx72" tabindex="-1" aria-hidden="true">
                                    <option data-select2-id="select2-data-15-256v"></option>
                                    <option value="Active">Active</option>
                                    <option value="Expiring">Expiring</option>
                                    <option value="Suspended">Suspended</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="reset"
                                        class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                        data-kt-menu-dismiss="true" data-kt-table-filter="reset">Reset
                                </button>
                                <button type="submit" class="btn btn-primary fw-semibold px-6"
                                        data-kt-menu-dismiss="true" data-kt-table-filter="filter">Apply
                                </button>
                            </div>
                        </div>
                    </div>


                    <a href="{{route('new.vehicle')}}"
                       class="btn btn-primary">
                    <span class="svg-icon svg-icon-2"><svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                           xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                  transform="rotate(-90 11.364 20.364)" fill="currentColor"></rect>
                            <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor"></rect>
                        </svg></span>
                        Add Vehicle
                    </a>
                </div>

                <div class="d-flex justify-content-end align-items-center d-none" kt_table-toolbar="selected">
                    <div class="fw-bold me-5">
                        <span class="me-2" kt_table-select="selected_count"></span> Selected
                    </div>

                    <button type="button" class="btn btn-danger" kt_table-select="delete_selected">
                        Deactivate Selected
                    </button>
                </div>

            </div>

        </div>

        <!--begin::Card body-->
        <div class="card-body pt-0">

            <!--begin::Table-->
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" id="kt_brands_table">
                    <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th>
                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                <input class="list-row-checkbox" type="checkbox" data-kt-check="true"
                                       data-kt-check-target="#kt_brands_table .form-check-input" value="all"/>
                            </div>
                        </th>

                        <th>
                            Brand
                        </th>

                        <th>
                            Model
                        </th>

                        <th>
                            Type
                        </th>
                        <th>
                            Reg. Number
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Date Registered
                        </th>

                        <th>
                            Actions
                        </th>
                    </tr>
                    </thead>


                    <tbody class="text-gray-600 fw-semibold">
                    @foreach($vehicleList as $vehicle)
                        <tr>
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="list-row-checkbox" type="checkbox" value="item.guid"/>
                                </div>
                            </td>

                            <td>
                                <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                    {{$vehicle->brand_name}}
                                </a>
                            </td>
                            <td>
                                <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                    {{$vehicle->model_name}} : {{$vehicle->model_code}}
                                </a>
                            </td>

                            <td>
                                <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                    {{$vehicle->body_type_name}}
                                </a>
                            </td>

                            <td>
                                <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                    {{$vehicle->registration_number}}
                                </a>
                            </td>

                            <td>
                                @if('') @endif
                                {{-- <div v-if="item.status.toLowerCase() === 'active'" class="badge badge-light-success">
                                     Active
                                 </div>
                                 <div v-else-if="item.status.toLowerCase() === 'expiring'" class="badge badge-light-warning">
                                     Expiring
                                 </div>
                                 <div v-else-if="item.status.toLowerCase() === 'suspended'" class="badge badge-light-danger">
                                     Suspended
                                 </div>
                                 <div v-else class="badge badge-danger">
                                     @{{ item.status.toLowerCase() }}
                                 </div>--}}
                            </td>


                            <td>
                                {{$vehicle->created_at }}
                            </td>

                            <td class="text-start">
                                <a href="#" class="btn btn-light btn-active-light-primary btn-sm"
                                   data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    Actions
                                    <span class="svg-icon svg-icon-5 m-0"><svg width="24" height="24"
                                                                               viewBox="0 0 24 24"
                                                                               fill="none"
                                                                               xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </span>

                                    <div
                                        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                        data-kt-menu="true">

                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">
                                                Edit
                                            </a>
                                        </div>

                                        <div class="menu-item px-3">
                                            <a href="#" data-kt-action="remove" data-kt-table-filter="delete_row"
                                               class="menu-link px-3">
                                                Deactivate
                                            </a>
                                        </div>

                                    </div>
                                </a>
                            </td>

                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('assets/dist/js/vehicle_list.js')}}"></script>
@endpush
