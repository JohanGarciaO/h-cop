<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

class PdfController extends Controller
{
    public function test(string $reservation)
    {
        $reservation = \App\Models\Reservation::find(8)->load(['room', 'guest']);
        $logo = embedImageAsBase64(public_path('assets/images/brasao_brasil.jpg'));

        $html = view('pdfs.receipt', compact('reservation', 'logo'))->render();

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
        $reservation = \App\Models\Reservation::find(8)->load(['room', 'guest']);
        $logo = embedImageAsBase64(public_path('assets/images/brasao_brasil.jpg'));

        return view('pdfs.receipt', compact('reservation', 'logo'));
    }
}
