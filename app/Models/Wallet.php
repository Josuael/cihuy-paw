<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $primaryKey = 'wallet_id';

    protected $fillable = [
        'member_id', 'balance'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class, 'wallet_id', 'wallet_id');
    }
}
