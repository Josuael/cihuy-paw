<?php

namespace App\Services;

use App\Models\InstallmentSchedule;
use App\Models\Loan;
use Carbon\Carbon;

class ScheduleService
{
    /**
     * Generate jadwal angsuran untuk satu loan.
     */
    public static function generate(Loan $loan, int $months, float $monthly, float $principal, float $interest): void
    {
        // Kalau sudah ada jadwal untuk loan ini, jangan buat lagi
        if (InstallmentSchedule::where('loan_id', $loan->loan_id)->exists()) {
            return;
        }

        for ($i = 1; $i <= $months; $i++) {
            InstallmentSchedule::create([
                'loan_id'            => $loan->loan_id,
                'installment_number' => $i,
                'due_date'           => Carbon::parse($loan->disbursement_date)->addMonths($i - 1),
                'principal_amount'   => $principal,
                'interest_amount'    => $interest,
                'total_amount'       => $monthly,
                'status'             => 'pending',
            ]);
        }
    }
}
