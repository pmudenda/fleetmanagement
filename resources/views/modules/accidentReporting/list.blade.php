@php @endphp
@extends('layouts.app')


@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush


@section('content')

    <x-content-header :pageTitle="'Mechanics List'" :activeCrumb="'Mechanic'" :link="'home'"
                      :linkText="'Mechanics'"/>

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
                                <h4>Accidents Reports</h4>
                            </div>
                            <div class="card-toolbar justify-content-end">
                                <!--begin::Filter-->
                                <button type="button" class="btn btn-sm btn-primary me-3" data-menu-trigger="click"
                                        data-menu-placement="bottom-end">
                                <span class="svg-icon svg-icon-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </span>
                                    Filter
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive mt-10 ">
                                <table role="table"
                                       aria-label="Accidents Reports"
                                       id="accidentsTable"
                                       class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Reg. No.</th>
                                        <th>Description</th>
                                        <th>Driver</th>
                                        <th>Date Acc.</th>
                                        <th>Date Rpt.</th>
                                        <th>Nature</th>
                                       {{-- <th>Status</th>--}}
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($accidents as $key =>$accident)
                                        <tr>
                                            <td>
                                                {{$accident->reference}}
                                            </td>
                                            <td>
                                                {{$accident->vehicle_reg_no}}
                                            </td>
                                            <td>
                                                {{$accident->driver}}
                                            </td>
                                            <td>
                                                {{$accident->date_of_accident}} : {{$accident->time_of_accident}}
                                            </td>

                                            <td>
                                                {{$accident->date_reported}} : {{$accident->time_reported}}
                                            </td>
                                            <td>
                                                {{$accident->nature_of_accident}}
                                            </td>
                                            <td>
                                                {{$accident->type_of_accident}}
                                            </td>
                                            <td>
                                                <a href="{{URL::signedRoute('accident.show', [
                                                    'reference'=>$accident->reference
                                                    ])}}"
                                                   class="btn btn-sm btn-success m-1">
                                                    <i class="fas fa-eye">Details</i>
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
    <script>
        (function (appInstance) {
            appInstance.initDatatable("#accidentsTable", true, true, []);
        })(window.tmsApp || {});
    </script>

@endpush
