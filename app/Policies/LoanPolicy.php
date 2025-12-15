<?php

namespace App\Policies;

use App\Models\Loan;
use App\Models\User;

class LoanPolicy
{
    /**
     * Siapa saja yang boleh melihat BP untuk suatu loan.
     */
    public function view(User $user, Loan $loan): bool
    {
        if (in_array($user->role, ['admin', 'staff', 'ketua'])) {
            return true;
        }

        if ($user->role === 'member') {
            $member = $loan->member; 
            return $member && $member->user_id === $user->user_id;
        }

        // role lain (kalau ada) ditolak
        return false;
    }
}
