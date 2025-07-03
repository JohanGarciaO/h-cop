<div class="modal fade" id="viewNotesModal{{ $clean->id }}" tabindex="-1" aria-labelledby="viewNotesModalLabel{{ $clean->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="#">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="viewNotesModalLabel{{ $clean->id }}">Notas de alterações</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body d-flex flex-column">

                    {{-- Último relato --}}
                    <div>
                        <small class="text-muted d-block">Última atualização: {{ $clean->updated_at_formated }}</small>
                        <p class="mb-0">
                            <span class="fw-bold text-muted">Por: </span>{{ $clean->housekeeper?->name ?? 'Não informado' }}<br>
                            <span class="fw-bold text-muted">Relato: </span>{{ $clean->notes ?: 'Nenhum' }}
                        </p>
                    </div>               

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>

            </div>
        </form>
    </div>
</div>