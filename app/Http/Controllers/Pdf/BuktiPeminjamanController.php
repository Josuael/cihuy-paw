<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Barryvdh\DomPDF\Facade\Pdf;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;

class BuktiPeminjamanController extends Controller
{
    public function generate($loan_id)
    {
        // Ambil loan + member + jadwal + pembayaran
        $loan = Loan::with([
                'member',
                'schedules' => function ($q) {
                    $q->orderBy('installment_number');
                },
                'payments'
            ])
            ->findOrFail($loan_id);

        $schedules = $loan->schedules;
        $payments  = $loan->payments;

        $firstSchedule = $schedules->first();
        $lastSchedule  = $schedules->last();

        $totalPaid = $payments->sum('amount_paid');

        $summary = [
            'principal'           => $loan->principal_amount,
            'interest_total'      => $loan->total_interest,
            'total_amount'        => $loan->total_amount,
            'tenor'               => $loan->duration_months,
            'monthly_installment' => $loan->monthly_installment,
            'disbursement_date'   => $loan->disbursement_date,
            'start_installment'   => optional($firstSchedule)->due_date,
            'end_installment'     => optional($lastSchedule)->due_date,
            'total_paid'          => $totalPaid,
            'remaining_balance'   => $loan->remaining_balance,
        ];

        $renderer = new ImageRenderer(
            new RendererStyle(160),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);

        $qrContent = "VERIFY_LOAN:" . $loan->loan_number;
        $qrSvg     = $writer->writeString($qrContent);
        $qrBase64  = base64_encode($qrSvg);

        $pdf = Pdf::loadView('pdf.bp', [
                'loan'     => $loan,
                'summary'  => $summary,
                'schedules'=> $schedules,
                'qr'       => $qrBase64,
            ])
            ->setPaper('A4', 'portrait');

        return $pdf->download("BP-{$loan->loan_number}.pdf");
    }
}
