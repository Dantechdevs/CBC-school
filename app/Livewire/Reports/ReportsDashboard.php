<?php

namespace App\Livewire\Reports;

use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\FeeInvoice;
use App\Models\FeePayment;
use App\Models\Learner;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReportsDashboard extends Component
{
    public string $term;
    public string $academicYear;

    private const RUBRIC_SQL = "CASE assessments.rubric_level WHEN 'EE' THEN 4 WHEN 'ME' THEN 3 WHEN 'AE' THEN 2 WHEN 'BE' THEN 1 ELSE NULL END";

    public function mount(): void
    {
        $this->term         = (string) config('school.current_term');
        $this->academicYear = (string) config('school.academic_year');
    }

    public function render()
    {
        // NOTE: academic_year exists on assessments, school_classes AND learners,
        // so it must stay qualified here — once a join is added below (to either
        // table), an unqualified where('academic_year', ...) becomes ambiguous
        // and errors on both MySQL and SQLite.
        $baseAssessments = Assessment::where('assessments.term', $this->term)
            ->where('assessments.academic_year', $this->academicYear)
            ->whereNotNull('assessments.rubric_level');

        // School-wide rubric distribution
        $rubricCounts = (clone $baseAssessments)
            ->selectRaw('rubric_level, count(*) as total')
            ->groupBy('rubric_level')
            ->pluck('total', 'rubric_level');

        // Per-class average competency (numeric 1–4 scale)
        $classAverages = (clone $baseAssessments)
            ->join('school_classes', 'assessments.class_id', '=', 'school_classes.id')
            ->selectRaw('school_classes.id, school_classes.name, AVG('.self::RUBRIC_SQL.') as avg_score, COUNT(*) as total')
            ->groupBy('school_classes.id', 'school_classes.name')
            ->orderByDesc('avg_score')
            ->get();

        // At-risk learners: average rubric score below 'Approaches Expectation' (2.0)
        $atRiskLearners = (clone $baseAssessments)
            ->join('learners', 'assessments.learner_id', '=', 'learners.id')
            ->selectRaw('learners.id, learners.first_name, learners.last_name, learners.admission_number, AVG('.self::RUBRIC_SQL.') as avg_score, COUNT(*) as total')
            ->groupBy('learners.id', 'learners.first_name', 'learners.last_name', 'learners.admission_number')
            ->havingRaw('AVG('.self::RUBRIC_SQL.') < 2')
            ->orderBy('avg_score')
            ->limit(10)
            ->get();

        // Attendance rate across all recorded attendance (the attendance table has no term column)
        $attendanceTotal   = Attendance::count();
        $attendancePresent = Attendance::where('status', 'present')->count();
        $attendanceRate    = $attendanceTotal > 0 ? round(($attendancePresent / $attendanceTotal) * 100, 1) : 0;

        // Fee collection for the term
        $feesCollected = FeePayment::confirmed()
            ->whereHas('invoice', fn($q) => $q->where('term', $this->term)->where('academic_year', $this->academicYear))
            ->sum('amount');
        $feeArrears = FeeInvoice::where('term', $this->term)->where('academic_year', $this->academicYear)
            ->selectRaw('SUM(total_amount - amount_paid) as arrears')
            ->value('arrears') ?? 0;
        $collectionTotal = $feesCollected + $feeArrears;
        $collectionRate  = $collectionTotal > 0 ? round(($feesCollected / $collectionTotal) * 100, 1) : 0;

        return view('livewire.reports.reports-dashboard', [
            'rubricCounts'    => $rubricCounts,
            'classAverages'   => $classAverages,
            'atRiskLearners'  => $atRiskLearners,
            'attendanceRate'  => $attendanceRate,
            'feesCollected'   => $feesCollected,
            'feeArrears'      => $feeArrears,
            'collectionRate'  => $collectionRate,
            'totalLearners'   => Learner::active()->count(),
            'totalClasses'    => SchoolClass::active()->count(),
        ])->layout('layouts.admin', ['header' => 'Analytics & Reports']);
    }
}
