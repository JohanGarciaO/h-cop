<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Reserva</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; padding: 30px; }
        h1 { text-align: center; }
        .details { margin-top: 20px; }
        .details table { width: 100%; border-collapse: collapse; }
        .details th, .details td { padding: 8px; text-align: left; border: 1px solid #ccc; }
        .footer { margin-top: 40px; text-align: center; font-size: 0.9em; color: #888; }
    </style>
</head>
<body>
    <h1>Recibo de Reserva</h1>
    <p><strong>Hóspede:</strong> {{ $reservation->guest->name }}</p>
    <p><strong>Quarto:</strong> {{ $reservation->room->number }}</p>
    <p><strong>Período:</strong> {{ $reservation->scheduled_check_in }} até {{ $reservation->scheduled_check_out }}</p>
    <p><strong>Valor da Diária:</strong> R$ {{ number_format($reservation->daily_price, 2, ',', '.') }}</p>

    <div class="details">
        <table>
            <tr>
                <th>Check-in Real</th>
                <td>{{ $reservation->check_in_at ?? '---' }}</td>
            </tr>
            <tr>
                <th>Check-out Real</th>
                <td>{{ $reservation->check_out_at ?? '---' }}</td>
            </tr>
            <tr>
                <th>Total de Diárias</th>
                <td>{{ $reservation->total_days }}</td>
            </tr>
            <tr>
                <th>Total</th>
                <td><strong>R$ {{ number_format($reservation->total_price, 2, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>SISTEMA DE HOTELARIA COP30 - Recibo gerado em {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
