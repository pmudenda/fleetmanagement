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
                                    <div id="bar_chart" style="height:400px;"></div>
                                </div>
                            </div>
                            <div class="table-responsive mt-10 ">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
   <input type="hidden" name="fuelExpenseReport" value="{{route('reports.fuel.data')}}">
@endsection

@push('scripts')
    <!-- DataTables  & Plugins -->
    @include('layouts.partials.dataTableScripts')
    <!-- page script -->
    <script type="text/javascript">
        function fuelExpensesByVehicle() {
            let myChart = echarts.init(document.getElementById('main'));
            let dat = genDataByVehicle();
            let option = {
                title: {
                    text: 'Fuel Consumption'
                },
                tooltip: {},
                legend: {},
                xAxis: {
                    data: dat.legendData,
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
                        name: '',
                        type: 'bar',
                        colorBy: 'data',
                        data: dat.seriesData // [5, 20, 36, 10, 10, 20]
                    }
                ]
            };

            function genDataByVehicle() {

                const legendData = [];
                const seriesData = [];
                let valueObject = {};

                for (const datum of window['data']) {

                    if (legendData.indexOf(datum['reg_no']) === -1) {
                        legendData.push(datum['reg_no']);
                    }
                    if (valueObject.hasOwnProperty(datum['reg_no'])) {
                        valueObject[datum['reg_no']] += parseFloat(datum['ttl']);
                    } else {
                        valueObject[datum['reg_no']] = parseFloat(datum['ttl']);
                    }
                }

                for (const key in valueObject) {
                    seriesData.push({value: valueObject[key], name: key});
                }

                return {
                    legendData: legendData,
                    seriesData: seriesData
                };
            }

            myChart.setOption(option);
        }

        function fuelExpenseTotalsByType() {
            let pieChartDom = document.getElementById('pie');
            let myPieChart2 = echarts.init(pieChartDom);

            const data = genData();
            option2 = {
                title: {
                    text: 'Fuel Type By Consumption',
                    subtext: 'Most Consumed',
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
                series: [
                    {
                        name: '',
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

            function genData() {

                const legendData = [];
                const seriesData = [];
                let valueObject = {};

                for (const datum of window['data']) {

                    if (legendData.indexOf(datum['fuel_type']) === -1) {
                        legendData.push(datum['fuel_type']);
                    }
                    if (valueObject.hasOwnProperty(datum['fuel_type'])) {
                        valueObject[datum['fuel_type']] += parseFloat(datum['ttl']);
                    } else {
                        valueObject[datum['fuel_type']] = parseFloat(datum['ttl']);
                    }
                }

                for (const key in valueObject) {
                    seriesData.push({value: valueObject[key], name: key});
                }

                return {
                    legendData: legendData,
                    seriesData: seriesData
                };
            }

            myPieChart2.setOption(option2);
        }

        function fuelExpensesByYear() {
            function genDataByYear() {

                const legendData = [];
                const sourceData = [];
                let valueObject = {};
                let years = [];
                let productValue = [];
                for (const datum of window['data']) {

                    if (legendData.indexOf(datum['fuel_type']) === -1) {
                        legendData.push(datum['fuel_type']);
                    }

                    if (valueObject.hasOwnProperty(datum['fuel_type'])) {
                        valueObject[datum['fuel_type']] += parseFloat(datum['ttl']);
                    } else {
                        valueObject[datum['fuel_type']] = parseFloat(datum['ttl']);
                    }

                    //if (!productValue.hasOwnProperty(datum['fuel_type'])){
                    let obj = productValue[datum.fuel_type] ?? {};
                    obj['product'] = datum['fuel_type'];
                    if (obj[datum['year']]) {
                        obj[datum['year']] += parseFloat(datum['ttl']);
                    } else {
                        obj[datum['year']] = parseFloat(datum['ttl']);
                    }
                    productValue[datum['fuel_type']] = obj;

                    if (years.indexOf(datum['year']) === -1) {
                        years.push(datum['year']);
                    }
                }

                for (const key in productValue) {
                    sourceData.push(productValue[key]);
                }
                console.log(productValue);

                return {
                    dimension: ['product', ...years],
                    source: sourceData
                };
            }

            let dataByYear = genDataByYear();
            let bar_chartDom = document.querySelector("#bar_chart");
            let bar_chart = echarts.init(bar_chartDom);
            let barCharOption = {
                legend: {},
                tooltip: {},
                dataset: {
                    dimensions: dataByYear.dimension,
                    source: dataByYear.source
                },
                xAxis: {type: 'category'},
                yAxis: {},
                series: [{type: 'bar'}, {type: 'bar'}, {type: 'bar'}]
            };

            bar_chart.setOption(barCharOption)
        }
    </script>
    <script>
        window.data = {!! json_encode($data)!!};

        (function (appInstance) {
            appInstance.initDatatable("#listTable", false);

            $.getJSON($('[name="fuelExpenseReport"]').val()).done((response) => {
                if (response.state != 'success') {
                    return;
                }
                window.costByYear = response.payload['cost_by_year'];
                //fuelExpensesByVehicle();
                //fuelExpenseTotalsByType();
                fuelExpensesByYear();
            }).fail(function () {
            })

        })(window.tmsApp || {});
    </script>

    <script>
        /* let chartDom = document.getElementById('pie2');
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
         myPieChart.setOption(pieOption);*/
    </script>

@endpush
