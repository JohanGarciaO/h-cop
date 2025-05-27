<div class="modal fade" id="checkOutModal" tabindex="-1" aria-labelledby="checkOutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('reservations.check-out', $reservation->id) }}" method="POST">
        @method('POST')
        @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="checkOutModalLabel">Confirma o Check-out?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <p class="">Atenção, ao confirmar a saída do hóspede no quarto não será mais possível reverter esta ação.</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-core">
                        <i class="bi bi-door-open"></i>
                        Fazer check-out
                    </button>
                </div>

            </div>

        </form>
    </div>
</div>