<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';
    protected $primaryKey = 'log_id';
    public $timestamps = false; // pakai kolom `timestamp` sendiri

    protected $fillable = [
        'user_id',
        'action_type',
        'table_name',
        'record_id',
        'old_values',
        'new_values',
        'ip_address',
        'timestamp',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'timestamp'  => 'datetime',
    ];

    public function user()
    {
        // primary key users kamu = user_id
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
