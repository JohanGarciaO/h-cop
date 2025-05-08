@extends('layouts.home')

@section('title', 'Início')

@section('content')

    @component('partials.components.body-header', ['title' => 'Dashboard'])
    @endcomponent

    <div class="row">
        @php
            $company_stats['percent_change'] = 2;
            $company_stats['total_current'] = 33;
            $company_stats['clients_card_message'] = '2 clientes novos';
        @endphp

        <div class="col-md-4 col-xl-4">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="widget-first">
                        <div class="d-flex align-items-center justify-content-between">

                            <div class="d-flex align-items-center">
                                <div class="p-2 border border-{{ $company_stats['percent_change']>=0 ? 'primary' : 'danger' }} border-opacity-10 bg-{{ $company_stats['percent_change']>=0 ? 'primary' : 'danger' }}-subtle rounded-pill me-2">
                                    <div class="bg-{{ $company_stats['percent_change']>=0 ? 'primary' : 'danger' }} rounded-circle widget-size text-center">

                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#fff" viewBox="-6 -5 30 30" stroke="white" stroke-width="1">
                                            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                                        </svg>

                                    </div>
                                </div>
                                <p class="mb-0 text-dark fs-15">Clientes gerenciados</p>
                            </div>

                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center">
                                    <h3 class="mb-0 fs-22 text-black me-3">{{$company_stats['total_current']}}</h3>
                                    <h5 class="mb-0 fs-14 fw-bold text-{{ $company_stats['percent_change']>=0 ? 'primary' : 'danger' }}">
                                        @if ($company_stats['percent_change']>=0)
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-down"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg>
                                        @endif
                                        {{ number_format($company_stats['percent_change'], 2, ',', '.') }}%
                                    </h5>
                                </div>
                                <small>{{$company_stats['clients_card_message']}}</small>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
