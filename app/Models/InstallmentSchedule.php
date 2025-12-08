<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentSchedule extends Model
{
    protected $primaryKey = 'schedule_id';

    protected $fillable = [
        'loan_id','installment_number','due_date',
        'principal_amount','interest_amount',
        'total_amount','status','paid_date'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }
}
