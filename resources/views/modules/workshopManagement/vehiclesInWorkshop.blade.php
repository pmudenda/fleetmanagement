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
                                                d="M19.0759 3H4.72777C3.95892
                                                    3 3.47768 3.83148 3.86067
                                                    4.49814L8.56967 12.6949C9.17923
                                                    13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5
                                                    20.2189 10.2223 20.7028 10.8805
                                                    20.432L13.8805 19.1977C14.2553
                                                    19.0435 14.5 18.6783 14.5
                                                    18.273V13.8372C14.5 12.8089
                                                    14.8171 11.8056 15.408
                                                    10.964L19.8943 4.57465C20.3596
                                                    3.912 19.8856 3 19.0759 3Z"
                                                fill="currentColor">
                                            </path>
                                        </svg>
                                    </span>
                                    Filter
                                </button>
                                @can(config('rights.create_job_card'))
                                    <a href="{{URL::signedRoute('show.job.card')}}"
                                       class="btn btn-sm btn-success float-right">
                                        <i class="fas fa-user-plus"></i>
                                        Create Job Card
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive mt-10 ">
                                <table aria-label="Open Job Cards"
                                       id="listTable"
                                       class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th scope="row">#</th>
                                        <th scope="row">Reg. No.</th>
                                        <th scope="row">Workshop Document No.</th>
                                        <th scope="row">Job Card Voucher</th>
                                        <th scope="row">Workshop</th>
                                        <th scope="row">Date Raised</th>
                                        <th scope="row">Repair Type</th>
                                        <th scope="row">Date Out</th>
                                        @can(config('rights.view_job_card'))
                                            <th>Action</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($workshopsVehicleList as $key => $workshop)
                                        <tr>
                                            <td>
                                                {{-- {{++$key}}--}}
                                                @if(Carbon::now()->isBefore(
                                                    Carbon::parse($workshop->expected_date_out))
                                                    )
                                                    <span title="Active"
                                                          data-toggle="tooltip"
                                                          class="badge badge-success p-2"
                                                          style="width: 20px;height: 20px; border-radius: 50%;">
                                                        <p></p>
                                                    </span>
                                                @elseif(Carbon::now()->isAfter(
                                                        Carbon::parse($workshop->expected_date_out))
                                                        )
                                                    <span title="Expired"
                                                          data-toggle="tooltip"
                                                          class="badge badge-danger p-2"
                                                          style="width: 20px;height: 20px; border-radius: 50%;">
                                                        <p></p>
                                                    </span>
                                                @elseif(Carbon::now()->addDays(3)->eq(
                                                        Carbon::parse($workshop->expected_date_out))
                                                        )
                                                    <span title="Expiring In 3 Days"
                                                          data-toggle="tooltip"
                                                          class="badge badge-warning p-2"
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
                                            <td>
                                                <div class="dropdown">
                                                    <button
                                                        class="btn btn-light
                                                            btn-active-light-primary btn-sm dropdown-toggle"
                                                        type="button"
                                                        id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li>
                                                            <a class="dropdown-item"
                                                               data-kt-action="edit"
                                                               href="{{URL::signedRoute('view.job.card',[
                                                                    "view"=>true,
                                                                    'step'=> '1',
                                                                    'reference'=>$workshop->job_card_no
                                                                    ])}}">
                                                                View Job Card
                                                            </a>
                                                        </li>

                                                        @if(empty($workshop->step))
                                                            <li>
                                                                <a class="dropdown-item"
                                                                   data-kt-action="edit"
                                                                   href="{{URL::signedRoute('vehicle.workshop.checkin',[
                                                                    "view"=>true,
                                                                    'step'=> '1',
                                                                    'reference'=>$workshop->job_card_no
                                                                    ])}}">
                                                                    Process Job Card
                                                                </a>
                                                            </li>
                                                        @endif

                                                        @if($workshop->status != StatusHelper::closed())
                                                            <li>
                                                                <a class="dropdown-item"
                                                                   data-kt-action="exit"
                                                                   href="{{URL::signedRoute('exit.from.card',[
                                                                            'reference'=>$workshop->job_card_no
                                                                   ])}}">
                                                                    Exit From Workshop
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
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
@endsection

@push('scripts')
    <script>
        (function (tmsApp) {
            let editRecordModalEl = document.querySelector('#editRecordModal')
            tmsApp.initDatatable("#listTable", true);

            $('input[name="name"]').on('paste keyup', function () {
                this.value = this.value.toLocaleUpperCase();
            });
        })(window.tmsApp || {});
    </script>

@endpush
