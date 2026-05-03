<?php

namespace App\Enums;

enum TermEnum: string
{
    case Term1 = 'Term 1';
    case Term2 = 'Term 2';
    case Term3 = 'Term 3';

    public function shortName(): string
    {
        return match($this) {
            self::Term1 => 'T1',
            self::Term2 => 'T2',
            self::Term3 => 'T3',
        };
    }
}
