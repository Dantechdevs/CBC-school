<?php

namespace App\Livewire\Assessment;

use App\Enums\RubricLevel;
use App\Enums\TermEnum;
use App\Models\Assessment;
use App\Models\LearningArea;
use App\Models\Learner;
use App\Models\SchoolClass;
use App\Models\Strand;
use Livewire\Component;

class BulkAssessmentEntry extends Component
{
    public ?int    $classId         = null;
    public ?int    $learningAreaId  = null;
    public ?int    $strandId        = null;
    public string  $term            = '';
    public string  $academicYear    = '';
    public string  $assessmentType  = 'formative';
    public array   $assessmentData  = []; // keyed by learner_id

    public function mount(): void
    {
        $this->academicYear = config('school.academic_year');
        $this->term         = config('school.current_term');
    }

    public function loadLearners(): void
    {
        if (!$this->classId) return;

        $learners = Learner::where('class_id', $this->classId)->where('is_active', true)
            ->orderBy('last_name')->get();

        $this->assessmentData = $learners->mapWithKeys(fn($l) => [
            $l->id => ['rubric_level' => '', 'remarks' => '', 'name' => $l->full_name]
        ])->toArray();
    }

    public function saveAssessments(): void
    {
        $this->validate([
            'classId'        => 'required|exists:school_classes,id',
            'learningAreaId' => 'required|exists:learning_areas,id',
            'term'           => 'required',
            'academicYear'   => 'required',
        ]);

        $teacherId = auth()->user()->staffMember?->id;
        $saved = 0;

        foreach ($this->assessmentData as $learnerId => $data) {
            if (empty($data['rubric_level'])) continue;

            Assessment::updateOrCreate(
                [
                    'learner_id'      => $learnerId,
                    'learning_area_id'=> $this->learningAreaId,
                    'strand_id'       => $this->strandId,
                    'term'            => $this->term,
                    'academic_year'   => $this->academicYear,
                    'assessment_type' => $this->assessmentType,
                ],
                [
                    'rubric_level' => $data['rubric_level'],
                    'remarks'      => $data['remarks'] ?? null,
                    'teacher_id'   => $teacherId,
                    'class_id'     => $this->classId,
                    'assessed_date'=> now(),
                ]
            );
            $saved++;
        }

        $this->dispatch('notify', type: 'success', message: "{$saved} assessments saved successfully.");
    }

    public function render()
    {
        return view('livewire.assessment.bulk-assessment-entry', [
            'classes'       => SchoolClass::orderBy('grade_level')->get(),
            'learningAreas' => LearningArea::where('is_active', true)->orderBy('name')->get(),
            'strands'       => $this->learningAreaId
                ? Strand::where('learning_area_id', $this->learningAreaId)->orderBy('sort_order')->get()
                : collect(),
            'rubricLevels'  => RubricLevel::cases(),
            'terms'         => TermEnum::cases(),
        ])->layout('layouts.teacher');
    }
}
