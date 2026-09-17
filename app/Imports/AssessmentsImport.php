<?php

namespace App\Imports;

use App\Enums\RubricLevel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AssessmentsImport implements ToCollection, WithHeadingRow
{
    public array $matched = [];   // ['ADM001' => ['rubric_level' => 'EE', 'remarks' => '...']]
    public array $errors = [];

    public function collection(Collection $rows): void
    {
        $validLevels = array_map(fn($case) => $case->value, RubricLevel::cases());

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;
            $admissionNumber = trim((string) ($row['admission_number'] ?? ''));
            $rubricLevel = strtoupper(trim((string) ($row['rubric_level'] ?? '')));
            $remarks = trim((string) ($row['remarks'] ?? ''));

            if (! $admissionNumber) {
                $this->errors[] = "Row {$rowNumber}: missing admission_number.";
                continue;
            }

            if (! in_array($rubricLevel, $validLevels)) {
                $this->errors[] = "Row {$rowNumber}: '{$rubricLevel}' is not a valid rubric level (expected one of: ".implode(', ', $validLevels).").";
                continue;
            }

            $this->matched[$admissionNumber] = [
                'rubric_level' => $rubricLevel,
                'remarks'      => $remarks ?: null,
            ];
        }
    }
}
