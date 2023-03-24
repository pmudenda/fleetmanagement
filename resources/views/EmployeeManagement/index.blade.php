<div class="body-content" id="bodycontent"><div id="add0" class="modal fade bd-example-modal-xl" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <strong>Add Employee</strong>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <form action="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/create_employee" id="emp_form" class="row" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                        <div class="col-md-12 col-lg-6">
                            <div class="form-group row">
                                <label for="emp_name" class="col-sm-5 col-form-label">Employee Name <i class="text-danger">*</i></label>
                                <div class="col-sm-7">
                                    <input name="emp_name" required="" id="emp_name" class="form-control" type="text" placeholder="Employee Name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pay_roll_type" class="col-sm-5 col-form-label">Pay Roll Type <i class="text-danger">*</i></label>
                                <div class="col-sm-7">
                                    <select class="form-control basic-single select2-hidden-accessible" required="" name="pay_roll_type" id="pay_roll_type" data-select2-id="pay_roll_type" tabindex="-1" aria-hidden="true">
                                        <option value="" selected="selected" data-select2-id="10">Please Select One</option>
                                        <option value="External">External</option>
                                        <option value="Internal">Internal</option>
                                    </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="9" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-pay_roll_type-container"><span class="select2-selection__rendered" id="select2-pay_roll_type-container" role="textbox" aria-readonly="true" title="Please Select One">Please Select One</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="department" class="col-sm-5 col-form-label">Department <i class="text-danger">*</i></label>
                                <div class="col-sm-7">
                                    <select class="form-control basic-single select2-hidden-accessible" required="" name="department" id="department" data-select2-id="department" tabindex="-1" aria-hidden="true">
                                        <option value="" selected="selected" data-select2-id="12">Please Select One</option>
                                        <option value="Accounting">
                                            Accounting</option>
                                        <option value="Human Resource">
                                            Human Resource</option>
                                        <option value="Marketing &amp; Sales">
                                            Marketing &amp; Sales</option>
                                        <option value="Technical">
                                            Technical</option>
                                        <option value="mmkmk">
                                            mmkmk</option>
                                    </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="11" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-department-container"><span class="select2-selection__rendered" id="select2-department-container" role="textbox" aria-readonly="true" title="Please Select One">Please Select One</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-5 col-form-label">Email <i class="text-danger">*</i></label>
                                <div class="col-sm-7">
                                    <input name="email" required="" class="form-control" type="email" placeholder="Email" id="email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email2" class="col-sm-5 col-form-label">Email Optional </label>
                                <div class="col-sm-7">
                                    <input name="email2" class="form-control" type="email" placeholder="Email Optional" id="email2">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="blood" class="col-sm-5 col-form-label">Blood Group </label>
                                <div class="col-sm-7">
                                    <select class="form-control basic-single select2-hidden-accessible" name="blood" id="blood" data-select2-id="blood" tabindex="-1" aria-hidden="true">
                                        <option value="" selected="selected" data-select2-id="14">Please Select One</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                    </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="13" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-blood-container"><span class="select2-selection__rendered" id="select2-blood-container" role="textbox" aria-readonly="true" title="Please Select One">Please Select One</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="working_slot_from" class="col-sm-5 col-form-label">Working Slot From </label>
                                <div class="col-sm-7">
                                    <input name="working_slot_from" class="form-control ttimepicker" type="text" placeholder="Working Slot From" id="working_slot_from">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="fater_name" class="col-sm-5 col-form-label">Father Name </label>
                                <div class="col-sm-7">
                                    <input name="fater_name" class="form-control" type="text" placeholder="Father Name" id="fater_name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="present_cont" class="col-sm-5 col-form-label">Present Contact Number </label>
                                <div class="col-sm-7">
                                    <input name="present_cont" class="form-control" type="number" placeholder="Present Contact Number" id="present_cont">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="present_address" class="col-sm-5 col-form-label">Present Address </label>
                                <div class="col-sm-7">
                                    <input name="present_address" class="form-control" type="text" placeholder="Present Address" id="present_address">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="present_city" class="col-sm-5 col-form-label">Present City </label>
                                <div class="col-sm-7">
                                    <select class="form-control basic-single select2-hidden-accessible" name="present_city" id="present_city" data-select2-id="present_city" tabindex="-1" aria-hidden="true">
                                        <option value="" selected="selected" data-select2-id="16">Please Select One</option>
                                        <option value="Dhaka">Dhaka</option>
                                        <option value="Faridpur">Faridpur</option>
                                        <option value="Gazipur">Gazipur</option>
                                        <option value="Gopalganj">Gopalganj</option>
                                        <option value="Jamalpur">Jamalpur</option>
                                        <option value="Kishoreganj">Kishoreganj</option>
                                        <option value="Madaripur">Madaripur</option>
                                        <option value="Manikganj">Manikganj</option>
                                        <option value="Munshiganj">Munshiganj</option>
                                        <option value="Mymensingh">Mymensingh</option>
                                        <option value="Narayanganj">Narayanganj</option>
                                        <option value="Narsingdi">Narsingdi</option>
                                        <option value="Netrokona">Netrokona</option>
                                        <option value="Rajbari">Rajbari</option>
                                        <option value="Shariatpur">Shariatpur</option>
                                        <option value="Sherpur">Sherpur</option>
                                        <option value="Tangail">Tangail</option>
                                        <option value="Bogura">Bogura</option>
                                        <option value="Joypurhat">Joypurhat</option>
                                        <option value="Naogaon">Naogaon</option>
                                        <option value="Natore">Natore</option>
                                        <option value="Chapainawabganj">Chapainawabganj</option>
                                        <option value="Pabna">Pabna</option>
                                        <option value="Rajshahi">Rajshahi</option>
                                        <option value="Sirajgonj">Sirajgonj</option>
                                        <option value="Dinajpur">Dinajpur</option>
                                        <option value="Gaibandha">Gaibandha</option>
                                        <option value="Kurigram">Kurigram</option>
                                        <option value="Lalmonirhat">Lalmonirhat</option>
                                        <option value="Nilphamari">Nilphamari</option>
                                        <option value="Panchagarh">Panchagarh</option>
                                        <option value="Rangpur">Rangpur</option>
                                        <option value="Thakurgaon">Thakurgaon</option>
                                        <option value="Barguna">Barguna</option>
                                        <option value="Barishal">Barishal</option>
                                        <option value="Bhola">Bhola</option>
                                        <option value="Jhalokati">Jhalokati</option>
                                        <option value="Patuakhali">Patuakhali</option>
                                        <option value="Pirojpur">Pirojpur</option>
                                        <option value="Bandarban">Bandarban</option>
                                        <option value="Brahmanbaria">Brahmanbaria</option>
                                        <option value="Chandpur">Chandpur</option>
                                        <option value="Chattogram">Chattogram</option>
                                        <option value="Cumilla">Cumilla</option>
                                        <option value="Cox's Bazar">Cox's Bazar</option>
                                        <option value="Feni">Feni</option>
                                        <option value="Khagrachhari">Khagrachhari</option>
                                        <option value="Lakshmipur">Lakshmipur</option>
                                        <option value="Noakhali">Noakhali</option>
                                        <option value="Rangamati">Rangamati</option>
                                        <option value="Habiganj">Habiganj</option>
                                        <option value="Moulvibazar">Moulvibazar</option>
                                        <option value="Sunamganj">Sunamganj</option>
                                        <option value="Sylhet">Sylhet</option>
                                        <option value="Bagerhat">Bagerhat</option>
                                        <option value="Chuadanga">Chuadanga</option>
                                        <option value="Jashore">Jashore</option>
                                        <option value="Jhenaidah">Jhenaidah</option>
                                        <option value="Khulna">Khulna</option>
                                        <option value="Kushtia">Kushtia</option>
                                        <option value="Magura">Magura</option>
                                        <option value="Meherpur">Meherpur</option>
                                        <option value="Narail">Narail</option>
                                        <option value="Satkhira">Satkhira</option>
                                    </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="15" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-present_city-container"><span class="select2-selection__rendered" id="select2-present_city-container" role="textbox" aria-readonly="true" title="Please Select One">Please Select One</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="referance" class="col-sm-5 col-form-label">Reference Name </label>
                                <div class="col-sm-7">
                                    <input name="referance" class="form-control" type="text" placeholder="Reference Name" id="referance">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ref_address" class="col-sm-5 col-form-label">Reference Address </label>
                                <div class="col-sm-7">
                                    <input name="ref_address" class="form-control" type="text" placeholder="Reference Address" id="present_address">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ref_email" class="col-sm-5 col-form-label">Reference Email </label>
                                <div class="col-sm-7">
                                    <input name="ref_email" class="form-control" type="email" placeholder="Reference Email " id="ref_email">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <div class="form-group row">
                                <label for="emp_nid" class="col-sm-5 col-form-label">Employee NID <i class="text-danger">*</i></label>
                                <div class="col-sm-7">
                                    <input name="emp_nid" required="" class="form-control" type="number" placeholder="Employee NID" id="emp_nid">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="designation" class="col-sm-5 col-form-label">Designation <i class="text-danger">*</i></label>
                                <div class="col-sm-7">
                                    <select class="form-control basic-single select2-hidden-accessible" required="" name="designation" id="designation" data-select2-id="designation" tabindex="-1" aria-hidden="true">
                                        <option value="" selected="selected" data-select2-id="18">Please Select One</option>
                                        <option value="Supervisor">Supervisor </option>
                                        <option value="Manager">Manager </option>
                                        <option value="Accounts">Accounts </option>
                                        <option value="Driver">Driver </option>
                                        <option value="Helper">Helper </option>
                                        <option value="Receptionist">Receptionist </option>
                                        <option value="Palero">Palero </option>
                                    </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="17" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-designation-container"><span class="select2-selection__rendered" id="select2-designation-container" role="textbox" aria-readonly="true" title="Please Select One">Please Select One</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="phone" class="col-sm-5 col-form-label">Employee Mobile <i class="text-danger">*</i></label>
                                <div class="col-sm-7">
                                    <input name="phone" required="" class="form-control" type="number" placeholder="Employee Mobile" id="phone">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="phone2" class="col-sm-5 col-form-label">Employee Mobile Optional </label>
                                <div class="col-sm-7">
                                    <input name="phone2" class="form-control" type="number" placeholder="Employee Mobile Optional " id="phone2">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="join_date" class="col-sm-5 col-form-label">Join Date <i class="text-danger">*</i></label>
                                <div class="col-sm-7">
                                    <input name="join_date" required="" autocomplete="off" class="form-control newdatetimepicker" type="text" placeholder="Join Date" id="join_date">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="dob" class="col-sm-5 col-form-label">Date of Birth <i class="text-danger">*</i></label>
                                <div class="col-sm-7">
                                    <input name="dob" required="" autocomplete="off" class="form-control newdatetimepicker" type="text" placeholder="Date of Birth" id="dob">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="working_slot_to" class="col-sm-5 col-form-label">Working Slot To </label>
                                <div class="col-sm-7">
                                    <input name="working_slot_to" class="form-control ttimepicker" type="text" placeholder="Working Slot To" id="working_slot_to">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="mother_name" class="col-sm-5 col-form-label">Mother Name </label>
                                <div class="col-sm-7">
                                    <input name="mother_name" class="form-control" type="text" placeholder="Mother Name" id="mother_name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="permanent_contact" class="col-sm-5 col-form-label">Permanent Contact Number </label>
                                <div class="col-sm-7">
                                    <input name="permanent_contact" class="form-control" type="text" placeholder="Permanent Contact Number" id="permanent_contact">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="permanent_address" class="col-sm-5 col-form-label">Permanent Address </label>
                                <div class="col-sm-7">
                                    <input name="permanent_address" class="form-control" type="text" placeholder="Permanent Address" id="permanent_address">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="permanent_city" class="col-sm-5 col-form-label">Permanent City </label>
                                <div class="col-sm-7">
                                    <select class="form-control basic-single select2-hidden-accessible" name="permanent_city" id="permanent_city" data-select2-id="permanent_city" tabindex="-1" aria-hidden="true">
                                        <option value="" selected="selected" data-select2-id="20">Please Select One</option>
                                        <option value="Dhaka">Dhaka</option>
                                        <option value="Faridpur">Faridpur</option>
                                        <option value="Gazipur">Gazipur</option>
                                        <option value="Gopalganj">Gopalganj</option>
                                        <option value="Jamalpur">Jamalpur</option>
                                        <option value="Kishoreganj">Kishoreganj</option>
                                        <option value="Madaripur">Madaripur</option>
                                        <option value="Manikganj">Manikganj</option>
                                        <option value="Munshiganj">Munshiganj</option>
                                        <option value="Mymensingh">Mymensingh</option>
                                        <option value="Narayanganj">Narayanganj</option>
                                        <option value="Narsingdi">Narsingdi</option>
                                        <option value="Netrokona">Netrokona</option>
                                        <option value="Rajbari">Rajbari</option>
                                        <option value="Shariatpur">Shariatpur</option>
                                        <option value="Sherpur">Sherpur</option>
                                        <option value="Tangail">Tangail</option>
                                        <option value="Bogura">Bogura</option>
                                        <option value="Joypurhat">Joypurhat</option>
                                        <option value="Naogaon">Naogaon</option>
                                        <option value="Natore">Natore</option>
                                        <option value="Chapainawabganj">Chapainawabganj</option>
                                        <option value="Pabna">Pabna</option>
                                        <option value="Rajshahi">Rajshahi</option>
                                        <option value="Sirajgonj">Sirajgonj</option>
                                        <option value="Dinajpur">Dinajpur</option>
                                        <option value="Gaibandha">Gaibandha</option>
                                        <option value="Kurigram">Kurigram</option>
                                        <option value="Lalmonirhat">Lalmonirhat</option>
                                        <option value="Nilphamari">Nilphamari</option>
                                        <option value="Panchagarh">Panchagarh</option>
                                        <option value="Rangpur">Rangpur</option>
                                        <option value="Thakurgaon">Thakurgaon</option>
                                        <option value="Barguna">Barguna</option>
                                        <option value="Barishal">Barishal</option>
                                        <option value="Bhola">Bhola</option>
                                        <option value="Jhalokati">Jhalokati</option>
                                        <option value="Patuakhali">Patuakhali</option>
                                        <option value="Pirojpur">Pirojpur</option>
                                        <option value="Bandarban">Bandarban</option>
                                        <option value="Brahmanbaria">Brahmanbaria</option>
                                        <option value="Chandpur">Chandpur</option>
                                        <option value="Chattogram">Chattogram</option>
                                        <option value="Cumilla">Cumilla</option>
                                        <option value="Cox's Bazar">Cox's Bazar</option>
                                        <option value="Feni">Feni</option>
                                        <option value="Khagrachhari">Khagrachhari</option>
                                        <option value="Lakshmipur">Lakshmipur</option>
                                        <option value="Noakhali">Noakhali</option>
                                        <option value="Rangamati">Rangamati</option>
                                        <option value="Habiganj">Habiganj</option>
                                        <option value="Moulvibazar">Moulvibazar</option>
                                        <option value="Sunamganj">Sunamganj</option>
                                        <option value="Sylhet">Sylhet</option>
                                        <option value="Bagerhat">Bagerhat</option>
                                        <option value="Chuadanga">Chuadanga</option>
                                        <option value="Jashore">Jashore</option>
                                        <option value="Jhenaidah">Jhenaidah</option>
                                        <option value="Khulna">Khulna</option>
                                        <option value="Kushtia">Kushtia</option>
                                        <option value="Magura">Magura</option>
                                        <option value="Meherpur">Meherpur</option>
                                        <option value="Narail">Narail</option>
                                        <option value="Satkhira">Satkhira</option>
                                    </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="19" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-permanent_city-container"><span class="select2-selection__rendered" id="select2-permanent_city-container" role="textbox" aria-readonly="true" title="Please Select One">Please Select One</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ref_mobile" class="col-sm-5 col-form-label">Reference Mobile </label>
                                <div class="col-sm-7">
                                    <input name="ref_mobile" class="form-control" type="number" placeholder="Reference Mobile" id="ref_mobile">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="picture" class="col-sm-5 col-form-label">Photograph </label>
                                <div class="col-sm-7">
                                    <input type="file" accept="image/*" name="picture" onchange="loadFile(event)">
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="reset" class="btn btn-primary w-md m-b-5">Reset</button>
                                <button type="submit" class="btn btn-success w-md m-b-5">Add</button>
                            </div>
                        </div>
                    </form> </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-header">
                    <h4>Search Here<small class="float-right">
                            <button type="button" class="btn btn-primary btn-md" data-target="#add0" data-toggle="modal"><i class="ti-plus" aria-hidden="true"></i>
                                Add Employee</button>
                        </small></h4>
                </div>
                <div class="card-body">
                    <form action="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/index" class="form-inline row" id="validate" method="post" accept-charset="utf-8">
                        <div class="col-sm-12 col-xl-4">
                            <div class="form-group row mb-1">
                                <label for="emp_type" class="col-sm-5 col-form-label justify-content-start text-left">Employee Type </label>
                                <div class="col-sm-7">
                                    <select class="form-control basic-single select2-hidden-accessible" name="emp_types" id="emp_types" data-select2-id="emp_types" tabindex="-1" aria-hidden="true">
                                        <option value="" selected="selected" data-select2-id="22">Please Select One</option>
                                        <option value="Internal">Internal </option>
                                        <option value="External">External </option>
                                    </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="21" style="width: 161px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-emp_types-container"><span class="select2-selection__rendered" id="select2-emp_types-container" role="textbox" aria-readonly="true" title="Please Select One">Please Select One</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label for="blood" class="col-sm-5 col-form-label justify-content-start text-left">Blood Group </label>
                                <div class="col-sm-7">
                                    <select class="form-control basic-single select2-hidden-accessible" name="shbloodg" id="shbloodg" data-select2-id="shbloodg" tabindex="-1" aria-hidden="true">
                                        <option value="" selected="selected" data-select2-id="24">Please Select One</option>
                                        <option value="B+">B+ </option>
                                        <option value="A-">A- </option>
                                        <option value=""> </option>
                                        <option value="A+">A+ </option>
                                        <option value="O+">O+ </option>
                                    </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="23" style="width: 161px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-shbloodg-container"><span class="select2-selection__rendered" id="select2-shbloodg-container" role="textbox" aria-readonly="true" title="Please Select One">Please Select One</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xl-4">
                            <div class="form-group row mb-1">
                                <label for="department" class="col-sm-5 col-form-label justify-content-start text-left">Department <i class="text-danger">*</i></label>
                                <div class="col-sm-7">
                                    <select class="form-control basic-single select2-hidden-accessible" required="" name="departmentsh" id="departmentsh" data-select2-id="departmentsh" tabindex="-1" aria-hidden="true">
                                        <option value="" selected="selected" data-select2-id="26">Please Select One</option>
                                        <option value="Accounting">
                                            Accounting</option>
                                        <option value="Human Resource">
                                            Human Resource</option>
                                        <option value="Marketing &amp; Sales">
                                            Marketing &amp; Sales</option>
                                        <option value="Technical">
                                            Technical</option>
                                        <option value="mmkmk">
                                            mmkmk</option>
                                    </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="25" style="width: 161px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-departmentsh-container"><span class="select2-selection__rendered" id="select2-departmentsh-container" role="textbox" aria-readonly="true" title="Please Select One">Please Select One</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label for="designation" class="col-sm-5 col-form-label justify-content-start text-left">Designation <i class="text-danger">*</i></label>
                                <div class="col-sm-7">
                                    <select class="form-control basic-single select2-hidden-accessible" required="" name="designationsh" id="designationsh" data-select2-id="designationsh" tabindex="-1" aria-hidden="true">
                                        <option value="" selected="selected" data-select2-id="28">Please Select One</option>
                                        <option value="Supervisor">Supervisor </option>
                                        <option value="Manager">Manager </option>
                                        <option value="Accounts">Accounts </option>
                                        <option value="Driver">Driver </option>
                                        <option value="Helper">Helper </option>
                                        <option value="Receptionist">Receptionist </option>
                                        <option value="Palero">Palero </option>
                                    </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="27" style="width: 161px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-designationsh-container"><span class="select2-selection__rendered" id="select2-designationsh-container" role="textbox" aria-readonly="true" title="Please Select One">Please Select One</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xl-4">
                            <div class="row">
                                <div class="col-sm-12 col-xl-12">
                                    <div class="form-group row mb-1">
                                        <label for="join_datefrsh" class="col-sm-5 col-form-label justify-content-start text-left">Joining Date From </label>
                                        <div class="col-sm-7">
                                            <input name="join_datefrsh" autocomplete="off" class="form-control newdatetimepicker  w-100" type="text" placeholder="Joining Date From" id="join_datefrsh">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xl-12">
                                    <div class="form-group row mb-1">
                                        <label for="joining_d_to" class="col-sm-5 col-form-label justify-content-start text-left">Joining Date To </label>
                                        <div class="col-sm-7">
                                            <input name="joining_d_to" autocomplete="off" class="form-control newdatetimepicker w-100" type="text" placeholder="Joining Date To" id="joining_d_to">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group row  mb-1">
                                <label for="joining_d_to" class="col-sm-5 col-form-label">&nbsp;</label>
                                <div class="col-sm-7 text-right">
                                    <button type="button" class="btn btn-success" id="btn-filter">Search</button>&nbsp;
                                    <button type="button" class="btn btn-inverse" id="btn-reset">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form> </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-header p-2">
                    <h4 class="pl-3">Manage Employee</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="empsear_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer"><div class="row"><div class="col-sm-12 col-md-6"><div class="dataTables_length" id="empsear_length"><label>Show <select name="empsear_length" aria-controls="empsear" class="custom-select custom-select-sm form-control form-control-sm"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> entries</label></div><div class="dt-buttons btn-group">          <button class="btn btn-secondary buttons-copy buttons-html5 btn-success" tabindex="0" aria-controls="empsear" type="button"><span>Copy</span></button> <button class="btn btn-secondary buttons-excel buttons-html5 btn-success" tabindex="0" aria-controls="empsear" type="button"><span>Excel</span></button> <button class="btn btn-secondary buttons-pdf buttons-html5 btn-success" tabindex="0" aria-controls="empsear" type="button"><span>PDF</span></button> <button class="btn btn-secondary buttons-print btn-success" tabindex="0" aria-controls="empsear" type="button"><span>Print</span></button> <div class="btn-group"><button class="btn btn-secondary buttons-collection dropdown-toggle buttons-colvis btn-success" tabindex="0" aria-controls="empsear" type="button" aria-haspopup="true" aria-expanded="false"><span>Column visibility</span></button></div> </div></div><div class="col-lg-6 col-md-12"><div id="empsear_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="empsear"></label></div></div></div><div class="row"><div class="col-sm-12"><table id="empsear" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" role="grid" aria-describedby="empsear_info" style="width: 1546px;">
                                        <thead>
                                        <tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="empsear" rowspan="1" colspan="1" style="width: 79px;" aria-sort="ascending" aria-label="SL: activate to sort column descending">SL</th><th class="sorting" tabindex="0" aria-controls="empsear" rowspan="1" colspan="1" style="width: 195px;" aria-label="Name: activate to sort column ascending">Name</th><th class="sorting" tabindex="0" aria-controls="empsear" rowspan="1" colspan="1" style="width: 280px;" aria-label="NID: activate to sort column ascending">NID</th><th class="sorting" tabindex="0" aria-controls="empsear" rowspan="1" colspan="1" style="width: 110px;" aria-label="Type: activate to sort column ascending">Type</th><th class="sorting" tabindex="0" aria-controls="empsear" rowspan="1" colspan="1" style="width: 222px;" aria-label="Department: activate to sort column ascending">Department</th><th class="sorting" tabindex="0" aria-controls="empsear" rowspan="1" colspan="1" style="width: 211px;" aria-label="Designation: activate to sort column ascending">Designation</th><th class="sorting" tabindex="0" aria-controls="empsear" rowspan="1" colspan="1" style="width: 190px;" aria-label="Mobile: activate to sort column ascending">Mobile</th><th class="sorting" tabindex="0" aria-controls="empsear" rowspan="1" colspan="1" style="width: 0px; display: none;" aria-label="Action: activate to sort column ascending">Action</th></tr>
                                        </thead>
                                        <tbody>
                                        <tr role="row" class="odd"><td class="sorting_1" tabindex="0">1</td><td>Jubaer Hossain</td><td>8091771322870011</td><td>Internal</td><td>Human Resource</td><td>Supervisor</td><td>01738465735</td><td style="display: none;"><a href="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/update_employee_form/E1RTC20Y" class="btn btn-xs btn-success btn-sm mr-1"><i class="ti-pencil"></i></a><a href="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/delete_employhistory/E1RTC20Y" onclick="return confirm('Are you sure ?') " class="btn btn-xs btn-danger btn-sm mr-1"><i class="ti-trash"></i></a></td></tr><tr role="row" class="even"><td class="sorting_1" tabindex="0">2</td><td>Rashid</td><td>5551177244338801</td><td>External</td><td>Human Resource</td><td>Driver</td><td>01923001234</td><td style="display: none;"><a href="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/update_employee_form/E0CRB403" class="btn btn-xs btn-success btn-sm mr-1"><i class="ti-pencil"></i></a><a href="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/delete_employhistory/E0CRB403" onclick="return confirm('Are you sure ?') " class="btn btn-xs btn-danger btn-sm mr-1"><i class="ti-trash"></i></a></td></tr><tr role="row" class="odd"><td class="sorting_1" tabindex="0">3</td><td>Al Amin</td><td>0214253645674577</td><td>External</td><td>Human Resource</td><td>Supervisor</td><td>01738465735</td><td style="display: none;"><a href="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/update_employee_form/EKDXW58G" class="btn btn-xs btn-success btn-sm mr-1"><i class="ti-pencil"></i></a><a href="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/delete_employhistory/EKDXW58G" onclick="return confirm('Are you sure ?') " class="btn btn-xs btn-danger btn-sm mr-1"><i class="ti-trash"></i></a></td></tr><tr role="row" class="even"><td class="sorting_1" tabindex="0">4</td><td>Kamrul</td><td>987214253667854</td><td>External</td><td>ACCOUNTING</td><td>Supervisor</td><td>01738465711</td><td style="display: none;"><a href="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/update_employee_form/ETMYQ36Y" class="btn btn-xs btn-success btn-sm mr-1"><i class="ti-pencil"></i></a><a href="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/delete_employhistory/ETMYQ36Y" onclick="return confirm('Are you sure ?') " class="btn btn-xs btn-danger btn-sm mr-1"><i class="ti-trash"></i></a></td></tr><tr role="row" class="odd"><td class="sorting_1" tabindex="0">5</td><td>Test Employee</td><td>53453434</td><td>External</td><td>ACCOUNTING</td><td>Supervisor</td><td>01785522541</td><td style="display: none;"><a href="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/update_employee_form/EDWWDMAV" class="btn btn-xs btn-success btn-sm mr-1"><i class="ti-pencil"></i></a><a href="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/delete_employhistory/EDWWDMAV" onclick="return confirm('Are you sure ?') " class="btn btn-xs btn-danger btn-sm mr-1"><i class="ti-trash"></i></a></td></tr><tr role="row" class="even"><td class="sorting_1" tabindex="0">6</td><td>abc</td><td>657657567</td><td>External</td><td>ACCOUNTING</td><td>Manager</td><td>12345678</td><td style="display: none;"><a href="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/update_employee_form/EJ5MOH4S" class="btn btn-xs btn-success btn-sm mr-1"><i class="ti-pencil"></i></a><a href="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/delete_employhistory/EJ5MOH4S" onclick="return confirm('Are you sure ?') " class="btn btn-xs btn-danger btn-sm mr-1"><i class="ti-trash"></i></a></td></tr><tr role="row" class="odd"><td class="sorting_1" tabindex="0">7</td><td>taslimul</td><td>765757657</td><td>Internal</td><td>Human Resource</td><td>Manager</td><td>12345678</td><td style="display: none;"><a href="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/update_employee_form/ECN3UOZ8" class="btn btn-xs btn-success btn-sm mr-1"><i class="ti-pencil"></i></a><a href="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/delete_employhistory/ECN3UOZ8" onclick="return confirm('Are you sure ?') " class="btn btn-xs btn-danger btn-sm mr-1"><i class="ti-trash"></i></a></td></tr><tr role="row" class="even"><td class="sorting_1" tabindex="0">8</td><td>dsfdf</td><td>4565646</td><td>External</td><td>Human Resource</td><td>Supervisor</td><td>6456546</td><td style="display: none;"><a href="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/update_employee_form/ESQ0BXI9" class="btn btn-xs btn-success btn-sm mr-1"><i class="ti-pencil"></i></a><a href="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/delete_employhistory/ESQ0BXI9" onclick="return confirm('Are you sure ?') " class="btn btn-xs btn-danger btn-sm mr-1"><i class="ti-trash"></i></a></td></tr><tr role="row" class="odd"><td class="sorting_1" tabindex="0">9</td><td>demo2</td><td>56465656</td><td>Internal</td><td>Human Resource</td><td>Manager</td><td>645645546</td><td style="display: none;"><a href="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/update_employee_form/E62WYC4J" class="btn btn-xs btn-success btn-sm mr-1"><i class="ti-pencil"></i></a><a href="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/delete_employhistory/E62WYC4J" onclick="return confirm('Are you sure ?') " class="btn btn-xs btn-danger btn-sm mr-1"><i class="ti-trash"></i></a></td></tr><tr role="row" class="even"><td class="sorting_1" tabindex="0">10</td><td>Rahim</td><td>4567</td><td>External</td><td>Technical</td><td>Manager</td><td>346567678</td><td style="display: none;"><a href="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/update_employee_form/EODSVEIF" class="btn btn-xs btn-success btn-sm mr-1"><i class="ti-pencil"></i></a><a href="https://vmsdemo.bdtask-demo.com/employeeManagement/Employees/delete_employhistory/EODSVEIF" onclick="return confirm('Are you sure ?') " class="btn btn-xs btn-danger btn-sm mr-1"><i class="ti-trash"></i></a></td></tr></tbody>
                                    </table><div id="empsear_processing" class="dataTables_processing card" style="display: none;">Processing...</div></div></div><div class="row"><div class="col-sm-12 col-md-5"><div class="dataTables_info" id="empsear_info" role="status" aria-live="polite">Showing 1 to 10 of 10 entries</div></div><div class="col-sm-12 col-md-7"><div class="dataTables_paginate paging_simple_numbers" id="empsear_paginate"><ul class="pagination"><li class="paginate_button page-item previous disabled" id="empsear_previous"><a href="#" aria-controls="empsear" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li><li class="paginate_button page-item active"><a href="#" aria-controls="empsear" data-dt-idx="1" tabindex="0" class="page-link">1</a></li><li class="paginate_button page-item next disabled" id="empsear_next"><a href="#" aria-controls="empsear" data-dt-idx="2" tabindex="0" class="page-link">Next</a></li></ul></div></div></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://vmsdemo.bdtask-demo.com/assets/dist/js/employee_view.js"></script>
</div>
