<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Barryvdh\DomPDF\Facade\Pdf;

// QR Code Dependencies (SVG renderer)
use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;

class BuktiPeminjamanController extends Controller
{
    public function generate(Loan $loan)
    {
        // pastikan relasi member ikut di-load
        $loan->load('member');

        // QR Code Generation - SVG (works in all versions)
        $renderer = new ImageRenderer(
            new RendererStyle(150),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        $qrBase64 = base64_encode(
            $writer->writeString("VERIFY_LOAN:" . $loan->loan_number)
        );

        $pdf = Pdf::loadView('pdf.bp', [
                'loan' => $loan,
                'qr'   => $qrBase64,
            ])
            ->setPaper('A4', 'portrait');

        return $pdf->download("BP-{$loan->loan_number}.pdf");
    }
}
