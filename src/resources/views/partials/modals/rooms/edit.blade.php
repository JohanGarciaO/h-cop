<div class="modal fade" id="editRoomModal{{ $room->id }}" tabindex="-1" aria-labelledby="editRoomModalLabel{{ $room->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('rooms.update', $room->id) }}" method="POST">
        @csrf
        @method('PUT')

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoomModalLabel{{ $room->id }}">Atualizar Quarto</b></h5>
                    {{-- <h4 class="card-title"><span class="badge bg-background"> {{ $room->number }}</span></h4> --}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="number" class="form-label">Número do Quarto:</label>
                        <input type="number" class="form-control" id="number" name="number" value="{{ $room->number }}" placeholder="Defina qual o número do quarto" required>
                    </div>
                    <div class="mb-3">
                        <label for="capacity" class="form-label">Capacidade:</label>
                        <input type="number" class="form-control" id="capacity" name="capacity" value="{{ $room->capacity }}" placeholder="Defina a capacidade do quarto" required>
                    </div>
                    <div class="mb-3">
                        <label for="daily_price" class="form-label">Diária:</label>
                        <input type="number" class="form-control" id="daily_price" name="daily_price" min="1" step="0.01" value="{{ $room->daily_price }}" placeholder="Defina a diária do quarto" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-core d-flex align-items-center justify-content-center gap-2" id="submitRoomButton{{$room->id}}">
                        <span class="btn-content">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 18 18">
                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                            </svg>
                            <span>Atualizar</span>
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
$("#editRoomModal{{$room->id}}").on('submit', function () {
    const $btn = $("#submitRoomButton{{$room->id}}");

    // Desativa botão
    $btn.prop('disabled', true);

    // Alterna visibilidade dos elementos
    $btn.find('.btn-content').addClass('d-none');
    $btn.find('.spinner-content').removeClass('d-none');
});

</script>
@endpush