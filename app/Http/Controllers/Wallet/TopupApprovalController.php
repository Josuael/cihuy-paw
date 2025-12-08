<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditLogService;


class TopupApprovalController extends Controller
{
    public function index()
    {
        // Hanya lihat top-up pending
        $pending = WalletTransaction::where('type', 'topup')
                    ->where('status', 'pending')
                    ->get();

        return view('wallet.staff.index', compact('pending'));
    }

    public function approve($transaction_id)
    {
        $trx = WalletTransaction::findOrFail($transaction_id);
        $old = $trx->toArray();

        $wallet = $trx->wallet;

        // Tambah saldo member
        $wallet->balance += $trx->amount;
        $wallet->save();

        // Update transaksi
        $trx->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        AuditLogService::log(
            'Approve',
            'wallet_transactions',
            $trx->transaction_id,
            $old,
            $trx->toArray()
        );

        return back()->with('success', 'Top-up berhasil disetujui.');
    }

    public function reject($transaction_id)
    {
        $trx = WalletTransaction::findOrFail($transaction_id);
        $old = $trx->toArray();

        $trx->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
        ]);

        AuditLogService::log(
            'Reject',
            'wallet_transactions',
            $trx->transaction_id,
            $old,
            $trx->toArray()
        );

        return back()->with('success', 'Top-up ditolak.');
    }
}
