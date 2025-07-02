@extends('pdfs.layouts.receipt')

@push('styles')
<style>
    body {
        font-family: Times New Roman,arial,verdana;
        color: #333;
        padding: 30px;
    }

    h1, h2 {
        text-align: center;
    }

    .details {
        margin-top: 20px;
    }

    .details table {
        width: 100%;
        border-collapse: collapse;
    }

    .details th,
    .details td {
        padding: 8px;
        text-align: left;
        border: 1px solid #ccc;
    }

    .signature {
        margin-top: 35px;
        text-align: center;
        display: flex;
        justify-content: center;
        gap: 20px;
    }

    .footer {
        margin-top: 40px;
        text-align: center;
        font-size: 0.9em;
        color: #888;
        }
</style>
@endpush

@section('content')
    
    <table width="100%">
        <tr>
            <td align="left"><strong>H-Cop</strong></td>
            <td align="right"><strong>RESERVA Nº: {{ $reservation->id }}</strong></td>
        </tr>
    </table>
    
    <div style="text-align: center; margin: 10px 0;">
        <strong>Recibo de Hospedagem - {{config('app.hotel_name')}}</strong><br>
    </div>
    
    <div style="text-align: center; margin: 20px 0;">
        <img src="{{ $brasao }}" width="80" alt="Brasão do Brasil"><br>
        <strong>MINISTÉRIO DA DEFESA</strong><br>
        COMANDO DA AERONÁUTICA<br>
    </div>
    
    
    <div class="details">

        <p>
            O hóspede <strong>{{ $reservation->guest->name }}</strong>, portador do e-mail <strong>{{ $reservation->guest->email }}</strong> e telefone <strong>{{ $reservation->guest->phone }}</strong>, foi acomodado no quarto de número <strong>{{ $reservation->room->number }}</strong> com diária no valor de <strong>R$ {{ number_format($reservation->daily_price, 2, ',', '.') }}</strong>.
        </p>

        <p>
            A entrada agendada foi para <strong>{{ $reservation->scheduled_check_in->format('d/m/Y') }}</strong> e a saída estava prevista para <strong>{{ $reservation->scheduled_check_out->format('d/m/Y') }}</strong>. O check-in foi realizado em <strong>{{ $reservation->check_in_at->format('d/m/Y \à\s H:i') }}</strong> e o check-out em <strong>{{ $reservation->check_out_at->format('d/m/Y \à\s H:i') }}</strong>.
        </p>

        <p>
            O total de diárias utilizadas foi de <strong>{{ $reservation->numberOfDays ?? '0' }}</strong>, com <strong>{{ $reservation->numberOfDaysLate ? $reservation->numberOfDaysLate : '0' }}</strong> diária(s) a mais do que o agendado. O valor total da hospedagem foi de <strong>R$ {{ number_format($reservation->total_price, 2, ',', '.') }}</strong>.
        </p>

        <p>
            Situação da reserva: <strong>{{ ucfirst($reservation->status) }}</strong>.
        </p>

    </div>
    
    <div class="signature">        
        <div>
            ___________________________________________<br>
            {{ $reservation->guest->name }}<br>
            Hóspede
        </div>
        <div>
            ___________________________________________<br>
            {{config('app.hotel_name')}}<br>
            Hotelaria
        </div>
    </div>

    {{-- <div class="footer">
        <p class="note">Declaro ter recebido os serviços de hospedagem conforme descrito acima. Recibo gerado em {{ now()->format('d/m/Y H:i') }}</p>
    </div> --}}
@endsection
