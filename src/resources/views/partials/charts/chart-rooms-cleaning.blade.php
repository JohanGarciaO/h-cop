<div class="fw-bold my-3 text-center">Estado de conservação dos quartos</div>
<div id="chart-rooms-cleaning" class="w-100"></div>

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
            series: @json($roomStateValues),
            labels: @json($roomStateLabels),
            stroke: {
                curve: 'smooth',
                width: 3
            },
            colors: ['#198754', '#ffc107', '#dc3545'], // Verde, Amarelo, Vermelho
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

        var chart = new ApexCharts(document.querySelector("#chart-rooms-cleaning"), options);
        chart.render();
    });
</script>
@endpush
