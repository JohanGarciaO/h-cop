<div class="fw-bold my-3 text-center">Comitivas dos Hóspedes</div>
<div id="chart-guests-committee" class="w-100"></div>

@push('scripts')
<script>   
    document.addEventListener("DOMContentLoaded", function () {
        var options = {
            chart: { 
                type: 'bar', 
                height: 350,
                background: 'transparent',
                foreColor: '#ffffff',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                    },
                }
            },
            theme: {
                mode: 'dark'
            },
            series: [{
                name: 'Quantidade',
                data: @json($committeeValues)
            }],
            xaxis: {
                categories: @json($committeeLabels),
                labels: { style: { colors: '#ffffff' } }
            },
            yaxis: {
                labels: { style: { colors: '#ffffff' } }
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '45%',
                    borderRadius: 5
                }
            },
            colors: ['#198754'],
            grid: {
                borderColor: '#444'
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart-guests-committee"), options);
        chart.render();
    });
</script>
@endpush
