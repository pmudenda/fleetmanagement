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
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                {{-- <h4>Fuel Requisitions Report</h4>--}}
                            </div>
                            <div class="card-toolbar justify-content-end"></div>
                        </div>
                        <div class="card-body p-2">
                            <div class="row">
                                <div id="bar_chart" style="height:400px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                {{-- <h4>Fuel Requisitions Report</h4>--}}
                            </div>
                            <div class="card-toolbar justify-content-end"></div>
                        </div>
                        <div class="card-body p-2">
                            <div class="row">
                                <div id="pie" style="height:400px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                {{-- <h4>Fuel Requisitions Report</h4>--}}
                            </div>
                            <div class="card-toolbar justify-content-end"></div>
                        </div>
                        <div class="card-body p-2">
                            <div class="row">
                                <div class="col-6">
                                    <div id="user_unit" style="height:400px;"></div>
                                </div>
                            </div>
                            <div class="table-responsive mt-10"></div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                {{-- <h4>Fuel Requisitions Report</h4>--}}
                            </div>
                            <div class="card-toolbar justify-content-end"></div>
                        </div>
                        <div class="card-body p-2">
                            <div class="row">
                                <div class="col-6">
                                    <div id="main" style="height:400px;"></div>
                                </div>
                            </div>
                            <div class="table-responsive mt-10"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <input type="hidden" name="fuelExpenseReport" value="{{route('reports.fuel.data')}}">
@endsection

@push('scripts')
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
            const option2 = {
                title: {
                    text: 'Fuel Type By Consumption',
                    subtext: 'Most Consumed',
                    left: 'center'
                },
                tooltip: {
                    trigger: 'item',
                    formatter: function (params) {
                        //'{a} <br/>{b} : {c} ({d}%)'
                        console.log(params);
                    }
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

                for (const datum of window['costByType']) {

                    if (legendData.indexOf(datum['fuel_type']) === -1) {
                        legendData.push(datum['fuel_type']);
                    }
                    if (valueObject.hasOwnProperty(datum['fuel_type'])) {
                        valueObject[datum['fuel_type']] += parseFloat(datum['cost']);
                    } else {
                        valueObject[datum['fuel_type']] = parseFloat(datum['cost']);
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
                let series = [];
                for (const datum of window['costByYear']) {

                    if (legendData.indexOf(datum['fuel_type']) === -1) {
                        legendData.push(datum['fuel_type']);
                    }

                    if (valueObject.hasOwnProperty(datum['fuel_type'])) {
                        valueObject[datum['fuel_type']] += parseFloat(datum['cost']);
                    } else {
                        valueObject[datum['fuel_type']] = parseFloat(datum['cost']);
                    }

                    let obj = productValue[datum.fuel_type] ?? {};
                    obj['product'] = datum['fuel_type'];
                    if (obj[datum['year']]) {
                        obj[datum['year']] += parseFloat(datum['cost']);
                    } else {
                        obj[datum['year']] = parseFloat(datum['cost']);
                    }

                    productValue[datum['fuel_type']] = obj;

                    if (years.indexOf(datum['year']) === -1) {
                        years.push(datum['year']);
                    }
                }

                let seriesData = {};
                const labelOption = {
                    show: true,
                    position: app.config.position,
                    distance: app.config.distance,
                    align: app.config.align,
                    verticalAlign: app.config.verticalAlign,
                    rotate: app.config.rotate,
                    formatter: '{c}  {name|{a}}',
                    fontSize: 16,
                    rich: {
                        name: {}
                    }
                };
                for (const fuelType of legendData) {
                    seriesData[fuelType] = {
                        name: fuelType,
                        type: 'bar',
                        label: labelOption,
                        barGap: 0,
                        emphasis: {
                            focus: 'series'
                        },
                        data: [320, 332, 301, 334, 390]
                    }
                }


                /* [{
                       data: dataByYear.source,
                       type: 'bar',
                       colorBy: 'data',
                       showBackground: true,
                       backgroundStyle: {
                           color: 'rgba(180, 180, 180, 0.2)'
                       }
                   }])*/

                for (const fuelType of legendData) {
                    series = seriesData[fuelType]
                }


                for (const key in productValue) {
                    const dat = productValue[key];
                    sourceData.push(dat);
                }

                return {
                    series: series,
                    years: years,
                    products: legendData
                };
            }

            let dataByYear = genDataByYear();
            let bar_chartDom = document.querySelector("#bar_chart");
            let bar_chart = echarts.init(bar_chartDom);
            let barCharOption = {
                title: {
                    text: 'Fuel Type By Year',
                    subtext: 'Most Consumed',
                    left: 'center'
                },
                tooltip: {
                    trigger: 'axis',
                    formatter(params) {
                        console.log(params[0].data);

                        return `${params[0].data.name} <br/> ' +
                            ${accounting.formatMoney(params[0].data.value)}`;
                    }
                },

                legend: {
                    data: dataByYear.products
                },
                xAxis: {
                    type: 'category',
                    axisTick: {show: false},
                    data: dataByYear.years
                },
                yAxis: {
                    type: 'value'
                },
                series: dataByYear.series
            };

            bar_chart.setOption(barCharOption)
        }
    </script>
    <script>
        window.data = {!! json_encode($data)!!};

        (function (appInstance) {
            appInstance.initDatatable("#listTable", false);

            $.getJSON($('[name="fuelExpenseReport"]').val())
                .done((response) => {
                    if (response.state !== 'success') {
                        return;
                    }
                    window.costByYear = response.payload['costByYear'];
                    window.costByUnit = response.payload['costByUnit'];
                    window.costByType = response.payload['costByType'];
// user_unit
                    //fuelExpensesByVehicle();
                    fuelExpenseTotalsByType();
                    fuelExpensesByYear();
                }).fail(function (xhr) {
            })

        })(window.tmsApp || {});
    </script>
@endpush
