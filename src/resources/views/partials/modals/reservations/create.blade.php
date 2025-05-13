<div class="modal fade" id="createReservationModal" tabindex="-1" aria-labelledby="createRerservationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('reservations.store') }}" method="POST">
        @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createRoomModalLabel">Nova Reserva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">

                    @php 
                        $today = Carbon\Carbon::now();
                    @endphp

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="scheduled_check_in" class="form-label">Do dia:</label>
                            <input type="date" class="form-control" id="scheduled_check_in" name="scheduled_check_in" min="{{ $today->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-6">
                            <label for="scheduled_check_out" class="form-label">Ao dia:</label>
                            <input type="date" class="form-control" id="scheduled_check_out" name="scheduled_check_out" min="{{ $today->addDays(1)->format('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="number" class="form-label">Escolha um dos quartos disponíveis:</label>
                        <input type="text" class="form-control" id="number" name="number" min="1" placeholder="Defina qual o número do quarto" required>
                    </div>
                    <div class="mb-3">
                        <label for="capacity" class="form-label">Hóspede:</label>
                        <input type="text" class="form-control" id="capacity" name="capacity" min="1" placeholder="Defina a capacidade do quarto" required>
                    </div>
                    <div class="mb-3">
                        <label for="daily_price" class="form-label">Valor da diária:</label>
                        <input type="number" class="form-control" id="daily_price" name="daily_price" min="1" step="0.01" placeholder="Defina a diária do quarto" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-core">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-house-lock-fill" viewBox="0 0 20 20">
                            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z"/>
                            <path d="m8 3.293 4.72 4.72a3 3 0 0 0-2.709 3.248A2 2 0 0 0 9 13v2H3.5A1.5 1.5 0 0 1 2 13.5V9.293z"/>
                            <path d="M13 9a2 2 0 0 0-2 2v1a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1v-1a2 2 0 0 0-2-2m0 1a1 1 0 0 1 1 1v1h-2v-1a1 1 0 0 1 1-1"/>
                        </svg>
                        Reservar quarto</button>
                </div>
            </div>

        </form>
    </div>
</div>
  
@push('scripts')
<script>

    $(document).ready(() => {

        const checkIn = $('#scheduled_check_in')
        const checkOut = $('#scheduled_check_out')

        checkOut.prop('disabled', true)

        checkIn.on('change', function () {
            const checkInValue = checkIn.val()

            if (!checkInValue) {
                checkOut.val('');
                checkOut.prop('disabled', true);
                return;
            }

            checkOut.prop('disabled', false)

            // Converte a data de entrada para objeto Date e adiciona 1 dia
            const nextDay = new Date(checkInValue);
            nextDay.setDate(nextDay.getDate() + 1); // Adiciona 1 dia
            const minCheckOut = nextDay.toISOString().split('T')[0];

            checkOut.attr('min', minCheckOut)

            // Limpa valor inválido
            if (checkOut.val() && checkOut.val() < minCheckOut) {
                checkOut.val('');
            }
        })

    })

</script>
@endpush