<?php

namespace App\Livewire\Fees;

use App\Models\FeeInvoice;
use App\Models\FeePayment;
use App\Models\SchoolClass;
use Livewire\Component;

class FinanceReports extends Component
{
    public function render()
    {
        $term = config('school.current_term');
        $year = config('school.academic_year');

        // Collection trend over the last 6 months
        $monthlyTrend = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = now()->subMonthsNoOverflow($monthsAgo);
            $amount = FeePayment::confirmed()
                ->whereMonth('paid_at', $date->month)
                ->whereYear('paid_at', $date->year)
                ->sum('amount');
            return ['label' => $date->format('M Y'), 'amount' => (float) $amount];
        });

        // Arrears by class, for the current term
        $arrearsByClass = SchoolClass::active()
            ->with(['learners' => fn($q) => $q->where('is_active', true)])
            ->get()
            ->map(function ($class) use ($term, $year) {
                $learnerIds = $class->learners->pluck('id');
                $arrears = FeeInvoice::whereIn('learner_id', $learnerIds)
                    ->where('term', $term)->where('academic_year', $year)
                    ->selectRaw('SUM(total_amount - amount_paid) as arrears')->value('arrears') ?? 0;
                return ['class' => $class->name, 'arrears' => (float) $arrears];
            })
            ->filter(fn($row) => $row['arrears'] > 0)
            ->sortByDesc('arrears')
            ->values();

        // Payment method breakdown, this term
        $methodBreakdown = FeePayment::confirmed()
            ->whereHas('invoice', fn($q) => $q->where('term', $term)->where('academic_year', $year))
            ->selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get();

        $totalCollected = $methodBreakdown->sum('total');

        return view('livewire.fees.finance-reports', [
            'monthlyTrend'     => $monthlyTrend,
            'arrearsByClass'   => $arrearsByClass,
            'methodBreakdown'  => $methodBreakdown,
            'totalCollected'   => $totalCollected,
        ])->layout('layouts.finance', ['header' => 'Finance Reports']);
    }
}
