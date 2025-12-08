<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;

// QR Code Dependencies (SVG)
use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;

class BuktiAngsuranController extends Controller
{
    public function generate(Payment $payment)
    {
        // load relasi yang dibutuhkan
        $payment->load(['loan.member', 'schedule']);

        $loan     = $payment->loan;
        $schedule = $payment->schedule;

        // QR Code - SVG Base64
        $renderer = new ImageRenderer(
            new RendererStyle(150),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        $qrContent = "PAYMENT:" . $payment->payment_number;

        $qrBase64 = base64_encode(
            $writer->writeString($qrContent)
        );

        // Render PDF
        $pdf = Pdf::loadView('pdf.ba', [
                'payment'  => $payment,
                'loan'     => $loan,
                'schedule' => $schedule,
                'qr'       => $qrBase64,
            ])
            ->setPaper('A4', 'portrait');

        return $pdf->download("BA-{$payment->payment_number}.pdf");
    }
}
