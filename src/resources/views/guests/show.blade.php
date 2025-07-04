@extends('layouts.home')
@section('title', 'Gerenciar reserva')

@section('content')

    @component('partials.components.body-header', ['title' => 'Gerenciar Hóspede'])
        @slot('buttons')
            <div>
                <a class="btn btn-outline-secondary" href="javascript:history.back()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left-circle-fill" viewBox="0 0 20 20">
                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0m3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
                    </svg>
                    Voltar
                </a>
                <a id="btn_submit_form" class="btn btn-outline-core" href="{{ route('guests.edit', $guest->id) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 18 18">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg>
                    Editar
                </a>
            </div>
        @endslot
    @endcomponent

    <div class="container my-5">
        <div class="row justify-content-md-start gap-5">
            
            <div class="col-12 p-4 bg-background shadow rounded d-md-flex">
                
                <dl class="row">
                    <h5 class="mb-5 fw-bold">Detalhes do Hóspede</h5>
                    <dt class="col-sm-5">Nome:</dt>
                    <dd class="col-sm-7">{{ $guest->name }}</dd>

                    <dt class="col-sm-5">Status:</dt>
                    <dd class="col-sm-7">
                        @if ($status == 'check-in pendente')
                            <span class="badge bg-danger">{{ $status }}</span>
                        @elseif ($status == 'check-out pendente')
                            <span class="badge bg-success">{{ $status }}</span>
                        @else
                            <span class="badge bg-secondary">nenhuma hospedagem ativa</span>
                        @endif
                    </dd>

                    <dt class="col-sm-5">Documento:</dt>
                    <dd class="col-sm-7">{{ $guest->document }}</dd>

                    <dt class="col-sm-5">Telefone:</dt>
                    <dd class="col-sm-7">{{ $guest->phone }}</dd>

                    @if ($guest->email)
                        <dt class="col-sm-5">E-mail:</dt>
                        <dd class="col-sm-7">{{ $guest->email }}</dd>
                    @endif

                    @if ($guest->committee_id)
                        <dt class="col-sm-5">Comitiva:</dt>
                        <dd class="col-sm-7">{{ $guest->committee->name }}</dd>
                    @endif

                    <dt class="col-sm-5">Gênero:</dt>
                    <dd class="col-sm-7">{{ $guest->gender->label() }}</dd>
                </dl>

                
                <dl class="row">
                    <h5 class="mb-5 fw-bold">Detalhes do Endereço</h5>
                    <dt class="col-sm-5">CEP:</dt>
                    <dd class="col-sm-7">{{ $guest->address->postal_code }}</dd>

                    <dt class="col-sm-5">Estado:</dt>
                    <dd class="col-sm-7">{{ $guest->address->state->name }}</dd>

                    <dt class="col-sm-5">Cidade:</dt>
                    <dd class="col-sm-7">{{ $guest->address->city->name }}</dd>

                    <dt class="col-sm-5">Rua:</dt>
                    <dd class="col-sm-7">{{ $guest->address->street }}</dd>

                    <dt class="col-sm-5">Número:</dt>
                    <dd class="col-sm-7">{{ $guest->address->number ?? 's/n' }}</dd>

                    @if ($guest->address->complement)
                        <dt class="col-sm-5">Complemento:</dt>
                        <dd class="col-sm-7">{{ $guest->address->complement }}</dd>
                    @endif
                </dl>

            </div>

            <div class="col-12 col-lg-12 justify-content-start align-items-center">
                {{-- <img src="{{asset('assets/images/edit.webp')}}" alt="Ilustração de edição" class="img-fluid" style="max-height: 800px;"> --}}
                <h5 class="mb-5 fw-bold">Histórico de Reservas</h5>
                @if ($guest->reservations->count())
                    <div class="table-responsive">
                        <table class="table table-dark table-hover table-striped align-middle shadow-sm">
                            <thead>
                                <tr>
                                    <th>Quarto</th>
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
                                @foreach ($guest->reservations as $reservation)
                                    @php $status = $reservation->status; @endphp
                                    <tr>

                                        <td class="fw-bold">{{ $reservation->room->number }}</td>

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
                @else
                    Este hóspede ainda não se hospedou nenhuma vez neste hotel.
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