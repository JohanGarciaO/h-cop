@extends('layouts.home')
@section('title', 'Reservas')

@section('content')
    
    @component('partials.components.body-header', ['title' => 'Gerenciamento de Reservas'])
        @slot('buttons')
            <div>
                <button class="btn btn-core" data-bs-toggle="modal" data-bs-target="#createReservationModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 20 20">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                    </svg> 
                    Nova Reserva
                </button>
            </div>
            <!-- Modal de Criação de Novo Hóspede -->
            @include('partials.modals.reservations.create')
        @endslot
    @endcomponent

    <x-filters action="{{ route('reservations.index') }}" results_count="{{ $result_count }}" >

        <x-slot name="filters">
            <div class="col-auto">
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Reservas finalizadas</option>
                    <option value="check-in-pending" {{ request('status') == 'check-in-pending' ? 'selected' : '' }}>Check-in pendente</option>
                    <option value="check-out-pending" {{ request('status') == 'check-out-pending' ? 'selected' : '' }}>Check-out pendente</option>
                </select>
            </div>

            <div class="col-auto">
                <input type="text" name="number_reservation_filter" class="form-control" placeholder="Número da reserva"
                    value="{{ request('number_reservation_filter') }}">
            </div>

            <div class="col-auto">
                <input type="text" name="number_room_filter" class="form-control" placeholder="Número do quarto"
                    value="{{ request('number_room_filter') }}">
            </div>
            
            <div class="col-auto">
                <input type="text" name="name_guest_filter" class="form-control" placeholder="Nome do hóspede"
                    value="{{ request('name_guest_filter') }}">
            </div>

            <div class="col-auto">
                <input type="text" id="document_guest_filter" name="document_guest_filter" class="form-control" maxlength="14" placeholder="Documento do hóspede"
                    value="{{ request('document_guest_filter') }}">
            </div>
            
            <div class="col-auto">
                <select name="gender_filter" id="gender_filter" class="form-select" data-selected="{{ request('gender_filter') ?? '' }}" data-placeholder="Gênero do hóspede">                
                    <option></option>
                    @foreach (App\Enums\Gender::cases() as $case)
                        <option value="{{$case->value}}" {{ request('gender_filter') == $case->value ? 'selected' : '' }}>{{$case->label()}}</option> 
                    @endforeach
                </select>
            </div>            

        </x-slot>

        <x-slot name="locality_filters">
            <x-slot name="locality_filters_title">Filtrar por origem</x-slot>

            <div class="col-auto">
                <select class="form-select" name="state_filter_id" id="state_filter_id" data-selected="{{ request('state_filter_id') ?? '' }}" data-placeholder="Filtre por estado">
                    <option></option>
                </select>
            </div>
            <div class="col-auto">
                <select class="form-select" name="city_filter_id" id="city_filter_id" data-selected="{{ request('city_filter_id') ?? '' }}" data-placeholder="Filtre por cidade">
                    <option></option>
                </select>
            </div>
            <div class="col-auto">
                <select class="form-select" name="committee_filter" id="committee_filter" data-selected="{{ request('committee_filter') ?? '' }}" data-placeholder="Filtre por comitiva">
                    <option></option>
                    @foreach (App\Models\Committee::all() as $committee)
                        <option value="{{$committee->id}}" {{ request('committee_filter') == $committee->id ? 'selected' : '' }}>{{$committee->name}}</option> 
                    @endforeach
                </select>
            </div>
        </x-slot>

        <x-slot name="date_filters">
            <div class="col-auto">
                Dia da entrada:
                <input type="date" id="scheduled_check_in" name="check_in" class="form-control" value="{{ request('check_in') }}">
            </div>
            <div class="col-auto">
                Dia da saída:
                <input type="date" id="scheduled_check_out" name="check_out" class="form-control" value="{{ request('check_out') }}">
            </div>
        </x-slot>

    </x-filters>

    <div class="table-responsive">
        <table class="table table-dark table-hover table-striped align-middle shadow-sm">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Hóspede</th>
                    @if (request('gender_filter'))
                    <th>Gênero</th>                        
                    @endif
                    <th>Quarto</th>
                    <th>Status</th>
                    @if (request('committee_filter'))
                        <th>Comitiva</th>
                    @endif
                    <th>Diária</th>
                    <th>Entrada</th>
                    <th>Saída</th>
                    <th class="text-nowrap">Check-in</th>
                    <th class="text-nowrap">Check-out</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $reservation)
                    @php $status = $reservation->status; @endphp
                    <tr>

                        <td class="fw-bold">#{{ $reservation->id }}</td>
                        <td>{{ $reservation->guest->name }}</td>
                        <td>{{ $reservation->room->number }}</td>
                        @if (request('gender_filter'))
                            <td>{{ $reservation->guest->gender->value }}</td>
                        @endif

                        <td>
                            @if ($status == 'check-in pendente')
                                <span class="badge bg-danger">{{ $status }}</span>
                            @elseif ($status == 'check-out pendente')
                                <span class="badge bg-success">{{ $status }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $status }}</span>
                            @endif
                        </td>

                        @if (request('committee_filter'))
                            <td>{{ $reservation->guest?->committee?->name ?? '-' }}</td>
                        @endif
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
                                    </svg> Ver
                                </a>

                                <a href="{{ route('reservations.show', ['reservation' => $reservation->id, 'edit' => '1']) }}" class="btn btn-outline-success btn-sm {{$reservation->check_out_at ? 'disabled' : ''}} @cannot('update', $reservation) disabled @endcannot">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 18 18">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                    </svg> Editar
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

    <!-- Paginação -->
    <div class="d-flex justify-content-center mt-4">
        {{ $reservations->links('partials.pagination') }}
    </div>

@endsection

@push('scripts')
<script>

    $(document).ready(function () {
        $('#document_guest_filter').on('input', function () {
            let val = $(this).val().replace(/\D/g, '')

            if (val.length < 8) {             
                // Máscara para o SARAM   
                $(this).mask('#0-0', {reverse: true});                
            }else {
                // Máscara do CPF: 000.000.000-00
                $(this).mask('000.000.000-00')
            }
        })
    })

</script>
@include('reservations.select2', ['modalId' => 'createReservationModal'])
@include('reservations.select2-brasil')
@endpush
