@extends('layouts.home')
@section('title', 'Gerenciar reserva')

@section('content')

    @component('partials.components.body-header', ['title' => 'Gerenciar Reserva'])
        @slot('buttons')
            <div>
                <a class="btn btn-outline-secondary" href="{{ route('reservations.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left-circle-fill" viewBox="0 0 20 20">
                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0m3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
                    </svg>
                    Voltar
                </a>
                @if (!$reservation->check_out_at)
                    <button id="btn_submit_form" class="btn btn-outline-core" data-bs-toggle="modal" data-bs-target="#editReservationModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 18 18">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg>
                        Editar
                    </button>
                @endif
            </div>
            <!-- Modal de Edição da Reserva -->
            @include('partials.modals.reservations.edit', ['reservation' => $reservation])
        @endslot
    @endcomponent

    <div class="container my-5">
        <div class="row justify-content-md-center">
            
            <div class="col-12 col-lg-4 p-4 bg-background shadow rounded">
                
                <h5 class="mb-5 fw-bold">Detalhes da Reserva</h5>

                <dl class="row">
                    <dt class="col-sm-6">Status:</dt>
                    <dd class="col-sm-6">
                        @if ($reservation->check_out_at)
                            Finalizada
                        @elseif ($reservation->check_in_at)
                            Check-in realizado
                        @else
                            Aguardando check-in
                        @endif
                    </dd>

                    <dt class="col-sm-6">Quarto:</dt>
                    <dd class="col-sm-6">{{ $reservation->room->number }}</dd>

                    <dt class="col-sm-6">Hóspede:</dt>
                    <dd class="col-sm-6">{{ $reservation->guest->name }}</dd>

                    @if ($reservation->guest->email)
                        <dt class="col-sm-6">E-mail:</dt>
                        <dd class="col-sm-6">{{ $reservation->guest->email }}</dd>
                    @endif

                    <dt class="col-sm-6">Telefone:</dt>
                    <dd class="col-sm-6">{{ $reservation->guest->phone }}</dd>


                    <dt class="col-sm-6">Valor da Diária:</dt>
                    <dd class="col-sm-6">R$ {{ number_format($reservation->daily_price, 2, ',', '.') }}</dd>

                    <dt class="col-sm-6">Entrada agendada:</dt>
                    <dd class="col-sm-6">{{ \Carbon\Carbon::parse($reservation->scheduled_check_in)->format('d/m/Y') }}</dd>

                    <dt class="col-sm-6">Saída agendada:</dt>
                    <dd class="col-sm-6">{{ \Carbon\Carbon::parse($reservation->scheduled_check_out)->format('d/m/Y') }}</dd>

                    <dt class="col-sm-6">Check-in:</dt>
                    <dd class="col-sm-6">
                        {{ $reservation->check_in_at ? \Carbon\Carbon::parse($reservation->check_in_at)->format('d/m/Y à\s H:i') : 'Não realizado' }}
                    </dd>

                    <dt class="col-sm-6">Check-out:</dt>
                    <dd class="col-sm-6">
                        {{ $reservation->check_out_at ? \Carbon\Carbon::parse($reservation->check_out_at)->format('d/m/Y à\s H:i') : 'Não realizado' }}
                    </dd>
                </dl>

                <div class="d-flex gap-2">
                    @if (!$reservation->check_in_at)
                        <form method="POST" action="{{ route('reservations.check-in', $reservation->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-door-open"></i> Fazer Check-in
                            </button>
                        </form>
                    @endif

                    @if ($reservation->check_in_at && !$reservation->check_out_at)
                        <form method="POST" action="{{ route('reservations.check-out', $reservation->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-door-closed"></i> Fazer Check-out
                            </button>
                        </form>
                    @endif
                </div>

            </div>

            <div class="col-8 d-none d-lg-flex justify-content-center align-items-center">
                <img src="{{asset('assets/images/edit.webp')}}" alt="Ilustração de edição" class="img-fluid" style="max-height: 800px;">
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script>

    $(document).ready(function () {
        
        const urlParams = new URLSearchParams(window.location.search);
        const openEdit = urlParams.get('edit');

        if (openEdit === '1') {
            const modal = new bootstrap.Modal(document.getElementById('editReservationModal'));
            modal.show();
        }

    })

</script>
@include('reservations.select2', ['modalId' => 'editReservationModal'])
@endpush