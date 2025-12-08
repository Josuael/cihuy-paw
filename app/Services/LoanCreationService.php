<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\LoanApplication;
use App\Services\InterestService;
use App\Services\ScheduleService;
use App\Services\LoanNumberService;

class LoanCreationService
{
    public static function createLoanFromApplication(LoanApplication $app, $rate = 2)
    {
        // 1. Hitung bunga
        $calc = InterestService::calculateFlat(
            $app->request_amount,
            $rate,
            $app->duration_months
        );

        // 2. Buat pinjaman
        $loan = Loan::create([
            'loan_number' => LoanNumberService::generate('BP'),
            'application_id' => $app->application_id,
            'member_id' => $app->member_id,
            'disbursement_date' => now(),
            'principal_amount' => $app->request_amount,
            'interest_rate' => $rate,
            'interest_type' => 'flat',
            'duration_months' => $app->duration_months,
            'monthly_installment' => $calc['monthly'],
            'total_interest' => $calc['total_interest'],
            'total_amount' => $calc['total_amount'],
            'remaining_balance' => $calc['total_amount'],
            'status' => 'active'
        ]);

        // 3. Generate jadwal angsuran
        ScheduleService::generate(
            $loan,
            $app->duration_months,
            $calc['monthly'],
            $app->request_amount / $app->duration_months,
            $calc['total_interest'] / $app->duration_months
        );

        return $loan;
    }
}
