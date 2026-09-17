<?php

namespace App\Livewire\Timetable;

use App\Models\SchoolClass;
use App\Models\TimetableSlot;
use Livewire\Component;

class TimetableBoard extends Component
{
    public string $classFilter = '';
    public string $term;
    public string $academicYear;

    public array $days = ['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday'];

    public function mount(): void
    {
        $this->term         = (string) config('school.current_term');
        $this->academicYear = (string) config('school.academic_year');

        $firstClass = SchoolClass::active()->orderBy('grade_level')->first();
        $this->classFilter = $firstClass?->id ? (string) $firstClass->id : '';
    }

    protected function isAdminRoute(): bool
    {
        return str_starts_with(request()->route()?->getName() ?? '', 'admin.');
    }

    public function render()
    {
        $slots = collect();

        if ($this->classFilter) {
            $slots = TimetableSlot::with(['learningArea', 'teacher'])
                ->where('class_id', $this->classFilter)
                ->where('term', $this->term)
                ->where('academic_year', $this->academicYear)
                ->where('is_active', true)
                ->orderBy('start_time')
                ->get()
                ->groupBy('day_of_week');
        }

        $layout = $this->isAdminRoute() ? 'layouts.admin' : 'layouts.teacher';

        return view('livewire.timetable.timetable-board', [
            'slots'   => $slots,
            'classes' => SchoolClass::active()->orderBy('grade_level')->orderBy('stream')->get(),
        ])->layout($layout, ['header' => 'Timetable']);
    }
}
