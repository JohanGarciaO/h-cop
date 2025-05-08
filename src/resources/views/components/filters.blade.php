@props(['action', 'results_count' => 0])

<form method="GET" action="{{ $action }}" class="row g-3 mb-3">

    {{-- Slot para filtros customizados --}}
    {{ $filters }}

    <div class="col-auto">
        <button type="submit" class="btn btn-core">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-search" viewBox="0 0 18 18">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
            </svg>
            Filtrar</button>
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
