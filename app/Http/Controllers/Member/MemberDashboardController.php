<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class MemberDashboardController extends Controller
{   
    public function index()
    {
        $member = Auth::user()->member;

        if (!$member) {
            abort(404, 'Profil member tidak ditemukan.');
        }

        // pastikan member SELALU punya wallet
        $wallet = Wallet::firstOrCreate(
            ['member_id' => $member->member_id],
            ['balance' => 0]
        );

        // bisa pakai relasi kalau sudah didefinisikan
        $applications = $member->applications()->latest()->take(5)->get();
        $loans        = $member->loans()->latest()->take(5)->get();

        return view('dashboard.member', [
            'member'       => $member,
            'wallet'       => $wallet,
            'applications' => $applications,
            'loans'        => $loans,
        ]);
    }
}
