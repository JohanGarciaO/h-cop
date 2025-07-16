<div class="fw-bold my-3 text-center">Entrada de hóspedes dos últimos 7 dias</div>
<div id="chart-reservations" class="w-100"></div>

@push('scripts')
<script>   
    document.addEventListener("DOMContentLoaded", function () {
        var options = {
            chart: { 
                type: 'line', 
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
                name: 'Reservas', 
                data: @json($reservasPorDia) 
            }],
            xaxis: { 
                categories: @json($diasDaSemana),
                labels: {
                    style: {
                        colors: '#ffffff'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#ffffff'
                    }
                }
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            colors: ['#198754'],
            grid: {
                borderColor: '#444'
            },
            markers: {
                size: 5,
                colors: ['#198754'],
                strokeColors: '#198754',
                strokeWidth: 2
            },
        };

        var chart = new ApexCharts(document.querySelector("#chart-reservations"), options);
        chart.render();
    });
</script>
@endpush
