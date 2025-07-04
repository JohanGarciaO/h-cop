<div class="modal fade" id="createHousekeeperModal" tabindex="-1" aria-labelledby="createHousekeeperModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('housekeepers.store') }}" method="POST">
        @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="createHousekeeperModalLabel">Novo camareiro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body d-flex flex-column">

                    <div class="col-12 mb-3">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nome completo" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <input type="text" class="form-control" id="document" name="document" maxlength="14" placeholder="Documento" required>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" id="phone" name="phone" maxlength="15" placeholder="Telefone">
                        </div>
                    </div>            

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button id="createHousekeeperButton" type="submit" class="btn btn-core">
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

    $(document).ready(function () {
        
        // Loading button
        $('#createHousekeeperModal').on('submit', function () {
            const $btn = $('#createHousekeeperButton');
            
            // Desativa botão
            $btn.prop('disabled', true);
            
            // Alterna visibilidade dos elementos
            $btn.find('.btn-content').addClass('d-none');
            $btn.find('.spinner-content').removeClass('d-none');
        });

        $('#document').on('input', function () {
            let val = $(this).val().replace(/\D/g, '')

            if (val.length < 8) {             
                // Máscara para o SARAM   
                $(this).mask('#0-0', {reverse: true});                
            }else {
                // Máscara do CPF: 000.000.000-00
                $(this).mask('000.000.000-00')
            }
        })

        $('#phone').on('input', function () {
            let value = this.value.replace(/\D/g,'')            
            value = value.replace(/(\d{2})(\d)/,"($1) $2")
            value = value.replace(/(\d)(\d{4})$/,"$1-$2")            
            this.value = value
        })
        
    })

</script>
@endpush