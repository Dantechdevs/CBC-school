<?php

namespace App\Livewire\Pathways;

use App\Models\Learner;
use App\Services\PathwayService;
use Livewire\Component;
use Livewire\WithPagination;

class PathwayTracker extends Component
{
    use WithPagination;

    public string $gradeFilter = '';
    public string $search = '';

    public function updatingSearch(): void { $this->resetPage(); }

    public function render()
    {
        $pathwayService = app(PathwayService::class);
        $term = (string) config('school.current_term');
        $year = (string) config('school.academic_year');

        $learners = Learner::where('is_active', true)
            ->whereIn('grade_level', ['Grade 7', 'Grade 8', 'Grade 9'])
            ->when($this->gradeFilter, fn($q) => $q->where('grade_level', $this->gradeFilter))
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('first_name', 'like', "%{$this->search}%")
                  ->orWhere('last_name', 'like', "%{$this->search}%")
                  ->orWhere('admission_number', 'like', "%{$this->search}%");
            }))
            ->orderBy('last_name')
            ->paginate(20);

        $rows = $learners->getCollection()->map(function ($learner) use ($pathwayService, $term, $year) {
            $pathways = $pathwayService->forLearner($learner->id, $term, $year);
            $recommended = $pathwayService->recommend($learner->id, $term, $year);
            return [
                'learner'     => $learner,
                'pathways'    => $pathways,
                'recommended' => $recommended,
            ];
        });

        // Cohort-level distribution for capacity planning, computed over all Grade 7-9 learners (not just this page)
        $allLearners = Learner::where('is_active', true)->whereIn('grade_level', ['Grade 7', 'Grade 8', 'Grade 9'])->get();
        $distribution = collect(array_keys(config('school.pathways', [])))->mapWithKeys(fn($p) => [$p => 0])->toArray();
        $unranked = 0;
        foreach ($allLearners as $learner) {
            $rec = $pathwayService->recommend($learner->id, $term, $year);
            if ($rec) {
                $distribution[$rec] = ($distribution[$rec] ?? 0) + 1;
            } else {
                $unranked++;
            }
        }

        return view('livewire.pathways.pathway-tracker', [
            'rows'         => $rows,
            'learners'     => $learners,
            'distribution' => $distribution,
            'unranked'     => $unranked,
            'pathwayNames' => array_keys(config('school.pathways', [])),
        ])->layout('layouts.admin', ['header' => 'Pathway Readiness']);
    }
}
