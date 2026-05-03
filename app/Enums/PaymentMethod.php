<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Mpesa      = 'mpesa';
    case Bank       = 'bank';
    case Cash       = 'cash';
    case Cheque     = 'cheque';
    case Bursary    = 'bursary';

    public function label(): string
    {
        return match($this) {
            self::Mpesa   => 'M-Pesa',
            self::Bank    => 'Bank Transfer',
            self::Cash    => 'Cash',
            self::Cheque  => 'Cheque',
            self::Bursary => 'Bursary/Scholarship',
        };
    }
}
