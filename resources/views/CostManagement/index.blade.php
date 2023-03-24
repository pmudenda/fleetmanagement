

@extends('layouts.app')
@section('content')
    <div class="content-header row align-items-center m-0" id="bedcumb">
        <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
            <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
                <li class="breadcrumb-item">
                    <a href="https://vmsdemo.bdtask-demo.com/dashboard/home">Home</a></li>
                <li id="moduleName" class="breadcrumb-item active">
                    Dashboard
                </li>
            </ol>
        </nav>
        <div class="col-sm-8 header-title p-0">
            <div class="media">
                <div class="header-icon text-success mr-3"><i class="typcn typcn-spiral"></i></div>
                <div class="media-body">
                    <h1 class="font-weight-bold" id="moduleName1">Dashboard</h1>
                    <small id="controllerName"></small>
                </div>
            </div>
        </div>
    </div>

    <div class="body-content" id="bodycontent">
        <div id="add0" class="modal fade bd-example-modal-lg" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <strong>Add New Expense</strong>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body">
                        <form action="https://vmsdemo.bdtask-demo.com/costManagement/costManagement/add_exptype"
                              id="emp_form" class="row" enctype="multipart/form-data" method="post"
                              accept-charset="utf-8">
                            <div class="col-md-12 col-lg-12">
                                <div class="form-group row">
                                    <label for="expense_name" class="col-sm-3 col-form-label">Expense Name <i
                                            class="text-danger">*</i></label>
                                    <div class="col-sm-5">
                                        <input name="expense_name" required="" class="form-control" type="text"
                                               placeholder="Expense Name" id="expense_name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="expence_cat" class="col-sm-3 col-form-label">Expense category <i
                                            class="text-danger">*</i></label>
                                    <div class="col-sm-9">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="fuel" name="expence_cat"
                                                   class="custom-control-input" value="Fuel">
                                            <label class="custom-control-label" for="fuel">Fuel</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="maintenance" name="expence_cat"
                                                   class="custom-control-input" value="Maintenance">
                                            <label class="custom-control-label"
                                                   for="maintenance">Maintenance</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="other" name="expence_cat"
                                                   class="custom-control-input" value="Other">
                                            <label class="custom-control-label" for="other">Other</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <button type="reset" class="btn btn-primary w-md m-b-5">Reset</button>
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
                        <strong>Update Expense Type</strong>
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
                        <h4 class="pl-3">Manage Expense Type<small class="float-right">
                                <button type="button" class="btn btn-primary btn-md" data-target="#add0"
                                        data-toggle="modal"><i class="ti-plus" aria-hidden="true"></i>
                                    Add New Expense
                                </button> &nbsp;
                                <a href="https://vmsdemo.bdtask-demo.com/costManagement/costManagement/add_expanselist"
                                   class="btn btn-primary btn-md"><i class="ti-plus" aria-hidden="true"></i>
                                    Add Expense</a>&nbsp;
                                <a href="https://vmsdemo.bdtask-demo.com/costManagement/costManagement/allexpenselist"
                                   class="btn btn-primary btn-md"><i class="ti-plus" aria-hidden="true"></i>
                                    Expense List</a>
                            </small></h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table display table-bordered table-striped table-hover ">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Expense Group</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Tyres</td>
                                    <td>Maintenance</td>
                                    <td>
                                        <input name="url" type="hidden" id="url_11"
                                               value="https://vmsdemo.bdtask-demo.com/costManagement/costManagement/updateexptfrm">
                                        <a onclick="editinfo(11)"
                                           class="btn btn-xs btn-success btn-sm mr-1 text-white"
                                           data-toggle="tooltip" data-placement="left" title="Update"><i
                                                class="ti-pencil"></i></a>
                                        <a href="https://vmsdemo.bdtask-demo.com/costManagement/costManagement/delete_exptype/11"
                                           onclick="return confirm('Are you sure ?') "
                                           class="btn btn-xs btn-danger btn-sm mr-1"><i
                                                class="ti-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>partner</td>
                                    <td>Maintenance</td>
                                    <td>
                                        <input name="url" type="hidden" id="url_10"
                                               value="https://vmsdemo.bdtask-demo.com/costManagement/costManagement/updateexptfrm">
                                        <a onclick="editinfo(10)"
                                           class="btn btn-xs btn-success btn-sm mr-1 text-white"
                                           data-toggle="tooltip" data-placement="left" title="Update"><i
                                                class="ti-pencil"></i></a>
                                        <a href="https://vmsdemo.bdtask-demo.com/costManagement/costManagement/delete_exptype/10"
                                           onclick="return confirm('Are you sure ?') "
                                           class="btn btn-xs btn-danger btn-sm mr-1"><i
                                                class="ti-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>dfghfhfgh</td>
                                    <td>Maintenance</td>
                                    <td>
                                        <input name="url" type="hidden" id="url_9"
                                               value="https://vmsdemo.bdtask-demo.com/costManagement/costManagement/updateexptfrm">
                                        <a onclick="editinfo(9)"
                                           class="btn btn-xs btn-success btn-sm mr-1 text-white"
                                           data-toggle="tooltip" data-placement="left" title="Update"><i
                                                class="ti-pencil"></i></a>
                                        <a href="https://vmsdemo.bdtask-demo.com/costManagement/costManagement/delete_exptype/9"
                                           onclick="return confirm('Are you sure ?') "
                                           class="btn btn-xs btn-danger btn-sm mr-1"><i
                                                class="ti-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>other</td>
                                    <td>Maintenance</td>
                                    <td>
                                        <input name="url" type="hidden" id="url_6"
                                               value="https://vmsdemo.bdtask-demo.com/costManagement/costManagement/updateexptfrm">
                                        <a onclick="editinfo(6)"
                                           class="btn btn-xs btn-success btn-sm mr-1 text-white"
                                           data-toggle="tooltip" data-placement="left" title="Update"><i
                                                class="ti-pencil"></i></a>
                                        <a href="https://vmsdemo.bdtask-demo.com/costManagement/costManagement/delete_exptype/6"
                                           onclick="return confirm('Are you sure ?') "
                                           class="btn btn-xs btn-danger btn-sm mr-1"><i
                                                class="ti-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Test Expense</td>
                                    <td>Maintenance</td>
                                    <td>
                                        <input name="url" type="hidden" id="url_5"
                                               value="https://vmsdemo.bdtask-demo.com/costManagement/costManagement/updateexptfrm">
                                        <a onclick="editinfo(5)"
                                           class="btn btn-xs btn-success btn-sm mr-1 text-white"
                                           data-toggle="tooltip" data-placement="left" title="Update"><i
                                                class="ti-pencil"></i></a>
                                        <a href="https://vmsdemo.bdtask-demo.com/costManagement/costManagement/delete_exptype/5"
                                           onclick="return confirm('Are you sure ?') "
                                           class="btn btn-xs btn-danger btn-sm mr-1"><i
                                                class="ti-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Landscape architect</td>
                                    <td>Maintenance</td>
                                    <td>
                                        <input name="url" type="hidden" id="url_4"
                                               value="https://vmsdemo.bdtask-demo.com/costManagement/costManagement/updateexptfrm">
                                        <a onclick="editinfo(4)"
                                           class="btn btn-xs btn-success btn-sm mr-1 text-white"
                                           data-toggle="tooltip" data-placement="left" title="Update"><i
                                                class="ti-pencil"></i></a>
                                        <a href="https://vmsdemo.bdtask-demo.com/costManagement/costManagement/delete_exptype/4"
                                           onclick="return confirm('Are you sure ?') "
                                           class="btn btn-xs btn-danger btn-sm mr-1"><i
                                                class="ti-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>Tips</td>
                                    <td>Fuel</td>
                                    <td>
                                        <input name="url" type="hidden" id="url_3"
                                               value="https://vmsdemo.bdtask-demo.com/costManagement/costManagement/updateexptfrm">
                                        <a onclick="editinfo(3)"
                                           class="btn btn-xs btn-success btn-sm mr-1 text-white"
                                           data-toggle="tooltip" data-placement="left" title="Update"><i
                                                class="ti-pencil"></i></a>
                                        <a href="https://vmsdemo.bdtask-demo.com/costManagement/costManagement/delete_exptype/3"
                                           onclick="return confirm('Are you sure ?') "
                                           class="btn btn-xs btn-danger btn-sm mr-1"><i
                                                class="ti-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>Comission</td>
                                    <td>Fuel</td>
                                    <td>
                                        <input name="url" type="hidden" id="url_2"
                                               value="https://vmsdemo.bdtask-demo.com/costManagement/costManagement/updateexptfrm">
                                        <a onclick="editinfo(2)"
                                           class="btn btn-xs btn-success btn-sm mr-1 text-white"
                                           data-toggle="tooltip" data-placement="left" title="Update"><i
                                                class="ti-pencil"></i></a>
                                        <a href="https://vmsdemo.bdtask-demo.com/costManagement/costManagement/delete_exptype/2"
                                           onclick="return confirm('Are you sure ?') "
                                           class="btn btn-xs btn-danger btn-sm mr-1"><i
                                                class="ti-trash"></i></a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

<div id="toTop" class="btn-top" style="display: none;"><i class="ti-upload"></i></div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected">12:00 AM - 12:00 AM</span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected">12:00 AM - 12:00 AM</span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
<div class="daterangepicker ltr single auto-apply opensright">
    <div class="ranges"></div>
    <div class="drp-calendar left single" style="display: block;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-calendar right" style="display: none;">
        <div class="calendar-table"></div>
        <div class="calendar-time" style="display: none;"></div>
    </div>
    <div class="drp-buttons"><span class="drp-selected"></span>
        <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button>
        <button class="applyBtn btn btn-sm btn-primary" disabled="disabled" type="button">Apply</button>
    </div>
</div>
</body>
</html>
