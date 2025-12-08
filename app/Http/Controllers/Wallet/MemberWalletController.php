<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditLogService;

class MemberWalletController extends Controller
{
    public function index()
    {
        $member = Auth::user()->member;

        if (!$member) {
            abort(404, 'Profil member tidak ditemukan.');
        }

        // pastikan selalu punya wallet
        $wallet = Wallet::firstOrCreate(
            ['member_id' => $member->member_id],
            ['balance' => 0]
        );

        $transactions = $wallet->transactions()
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('wallet.member.index', compact('wallet', 'transactions'));
    }

    public function requestTopup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
        ]);

        $member = Auth::user()->member;

        if (!$member) {
            abort(404, 'Profil member tidak ditemukan.');
        }

        $wallet = Wallet::firstOrCreate(
            ['member_id' => $member->member_id],
            ['balance' => 0]
        );

        

        $trx = WalletTransaction::create([
            'wallet_id'   => $wallet->wallet_id,   // atau $wallet->id, sesuaikan
            'amount'      => $request->amount,
            'type'        => 'topup',
            'status'      => 'pending',
            'created_by'  => Auth::id(),
            'approved_by' => null,
        ]);

        AuditLogService::log(
            'Create',
            'wallet_transactions',
            $trx->transaction_id,
            [],
            $trx->toArray()
        );

        return redirect()->route('wallet.index')
            ->with('success', 'Permintaan top up dikirim ke petugas.');
    }
}
