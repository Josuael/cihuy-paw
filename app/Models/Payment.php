<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $primaryKey = 'payment_id';
    protected $casts = [
        'payment_date' => 'datetime',
    ];

    protected $fillable = [
        'payment_number','loan_id','schedule_id',
        'amount_paid','principal_paid','interest_paid',
        'payment_method','recorded_by'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id', 'loan_id');
    }

    public function schedule()
    {
        return $this->belongsTo(InstallmentSchedule::class, 'schedule_id');
    }
}
