<div class="modal fade" id="createGuestModal" tabindex="-1" aria-labelledby="createGuestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('guests.store') }}" method="POST">
        @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="createGuestModalLabel">Novo Hóspede</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body d-flex flex-column">
                    
                    <div class="col-12 mb-3">
                        <input type="text" class="form-control" id="name" name="name" minlength="14" placeholder="Digite o nome completo" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <input type="text" class="form-control" id="document" name="document" minlength="14" placeholder="Digite o CPF" required>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" id="phone" name="phone" maxlength="15" placeholder="Digite o telefone" required>
                        </div>
                    </div>
            
                    <div class="col-12 mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Digite o e-mail">
                    </div>
            
                    <div class="col-12 mb-3">
                        <input type="text" class="form-control" id="postal_code" name="postal_code" maxlength="9" placeholder="Digite o CEP" required>
                    </div>
            
                    <div class="row mb-3">
                        <div class="col-6">
                            <select class="form-select" id="state_create_id" name="state_id" data-selected="" data-placeholder="Digite o estado" required>
                                <option></option>
                            </select>
                        </div>            
                        <div class="col-6">
                            <select class="form-select" id="city_create_id" name="city_id" data-selected="" data-placeholder="Digite a cidade" required>
                                <option></option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-9">
                            <input type="text" class="form-control" id="street" name="street" placeholder="Digite o nome da rua" required>
                        </div>
                        <div class="col-3">
                            <input type="text" class="form-control" id="number" name="number" placeholder="Número">
                        </div>
                    </div>
            
                    <div class="row mb-3">
                        <div class="col-6">
                            <input type="text" class="form-control" id="neighborhood" name="neighborhood" placeholder="Digite o bairro" required>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" id="complement" name="complement" placeholder="Complemento">
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button id="createGuestButton" type="submit" class="btn btn-core">
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
    $('#createGuestModal').on('submit', function () {
        const $btn = $('#createGuestButton');
        
        // Desativa botão
        $btn.prop('disabled', true);
        
        // Alterna visibilidade dos elementos
        $btn.find('.btn-content').addClass('d-none');
        $btn.find('.spinner-content').removeClass('d-none');
    });
        
</script>
@endpush