<div class="fw-bold my-3 text-center">Localidade dos Hóspedes</div>
<div id="chart-guests-location" class="w-100"></div>

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
            series: @json($locationValues),
            labels: @json($locationLabels),
            stroke: {
                curve: 'smooth',
                width: 3
            },
            colors: ['#0dcaf0', '#198754', '#ffc107', '#fd7e14', '#6f42c1'],
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

        var chart = new ApexCharts(document.querySelector("#chart-guests-location"), options);
        chart.render();
    });
</script>
@endpush
