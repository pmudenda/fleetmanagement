@php use App\Models\MaterialHeader;use App\Models\Workflow\WorkflowTaskHeader;use Carbon\Carbon; @endphp
@extends('layouts.app')

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush

@section('content')
    <x-content-header :pageTitle="'Dashboard'"/>
    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-3 col-6">

                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>150</h3>
                            <p>Fuel Requisitions</p>
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
                                    <a href="#">Maintenance Requisition<span
                                            class="float-right"><strong>0</strong></span></a>
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

            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <div class="col-md-12 pl-0">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h3>My Tasks</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="listTable"
                                       class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer">
                                    <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Subject</th>
                                        <th>Description</th>
                                        <th>Originator</th>
                                        <th>Date Requested</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($approvalTasks as $rec)
                                        <tr>
                                            <td>
                                                <a href="{{URL::signedRoute('show.fuel.requisition', ['ref'=>  $rec->reference])}}">
                                                    {{$rec->reference}}
                                                </a>

                                            </td>
                                            <td>
                                                {{$rec->subject ?? '--'}}
                                            </td>
                                            <td>
                                                {{$rec->description}}
                                            </td>

                                            <td>
                                                {{$rec->originator}}
                                            </td>
                                            <td>
                                               {{Carbon::parse($rec->date_acted)->format('d/m/Y')}}
                                            </td>
                                            <td>
                                                <a href="{{URL::signedRoute('show.fuel.requisition',['ref'=> $rec->reference])}}"
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
    </section>
@endsection

@push('scripts')
    @include('layouts.partials.dataTableScripts')
    <script>
        (function (appInstance) {
            appInstance.initDatatable("#listTable", false, true);
        })(window.tmsApp || {});
    </script>
@endpush
