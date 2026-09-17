<?php

namespace App\Livewire\Students;

use App\Models\Learner;
use App\Models\SchoolClass;
use App\Services\ReportCardService;
use Livewire\Component;
use Livewire\WithPagination;

class StudentList extends Component
{
    use WithPagination;

    public string $search         = '';
    public string $gradeFilter    = '';
    public string $classFilter    = '';
    public string $statusFilter   = '1';
    public string $boardingFilter = '';
    public int    $perPage        = 25;

    protected $queryString = ['search', 'gradeFilter', 'classFilter'];

    // Modal state
    public bool $showFormModal = false;
    public bool $showViewModal = false;
    public bool $isEditing = false;
    public ?int $editingId = null;
    public ?Learner $viewingLearner = null;
    public string $flashMessage = '';

    // Form fields
    public string $admissionNumber = '';
    public string $kemisUpi = '';
    public string $firstName = '';
    public string $lastName = '';
    public string $gender = 'male';
    public string $dateOfBirth = '';
    public string $gradeLevel = '';
    public ?int $classId = null;
    public string $boardingStatus = 'day';

    public function updatingSearch(): void { $this->resetPage(); }

    protected function rules(): array
    {
        return [
            'admissionNumber' => 'required|string|max:50|unique:learners,admission_number,'.($this->editingId ?? 'NULL').',id',
            'kemisUpi'        => 'nullable|string|max:50',
            'firstName'       => 'required|string|max:100',
            'lastName'        => 'required|string|max:100',
            'gender'          => 'required|in:male,female',
            'dateOfBirth'     => 'required|date|before:today',
            'gradeLevel'      => 'required|string',
            'classId'         => 'nullable|exists:school_classes,id',
            'boardingStatus'  => 'required|in:day,boarding',
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showFormModal = true;
    }

    public function edit(int $id): void
    {
        $learner = Learner::findOrFail($id);
        $this->editingId       = $learner->id;
        $this->admissionNumber = $learner->admission_number;
        $this->kemisUpi        = $learner->kemis_upi ?? '';
        $this->firstName       = $learner->first_name;
        $this->lastName        = $learner->last_name;
        $this->gender          = $learner->gender;
        $this->dateOfBirth     = $learner->date_of_birth?->format('Y-m-d') ?? '';
        $this->gradeLevel      = $learner->grade_level?->value ?? '';
        $this->classId         = $learner->class_id;
        $this->boardingStatus  = $learner->boarding_status ?? 'day';
        $this->isEditing       = true;
        $this->showFormModal   = true;
    }

    public function view(int $id): void
    {
        $this->viewingLearner = Learner::with(['schoolClass', 'guardians'])->findOrFail($id);
        $this->showViewModal = true;
    }

    public function closeModals(): void
    {
        $this->showFormModal = false;
        $this->showViewModal = false;
        $this->viewingLearner = null;
    }

    protected function resetForm(): void
    {
        $this->editingId = null;
        $this->admissionNumber = '';
        $this->kemisUpi = '';
        $this->firstName = '';
        $this->lastName = '';
        $this->gender = 'male';
        $this->dateOfBirth = '';
        $this->gradeLevel = '';
        $this->classId = null;
        $this->boardingStatus = 'day';
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'admission_number' => $this->admissionNumber,
            'kemis_upi'        => $this->kemisUpi ?: null,
            'first_name'       => $this->firstName,
            'last_name'        => $this->lastName,
            'gender'           => $this->gender,
            'date_of_birth'    => $this->dateOfBirth,
            'grade_level'      => $this->gradeLevel,
            'class_id'         => $this->classId,
            'boarding_status'  => $this->boardingStatus,
        ];

        if ($this->isEditing && $this->editingId) {
            Learner::findOrFail($this->editingId)->update($data);
            $this->flashMessage = "{$this->firstName} {$this->lastName} updated successfully.";
        } else {
            Learner::create($data + [
                'admission_date' => now()->format('Y-m-d'),
                'academic_year'  => config('school.academic_year'),
                'is_active'      => true,
            ]);
            $this->flashMessage = "{$this->firstName} {$this->lastName} enrolled successfully.";
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function generateReport(int $id)
    {
        $service = app(ReportCardService::class);
        $path = $service->generate($id, (string) config('school.current_term'), (string) config('school.academic_year'));

        return response()->redirectTo(\Illuminate\Support\Facades\Storage::url($path));
    }

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
            ->when($this->statusFilter === '1', fn($q) => $q->where('is_active', true))
            ->when($this->statusFilter === '0', fn($q) => $q->where('is_active', false))
            ->when($this->boardingFilter, fn($q) => $q->where('boarding_status', $this->boardingFilter))
            ->orderBy('last_name')
            ->paginate($this->perPage);

        return view('livewire.students.student-list', [
            'learners'    => $learners,
            'classes'     => SchoolClass::active()->orderBy('grade_level')->get(),
            'totalCount'  => Learner::where('is_active', true)->count(),
        ])->layout('layouts.admin', ['header' => 'Learners']);
    }
}
