<div class="modal fade" id="deleteHousekeeperModal{{ $housekeeper->id }}" tabindex="-1" aria-labelledby="deleteHousekeeperModalLabel{{ $housekeeper->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('housekeepers.destroy', $housekeeper) }}" method="POST">
        @method('DELETE')
        @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Apagar Camareiro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <p class="">Tem certeza de que deseja apagar o camareiro <b>{{$housekeeper->name}}</b> do sistema?</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-core" data-bs-dismiss="modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash-fill" viewBox="-2 0 20 20">
                            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                        </svg>
                        Apagar</button>
                </div>

            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>

    // Loading button
    $("#deleteHousekeeperModal{{$housekeeper->id}}").on('submit', function () {
        const $btn = $("#deleteHousekeeperButton{{$housekeeper->id}}");
        
        // Desativa botão
        $btn.prop('disabled', true);
        
        // Alterna visibilidade dos elementos
        $btn.find('.btn-content').addClass('d-none');
        $btn.find('.spinner-content').removeClass('d-none');
    });
        
</script>
@endpush