<div class="modal fade" id="deleteRoomModal{{ $room->id }}" tabindex="-1" aria-labelledby="deleteRoomModalLabel{{ $room->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('rooms.destroy', $room) }}" method="POST">
        @method('DELETE')
        @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoomModalLabel{{ $room->id }}">Remover Quarto</h5>
                    {{-- <h4 class="card-title"><span class="badge bg-background"> {{ $room->number }}</span></h4> --}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <p class="">Tem certeza de que deseja excluir o quarto <b>{{$room->number}}</b>?</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-core">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash-fill" viewBox="-2 0 20 20">
                            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                        </svg>
                        Apagar</button>
                </div>

            </div>

        </form>
    </div>
</div>