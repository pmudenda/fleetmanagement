
<div id="chart-fuel-trend" class="w-100 h-100"></div>


@push('scripts')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            Highcharts.setOptions({
                lang: {
                    thousandsSep: ','
                }
            });
            // Data retrieved https://en.wikipedia.org/wiki/List_of_cities_by_average_temperature
            Highcharts.chart('chart-fuel-trend', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Fuel Requisition Trend Analysis - {{now()->year}}'
                },
                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                },
                yAxis: {
                    title: {
                        text: 'Litres (L)'
                    }
                },
                plotOptions: {
                    series: {
                        label: {
                            connectorAllowed: false
                        },
                        // pointStart: 2010
                    }
                },
                series: @json($data)
            });


        });
    </script>
@endpush
