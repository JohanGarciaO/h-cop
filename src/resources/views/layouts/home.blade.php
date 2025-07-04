<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'H-Cop') }} - @yield('title')</title>
    <meta name="description" content="Sistema de Hotelaria COP30 - Gerencie hóspedes, quartos, reservas e recibos de forma prática e eficiente em ambiente offline.">
    <meta name="author" content="Johan Garcia">
    <meta name="keywords" content="hotelaria, reservas, hóspedes, quartos, recibos, sistema offline, sistema local, H-Cop, COP30, gerenciamento de hotel, Laravel">
    <link rel="shortcut icon" href="{{asset("assets/images/".config('app.logo_navbar').".png")}}" type="image/x-icon">
    @include('partials.css.styles')
    @stack('styles')
</head>
<body class="bg-dark text-light">

    {{-- Importa o Módulo de notificações para erros de sessão e de validação de formulários --}}
    @include('partials.components.notifications')

    @include('partials.navbar')

    <!-- Container principal -->
    <div class="d-flex">
        @include('partials.sidebar')
        

        <!-- Conteúdo principal -->
        <div class="p-4" style="flex: 1;">
            @yield('content')
        </div>

    </div>

    @include('partials.js.scripts')
    @stack('scripts')
</body>
</html>
