<link rel="stylesheet" href="{{ asset('assets/libs/select2/select2-bootstrap-5-theme.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
<script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>

<script>

const situationFilter = $('#situationFilter')

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

$(document).ready(() => {

    Array(situationFilter).forEach(element => {
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

    // Array(housekeeper).forEach(element => {
    //     $(element).select2({
    //         theme: 'bootstrap-5',
    //         allowClear: true,
    //         width: '100%',
    //         dropdownParent: $('#clearRoomModal'),
    //         placeholder: $(this).data('placeholder'),
    //         language: {
    //             noResults: function () {
    //                 return "Nenhum resultado encontrado"
    //             },
    //         },
    //     })
    // });

})
</script>
