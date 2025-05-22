<link rel="stylesheet" href="{{ asset('assets/libs/select2/select2-bootstrap-5-theme.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
<script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>

<style>

    .select2-container--default .select2-selection--single {
        height: calc(3.5rem + 2px);
        padding: 1rem 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.5rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 16px;
        right: 0.75rem;
    }

</style>

<script>

const room_create = $('#room_create_id')
const guest_create = $('#guest_create_id')

function normalizeText(str) {
    if (typeof(str) !== 'string') {
        return str
    }
    return str
        .toUpperCase()
        .normalize("NFD")                // separa acento de letra
        .replace(/[\u0300-\u036f]/g, '') // remove acentos
        .trim();
}

function fetchAvailableRooms() {
    const checkIn = $('#scheduled_check_in').val();
    const checkOut = $('#scheduled_check_out').val();

    if (!checkIn || !checkOut || checkIn >= checkOut) {
        room_create.prop('disabled', true);
        updateSelect2Placeholder(room_create, 'Selecione as datas primeiro');
        return;
    }

    room_create.prop('disabled', true);
    updateSelect2Placeholder(room_create, 'Procurando quartos...');
    toggleInputGroupSpinner(room_create, true); // Ativa spinner

    $.ajax({
        url: '/api/available-between/room',
        method: 'GET',
        data: { check_in: checkIn, check_out: checkOut },
        success: function (response) {
            const rooms = response.data || [];
            room_create.empty().append('<option></option>');

            if (rooms.length > 0) {
                updateSelect2Placeholder(room_create, 'Selecione o quarto');
                rooms.forEach(function (room) {
                    room_create.append(
                        $('<option>', {
                            value: room.id,
                            text: `Quarto ${room.number}`,
                            'data-daily-price': room.daily_price
                        })
                    );
                });
                room_create.prop('disabled', false);
            } else {
                updateSelect2Placeholder(room_create, 'Nenhum quarto disponível');
                room_create.prop('disabled', true);
            }

            room_create.trigger('change');
        },
        error: function () {
            updateSelect2Placeholder(room_create, 'Erro ao buscar quartos');
            room_create.prop('disabled', true).trigger('change');
        },
        complete: function () {
            toggleInputGroupSpinner(room_create, false); // Desativa spinner
        }
    });
}

function fetchAvailableGuests() {
    const checkIn = $('#scheduled_check_in').val();
    const checkOut = $('#scheduled_check_out').val();

    if (!checkIn || !checkOut || checkIn >= checkOut) {
        guest_create.prop('disabled', true);
        updateSelect2Placeholder(guest_create, 'Selecione as datas primeiro');
        return;
    }

    guest_create.prop('disabled', true);
    updateSelect2Placeholder(guest_create, 'Procurando hóspedes...');
    toggleInputGroupSpinner(guest_create, true); // Ativa spinner

    $.ajax({
        url: '/api/available-between/guest',
        method: 'GET',
        data: { check_in: checkIn, check_out: checkOut },
        success: function (response) {
            const rooms = response.data || [];
            guest_create.empty().append('<option></option>');

            if (rooms.length > 0) {
                updateSelect2Placeholder(guest_create, 'Selecione o hóspede');
                rooms.forEach(function (guest) {
                    guest_create.append(
                        new Option(`${guest.name}`, guest.id, false, false)
                    );
                });
                guest_create.prop('disabled', false);
            }else {
                updateSelect2Placeholder(guest_create, 'Nenhum hóspede disponível');
                guest_create.prop('disabled', true);
            }

            guest_create.trigger('change');
        },
        error: function () {
            updateSelect2Placeholder(guest_create, 'Erro ao buscar hóspedes');
            guest_create.prop('disabled', true).trigger('change');
        },
        complete: function () {
            toggleInputGroupSpinner(guest_create, false); // Desativa spinner
        }
    });
}

function initializeSelect2($element) {
    $element.select2({
        theme: 'bootstrap-5',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#createReservationModal'),
        placeholder: $element.attr('data-placeholder'),
        language: {
            noResults: function () {
                return "Nenhum resultado encontrado";
            },
        },
    });
}

function updateSelect2Placeholder($element, newPlaceholder) {
    $element.off('select2:select'); // Remove eventos antigos se necessário

    $element.data('placeholder', newPlaceholder);
    $element.attr('data-placeholder', newPlaceholder); // necessário para reuso

    $element.select2('destroy');
    $element.empty().append('<option></option>'); // mantém o campo vazio
    initializeSelect2($element);
}


$(document).ready(() => {

    initializeSelect2(room_create);
    initializeSelect2(guest_create);
    
    const selected_room_create = room_create.data('selected')
    const selected_guest_create = guest_create.data('selected')

    const scheduled_check_in = $('#scheduled_check_in').val()
    const scheduled_check_out = $('#scheduled_check_out').val()

    $('#scheduled_check_in, #scheduled_check_out').on('change', function () {
        const checkIn = $('#scheduled_check_in').val();
        const checkOut = $('#scheduled_check_out').val();

        if (checkIn && checkOut && checkIn < checkOut) {
            fetchAvailableRooms();
            fetchAvailableGuests();
        } else {
            room_create.prop('disabled', true);
            guest_create.prop('disabled', true);
            updateSelect2Placeholder(room_create, 'Quarto');
            updateSelect2Placeholder(guest_create, 'Hóspede');
        }
    });

    room_create.on('change', function () {
        const selectedOption = $(this).find(':selected')
        const dailyPrice = selectedOption.data('daily-price')
        $('#daily_price').val(dailyPrice || '')
        $('#daily_price').trigger('change')
    })

})
</script>
