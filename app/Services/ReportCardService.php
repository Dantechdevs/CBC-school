<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\Learner;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ReportCardService
{
    public function generate(int $learnerId, string $term, string $academicYear): string
    {
        $learner = Learner::with(['schoolClass', 'guardians'])->findOrFail($learnerId);

        $assessments = $this->buildAssessmentSummary($learnerId, $term, $academicYear);
        $attendance  = $this->buildAttendanceSummary($learnerId, $term, $academicYear);

        $pdf = Pdf::loadView('pdf.report-card', [
            'learner'             => $learner,
            'term'                => "Term {$term}",
            'academicYear'        => $academicYear,
            'assessments'         => $assessments,
            'attendance'          => $attendance,
            'classTeacherRemark'  => '',
        ])->setPaper('a4', 'portrait');

        $fileName = "reports/{$academicYear}/term{$term}/{$learner->admission_number}_report.pdf";
        Storage::put("public/{$fileName}", $pdf->output());

        return $fileName;
    }

    private function buildAssessmentSummary(int $learnerId, string $term, string $academicYear): array
    {
        $assessments = Assessment::with(['learningArea', 'strand'])
            ->where('learner_id', $learnerId)
            ->where('term', $term)
            ->where('academic_year', $academicYear)
            ->get();

        $summary = [];

        foreach ($assessments as $a) {
            $areaName = $a->learningArea->name ?? 'Unknown';
            if (!isset($summary[$areaName])) {
                $summary[$areaName] = [
                    'strand'    => $a->strand->name ?? null,
                    'formative' => null,
                    'summative' => null,
                    'overall'   => null,
                    'remarks'   => $a->remarks,
                ];
            }
            if ($a->assessment_type === 'formative') {
                $summary[$areaName]['formative'] = $a->rubric_level;
            } else {
                $summary[$areaName]['summative'] = $a->rubric_level;
            }

            // Compute overall — summative takes precedence, else formative
            $f = $summary[$areaName]['formative'];
            $s = $summary[$areaName]['summative'];
            $summary[$areaName]['overall'] = $s ?? $f;
        }

        return $summary;
    }

    private function buildAttendanceSummary(int $learnerId, string $term, string $academicYear): array
    {
        $records = Attendance::where('learner_id', $learnerId)
            ->whereYear('date', substr($academicYear, 0, 4))
            ->get();

        $present = $records->where('status', 'present')->count();
        $absent  = $records->where('status', 'absent')->count();
        $late    = $records->where('status', 'late')->count();
        $total   = $records->count();

        return [
            'present'    => $present,
            'absent'     => $absent,
            'late'       => $late,
            'total'      => $total,
            'percentage' => $total > 0 ? round(($present / $total) * 100, 1) : 0,
        ];
    }
}
