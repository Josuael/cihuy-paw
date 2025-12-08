<?php

namespace App\Services;

class InterestService
{
    public static function calculateFlat($principal, $rate, $months)
    {
        $monthlyInterest = $principal * ($rate / 100);
        $totalInterest = $monthlyInterest * $months;

        return [
            'monthly' => ($principal + $totalInterest) / $months,
            'total_interest' => $totalInterest,
            'total_amount' => $principal + $totalInterest
        ];
    }
}
