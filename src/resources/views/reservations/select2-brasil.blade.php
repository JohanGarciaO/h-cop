<link rel="stylesheet" href="{{ asset('assets/libs/select2/select2-bootstrap-5-theme.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
<script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>

<script>

const state_filter = $('#state_filter_id')
const city_filter = $('#city_filter_id')

const state_edit = $('#state_edit_id')
const city_edit = $('#city_edit_id')

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

function loadCities(uf, selected_city, city_select) {
    city_select.empty().append(new Option('Filtre por Cidade', '', true, true))

    $.getJSON(`{{ route('brasil.states') }}/${uf}/cities`, function (data) {

        data.forEach(function (city_data) {
            city_select.append(new Option(city_data.name, city_data.id, false, city_data.id === selected_city))
        })

    })
}

$(document).ready(() => {

    Array(state_filter, city_filter, state_edit, city_edit).forEach(element => {
        $(element).select2({
            theme: 'bootstrap-5',
            allowClear: true,
            width:'100%',            
            placeholder: $(this).data('placeholder'),
            language: {
                noResults: function () {
                    return "Nenhum resultado encontrado"
                },
            },
        })
    });

    const selected_state_filter = state_filter.data('selected')
    const selected_city_filter = city_filter.data('selected')

    const selected_state_edit = state_edit.data('selected')
    const selected_city_edit = city_edit.data('selected')

    $.getJSON("{{ route('brasil.states') }}", function (data) {

        data.forEach(function (state_data) {
            state_filter.append(new Option(state_data.name, state_data.id, false, state_data.id === selected_state_filter))
            state_edit.append(new Option(state_data.name, state_data.id, false, state_data.id === selected_state_edit))

            if (state_data.id === selected_state_filter) {
                loadCities(state_data.id, selected_city_filter, city_filter)
            }else if(state_data.id === selected_state_edit){
                loadCities(state_data.id, selected_city_edit, city_edit)
            }
        })
    })
    
    Array(state_filter, state_edit).forEach((select) => {

        let city_select;
        if (select == state_filter) {
            city_select = city_filter
        }else if (select == state_edit) {
            city_select = city_edit
        }

        select.on('change', () => {
            city_select.empty().append(new Option('Filtre por Cidade', '', true, true))
            if (select.val()) loadCities(select.val(), null, city_select)
        })
    })

})
</script>
