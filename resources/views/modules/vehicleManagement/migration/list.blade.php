@php @endphp
@extends('layouts.app')
@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush
@section('content')
    <x-content-header :pageTitle="'Vehicle Data Clean up'"/>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                </div>
                <div class="card-toolbar justify-content-end">
                    <div class="d-flex" kt_table-toolbar="base">
                        {{--<a href="{{route('new.vehicle')}}"
                           class="btn btn-success">
                            <i class="fas fa-plus"></i>
                            Onboard Vehicle
                        </a>--}}
                    </div>
                </div>

            </div>

            <!--begin::Card body-->
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <form method="post" action="{{route('data.migration.filter')}}">
                            @csrf
                            <div class="form-group">
                                <label class="col-4 pl-0">Filter:</label>
                                <div class="col-8 pl-0">
                                    <div class="input-group">
                                        <select name="userUnit" class="form-select form-select-sm">
                                            <option></option>
                                            @foreach($userUnits as $userUnit)
                                                <option
                                                    value="{{$userUnit->codigo_unidad}}">{{$userUnit->name_dec}}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-addon">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-filter"></i>
                                                Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--begin::Table-->
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                           id="kt_brands_table">
                        <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th>
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="list-row-checkbox" type="checkbox" data-kt-check="true"
                                           data-kt-check-target="#kt_brands_table .form-check-input" value="all"/>
                                </div>
                            </th>

                            <th>
                                Brand
                            </th>

                            <th>
                                Chassis No.
                            </th>

                            <th>
                                Engine No.
                            </th>
                            <th>
                                Reg. Number
                            </th>

                            <th>
                                Assigned To
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Location
                            </th>

                            <th>
                                Date Registered
                            </th>

                            <th>
                                Actions
                            </th>
                        </tr>
                        </thead>


                        <tbody class="text-gray-600 fw-semibold">

                        @foreach($vehicleList as $vehicle)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="list-row-checkbox" type="checkbox" value="item.guid"/>
                                    </div>
                                </td>

                                <td>
                                    {{$vehicle->marca_motor}}
                                </td>
                                <td>
                                    {{$vehicle->bastidor}}
                                </td>

                                <td>
                                    {{$vehicle->engine_no}}
                                </td>

                                <td>
                                    <a href="{{URL::signedRoute('vehicle.data.cleanup', ['reg'=> trim($vehicle->matricula)])}}"
                                       class="btn btn-link mb-1">
                                        {{$vehicle->matricula}}
                                    </a>
                                </td>

                                <td>
                                    <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                        {{$vehicle->user_unit}}
                                    </a>
                                </td>

                                <td>
                                    {{--@if($vehicle->status == '01')
                                        <div class="badge badge-light-success">
                                            Active
                                        </div>
                                    @elseif($vehicle->status == '02')
                                        <div class="badge badge-light-danger">
                                            Inactive
                                        </div>
                                    @else
                                        <div class="badge badge-light-warning">
                                            Onboarding
                                        </div>
                                    @endif--}}
                                </td>
                                <td>
                                    {{$vehicle->unidad_ads}}
                                </td>

                                <td>
                                    {{$vehicle->fech_act }}
                                </td>

                                <td class="text-start">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-active-light-primary btn-sm dropdown-toggle"
                                                type="button"
                                                id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <a class="dropdown-item" data-kt-action="edit"
                                                   href="{{URL::signedRoute('vehicle.data.cleanup', ['reg'=> trim($vehicle->matricula)])}}">
                                                    View
                                                </a>
                                            </li>
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
    </section>
@endsection

@push('scripts')

    <script src="{{asset('application/modules/vehicleManagement/assets/js/vehicle_list.js')}}"></script>
    <script>
        (function (appInstance) {
            appInstance.initDatatable("#kt_brands_table", true);
        })(window.tmsApp || {});
    </script>
@endpush
