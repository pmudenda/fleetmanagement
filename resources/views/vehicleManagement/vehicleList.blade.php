@php use App\Helpers\StatusHelper; @endphp
@extends('layouts.app')
@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush
@section('content')
    <x-content-header :pageTitle="'Vehicle Register'"/>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                </div>
                <div class="card-toolbar justify-content-end">
                    <div class="d-flex" kt_table-toolbar="base">

                        <a href="{{route('new.vehicle')}}"
                           class="btn btn-success">
                            <i class="fas fa-plus"></i>
                            Onboard Vehicle
                        </a>
                    </div>
                </div>

            </div>

            <!--begin::Card body-->
            <div class="card-body pt-0">

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
                                Model
                            </th>

                            <th>
                                Type
                            </th>
                            <th>
                                Reg. Number
                            </th>

                            <th>
                                Onboarded By
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Onboarding Status
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
                                    <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                        {{$vehicle->brand_name}}
                                    </a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                        {{$vehicle->model_name}} : {{$vehicle->model_code}}
                                    </a>
                                </td>

                                <td>
                                    <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                        {{$vehicle->body_type_name}}
                                    </a>
                                </td>

                                <td>
                                    <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                        {{$vehicle->registration_number}}
                                    </a>
                                </td>

                                <td>
                                    <a href="#" class="text-gray-800 text-hover-primary mb-1">
                                        {{$vehicle->created_by_name}}
                                    </a>
                                </td>

                                <td>
                                    @if($vehicle->status == '01')
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
                                    @endif
                                </td>
                                <td>
                                    @if($vehicle->on_boarding_status == '030')
                                        <div class="badge badge-light-success">
                                            Complete
                                        </div>
                                    @else
                                        <div class="badge badge-light-warning">
                                            Pending
                                        </div>
                                        @if ($vehicle->on_boarding_status == '100')
                                            <div class="badge badge-light-warning">
                                                Pending General Data Entry
                                            </div>
                                        @elseif ($vehicle->on_boarding_status == '101')
                                            <div class="badge badge-light-warning">
                                                Pending Technical Data Entry
                                            </div>
                                        @elseif ($vehicle->on_boarding_status == "102")
                                            <div class="badge badge-light-warning">
                                                Pending Accessories Checkin
                                            </div>
                                        @elseif ($vehicle->on_boarding_status == "103")

                                            <div class="badge badge-light-warning">
                                                Pending Costing Data Entry
                                            </div>
                                        @elseif ($vehicle->on_boarding_status == "104")
                                            <div class="badge badge-light-warning">
                                                Pending Assignment
                                            </div>
                                        @endif
                                    @endif
                                </td>

                                <td>
                                    {{$vehicle->created_at }}
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
                                            @can(config('edit_vehicle'))
                                                @if($vehicle->on_boarding_status == StatusHelper::onboardingComplete())
                                                    <li>
                                                        <a class="dropdown-item" data-kt-action="edit"
                                                           href="{{URL::signedRoute('view.vehicle', ['step' => 6, 'reference' => $vehicle->id, 'edit'=> true])}}">
                                                            Edit
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a class="dropdown-item" data-kt-action="edit"
                                                           href="{{URL::signedRoute('vehicle.show', [
                                                            'step' => 2,
                                                            'reference' => $vehicle->id,
                                                            'edit'=> true])}}">
                                                            View
                                                        </a>
                                                    </li>
                                                @endif
                                                @if($vehicle->on_boarding_status != StatusHelper::onboardingComplete())
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{URL::signedRoute('resume.onboarding',['reference' => $vehicle->id])}}">
                                                            Complete Onboarding
                                                        </a>
                                                    </li>
                                                @endif
                                            @endcan
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
    @include('layouts.partials.dataTableScripts')
    <script src="{{asset('application/modules/vehicleManagement/assets/js/vehicle_list.js')}}"></script>
    <script>
        (function (appInstance) {
            appInstance.initDatatable("#kt_brands_table", false);
        })(window.tmsApp || {});
    </script>
@endpush
