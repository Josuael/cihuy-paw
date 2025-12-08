<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Support\Facades\Auth;

class LoanDetailController extends Controller
{
    public function show($loan_id)
    {
        $member = Auth::user()->member;

        // pastikan ini loan milik member yang login
        $loan = Loan::with(['member', 'schedules', 'payments'])
            ->where('loan_id', $loan_id)
            ->where('member_id', $member->member_id)
            ->firstOrFail();

        $member        = $loan->member;
        $wallet        = $member->wallet;
        $walletBalance = $wallet->balance ?? 0;
        
        $schedules = $loan->schedules()->orderBy('installment_number')->get();
        $payments  = $loan->payments()->orderBy('payment_date')->get();

        return view('loans.show-loan', [
            'loan'          => $loan,
            'schedules'     => $schedules,
            'payments'      => $payments,
            'walletBalance' => $walletBalance,
        ]);
    }
}
