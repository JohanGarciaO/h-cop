<div class="modal fade" id="editRoomModal{{ $room->id }}" tabindex="-1" aria-labelledby="editRoomModalLabel{{ $room->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('rooms.update', $room->id) }}" method="POST">
        @csrf
        @method('PUT')

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoomModalLabel{{ $room->id }}">Editar Quarto {{ $room->number }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="number" class="form-label">Número do Quarto</label>
                        <input type="number" class="form-control" id="number" name="number" value="{{ $room->number }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="capacity" class="form-label">Capacidade</label>
                        <input type="number" class="form-control" id="capacity" name="capacity" value="{{ $room->capacity }}" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Atualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>
  