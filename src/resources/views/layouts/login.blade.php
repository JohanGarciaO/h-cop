<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{env('APP_NAME')}} - @yield('title')</title>
    <meta name="description" content="Sistema de Hotelaria COP30 - Gerencie hóspedes, quartos, reservas e recibos de forma prática e eficiente em ambiente offline.">
    <meta name="author" content="Johan Garcia">
    <meta name="keywords" content="hotelaria, reservas, hóspedes, quartos, recibos, sistema offline, sistema local, H-Cop, COP30, gerenciamento de hotel, Laravel">
    <link rel="shortcut icon" href="{{asset("assets/images/".config('app.logo_navbar').".png")}}" type="image/x-icon">
    @include('partials.css.styles')
</head>
<body>

    {{-- Importa o Módulo de notificações para erros de sessão e de validação de formulários --}}
    @include('partials.components.notifications')
    
    @yield('content')

    @include('partials.js.scripts')
    @stack('scripts')
</body>
</html>