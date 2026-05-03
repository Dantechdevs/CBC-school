<?php

namespace App\Enums;

enum GradeLevel: string
{
    case PP1     = 'PP1';
    case PP2     = 'PP2';
    case Grade1  = 'Grade 1';
    case Grade2  = 'Grade 2';
    case Grade3  = 'Grade 3';
    case Grade4  = 'Grade 4';
    case Grade5  = 'Grade 5';
    case Grade6  = 'Grade 6';
    case Grade7  = 'Grade 7';
    case Grade8  = 'Grade 8';
    case Grade9  = 'Grade 9';
    case Grade10 = 'Grade 10';
    case Grade11 = 'Grade 11';
    case Grade12 = 'Grade 12';

    public function cycleLevel(): string
    {
        return match(true) {
            in_array($this, [self::PP1, self::PP2])                       => 'Pre-Primary',
            in_array($this, [self::Grade1, self::Grade2, self::Grade3])   => 'Lower Primary',
            in_array($this, [self::Grade4, self::Grade5, self::Grade6])   => 'Upper Primary',
            in_array($this, [self::Grade7, self::Grade8, self::Grade9])   => 'Junior Secondary',
            default                                                        => 'Senior Secondary',
        };
    }

    public function usesRubric(): bool
    {
        return in_array($this, [
            self::PP1, self::PP2,
            self::Grade1, self::Grade2, self::Grade3,
            self::Grade4, self::Grade5, self::Grade6,
        ]);
    }

    public function sortOrder(): int
    {
        return match($this) {
            self::PP1     => 1,  self::PP2     => 2,
            self::Grade1  => 3,  self::Grade2  => 4,  self::Grade3  => 5,
            self::Grade4  => 6,  self::Grade5  => 7,  self::Grade6  => 8,
            self::Grade7  => 9,  self::Grade8  => 10, self::Grade9  => 11,
            self::Grade10 => 12, self::Grade11 => 13, self::Grade12 => 14,
        };
    }
}
