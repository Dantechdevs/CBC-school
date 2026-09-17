<?php

namespace App\Livewire\Homework;

use App\Enums\RubricLevel;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\LearningArea;
use App\Models\Learner;
use App\Models\SchoolClass;
use App\Models\SubStrand;
use Livewire\Component;

class HomeworkManager extends Component
{
    // View state
    public string $view = 'list'; // list | grade
    public ?int $gradingHomeworkId = null;

    // Create form
    public bool $showCreateModal = false;
    public ?int $classId = null;
    public ?int $learningAreaId = null;
    public ?int $subStrandId = null;
    public string $title = '';
    public string $description = '';
    public string $dueDate = '';
    public string $dueTime = '15:00';
    public bool $allowLate = false;

    // Grading (keyed by learner_id)
    public array $grades = [];

    public string $flashMessage = '';

    public function mount(): void
    {
        $this->dueDate = now()->addDays(3)->format('Y-m-d');
    }

    protected function isAdminRoute(): bool
    {
        return str_starts_with(request()->route()?->getName() ?? '', 'admin.');
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeCreate(): void
    {
        $this->showCreateModal = false;
    }

    protected function resetForm(): void
    {
        $this->classId = null;
        $this->learningAreaId = null;
        $this->subStrandId = null;
        $this->title = '';
        $this->description = '';
        $this->dueDate = now()->addDays(3)->format('Y-m-d');
        $this->dueTime = '15:00';
        $this->allowLate = false;
    }

    public function save(): void
    {
        $this->validate([
            'classId'        => 'required|exists:school_classes,id',
            'learningAreaId' => 'required|exists:learning_areas,id',
            'title'          => 'required|string|max:255',
            'dueDate'        => 'required|date',
            'dueTime'        => 'required',
        ]);

        Homework::create([
            'class_id'         => $this->classId,
            'learning_area_id' => $this->learningAreaId,
            'sub_strand_id'    => $this->subStrandId,
            'teacher_id'       => auth()->user()->staffMember?->id,
            'title'            => $this->title,
            'description'      => $this->description,
            'academic_year'    => config('school.academic_year'),
            'term'             => config('school.current_term'),
            'due_at'           => $this->dueDate.' '.$this->dueTime,
            'allow_late'       => $this->allowLate,
        ]);

        $this->flashMessage = "Homework \"{$this->title}\" assigned successfully.";
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openGrading(int $homeworkId): void
    {
        $this->gradingHomeworkId = $homeworkId;
        $this->view = 'grade';

        $homework = Homework::with('submissions')->findOrFail($homeworkId);
        $this->grades = [];

        foreach (Learner::where('class_id', $homework->class_id)->where('is_active', true)->get() as $learner) {
            $submission = $homework->submissions->firstWhere('learner_id', $learner->id);
            $this->grades[$learner->id] = [
                'score'        => $submission?->score,
                'rubric_level' => $submission?->rubric_level?->value,
                'feedback'     => $submission?->feedback,
                'status'       => $submission?->status ?? 'pending',
            ];
        }
    }

    public function backToList(): void
    {
        $this->view = 'list';
        $this->gradingHomeworkId = null;
    }

    public function saveGrades(): void
    {
        $homework = Homework::findOrFail($this->gradingHomeworkId);

        foreach ($this->grades as $learnerId => $data) {
            if (blank($data['score']) && blank($data['rubric_level']) && blank($data['feedback'])) {
                continue;
            }

            HomeworkSubmission::updateOrCreate(
                ['homework_id' => $homework->id, 'learner_id' => $learnerId],
                [
                    'score'        => $data['score'] !== '' ? $data['score'] : null,
                    'max_score'    => 100,
                    'rubric_level' => $data['rubric_level'] ?: null,
                    'feedback'     => $data['feedback'] ?: null,
                    'status'       => 'graded',
                    'graded_by'    => auth()->user()->staffMember?->id,
                    'graded_at'    => now(),
                ]
            );
        }

        $this->flashMessage = 'Grades saved successfully.';
        $this->backToList();
    }

    public function render()
    {
        $homework = Homework::with(['schoolClass', 'learningArea', 'teacher'])
            ->withCount('submissions')
            ->forTerm(config('school.current_term'), config('school.academic_year'))
            ->latest('due_at')
            ->get();

        $gradingHomework = $this->gradingHomeworkId ? Homework::with('schoolClass')->find($this->gradingHomeworkId) : null;
        $gradingLearners = $gradingHomework
            ? Learner::where('class_id', $gradingHomework->class_id)->where('is_active', true)->orderBy('last_name')->get()
            : collect();

        $layout = $this->isAdminRoute() ? 'layouts.admin' : 'layouts.teacher';

        return view('livewire.homework.homework-manager', [
            'homework'        => $homework,
            'classes'         => SchoolClass::active()->orderBy('grade_level')->get(),
            'learningAreas'   => LearningArea::where('is_active', true)->orderBy('name')->get(),
            'subStrands'      => $this->learningAreaId
                ? SubStrand::whereHas('strand', fn($q) => $q->where('learning_area_id', $this->learningAreaId))->get()
                : collect(),
            'rubricLevels'    => RubricLevel::cases(),
            'gradingHomework' => $gradingHomework,
            'gradingLearners' => $gradingLearners,
        ])->layout($layout, ['header' => 'Homework']);
    }
}
