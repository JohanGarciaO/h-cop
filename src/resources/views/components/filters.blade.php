@props(['action', 'results_count' => 0])

<form method="GET" action="{{ $action }}" class="row g-3 mb-3">
    <div class="col-auto">
        <select name="status" class="form-select">
            <option value="">Todos</option>
            <option value="empty" {{ request('status') == 'empty' ? 'selected' : '' }}>Vazios</option>
            <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Não vazios</option>
            <option value="crowded" {{ request('status') == 'crowded' ? 'selected' : '' }}>Cheios</option>
            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Com vagas</option>
        </select>
    </div>
    <div class="col-auto">
        <input type="number" name="min_capacity" class="form-control" placeholder="Capacidade mínima"
            value="{{ request('min_capacity') }}">
    </div>
    <div class="col-auto">
        <input type="number" name="min_free" class="form-control" placeholder="Mínimo de vagas"
            value="{{ request('min_free') }}">
    </div>
    <div class="col-auto">
        <input type="number" name="room_number" class="form-control" placeholder="Número do quarto"
            value="{{ request('room_number') }}">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-core">Filtrar</button>
    </div>
</form>
<div class="row mb-4">
    @if ($results_count > 0)
        <small>
            Foram encontrados <strong>{{ $results_count }}</strong> {{ Str::plural('resultado', $results_count) }} para sua pesquisa.
        </small>
    @else
        <small><strong>Nenhum resultado encontrado.</strong></small>
    @endif
</div>
