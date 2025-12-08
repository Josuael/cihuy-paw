<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportingDocument extends Model
{
    protected $primaryKey = 'doc_id';

    protected $fillable = [
        'application_id','doc_type','file_name',
        'file_path','file_size'
    ];

    public function application()
    {
        return $this->belongsTo(LoanApplication::class, 'application_id');
    }
}
