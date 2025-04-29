<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{env('APP_NAME')}} - @yield('title')</title>

    <!-- Link para o CSS do Bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <!-- Link para o CSS personalizado -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
</head>
<body>

    {{-- Importa o Módulo de notificações para erros de sessão e de validação de formulários --}}
    @include('partials.components.notifications')
    
    @yield('content')

    <!-- Link para o JS do Bootstrap -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    
    @stack('scripts')
</body>
</html>