<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'wallet_id',
        'amount',
        'type',
        'status',
        'created_by',
        'approved_by'
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'wallet_id');
    }
}
