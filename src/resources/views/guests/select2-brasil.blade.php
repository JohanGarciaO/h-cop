<link rel="stylesheet" href="{{ asset('assets/libs/select2/select2-bootstrap-5-theme.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
<script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>

<script>

const state_filter = $('#state_filter_id')
const city_filter = $('#city_filter_id')
let city_filter_cache = []

const state = $('#state_id')
const city = $('#city_id')
let city_cache = []

function normalizeText(str) {
    return str
        .toUpperCase()
        .normalize("NFD")                // separa acento de letra
        .replace(/[\u0300-\u036f]/g, '') // remove acentos
        .trim();
}

function loadCities(uf, selectedCity, city_select) {
    city_select.empty().append(new Option('Filtre por Cidade', '', true, true))

    $.getJSON(`{{ route('brasil.states') }}/${uf}/cities`, function (data) {
        if (city_select == city_filter){
            city_filter_cache = data
        }else{
            city_cache = data
        }

        data.forEach(function (city_data) {
            city_select.append(new Option(city_data.name, city_data.id, false, city_data.id === selectedCity))
        })

        if (selectedCity) {

            let cidadeObj;
            if (city_select == city_filter){
                cidadeObj = city_filter_cache.find(city_data =>
                    normalizeText(city_data.name) === normalizeText(selectedCity)
                )
            }else{
                cidadeObj = city_cache.find(city_data =>
                    normalizeText(city_data.name) === normalizeText(selectedCity)
                )
            }

            if (cidadeObj) {
                city_select.val(cidadeObj.id).trigger('change')
            } else {
                console.warn('Cidade não encontrada:', selectedCity)
            }
        }

    })
}

$(document).ready(() => {

    Array(state_filter, city_filter).forEach(element => {
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

    Array(state, city).forEach(element => {
        $(element).select2({
            theme: 'bootstrap-5',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#createGuestModal'),
            placeholder: $(this).data('placeholder'),
            language: {
                noResults: function () {
                    return "Nenhum resultado encontrado"
                },
            },
        })
    });

    const selectedFilterState = $('#state_filter_id').data('selected')
    const selectedFilterCity = $('#city_filter_id').data('selected')
    
    const selectedState = $('#state_id').data('selected')
    const selectedCity = $('#city_id').data('selected')

    $.getJSON("{{ route('brasil.states') }}", function (data) {

        data.forEach(function (state_data) {
            state_filter.append(new Option(state_data.name, state_data.id, false, state_data.id === selectedFilterState))
            state.append(new Option(state_data.name, state_data.id, false, state_data.id === selectedState))

            if (state_data.id === selectedFilterState) {
                loadCities(state_data.id, selectedFilterCity, city_filter)
            }else if(state_data.id === selectedState){
                loadCities(state_data.id, selectedCity, city)
            }
        })
    })
    
    Array(state_filter, state).forEach((select) => {
        let city_select = (select == state_filter) ? city_filter : city

        select.on('change', () => {
            city_select.empty().append(new Option('Filtre por Cidade', '', true, true))
            if (select.val()) loadCities(select.val(), null, city_select)
        })
    })

})
</script>
