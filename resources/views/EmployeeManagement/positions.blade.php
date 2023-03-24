<div class="body-content" id="bodycontent"><div id="add0" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <strong> position list</strong>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <form action="https://vmsdemo.bdtask-demo.com/employeeManagement/employees/create_position" method="post" accept-charset="utf-8">
                                <div class="form-group row">
                                    <label for="position_name" class="col-sm-3 col-form-label">Position Name <i class="text-danger">*</i></label>
                                    <div class="col-sm-9">
                                        <input name="position_name" required="" class="form-control" type="text" placeholder="Position Name" id="position_name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="position_details" class="col-sm-3 col-form-label">Details <i class="text-danger">*</i></label>
                                    <div class="col-sm-9">
                                        <textarea name="position_details" required="" class="form-control" placeholder="Details" id="position_details"></textarea>
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <button type="reset" class="btn btn-primary w-md m-b-5">Reset</button>
                                    <button type="submit" class="btn btn-success w-md m-b-5">Add</button>
                                </div>
                            </form> </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4>Position List Details<small class="float-right">
                            <button type="button" class="btn btn-primary btn-md" data-target="#add0" data-toggle="modal">
                                <i class="ti-plus" aria-hidden="true"></i>
                                Add Position</button>
                            <a href="https://vmsdemo.bdtask-demo.com/employeeManagement/employees/position_view" class="btn btn-primary">Manage Position</a>
                        </small></h4>
                </div>
                <div class="card-body">
                    <table width="100%" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Position Name</th>
                            <th>Details</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="odd gradeX">
                            <td>1</td>
                            <td>Palero</td>
                            <td>Test Palero</td>
                        </tr>
                        <tr class="even gradeC">
                            <td>2</td>
                            <td>Receptionist</td>
                            <td>Test Receptionist</td>
                        </tr>
                        <tr class="odd gradeX">
                            <td>3</td>
                            <td>Helper</td>
                            <td>Test Helper</td>
                        </tr>
                        <tr class="even gradeC">
                            <td>4</td>
                            <td>Driver</td>
                            <td></td>
                        </tr>
                        <tr class="odd gradeX">
                            <td>5</td>
                            <td>Accounts</td>
                            <td>Play a key role in every restaurant. </td>
                        </tr>
                        <tr class="even gradeC">
                            <td>6</td>
                            <td>Manager</td>
                            <td>Recruits and hires qualified employees, creates in-house job-training programs, and assists employees with their career needs.</td>
                        </tr>
                        <tr class="odd gradeX">
                            <td>7</td>
                            <td>Supervisor</td>
                            <td>Responsible for the pastry shop in a foodservice establishment. Ensures that the products produced in the pastry shop meet the quality standards in conjunction with the executive chef.</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://vmsdemo.bdtask-demo.com/assets/dist/js/position_form.js"></script></div>
