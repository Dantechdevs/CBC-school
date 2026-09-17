<?php

namespace App\Livewire\Attendance;

use App\Models\Attendance;
use App\Models\Learner;
use App\Models\SchoolClass;
use Livewire\Component;

class AttendanceMarker extends Component
{
    public string $classId = '';
    public string $date;
    public string $session = 'full_day';
    public array $records = [];
    public string $flashMessage = '';

    public function mount(): void
    {
        $this->date = now()->format('Y-m-d');
        $firstClass = SchoolClass::active()->orderBy('grade_level')->first();
        $this->classId = $firstClass?->id ? (string) $firstClass->id : '';
        $this->loadRecords();
    }

    public function updatedClassId(): void { $this->loadRecords(); }
    public function updatedDate(): void { $this->loadRecords(); }
    public function updatedSession(): void { $this->loadRecords(); }

    protected function loadRecords(): void
    {
        $this->records = [];

        if (! $this->classId) {
            return;
        }

        $existing = Attendance::where('class_id', $this->classId)
            ->where('date', $this->date)
            ->where('session', $this->session)
            ->get()
            ->keyBy('learner_id');

        foreach (Learner::where('class_id', $this->classId)->where('is_active', true)->orderBy('last_name')->get() as $learner) {
            $this->records[$learner->id] = $existing[$learner->id]->status ?? 'present';
        }
    }

    public function markAll(string $status): void
    {
        foreach ($this->records as $learnerId => $current) {
            $this->records[$learnerId] = $status;
        }
    }

    public function save(): void
    {
        $staffId = auth()->user()->staffMember?->id;

        if (! $staffId) {
            $this->flashMessage = 'Your account has no linked staff record, so attendance cannot be attributed to you.';
            return;
        }

        foreach ($this->records as $learnerId => $status) {
            Attendance::updateOrCreate(
                ['learner_id' => $learnerId, 'date' => $this->date, 'session' => $this->session],
                [
                    'class_id'    => $this->classId,
                    'status'      => $status,
                    'recorded_by' => $staffId,
                ]
            );
        }

        $this->flashMessage = 'Attendance saved for '.count($this->records).' learner(s).';
    }

    protected function isAdminRoute(): bool
    {
        return str_starts_with(request()->route()?->getName() ?? '', 'admin.');
    }

    public function render()
    {
        $learners = $this->classId
            ? Learner::where('class_id', $this->classId)->where('is_active', true)->orderBy('last_name')->get()
            : collect();

        $layout = $this->isAdminRoute() ? 'layouts.admin' : 'layouts.teacher';

        return view('livewire.attendance.attendance-marker', [
            'learners' => $learners,
            'classes'  => SchoolClass::active()->orderBy('grade_level')->get(),
        ])->layout($layout, ['header' => 'Attendance']);
    }
}
