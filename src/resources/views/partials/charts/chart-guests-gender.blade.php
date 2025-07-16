<div class="fw-bold my-3 text-center">Gênero dos Hóspedes</div>
<div id="chart-guests-gender" class="w-100"></div>

@push('scripts')
<script>   
    document.addEventListener("DOMContentLoaded", function () {
        var options = {
            chart: { 
                type: 'pie', 
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
            series: @json($genderValues),
            labels: @json($genderLabels),
            stroke: {
                curve: 'smooth',
                width: 3
            },
            colors: ['#0d6efd', '#d63384'], // Azul e Amarelo
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

        var chart = new ApexCharts(document.querySelector("#chart-guests-gender"), options);
        chart.render();
    });
</script>
@endpush
