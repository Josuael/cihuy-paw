<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function view(User $user, Payment $payment)
    {
        // 1. Member yang melakukan payment → boleh
        if ($user->role === 'member' && $payment->loan->member->user_id === $user->user_id) {
            return true;
        }

        // 2. Staff pencatat pembayaran → boleh
        if ($user->role === 'staff' && $user->user_id === $payment->recorded_by) {
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
