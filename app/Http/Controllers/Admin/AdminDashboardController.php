<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Member;
use App\Models\InstallmentSchedule;
use App\Models\Payment;
use App\Models\LoanApplication;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ============================
        // 1. MONTH LIST (last 6 months)
        // ============================
        $months = collect(range(0, 5))->map(function ($i) {
            return Carbon::now()->subMonths($i)->format('M Y');
        })->reverse()->values();


        // ================================================
        // 2. LOANS PER MONTH (jumlah pinjaman baru / bulan)
        // ================================================
        $loansData = [];

        foreach ($months as $month) {
            $time = Carbon::createFromFormat('M Y', $month);

            $loansData[] = Loan::whereYear('created_at', $time->year)
                                ->whereMonth('created_at', $time->month)
                                ->count();
        }


        // ========================================================
        // 3. PAYMENTS PER MONTH (jumlah uang angsuran masuk / bulan)
        // ========================================================
        $paymentsData = [];

        foreach ($months as $month) {
            $time = Carbon::createFromFormat('M Y', $month);

            $paymentsData[] = Payment::whereYear('created_at', $time->year)
                                      ->whereMonth('created_at', $time->month)
                                      ->sum('amount_paid');
        }


        // ======================================================
        // 4. Monthly income (pendapatan bulan ini)
        // ======================================================
        $monthlyIncome = Payment::whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->sum('amount_paid');


        // ======================================================
        // 5. Overdue count (angsuran terlambat)
        // ======================================================
        $overdueCount = InstallmentSchedule::where('status', 'overdue')->count();


        // ======================================================
        // 6. Active borrowers (anggota yang sedang meminjam)
        // ======================================================
        $activeBorrowers = Loan::where('status', 'active')
                               ->distinct('member_id')
                               ->count('member_id');


        // ======================================================
        // 7. TOTAL LOAN (pinjaman aktif)
        // ======================================================
        $totalLoans = Loan::where('status', 'active')->count();


        // ======================================================
        // 8. HISTORY PENGAJUAN & PINJAMAN (untuk tabel bawah)
        // ======================================================

        // 8a. 5 pengajuan pinjaman terbaru (semua status)
        $recentApplications = LoanApplication::with('member')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // 8b. 5 pinjaman aktif / terbaru
        $recentLoans = Loan::with('member')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();


        // SEND DATA KE VIEW
        return view('dashboard.admin', [
            'months'           => $months,
            'loansData'        => $loansData,
            'paymentsData'     => $paymentsData,
            'monthlyIncome'    => $monthlyIncome,
            'overdueCount'     => $overdueCount,
            'activeBorrowers'  => $activeBorrowers,
            'totalLoans'       => $totalLoans,

            // tambahan history
            'recentApplications' => $recentApplications,
            'recentLoans'        => $recentLoans,
        ]);
    }
}
