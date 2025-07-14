<div class="fw-bold my-3 text-center">Status das Reservas</div>
<div id="chart-reservations-status" class="w-100"></div>

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
                name: 'Reservas',
                data: @json($reservationStatusValues)
            }],
            xaxis: {
                categories: @json($reservationStatusLabels), // Ex: ['Check-in pendente', 'Check-out pendente']
                labels: {
                    style: { colors: '#ffffff' }
                }
            },
            yaxis: {
                labels: {
                    style: { colors: '#ffffff' }
                }
            },
            plotOptions: {
                bar: {
                    columnWidth: '45%',
                    borderRadius: 5,
                    horizontal: true
                }
            },
            colors: ['#198754'],
            grid: {
                borderColor: '#444'
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart-reservations-status"), options);
        chart.render();
    });
</script>
@endpush
