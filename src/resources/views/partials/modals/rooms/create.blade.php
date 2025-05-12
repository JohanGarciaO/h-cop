<div class="modal fade" id="createRoomModal" tabindex="-1" aria-labelledby="createRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('rooms.store') }}" method="POST">
        @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createRoomModalLabel">Novo Quarto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="number" class="form-label">Número do Quarto:</label>
                        <input type="number" class="form-control" id="number" name="number" min="1" placeholder="Defina qual o número do quarto" required>
                    </div>
                    <div class="mb-3">
                        <label for="capacity" class="form-label">Capacidade:</label>
                        <input type="number" class="form-control" id="capacity" name="capacity" min="1" placeholder="Defina a capacidade do quarto" required>
                    </div>
                    <div class="mb-3">
                        <label for="daily_price" class="form-label">Diária:</label>
                        <input type="number" class="form-control" id="daily_price" name="daily_price" min="1" step="0.01" placeholder="Defina a diária do quarto" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-core">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 20 20">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                        </svg> 
                        Adicionar</button>
                </div>
            </div>

        </form>
    </div>
</div>
  