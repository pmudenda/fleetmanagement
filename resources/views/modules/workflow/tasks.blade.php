@php use Carbon\Carbon; @endphp
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
                <!-- Left col -->
                <div class="col-md-12 pl-0">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h3>All Tasks</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table aria-label="tasks table"
                                       id="allTasksTable"
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
                                                <a href="{{URL::signedRoute($rec->url,
                                                    ['ref'=>  $rec->reference, 'view_only'=>true])}}">
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
                                                @canany(config('rights.manage_tasks'), config('rights.view_tasks'))
                                                    <a href="{{URL::signedRoute($rec->url,[
                                                        'ref'=> $rec->reference,
                                                        'view_only'=>true
                                                        ])}}"
                                                       class="btn btn-sm btn-success">
                                                        Details
                                                    </a>
                                                @endcanany
                                                @canany(config('rights.manage_tasks'))
                                                    <a href="#"
                                                       data-bs-target="#taskReassignment"
                                                       data-bs-toggle="modal"
                                                       class="btn btn-sm btn-success">
                                                        Reassign
                                                    </a>
                                                @endcanany
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
        window.vehicleData = {!! json_encode($approvalTasks) !!};
        (function (appInstance) {
            appInstance.initDatatable("#allTasksTable", false, true);

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
        })(window.tmsApp || {});
    </script>
@endpush
