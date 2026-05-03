<?php

namespace App\Livewire\Students;

use App\Models\Learner;
use App\Models\SchoolClass;
use Livewire\Component;
use Livewire\WithPagination;

class StudentList extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $gradeFilter  = '';
    public string $classFilter  = '';
    public string $statusFilter = 'active';
    public int    $perPage      = 25;

    protected $queryString = ['search', 'gradeFilter', 'classFilter'];

    public function updatingSearch(): void { $this->resetPage(); }

    public function render()
    {
        $learners = Learner::with(['schoolClass'])
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('first_name', 'like', "%{$this->search}%")
                  ->orWhere('last_name', 'like', "%{$this->search}%")
                  ->orWhere('admission_number', 'like', "%{$this->search}%")
                  ->orWhere('kemis_upi', 'like', "%{$this->search}%");
            }))
            ->when($this->gradeFilter, fn($q) => $q->where('grade_level', $this->gradeFilter))
            ->when($this->classFilter, fn($q) => $q->where('class_id', $this->classFilter))
            ->when($this->statusFilter === 'active', fn($q) => $q->where('is_active', true))
            ->when($this->statusFilter === 'inactive', fn($q) => $q->where('is_active', false))
            ->orderBy('last_name')
            ->paginate($this->perPage);

        return view('livewire.students.student-list', [
            'learners' => $learners,
            'classes'  => SchoolClass::orderBy('grade_level')->get(),
        ])->layout('layouts.admin');
    }
}
