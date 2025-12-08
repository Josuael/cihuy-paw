<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanApplication;

class KetuaDashboardController extends Controller
{
    public function index()
    {
        // Berapa yang masih nunggu final approval (setelah admin otorisasi)
        $pendingFinal = LoanApplication::where('status', 'Authorized')->count();

        // Berapa pinjaman yang sedang aktif
        $activeLoans = Loan::where('status', 'active')->count();

        // List pengajuan yang masih menunggu ketua
        $waitingApprovals = LoanApplication::with('member')
            ->where('status', 'Authorized')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Pinjaman yang baru disetujui / dicairkan
        $recentApprovedLoans = Loan::with('member')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('dashboard.ketua', [
            'pendingFinal'        => $pendingFinal,
            'activeLoans'         => $activeLoans,
            'waitingApprovals'    => $waitingApprovals,
            'recentApprovedLoans' => $recentApprovedLoans,
        ]);
    }
}
