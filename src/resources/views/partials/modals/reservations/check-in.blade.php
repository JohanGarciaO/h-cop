<div class="modal fade" id="checkInModal" tabindex="-1" aria-labelledby="checkInModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('reservations.check-in', $reservation->id) }}" method="POST">
        @method('POST')
        @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="checkInModalLabel">Confirma o Check-in?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <p class="">Atenção, ao confirmar a entrada do hóspede no quarto não será mais possível reverter esta ação.</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-core" data-bs-dismiss="modal">
                        <i class="bi bi-door-open"></i>
                        Fazer check-in
                    </button>
                </div>

            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>

    // Loading button
    $('#checkInModal').on('submit', function () {
        const $btn = $('#checkInButton');
        
        // Desativa botão
        $btn.prop('disabled', true);
        
        // Alterna visibilidade dos elementos
        $btn.find('.btn-content').addClass('d-none');
        $btn.find('.spinner-content').removeClass('d-none');
    });
        
</script>
@endpush