@extends('layouts.home')

@section('title', 'Quartos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gerenciamento de Quartos</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoomModal">
        <i class="bi bi-plus-circle"></i> Novo Quarto
    </button>
</div>

<div class="row">
    @foreach ($rooms as $room)
        @php
            $capacity = $room->capacity;
            $activeCount = $room->activeReservations->count();
            $percent = $capacity > 0 ? ($activeCount / $capacity) * 100 : 0;
            $percentFormatted = number_format($percent, 0);
        @endphp
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Quarto {{ $room->number }}</h5>
                    <p class="card-text mb-1">
                        <strong>Capacidade:</strong> {{ $room->$capacity }}
                    </p>
                    <p class="card-text mb-1">
                        <strong>Ocupação:</strong> {{ $activeCount }}/{{ $room->$capacity }} ({{ number_format($percent, 0) }}%)
                    </p>
                    <div class="progress mb-3" style="height: 6px;">
                        <div 
                            class="progress-bar bg-{{ $percent >= 100 ? 'danger' : 'primary' }}" 
                            role="progressbar" 
                            style="width: {{ $percent }}%;" 
                            aria-valuenow="{{ $percent }}" 
                            aria-valuemin="0" 
                            aria-valuemax="100">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditRoom{{ $room->id }}">
                            Editar
                        </button>
                        <form action="{{ route('rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este quarto?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                Remover
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <!-- Modal de Edição de Quarto -->
    @include('partials.modals.rooms.edit', ['room' => $room])

    @endforeach
</div>

<!-- Modal de Criação de Novo Quarto -->
@include('partials.modals.rooms.create')

@endsection
