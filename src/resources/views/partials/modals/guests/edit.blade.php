<div class="modal fade" id="editGuestModal{{ $guest->id }}" tabindex="-1" aria-labelledby="editGuestModalLabel{{ $guest->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('guests.update', $guest) }}" method="POST">
        @csrf
        @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="editGuestModalLabel{{ $guest->id }}">Editar Hóspede</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body d-flex flex-column">
                    <div class="col-12 mb-3">
                        <input type="text" class="form-control" id="name_edit" name="name" minlength="14" placeholder="Digite o nome completo" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <input type="text" class="form-control" id="document_edit" name="document" minlength="14" placeholder="Digite o CPF" required>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" id="phone_edit" name="phone" maxlength="15" placeholder="Digite o telefone" required>
                        </div>
                    </div>
            
                    <div class="col-12 mb-3">
                        <input type="email" class="form-control" id="email_edit" name="email" placeholder="Digite o e-mail">
                    </div>
            
                    <div class="col-12 mb-3">
                        <input type="text" class="form-control" id="postal_code_edit" name="postal_code" maxlength="9" placeholder="Digite o CEP" required>
                    </div>
            
                    <div class="row mb-3">
                        <div class="col-6">
                            <select class="form-select" id="state_id_edit" name="state_id" data-selected="" data-placeholder="Digite o estado" required>
                                <option></option>
                            </select>
                        </div>            
                        <div class="col-6">
                            <select class="form-select" id="city_id_edit" name="city_id" data-selected="" data-placeholder="Digite a cidade" required>
                                <option></option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-9">
                            <input type="text" class="form-control" id="street_edit" name="street" placeholder="Digite o nome da rua" required>
                        </div>
                        <div class="col-3">
                            <input type="text" class="form-control" id="number_edit" name="number" placeholder="Número">
                        </div>
                    </div>
            
                    <div class="row mb-3">
                        <div class="col-6">
                            <input type="text" class="form-control" id="neighborhood_edit" name="neighborhood" placeholder="Digite o bairro" required>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" id="complement_edit" name="complement" placeholder="Complemento">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-core">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 18 18">
                            <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                        </svg>
                        Atualizar</button>
                </div>

            </div>
        </form>
    </div>
</div>
  