<link rel="stylesheet" href="{{ asset('assets/libs/select2/select2-bootstrap-5-theme.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
<script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>

<script>

let estadosCache = []
let cidadesCache = []

function normalizeText(str) {
    return str
        .toUpperCase()
        .normalize("NFD")                // separa acento de letra
        .replace(/[\u0300-\u036f]/g, '') // remove acentos
        .trim();
}

function loadCities(uf, selectedCity) {
    $('#city_id').empty().append(new Option('Filtre por Cidade', '', true, true))

    $.getJSON(`{{ route('brasil.states') }}/${uf}/cities`, function (data) {
        cidadesCache = data

        data.sort((a, b) => a.name.localeCompare(b.name)).forEach(function (city) {

            $('#city_id').append(new Option(city.name, city.id, false, city.id === selectedCity))
        })

        if (selectedCity) {
            const cidadeObj = cidadesCache.find(city =>
                normalizeText(city.name) === normalizeText(selectedCity)
            )

            if (cidadeObj) {
                $('#city_id').val(cidadeObj.id).trigger('change')
            } else {
                console.warn('Cidade não encontrada:', selectedCity)
            }
        }

    })
}

$(document).ready(() => {

    $('#state_id').select2({
        theme: 'bootstrap-5',
        allowClear: true,
        width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        language: {
            noResults: function () {
                return "Nenhum resultado encontrado"
            },
        },
    })
    $('#city_id').select2({
        theme: 'bootstrap-5',
        allowClear: true,
        width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        language: {
            noResults: function () {
                return "Nenhum resultado encontrado"
            },
        }
    })

    const selectedState = $('#state_id').data('selected')
    const selectedCity = $('#city_id').data('selected')

    $.getJSON("{{ route('brasil.states') }}", function (data) {
        estadosCache = data // salva os estados para usar depois

        let states = data.sort((a, b) => a.name.localeCompare(b.name))
        states.forEach(function (state) {
            $('#state_id').append(new Option(state.name, state.id, false, state.id === selectedState))
            if (state.id === selectedState) {
                loadCities(state.id, selectedCity)
            }
        })
    })

    $('#state_id').on('change', function () {
        loadCities($(this).val(), null)
    })

})

</script>
