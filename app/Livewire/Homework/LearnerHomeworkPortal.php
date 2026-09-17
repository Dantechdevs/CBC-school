<?php

namespace App\Livewire\Homework;

use App\Models\Homework;
use App\Models\HomeworkSubmission;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class LearnerHomeworkPortal extends Component
{
    use WithFileUploads;

    public ?int $submittingHomeworkId = null;
    public string $submissionText = '';
    public $uploadedFile = null;

    protected $rules = [
        'submissionText' => 'nullable|string|max:5000',
        'uploadedFile'   => 'nullable|file|max:20480',
    ];

    protected function learner()
    {
        return Auth::user()->learner;
    }

    public function openSubmit(int $homeworkId): void
    {
        $existing = HomeworkSubmission::where('homework_id', $homeworkId)
            ->where('learner_id', $this->learner()?->id)
            ->first();

        $this->submittingHomeworkId = $homeworkId;
        $this->submissionText = $existing?->submission_text ?? '';
        $this->uploadedFile = null;
    }

    public function closeSubmit(): void
    {
        $this->submittingHomeworkId = null;
        $this->submissionText = '';
        $this->uploadedFile = null;
    }

    public function submit(): void
    {
        $this->validate();

        $learner = $this->learner();
        if (! $learner) {
            $this->addError('submissionText', 'Your account is not linked to a learner record. Please contact the school office.');
            return;
        }

        $homework = Homework::findOrFail($this->submittingHomeworkId);
        $isLate = now()->greaterThan($homework->due_at);

        if ($isLate && ! $homework->allow_late) {
            $this->addError('submissionText', 'The deadline for this homework has passed and late submissions are not allowed.');
            return;
        }

        $filePath = $this->uploadedFile
            ? $this->uploadedFile->store("homework/{$homework->id}", 'public')
            : null;

        $data = [
            'submission_text' => $this->submissionText ?: null,
            'status'          => $isLate ? 'late' : 'submitted',
            'submitted_at'    => now(),
        ];

        if ($filePath) {
            $data['file_path'] = $filePath;
        }

        HomeworkSubmission::updateOrCreate(
            ['homework_id' => $homework->id, 'learner_id' => $learner->id],
            $data
        );

        $this->closeSubmit();
        session()->flash('success', 'Homework submitted successfully.');
    }

    public function render()
    {
        $learner = $this->learner();

        $homework = $learner
            ? Homework::with(['learningArea', 'schoolClass'])
                ->where('class_id', $learner->class_id)
                ->forTerm(config('school.current_term'), config('school.academic_year'))
                ->with(['submissions' => fn($q) => $q->where('learner_id', $learner->id)])
                ->latest('due_at')
                ->get()
            : collect();

        return view('livewire.homework.learner-homework-portal', [
            'learner'  => $learner,
            'homework' => $homework,
        ])->layout('layouts.learner', ['header' => 'Homework']);
    }
}
