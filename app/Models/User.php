<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username','email','password','full_name','role','is_active'
    ];

    public function member()
    {
        return $this->hasOne(Member::class, 'user_id', 'user_id');
    }
}


