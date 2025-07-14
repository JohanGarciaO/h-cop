<div class="fw-bold my-3 text-center">Ocupação dos quartos</div>
<div id="chart-rooms-status" class="w-100"></div>

@push('scripts')
<script>   
    document.addEventListener("DOMContentLoaded", function () {
        var options = {
            chart: { 
                type: 'donut', 
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
            series: @json($quartosStatusValues),
            labels: @json($quartosStatusLabels),
            stroke: {
                curve: 'smooth',
                width: 3
            },
            colors: ['#dc3545','#198754'],
            legend: {
                position: 'bottom',
                labels: {
                    colors: ['#ffffff']
                }
            },
            dataLabels: {
                style: {
                    colors: ['#ffffff']
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart-rooms-status"), options);
        chart.render();
    });
</script>
@endpush
