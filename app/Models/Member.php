<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $primaryKey = 'member_id';

    protected $fillable = [
        'user_id','full_name','email','phone_number',
        'address','member_category','max_loan_limit','status'
    ];

    protected static function booted()
    {
        static::created(function ($member) {
            Wallet::create([
                'member_id' => $member->member_id,
                'balance' => 0
            ]);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'member_id', 'member_id');
    }

    public function applications()
    {
        return $this->hasMany(LoanApplication::class, 'member_id');
    }

    public function loans()
    {
        return $this->hasMany(Loan::class, 'member_id', 'member_id');
    }



}
