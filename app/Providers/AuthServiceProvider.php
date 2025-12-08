<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

// IMPORT POLICY & MODEL
use App\Models\Loan;
use App\Models\Payment;
use App\Policies\LoanPolicy;
use App\Policies\PaymentPolicy;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        Loan::class => LoanPolicy::class,
        Payment::class => PaymentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
    //     Gate::guessPolicyNamesUsing(function ($modelClass) {
    //     return '\\App\\Policies\\' . class_basename($modelClass) . 'Policy';
    // });

    }
}
