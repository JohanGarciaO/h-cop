<div class="modal fade" id="createReservationModal" tabindex="-1" aria-labelledby="createRerservationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="reservationForm" action="{{ route('reservations.store') }}" method="POST">
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

                    <div class="card p-3 border-0 rounded-3">

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input 
                                        type="date" 
                                        class="form-control" 
                                        id="scheduled_check_in" 
                                        name="scheduled_check_in" 
                                        min="{{ $today->format('Y-m-d') }}" 
                                        required
                                    >
                                    <label for="scheduled_check_in">Do dia</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input 
                                        type="date" 
                                        class="form-control" 
                                        id="scheduled_check_out" 
                                        name="scheduled_check_out" 
                                        min="{{ $today->addDays(1)->format('Y-m-d') }}" 
                                        required
                                    >
                                    <label for="scheduled_check_out">Ao dia</label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-door-open text-muted"></i>
                                        <span class="spinner-border spinner-border-sm text-primary d-none" style="width: 1rem; height: 1rem;" aria-hidden="true"></span>
                                    </span>
                                    <div class="form-floating">
                                        <select 
                                            class="form-select" 
                                            id="room_create_id" 
                                            name="room_id" 
                                            aria-label="Selecione o quarto"
                                            data-placeholder="Quarto"
                                            disabled
                                            required
                                        >
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person text-muted"></i>
                                        <span class="spinner-border spinner-border-sm text-primary d-none" style="width: 1rem; height: 1rem;" aria-hidden="true"></span>
                                    </span>
                                    <div class="form-floating">
                                        <select 
                                            class="form-select" 
                                            id="guest_create_id" 
                                            name="guest_id" 
                                            aria-label="Seldecione o hóspede"
                                            data-placeholder="Hóspede"
                                            disabled
                                            required
                                        >
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-2">
                            <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-currency-dollar text-muted"></i></span>
                                        <input 
                                        type="number" 
                                        class="form-control" 
                                        id="daily_price" 
                                        name="daily_price" 
                                        min="1" 
                                        step="1" 
                                        placeholder="Diária" 
                                        required
                                        >
                                    </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-12">
                                <h5 for="total_display" class="form-label text-muted">Resumo da reserva:</h5>
                                <div 
                                    class="form-control bg-light d-flex align-items-center justify-content-between"
                                    id="total_display"
                                    style="transition: all 0.3s ease; font-weight: 500;"
                                >
                                    <div>
                                        <i class="bi bi-calendar-week me-2 text-primary"></i>
                                        <span id="summary_days">0 diárias</span>
                                    </div>
                                    <div>
                                        <i class="bi bi-currency-dollar me-1 text-success"></i>
                                        <span id="total_amount">R$ 0,00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                    <button type="submit" class="btn btn-core d-flex align-items-center justify-content-center gap-2" id="submitReservation">
                        <span class="btn-content">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                class="bi bi-house-lock-fill" viewBox="0 0 20 20">
                                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z"/>
                                <path d="m8 3.293 4.72 4.72a3 3 0 0 0-2.709 3.248A2 2 0 0 0 9 13v2H3.5A1.5 1.5 0 0 1 2 13.5V9.293z"/>
                                <path d="M13 9a2 2 0 0 0-2 2v1a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1v-1a2 2 0 0 0-2-2m0 1a1 1 0 0 1 1 1v1h-2v-1a1 1 0 0 1 1-1"/>
                            </svg>
                            <span>Reservar quarto</span>
                        </span>
                        <span class="spinner-content d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span>Reservando...</span>
                        </span>
                    </button>

                </div>
            </div>

        </form>
    </div>
</div>
  
@push('scripts')
<script>

    function toggleInputGroupSpinner(selectElement, showSpinner = true) {
        const inputGroupText = $(selectElement).closest('.input-group').find('.input-group-text');
        const icon = inputGroupText.find('i');
        const spinner = inputGroupText.find('.spinner-border');

        if (showSpinner) {
            icon.addClass('d-none');
            spinner.removeClass('d-none');
        } else {
            icon.removeClass('d-none');
            spinner.addClass('d-none');
        }
    }

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

        $('#daily_price').on('change', () => {
            // Lógica para calcular o valor total (qtd dias * daily_price)
            const dailyPrice = parseFloat($('#daily_price').val());
            const checkIn = new Date($('#scheduled_check_in').val());
            const checkOut = new Date($('#scheduled_check_out').val());

            const $summaryDays = $('#summary_days');
            const $totalAmount = $('#total_amount');

            if (!isNaN(dailyPrice) && checkIn && checkOut && checkOut > checkIn) {
                const timeDiff = checkOut - checkIn;
                const days = timeDiff / (1000 * 60 * 60 * 24);
                const total = dailyPrice * days;

                const formattedTotal  = total.toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                });

                const dayLabel = days === 1 ? '1 diária' : `${days} diárias`;

                $summaryDays.fadeOut(150, function () {
                    $(this).text(dayLabel).fadeIn(150);
                });

                $totalAmount.fadeOut(150, function () {
                    $(this).text(formattedTotal).fadeIn(150);
                });
            } else {
                $summaryDays.text('0 diárias');
                $totalAmount.text('R$ 0,00');
            }
        });

        // Loading button
        $('#reservationForm').on('submit', function () {
            const $btn = $('#submitReservation');
    
            // Desativa botão
            $btn.prop('disabled', true);

            // Alterna visibilidade dos elementos
            $btn.find('.btn-content').addClass('d-none');
            $btn.find('.spinner-content').removeClass('d-none');
        });

    })

</script>
@endpush