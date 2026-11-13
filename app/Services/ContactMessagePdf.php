<?php

namespace App\Services;

use App\Models\ContactMessage;
use App\Models\Setting;
use Dompdf\Dompdf;
use Dompdf\Options;

class ContactMessagePdf
{
    public function render(ContactMessage $message): string
    {
        $options = new Options;
        $options->set('isRemoteEnabled', false);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('pdf.message', [
            'message' => $message,
            'settings' => Setting::all_values(),
            'logo' => $this->logo(),
            'generatedAt' => now(),
        ])->render(), 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    private function logo(): ?string
    {
        $path = public_path('images/logo-camer-auto-key-160.png');

        if (! is_file($path)) {
            return null;
        }

        return 'data:image/png;base64,'.base64_encode((string) file_get_contents($path));
    }
}
