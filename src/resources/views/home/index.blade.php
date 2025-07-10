@extends('layouts.home')

@section('title', 'Início')

@push('styles')
<style>
    #chart {
        max-width: 650px;
        margin: 35px auto;
    }

    .dash-icon {
        width: 45px;
        height: 45px;
    }
</style>
@endpush

@section('content')

    @component('partials.components.body-header', ['title' => 'Dashboard'])
    @endcomponent

    {{-- Cards de Contadores --}}
    <div class="row g-5 mb-4">
        <div class="col-md-6 col-xl-3">
            <x-dashboard-card title="Quartos" :value="$totalRooms">
                <svg class="dash-icon" xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" viewBox="1 3 21 21"><path d="M8 5C7.5 5 7 5.21 6.61 5.6S6 6.45 6 7V10C5.47 10 5 10.19 4.59 10.59S4 11.47 4 12V17H5.34L6 19H7L7.69 17H16.36L17 19H18L18.66 17H20V12C20 11.47 19.81 11 19.41 10.59S18.53 10 18 10V7C18 6.45 17.8 6 17.39 5.6S16.5 5 16 5M8 7H11V10H8M13 7H16V10H13M6 12H18V15H6Z" /></svg>
            </x-dashboard-card>
        </div>
        <div class="col-md-6 col-xl-3">
            <x-dashboard-card title="Hóspedes" :value="$totalGuests">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="dash-icon" viewBox="-2 0 20 20">
                    <path d="M9.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0M6.44 3.752A.75.75 0 0 1 7 3.5h1.445c.742 0 1.32.643 1.243 1.38l-.43 4.083a1.8 1.8 0 0 1-.088.395l-.318.906.213.242a.8.8 0 0 1 .114.175l2 4.25a.75.75 0 1 1-1.357.638l-1.956-4.154-1.68-1.921A.75.75 0 0 1 6 8.96l.138-2.613-.435.489-.464 2.786a.75.75 0 1 1-1.48-.246l.5-3a.75.75 0 0 1 .18-.375l2-2.25Z"/>
                    <path d="M6.25 11.745v-1.418l1.204 1.375.261.524a.8.8 0 0 1-.12.231l-2.5 3.25a.75.75 0 1 1-1.19-.914zm4.22-4.215-.494-.494.205-1.843.006-.067 1.124 1.124h1.44a.75.75 0 0 1 0 1.5H11a.75.75 0 0 1-.531-.22Z"/>
                </svg>
            </x-dashboard-card>
        </div>
        <div class="col-md-6 col-xl-3">
            <x-dashboard-card title="Camareiros" :value="$totalHousekeepers">
                <img class="dash-icon" src="{{asset('assets/images/icons/housekeeper.png')}}" style="filter: brightness(0) invert(1)" width="20" height="20" alt="Camareiros">
            </x-dashboard-card>
        </div>
        <div class="col-md-6 col-xl-3">
            <x-dashboard-card title="Operadores" :value="$totalOperators">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="dash-icon" viewBox="-2 0 20 20">
                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                </svg>
            </x-dashboard-card>
        </div>

        <div class="col-md-4 col-xl-4">
            <div id="chart-reservas" class="w-100"></div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="{{asset('assets/libs/apexcharts/apexcharts.js')}}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var options = {
            chart: { type: 'bar', height: 350 },
            series: [{ name: 'Reservas', data: @json($reservasPorDia) }],
            xaxis: { categories: @json($diasDaSemana) }
        };

        var chart = new ApexCharts(document.querySelector("#grafico-reservas-dia"), options);
        chart.render();
    });
</script>
@endpush
