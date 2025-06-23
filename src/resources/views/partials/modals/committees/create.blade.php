<div class="modal fade" id="createCommitteeModal" tabindex="-1" aria-labelledby="createCommitteeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('committees.store') }}" method="POST">
        @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="createCommitteeModalLabel">Nova comitiva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body d-flex flex-column">

                    <div class="col-12 mb-3">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Digite o nome da comitiva" required>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Descrição (opcional)" id="description" name="description" style="height: 100px"></textarea>
                            <label for="description">Descrição</label>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button id="createCommitteeButton" type="submit" class="btn btn-core">
                        <span class="btn-content">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 20 20">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                            </svg>
                            Criar
                        </span>
                        <span class="spinner-content d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span>Criando...</span>
                        </span>
                    </button>
                </div>

            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>

    // Loading button
    $('#createCommitteeModal').on('submit', function () {
        const $btn = $('#createCommitteeButton');

        // Desativa botão
        $btn.prop('disabled', true);

        // Alterna visibilidade dos elementos
        $btn.find('.btn-content').addClass('d-none');
        $btn.find('.spinner-content').removeClass('d-none');
    });

</script>
@endpush