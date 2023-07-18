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
            <div class="row">
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

        </div>
    </section>

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
    <script type="text/javascript">
        // Initialize the echarts instance based on the prepared dom
        let myChart = echarts.init(document.getElementById('main'));

        let option = {
            title: {
                text: 'ECharts'
            },
            tooltip: {},
            legend: {
                data: ['sales']
            },
            xAxis: {
                data: ['Shirts', 'Cardigans', 'Chiffons', 'Pants', 'Heels', 'Socks']
            },
            yAxis: {},
            series: [
                {
                    name: 'sales',
                    type: 'bar',
                    data: [5, 20, 36, 10, 10, 20]
                }
            ]
        };

        myChart.setOption(option);
    </script>
    <script>
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

        myPieChart &&
        myPieChart.setOption(pieOption);
    </script>
    <script>
        let pieChartDom = document.getElementById('pie');
        let myPieChart2 = echarts.init(pieChartDom);

        const data = genData(5);
        option2 = {
            title: {
                text: 'Chart',
                subtext: 'Sub text',
                left: 'center'
            },
            tooltip: {
                trigger: 'item',
                formatter: '{a} <br/>{b} : {c} ({d}%)'
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
                    name: 'data',
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
        };

        function genData(count) {
            // prettier-ignore
            const nameList = [
                'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
                'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
            ];
            const legendData = [];
            const seriesData = [];
            for (let i = 0; i < count; i++) {
                let name = Math.random() > 0.65
                        ? makeWord(4, 1) + '·' + makeWord(3, 0)
                        : makeWord(2, 1);
                legendData.push(name);
                seriesData.push({
                    name: name,
                    value: Math.round(Math.random() * 100000)
                });
            }
            return {
                legendData: legendData,
                seriesData: seriesData
            };

            function makeWord(max, min) {
                const nameLen = Math.ceil(Math.random() * max + min);
                const name = [];
                for (let i = 0; i < nameLen; i++) {
                    name.push(nameList[Math.round(Math.random() * nameList.length - 1)]);
                }
                return name.join('');
            }
        }

        myPieChart2.setOption(option2);
    </script>
    <script>
        let bar_chartDom = document.querySelector("#bar_chart");
        let bar_chart = echarts.init(bar_chartDom);
        let barCharOption = {
            legend: {},
            tooltip: {},
            dataset: {
                dimensions: ['product', '2015', '2016', '2017'],
                source: [
                    { product: 'Matcha Latte', 2015: 43.3, 2016: 85.8, 2017: 93.7 },
                    { product: 'Milk Tea', 2015: 83.1, 2016: 73.4, 2017: 55.1 },
                    { product: 'Cheese Cocoa', 2015: 86.4, 2016: 65.2, 2017: 82.5 },
                    { product: 'Walnut Brownie', 2015: 72.4, 2016: 53.9, 2017: 39.1 }
                ]
            },
            xAxis: { type: 'category' },
            yAxis: {},
            series: [{ type: 'bar' }, { type: 'bar' }, { type: 'bar' }]
        };

        bar_chart.setOption(barCharOption)
    </script>
@endpush
