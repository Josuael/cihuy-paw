<?php

namespace App\Services;

class ApplicationNumberService
{
    public static function generate()
    {
        return 'FPP-' . date('YmdHis') . '-' . strtoupper(uniqid());
    }
}
