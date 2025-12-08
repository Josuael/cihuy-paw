<?php

namespace App\Services;

class PaymentNumberService
{
    public static function generate()
    {
        return 'BA-' . date('YmdHis') . '-' . strtoupper(uniqid());
    }
}
