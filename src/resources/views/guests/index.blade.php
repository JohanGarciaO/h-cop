@extends('layouts.home')
@section('title', 'Hóspedes')

@section('content')
    
    @component('partials.components.body-header', ['title' => 'Gerenciamento de Hóspedes'])
        @slot('buttons')
            <div>
                <button class="btn btn-core" data-bs-toggle="modal" data-bs-target="#createGuestModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 20 20">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                    </svg> 
                    Novo Hóspede
                </button>
            </div>
            <!-- Modal de Criação de Novo Hóspede -->
            @include('partials.modals.guests.create')
        @endslot
    @endcomponent

    <x-filters action="{{ route('guests.index') }}" results_count="{{ $result_count }}" >
        <x-slot name="filters">
            <div class="col-auto">
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="unhosted" {{ request('status') == 'unhosted' ? 'selected' : '' }}>Não hospedados</option>
                    <option value="check-in-pending" {{ request('status') == 'check-in-pending' ? 'selected' : '' }}>Check-in pendente</option>
                    <option value="check-out-pending" {{ request('status') == 'check-out-pending' ? 'selected' : '' }}>Check-out pendente</option>
                </select>
            </div>
            
            <div class="col-auto">
                <input type="text" name="name_filter" class="form-control" placeholder="Nome do hóspede"
                    value="{{ request('name_filter') }}">
            </div>
            <div class="col-auto">
                <input type="text" id="cpf_filter" name="cpf_filter" class="form-control" maxlength="14" placeholder="CPF do hóspede"
                    value="{{ request('cpf_filter') }}">
            </div>

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

        </x-slot>
    </x-filters>

    <div class="table-responsive">
        <table class="table table-dark table-hover table-striped align-middle shadow-sm">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Status</th>
                    <th>Documento</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Estado</th>
                    <th>Cidade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($guests as $guest)
                    @php 
                    $status = $guest->reservations()->whereNull('check_out_at')?->first()?->status();
                    @endphp
                    <tr>

                        <td>{{ $guest->name }}</td>

                        <td>
                            @if ($status == 'check-in pendente')
                                <span class="badge bg-danger">{{ $status }}</span>
                            @elseif ($status == 'check-out pendente')
                                <span class="badge bg-success">{{ $status }}</span>
                            @else
                                <span class="badge bg-secondary">não hospedado</span>
                            @endif
                        </td>

                        <td>{{ $guest->document }}</td>
                        <td>{{ $guest->phone }}</td>
                        <td>{{ $guest->email }}</td>
                        <td>{{ $guest->address->state->acronym }}</td>
                        <td>{{ $guest->address->city->name }}</td>

                        <td>
                            <div class="btn-group" role="group">
                                <a class="btn btn-outline-primary btn-sm" href="{{ route('guests.show', $guest->id) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-person-vcard-fill" viewBox="0 0 18 18">
                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0"/>
                                    </svg> Ver
                                </a>

                                <a href="{{ route('guests.edit', $guest->id) }}" class="btn btn-outline-success btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 18 18">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                    </svg> Editar
                                </a>

                                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteGuestModal{{ $guest->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash-fill" viewBox="-2 -2 20 20">
                                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                                    </svg>
                                </button>
                            </div>
                        </td>

                    </tr>

                    @include('partials.modals.guests.delete', ['guest' => $guest])
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- <div class="row">
        @foreach ($guests as $guest)
            @php
                $status = $guest->reservations()->whereNull('check_out_at')?->first()?->status();
            @endphp
            
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm h-100">

                    <div class="card-header">
                        <span class="fw-bold">{{ $guest->name }}</span>
                    </div>

                    <div class="card-body">

                        <p class="card-text mb-1">
                            @if ($status == 'check-in pendente')
                                <span class="fw-bold">Status: <span class="badge bg-danger">{{$status}}</span></span>                                
                            @elseif($status == 'check-out pendente')
                                <span class="fw-bold">Status: <span class="badge bg-success">{{$status}}</span></span>     
                            @else                           
                                <span class="fw-bold">Status: <span class="badge bg-secondary">Não hospedado</span></span>                            
                            @endif
                        </p>

                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editRoomModal{{ $guest->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-person-vcard-fill" viewBox="0 0 18 18">
                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0"/>
                                    </svg>
                                    Ver
                                </button>
                                <a class="btn btn-secondary btn-sm" href="{{ route('guests.edit', $guest->id) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 18 18">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                    </svg>
                                    Editar
                                </a>
                            </div>
                            <button type="submit" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteGuestModal{{ $guest->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash-fill" viewBox="-2 0 20 20">
                                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal de Remoção de Quarto -->
            @include('partials.modals.guests.delete', ['guest' => $guest])
        @endforeach
    </div> --}}

    <!-- Paginação -->
    <div class="d-flex justify-content-center mt-4">
        {{ $guests->links('partials.pagination') }}
    </div>

@endsection

@push('scripts')
<script>

    $(document).ready(function () {
        $('#cpf_filter').mask('000.000.000-00')
        $('#cpf').mask('000.000.000-00')
        $('#postal_code').mask('00000-000')

        $('#phone').on('input', function () {
            let value = this.value.replace(/\D/g,'')            
            value = value.replace(/(\d{2})(\d)/,"($1) $2")
            value = value.replace(/(\d)(\d{4})$/,"$1-$2")            
            this.value = value
        })
    })

</script>
@include('guests.select2-brasil')
@endpush
