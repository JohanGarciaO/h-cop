<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config('app.name')}} - Recibo de Reserva</title>
    <style>
        .receipt {
            /* margin-bottom: 30px; */
        }

        .cut-line {
            border-top: 1px dashed #999;
            margin: 25px 0 25px;
            position: relative;
        }

        .cut-line::after {
            content: "Via do Hotel | Via do Hóspede";
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            font-size: 12px;
            color: #999;
            padding: 0 10px;
        }

        .footer {
            font-size: 11px;
            margin-top: 30px;
            color: #666;
        }

        .signature-line {
            margin-top: 40px;
        }

        .signature-line p {
            margin-bottom: 20px;
        }

        .signature-box {
            border-top: 1px solid #000;
            width: 250px;
            margin-top: 10px;
        }

        .note {
            font-size: 12px;
            margin-top: 15px;
            color: #333;
        }
    </style>
    
    @stack('styles')
</head>
<body>

    {{-- Primeira via --}}
    <div class="receipt">
        @yield('content')
    </div>

    <div class="cut-line"></div>

    {{-- Segunda via (duplicada) --}}
    <div class="receipt">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>