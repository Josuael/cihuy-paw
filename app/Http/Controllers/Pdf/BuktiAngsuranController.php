<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;

class BuktiAngsuranController extends Controller
{
    public function generate(Payment $payment)
    {
        $payment->load(['loan.member', 'schedule']);

        $loan     = $payment->loan;
        $member   = $loan->member;
        $schedule = $payment->schedule;

        $loanTotal = $loan->total_amount;

        $totalPaidSampaiSekarang = $loan->payments()
            ->where(function ($q) use ($payment) {
                $q->where('payment_date', '<', $payment->payment_date)
                  ->orWhere(function ($q2) use ($payment) {
                      $q2->where('payment_date', $payment->payment_date)
                         ->where('payment_id', '<=', $payment->payment_id);
                  });
            })
            ->sum('amount_paid');

        $remainingAfter = $loanTotal - $totalPaidSampaiSekarang;

        $renderer = new ImageRenderer(
            new RendererStyle(160),
            new SvgImageBackEnd()
        );
        $writer   = new Writer($renderer);

        $qrContent = "VERIFY_PAYMENT:" . $payment->payment_id;
        $qrSvg     = $writer->writeString($qrContent);
        $qrBase64  = base64_encode($qrSvg);

        $pdf = Pdf::loadView('pdf.ba', [
                'payment'        => $payment,
                'loan'           => $loan,
                'member'         => $member,
                'schedule'       => $schedule,
                'remainingAfter' => $remainingAfter,
                'qr'             => $qrBase64,
            ])
            ->setPaper('A4', 'portrait');

        $number = $payment->payment_number ?? ('PAY-' . $payment->payment_id);

        return $pdf->download("BA-{$number}.pdf");
    }
}
