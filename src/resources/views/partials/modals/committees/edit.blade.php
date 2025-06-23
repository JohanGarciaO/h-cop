<div class="modal fade" id="editCommitteeModal{{ $committee->id }}" tabindex="-1" aria-labelledby="editCommitteeModalLabel{{ $committee->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('committees.update', $committee->id) }}" method="POST">
        @csrf
        @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="editCommitteeModalLabel{{ $committee->id }}">Atualizar comitiva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body d-flex flex-column">

                    <div class="col-12 mb-3">
                        <input type="text" class="form-control" id="name" name="name" minlength="14" placeholder="Digite o nome da comitiva" value="{{ $committee->name }}" required>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Descrição (opcional)" id="description" name="description" style="height: 100px">{{ $committee->description }}</textarea>
                            <label for="description">Descrição</label>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button id="submitCommitteeButton{{$committee->id}}" type="submit" class="btn btn-core">
                        <span class="btn-content">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 18 18">
                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                            </svg>
                            Atualizar
                        </span>
                        <span class="spinner-content d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span>Atualizando...</span>
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
    $("#editCommitteeModal{{ $committee->id }}").on('submit', function () {
        const $btn = $('#submitCommitteeButton{{ $committee->id }}');

        // Desativa botão
        $btn.prop('disabled', true);

        // Alterna visibilidade dos elementos
        $btn.find('.btn-content').addClass('d-none');
        $btn.find('.spinner-content').removeClass('d-none');
    });

</script>
@endpush