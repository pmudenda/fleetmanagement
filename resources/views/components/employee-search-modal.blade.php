<div class="modal fade" id="searchEmployeeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div style="padding: 0.2rem 1rem !important;"
                class="modal-header ui-dialog-titlebar ui-corner-all ui-widget-header ui-helper-clearfix ui-draggable-handle">
                <h4 id="employeeModalTitle" class="modal-title text-center">Search Employee</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- form start -->
            <form role="form" id="userSearch" style="margin-bottom: 0;" method="post" action="{{route('user.search')}}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="searchCriteria">Search</label>
                                <div class="input-group">
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           id="searchCriteria"
                                           name="searchCriteria"
                                           placeholder="Enter staff number" required>
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-sm btn-primary border-radius-0">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="errorMsg"></div>
                            <!-- Image loader -->
                            <div id="loader_inv" class="text-center d-none">
                                <img src="{{ asset('assets/gif/Eclipse_loading.gif')}}"
                                     width="100px"
                                     alt="Loader"
                                     height="100px"/>
                            </div>
                            <div class="hideWhenLoading justify-content-between mb-3" id="actionBtnContainer"></div>
                            <div id="users"
                                 class="hideWhenLoading"
                                 style="max-height: 300px;
                                 overflow-y: auto;">
                            </div>
                            <div class="hideWhenLoading justify-content-between mb-3 pull-right"
                                 id="secondActionBtnContainer"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 0.2rem !important;">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
