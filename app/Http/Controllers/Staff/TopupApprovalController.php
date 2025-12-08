<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;

class TopupApprovalController extends Controller
{
    // List semua request top up yang masih pending
    public function index()
    {
        $requests = WalletTransaction::with(['wallet.member'])
            ->where('type', 'topup')
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->get();

        return view('staff.topup.index', compact('requests'));
    }

    // Approve top up → saldo wallet bertambah
    public function approve($id)
    {
        $tx = WalletTransaction::with('wallet')->findOrFail($id);

        // Safety check
        if ($tx->type !== 'topup' || $tx->status !== 'pending') {
            abort(400, 'Transaksi tidak valid.');
        }

        $wallet = $tx->wallet;
        $wallet->balance += $tx->amount;
        $wallet->save();

        $tx->status      = 'approved';
        $tx->approved_by = Auth::id();
        $tx->save();

        return back()->with('success', 'Top up disetujui dan saldo member telah bertambah.');
    }

    // Reject top up → status jadi rejected, saldo tidak berubah
    public function reject($id)
    {
        $tx = WalletTransaction::findOrFail($id);

        if ($tx->type !== 'topup' || $tx->status !== 'pending') {
            abort(400, 'Transaksi tidak valid.');
        }

        $tx->status      = 'rejected';
        $tx->approved_by = Auth::id();
        $tx->save();

        return back()->with('success', 'Top up ditolak.');
    }
}
