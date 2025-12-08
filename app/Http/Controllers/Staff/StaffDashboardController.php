<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Member;
use App\Models\LoanApplication;
use App\Models\Payment;



class StaffDashboardController extends Controller
{
    public function index()
    {
        $pending  = LoanApplication::where('status', 'Submitted')->count();
        $verified = LoanApplication::where('status', 'Verified')->count();

        $recentApplications = LoanApplication::with('member')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $recentPayments = Payment::with(['loan.member'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('dashboard.staff', [
            'pending'            => $pending,
            'verified'           => $verified,
            'recentApplications' => $recentApplications,
            'recentPayments'     => $recentPayments,
        ]);
    }
}
