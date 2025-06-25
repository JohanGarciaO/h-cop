<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Editar usuário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body d-flex flex-column">

                    <div class="row-12 mb-3 d-flex justify-content-between">
                        <div class="col-5">
                            <select class="form-select" name="role_id" id="role" data-selected="" data-placeholder="Tipo de usuário" required>
                                <option value="" disabled selected>Tipo de usuário</option>
                                @foreach (App\Enums\Role::cases() as $role)
                                    <option value="{{$role->value}}" {{$role->value === $user->role_id ? 'selected' : ''}}>{{$role->label()}}</option> 
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Nome de usuário" value="{{ $user->username }}" required>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nome completo" value="{{ $user->name }}" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <input type="text" class="form-control" id="document{{ $user->id }}" name="document" maxlength="14" placeholder="Documento" value="{{ $user->document }}" required>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" id="phone{{ $user->id }}" name="phone" maxlength="15" placeholder="Telefone" value="{{ $user->phone }}" required>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" value="{{ $user->email }}" required>
                    </div>  

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button id="submitUserButton{{$user->id}}" type="submit" class="btn btn-core">
                        <span class="btn-content">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 18 18">
                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                            </svg>
                            Editar
                        </span>
                        <span class="spinner-content d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span>Editando...</span>
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

        $("#document{{ $user->id }}").on('input', function() {
            let val = $(this).val().replace(/\D/g, '')

            if (val.length < 8) {             
                // Máscara para o SARAM   
                $(this).mask('#0-0', {reverse: true});                
            }else {
                // Máscara do CPF: 000.000.000-00
                $(this).mask('000.000.000-00')
            }
        })

        $("#phone{{ $user->id }}").on('input', function () {
            let value = this.value.replace(/\D/g,'')            
            value = value.replace(/(\d{2})(\d)/,"($1) $2")
            value = value.replace(/(\d)(\d{4})$/,"$1-$2")            
            this.value = value
        })
        
        // Loading button
        $("#editUserModal{{ $user->id }}").on('submit', function () {
            const $btn = $('#submitUserButton{{ $user->id }}');
            
            // Desativa botão
            $btn.prop('disabled', true);
            
            // Alterna visibilidade dos elementos
            $btn.find('.btn-content').addClass('d-none');
            $btn.find('.spinner-content').removeClass('d-none');
        });
    
    });

</script>
@endpush