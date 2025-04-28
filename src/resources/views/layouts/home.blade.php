<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>

    <!-- CSS do Bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    {{-- Ícones do Bootstrap Icons --}}
    <link href="{{ asset('assets/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
</head>
<body class="bg-dark text-light">

    @include('partials.navbar')

    <!-- Container principal -->
    <div class="d-flex">
        @include('partials.sidebar')
        

        <!-- Conteúdo principal -->
        <div class="p-4" style="flex: 1;">
            @yield('content')
        </div>

    </div>

    <!-- JS do Bootstrap -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
