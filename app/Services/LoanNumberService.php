<?php

namespace App\Services;

class LoanNumberService
{
    public static function generate($prefix)
    {
        $unique = strtoupper(uniqid());
        return $prefix . '-' . date('YmdHis') . '-' . $unique;
    }
}
