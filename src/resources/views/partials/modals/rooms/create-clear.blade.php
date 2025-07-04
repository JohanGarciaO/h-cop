<div class="modal fade" id="createClearRoomModal{{ $clean->id }}" tabindex="-1" aria-labelledby="createClearRoomModalLabel{{ $clean->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('cleanings.store', $clean) }}" method="POST">
        @method('POST')
        @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="createClearRoomModalLabel{{ $clean->id }}">Atualizar situação do quarto</h5>
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

                    <hr class="my-4">

                    {{-- Nova atualização --}}
                    <h6 class="text-secondary fw-bold mb-3">Nova situação do quarto</h6>

                    <input type="hidden" name="room_id" value="{{$clean->room->id}}">
                    <div class="col-12 mb-3">
                        <select class="form-select" name="housekeeper_id" id="housekeeper{{$clean->id}}CreateClear" data-placeholder="Quem reportou a situação?">
                            <option></option>
                            @foreach (App\Models\Housekeeper::all() as $housekeeper)
                                <option value="{{$housekeeper->id}}">{{$housekeeper->name}}</option> 
                            @endforeach
                        </select>
                    </div>

                    <div class="col-auto mb-3">
                        <select class="form-select" name="status" id="status{{$clean->id}}CreateClear" data-placeholder="Estado atual do quarto" required>
                            <option></option>
                            @foreach (App\Enums\RoomCleaningStatus::cases() as $case)
                                <option value="{{$case->name}}">{{Str::of($case->label())->apa()}}</option> 
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 mb-3 d-none" id="js-notes-area-{{$clean->id}}CreateClear">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Alterações (opcional)" id="notes" name="notes" style="height: 100px"></textarea>
                            <label for="notes">Alterações</label>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-core" data-bs-dismiss="modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-floppy-fill" viewBox="0 0 18 18">
                            <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0H3v5.5A1.5 1.5 0 0 0 4.5 7h7A1.5 1.5 0 0 0 13 5.5V0h.086a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5H14v-5.5A1.5 1.5 0 0 0 12.5 9h-9A1.5 1.5 0 0 0 2 10.5V16h-.5A1.5 1.5 0 0 1 0 14.5z"/>
                            <path d="M3 16h10v-5.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5zm9-16H4v5.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5zM9 1h2v4H9z"/>
                        </svg>
                        Salvar
                    </button>
                </div>

            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>

    $(document).ready(() => {
        $('#housekeeper{{$clean->id}}CreateClear').select2({
            theme: 'bootstrap-5',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#createClearRoomModal{{$clean->id}}'),
            placeholder: $(this).data('placeholder'),
            language: {
                noResults: function () {
                    return "Nenhum resultado encontrado"
                },
            },
        })
        $('#status{{$clean->id}}CreateClear').select2({
            theme: 'bootstrap-5',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#createClearRoomModal{{$clean->id}}'),
            placeholder: $(this).data('placeholder'),
            language: {
                noResults: function () {
                    return "Nenhum resultado encontrado"
                },
            },
        })

        const statusSelect = $('#status{{$clean->id}}CreateClear')
        const notesArea = $("#js-notes-area-{{$clean->id}}CreateClear")
        
        // Define a função para verificar o valor e exibir/esconder
        function toggleNotesArea() {
            const selectedValue = statusSelect.val();
            if (selectedValue === 'NEEDS_MAINTENANCE' || selectedValue === 'READY') {
                notesArea.removeClass('d-none')
            } else {
                notesArea.addClass('d-none')
            }
        }

        // Chama a função quando o select mudar
        statusSelect.on('change', toggleNotesArea)
        toggleNotesArea()
    })

    // Loading button
    $("#createClearRoomModal{{$clean->id}}").on('submit', function () {
        const btn = $("#createClearRoomButton{{$clean->id}}");
        
        // Desativa botão
        btn.prop('disabled', true);
        
        // Alterna visibilidade dos elementos
        btn.find('.btn-content').addClass('d-none');
        btn.find('.spinner-content').removeClass('d-none');
    });
        
</script>
@endpush