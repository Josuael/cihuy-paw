<?php

namespace App\Policies;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Response;

class LoanPolicy
{
    // siapa saja boleh melihat loan ini?
    public function view(User $user, Loan $loan)
    {
        // 1. Member pemilik loan → boleh
        if ($user->role === 'member' && $user->user_id === $loan->member->user_id) {
            return true;
        }

        // 2. Staff → boleh akses untuk verifikasi & angsuran
        if ($user->role === 'staff') {
            return true;
        }

        // 3. Admin → boleh
        if ($user->role === 'admin') {
            return true;
        }

        // 4. Ketua → boleh
        if ($user->role === 'ketua') {
            return true;
        }

        return false;
    }
}
