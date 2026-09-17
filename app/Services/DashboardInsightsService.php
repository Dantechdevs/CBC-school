<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\FeeInvoice;
use App\Models\FeePayment;
use App\Models\HomeworkSubmission;
use App\Models\Learner;
use App\Models\SchoolClass;
use Illuminate\Support\Carbon;

class DashboardInsightsService
{
    public function generate(): array
    {
        $insights = [];
        $term = (string) config('school.current_term');
        $year = (string) config('school.academic_year');

        $totalLearners = Learner::active()->count();

        if ($totalLearners > 0) {
            // Pending fee balances
            $withBalance = FeeInvoice::where('term', $term)->where('academic_year', $year)
                ->whereColumn('amount_paid', '<', 'total_amount')
                ->pluck('learner_id')->unique()->count();
            if ($withBalance > 0) {
                $insights[] = ['icon' => 'warning', 'text' => "{$withBalance} of {$totalLearners} learner(s) have pending fee balances."];
            }

            // Gender split
            $boys = Learner::active()->where('gender', 'male')->count();
            $girls = Learner::active()->where('gender', 'female')->count();
            if ($boys + $girls > 0) {
                $girlsPct = round(($girls / ($boys + $girls)) * 100);
                $insights[] = ['icon' => 'people', 'text' => "{$boys} boys and {$girls} girls enrolled ({$girlsPct}% girls)."];
            }

            // New admissions in the last 30 days
            $newAdmissions = Learner::where('admission_date', '>=', now()->subDays(30))->count();
            if ($newAdmissions > 0) {
                $insights[] = ['icon' => 'new', 'text' => "{$newAdmissions} new learner(s) admitted in the last 30 days."];
            }

            // Learners with no assessment this term
            $assessedLearnerIds = Assessment::where('term', $term)->where('academic_year', $year)->distinct()->pluck('learner_id');
            $unassessed = $totalLearners - Learner::active()->whereIn('id', $assessedLearnerIds)->count();
            if ($unassessed > 0) {
                $insights[] = ['icon' => 'assessment', 'text' => "{$unassessed} learner(s) have no assessment recorded yet this term."];
            }

            // Attendance marked today?
            $activeClasses = SchoolClass::active()->count();
            $classesMarkedToday = Attendance::whereDate('date', now())->pluck('class_id')->unique()->count();
            if ($activeClasses > 0 && $classesMarkedToday < $activeClasses) {
                $remaining = $activeClasses - $classesMarkedToday;
                $insights[] = ['icon' => 'warning', 'text' => "Attendance has not been marked yet for {$remaining} of {$activeClasses} class(es) today."];
            }
        }

        // Revenue trend: this calendar month vs last
        $thisMonthRevenue = FeePayment::confirmed()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount');
        $lastMonth = now()->subMonthNoOverflow();
        $lastMonthRevenue = FeePayment::confirmed()->whereMonth('created_at', $lastMonth->month)->whereYear('created_at', $lastMonth->year)->sum('amount');
        if ($lastMonthRevenue > 0) {
            $change = round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100);
            if (abs($change) >= 5) {
                $direction = $change < 0 ? 'down' : 'up';
                $icon = $change < 0 ? 'trend_down' : 'trend_up';
                $insights[] = ['icon' => $icon, 'text' => "Revenue in ".now()->format('M Y')." is {$direction} ".abs($change)."% vs ".$lastMonth->format('M Y').'.'];
            }
        }

        // Homework awaiting grading
        $awaitingGrading = HomeworkSubmission::whereIn('status', ['submitted', 'late'])->count();
        if ($awaitingGrading > 0) {
            $insights[] = ['icon' => 'homework', 'text' => "{$awaitingGrading} homework submission(s) are awaiting grading."];
        }

        // Next term
        if ($nextTerm = config('school.next_term_start')) {
            $insights[] = ['icon' => 'calendar', 'text' => 'Next term begins on '.Carbon::parse($nextTerm)->format('d M Y').'.'];
        }

        return $insights;
    }
}
