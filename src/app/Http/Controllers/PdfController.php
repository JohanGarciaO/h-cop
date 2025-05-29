<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

class PdfController extends Controller
{
    public function test(string $reservation)
    {
        // Cria dados fictícios simulando uma reserva
        $reservation = (object) [
            'guest' => (object) ['name' => 'João da Silva'],
            'room' => (object) ['number' => '204'],
            'scheduled_check_in' => '2025-06-01 14:00',
            'scheduled_check_out' => '2025-06-05 12:00',
            'daily_price' => 150.00,
            'check_in_at' => '2025-06-01 14:15',
            'check_out_at' => null,
            'total_days' => 4,
            'total_price' => 600.00,
        ];

        $html = view('pdfs.receipt', compact('reservation'))->render();

        return response(
            Browsershot::html($html)
                ->setChromePath('/usr/bin/google-chrome')
                ->format('A4')
                ->showBackground()
                ->pdf()
        )->header('Content-Type', 'application/pdf');
    }

    public function view()
    {
        $reservation = (object) [
            'guest' => (object) ['name' => 'João da Silva'],
            'room' => (object) ['number' => '204'],
            'scheduled_check_in' => '2025-06-01 14:00',
            'scheduled_check_out' => '2025-06-05 12:00',
            'daily_price' => 150.00,
            'check_in_at' => '2025-06-01 14:15',
            'check_out_at' => null,
            'total_days' => 4,
            'total_price' => 600.00,
        ];

        return view('pdfs.receipt', compact('reservation'));
    }
}
