<div class="body-content" id="bodycontent"><div id="add0" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <strong>Add Recurring Period</strong>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <form action="https://vmsdemo.bdtask-demo.com/setting/Setting/add_period" id="company" class="row" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group row">
                                <label for="recurring_periode_name" class="col-sm-5 col-form-label">Recurring Period Name <i class="text-danger">*</i></label>
                                <div class="col-sm-7">
                                    <input name="recurring_periode_name" required="" class="form-control" type="text" placeholder="Recurring Period Name" id="recurring_periode_name">
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
    <div id="edit" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <strong>Update Recurring Period</strong>
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
                    <h4 class="pl-3">Manege Recurring Period<small class="float-right">
                            <button type="button" class="btn btn-primary btn-md" data-target="#add0" data-toggle="modal"><i class="ti-plus" aria-hidden="true"></i>
                                Add Recurring Period</button>
                        </small></h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="example_recurringperiod_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer"><div class="row"><div class="col-sm-12 col-md-6"><div class="dt-buttons btn-group">          <button class="btn btn-secondary buttons-copy buttons-html5 btn-success" tabindex="0" aria-controls="example_recurringperiod" type="button"><span>Copy</span></button> <button class="btn btn-secondary buttons-excel buttons-html5 btn-success" tabindex="0" aria-controls="example_recurringperiod" type="button"><span>Excel</span></button> <button class="btn btn-secondary buttons-pdf buttons-html5 btn-success" tabindex="0" aria-controls="example_recurringperiod" type="button"><span>PDF</span></button> <button class="btn btn-secondary buttons-print btn-success" tabindex="0" aria-controls="example_recurringperiod" type="button"><span>Print</span></button> <div class="btn-group"><button class="btn btn-secondary buttons-collection dropdown-toggle buttons-colvis btn-success" tabindex="0" aria-controls="example_recurringperiod" type="button" aria-haspopup="true" aria-expanded="false"><span>Column visibility</span></button></div> </div></div><div class="col-lg-6 col-md-12"><div id="example_recurringperiod_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="example_recurringperiod"></label></div></div></div><div class="row"><div class="col-sm-12"><table id="example_recurringperiod" class="table display table-bordered table-striped table-hover dataTable no-footer" role="grid" aria-describedby="example_recurringperiod_info">
                                        <thead>
                                        <tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="example_recurringperiod" rowspan="1" colspan="1" aria-sort="ascending" aria-label="SL: activate to sort column descending" style="width: 217.219px;">SL</th><th class="sorting" tabindex="0" aria-controls="example_recurringperiod" rowspan="1" colspan="1" aria-label="Recurring Period Name: activate to sort column ascending" style="width: 850.016px;">Recurring Period Name</th><th class="sorting" tabindex="0" aria-controls="example_recurringperiod" rowspan="1" colspan="1" aria-label="Action: activate to sort column ascending" style="width: 378.766px;">Action</th></tr>
                                        </thead>
                                        <tbody>



                                        <tr role="row" class="odd">
                                            <td class="sorting_1">1</td>
                                            <td>15 Days</td>
                                            <td>
                                                <input name="url" type="hidden" id="url_1" value="https://vmsdemo.bdtask-demo.com/setting/Setting/updateperiodfrm">
                                                <a onclick="editinfo(1)" class="btn btn-xs btn-success btn-sm mr-1 text-white" data-toggle="tooltip" data-placement="left" title="Update"><i class="ti-pencil"></i></a>
                                                <a href="https://vmsdemo.bdtask-demo.com/setting/Setting/delete_period/1" onclick="return confirm('Are you sure ?') " class="btn btn-xs btn-danger btn-sm mr-1"><i class="ti-trash"></i></a>
                                            </td>
                                        </tr><tr role="row" class="even">
                                            <td class="sorting_1">2</td>
                                            <td>1 Month</td>
                                            <td>
                                                <input name="url" type="hidden" id="url_2" value="https://vmsdemo.bdtask-demo.com/setting/Setting/updateperiodfrm">
                                                <a onclick="editinfo(2)" class="btn btn-xs btn-success btn-sm mr-1 text-white" data-toggle="tooltip" data-placement="left" title="Update"><i class="ti-pencil"></i></a>
                                                <a href="https://vmsdemo.bdtask-demo.com/setting/Setting/delete_period/2" onclick="return confirm('Are you sure ?') " class="btn btn-xs btn-danger btn-sm mr-1"><i class="ti-trash"></i></a>
                                            </td>
                                        </tr><tr role="row" class="odd">
                                            <td class="sorting_1">3</td>
                                            <td>1 Year</td>
                                            <td>
                                                <input name="url" type="hidden" id="url_3" value="https://vmsdemo.bdtask-demo.com/setting/Setting/updateperiodfrm">
                                                <a onclick="editinfo(3)" class="btn btn-xs btn-success btn-sm mr-1 text-white" data-toggle="tooltip" data-placement="left" title="Update"><i class="ti-pencil"></i></a>
                                                <a href="https://vmsdemo.bdtask-demo.com/setting/Setting/delete_period/3" onclick="return confirm('Are you sure ?') " class="btn btn-xs btn-danger btn-sm mr-1"><i class="ti-trash"></i></a>
                                            </td>
                                        </tr></tbody>
                                    </table></div></div><div class="row"><div class="col-sm-12 col-md-5"><div class="dataTables_info" id="example_recurringperiod_info" role="status" aria-live="polite">Showing 1 to 3 of 3 entries</div></div><div class="col-sm-12 col-md-7"><div class="dataTables_paginate paging_simple_numbers" id="example_recurringperiod_paginate"><ul class="pagination"><li class="paginate_button page-item previous disabled" id="example_recurringperiod_previous"><a href="#" aria-controls="example_recurringperiod" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li><li class="paginate_button page-item active"><a href="#" aria-controls="example_recurringperiod" data-dt-idx="1" tabindex="0" class="page-link">1</a></li><li class="paginate_button page-item next disabled" id="example_recurringperiod_next"><a href="#" aria-controls="example_recurringperiod" data-dt-idx="2" tabindex="0" class="page-link">Next</a></li></ul></div></div></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://vmsdemo.bdtask-demo.com/assets/dist/js/recurringperiod_list.js"></script></div>
