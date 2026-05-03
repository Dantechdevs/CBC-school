<?php

namespace App\Enums;

enum RubricLevel: string
{
    case ExceedsExpectation    = 'EE';
    case MeetsExpectation      = 'ME';
    case ApproachesExpectation = 'AE';
    case BelowExpectation      = 'BE';

    public function label(): string
    {
        return match($this) {
            self::ExceedsExpectation    => 'Exceeds Expectation',
            self::MeetsExpectation      => 'Meets Expectation',
            self::ApproachesExpectation => 'Approaches Expectation',
            self::BelowExpectation      => 'Below Expectation',
        };
    }

    public function numericValue(): int
    {
        return match($this) {
            self::ExceedsExpectation    => 4,
            self::MeetsExpectation      => 3,
            self::ApproachesExpectation => 2,
            self::BelowExpectation      => 1,
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ExceedsExpectation    => 'green',
            self::MeetsExpectation      => 'blue',
            self::ApproachesExpectation => 'yellow',
            self::BelowExpectation      => 'red',
        };
    }

    public static function fromNumeric(int $value): self
    {
        return match($value) {
            4       => self::ExceedsExpectation,
            3       => self::MeetsExpectation,
            2       => self::ApproachesExpectation,
            default => self::BelowExpectation,
        };
    }
}
