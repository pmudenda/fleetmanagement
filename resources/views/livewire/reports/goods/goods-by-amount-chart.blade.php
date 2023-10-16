
<div id="fuel-status-by-amount" class="w-100 h-100"></div>


@push('scripts')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            Highcharts.setOptions({
                lang: {
                    thousandsSep: ','
                }
            });
            Highcharts.chart('fuel-status-by-amount', {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Fuel Requisition Status by Amount',
                    align: 'left'
                },
                // subtitle: {
                //     text: 'Source: <a ' +
                //         'href="https://en.wikipedia.org/wiki/List_of_continents_and_continental_subregions_by_population"' +
                //         'target="_blank">Wikipedia.org</a>',
                //     align: 'left'
                // },
                xAxis: {
                    categories: @json($categories),
                    title: {
                        text: null
                    },
                    gridLineWidth: 1,
                    lineWidth: 0
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Costs(ZMW)',
                        align: 'high'
                    },
                    labels: {
                        overflow: 'justify'
                    },
                    gridLineWidth: 0
                },
                tooltip: {
                    valueSuffix: ''
                },
                plotOptions: {
                    bar: {
                        borderRadius: '50%',
                        dataLabels: {
                            enabled: true,
                            format: 'K{y:,.0f}'                        },
                        groupPadding: 0.1
                    }
                },
                legend: false,
                credits: {
                    enabled: false
                },
                series: [{
                    name: 'Fuel Costs',
                    data: @json($data)
                }]
            });

        });
    </script>
@endpush
