<div class="modal fade" id="editReservationModal" tabindex="-1" aria-labelledby="editReservationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="reservationForm" data-reservation-id="{{$reservation->id}}" action="{{ route('reservations.update', $reservation->id) }}" method="POST">
        @csrf
        @method('PUT')

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createRoomModalLabel">Editar Reserva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="card p-3 border-0 rounded-3">

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input 
                                        type="date" 
                                        class="form-control" 
                                        id="scheduled_check_in" 
                                        name="scheduled_check_in" 
                                        value="{{$reservation->scheduled_check_in->format('Y-m-d')}}"
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
                                        value="{{$reservation->scheduled_check_out->format('Y-m-d')}}"
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
                                            data-selected="{{ old('room_id', $reservation->room->id) }}"
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
                                            data-selected="{{ old('room_id', $reservation->guest->id) }}"
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
                                        step="0.01" 
                                        placeholder="Diária" 
                                        data-value="{{$reservation->daily_price}}"
                                        value="{{$reservation->daily_price}}"
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-floppy-fill" viewBox="0 0 18 18">
                                <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0H3v5.5A1.5 1.5 0 0 0 4.5 7h7A1.5 1.5 0 0 0 13 5.5V0h.086a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5H14v-5.5A1.5 1.5 0 0 0 12.5 9h-9A1.5 1.5 0 0 0 2 10.5V16h-.5A1.5 1.5 0 0 1 0 14.5z"/>
                                <path d="M3 16h10v-5.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5zm9-16H4v5.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5zM9 1h2v4H9z"/>
                            </svg>
                            <span>Salvar</span>
                        </span>
                        <span class="spinner-content d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span>Salvando...</span>
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

        // checkOut.prop('disabled', true)

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

        $('#daily_price').on('input', function () {
            let input = $(this);
            let value = input.val().replace(/[^\d]/g, '');

            if (value.length === 0) {
                input.val('');
                return;
            }

            // Divide por 100 e fixa em 2 casas decimais
            let formatted = (parseInt(value, 10) / 100).toFixed(2);
            input.val(formatted);
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