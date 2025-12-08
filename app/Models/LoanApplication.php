<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanApplication extends Model
{
    protected $table = 'loan_applications';
    protected $primaryKey = 'application_id';

    protected $fillable = [
        'application_number',
        'member_id',
        'application_date',
        'request_amount',
        'loan_purpose',
        'duration_months',
        'status',
        'verified_by',
        'verified_at',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'application_date' => 'datetime',
        'verified_at'      => 'datetime',
        'approved_at'      => 'datetime',
    ];


    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function documents()
    {
        return $this->hasMany(SupportingDocument::class, 'application_id', 'application_id');
    }
}
