<?php

namespace App\Imports;

use App\Models\Learner;
use App\Models\SchoolClass;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LearnersImport implements ToCollection, WithHeadingRow
{
    public array $imported = [];
    public array $errors = [];
    public string $academicYear;

    public function __construct(?string $academicYear = null)
    {
        $this->academicYear = $academicYear ?? (string) config('school.academic_year');
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // account for header row

            $admissionNumber = trim((string) ($row['admission_number'] ?? ''));
            $firstName       = trim((string) ($row['first_name'] ?? ''));
            $lastName        = trim((string) ($row['last_name'] ?? ''));
            $gradeLevel      = trim((string) ($row['grade_level'] ?? ''));
            $gender          = strtolower(trim((string) ($row['gender'] ?? '')));

            if (! $admissionNumber || ! $firstName || ! $lastName || ! $gradeLevel) {
                $this->errors[] = "Row {$rowNumber}: missing required field (admission_number, first_name, last_name, or grade_level).";
                continue;
            }

            if (Learner::where('admission_number', $admissionNumber)->exists()) {
                $this->errors[] = "Row {$rowNumber}: admission number '{$admissionNumber}' already exists — skipped.";
                continue;
            }

            $validGrades = collect(config('school.grade_levels'))->flatten()->toArray();
            if (! in_array($gradeLevel, $validGrades)) {
                $this->errors[] = "Row {$rowNumber}: '{$gradeLevel}' is not a recognised grade level.";
                continue;
            }

            $className = trim((string) ($row['class_name'] ?? ''));
            $schoolClass = $className
                ? SchoolClass::where('name', $className)->orWhere(fn($q) => $q->where('grade_level', $gradeLevel)->where('stream', $className))->first()
                : SchoolClass::where('grade_level', $gradeLevel)->first();

            try {
                $learner = Learner::create([
                    'admission_number' => $admissionNumber,
                    'kemis_upi'        => trim((string) ($row['kemis_upi'] ?? '')) ?: null,
                    'first_name'       => $firstName,
                    'last_name'        => $lastName,
                    'gender'           => in_array($gender, ['male', 'female']) ? $gender : 'male',
                    'date_of_birth'    => $this->parseDate($row['date_of_birth'] ?? null),
                    'grade_level'      => $gradeLevel,
                    'class_id'         => $schoolClass?->id,
                    'boarding_status'  => trim((string) ($row['boarding_status'] ?? '')) ?: 'day',
                    'admission_date'   => now()->format('Y-m-d'),
                    'academic_year'    => $this->academicYear,
                    'is_active'        => true,
                ]);

                $this->imported[] = $learner;
            } catch (\Throwable $e) {
                $this->errors[] = "Row {$rowNumber}: could not save — {$e->getMessage()}";
            }
        }
    }

    private function parseDate($value): ?string
    {
        if (! $value) {
            return null;
        }
        try {
            return \Illuminate\Support\Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }
}
