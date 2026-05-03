<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending   = 'pending';
    case Completed = 'completed';
    case Failed    = 'failed';
    case Reversed  = 'reversed';
}
