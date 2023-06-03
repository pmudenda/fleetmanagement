@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush

@section('content')

    <x-content-header :pageTitle="'Fuel Requisitions'" />
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
                            <table id="listTable" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Reference #</th>
                                    <th>Document No.</th>
                                    <th>Registration</th>
                                    <th>Valid From</th>
                                    <th>Valid To</th>
                                    <th>Originator</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($requisitions as $rec)
                                    <tr>
                                        <td>
                                            <a href="{{URL::signedRoute('show.fuel.requisition', ['ref'=>  $rec->req_no])}}">
                                                {{$rec->req_no}}
                                            </a>
                                        </td>
                                        <td>
                                            {{$rec->st_pur}}
                                        </td>
                                        <td>
                                            {{$rec->veh_reg_no}}
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
    <!-- DataTables  & Plugins -->
    @include('layouts.partials.dataTableScripts')
    <!-- page script -->
    <script>
        (function (appInstance) {
            appInstance.initDatatable("#listTable", false);
        })(window.tmsApp || {});
    </script>
@endpush
