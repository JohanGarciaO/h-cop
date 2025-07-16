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

        // Define o diretório que ficará os receibos
        $dirName = "receipts";
        $storageDir = storage_path("app/private/{$dirName}");

        // Cria o diretório de recibos se não existir ainda
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        $relativePath = $dirName . "/reservation_{$reservation->id}.pdf";
        $fullPath = storage_path("app/private/{$relativePath}");

        $brasao = embedImageAsBase64(public_path('assets/images/brasao_brasil.jpg')); // Aqui deverá ser dinâmica a logo passada para o template do hotel (no futuro)
        $html = view('pdfs.receipt', compact('reservation', 'brasao'))->render();

        try {
            Browsershot::html($html)
                // ->setChromePath('/usr/bin/google-chrome')
                // ->setChromePath('/var/www/.cache/puppeteer/chrome/linux-138.0.7204.49/chrome-linux64/chrome')
                // ->setChromePath('/var/www/.cache/puppeteer/chrome-headless-shell/linux-138.0.7204.49/chrome-headless-shell-linux64/chrome-headless-shell')
                // ->setChromePath(env('CHROME_PATH'))
                ->noSandbox()
                ->format('A4')
                ->savePdf($fullPath);

            $reservation->receipt_path = $relativePath;
            $reservation->save();

            return $relativePath;
        } catch (\Throwable $th) {
            \Log::error('Failed to generate PDF: ' . $th->getMessage());
            throw $th;
        }
    }
}
