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

    <x-content-header :pageTitle="'Vehicles In Workshop'"
                      :activeCrumb="'Vehicles'"
                      :link="'home'"
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

                            </div>
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive mt-10 ">
                                <table aria-label="Open Job Cards"
                                       id="inWorkShopList"
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

                                                        @can(config('rights.view_job_card'))
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
                                                        @endcan

                                                        @canany([config('rights.create_job_card')])
                                                            @if(empty($workshop->step))
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                       data-kt-action="edit"
                                                                       href="{{URL::signedRoute(
                                                                    'vehicle.workshop.checkin',[
                                                                    "view"=>true,
                                                                    'step'=> '1',
                                                                    'reference'=>$workshop->job_card_no
                                                                    ])}}">
                                                                        Complete Job Card Opening
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        @endcanany

                                                        @canany([config('rights.close_job_card')])
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
                                                        @endcanany
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

@endsection

@push('scripts')
    <script>
        $(function () {
            const table = $('#inWorkShopList').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('job_card.list.json') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'username', name: 'username'},
                    {data: 'phone', name: 'phone'},
                    {data: 'dob', name: 'dob'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ]
            });
        });
        (function (tmsApp) {
            let editRecordModalEl = document.querySelector('#editRecordModal')
           /* tmsApp.initDatatable("#inWorkShopList", true, true, []);*/

            $('input[name="name"]').on('paste keyup', function () {
                this.value = this.value.toLocaleUpperCase();
            });
        })(window.tmsApp || {});
    </script>

@endpush
