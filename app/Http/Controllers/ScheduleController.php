<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Services\ScheduleService;

class ScheduleController extends Controller
{
    public function generate($loan_id)
    {
        $loan = Loan::findOrFail($loan_id);

        ScheduleService::generate(
            $loan,
            $loan->duration_months,
            $loan->monthly_installment,
            $loan->principal_amount / $loan->duration_months,
            $loan->total_interest   / $loan->duration_months
        );

        return back()->with('success', 'Jadwal angsuran dibuat');
    }
}
