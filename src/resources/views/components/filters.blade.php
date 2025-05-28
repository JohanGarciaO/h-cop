@props(['action', 'results_count' => 0])

@push('styles')
<style>

    /* Transição suave para blocos */
    .filters-wrapper,
    .filter-block {
        overflow: hidden;
        transition: all 0.4s ease;
    }

    .filters-wrapper.collapsed,
    .filter-block.collapsed {
        max-height: 0;
        opacity: 0;
        pointer-events: none;
    }

    .filters-wrapper:not(.collapsed),
    .filter-block:not(.collapsed) {
        max-height: 500px; /* Ajuste conforme o necessário */
        opacity: 1;
        pointer-events: auto;
    }

</style>
@endpush

<form method="GET" action="{{ $action }}" class="mb-4" id="filtersForm">

    {{-- BOTÃO GLOBAL QUE MOSTRA TODOS OS GRUPOS --}}
    <div class="mb-3">
        <button type="button" class="btn btn-outline-core d-flex align-items-center gap-2" id="toggle-all-filters">
            <i class="bi bi-eye" id="icon-all-filters"></i>
            <span class="label">Filtros</span>
        </button>
    </div>

    <div id="filters-wrapper" class="filters-wrapper collapsed">
        {{-- BOTÃO E GRUPO 1: $filters --}}
        @if (isset($filters))
            <div class="mb-3">
                <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-2 toggle-group" data-target="#filters-block">
                    <i class="bi bi-unlock" id="icon-filters-block"></i>
                    <span class="label">Filtros simples</span>
                </button>

                <div class="filter-block mt-2 collapsed" id="filters-block">
                    <div class="d-flex flex-wrap gap-3">
                        {{ $filters }}
                    </div>
                </div>
            </div>
        @endif

        {{-- BOTÃO E GRUPO 2: $availability_filters --}}
        @if (isset($availability_filters))
            <div class="mb-3">
                <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-2 toggle-group" data-target="#availability-filters-block">
                    <i class="bi bi-unlock" id="icon-availability-filters-block"></i>
                    <span class="label">Filtrar por disponibilidade</span>
                </button>

                <div class="filter-block mt-2 collapsed" id="availability-filters-block">
                    <div class="d-flex flex-wrap gap-3">
                        {{ $availability_filters }}
                    </div>
                </div>
            </div>
        @endif

        {{-- BOTÃO E GRUPO 3: $locality_filters --}}
        @if (isset($locality_filters))
            <div class="mb-3">
                <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-2 toggle-group" data-target="#locality-filters-block">
                    <i class="bi bi-unlock" id="icon-locality-filters-block"></i>
                    <span class="label">Filtrar por localidade</span>
                </button>

                <div class="filter-block mt-2 collapsed" id="locality-filters-block">
                    <div class="d-flex flex-wrap gap-3">
                        {{ $locality_filters }}
                    </div>
                </div>
            </div>
        @endif

        {{-- BOTÃO E GRUPO 4: $date_filters --}}
        @if (isset($date_filters))
            <div class="mb-3">
                <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-2 toggle-group" data-target="#date-filters-block">
                    <i class="bi bi-unlock" id="icon-date-filters-block"></i>
                    <span class="label">Filtrar por agendamento</span>
                </button>

                <div class="filter-block mt-2 collapsed" id="date-filters-block">
                    <div class="d-flex flex-wrap gap-3">
                        {{ $date_filters }}
                    </div>
                </div>
            </div>
        @endif

        {{-- BOTÃO DE FILTRAR --}}
        <div class="mb-3">
            <button type="submit" class="btn btn-outline-primary btn-content" id="submitFilters">
                <span class="btn-content">
                    <i class="bi bi-search"></i>
                    Filtrar
                </span>
                <span class="spinner-content d-none">
                    <i class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></i>
                    Filtrando...
                </span>
            </button>
        </div>
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

@push('scripts')
<script>

    $(document).ready(function () {

        // Botão geral
        $('#toggle-all-filters').on('click', function () {
            const wrapper = $('#filters-wrapper');
            const icon = $('#icon-all-filters');

            $(this).toggleClass('btn-outline-core btn-core')
            wrapper.toggleClass('collapsed');
            icon.toggleClass('bi-eye bi-eye-slash');
        });

        // Botões de grupo individual
        $('.toggle-group').on('click', function () {
            const targetId = $(this).data('target');
            const block = $(targetId);
            const icon = $(this).find('i');

            $(this).toggleClass('btn-outline-secondary btn-secondary')
            block.toggleClass('collapsed');
            icon.toggleClass('bi-unlock bi-lock');
        });

        // Loading button
        $('#filtersForm').on('submit', function () {
            const $btn = $('#submitFilters');
    
            // Desativa botão
            $btn.prop('disabled', true);

            // Alterna visibilidade dos elementos
            $btn.find('.btn-content').addClass('d-none');
            $btn.find('.spinner-content').removeClass('d-none');
        });

    });

</script>
@endpush