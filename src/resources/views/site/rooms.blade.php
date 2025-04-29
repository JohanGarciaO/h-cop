@extends('layouts.home')

@section('title', 'Quartos')

@section('content')
    
    @component('partials.components.body-header', ['title' => 'Gerenciamento de Quartos'])
        @slot('buttons')
            <div>
                <button class="btn btn-core" data-bs-toggle="modal" data-bs-target="#createRoomModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 20 20">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                    </svg> 
                    Novo Quarto
                </button>
            </div>
        @endslot
    @endcomponent

    <div class="row">

    @foreach ($rooms as $room)
        @php
            $capacity = $room->capacity;
            $occupied = $room->activeReservations->count();
            $percent = $capacity > 0 ? ($occupied / $capacity) * 100 : 0;
            $percentFormatted = number_format($percent, 0);
        @endphp
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Quarto:</h5>
                    <h4 class="card-title"><span class="badge bg-background">{{ $room->number }}</span></h4>

                    <p class="card-text mb-1">
                        <span class="fw-bold">Ocupação:</span>
                        <span class="occupancy-ratio" data-current="{{ $occupied }}" data-capacity="{{ $capacity }}">0/0</span>
                    </p>
                    <div class="progress mb-3" style="height: 18px;">
                        <div 
                            class="progress-bar percent-label bg-background fw-bold" 
                            role="progressbar" 
                            style="width: 0%" 
                            data-target="{{ $percent }}"
                            aria-valuenow="{{ $percent }}" 
                            aria-valuemin="0" 
                            aria-valuemax="100"
                        >0%</div>
                    </div>
                    {{-- <p class="card-text">
                        <small class="text-muted">
                            Ocupação: <span class="percent-label" data-target="{{ $percent }}">0%</span>
                        </small>
                    </p> --}}

                    <div class="d-flex justify-content-between">
                        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editRoomModal{{ $room->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 18 18">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                            Editar
                        </button>
                        <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteRoomModal{{ $room->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash-fill" viewBox="-2 0 20 20">
                                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    <!-- Modal de Edição de Quarto -->
    @include('partials.modals.rooms.edit', ['room' => $room])

    <!-- Modal de Remoção de Quarto -->
    @include('partials.modals.rooms.delete', ['room' => $room])

    @endforeach
</div>

<!-- Modal de Criação de Novo Quarto -->
@include('partials.modals.rooms.create')

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {

        // Animação da ProgressBar
        document.querySelectorAll('.progress-bar').forEach(bar => {
            const percent = parseInt(bar.getAttribute('data-target')) || 0;

            bar.style.transition = 'width 1s ease-in-out';
            setTimeout(() => {
                bar.style.width = percent + '%';
                bar.setAttribute('aria-valuenow', percent);

                // Muda cor para vermelho se for 100%
                if (percent >= 100) {
                    bar.classList.remove('bg-primary');
                    bar.classList.add('bg-danger');
                }
            }, 100);
        });

        // Animação do texto de percentual (0% → N%)
        document.querySelectorAll('.percent-label').forEach(label => {
            const target = parseInt(label.getAttribute('data-target')) || 0;
            let count = 0;
            const step = Math.ceil(target / 20); // 20 frames

            const interval = setInterval(() => {
                count += step;
                if (count >= target) {
                    count = target;
                    clearInterval(interval);
                }
                label.textContent = count + '%';
            }, 30);
        });

        // Animação do texto X/Y
        document.querySelectorAll('.occupancy-ratio').forEach(el => {
            const current = parseInt(el.getAttribute('data-current')) || 0;
            const capacity = parseInt(el.getAttribute('data-capacity')) || 0;
            let count = 0;
            const step = 1;

            const interval = setInterval(() => {
                count += step;
                if (count >= current) {
                    count = current;
                    clearInterval(interval);
                }
                el.textContent = `${count}/${capacity}`;
            }, 40);
        });

    });
</script>
@endpush
