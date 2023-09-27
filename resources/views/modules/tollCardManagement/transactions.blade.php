@php use Carbon\Carbon; @endphp
@extends('layouts.app')


@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <style>
        /**===NO WRAP ON TABLE =====**/
        table.dataTable.nowrap th,
        table.dataTable.nowrap td {
            white-space: nowrap;
        }

    </style>
    <style>
        #fileSelect {
            background-color: #0c63ce;
            color: white;
            padding: 0.5rem;
            font-family: sans-serif;
            border-radius: 0.3rem;
            cursor: pointer;
            margin-top: 1rem;
            width: 100%;
            display: none;
        }

        #file-chosen {
            margin-left: 0.3rem;
            font-family: sans-serif;
        }
    </style>
@endpush


@section('content')

    <x-content-header :pageTitle="'eToll Card'" :activeCrumb="'eToll Cards'" :link="'e-toll.card.list'"
                      :linkText="'eToll Card Transaction'"/>

    <!-- Main content -->
    <section class="content">
        <x-error-view/>
        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <div class="col-md-12 pl-0">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h4>e-Toll Cards Transactions</h4>
                            </div>
                            <div class="card-toolbar justify-content-end">
                                <button type="button"
                                        id="saveTransactions"
                                        class="btn btn-sm btn-success me-3"
                                        data-menu-trigger="click"
                                        data-menu-placement="bottom-end">
                                <span class="svg-icon svg-icon-2">
                                    <i class="fas fa-save"></i>
                                </span>
                                    Save
                                </button>
                                <!--begin::Filter-->
                            </div>
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive" style="max-height:500px; min-width: fit-content">
                                <table class="table table-striped table-bordered table-numbered dataTable nowrap"
                                       id="part1"
                                       data-url="{{route('e-toll.card.save.transactions')}}"
                                       data-model-name="eTollCards">
                                    <thead>
                                    <tr>
                                        <th>Batch ID</th>
                                        <th>Scheme</th>
                                        <th>Assigned Distributor</th>
                                        <th class="text-center">Card Number</th>
                                        <th class="text-center">Expiry Date</th>
                                        <th class="text-center">CVV</th>
                                        <th class="text-center">Current Value</th>
                                        <th class="text-center">Card Status</th>
                                        <th class="text-center">First Name</th>
                                        <th class="text-center">Last Name</th>
                                        <th class="text-center">Mobile</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="increment">
                                        <td class="">
                                            <input name="BatchId"
                                                   class="form-control form-control-sm required"
                                                   type="text"/>
                                        </td>
                                        <td class="">
                                            <select name="Scheme"
                                                    required
                                                    data-display-text="Card Scheme"
                                                    class="form-select form-select-sm required">
                                                <option value="Standard Card">Standard Card</option>
                                            </select>
                                        </td>
                                        <td class="">
                                            <input name="AssignedDistributor"
                                                   class="form-control form-control-sm required"
                                                   type="text"/>
                                        </td>
                                        <td class="">
                                            <input name="CardNumber"
                                                   class="form-control form-control-sm required" type="text"/>
                                        </td>
                                        <td class="">
                                            <input name="ExpiryDate"
                                                   class="form-control form-control-sm required"
                                                   type="text"/>
                                        </td>

                                        <td class="">
                                            <input name="Cvv"
                                                   class="form-control form-control-sm" type="text"/>
                                        </td>

                                        <td class="">
                                            <input name="CurrentValue"
                                                   class="form-control form-control-sm input-number required"
                                                   type="text"/>
                                        </td>
                                        <td class="">
                                            <select name="CardStatus"
                                                    class="form-select form-select-sm"
                                                    type="text">
                                                <option value="new">NEW</option>
                                                <option value="Assigned to End User">Assigned to End User</option>
                                                <option value="Inactive">INACTIVE</option>
                                            </select>
                                        </td>
                                        <td class="">
                                            <input name="FirstName"
                                                   class="form-control form-control-sm form-control-sm"
                                                   type="text"/>
                                        </td>
                                        <td class="">
                                            <input name="LastName"
                                                   class="form-control form-control-sm"
                                                   type="text"/>
                                        </td>
                                        <td class="">
                                            <input name="Mobile"
                                                   class="form-control form-control-sm" type="text"/>
                                        </td>
                                        <td class="">
                                            <button value="deleteRow" type="button" class="btn btn-danger p-2">
                                                <i class="fa fa-trash m-0"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                    {{--<tfoot>
                                    <tr>
                                        <td colspan="2" style="text-align: right;">
                                            <b>Total</b>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <b id="quantityTotal" class="input-number">0.00</b>
                                        </td>
                                        <td></td>
                                        <td>
                                            <b id="salesTotal" class="input-number">0.00</b>
                                        </td>
                                        <td>
                                            <b id="licenseFeeTotal" class="input-number">0.00</b>
                                        </td>
                                        <td>
                                            <b id="srfTotal" class="input-number">0.00</b>
                                        </td>
                                        <td>
                                            <b id="fuelMarkingTotal" class="input-number">0.00</b>
                                        </td>
                                        <td>
                                            <b></b>
                                        </td>
                                    </tr>
                                    </tfoot>--}}
                                </table>
                            </div>
                            <hr>
                            <button type="button" data-table-id="part1"
                                    class="btn btn-sm btn-success waves-effect waves-light add" value="addRow">
                                <i class="fa fa-plus"></i> Add Row
                            </button>

                            <button type="button" data-table-id="part1"
                                    class="btn btn-sm btn-primary waves-effect waves-light add" value="bulkUpload">
                                <i class="fa fa-upload"></i> Bulk Upload
                            </button>

                            <button type="button" data-table-id="part1"
                                    class="btn btn-sm btn-danger waves-effect waves-light add" value="clearUpload">
                                <i class="fa fa-trash"></i> Clear Rows
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <div class="modal fade csv-upload" id="csv-upload" tabindex="-1" data-table-id="x">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Excel / CSV Upload</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-b-0">
                    <div class="row">
                        <div class="col-sm-5 col-form-label">
                            <label class="form-control-label">Download Template</label>
                        </div>
                        <div class="col-sm-6">
                        <span class="template-file"
                              style="text-decoration: underline; cursor: pointer; color: #0000dd;">
                            <i class="fa fa-file-excel-o"
                               style="color:green;"></i>&nbsp; <span>Schedule_Template.xlsx</span>
                        </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5 col-form-label">
                            <label class="form-control-label">File To Upload:</label>
                        </div>
                        <div class="col-sm-6">
                            <label id="fileSelect" class="btn btn-outline-primary" for="csvUploadFile">Choose
                                File</label>
                            <input name="csvUpload" id="csvUploadFile" type="file" class="form-control"
                                   accept=".csv,.xls,.xlsx"/>
                            <p class="messages">
                                <span class="text-danger error" data-field="file"></span>
                            </p>
                        </div>
                        <div class="col-12 d-none"><span id="file-chosen">No file chosen</span></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary mr-2" value="csvUpload">
                        <i class="fa fa-upload"></i> Choose File
                    </button>
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade csv-upload-errors" id="csv-upload-errors" tabindex="-1" data-table-id="x">
        <div class="modal-dialog modal-xlg" role="document" style="min-width: 50%;">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title">Errors</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="color:#fff;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-b-0">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Row Number</th>
                            <th>Column</th>
                            <th>Error</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="text-danger">
                            <td>0</td>
                            <td>RecoverableRate</td>
                            <td>Recoverable rate cannot be null or empty</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <!-- DataTables  & Plugins -->
    <script src="{{asset('libs/plugins/sheetjs/xlsx.full.min.js')}}"></script>
    <script src="{{asset('libs/plugins/sheetjs/xlsx.js')}}"></script>
    <script src="{{asset('libs/plugins/sheetjs/csvUploader.js')}}"></script>
    <!-- page script -->
    <script>
        (function (tmsApp) {
            //tmsApp.initDatatable("#listTable", true);
            const csvUploader = new CSVFileUploader({});
            csvUploader.init();

            $('button[value="addRow"][data-table-id]').off('click').on('click', function () {
                let tableId = $(this).data('tableId');
                Table.addRow($('table#' + tableId));
                if (tableId === "part5") {
                    //initInvoiceDatePicker();
                }
            });

            $(document).on('click', '#saveTransactions', function (e) {

                let formSel = $('#part1');
                let formData = {
                    modelName: $(formSel).data('modelName'),
                    submitForm: true
                };
                let arr = [];
                let obj = {};

                $(formSel).find("tbody").children().map(function (index, row) {
                    let obj = {};
                    $(row).find('input[name], select[name]').each(function (i, item) {
                        let val = item.value.replace(/,/g, '');

                        if (item.name === 'endDate' || item.name === 'startDate' || item.name === 'invoiceDate') {
                            let dateField = val;
                            dateField = DateFormatter.format(new Date(moment(val, 'DD/MM/yyyy')), DateFormatter.ISO);

                            obj[item.name] = dateField;
                        } else {
                            obj[item.name] = item.value;
                        }
                    });

                    arr.push(obj);
                });

                formData['items'] = arr;

                formData = {
                    ...obj,
                    ...formData
                }

                $.ajax({
                    type: "POST",
                    url: formSel.data('url'),
                    data: JSON.stringify(formData),
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                }).done(function (response) {
                    window.loaderMessage = "Loading... please wait";
                    if (response.hasOwnProperty("success") && response.success) {
                        const message = response.message > ""
                            ? response.message
                            : "Request submitted successfully, Click 'Ok' to Proceed";

                        tmsApp.showSystemMessage(
                            "Request Submission",
                            message,
                            function () {
                                window.location.reload();
                            },
                            "success"
                        );
                    } else {
                        if (!Util.isEmpty(response.errors)) {
                            if (response.errors) {
                                tmsApp.printErrorMsg(response.errors);
                            }
                        } else if (!Util.isEmpty(response.message)) {
                            tmsApp.systemError("Request Submission", response.message);
                        }
                    }
                }).fail(function (xhr) {
                    tmsApp.showErrorMessages(xhr, "Request Submission");
                });

            })

            $(document).on('click', 'button[value="clearUpload"]', function (e, param) {
                e.preventDefault();
                e.stopPropagation();
                let tableId = $(this).data('tableId');

                tmsApp.confirm(
                    "Are you sure ?",
                    "The data entered will be cleared out, if not saved already, you will not be able to recover it",
                    "Yes",
                    "Cancel",
                    function () {
                        Table.clearRows($('#' + tableId));
                        let table = $(document).find('table#' + tableId);
                        //scheduleUpdater(tableId, table);
                        if (typeof param === 'object' && param.hasOwnProperty('unRequired')) {
                            if (param.unRequired) {
                                Table.removeRequiredAttClass(tableId, table);
                            }
                        }

                        return false;
                    }
                );
            });

            $(document).on('click', 'button[value="deleteRow"]', function (e) {
                e.preventDefault();
                e.stopPropagation();

                let btnEl = $(this);
                let tableId = $(this).closest('table').attr('id');
                let tableRow = btnEl.closest('tr');
                let table = btnEl.closest('table');
                Table.deleteRow(tableRow);

                return false;
            });


        })
        (window.tmsApp || {});
    </script>
@endpush
