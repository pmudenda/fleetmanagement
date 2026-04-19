@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@push('styles')
    <style>
        .imagePreview {
            width: 100%;
            min-height: 280px;
            background-position: center center;
            background-color: #fff;
            background-size: cover;
            background-repeat: no-repeat;
            display: inline-block;
            box-shadow: 0px -3px 6px 2px rgba(0, 0, 0, 0.2);
        }

        .img_title {
            background-color: #454546ad;
        }

        .form-control:disabled {
            border: none !important;
            background-color: transparent !important;
        }

        select.form-control:disabled {
            border: none !important;
            background-color: transparent !important;
            box-shadow: none !important;

        }

    </style>
    <link rel="stylesheet" href="{{asset('libs/handsontable/handsontable.full.min.css')}}"/>
@endpush
@section('content')
    <x-content-header/>
    <section class="content">
        <div class="row g-12 g-xl-12" id="kt_app_main">

            <!--BEGIN:::VEHICLE HEADER -->
            <div class="card mb-xl-5">
                <div id="card_header" class="card-header min-h-2px">
                    <div class="card-title">
                        <h2>Vehicle Details</h2>
                    </div>
                    <div class="card-toolbar justify-content-end">
                        @if($vehicle && !empty($vehicle->barcode))
                            <img id="barcode" alt="vehicle barcode" style="max-height: 40px;"
                                 src="/storage/{{$vehicle->barcode}}">
                        @endif
                        <button type="button" data-bs-target="#vehicleDisk" data-bs-toggle="modal"
                                class="btn btn-default btn-sm mr-3">
                            <i class="fas fa-print"></i> Print Disk
                        </button>
                    </div>
                </div>

                <!--begin::Card body-->
                <div class="card-body py-0">
                    <x-error-view/>
                    <form name="vehicleHeaderForm" id="tms_vehicle_header_form"
                          class="form mb-5">
                        <input type="hidden" name="doctype" value="VehicleHeader"/>
                        <div class="row">
                            <table role="table"
                                   aria-label="header data">
                                <thead class="d-none">
                                <tr>
                                    <th scope="row"></th>
                                    <th scope="row"></th>
                                </tr>
                                </thead>
                                <tr>
                                    <td style="vertical-align: baseline; width:15%;">
                                        <div v-if="images && images.frontView">
                                            <img style="height: 100px;" class="frontImagePreview"
                                                 v-bind:src='"/storage/" + images.frontView.path' alt=""/>
                                        </div>
                                    </td>
                                    <td class="pl-3" style="vertical-align: top;">
                                        <div>
                                            <table
                                                    aria-label="vehicle description"
                                                    role="table">
                                                <thead class="d-none">
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tr>
                                                    <td>
                                                        <div class="row">
                                                            <span data-name="brand"
                                                                  class="text-bold"
                                                                  id="brand"></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: x-small;">
                                                        <span id="description"
                                                              class="text-extra-muted"
                                                              data-name="description">
                                                        </span>
                                                    </td>
                                                    <td>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table aria-label="vehicle status"
                                                               role="table">
                                                            <thead class="d-none">
                                                            <tr>
                                                                <th></th>
                                                                <th></th>
                                                            </tr>
                                                            </thead>
                                                            <tr>
                                                                <td>
                                                                    <span data-name="vehicleMileage"
                                                                          style="font-size: smaller"
                                                                          id="vehicleMileage"></span>
                                                                </td>
                                                                <td class="pl-3">
                                                                    <i class="ion ion-location ion-solid mr-1"
                                                                       style="font-size: 16px; color: green;"></i>
                                                                    <span data-name="vehicleLocation"
                                                                          style="font-size: smaller"
                                                                          id="vehicleLocation"></span>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                    </td>
                                                    <td class="pl-0"></td>
                                                    <td class="pl-2 d-none"
                                                        id="tom_cardRow">
                                                        <i class="fa fa-credit-card-alt"
                                                           style="font-size: 18px; color: red;"></i>
                                                        <span data-name="tom_card">Tom Card</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table
                                                                aria-label="vehicle status"
                                                                role="table">
                                                            <thead class="d-none">
                                                            <tr>
                                                                <th></th>
                                                                <th></th>
                                                            </tr>
                                                            </thead>
                                                            <tr>
                                                                <td>
                                                                    <span data-name="registrationNumber"
                                                                          id="registrationNumber"></span>
                                                                </td>
                                                                <td class="pl-3">
                                                                    <span class="badge badge-success badge-circle"
                                                                          style="height: 8px; width: 8px;"></span>

                                                                    <span data-name="vehicleState"
                                                                          id="vehicleState"></span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label style="display: none;"
                                                           for="registration_type"
                                                           class="fs-6 fw-semibold form-label mt-3 col-md-3">
                                                        <span class="required">Registration Type</span>
                                                    </label>
                                                    <div class="col-md-9 fv-row"
                                                         style="display: none; visibility: hidden; ">
                                                        <div class="col-md-9">
                                                            <div class="w-100 fv-row">
                                                                <select class="form-control form-control-sm"
                                                                        id="registration_type"
                                                                        name="registration_type"
                                                                        @input="registrationTypeChanged"
                                                                        v-model="vehicleHeader.registration_type">
                                                                    <option v-for="regType in registrationTypes"
                                                                            :key="regType.code"
                                                                            :value="regType.code">
                                                                        @{{ regType.label }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">

                                                    <div class="form-group row" style="display: none;">
                                                        <label for="model_code"
                                                               class="fs-6 fw-semibold form-label col-md-3">
                                                            <span class="required">Model Code</span>
                                                        </label>

                                                        <div class="col-md-9 fv-row">
                                                            <div class="col-md-9">
                                                                <div class="w-100">
                                                                    <input class="form-control form-control-solid"
                                                                           name="model_code"
                                                                           readonly
                                                                           id="model_code"
                                                                    />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row" style="display: none;">
                                                        <label for="user_unit"
                                                               class="fs-6 fw-semibold form-label col-md-3">
                                                            <span class="required">User Unit</span>
                                                        </label>

                                                        <div class="col-md-9 fv-row ">
                                                            <div class="col-sm-12 col-md-12">
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <select class="form-control"
                                                                                    required
                                                                                    name="user_unit"
                                                                                    id="user_unit"
                                                                                    data-doc-type="vehicleHeader">
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="control-value like-disabled-input"
                                                                         style="display: none;"></div>
                                                                    <p class="help-box small text-muted"></p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="col-6">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="col-4">
                                            <div id="qrcode"></div>
                                        </div>
                                        <div class="col-8">

                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </form>
                    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent"
                        role="tablist">

                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link active" data-toggle="tab" href="#overview" role="tab">Selected Motor Vehicle Overview</a>
                        </li>

                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link " data-toggle="tab" href="#specs" role="tab">Specs</a>
                        </li>

                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link" data-toggle="tab" href="#accessoriesTab"
                               role="tab">Accessories</a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-primary pb-5"
                               data-bs-toggle="tab"
                               href="#financial" aria-selected="false" role="tab"
                               tabindex="-1">
                                Financial
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-primary pb-5"
                               data-bs-toggle="tab"
                               href="#costAnalysis" aria-selected="false" role="tab"
                               tabindex="-1">
                                <i class="fas fa-chart-line me-1"></i> Cost Analysis
                            </a>
                        </li>

                        <li class="nav-item" style="list-style: none; display: none;">
                            <a class="nav-link" data-toggle="tab" href="#serviceHistory" role="tab">Service History</a>
                        </li>
                        <li class="nav-item" style="list-style: none; display: none;">
                            <a class="nav-link" data-toggle="tab" href="#inspectionHistory" role="tab">Inspection
                                History
                            </a>
                        </li>
                        <li class="nav-item" style="list-style: none; display: none;">
                            <a class="nav-link" data-toggle="tab" href="#workOrders" role="tab">Work Orders</a>
                        </li>
                        <li class="nav-item" style="list-style: none; display: none;">
                            <a class="nav-link" data-toggle="tab" href="#serviceReminders" role="tab">
                                Service
                                Reminders</a>
                        </li>
                        <li class="nav-item" style="list-style: none; display: none;">
                            <a class="nav-link" data-toggle="tab" href="#renewalReminder" role="tab">Renewal
                                Reminders</a>
                        </li>
                        <li class="nav-item" style="list-style: none; display: none;">
                            <a class="nav-link" data-toggle="tab" href="#odometerHistory" role="tab">
                                Meter History
                            </a>
                        </li>

                        <li class="nav-item" style="list-style: none; display: none;">
                            <a class="nav-link" data-toggle="tab" href="#fuelHistory" role="tab">Fuel History</a>
                        </li>

                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link" data-toggle="tab" href="#assignmentHistory" role="tab">Assignment
                                History</a>
                        </li>

                        <li class="nav-item" style="list-style: none;">
                            <a class="nav-link" data-toggle="tab" href="#roadtax" role="tab">
                                Fitness & Road Tax</a>
                        </li>
                    </ul>
                    {{-- <hr/>--}}
                </div>
            </div>

            <div class="card" style="background-color: #f5f8fa;">
                <div class="card-body py-0 px-1" style="background-color: #f5f8fa;">
                    <div class="tab-content">
                        <div class="tab-pane active" id="overview" role="tabpanel">
                            <div class="container-fluid pl-0">
                                <!-- Executive KPI Cards - 4x4 Grid Layout -->
                                <div class="row mb-4">
                                    <!-- Row 1: First 4 Cards -->
                                    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                                        <div class="card border-left-primary shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Active Vehicles</div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="activeVehiclesCount">Loading...</div>
                                                        <div class="text-xs text-gray-500" id="activeVehiclesTrend">Last 30 days</div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-truck fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                                        <div class="card border-left-success shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Fuel Cost</div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalFuelCost">Loading...</div>
                                                        <div class="text-xs" id="fuelCostTrend">
                                                            <span class="text-success">+0%</span> vs previous period
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-gas-pump fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                                        <div class="card border-left-warning shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Maintenance Cost</div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalMaintenanceCost">Loading...</div>
                                                        <div class="text-xs" id="maintenanceCostTrend">
                                                            <span class="text-warning">+0%</span> vs previous period
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-wrench fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                                        <div class="card border-left-info shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Avg Cost/Vehicle</div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="avgCostPerVehicle">Loading...</div>
                                                        <div class="text-xs text-gray-500" id="avgCostVehiclesCount">0 vehicles</div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Row 2: Second 4 Cards -->
                                <div class="row mb-4">
                                    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                                        <div class="card border-left-danger shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Operating Cost</div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalOperatingCost">Loading...</div>
                                                        <div class="text-xs text-gray-500">Fuel + Maintenance</div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-calculator fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                                        <div class="card border-left-secondary shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Highest Cost Vehicle</div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="highestCostVehicle">Loading...</div>
                                                        <div class="text-xs text-gray-500" id="highestCostAmount">ZMW 0</div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                                        <div class="card border-left-primary shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Maintenance Events</div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="maintenanceEvents">Loading...</div>
                                                        <div class="text-xs text-gray-500" id="maintenanceVehiclesCount">0 vehicles</div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-tools fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                                        <div class="card border-left-success shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Fuel Events</div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="fuelEvents">Loading...</div>
                                                        <div class="text-xs text-gray-500" id="fuelVehiclesCount">0 vehicles</div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-gas-pump fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Trend Charts Section -->
                                <div class="row mb-4">
                                    <div class="col-lg-8 col-md-12 mb-3">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-light">
                                                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i> Monthly Operating Cost Trends</h5>
                                            </div>
                                            <div class="card-body p-3">
                                                <div id="monthlyTrendsChart" style="height:350px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-12 mb-3">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-light">
                                                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i> Cost Distribution</h5>
                                            </div>
                                            <div class="card-body p-3">
                                                <div id="costDistributionChart" style="height:350px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Top Vehicles Performance with Tabs -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-light">
                                                <h5 class="mb-3"><i class="fas fa-trophy me-2"></i> Top Vehicles Performance</h5>
                                                <ul class="nav nav-tabs card-header-tabs" id="topVehiclesTabs" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="total-cost-tab" data-toggle="tab" href="#total-cost" role="tab" aria-controls="total-cost" aria-selected="true">
                                                            <i class="fas fa-calculator me-1"></i> Operating Cost
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="fuel-cost-tab" data-toggle="tab" href="#fuel-cost" role="tab" aria-controls="fuel-cost" aria-selected="false">
                                                            <i class="fas fa-gas-pump me-1"></i> Fuel Cost
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="maintenance-cost-tab" data-toggle="tab" href="#maintenance-cost" role="tab" aria-controls="maintenance-cost" aria-selected="false">
                                                            <i class="fas fa-wrench me-1"></i> Maintenance Cost
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="maintenance-events-tab" data-toggle="tab" href="#maintenance-events" role="tab" aria-controls="maintenance-events" aria-selected="false">
                                                            <i class="fas fa-tools me-1"></i> Maintenance Events
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="card-body">
                                                <div class="tab-content" id="topVehiclesTabContent">
                                                    <div class="tab-pane fade show active" id="total-cost" role="tabpanel" aria-labelledby="total-cost-tab">
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div id="topVehiclesTotalCostChart" style="height:350px;"></div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="list-group" id="topVehiclesTotalCostList" style="max-height:350px; overflow-y:auto;">
                                                                    <!-- Top vehicles by total cost will be loaded here -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="fuel-cost" role="tabpanel" aria-labelledby="fuel-cost-tab">
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div id="topVehiclesFuelCostChart" style="height:350px;"></div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="list-group" id="topVehiclesFuelCostList" style="max-height:350px; overflow-y:auto;">
                                                                    <!-- Top vehicles by fuel cost will be loaded here -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="maintenance-cost" role="tabpanel" aria-labelledby="maintenance-cost-tab">
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div id="topVehiclesMaintenanceCostChart" style="height:350px;"></div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="list-group" id="topVehiclesMaintenanceCostList" style="max-height:350px; overflow-y:auto;">
                                                                    <!-- Top vehicles by maintenance cost will be loaded here -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="maintenance-events" role="tabpanel" aria-labelledby="maintenance-events-tab">
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div id="topVehiclesMaintenanceEventsChart" style="height:350px;"></div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="list-group" id="topVehiclesMaintenanceEventsList" style="max-height:350px; overflow-y:auto;">
                                                                    <!-- Top vehicles by maintenance events will be loaded here -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cost by Organizational Unit -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="mb-0"><i class="fas fa-building"></i> Cost by Organizational Unit</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div id="costByOrgUnitChart" style="height:350px;"></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="list-group" id="costByOrgUnitList" style="max-height:350px; overflow-y:auto;">
                                                            <!-- Cost by org unit list will be loaded here -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Vehicle Cost Analysis -->
                                <div class="row">
                                    <div class="col-4">
                                        <div class="card mt-5">
                                            <div class="card-header py-0">
                                                <div class="card-title">
                                                    <h2>Cost of Operations</h2>
                                                </div>
                                            </div>
                                            <div class="card-body px-0 py-0 pl-1">
                                                <div class="card">
                                                    <div class="card-header pl-0 view_mode px-0 py-0">
                                                        <div class="card-title pl-3">
                                                            Total Costs <small class="pl-2">Fuel + Maintenance</small>
                                                            <span style="font-weight: bold;" class="ml-3"
                                                                  id="totalOwnershipCosts"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="main" style="height:300px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="card mt-5">
                                            <div class="card-header py-0">
                                                <div class="card-title">
                                                    <h2>Cost Distribution</h2>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div id="pie" style="height:300px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div id="performanceMetrics" style="height:400px;"></div>
                                    </div>
                                </div>

                                <!-- Fleet Exceptions & Alerts -->
                                <div class="row mb-4">
                                    <div class="col-lg-6 col-md-12 mb-3">
                                        <div class="card shadow-sm border-left-warning">
                                            <div class="card-header bg-light">
                                                <h5 class="mb-0"><i class="fas fa-exclamation-triangle text-warning me-2"></i> Vehicles with No Maintenance (6+ months)</h5>
                                            </div>
                                            <div class="card-body p-3">
                                                <div id="noMaintenanceAlerts" style="max-height:250px; overflow-y:auto;">
                                                    <div class="text-center text-muted">Loading alerts...</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 mb-3">
                                        <div class="card shadow-sm border-left-danger">
                                            <div class="card-header bg-light">
                                                <h5 class="mb-0"><i class="fas fa-exclamation-circle text-danger me-2"></i> High Maintenance Spend Vehicles</h5>
                                            </div>
                                            <div class="card-body p-3">
                                                <div id="highMaintenanceAlerts" style="max-height:250px; overflow-y:auto;">
                                                    <div class="text-center text-muted">Loading alerts...</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Drill-down Data Table -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="mb-0"><i class="fas fa-table"></i> Vehicle Performance Details</h5>
                                                <div class="card-toolbar float-right">
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-outline-primary" onclick="exportDashboardData()">
                                                            <i class="fas fa-download"></i> Export
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-secondary" onclick="refreshDashboardData()">
                                                            <i class="fas fa-sync"></i> Refresh
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-3">
                                                        <label for="dashboardOrgUnitFilter" class="form-label small">Organizational Unit</label>
                                                        <select class="form-control form-control-sm" id="dashboardOrgUnitFilter" name="dashboardOrgUnitFilter">
                                                            <option value="">All Org Units</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="dashboardPeriodFilter" class="form-label small">Time Period</label>
                                                        <select class="form-control form-control-sm" id="dashboardPeriodFilter" name="dashboardPeriodFilter">
                                                            <option value="30">Last 30 Days</option>
                                                            <option value="90">Last 90 Days</option>
                                                            <option value="180">Last 6 Months</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="dashboardMetricFilter" class="form-label small">Vehicle Filter</label>
                                                        <select class="form-control form-control-sm" id="dashboardMetricFilter" name="dashboardMetricFilter">
                                                            <option value="">All Vehicles</option>
                                                            <option value="high_cost">High Cost Only</option>
                                                            <option value="no_maintenance">No Maintenance</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="dashboardSearchFilter" class="form-label small">Search Vehicle</label>
                                                        <input type="text" class="form-control form-control-sm" id="dashboardSearchFilter" name="dashboardSearchFilter" placeholder="Search by Reg No..." autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-hover" id="vehicleDetailsTable">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th>Reg No</th>
                                                                <th>Type/Brand</th>
                                                                <th>Org Unit</th>
                                                                <th>Fuel Cost</th>
                                                                <th>Maintenance Cost</th>
                                                                <th>Total Cost</th>
                                                                <th>Fuel Events</th>
                                                                <th>Maintenance Events</th>
                                                                <th>Last Maintenance</th>
                                                                <th>Alerts</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="vehicleDetailsBody">
                                                            <tr>
                                                                <td colspan="10" class="text-center text-muted">
                                                                    Loading vehicle details...
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Comprehensive Maintenance Tracking Section -->
                                <div class="row mt-5">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-primary text-white">
                                                <h5 class="mb-0"><i class="fas fa-wrench"></i> Comprehensive Maintenance Tracking</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <button class="btn btn-info" onclick="loadMaintenanceDetails()">
                                                            <i class="fas fa-sync"></i> Load Maintenance Details
                                                        </button>
                                                        <button class="btn btn-secondary ml-2" onclick="exportMaintenanceData()">
                                                            <i class="fas fa-download"></i> Export Data
                                                        </button>
                                                    </div>
                                                    <div class="col-md-6 text-right">
                                                        <div class="form-group mb-0">
                                                            <label for="maintenancePeriod" class="mr-2">Maintenance Period:</label>
                                                            <select id="maintenancePeriod" name="maintenancePeriod" class="form-control d-inline-block" style="width: auto;">
                                                                <option value="3">Last 3 Months</option>
                                                                <option value="6" selected>Last 6 Months</option>
                                                                <option value="12">Last 12 Months</option>
                                                                <option value="24">Last 24 Months</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Maintenance Statistics Cards -->
                                                <div class="row mb-4" id="maintenanceStatsCards">
                                                    <div class="col-md-3">
                                                        <div class="card bg-light">
                                                            <div class="card-body text-center">
                                                                <h6 class="text-muted">Total Maintenance Cost</h6>
                                                                <h4 class="text-primary" id="totalMaintenanceCostDetail">ZMW 0.00</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="card bg-light">
                                                            <div class="card-body text-center">
                                                                <h6 class="text-muted">Maintenance Events</h6>
                                                                <h4 class="text-info" id="maintenanceEventCount">0</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="card bg-light">
                                                            <div class="card-body text-center">
                                                                <h6 class="text-muted">Avg Cost per Event</h6>
                                                                <h4 class="text-success" id="avgMaintenanceCost">ZMW 0.00</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="card bg-light">
                                                            <div class="card-body text-center">
                                                                <h6 class="text-muted">Last Maintenance</h6>
                                                                <h5 class="text-warning" id="lastMaintenanceDate">N/A</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Maintenance Details Table -->
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-hover" id="maintenanceDetailsTable">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th>Document Date</th>
                                                                <th>Job Card No</th>
                                                                <th>Requisition No</th>
                                                                <th>Issue No</th>
                                                                <th>Article Code</th>
                                                                <th>Description</th>
                                                                <th>Vehicle Assignment</th>
                                                                <th>Org Unit</th>
                                                                <th>Cost (ZMW)</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="maintenanceDetailsBody">
                                                            <tr>
                                                                <td colspan="10" class="text-center text-muted">
                                                                    Click "Load Maintenance Details" to view comprehensive maintenance data
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="specs" role="tabpanel">
                            <div style="background-color: #fff;"
                                 id="tms_chassis_details_form"
                                 name="tmsChassisDetailsForm"
                                 class="form"
                                 action="{{route('vehicle.chassis.detail')}}">
                                <input type="hidden" name="doctype" value="ChassisDetails"/>
                                <input type="hidden" name="headerId" value="{{$reference}}"/>
                                <input type="hidden" name="chassisDetailsId"
                                       value="{{$vehicle->chassisDetailsId ?? 0}}"/>
                                <x-error-view/>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="row">
                                            <fieldset class="border p-3">
                                                <legend style="width: inherit;">
                                                    <h4 class="pt-2">Technical Details</h4>
                                                </legend>
                                                <table aria-label="Technical Details"
                                                       role="table"
                                                       class="gs-0 gy-3 my-0">
                                                    <thead class="d-none">
                                                    <tr>
                                                        <th scope="row"></th>
                                                        <th scope="row"></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td class="frappe-control ">
                                                            <label for="chassisNumber" class="control-label reqd"
                                                                   style="padding-right: 0px;">
                                                                Chassis #:
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <div class="">
                                                                            <input type="text"
                                                                                   required
                                                                                   id="chassisNumber"
                                                                                   name="chassisNumber"
                                                                                   class="input-with-feedback
                                                                                   form-control view_mode"
                                                                                   maxlength="140"
                                                                                   data-fieldtype="Link"
                                                                                   data-fieldname="company"
                                                                                   placeholder=""
                                                                                   data-doctype="ChassisDetails"
                                                                                   data-target="Company"
                                                                                   autocomplete="off" role="combobox"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="frappe-control">
                                                            <div class="clearfix">
                                                                <label for="engineNumber" class="control-label reqd"
                                                                       style="padding-right: 0px;">Engine #:</label>
                                                                <span class="help"></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <div class="">
                                                                            <input type="text"
                                                                                   required
                                                                                   class="input-with-feedback
                                                                                   form-control view_mode"
                                                                                   maxlength="140" data-fieldtype="Link"
                                                                                   data-fieldname="company"
                                                                                   id="engineNumber"
                                                                                   name="engineNumber"
                                                                                   placeholder=""
                                                                                   data-doctype="ChassisDetails"
                                                                                   autocomplete="off"/>
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="frappe-control ">
                                                            <label for="whiteBookSerial" class="control-label reqd"
                                                                   style="padding-right: 0px;">
                                                                White Book Serial #:
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <div class="">
                                                                            <input type="text"
                                                                                   class="input-with-feedback
                                                                                   form-control view_mode"
                                                                                   maxlength="50"
                                                                                   required
                                                                                   data-fieldname="company"
                                                                                   id="whiteBookSerial"
                                                                                   name="whiteBookSerial"
                                                                                   placeholder=""
                                                                                   autocomplete="off">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="frappe-control">
                                                            <div class="clearfix" style="display: none;">
                                                                <label for="stickerRegistrationNumber"
                                                                       class="control-label"
                                                                       style="padding-right: 0px;">
                                                                    Sticker #:
                                                                </label>
                                                                <span class="help"></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper" style="display: none;">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <div class="">
                                                                            <input type="text"
                                                                                   class="input-with-feedback
                                                                                   form-control view_mode"
                                                                                   maxlength="140"
                                                                                   name="stickerRegistrationNumber"
                                                                                   id="stickerRegistrationNumber"
                                                                                   placeholder=""
                                                                                   data-doctype="ChassisDetails"
                                                                                   autocomplete="off"/>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <p class="help-box small text-muted"></p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="frappe-control ">
                                                            <label for="yearOfManufacture" class="control-label reqd"
                                                                   style="padding-right: 0px;">
                                                                Year Manufactured:
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <div class="">
                                                                            <input
                                                                                    date-format="YYYY"
                                                                                    class="input-with-feedback
                                                                                form-control
                                                                                number_input view_mode"
                                                                                    type="number" min="1990"
                                                                                    max="{{date('Y')}}"
                                                                                    step="1"
                                                                                    required
                                                                                    id="yearOfManufacture"
                                                                                    name="yearOfManufacture"
                                                                                    placeholder=""
                                                                                    data-doctype="ChassisDetails"/>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="frappe-control">
                                                            <div class="clearfix">
                                                                <label for="registrationDate" class="control-label reqd"
                                                                       style="padding-right: 0px;">Reg. Date:</label>
                                                                <span class="help"></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <div class="">
                                                                            <input type="date"
                                                                                   max="{{
                                                                                         date('Y-m-d',
                                                                                         strtotime(Carbon::now()))
                                                                                        }}"
                                                                                   required
                                                                                   class="input-with-feedback
                                                                                   form-control view_mode"
                                                                                   data-fieldname="registrationDate"
                                                                                   name="registrationDate"
                                                                                   id="registrationDate"
                                                                                   placeholder=""
                                                                                   data-doctype="ChassisDetails"
                                                                            />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr class="d-none">
                                                        <td>
                                                            <div class="clearfix">
                                                                <label for="dateOnRoad" class="control-label"
                                                                       style="padding-right: 0px;">
                                                                    Date on road :
                                                                </label>
                                                                <span class="help"></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <input type="date" name="dateOnRoad" id="dateOnRoad"
                                                                           disabled
                                                                           autocomplete="off"
                                                                           class="input-with-feedback
                                                                           form-control view_mode"
                                                                           data-fieldtype="Datetime"
                                                                           data-fieldname="first_date_on_road"
                                                                           placeholder=""
                                                                           data-doctype="ChassisDetails"/>
                                                                </div>

                                                            </div>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="frappe-control ">
                                                            <label for="chargeOutRate" class="control-label reqd"
                                                                   style="padding-right: 0px;">
                                                                Charge-Out Rate (/Km):
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <div class="input-group">
                                                                            <input type="text"
                                                                                   name="chargeOutRate"
                                                                                   id="chargeOutRate"
                                                                                   class="input-with-feedback
                                                                                   form-control view_mode"
                                                                                   required
                                                                                   placeholder=""
                                                                                   data-doctype="ChassisDetails"
                                                                                   autocomplete="off"/>
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </td>
                                                        <td class="frappe-control" colspan="1">
                                                            <div class="clearfix">
                                                                <label for="requiredMinimumDrivingLicense"
                                                                       class="control-label reqd"
                                                                       style="padding-right: 0px;">Driving License
                                                                    Class:</label>
                                                                <span class="help"></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <div class="">
                                                                            <select
                                                                                    class="form-control
                                                                                    form-control-sm view_mode"
                                                                                    required
                                                                                    name="requiredMinimumDrivingLicense"
                                                                                    id="requiredMinimumDrivingLicense"
                                                                                    data-doctype="ChassisDetails">
                                                                                <option>--Select Licence Class--
                                                                                </option>
                                                                                <option
                                                                                        v-for="license in licenseTypes"
                                                                                        :value="license.code">
                                                                                    @{{ license.name}}
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="frappe-control ">
                                                            <label for="initialOdometerReading"
                                                                   class="control-label reqd"
                                                                   style="padding-right: 0px;">
                                                                Initial Odometer Reading:
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <input type="number"

                                                                               name="initialOdometerReading"
                                                                               id="initialOdometerReading"
                                                                               class="input-with-feedback
                                                                               number_input form-control view_mode"
                                                                               placeholder=""
                                                                               required
                                                                               data-doctype="ChassisDetails"
                                                                               autocomplete="off"/>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </td>
                                                        <td class="frappe-control" colspan="1">
                                                            <div class="clearfix" style="display: none;">
                                                                <label for="currentOdometerReading"
                                                                       class="control-label reqd"
                                                                       style="padding-right: 0px;">Km Done:</label>
                                                                <span class="help"></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper" style="display: none;">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <div class="">
                                                                            <input type="text"
                                                                                   class="input-with-feedback
                                                                                   number_input form-control view_mode"
                                                                                   required
                                                                                   name="currentOdometerReading"
                                                                                   id="currentOdometerReading"
                                                                                   value="0"
                                                                                   placeholder=""
                                                                                   data-doctype="ChassisDetails"
                                                                                   autocomplete="off"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr style="display: none;">
                                                        <td class="frappe-control ">
                                                            <label for="odometerReadingLastService"
                                                                   class="control-label reqd"
                                                                   style="padding-right: 0px;">
                                                                Odometer Reading Last Service
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <input type="text"
                                                                               name="odometerReadingLastService"
                                                                               id="odometerReadingLastService"
                                                                               value="0"
                                                                               class="input-with-feedback
                                                                               number_input form-control view_mode"
                                                                               required
                                                                               placeholder=""
                                                                               data-doctype="ChassisDetails"
                                                                               autocomplete="off">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </td>
                                                        <td class="frappe-control" colspan="1">
                                                            <div class="clearfix">
                                                                <label for="nextServiceOdometerReading"
                                                                       class="control-label reqd"
                                                                       style="padding-right: 0px;">Next Service Odometer
                                                                    Reading:</label>
                                                                <span class="help"></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">

                                                                        <input type="text"
                                                                               class="input-with-feedback
                                                                               number_input form-control "
                                                                               required
                                                                               name="nextServiceOdometerReading"
                                                                               id="nextServiceOdometerReading"
                                                                               value="0"
                                                                               placeholder=""
                                                                               data-doctype="ChassisDetails"
                                                                               autocomplete="off"/>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr style="display: none;">
                                                        <td class="frappe-control ">
                                                            <label for="inspectionDate" class="control-label reqd"
                                                                   style="padding-right: 0px;">
                                                                Inspection Date:
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <input type="date"
                                                                               max="{{ date('Y-m-d',
                                                                                strtotime(Carbon::now())) }}"
                                                                               name="inspectionDate"
                                                                               id="inspectionDate"
                                                                               value="{{ date('Y-m-d',
                                                                                strtotime(Carbon::now()))}}"
                                                                               required
                                                                               class="input-with-feedback
                                                                               form-control  view_mode"
                                                                               placeholder=""
                                                                               data-doctype="ChassisDetails"
                                                                               autocomplete="off">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </td>
                                                        <td class="frappe-control" colspan="1">
                                                            <div class="clearfix" style="display: none;">
                                                                <label for="odometerReset"
                                                                       class="control-label"
                                                                       style="padding-right: 0px;">
                                                                    Odometer Reset:</label>
                                                                <span class="help"></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="control-input-wrapper" style="display: none;">
                                                                <div class="control-input">
                                                                    <div class="link-field ui-front"
                                                                         style="position: relative;">
                                                                        <input type="checkbox"
                                                                               class="input-with-feedback
                                                                               form-check-input "
                                                                               disabled
                                                                               name="odometerReset"
                                                                               id="odometerReset"
                                                                               v-model="chassisDetails.odometerReset"
                                                                               placeholder=""
                                                                               data-doctype="ChassisDetails"/>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    </tbody>
                                                </table>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row"
                                             v-if="documents && documents.insurance && documents.certificate">
                                            <fieldset class="border p-3">
                                                <legend style="width: inherit;">
                                                    <h4 id="documents" class="pt-2">Documents</h4>
                                                </legend>
                                                <table aria-describedby="documents"
                                                       role="table" class="">
                                                    <thead>
                                                    <tr class="bg-dark">
                                                        <th scope="row">Document Type</th>
                                                        <th scope="row">Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tr>
                                                        <td class="pl-3">Motor Vehicle Certificate</td>
                                                        <td class="pl-3">
                                                            <button data-zfm-view-file="certificate"
                                                                    type="button"
                                                                    :data-document-url="'/storage/'
                                                                    +documents.certificate?.path"
                                                                    class="btn btn-sm btn-success">
                                                                <i class="fa fa-paperclip"></i>
                                                                View File
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="pl-3">Insurance Cover Note</td>
                                                        <td class="pl-3">
                                                            <button data-zfm-view-file="insurance"
                                                                    type="button"
                                                                    :data-document-url="'/storage/'
                                                                    +documents.insurance?.path"
                                                                    class="btn btn-sm btn-success">
                                                                <i class="fa fa-paperclip"></i>
                                                                View File
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-10">
                                    <div class="col-6">
                                        <form id="tms_engine_details_form"
                                              name="engineDetailsForm"
                                              class="form"
                                              action="{{route('vehicle.engine.detail')}}">
                                            <input type="hidden" name="doctype" value="EngineDetails"/>
                                            <input type="hidden" name="headerId" value="{{$reference}}"/>
                                            <input type="hidden" name="engineDetailsId"
                                                   value="{{$vehicle->engineDetailsId ?? 0}}"/>

                                            <x-error-view/>
                                            <fieldset class="border p-3">
                                                <legend style="width: inherit;">
                                                    <h4 class="pt-2">Engine Details</h4>
                                                </legend>
                                                <div class="col-xs-12 col-sm-12 col-md-12 pl-0">
                                                    <table role="table"
                                                           aria-label="engine details"
                                                           class="align-middle gs-0 gy-3 my-0">
                                                        <thead class="d-none">
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td class="frappe-control ">
                                                                <label for="numberOfCylinders"
                                                                       class="control-label reqd"
                                                                       style="padding-right: 0px;">
                                                                    Number Of Cylinders:
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div>
                                                                                <input type="number"
                                                                                       max="16"
                                                                                       min="2"
                                                                                       required
                                                                                       id="numberOfCylinders"
                                                                                       name="numberOfCylinders"
                                                                                       class="input-with-feedback
                                                                                       form-control
                                                                                       number_input view_mode"
                                                                                       data-fieldtype="Link"
                                                                                       data-fieldname="company"
                                                                                       data-doctype="EngineDetails"
                                                                                       autocomplete="off"/>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="frappe-control">
                                                                <div class="clearfix">
                                                                    <label for="engineCapacity"
                                                                           class="control-label reqd"
                                                                           style="padding-right: 0px;">Engine Capacity
                                                                        (cc)
                                                                        :</label>
                                                                    <span class="help"></span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div class="input-group">
                                                                                <input type="number"
                                                                                       class="input-with-feedback
                                                                                       form-control
                                                                                       number_input
                                                                                       view_mode"
                                                                                       max="10000"
                                                                                       required
                                                                                       data-fieldtype="Link"
                                                                                       data-fieldname="company"
                                                                                       id="engineCapacity"
                                                                                       name="engineCapacity"
                                                                                       placeholder=""
                                                                                       data-doctype="EngineDetails"/>
                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="frappe-control">
                                                                <div class="clearfix">
                                                                    <label for="actualEnginePower"
                                                                           class="control-label reqd"
                                                                           style="padding-right: 0px;">
                                                                        Engine Horse Power (hp):
                                                                    </label>
                                                                    <span class="help"></span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div class="input-group">
                                                                                <input type="number"
                                                                                       required
                                                                                       class="input-with-feedback
                                                                                       form-control  number_input
                                                                                       view_mode"
                                                                                       maxlength="140"
                                                                                       name="actualEnginePower"
                                                                                       id="actualEnginePower"
                                                                                       placeholder=""
                                                                                       data-doctype="EngineDetails"
                                                                                       autocomplete="off"/>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <p class="help-box small text-muted"></p>
                                                                </div>
                                                            </td>

                                                            <td style="display: none;" class="frappe-control ">
                                                                <label for="claimedEnginePower"
                                                                       class="control-label reqd"
                                                                       style="padding-right: 0px;">
                                                                    Horse Power:
                                                                </label>
                                                            </td>
                                                            <td style="display: none;">
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div class="input-group">
                                                                                <input type="number"
                                                                                       required
                                                                                       class="input-with-feedback
                                                                                       form-control
                                                                                       number_input view_mode"
                                                                                       maxlength="140"
                                                                                       value="0"
                                                                                       data-fieldname="company"
                                                                                       id="claimedEnginePower"
                                                                                       name="claimedEnginePower"
                                                                                       placeholder=""
                                                                                       data-doctype="EngineDetails"
                                                                                       data-target="Company"
                                                                                       autocomplete="off"/>
                                                                                <div class="input-group-append
                                                                                        pl-3 pr-3 align-self-center">
                                                                                    hp
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="frappe-control">
                                                                <div class="clearfix">
                                                                    <label for="fuelTypes" class="control-label reqd"
                                                                           style="padding-right: 0px;">
                                                                        Fuel Type:
                                                                    </label>
                                                                    <span class="help"></span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div>
                                                                                <select
                                                                                        required
                                                                                        class="input-with-feedback
                                                                                        form-control view_mode"
                                                                                        id="fuelTypes"
                                                                                        name="fuelTypes"
                                                                                        data-doctype="EngineDetails">
                                                                                    <option
                                                                                            v-for="fuelType in
                                                                                            fuelTypes"
                                                                                            :value="fuelType.
                                                                                            code_article"
                                                                                            :key="fuelType.
                                                                                            code_article">
                                                                                        @{{ fuelType.description }}
                                                                                    </option>
                                                                                </select>
                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </td>

                                                            <td class="frappe-control " style="display: none">
                                                                <label for="engineBrand" class="control-label reqd"
                                                                       style="padding-right: 0px;">
                                                                    Engine Brand:
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <input type="hidden"
                                                                                   data-fieldtype="Link"
                                                                                   data-fieldname="company"
                                                                                   id="engineBrand"
                                                                                   name="engineBrand"
                                                                                   value="N/A"
                                                                                   data-doctype="EngineDetails"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="frappe-control ">
                                                                <label for="engineType"
                                                                       class="control-label reqd"
                                                                       style="padding-right: 0px;">
                                                                    Engine Code:
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <input
                                                                                    required
                                                                                    class="input-with-feedback
                                                                                    form-control view_mode"
                                                                                    data-fieldtype="Link"
                                                                                    data-fieldname="company"
                                                                                    placeholder="e.g 1NZ"
                                                                                    id="engineType"
                                                                                    name="engineType"
                                                                                    v-model="engineDetails.engineType"
                                                                                    data-doctype="EngineDetails"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <label for="transmission_type"
                                                                       class="control-label reqd"
                                                                       style="padding-right: 0px;">
                                                                    Transmission Type:
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <select
                                                                                    required
                                                                                    id="transmission_type"
                                                                                    name="transmission_type"
                                                                                    class="form-control
                                                                                    form-control-sm view_mode"
                                                                                    data-doctype="EngineDetails"
                                                                                    @change="transmissionTypeChanged">
                                                                                <option
                                                                                        v-for="transmissionType in transmissionTypes"
                                                                                        :value="transmissionType.code">
                                                                                    @{{ transmissionType.name }}
                                                                                </option>
                                                                            </select>
                                                                            <input type="hidden" required/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>


                                                        <tr>
                                                            <td class="frappe-control">
                                                                <div class="clearfix">
                                                                    <label for="fuelConsumption"
                                                                           class="control-label reqd"
                                                                           style="padding-right: 0px;">
                                                                        Fuel Consumption (Km/Ltr):
                                                                    </label>
                                                                    <span class="help"></span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div title="Number of kilometers per litre"
                                                                     class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div class="input-group">
                                                                                <input type="text"
                                                                                       required
                                                                                       class="input-with-feedback
                                                                                       form-control view_mode"
                                                                                       maxlength="4"
                                                                                       max="25"
                                                                                       name="fuelConsumption"
                                                                                       id="fuelConsumption"
                                                                                       placeholder=""
                                                                                       data-doctype="EngineDetails"
                                                                                       autocomplete="off">
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <p class="help-box small text-muted"></p>
                                                                </div>
                                                            </td>
                                                            <td class="frappe-control ">
                                                            </td>
                                                            <td>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="frappe-control">
                                                                <div class="clearfix">
                                                                    <label for="tank_capacity"
                                                                           class="control-label reqd"
                                                                           style="padding-right: 0px;">
                                                                        Main Tank Capacity (Ltr):
                                                                    </label>
                                                                    <span class="help"></span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div class="input-group">
                                                                                <input type="number"
                                                                                       class="input-with-feedback
                                                                                       number_input
                                                                                       form-control view_mode"
                                                                                       maxlength="4"
                                                                                       required
                                                                                       name="tank_capacity"
                                                                                       id="tank_capacity"
                                                                                       placeholder=""
                                                                                       autocomplete="off">
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="clearfix">
                                                                    <label for="sub_tank_capacity" class="control-label"
                                                                           style="padding-right: 0px;">
                                                                        Sub Tank Capacity <small>(If Any)</small> (Ltr):
                                                                    </label>
                                                                    <span class="help"></span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div class="input-group">
                                                                                <input type="number"
                                                                                       maxlength="4"
                                                                                       class="input-with-feedback
                                                                                       number_input
                                                                                       form-control view_mode"
                                                                                       name="sub_tank_capacity"
                                                                                       id="sub_tank_capacity"
                                                                                       placeholder=""
                                                                                       autocomplete="off">
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </fieldset>

                                            <fieldset class="border p-3">
                                                <legend style="width: inherit;">
                                                    <h4 class="pt-2">Tyres</h4>
                                                </legend>
                                                <div class="col-xs-12 col-sm-8 col-md-8 pl-0">
                                                    <table aria-label="tyre data"
                                                           role="table"
                                                           class="align-middle gy-3 my-0">
                                                        <thead class="d-none">
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td class="frappe-control ">
                                                                <label for="numberOfTyres" class="control-label reqd"
                                                                       style="padding-right: 0px;">
                                                                    Total Number:
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div class="fv-row">
                                                                                <input type="number"
                                                                                       title="The number of tyres
                                                                                       the vehicle has"
                                                                                       id="numberOfTyres"
                                                                                       name="numberOfTyres"
                                                                                       class="input-with-feedback
                                                                                       form-control
                                                                                       number_input
                                                                                       view_mode"
                                                                                       maxlength="140"
                                                                                       placeholder=""
                                                                                       data-doctype="EngineDetails"
                                                                                       autocomplete="off"/>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="frappe-control">
                                                                <div class="clearfix">
                                                                    <div class="clearfix">
                                                                        <label for="tyreBrand"
                                                                               class="control-label reqd"
                                                                               style="padding-right: 0px;">Brand
                                                                            :</label>
                                                                        <span class="help"></span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            {{-- v-model="otherDetails.tyreBrand"--}}
                                                                            <input type="text"
                                                                                   title="The tyre make e.g Good Year"
                                                                                   class="form-control view_mode"
                                                                                   maxlength="140"
                                                                                   id="tyreBrand"
                                                                                   name="tyreBrand"
                                                                                   placeholder="e.g Good Year"/>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="frappe-control ">
                                                                <div class="clearfix">
                                                                    <label for="frontTyreSize"
                                                                           class="control-label field-required"
                                                                           style="padding-right: 0px;">
                                                                        Front Size:
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div>
                                                                                <input type="text"
                                                                                       class="form-control
                                                                                       tyre-size view_mode"
                                                                                       required
                                                                                       id="frontTyreSize"
                                                                                       name="frontTyreSize"
                                                                                       autocomplete="off"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="frappe-control">
                                                                <div class="clearfix">
                                                                    <label for="rearTyreSize"
                                                                           class="control-label field-required"
                                                                           style="padding-right: 0px;">
                                                                        Rear Size:
                                                                    </label>
                                                                    <span class="help"></span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div>
                                                                                <input type="text"
                                                                                       class="form-control
                                                                                       tyre-size view_mode"
                                                                                       name="rearTyreSize"
                                                                                       id="rearTyreSize"/>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <p class="help-box small text-muted"></p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </fieldset>

                                            <fieldset class="border p-3">
                                                <legend style="width: inherit;">
                                                    <h4 class="pt-2">Battery</h4>
                                                </legend>
                                                <div class="col-xs-12 col-sm-8 col-md-8 pl-0">
                                                    <table role="table"
                                                           aria-label="battery data"
                                                           class="table table-row-dashed align-middle gy-3 my-0">
                                                        <thead class="d-none">
                                                        <tr>
                                                            <th scope="row"></th>
                                                            <th scope="row"></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td class="frappe-control ">
                                                                <label for="batteryBrand"
                                                                       class="control-label reqd"
                                                                       style="padding-right: 0px;">
                                                                    Brand:
                                                                </label>
                                                            </td>
                                                            <td style="width: 25%;">
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div>
                                                                                <input type="text"
                                                                                       id="batteryBrand"
                                                                                       name="batteryBrand"
                                                                                       class="input-with-feedback
                                                                                       form-control view_mode"
                                                                                       data-fieldtype="Link"
                                                                                       data-fieldname="company"
                                                                                       data-doctype="OtherDetails"
                                                                                       autocomplete="off"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="frappe-control">
                                                                <div class="clearfix">
                                                                    <label for="batterySize"
                                                                           class="control-label reqd"
                                                                           style="padding-right: 0px;">Size :</label>
                                                                    <span class="help"></span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div>
                                                                                <input
                                                                                        class="form-control
                                                                                        input-with-feedback
                                                                                        view_mode"
                                                                                        data-fieldtype="Link"
                                                                                        data-fieldname="company"
                                                                                        id="batterySize"
                                                                                        name="batterySize"
                                                                                        data-doctype="OtherDetails"/>
                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="frappe-control ">
                                                                <label for="batteryPower" class="control-label reqd"
                                                                       style="padding-right: 0px;">
                                                                    Power (Volts):
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <div class="control-input-wrapper">
                                                                    <div class="control-input">
                                                                        <div class="link-field ui-front"
                                                                             style="position: relative;">
                                                                            <div class="input-group ">
                                                                                <select type="number"
                                                                                        class="form-control view_mode"
                                                                                        data-fieldtype="Link"
                                                                                        data-fieldname="company"
                                                                                        id="batteryPower"
                                                                                        name="batteryPower"
                                                                                        data-target="Company">
                                                                                    <option value="12">12</option>
                                                                                    <option value="24">24</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>

                                                            <td class="frappe-control"></td>
                                                            <td></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                    <div class="col-6 row">
                                        <div class="col-md-6" v-if="images && images.frontView">
                                            <div class="card text-center my-2">
                                                <div class="form-group">
                                                    <div class="imagePreview"
                                                         :style='{backgroundImage: "url(/storage/"
                                                         + images.frontView.path + ")",}'>
                                                        <p class="img_title fs-2x fw-bold mb-10 text-white">
                                                            Front View
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6" v-if="images && images.rearView">
                                            <div class="card-px text-center my-2">
                                                <div class="form-group">
                                                    <div class="imagePreview"
                                                         :style='{backgroundImage: "url(/storage/"
                                                         + images.rearView.path + ")",}'>
                                                        <p class="img_title fs-2x fw-bold mb-10 text-white">
                                                            Rear View
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6" v-if="images && images.rightView">
                                            <div class="card text-center my-2">

                                                <div class="form-group">
                                                    <div class="imagePreview"
                                                         :style='{backgroundImage: "url(/storage/"
                                                         + images.rightView.path + ")",}'>
                                                        <p class="img_title fs-2x fw-bold mb-10 text-white">
                                                            Right View
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6"
                                             v-if="images && images.leftView">
                                            <div class="card text-center my-2">

                                                <div class="form-group">
                                                    <div class="imagePreview"
                                                         :style='{backgroundImage: "url(/storage/"
                                                         + images.leftView.path + ")",}'>
                                                        <p class="img_title fs-2x fw-bold mb-10 text-white">
                                                            Left View
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <fieldset class="border p-3">
                                <legend style="width: inherit;">
                                    <h4 class="pt-2">Dimensions</h4>
                                </legend>
                                <div id="tms_body_weight_form" style="background-color: #fff;"
                                     class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                     data-action="{{route('vehicle.body.detail')}}">
                                    <input type="hidden" name="doctype" value="BodyDetails"/>
                                    <input type="hidden" name="headerId" value="{{$reference}}"/>
                                    <input type="hidden" name="weightDetailsId"
                                           value="{{$vehicle->weightDetailsId ?? 0}}"/>
                                    <x-error-view/>
                                    <div class="col-6">
                                        <table role="table"
                                               aria-label="dimensions"
                                               class="table table-row-dashed align-middle gy-3 my-0">
                                            <thead class="d-none">
                                            <tr>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="frappe-control ">
                                                    <label for="vehicleHeight" class="control-label reqd"
                                                           style="padding-right: 0px;">
                                                        Height (m):
                                                    </label>
                                                </td>
                                                <td colspan="1">
                                                    <div class="control-input-wrapper">
                                                        <div class="control-input">
                                                            <div class="link-field ui-front"
                                                                 style="position: relative;">
                                                                <div>
                                                                    <input type="text"
                                                                           class="input-with-feedback
                                                                           number_input form-control view_mode"
                                                                           maxlength="4"
                                                                           data-fieldtype="Link"
                                                                           data-fieldname="company"
                                                                           id="vehicleHeight"
                                                                           name="height"
                                                                           v-model="bodyDetails.height"
                                                                           placeholder=""
                                                                           data-doctype="BodyDetails"/>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="frappe-control">
                                                    <label for="length" class="control-label reqd"
                                                           style="padding-right: 0px;">
                                                        Length (m):
                                                    </label>
                                                </td>
                                                <td>
                                                    <input type="text"
                                                           class="input-with-feedback
                                                           number_input form-control view_mode"
                                                           maxlength="140"
                                                           required
                                                           data-fieldtype="Link"
                                                           data-fieldname="company"
                                                           id="length"
                                                           name="length"
                                                           v-model="bodyDetails.length"
                                                           placeholder=""
                                                           data-doctype="BodyDetails"/>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="frappe-control ">
                                                    <label for="width" class="control-label reqd"
                                                           style="padding-right: 0px;">
                                                        Width (m):
                                                    </label>
                                                </td>
                                                <td colspan="1">
                                                    <div class="control-input-wrapper">
                                                        <div class="control-input">
                                                            <div class="link-field ui-front"
                                                                 style="position: relative;">
                                                                <div>
                                                                    <input type="text"
                                                                           class="input-with-feedback
                                                                           number_input form-control view_mode"
                                                                           maxlength="140"
                                                                           required
                                                                           data-fieldtype="Link"
                                                                           data-fieldname="company"
                                                                           id="width"
                                                                           name="width"
                                                                           v-model="bodyDetails.width"
                                                                           placeholder=""
                                                                           data-doctype="BodyDetails"/>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="frappe-control"></td>
                                                <td></td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                    <h4>Interior</h4>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="frappe-control ">
                                                    <label for="vehicleWidth" class="control-label reqd"
                                                           style="padding-right: 0px;">
                                                        Seat Capacity:
                                                    </label>
                                                </td>
                                                <td>
                                                    <div class="control-input-wrapper">
                                                        <div class="control-input">
                                                            <div class="link-field ui-front"
                                                                 style="position: relative;">
                                                                <div>
                                                                    <input type="text"
                                                                           class="input-with-feedback
                                                                           form-control view_mode"
                                                                           maxlength="15"
                                                                           id="seatCapFront"
                                                                           name="seatCapFront"
                                                                           v-model="bodyDetails.seatCapFront"
                                                                           placeholder=""
                                                                           data-doctype="BodyDetails"
                                                                           autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="frappe-control">
                                                </td>
                                                <td>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="frappe-control">
                                                </td>
                                                <td>

                                                </td>
                                                <td class="frappe-control">
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <h4>Weight</h4>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="frappe-control ">
                                                    <label for="tareWeight" class="control-label reqd"
                                                           style="padding-right: 0px;">
                                                        Net Weight (kg):
                                                    </label>
                                                </td>
                                                <td colspan="1">
                                                    <div class="control-input-wrapper">
                                                        <div class="control-input">
                                                            <div class="link-field ui-front"
                                                                 style="position: relative;">
                                                                <div>
                                                                    <input type="text"
                                                                           required
                                                                           class="input-with-feedback
                                                                           form-control view_mode weight_control"
                                                                           maxlength="140"
                                                                           data-fieldtype="Link"
                                                                           data-fieldname="company"
                                                                           id="tareWeight"
                                                                           name="tareWeight"
                                                                           v-model="weightDetails.tareWeight"
                                                                           placeholder=""
                                                                           data-doctype="WeightDetails"/>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="frappe-control">
                                                    <label for="grossWeight" class="control-label reqd"
                                                           style="padding-right: 0px;">
                                                        Gross Weight (kg):
                                                    </label>
                                                </td>
                                                <td>
                                                    <input type="text"
                                                           class="input-with-feedback
                                                           form-control view_mode
                                                           weight_control"
                                                           maxlength="140"
                                                           required
                                                           data-fieldtype="Link"
                                                           data-fieldname="company"
                                                           id="grossWeight"
                                                           name="grossWeight"
                                                           v-model="weightDetails.grossWeight"
                                                           placeholder=""
                                                           data-doctype="WeightDetails"/>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <div class="tab-pane fade" id="financial" role="tabpanel">
                            <form id="tms_costing_valuation_form" style="background-color: #fff;"
                                  name="tms_costing_valuation_form"
                                  class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                  action="{{route('vehicle.cost.detail')}}">
                                <input type="hidden" name="doctype" value="CostingDetails"/>
                                <input type="hidden" name="headerId" value="{{$reference}}"/>
                                <input type="hidden" name="costAndValuationId"
                                       value="{{$vehicle->costAndValuationId ?? 0}}"/>
                                <x-error-view/>
                                <div class="col-8">
                                    <table class="" role="table" aria-label="cost">
                                        <thead class="d-none">
                                        <tr>
                                            <th scope="row"></th>
                                            <th scope="row"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="frappe-control d-none">
                                                <label class="app-field-label reqd"
                                                       for="staff_no"> :
                                                </label>
                                            </td>
                                            <td>

                                            </td>
                                            <td class="frappe-control"></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="frappe-control ">
                                                <label for="supplierName" class="control-label reqd"
                                                       style="padding-right: 0px;">
                                                    Supplier Name:
                                                </label>
                                            </td>
                                            <td colspan="1">
                                                <div class="control-input-wrapper">
                                                    <div class="control-input">
                                                        <div class="link-field ui-front" style="position: relative;">
                                                            <div>
                                                                <select class="form-control form-control-sm view_mode"
                                                                        data-doctype="CostingDetails"
                                                                        data-value=""
                                                                        id="supplierName"
                                                                        name="supplierName">
                                                                </select>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="frappe-control"></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td class="frappe-control ">
                                                <label for="costPrice" class="control-label reqd"
                                                       style="padding-right: 0px;">
                                                    Cost Price:
                                                </label>
                                            </td>
                                            <td>
                                                <div class="control-input-wrapper">
                                                    <div class="control-input">
                                                        <div class="link-field ui-front" style="position: relative;">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-addon">
                                                                        <select name="bookValueCurrency"
                                                                                class="form-control form-control-sm"
                                                                                style="height: 2.5em;
                                                                                border-radius: 0;">
                                                                            <option value="001">ZMW</option>
                                                                            <option value="002">USD</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <input type="text"
                                                                       class="input-with-feedback
                                                                       form-control view_mode"
                                                                       maxlength="15"
                                                                       data-a-sign="ZMW "
                                                                       id="costPrice"
                                                                       name="costPrice"
                                                                       placeholder=""
                                                                       autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="frappe-control">
                                                <div class="clearfix">
                                                    <label for="yearOfPurchase" class="control-label reqd"
                                                           style="padding-right: 0px;">
                                                        Year Purchased:
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="control-input-wrapper">
                                                    <div class="control-input">
                                                        <div class="link-field ui-front" style="position: relative;">
                                                            <div class="input-group">
                                                                <input type="number" min="1990" max="{{date('Y')}}"
                                                                       step="1"
                                                                       class="input-with-feedback
                                                                       form-control number_input view_mode"
                                                                       maxlength="4"
                                                                       name="yearOfPurchase"
                                                                       id="yearOfPurchase"
                                                                       data-doctype="CostingDetails"
                                                                       autocomplete="off">
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <p class="help-box small text-muted"></p>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="frappe-control ">
                                                <label for="bookValue" class="control-label reqd"
                                                       style="padding-right: 0px;">
                                                    Book Value:
                                                </label>
                                            </td>
                                            <td>
                                                <div class="control-input-wrapper">
                                                    <div class="control-input">
                                                        <div class="link-field ui-front" style="position: relative;">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-addon">
                                                                        <select name="bookValueCurrency"
                                                                                class="form-control
                                                                                form-control-sm"
                                                                                style="height: 2.5em;
                                                                                border-radius: 0;">
                                                                            <option value="001">ZMW</option>
                                                                            <option value="002">USD</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <input type="text"
                                                                       class="input-with-feedback
                                                                       form-control view_mode"
                                                                       id="bookValue"
                                                                       data-a-sign="ZMW "
                                                                       name="bookValue"
                                                                       placeholder=""
                                                                       data-doctype="CostingDetails"
                                                                       autocomplete="off"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="frappe-control">
                                                <div class="clearfix">
                                                    <label for="assetNumber" class="control-label reqd"
                                                           style="padding-right: 0px;">
                                                        Asset No. :
                                                    </label>
                                                    <span class="help"></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="control-input-wrapper">
                                                    <div class="control-input">
                                                        <div class="link-field ui-front" style="position: relative;">
                                                            <div>
                                                                <input type="text"
                                                                       class="input-with-feedback
                                                                       form-control view_mode"
                                                                       maxlength="140"
                                                                       data-fieldtype="Link"
                                                                       data-fieldname="company"
                                                                       id="assetNumber"
                                                                       name="assetNumber"
                                                                       placeholder=""/>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="frappe-control ">
                                                <label for="costOfLicense" class="control-label reqd"
                                                       style="padding-right: 0px;">
                                                    Cost Of License (Road Tax):
                                                </label>
                                            </td>
                                            <td>
                                                {{--v-model="costingAndValuation.costOfLicense"--}}
                                                <div class="control-input-wrapper">
                                                    <div class="control-input">
                                                        <div class="link-field ui-front" style="position: relative;">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-addon">
                                                                        ZMW
                                                                    </div>
                                                                </div>
                                                                <input type="text"
                                                                       class="input-with-feedback
                                                                       form-control number_input view_mode"
                                                                       id="costOfLicense"
                                                                       data-a-sign="ZMW"
                                                                       name="costOfLicense"
                                                                       placeholder=""
                                                                       data-target="Company">
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="frappe-control ">
                                                <label for="premium" class="control-label reqd"
                                                       style="padding-right: 0px;">
                                                    Insurance Premium:
                                                </label>
                                            </td>
                                            <td>
                                                <div class="control-input-wrapper">
                                                    <div class="control-input">
                                                        <div class="link-field ui-front" style="position: relative;">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-addon">
                                                                        ZMW
                                                                    </div>
                                                                </div>
                                                                <input type="text"
                                                                       class="input-with-feedback
                                                                       form-control view_mode"
                                                                       maxlength="140"
                                                                       id="premium"
                                                                       name="premium"
                                                                       placeholder=""/>
                                                            </div>
                                                        </div>
                                                        <small>10% of Cost Price</small>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="frappe-control ">
                                                <label for="premium" class="control-label"
                                                       style="padding-right: 0px;">
                                                    Purchase Order:
                                                </label>
                                            </td>
                                            <td>
                                                <div class="col-md-7 fv-row pl-0">
                                                    <div class="col-md-9 pl-0">
                                                        <input type="file"
                                                               accept="image/*,.pdf"
                                                               class="filer_input"
                                                               name="purchaseOrderDocument"/>
                                                    </div>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <div class="create_mode">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <table role="table"
                                           aria-label="attached documents"
                                           class="table align-middle table-row-dashed dataTable no-footer">
                                        <thead>
                                        <tr class="bg-dark">
                                            <th v-if="documents && documents.purchase_order">
                                                Document No.
                                            </th>
                                            <th>Document Type</th>
                                            <th>File Name</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tr>
                                            <td>
                                                <div class="input-group">
                                                    <input type="text"
                                                           data-action="{{route('verify.purchase.order')}}"
                                                           class="form-control form-control-sm view_mode"
                                                           id="purchase_order_number"
                                                           placeholder=""
                                                           name="purchase_order_number">
                                                    <div class="input-group-addon">
                                                        <button type="button" id="poSearchBtn"
                                                                name="poSearchBtn"
                                                                class="btn btn-primary btn-sm
                                                                border-radius-0 view_mode">
                                                            <i class="fas fa-search"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Purchase Order</td>
                                            <td v-if="documents && documents.purchase_order">@{{
                                                documents.purchase_order?.originalDocumentName }}
                                            </td>
                                            <td v-if="documents && documents.purchase_order">
                                                <button data-zfm-view-file="insurance"
                                                        type="button"
                                                        :data-document-url="'/storage/'+documents.purchase_order?.path"
                                                        class="btn btn-sm btn-success">View File
                                                </button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="accessoriesTab" role="tabpanel">
                            <div class="container-fluid pl-0" style="background-color: #fff;">
                                <div id="tms_accessories_form"
                                     name="tms_accessories_form"
                                     class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                     action="{{route('vehicle.accessories.save')}}">
                                    <input type="hidden" name="doctype" value="CostingDetails"/>
                                    <input type="hidden" name="headerId" value="{{$reference}}"/>
                                    <input type="hidden" name="accessoryHeaderId"
                                           value="{{$vehicle->accessoryHeaderId ?? 0}}"/>

                                    <x-error-view/>
                                    <div class="d-flex justify-content-end">
                                        <div class="create_mode">
                                            <button type="submit" id="saveVehicleAccessories"
                                                    class="btn btn-success btn-sm">
                                                <i class="fas fa-paper-plane"></i>
                                                <span class="indicator-label">
                                                    Save
                                                </span>
                                                <span class="indicator-progress">
                                                    Please wait...
                                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="container-fluid mt-5">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="row">

                                                    <div class="col">
                                                        <table aria-label="accessories"
                                                               role="tab"
                                                               class="table table-row-dashed
                                                               align-middle gs-0 table-bordered">
                                                            <thead>
                                                            <tr class="bg-dark">
                                                                <th scope="row" class="pl-2">Item</th>
                                                                <th scope="row">Present</th>
                                                                <th scope="row" class="pr-2">Not Present</th>
                                                                <th scope="row" class="pr-2">Remarks</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($accessories as $key => $accessory)
                                                                @if(($key%2) == 0)
                                                                    <tr>
                                                                        <td class="pl-2"
                                                                            style="width: 35%;">
                                                                            {{$accessory->name}}
                                                                        </td>
                                                                        <td>
                                                                            <input type="radio" value="YES" required
                                                                                   disabled
                                                                                   name="{{str_replace(' ','',
                                                                                    $accessory->code)}}">
                                                                        </td>
                                                                        <td>
                                                                            <input type="radio" value="NO" required
                                                                                   disabled
                                                                                   name="{{
                                                                                    str_replace(' ','',
                                                                                    $accessory->code)
                                                                                    }}"/>
                                                                        </td>
                                                                        <td style="width: 45%;">
                                                                            <input typeof="text"
                                                                                   name="COMMENT_{{
                                                                                    str_replace(' ','',
                                                                                    $accessory->code)
                                                                                   }}"
                                                                                   class="form-control
                                                                                   form-control-sm"/>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="col">
                                                        <table role="table" aria-label="accessories"
                                                               class="table table-row-dashed align-middle
                                                                 gs-0 table-bordered">
                                                            <thead>
                                                            <tr class="bg-dark">
                                                                <th class="pl-2">Item</th>
                                                                <th>Present</th>
                                                                <th class="pr-2">Not Present</th>
                                                                <th class="pr-2">Remarks</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($accessories as $key => $accessory)
                                                                @if(($key%2) != 0)
                                                                    <tr>
                                                                        <td class="pl-2" style="width: 35%;">
                                                                            {{$accessory->name}}
                                                                        </td>
                                                                        <td>
                                                                            <input type="radio"
                                                                                   required value="YES"
                                                                                   disabled
                                                                                   name="{{str_replace(' ','',
                                                                                    $accessory->code)}}">
                                                                        </td>
                                                                        <td>
                                                                            <input type="radio" required value="NO"
                                                                                   disabled
                                                                                   name="{{str_replace(' ','',
                                                                                    $accessory->code)}}">
                                                                        </td>
                                                                        <td style="width: 45%;">
                                                                            <input typeof="text"
                                                                                   name="COMMENT_{{str_replace(' ','',
                                                                                     $accessory->code)}}"
                                                                                   class="form-control form-control-sm">
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="serviceHistory" role="tabpanel">
                            <div class="container-fluid pl-0">
                                Service History
                            </div>
                        </div>

                        <div class="tab-pane fade" id="roadtax" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-body">

                                            @if($roadtax)
                                                <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group ">
                                                        <label class="m-0 p-0">Last Synced</label>
                                                        <input type="email" class="form-control-plaintext m-0 p-0"
                                                               id="exampleInputEmail1"
                                                               value="{{$roadtax->updated_at->toFormattedDateString()}}" readonly>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="m-0 p-0">Vehicle Registration Date</label>
                                                        <input type="email" class="form-control-plaintext m-0 p-0"
                                                               id="exampleInputEmail1"
                                                               value="{{$roadtax->valid_from->toFormattedDateString()}}"
                                                               readonly>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="m-0 p-0">RoadTax Valid till</label>
                                                        <input type="email" class="form-control-plaintext m-0 p-0"
                                                               id="exampleInputEmail1"
                                                               value="{{$roadtax->valid_to ?  $roadtax->valid_to ->toFormattedDateString() : '--'}}"
                                                               readonly>
                                                    </div>
                                                </div>

                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="m-0 p-0">Fitness Valid till</label>
                                                            <input type="email" class="form-control-plaintext m-0 p-0"
                                                                   id="exampleInputEmail1"
                                                                   value="{{$roadtax->fitness_expiry ?  $roadtax->fitness_expiry ->toFormattedDateString() : '--'}}"
                                                                   readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label class="m-0 p-0">Status</label>
                                                            <p>{{$roadtax->status ?? '--'}}</p>
                                                        </div>
                                                    </div>


                                            </div>
                                            @else
                                                <h3>This vehicle has no roadtax or fitness information</h3>
                                                @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="inspectionHistory" role="tabpanel">
                            <div class="container-fluid pl-0">
                                Inspection History
                            </div>
                        </div>

                        <div class="tab-pane fade" id="workOrders" role="tabpanel">
                            <div class="container-fluid pl-0">
                                Work Orders
                            </div>
                        </div>

                        <div class="tab-pane fade" id="serviceReminders" role="tabpanel">
                            <div class="container-fluid pl-0">
                                Service Reminders
                            </div>
                        </div>

                        <div class="tab-pane fade" id="odometerHistory" role="tabpanel">

                        </div>

                        <div class="tab-pane fade" id="renewalReminder" role="tabpanel">
                            <div class="container-fluid pl-0">
                                Renewal Reminder
                            </div>
                        </div>

                        <div class="tab-pane fade" id="fuelHistory" role="tabpanel">
                            <div class="container-fluid pl-0">
                                Fuel History
                            </div>
                        </div>

                        <div class="tab-pane fade" id="assignmentHistory" role="tabpanel">
                            <div class="container-fluid pl-0" style="background-color: #fff;">
                                @include('modules.vehicleManagement.onboarding.tabs.assignment_details')
                            </div>
                        </div>

                        <div class="tab-pane fade" id="costAnalysis" role="tabpanel">
                            <div class="container-fluid pl-0">
                                <!-- Vehicle Identification Header -->
                                @if($vehicle && $vehicle->registration_number)
                                <div class="alert alert-info mb-4">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('img/car.png') }}" alt="Vehicle" class="me-3" style="width: 60px; height: auto; border-radius: 8px;">
                                        <div>
                                            <strong>Cost Analysis for Vehicle:</strong> 
                                            <span class="badge bg-primary ms-2">{{ $vehicle->registration_number }}</span>
                                            @if($vehicle->brand_name || $vehicle->model_name)
                                                <span class="text-muted ms-2">
                                                    {{ $vehicle->brand_name ?? '' }} {{ $vehicle->model_name ?? '' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="alert alert-warning mb-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <div>
                                            <strong>No Vehicle Selected:</strong> 
                                            Please select a vehicle from the dashboard to view its cost analysis.
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Vehicle Cost Summary Cards -->
                                @if($vehicle && $vehicle->registration_number)
                                <div class="row mb-4">
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <div class="card bg-gradient-primary text-white shadow-lg border-0 h-100">
                                            <div class="card-body">
                                                <div class="d-flex flex-column">
                                                    <div class="text-start">
                                                        <div class="small text-white-50 mb-2">Total Fuel Cost</div>
                                                        <div class="h3 mb-2 fw-bold text-start">
                                                            ZMW {{ number_format($costSummary['fuel_summary']['total_fuel_cost'] ?? 0, 2) }}
                                                        </div>
                                                        <div class="small text-white-75 text-start">
                                                            <i class="fas fa-receipt me-1"></i>
                                                            {{ $costSummary['fuel_summary']['fuel_transactions'] ?? 0 }} transactions
                                                        </div>
                                                    </div>
                                                    <div class="text-end mt-auto">
                                                        <i class="fas fa-gas-pump fa-3x opacity-75"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-transparent border-0 text-white-50 small">
                                                <i class="fas fa-calendar me-1"></i> Last 12 months
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <div class="card bg-gradient-warning text-white shadow-lg border-0 h-100">
                                            <div class="card-body">
                                                <div class="d-flex flex-column">
                                                    <div class="text-start">
                                                        <div class="small text-white-50 mb-2">Total Maintenance Cost</div>
                                                        <div class="h3 mb-2 fw-bold text-start">
                                                            ZMW {{ number_format($costSummary['maintenance_summary']['total_maintenance_cost'] ?? 0, 2) }}
                                                        </div>
                                                        <div class="small text-white-75 text-start">
                                                            <i class="fas fa-tools me-1"></i>
                                                            {{ $costSummary['maintenance_summary']['maintenance_events'] ?? 0 }} events
                                                        </div>
                                                    </div>
                                                    <div class="text-end mt-auto">
                                                        <i class="fas fa-wrench fa-3x opacity-75"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-transparent border-0 text-white-50 small">
                                                <i class="fas fa-calendar me-1"></i> Last 12 months
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <div class="card bg-gradient-danger text-white shadow-lg border-0 h-100">
                                            <div class="card-body">
                                                <div class="d-flex flex-column">
                                                    <div class="text-start">
                                                        <div class="small text-white-50 mb-2">Total Operating Cost</div>
                                                        <div class="h3 mb-2 fw-bold text-start">
                                                            ZMW {{ number_format($costSummary['total_operating_cost'] ?? 0, 2) }}
                                                        </div>
                                                        <div class="small text-white-75 text-start">
                                                            <i class="fas fa-plus me-1"></i> Fuel + Maintenance
                                                        </div>
                                                    </div>
                                                    <div class="text-end mt-auto">
                                                        <i class="fas fa-calculator fa-3x opacity-75"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-transparent border-0 text-white-50 small">
                                                <i class="fas fa-chart-pie me-1"></i> Total expenditure
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <div class="card bg-gradient-info text-white shadow-lg border-0 h-100">
                                            <div class="card-body">
                                                <div class="d-flex flex-column">
                                                    <div class="text-start">
                                                        <div class="small text-white-50 mb-2">Avg Cost/Month</div>
                                                        <div class="h3 mb-2 fw-bold text-start">
                                                            ZMW {{ number_format(($costSummary['total_operating_cost'] ?? 0) / 12, 2) }}
                                                        </div>
                                                        <div class="small text-white-75 text-start">
                                                            <i class="fas fa-chart-line me-1"></i> Monthly average
                                                        </div>
                                                    </div>
                                                    <div class="text-end mt-auto">
                                                        <i class="fas fa-trending-up fa-3x opacity-75"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-transparent border-0 text-white-50 small">
                                                <i class="fas fa-calculator me-1"></i> Per month analysis
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cost Distribution Chart -->
                                <div class="row mb-4">
                                    <div class="col-lg-4 col-md-12 mb-3">
                                        <div class="card bg-white shadow-lg border-0">
                                            <div class="card-header bg-gradient-primary text-white border-0">
                                                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i> Cost Distribution</h5>
                                            </div>
                                            <div class="card-body p-4">
                                                <div id="vehicleCostDistributionChart" style="height:280px;"></div>
                                                <div class="mt-4">
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <div>
                                                            <div class="small text-muted">Fuel Cost</div>
                                                            <div class="h5 mb-0 fw-bold text-success">{{ number_format($costSummary['cost_breakdown']['fuel_percentage'] ?? 0, 1) }}%</div>
                                                        </div>
                                                        <div class="text-end">
                                                            <div class="small text-muted">ZMW {{ number_format($costSummary['fuel_summary']['total_fuel_cost'] ?? 0, 2) }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="progress mb-3" style="height: 10px;">
                                                        <div class="progress-bar bg-success" style="width: {{ $costSummary['cost_breakdown']['fuel_percentage'] ?? 0 }}%"></div>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <div class="small text-muted">Maintenance Cost</div>
                                                            <div class="h5 mb-0 fw-bold text-warning">{{ number_format($costSummary['cost_breakdown']['maintenance_percentage'] ?? 0, 1) }}%</div>
                                                        </div>
                                                        <div class="text-end">
                                                            <div class="small text-muted">ZMW {{ number_format($costSummary['maintenance_summary']['total_maintenance_cost'] ?? 0, 2) }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="progress" style="height: 10px;">
                                                        <div class="progress-bar bg-warning" style="width: {{ $costSummary['cost_breakdown']['maintenance_percentage'] ?? 0 }}%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-12 mb-3">
                                        <div class="card bg-white shadow-lg border-0">
                                            <div class="card-header bg-gradient-info text-white border-0">
                                                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i> Monthly Cost Trends</h5>
                                            </div>
                                            <div class="card-body p-4">
                                                <div id="vehicleMonthlyTrendsChart" style="height:280px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detailed Tables -->
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 mb-3">
                                        <div class="card bg-white shadow-lg border-0">
                                            <div class="card-header bg-gradient-success text-white border-0">
                                                <h5 class="mb-0"><i class="fas fa-gas-pump me-2"></i> Fuel Cost Analysis</h5>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="table-responsive">
                                                    <table class="table table-hover mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th class="border-0"><i class="fas fa-calendar me-1"></i> Period</th>
                                                                <th class="text-end border-0"><i class="fas fa-money-bill-wave me-1"></i> Cost</th>
                                                                <th class="text-end border-0"><i class="fas fa-receipt me-1"></i> Transactions</th>
                                                                <th class="text-end border-0"><i class="fas fa-chart-bar me-1"></i> Avg Cost</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($fuelCostAnalysis as $fuel)
                                                                <tr>
                                                                    <td>{{ $fuel->period ?? '--' }}</td>
                                                                    <td class="text-end">{{ number_format($fuel->total_cost ?? 0, 2) }}</td>
                                                                    <td class="text-end">{{ $fuel->transaction_count ?? 0 }}</td>
                                                                    <td class="text-end">{{ number_format($fuel->avg_cost_per_transaction ?? 0, 2) }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="4" class="text-center text-muted p-3">No fuel data available</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 mb-3">
                                        <div class="card bg-white shadow-lg border-0">
                                            <div class="card-header bg-gradient-warning text-white border-0">
                                                <h5 class="mb-0"><i class="fas fa-wrench me-2"></i> Maintenance Analysis</h5>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="table-responsive">
                                                    <table class="table table-hover mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th class="border-0"><i class="fas fa-calendar me-1"></i> Period</th>
                                                                <th class="text-end border-0"><i class="fas fa-money-bill-wave me-1"></i> Cost</th>
                                                                <th class="text-end border-0"><i class="fas fa-tools me-1"></i> Events</th>
                                                                <th class="text-end border-0"><i class="fas fa-chart-bar me-1"></i> Avg Cost</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($maintenanceAnalysis as $maintenance)
                                                                <tr>
                                                                    <td>{{ $maintenance->period ?? '--' }}</td>
                                                                    <td class="text-end">{{ number_format($maintenance->total_cost ?? 0, 2) }}</td>
                                                                    <td class="text-end">{{ $maintenance->maintenance_events ?? 0 }}</td>
                                                                    <td class="text-end">{{ number_format($maintenance->avg_cost_per_transaction ?? 0, 2) }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="4" class="text-center text-muted p-3">No maintenance data available</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

            <input type="hidden"
                   name="vehicle_details"
                   value="{{route('vehicle.details')}}"/>
            @include('modules.vehicleManagement.partial.data_end_point')
        </div>
    </section>
    <x-employee-search-modal/>

    <div class="modal fade" id="vehicleDisk"
         tabindex="-1"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Vehicle Disk</h1>
                    <button type="button" class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body text-center" id="diskArea">
                    <img class="img-fluid"
                         alt="Disk"
                         src="{{asset('assets/dist/img/disc.jpg')}}"/>
                </div>
                <div class="modal-footer">
                    <button type="button" id="print" class="btn btn-primary btn-sm">
                        <i class="fas fa-print"></i>
                        Print
                    </button>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-default">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="fileViewModal"
         tabindex="-1"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">File Viewer</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe title="file viewer" id="documentView" src="" style="border: none;" width="100%"
                            height="600px;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-default">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        window.reference = `{!! $reference !!}`;
        window.vehicle = `{!! json_encode($vehicle ?? (object)[]) !!}`;
        window.costSummary = `{!! json_encode($costSummary ?? []) !!}`;
        window.fuelCostAnalysis = `{!! json_encode($fuelCostAnalysis ?? []) !!}`;
        window.maintenanceAnalysis = `{!! json_encode($maintenanceAnalysis ?? []) !!}`;
    </script>
    <script
            src="{{asset('modules/vehicleManagement/assets/js/vehicle_over_view.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{asset('modules/vehicleManagement/assets/js/cost_analysis_charts.js')}}"></script>
    <script>
        $(document).ready(function () {
            console.log('Are you working atleast')
            let elements = document.querySelectorAll('.view_mode');
            let elementsOnCreate = document.querySelectorAll('.create_mode');

            elements.forEach(function (element) {
                element.setAttribute('disabled', 'disabled');
            });

            elementsOnCreate.forEach(function (element) {
                element.style.display = 'none';
            });

            setInterval(function () {
                $("#vehicleLocation").attr('disabled', true);

                const registrationNumber = document.querySelector('#registrationNumber');
                if (registrationNumber && registrationNumber.value) {
                    registrationNumber.setAttribute('disabled', 'disabled');
                    $('#vehicleRegistration').val(registrationNumber.value).attr('readonly', true);
                }

            }, 600);
        });
    </script>
@endpush
