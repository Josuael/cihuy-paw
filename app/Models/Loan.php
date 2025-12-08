<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $primaryKey = 'loan_id';

    protected $fillable = [
        'loan_number','application_id','member_id',
        'disbursement_date','principal_amount',
        'interest_rate','interest_type','duration_months',
        'monthly_installment','total_interest','total_amount',
        'remaining_balance','status'
    ];

    public function application()
    {
        return $this->belongsTo(LoanApplication::class, 'application_id');
    }

    public function schedules()
    {
        return $this->hasMany(InstallmentSchedule::class, 'loan_id', 'loan_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'loan_id');
    }

    public function member()
    {
        return $this->belongsTo(\App\Models\Member::class, 'member_id', 'member_id');
    }

}
