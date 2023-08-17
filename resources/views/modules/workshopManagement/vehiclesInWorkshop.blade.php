@php use App\Helpers\StatusHelper;use Carbon\Carbon; @endphp
@extends('layouts.app')


@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <style>
        tbody td {
            border-bottom-width: 0;
            padding: 0.5rem !important;
        }
    </style>
@endpush


@section('content')

    <x-content-header :pageTitle="'Vehicles In Workshop'" :activeCrumb="'Vehicles'" :link="'home'"
                      :linkText="'Vehicles In Workshop'"/>

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
                                <h4>Manage Vehicles In Workshops</h4>
                            </div>
                            <div class="card-toolbar justify-content-end">
                                <!--begin::Filter-->
                                <button type="button" class="btn btn-sm btn-primary me-3"
                                        data-toggle="modal"
                                        data-target="#finderModal"
                                        data-menu-trigger="click"
                                        data-menu-placement="bottom-end">
                                    <span class="svg-icon svg-icon-2">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                    d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                                    fill="currentColor">
                                            </path>
                                        </svg>
                                    </span>
                                    Filter
                                </button>
                                {{--@can(config('rights.create_workshop'))@endcan--}}
                                <a href="{{URL::signedRoute('show.job.card')}}"
                                   class="btn btn-sm btn-success float-right">
                                    <i class="fas fa-user-plus"></i>
                                    Create Job Card
                                </a>

                            </div>
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive mt-10 ">
                                <table id="listTable" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Reg. No.</th>
                                        <th>Workshop Document No.</th>
                                        <th>Job Card Voucher</th>
                                        <th>Workshop</th>
                                        <th>Date Raised</th>
                                        <th>Repair Type</th>
                                        <th>Date Out</th>
                                        {{--@can(config('rights.user_show'))--}}
                                        <th>Action</th>
                                        {{--@endcan--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($workshopsVehicleList as $key => $workshop)
                                        <tr>
                                            <td>
                                                {{-- {{++$key}}--}}
                                                @if(Carbon::now()->isBefore(Carbon::parse($workshop->expected_date_out)))
                                                    <span class="badge badge-success p-2"
                                                          style="width: 20px;height: 20px; border-radius: 50%;">
                                                        <p></p>
                                                    </span>
                                                @elseif(Carbon::now()->isAfter(Carbon::parse($workshop->expected_date_out)))
                                                    <span class="badge badge-danger p-2"
                                                          style="width: 20px;height: 20px; border-radius: 50%;">
                                                        <p></p>
                                                    </span>
                                                @elseif(Carbon::now()->addDays(3)->eq(Carbon::parse($workshop->expected_date_out)))
                                                    <span class="badge badge-warning p-2"
                                                          style="width: 20px;height: 20px; border-radius: 50%;">
                                                        <p></p>
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                {{$workshop->reg_no ?? '--'}}
                                            </td>
                                            <td>
                                                {{$workshop->wshp_act_code ?? ''}}
                                            </td>
                                            <td>
                                                {{$workshop->job_card_no ?? ''}}
                                            </td>
                                            <td>
                                                {{$workshop->workshop_name}}
                                            </td>

                                            <td>
                                                {{$workshop->date_in ?? '--'}}
                                            </td>

                                            <td>
                                                {{$workshop->repair_type_name}}
                                            </td>

                                            <td>
                                                {{$workshop->date_out}}
                                            </td>
                                            {{--<td>
                                               @if($workshop->status == '01')
                                                    Active
                                                @else
                                                    Inactive
                                                @endif
                                            </td>--}}
                                            {{--@can(config('rights.user_show'))--}}
                                            <td>
                                                <div class="dropdown">
                                                    <button
                                                            class="btn btn-light btn-active-light-primary btn-sm dropdown-toggle"
                                                            type="button"
                                                            id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        {{--@can(config('rights.edit_vehicle'))--}}
                                                        <li>
                                                            <a class="dropdown-item"
                                                               data-kt-action="edit"
                                                               href="{{URL::signedRoute('show.job.card',['step'=> '1', 'reference'=>$workshop->job_card_no])}}">
                                                                View Job Card
                                                            </a>
                                                        </li>

                                                        @if($workshop->status != StatusHelper::authorised())
                                                            <li>
                                                                <a class="dropdown-item"
                                                                   data-kt-action="exit"
                                                                   href="{{URL::signedRoute('exit.from.card',['reference'=>$workshop->job_card_no])}}">
                                                                    Exit From Workshop
                                                                </a>
                                                            </li>
                                                        @endif
                                                        {{--@endcan--}}
                                                    </ul>
                                                </div>
                                            </td>
                                            {{-- @endcan--}}
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>

    <!-- The Modal -->
    <div class="modal" id="finderModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">
                        <div class="alert alert-warning" id="query"></div>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <table id="filterProperty" class="table">
                        <tbody>
                        <tr>
                            <td>
                                <select class="form-select" name="property">
                                    <option value="" disabled>--Select--</option>
                                    <option value="userUnit">User Unit</option>
                                    <option value="workshopSection">Section</option>
                                    <option value="workshop">Workshop</option>
                                    <option value="dateIn">Date In</option>
                                    <option value="dateOut">Date Out</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-select" name="operator">
                                    <option value="=">Is</option>
                                    <option value="<>">Is not</option>
                                    <option value=">">Is After</option>
                                    <option value="<">Is Before</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-select" name="filterValue">
                                </select>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <button type="button"
                            data-table-id="filterProperty"
                            class="btn btn-sm btn-primary add pull-left"
                            value="addRow">
                        <i class="fa fa-plus"></i> Add Property
                    </button>
                    <div class="clearfix"></div>
                </div>

                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button"
                            class="btn btn-sm btn-success"
                            value="applyFilter"> Apply Filter
                    </button>
                </div>

            </div>

        </div>
    </div>
    </div>

    {{--<div class="modal fade" id="createRecordModal" tabindex="-1" aria-labelledby="createRecordModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createRecordModalLabel">
                        <span id="modalTitle">Add Workshop</span>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form name="addRecordForm" method="post" action="{{route('save.data')}}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="recordId">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="data_name" class="col-form-label">Name:</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           name="name"
                                           id="data_name" required>
                                    @error('name')
                                    <p class=" errorText">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="data_code" class="col-form-label">Code:</label>
                                    <input type="text"
                                           class="form-control @error('code') is-invalid @enderror"
                                           id="data_code"
                                           name="code" required>
                                    @error('code')
                                    <p class=" errorText">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="area" class="col-form-label">Area:</label>
                                    <select type="text"
                                            class="form-select @error('code') is-invalid @enderror"
                                            id="area"
                                            name="area" required>
                                        @foreach($businessAreas as $businessArea)
                                            <option value="{{$businessArea->area}}">{{$businessArea->description}}
                                                => {{$businessArea->area}}</option>
                                        @endforeach
                                    </select>
                                    @error('code')
                                    <p class=" errorText">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col"></div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="message-text" class="col-form-label">Cost Center:</label>
                                    <select type="text"
                                            class="form-select @error('code') is-invalid @enderror"
                                            id="data_code"
                                            name="code" required>
                                        @foreach($costCenters as $costCenter) @endforeach
                                        <option value="{{$costCenter->code_cost_center}}">
                                            {{$costCenter->description}}=>{{$costCenter->code_cost_center}}
                                        </option>
                                    </select>
                                    @error('code')
                                    <p class=" errorText">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="justify-content-end">
                            <button id="closeButton" type="button"
                                    class="btn btn-sm btn-danger mr-3" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i>
                                Close
                            </button>
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="fas fa-save"></i>
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editRecordModal" tabindex="-1" aria-labelledby="editRecordModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editRecordModalLabel">
                        <span id="modalTitle">Edit Workshop</span>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form name="configurationEditTableForm" method="post">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">
                        UNDER CONSTRUCTION
                    </div>
                    <div class="modal-footer">
                        <button id="closeEditButton" type="button"
                                class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        --}}{{--<button type="submit" class="btn btn-success">Edit</button>--}}{{--
                    </div>
                </form>
            </div>
        </div>
    </div>--}}
@endsection

@push('scripts')

    <!-- DataTables  & Plugins -->
    @include('layouts.partials.dataTableScripts')
    <!-- page script -->
    <script>
        (function (tmsApp) {
            let editRecordModalEl = document.querySelector('#editRecordModal')
            //let addRecordModalEl = document.querySelector('#createRecordModal')

            tmsApp.initDatatable("#listTable", true);

            /*let editModal = bootstrap.Modal.getOrCreateInstance(editRecordModalEl, {
                'backdrop': true,
                'keyboard': false
            });
*/
            /* editRecordModalEl.addEventListener('hidden.bs.modal', function (event) {
                 document.querySelector('[name="editRecordForm"]').reset();
             });*/

            /*addRecordModalEl.addEventListener('hidden.bs.modal', function (event) {
                document.querySelector('[name="addRecordForm"]').reset();
            });*/

            /*$(document).on('click', '[data-kt-action="edit"]', function () {
                $('#modalTitle').text('Edit Workshop');
                addRecordModalEl.show();
                console.log(JSON.parse(this.getAttribute('data-model')));
            });*/

            $('input[name="name"]').on('paste keyup', function () {
                this.value = this.value.toLocaleUpperCase();
            });


            $(document).on('click', 'button[value="addRow"][data-table-id]', function () {
                let tableId = $(this).data('tableId');

                function reinitializeSelect2($_defect_sel) {
                    if ($_defect_sel) {
                        $($_defect_sel).removeClass('select2-hidden-accessible');
                        $($_defect_sel).select2({
                            theme: "bootstrap4",
                            width: "resolve",
                        });
                    }
                }

                if (tableId === "filterProperty") {
                    if ($('.select_2_control').data('select2')) {
                        $('.select_2_control').select2('destroy');
                    }
                }

                Table.addRow($('table#' + tableId));
                let lastRow = $('table#' + tableId).find('tbody tr').eq((0 + 1) * -1);

                lastRow.find('[name="registrationNumber"]').val('');

                if (tableId === "filterProperty") {
                    let row = lastRow[0];
                    $(row).find('.select2-container').remove();
                    let $_defect_sel = $('[name="organizationalUnit"]');
                    reinitializeSelect2($_defect_sel);
                }
                let $_defect_sel = $('[name="organizationalUnit"]');
                reinitializeSelect2($_defect_sel);
            });

            $(document).on('click', 'button[value="deleteRow"]', function (e) {
                e.preventDefault();
                e.stopPropagation();

                let btnEl = $(this);
                let tableId = $(this).closest('table').attr('id');
                let valueId = $(this).attr('data-value');
                let tableRow = btnEl.closest('tr');
                let table = btnEl.closest('table');
                tmsApp.confirm(
                    "Are you sure ?",
                    "The data entered on this line will be cleared out, if not saved already, you will not be able to recover it",
                    "Yes",
                    "No",
                    function () {
                        Table.deleteRow(tableRow);
                        e.preventDefault();
                        e.stopPropagation();
                        if (!valueId || valueId === "0") {
                            return;
                        }
                        let dataUrl = "";
                        if (tableId === 'filterProperty') {
                            dataUrl = document.querySelector('[name="deleteDefectUrl"]').value;
                        } else {
                            dataUrl = document.querySelector('[name="deleteMaterialUrl"]').value;
                        }

                        let formData = new FormData();
                        formData.append('record_id', valueId);

                        tmsApp.asyncPostFormData(
                            dataUrl,
                            formData,
                            function (asyncResponse) {
                                if (asyncResponse['state'] !== 'success') {
                                    if (asyncResponse.hasOwnProperty('errors')) {
                                        toastr.error(
                                            asyncResponse.message
                                        );
                                        tmsApp.printErrorMsg(asyncResponse.errors);
                                        return
                                    }

                                    setTimeout(function () {
                                            tmsApp.systemError(
                                                'System Configuration',
                                                asyncResponse['message'],
                                                function () {
                                                }, 'error');
                                        },
                                        300);
                                    return;
                                }

                                if (asyncResponse['state'] == 'success') {
                                    const entry = asyncResponse.payload;
                                    tmsApp.showSystemMessage(
                                        'System Configuration',
                                        asyncResponse['message'],
                                        function () {
                                            window.location.reload();
                                        },
                                        'success'
                                    );
                                }
                            },
                            function (xhr, settings, error) {
                                setTimeout(
                                    function () {
                                        tmsApp.showErrorMessages(xhr, 'System Configuration');
                                    },
                                    300);
                            },
                            'POST',
                        )
                    });

                return false;
            });

            $(document).on('click', 'button[value="applyFilter"]', function (e) {
                e.preventDefault();
                e.stopPropagation();

                // Declare variables
                let input, filter, table, tr, td, i, txtValue;
                input = document.getElementById("myInput");
                filter = input.value.toUpperCase();
                table = document.getElementById("myTable");
                tr = table.getElementsByTagName("tr");

                // Loop through all table rows, and hide those who don't match the search query
                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[0];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
                return false;
            });

        })(window.tmsApp || {});
    </script>

@endpush
