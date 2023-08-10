@extends('layouts.app')
@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush


@section('content')

    <x-content-header :pageTitle="'Reports'" :activeCrumb="'Reports'" :link="'home'"
                      :linkText="'Fuel Requisitions'"/>

    <!-- Main content -->
    <section class="content">
        <x-error-view/>
        <div class="container-fluid">
            <!-- Main row -->
            <div class="row d-none">
                <!-- Left col -->
                <div class="col-md-12 pl-0">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h4>Fuel Requisitions Report</h4>
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
                            <div class="row">
                                <div class="col-6">
                                    <div id="pie2" style="height:400px;"></div>
                                </div>
                                <div class="col-6">
                                    <div id="bar_chart" style="height:400px;"></div>
                                </div>
                            </div>
                            <div class="table-responsive mt-10 ">
                                <table id="listTable" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Staff Number</th>
                                        <th>Email Address</th>
                                        <th>Grade</th>
                                        <th>JobTitle</th>
                                        <th>Last Login</th>
                                        <th>Status</th>
                                        {{--@can(config('rights.user_show'))--}}
                                        <th>Action</th>
                                        {{--@endcan--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{--@foreach($users as $key => $user)
                                        <tr>
                                            <td>
                                                {{++$key}}
                                            </td>
                                            <td>
                                                @if(!empty($user->avatar))
                                                    <img class="profile-user-img img-fluid img-circle border-0" width="100%"
                                                         src="{{ asset('storage/user_avatar/' . $user->avatar) }}"
                                                         alt="Image not found"
                                                         style="width: 60px; height: 54px;"
                                                    />
                                                @else
                                                    <img class="profile-user-img img-fluid img-circle border-0" width="100%"
                                                         src="{{ asset('assets/media/avatars/avatar.png') }}"
                                                         alt="Image not found"
                                                         style="width: 60px; height: 54px;"
                                                    />
                                                @endif
                                            </td>
                                            <td>
                                                {{$user->name}}
                                            </td>
                                            <td>
                                                {{$user->staff_no ?? '--'}}
                                            </td>
                                            <td>
                                                {{$user->email}}
                                            </td>

                                            <td>
                                                {{$user->grade ?? '--'}}
                                            </td>
                                            <td>
                                                {{$user->job_title ?? '--'}}
                                            </td>
                                            <td>
                                                {{$user->last_login ?? '--'}}
                                            </td>
                                            <td>
                                                @if($user->con_st_code == '01')
                                                    Active
                                                @else
                                                    {{$user->con_st_code ?? '--'}}
                                                @endif
                                            </td>
                                            --}}{{--@can(config('rights.user_show'))--}}{{--
                                            <td>
                                                <a href="{{route('user.show', $user->id)}}"
                                                   class="btn btn-sm btn-success m-1">
                                                    <i class="fas fa-eye">Details</i>
                                                </a>
                                            </td>
                                            --}}{{-- @endcan--}}{{--
                                        </tr>
                                    @endforeach--}}
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


            <div class="row">
                <!-- Left col -->
                <div class="col-md-12 pl-0">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h4>TMS Vehicle Data Report</h4>
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
                                    <div id="tmsMain" style="height:400px;"></div>
                                </div>
                                <div class="col-6">
                                    <div id="tmsPie" style="height:400px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h4>Vehicle Assignation Data Report</h4>
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
                                    <div id="cleanMain" style="height:400px;"></div>
                                </div>
                                <div class="col-6">
                                    <div id="pieClean" style="height:400px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')

    <!-- DataTables  & Plugins -->
    @include('layouts.partials.dataTableScripts')
    <!-- page script -->
    <script>
        window.vehicleData = {!! json_encode($vehicleData) !!};
        window.tmsVehicleData = {!! json_encode($tmsVehicleData) !!};
        window.cleanVehicleData = {!! json_encode($cleanVehicleData) !!};
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

                console.log(
                    {
                        legendData,
                        seriesData
                    }
                )
                return {
                    legendData,
                    seriesData
                }
            }

            const data = genData();

            const tmsData = tmsGenData();
            const cleanData = genCleanData();

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

            function tmsGenData() {
                let legendData = [];
                let valueObject = {};
                for (const vehicle of window['tmsVehicleData']) {
                    if (legendData.indexOf(vehicle['vehicle_status']) === -1) {
                        legendData.push(vehicle['vehicle_status']);
                    }
                    if (valueObject.hasOwnProperty(vehicle['vehicle_status'])) {
                        valueObject[vehicle['vehicle_status']] += 1;
                    } else {
                        valueObject[vehicle['vehicle_status']] = 1;
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

            function genCleanData() {
                let legendData = [];
                let valueObject = {};
                for (const vehicle of window['cleanVehicleData']) {
                    if (legendData.indexOf(vehicle['vehicle_status']) === -1) {
                        legendData.push(vehicle['vehicle_status']);
                    }
                    if (valueObject.hasOwnProperty(vehicle['vehicle_status'])) {
                        valueObject[vehicle['vehicle_status']] += 1;
                    } else {
                        valueObject[vehicle['vehicle_status']] = 1;
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

            function createCleanVehicleChartByStatus() {
                let myChart = echarts.init(document.getElementById('cleanMain'));

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
                        data: cleanData.legendData,
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
                            data: cleanData.seriesData
                        }
                    ]
                };

                myChart.setOption(option);
            }

            function createCleanVehicleByStatusPie() {
                let pieChartDom = document.getElementById('pieClean');
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
                        data: cleanData.legendData
                    },
                    series: [
                        {
                            name: 'Vehicle By Status',
                            type: 'pie',
                            radius: '55%',
                            center: ['40%', '50%'],
                            data: cleanData.seriesData,
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

            function createTMSVehicleChartByStatus() {
                let myChart = echarts.init(document.getElementById('tmsMain'));

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
                        data: tmsData.legendData,
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
                            data: tmsData.seriesData
                        }
                    ]
                };

                myChart.setOption(option);
            }

            function createTMSVehicleByStatusPie() {
                let pieChartDom = document.getElementById('tmsPie');
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
                        data: tmsData.legendData
                    },
                    series: [
                        {
                            name: 'Vehicle By Status',
                            type: 'pie',
                            radius: '55%',
                            center: ['40%', '50%'],
                            data: tmsData.seriesData,
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

            createTMSVehicleChartByStatus();

            createTMSVehicleByStatusPie();

            createCleanVehicleChartByStatus();

            createCleanVehicleByStatusPie();

        })(window.tmsApp || {});
    </script>
@endpush
