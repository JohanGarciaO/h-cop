<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Models\Reservation;
use Spatie\Browsershot\Browsershot;

class ReservationReceiptService
{
    public function generate(string $id): string
    {
        $reservation = Reservation::with(['room', 'guest'])->findOrFail($id);

        if ($reservation && $reservation->isActive()) {
            throw new \Exception('Esta reserva não existe ou ainda não foi finalizada.');
        }

        $path = "receipt/reservation_{$reservation->id}.pdf";
        $fullPath = storage_path("app/private/{$path}");

        $brasao = embedImageAsBase64(public_path('assets/images/brasao_brasil.jpg'));
        $html = view('pdfs.receipt', compact('reservation', 'brasao'))->render();

        try {
            Browsershot::html($html)
                // ->setChromePath('/usr/bin/google-chrome')
                // ->setChromePath('/var/www/.cache/puppeteer/chrome/linux-138.0.7204.49/chrome-linux64/chrome')
                ->setChromePath('/var/www/.cache/puppeteer/chrome-headless-shell/linux-138.0.7204.49/chrome-headless-shell-linux64/chrome-headless-shell')
                ->noSandbox()
                ->format('A4')
                ->savePdf($fullPath);

            $reservation->receipt_path = $path;
            $reservation->save();

            return $path;
        } catch (\Throwable $th) {
            \Log::error('Failed to generate PDF: ' . $th->getMessage());
            throw $th;
        }
    }
}