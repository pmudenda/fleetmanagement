@php use App\Helpers\StatusHelper;use App\Models\Driver;use App\Models\MaterialHeader;use App\Models\Security\User;use Carbon\Carbon; @endphp
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
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3 class="text-white">{{$vehicleData->count()}}</h3>
                            <p>Total Fleet</p>
                        </div>
                        <div class="icon">
                            <i class="ion fonticon-truck"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 class="text-white">53<sup style="font-size: 20px">%</sup></h3>
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
                            <h3 class="text-white">{{User::where('con_st_code','=', StatusHelper::active())->count()}}</h3>
                            <p>Active Users</p>
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
                            <h3 class="text-white">{{Driver::get()->count()}}</h3>
                            <p>Registered Drivers</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-drivers-license"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

            </div>

            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Evaluation</th>
                </tr>
                </thead>

                <tr>
                    <td>{{config('rights.create_job_card')}}  (Create Job Card)</td>
                    <td>{{auth()->user()->can(config('rights.create_job_card'))}}</td>
                </tr>

                <tr>
                    <td>{{config('rights.view_job_card')}}  (View Job Card)</td>
                    <td>{{auth()->user()->can(config('rights.view_job_card'))}}</td>
                </tr>

                <tr>
                    <td>{{config('rights.view_job_card')}}  (View Job Card)</td>
                    <td>{{auth()->user()->can(config('rights.view_job_card'))}}</td>
                </tr>

                <tr>
                    <td>{{ config('rights.requisition_fuel')}}  (Request Fuel)</td>
                    <td>{{auth()->user()->can(config('rights.requisition_fuel'))}}</td>
                </tr>

            </table>

            {{dd(config('rights'))}}
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
                                                <a href="{{URL::signedRoute($rec->url, ['ref'=>  $rec->reference])}}">
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
                                                {{--'show.fuel.requisition'--}}
                                                <a href="{{URL::signedRoute($rec->url,['ref'=> $rec->reference])}}"
                                                   class="btn btn-sm btn-success">
                                                    <i class="fas fa-eye"></i>
                                                    Open
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

            <div class="row">
                <!-- Left col -->
                <div class="col-md-12 pl-0">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h4>Vehicle Data Report</h4>
                            </div>
                            <div class="card-toolbar justify-content-end">

                                <button type="button"
                                        class="btn btn-sm btn-primary me-3"
                                        data-menu-trigger="click"
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
                            <div class="row">
                                <div class="col-6">
                                    <div id="main" style="height:400px;"></div>
                                </div>
                                <div class="col-6">
                                    <div id="pie" style="height:400px;"></div>
                                </div>
                            </div>
                            {{--<div class="row">
                                <div class="col-6">
                                    <div id="pie2" style="height:400px;"></div>
                                </div>
                                <div class="col-6">
                                    <div id="bar_chart" style="height:400px;"></div>
                                </div>
                            </div>--}}
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
        window.vehicleData = {!! json_encode($vehicleData) !!};
        (function (appInstance) {
            appInstance.initDatatable("#listTable", false, true);

            function genData() {
                let legendData = [];
                let valueObject = {};
                for (const vehicle of window['vehicleData']) {
                    if (legendData.indexOf(vehicle['status_name']) === -1) {
                        legendData.push(vehicle['status_name']);
                    }
                    if (valueObject.hasOwnProperty(vehicle['status_name'])) {
                        valueObject[vehicle['status_name']] += 1;
                    } else {
                        valueObject[vehicle['status_name']] = 1;
                    }
                }

                let seriesData = [];
                for (const key in valueObject) {
                    seriesData.push({value: valueObject[key], name: key});
                }
                return {
                    legendData,
                    seriesData
                }
            }

            const data = genData();

            function createVehicleChartByStatus() {
                let myChart = echarts.init(document.getElementById('main'));

                let option = {
                    title: {
                        text: 'Vehicle By Status',
                        left: 'center'
                    },
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'shadow',
                            label: {
                                show: true,
                            },
                            formatter(params) {
                                return params[0].data.name;
                            }
                        },
                        formatter(params) {
                            const value = params[0].data.value;
                            return 'Status: ' + params[0].data.name
                                + '<br/>No. Of Vehicles: ' + value;
                        }
                    },
                    toolbox: {
                        show: true,
                        feature: {
                            mark: {show: true},
                            dataView: {show: true, readOnly: false},
                            magicType: {show: true, type: ['line', 'bar', 'stack']},
                            restore: {show: true},
                            saveAsImage: {show: true},
                        },
                    },
                    legend: {
                        data: ['sales']
                    },
                    xAxis: {
                        data: data.legendData,
                        axisLabel: {
                            rotate: 45,
                            width: 50,
                            ellipsis: '...',
                            overflow: 'truncate'
                        }
                    },
                    yAxis: {},
                    series: [
                        {
                            name: 'Vehicle By Status',
                            type: 'bar',
                            colorBy: 'data',
                            data: data.seriesData
                        }
                    ]
                };

                myChart.setOption(option);
            }

            function createVehicleByStatusPie() {
                let pieChartDom = document.getElementById('pie');
                let myPieChart2 = echarts.init(pieChartDom);

                myPieChart2.setOption({
                    title: {
                        text: 'Vehicle By Status',
                        left: 'center'
                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b} : {c} ({d}%)'
                    },
                    toolbox: {
                        show: true,
                        feature: {
                            mark: {show: true},
                            dataView: {show: true, readOnly: false},
                            magicType: {show: true, type: ['line', 'bar', 'stack']},
                            restore: {show: true},
                            saveAsImage: {show: true},
                        },
                    },
                    legend: {
                        type: 'scroll',
                        orient: 'vertical',
                        right: 10,
                        top: 20,
                        bottom: 20,
                        data: data.legendData
                    },
                    series: [
                        {
                            name: 'Vehicle By Status',
                            type: 'pie',
                            radius: '55%',
                            center: ['40%', '50%'],
                            data: data.seriesData,
                            emphasis: {
                                itemStyle: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                }
                            }
                        }
                    ]
                });
            }

            createVehicleChartByStatus();

            createVehicleByStatusPie();
        })(window.tmsApp || {});

        let chartDom = document.getElementById('pie2');
        let myPieChart = echarts.init(chartDom);

        let pieOption = {
            legend: {},
            tooltip: {
                trigger: 'axis',
                showContent: false
            },
            dataset: {
                source: [
                    ['product', '2012', '2013', '2014', '2015', '2016', '2017'],
                    ['Milk Tea', 56.5, 82.1, 88.7, 70.1, 53.4, 85.1],
                    ['Matcha Latte', 51.1, 51.4, 55.1, 53.3, 73.8, 68.7],
                    ['Cheese Cocoa', 40.1, 62.2, 69.5, 36.4, 45.2, 32.5],
                    ['Walnut Brownie', 25.2, 37.1, 41.2, 18, 33.9, 49.1]
                ]
            },
            xAxis: {type: 'category'},
            yAxis: {gridIndex: 0},
            grid: {top: '55%'},
            series: [
                {
                    type: 'line',
                    smooth: true,
                    seriesLayoutBy: 'row',
                    emphasis: {focus: 'series'}
                },
                {
                    type: 'line',
                    smooth: true,
                    seriesLayoutBy: 'row',
                    emphasis: {focus: 'series'}
                },
                {
                    type: 'line',
                    smooth: true,
                    seriesLayoutBy: 'row',
                    emphasis: {focus: 'series'}
                },
                {
                    type: 'line',
                    smooth: true,
                    seriesLayoutBy: 'row',
                    emphasis: {focus: 'series'}
                },
                {
                    type: 'pie',
                    id: 'pie',
                    radius: '30%',
                    center: ['50%', '25%'],
                    emphasis: {
                        focus: 'self'
                    },
                    label: {
                        formatter: '{b}: {@2012} ({d}%)'
                    },
                    encode: {
                        itemName: 'product',
                        value: '2012',
                        tooltip: '2012'
                    }
                }
            ]
        };
        myPieChart.on('updateAxisPointer', function (event) {
            const xAxisInfo = event.axesInfo[0];
            if (xAxisInfo) {
                const dimension = xAxisInfo.value + 1;
                myChart.setOption({
                    series: {
                        id: 'pie',
                        label: {
                            formatter: '{b}: {@[' + dimension + ']} ({d}%)'
                        },
                        encode: {
                            value: dimension,
                            tooltip: dimension
                        }
                    }
                });
            }
        });

        // myPieChart &&
        // myPieChart.setOption(pieOption);
        let bar_chartDom = document.querySelector("#bar_chart");
        let bar_chart = echarts.init(bar_chartDom);
        let barCharOption = {
            legend: {},
            tooltip: {},
            dataset: {
                dimensions: ['product', '2015', '2016', '2017'],
                source: [
                    {product: 'Matcha Latte', 2015: 43.3, 2016: 85.8, 2017: 93.7},
                    {product: 'Milk Tea', 2015: 83.1, 2016: 73.4, 2017: 55.1},
                    {product: 'Cheese Cocoa', 2015: 86.4, 2016: 65.2, 2017: 82.5},
                    {product: 'Walnut Brownie', 2015: 72.4, 2016: 53.9, 2017: 39.1}
                ]
            },
            xAxis: {type: 'category'},
            yAxis: {},
            series: [{type: 'bar'}, {type: 'bar'}, {type: 'bar'}]
        };

        //bar_chart.setOption(barCharOption)
    </script>
@endpush
