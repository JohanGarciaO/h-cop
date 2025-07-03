@extends('layouts.home')
@section('title', 'Gerenciar Quarto')

@section('content')

    @component('partials.components.body-header', ['title' => 'Gerenciar Quarto'])
        @slot('buttons')
            <div>
                <a class="btn btn-outline-secondary" href="javascript:history.back()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left-circle-fill" viewBox="0 0 20 20">
                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0m3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
                    </svg>
                    Voltar
                </a>
                @can('update', $room)
                    <a id="btn_submit_form" class="btn btn-outline-core" data-bs-toggle="modal" data-bs-target="#editRoomModal{{ $room->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 18 18">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg>
                        Editar
                    </a>
                @endcan
            </div>
            <!-- Modal de Edição de Quarto -->
            @include('partials.modals.rooms.edit', ['room' => $room])
        @endslot
    @endcomponent

    <div class="container my-5">
        <div class="row justify-content-md-start gap-5">
            
            {{-- Detalhes do Quarto --}}
            <div class="col-12 p-4 bg-background shadow rounded d-md-flex">
                
                <dl class="row">
                    <h5 class="mb-5 fw-bold">Detalhes do Quarto</h5>
                    <dt class="col-sm-6">Número:</dt>
                    <dd class="col-sm-6">{{ $room->number }}</dd>

                    <dt class="col-sm-6">Status:</dt>
                    <dd class="col-sm-6">
                        @php                            
                            $activeReservationsCount = $room->active_reservations_count;
                            $capacity = $room->capacity;
                        @endphp

                        @if (!$activeReservationsCount)
                            <span class="badge bg-success">vazio</span>
                        @elseif ($activeReservationsCount < $capacity)
                            <span class="badge bg-secondary">possui vagas</span>
                        @elseif ($activeReservationsCount >= $capacity)
                            <span class="badge bg-danger">lotado</span>
                        @endif
                    </dd>

                    <dt class="col-sm-6">Ocupação:</dt>
                    <dd class="col-sm-6">{{ $activeReservationsCount . ' de ' . $room->capacity . ' vagas' }}</dd>

                    <dt class="col-sm-6">Diária:</dt>
                    <dd class="col-sm-6">R$ {{ number_format($room->daily_price, 2, ',', '.') }}</dd>
                </dl>

            </div>

            {{-- Histórico de Reservas --}}
            <div class="col-12 col-lg-12 justify-content-start align-items-center">

                @php
                    $reservations_count = $room->reservations->count();
                @endphp

                <h5 class="mb-5 fw-bold">Histórico de Reservas</h5>
                <div class="row mb-4">
                    <small>Foram encontradas <strong>{{ $reservations_count }}</strong> {{ Str::plural('reserva', $reservations_count) }} para este quarto.</small>
                </div>

                @if ($reservations_count)               
                    <div class="table-responsive">
                        <table class="table table-dark table-hover table-striped align-middle shadow-sm">
                            <thead>
                                <tr>
                                    <th>Hóspede</th>
                                    <th>Status</th>
                                    <th>Diária</th>
                                    <th>Entrada</th>
                                    <th>Saída</th>
                                    <th class="text-nowrap">Check-in</th>
                                    <th class="text-nowrap">Check-out</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($room->reservations as $reservation)
                                    @php $status = $reservation->status; @endphp
                                    <tr>

                                        <td class="fw-bold">{{ $reservation->guest->name }}</td>

                                        <td>
                                            @if ($status == 'check-in pendente')
                                                <span class="badge bg-danger">{{ $status }}</span>
                                            @elseif ($status == 'check-out pendente')
                                                <span class="badge bg-success">{{ $status }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $status }}</span>
                                            @endif
                                        </td>

                                        <td>R$ {{ number_format($reservation->daily_price, 2, ',', '.') ?? '-' }}</td>
                                        <td>{{ $reservation->scheduled_check_in?->format('d/m/Y') ?? '-' }}</td>
                                        <td>{{ $reservation->scheduled_check_out?->format('d/m/Y') ?? '-' }}</td>
                                        <td>{{ $reservation->check_in_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                        <td>{{ $reservation->check_out_at?->format('d/m/Y H:i') ?? '-' }}</td>

                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-outline-primary btn-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-person-vcard-fill" viewBox="0 0 18 18">
                                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0"/>
                                                    </svg>
                                                </a>

                                                <a href="{{ route('reservations.show', ['reservation' => $reservation->id, 'edit' => '1']) }}" class="btn btn-outline-success btn-sm {{$reservation->check_out_at ? 'disabled' : ''}} @cannot('update', $reservation) disabled @endcannot">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 18 18">
                                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                                    </svg>
                                                </a>

                                                <button id="deleteReservationButton{{$reservation->id}}" type="button" class="btn btn-outline-danger btn-sm @cannot('delete', $reservation) disabled @endcannot" data-bs-toggle="modal" data-bs-target="#deleteReservationModal{{ $reservation->id }}">
                                                    <span class="btn-content">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash-fill" viewBox="-2 -2 20 20">
                                                            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                                                        </svg>
                                                    </span>
                                                    <span class="spinner-content d-none">
                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    </span>
                                                </button>
                                            </div>
                                        </td>

                                    </tr>

                                    @include('partials.modals.reservations.delete', ['reservation' => $reservation])
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Histórico de Alterações --}}
            <div class="col-12 col-lg-12 justify-content-start align-items-center">

                @php $cleanings_count = $room->cleanings->count(); @endphp
                <h5 class="mb-5 fw-bold">Histórico de Alterações</h5>
                <div class="row mb-4">
                    <small>Foram encontrados <strong>{{ $cleanings_count }}</strong> {{ Str::plural('reporte', $cleanings_count) }} para este quarto.</small>
                </div>

                @if ($cleanings_count)               
                    <div class="table-responsive">
                        <table class="table table-dark table-hover table-striped align-middle shadow-sm">
                            <thead>
                                <tr>
                                    <th>Reserva</th>
                                    <th>Camareiro(a)</th>
                                    <th>Status</th>
                                    <th>Notas</th>
                                    <th>Última alteração</th>
                                    <th>Quem lançou</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($room->cleanings as $clean)
                                    @php $situation = $clean->status; @endphp
                                    <tr>

                                        <td class="fw-bold">{{ $clean->reservation?->id ? '#' . $clean->reservation?->id : '-' }}</td>
                                        <td class="fw-bold">{{ $clean->housekeeper?->name ?? '-' }}</td>

                                        <td>
                                            @if ($situation->name == 'READY')
                                                <span class="badge bg-success">{{ $situation->label() }}</span>
                                            @elseif ($situation->name == 'IN_PREPARATION')
                                                <span class="badge bg-warning">{{ $situation->label() }}</span>
                                            @elseif ($situation->name == 'NEEDS_MAINTENANCE')
                                                <span class="badge bg-danger">{{ $situation->label() }}</span>
                                            @endif
                                        </td>

                                        <td>{{ $clean->notes ? 'consta' : 'N/C' }}</td>
                                        <td>{{ $clean->updated_at_formated}}</td>
                                        <td>{{ $clean->updatedBy->username}}</td>

                                        <td>
                                            <div class="btn-group" role="group">
                                                @if ($clean->notes)
                                                    <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#viewNotesModal{{ $clean->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye-fill" viewBox="-2 0 20 20">
                                                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                                            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                                                        </svg>
                                                    </button>
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- @include('partials.modals.rooms.view-notes', ['clean' => $clean]) --}}
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
    
@endsection

@push('scripts')
<script>

    $(document).ready(function () {
        //
    })

</script>
@endpush