@php use App\Helpers\StatusHelper; @endphp
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

    <x-content-header :pageTitle="'Closed Job Cards'" :activeCrumb="'Vehicles'" :link="'home'"
                      :linkText="'Vehicles Exited From Workshop'"/>

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
                                <h4>Closed Job Cards</h4>
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
                                                    d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067
                                                    4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582
                                                    9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805
                                                    20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5
                                                    18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408
                                                    10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
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
                                <table aria-label="Closed Job Cards"
                                       id="closedJobCardsList"
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
                                        <th scope="row">Date Closed</th>
                                        @can(config('rights.view_job_card'))
                                            <th>Action</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($workshopsVehicleList as $key => $workshop)
                                        <tr>
                                            <td>
                                                <span class="badge badge-secondary p-2"
                                                      style="width: 20px;height: 20px; border-radius: 50%;">
                                                        <p></p>
                                                </span>
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
                                            @can(config('rights.view_job_card'))
                                                <td>
                                                    <div class="dropdown">
                                                        <button
                                                                class="btn btn-light
                                                            btn-active-light-primary
                                                            btn-sm dropdown-toggle"
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
                                                            @if($workshop->status != StatusHelper::authorised())
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
                                            @endcan
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
        (function (tmsApp) {
            tmsApp.initDatatable("#closedJobCardsList", true, false, []);

            $('input[name="name"]').on('paste keyup', function () {
                this.value = this.value.toLocaleUpperCase();
            });
        })(window.tmsApp || {});
    </script>

@endpush
