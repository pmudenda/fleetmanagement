@php use App\Models\MaterialHeader;use Carbon\Carbon; @endphp
@extends('layouts.app')

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush

@section('content')

    <div class="row g-5 g-xl-8 d-none">
        <div class="col-xl-3">

            <!--begin::Statistics Widget 5-->
            <a href="#" class="card bg-danger hoverable card-xl-stretch mb-xl-8">
                <!--begin::Body-->
                <div class="card-body">
                    <!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm002.svg-->
                    <span class="svg-icon svg-icon-white svg-icon-3x ms-n1"><svg width="24" height="24"
                                                                                 viewBox="0 0 24 24" fill="none"
                                                                                 xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M21 10H13V11C13 11.6 12.6 12 12 12C11.4 12 11 11.6 11 11V10H3C2.4 10 2 10.4 2 11V13H22V11C22 10.4 21.6 10 21 10Z"
                            fill="currentColor"></path>
                        <path opacity="0.3"
                              d="M12 12C11.4 12 11 11.6 11 11V3C11 2.4 11.4 2 12 2C12.6 2 13 2.4 13 3V11C13 11.6 12.6 12 12 12Z"
                              fill="currentColor"></path>
                        <path opacity="0.3"
                              d="M18.1 21H5.9C5.4 21 4.9 20.6 4.8 20.1L3 13H21L19.2 20.1C19.1 20.6 18.6 21 18.1 21ZM13 18V15C13 14.4 12.6 14 12 14C11.4 14 11 14.4 11 15V18C11 18.6 11.4 19 12 19C12.6 19 13 18.6 13 18ZM17 18V15C17 14.4 16.6 14 16 14C15.4 14 15 14.4 15 15V18C15 18.6 15.4 19 16 19C16.6 19 17 18.6 17 18ZM9 18V15C9 14.4 8.6 14 8 14C7.4 14 7 14.4 7 15V18C7 18.6 7.4 19 8 19C8.6 19 9 18.6 9 18Z"
                              fill="currentColor"></path>
                    </svg>
                </span>
                    <!--end::Svg Icon-->

                    <div class="text-white fw-bold fs-2 mb-2 mt-5">
                        Shopping Cart
                    </div>

                    <div class="fw-semibold text-white">
                        Lands, Houses, Ranchos, Farms
                    </div>
                </div>
                <!--end::Body-->
            </a>
            <!--end::Statistics Widget 5-->
        </div>

        <div class="col-xl-3">

            <!--begin::Statistics Widget 5-->
            <a href="#" class="card bg-primary hoverable card-xl-stretch mb-xl-8">
                <!--begin::Body-->
                <div class="card-body">
                    <!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm008.svg-->
                    <span class="svg-icon svg-icon-white svg-icon-3x ms-n1"><svg width="24" height="24"
                                                                                 viewBox="0 0 24 24" fill="none"
                                                                                 xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.3"
                              d="M18 21.6C16.3 21.6 15 20.3 15 18.6V2.50001C15 2.20001 14.6 1.99996 14.3 2.19996L13 3.59999L11.7 2.3C11.3 1.9 10.7 1.9 10.3 2.3L9 3.59999L7.70001 2.3C7.30001 1.9 6.69999 1.9 6.29999 2.3L5 3.59999L3.70001 2.3C3.50001 2.1 3 2.20001 3 3.50001V18.6C3 20.3 4.3 21.6 6 21.6H18Z"
                              fill="currentColor"></path>
                        <path
                            d="M12 12.6H11C10.4 12.6 10 12.2 10 11.6C10 11 10.4 10.6 11 10.6H12C12.6 10.6 13 11 13 11.6C13 12.2 12.6 12.6 12 12.6ZM9 11.6C9 11 8.6 10.6 8 10.6H6C5.4 10.6 5 11 5 11.6C5 12.2 5.4 12.6 6 12.6H8C8.6 12.6 9 12.2 9 11.6ZM9 7.59998C9 6.99998 8.6 6.59998 8 6.59998H6C5.4 6.59998 5 6.99998 5 7.59998C5 8.19998 5.4 8.59998 6 8.59998H8C8.6 8.59998 9 8.19998 9 7.59998ZM13 7.59998C13 6.99998 12.6 6.59998 12 6.59998H11C10.4 6.59998 10 6.99998 10 7.59998C10 8.19998 10.4 8.59998 11 8.59998H12C12.6 8.59998 13 8.19998 13 7.59998ZM13 15.6C13 15 12.6 14.6 12 14.6H10C9.4 14.6 9 15 9 15.6C9 16.2 9.4 16.6 10 16.6H12C12.6 16.6 13 16.2 13 15.6Z"
                            fill="currentColor"></path>
                        <path
                            d="M15 18.6C15 20.3 16.3 21.6 18 21.6C19.7 21.6 21 20.3 21 18.6V12.5C21 12.2 20.6 12 20.3 12.2L19 13.6L17.7 12.3C17.3 11.9 16.7 11.9 16.3 12.3L15 13.6V18.6Z"
                            fill="currentColor"></path>
                    </svg>
                </span>
                    <!--end::Svg Icon-->

                    <div class="text-white fw-bold fs-2 mb-2 mt-5">
                        Appartments
                    </div>

                    <div class="fw-semibold text-white">
                        Flats, Shared Rooms, Duplex
                    </div>
                </div>
                <!--end::Body-->
            </a>
            <!--end::Statistics Widget 5-->
        </div>

        <div class="col-xl-3">

            <!--begin::Statistics Widget 5-->
            <a href="#" class="card bg-success hoverable card-xl-stretch mb-5 mb-xl-8">
                <!--begin::Body-->
                <div class="card-body">
                    <!--begin::Svg Icon | path: icons/duotune/graphs/gra005.svg-->
                    <span class="svg-icon svg-icon-white svg-icon-3x ms-n1"><svg width="24" height="24"
                                                                                 viewBox="0 0 24 24" fill="none"
                                                                                 xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.3"
                              d="M14 12V21H10V12C10 11.4 10.4 11 11 11H13C13.6 11 14 11.4 14 12ZM7 2H5C4.4 2 4 2.4 4 3V21H8V3C8 2.4 7.6 2 7 2Z"
                              fill="currentColor"></path>
                        <path
                            d="M21 20H20V16C20 15.4 19.6 15 19 15H17C16.4 15 16 15.4 16 16V20H3C2.4 20 2 20.4 2 21C2 21.6 2.4 22 3 22H21C21.6 22 22 21.6 22 21C22 20.4 21.6 20 21 20Z"
                            fill="currentColor"></path>
                    </svg>
                </span>
                    <!--end::Svg Icon-->

                    <div class="text-white fw-bold fs-2 mb-2 mt-5">
                        Sales Stats
                    </div>

                    <div class="fw-semibold text-white">
                        50% Increased for FY20
                    </div>
                </div>
                <!--end::Body-->
            </a>
            <!--end::Statistics Widget 5-->
        </div>

        <div class="col-xl-3">
            <!--begin::Statistics Widget 5-->
            <a href="#" class="card bg-danger hoverable card-xl-stretch mb-xl-8">
                <!--begin::Body-->
                <div class="card-body">
                    <!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm002.svg-->
                    <span class="svg-icon svg-icon-white svg-icon-3x ms-n1"><svg width="24" height="24"
                                                                                 viewBox="0 0 24 24" fill="none"
                                                                                 xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M21 10H13V11C13 11.6 12.6 12 12 12C11.4 12 11 11.6 11 11V10H3C2.4 10 2 10.4 2 11V13H22V11C22 10.4 21.6 10 21 10Z"
                            fill="currentColor"></path>
                        <path opacity="0.3"
                              d="M12 12C11.4 12 11 11.6 11 11V3C11 2.4 11.4 2 12 2C12.6 2 13 2.4 13 3V11C13 11.6 12.6 12 12 12Z"
                              fill="currentColor"></path>
                        <path opacity="0.3"
                              d="M18.1 21H5.9C5.4 21 4.9 20.6 4.8 20.1L3 13H21L19.2 20.1C19.1 20.6 18.6 21 18.1 21ZM13 18V15C13 14.4 12.6 14 12 14C11.4 14 11 14.4 11 15V18C11 18.6 11.4 19 12 19C12.6 19 13 18.6 13 18ZM17 18V15C17 14.4 16.6 14 16 14C15.4 14 15 14.4 15 15V18C15 18.6 15.4 19 16 19C16.6 19 17 18.6 17 18ZM9 18V15C9 14.4 8.6 14 8 14C7.4 14 7 14.4 7 15V18C7 18.6 7.4 19 8 19C8.6 19 9 18.6 9 18Z"
                              fill="currentColor"></path>
                    </svg>
                </span>
                    <!--end::Svg Icon-->

                    <div class="text-white fw-bold fs-2 mb-2 mt-5">
                        Shopping Cart
                    </div>

                    <div class="fw-semibold text-white">
                        Lands, Houses, Ranchos, Farms
                    </div>
                </div>
                <!--end::Body-->
            </a>
            <!--end::Statistics Widget 5-->
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-6">

            <div class="small-box bg-info">
                <div class="inner">
                    <h3>150</h3>
                    <p>New Orders</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">

            <div class="small-box bg-success">
                <div class="inner">
                    <h3>53<sup style="font-size: 20px">%</sup></h3>
                    <p>Bounce Rate</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">

            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>44</h3>
                    <p>User Registrations</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">

            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>65</h3>
                    <p>Unique Visitors</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

    </div>

    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fs-17 font-weight-600 mb-0">Vehicles</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <div>
                            <i class="fas fa fa-caret-right text-success"></i>
                            <a href="#">On Requisition<span class="float-right"><strong>27</strong></span></a>
                        </div>
                        <div>
                            <i class="fas fa fa-caret-right text-success"></i>
                            <a href="#">On Maintenance <span class="float-right"><strong>13</strong></span></a>
                        </div>
                        <div>
                            <i class="fas fa fa-caret-right text-success"></i>
                            <a href="#">Available <span class="float-right"><strong>2</strong></span></a>
                        </div>
                        <div>
                            &nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fs-17 font-weight-600 mb-0">Todays Requisition</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <div>
                            <i class="fas fa fa-caret-right text-success"></i>
                            <a href="#">Vehicle Requisition <span class="float-right"><strong>0</strong></span></a>
                        </div>
                        <div>
                            <i class="fas fa fa-caret-right text-success"></i>
                            <a href="#">Maintenance Requisition<span class="float-right"><strong>0</strong></span></a>
                        </div>
                        <div>
                            <i class="fas fa fa-caret-right text-success"></i>
                            <a href="#">Fuel Requisition<span class="float-right"><strong>
                                        {{MaterialHeader::whereDate('date_created','=' ,Carbon::now())->count()}}
                                    </strong></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fs-17 font-weight-600 mb-0">Reminder </h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <div>
                            <i class="fas fa fa-caret-right text-success"></i>
                            <a href="#">License Soon Expire <span class="float-right"><strong>0</strong></span></a>
                        </div>
                        <div>
                            <i class="fas fa fa-caret-right text-success"></i>
                            <a href="#">License Expired <span class="float-right"><strong>0</strong></span></a>
                        </div>
                        <div>
                            &nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fs-17 font-weight-600 mb-0"> Others Activities</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <div>
                            <i class="fas fa fa-caret-right text-success"></i>
                            <a href="#">Stock In <span class="float-right"><strong>115</strong></span></a>
                        </div>
                        <div>
                            <i class="fas fa fa-caret-right text-success"></i>
                            <a href="#"> Stock Out <span class="float-right"><strong>772040</strong></span></a>
                        </div>
                        <div>
                            &nbsp;
                        </div>
                        <div>
                            &nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row d-none">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fs-17 font-weight-600 mb-0">Expense Summary </h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart mb-3">
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <canvas id="doughutChart" height="484" style="display: block; width: 469px; height: 484px;"
                                width="469" class="chartjs-render-monitor"></canvas>
                    </div>
                    <div class="chart-legend">
                        <div class="chart-legend-item">
                            <div class="chart-legend-color kelly-green"></div>
                            <p>Fuel Cost</p>
                            <p class="percentage text-muted">13500.00</p>
                        </div>
                        <div class="chart-legend-item">
                            <div class="chart-legend-color kelly-green2"></div>
                            <p>Maintenance Cost</p>
                            <p class="percentage text-muted">44260.00</p>
                        </div>
                        <div class="chart-legend-item">
                            <div class="chart-legend-color whisper"></div>
                            <p>Other Cost</p>
                            <p class="percentage text-muted">1960.00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="fs-18 font-weight-bold mb-0">Maintenance Cost</h2>
                </div>
                <div class="card-body">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <canvas id="barChart" height="637" width="1006"
                            style="display: block; width: 1006px; height: 637px;"
                            class="chartjs-render-monitor"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <div class="col-md-12 pl-0">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body p-2">
                        <div class="table-responsive mt-10 ">
                            <table id="listTable" class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Reference #</th>
                                    <th>Registration</th>
                                    <th>Valid From</th>
                                    <th>Valid To</th>
                                    <th>Originator</th>
                                    <th>Remarks</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach(MaterialHeader::get() as $rec)
                                    <tr>
                                        <td>
                                            <a href="{{URL::signedRoute('show.fuel.requisition', ['ref'=>  $rec->req_no])}}">
                                                {{$rec->req_no}}
                                            </a>

                                        </td>
                                        <td>
                                            {{$rec->reg_no}}
                                        </td>
                                        <td>
                                            {{Carbon::parse($rec->valid_date_from)->format('d/m/Y')}}
                                        </td>
                                        <td>
                                            {{Carbon::parse($rec->valid_date_to)->format('d/m/Y')}}
                                        </td>
                                        <td>
                                            {{$rec->requested_by}}
                                        </td>
                                        <td>
                                            {{$rec->comments}}
                                        </td>
                                        <td>
                                            <a href="{{URL::signedRoute('show.fuel.requisition',['ref'=> $rec->req_no])}}"
                                               class="btn btn-sm btn-success">
                                                <i class="fas fa-eye"></i> Open
                                            </a>
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

@endsection

@push('scripts')
    @include('layouts.partials.dataTableScripts')
    <script>
        (function (appInstance) {
            appInstance.initDatatable("#listTable", false, true);
        })(window.tmsApp || {});
    </script>
@endpush
