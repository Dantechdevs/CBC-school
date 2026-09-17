<?php

namespace App\Livewire\Assessment;

use App\Enums\RubricLevel;
use App\Enums\TermEnum;
use App\Imports\AssessmentsImport;
use App\Models\Assessment;
use App\Models\LearningArea;
use App\Models\Learner;
use App\Models\SchoolClass;
use App\Models\Strand;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class BulkAssessmentEntry extends Component
{
    use WithFileUploads;

    public ?int    $classId         = null;
    public ?int    $learningAreaId  = null;
    public ?int    $strandId        = null;
    public string  $term            = '';
    public string  $academicYear    = '';
    public string  $assessmentType  = 'formative';
    public array   $assessmentData  = []; // keyed by learner_id

    // Bulk import
    public $importFile = null;
    public array $importErrors = [];
    public int $importMatchedCount = 0;

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
            $l->id => [
                'rubric_level'     => '',
                'remarks'          => '',
                'name'             => $l->full_name,
                'admission_number' => $l->admission_number,
            ]
        ])->toArray();
    }

    public function importFromExcel(): void
    {
        $this->importErrors = [];
        $this->importMatchedCount = 0;

        if (! $this->classId || empty($this->assessmentData)) {
            $this->importErrors[] = 'Select a class first so learners are loaded before importing.';
            return;
        }

        $this->validate(['importFile' => 'required|file|mimes:csv,xlsx,xls|max:5120']);

        $importer = new AssessmentsImport();
        Excel::import($importer, $this->importFile->getRealPath());

        // Match imported rows (keyed by admission_number) back to the loaded learners
        foreach ($this->assessmentData as $learnerId => $data) {
            $admissionNumber = $data['admission_number'] ?? null;
            if ($admissionNumber && isset($importer->matched[$admissionNumber])) {
                $this->assessmentData[$learnerId]['rubric_level'] = $importer->matched[$admissionNumber]['rubric_level'];
                $this->assessmentData[$learnerId]['remarks'] = $importer->matched[$admissionNumber]['remarks'] ?? $data['remarks'];
                $this->importMatchedCount++;
            }
        }

        $this->importErrors = $importer->errors;
        $this->importFile = null;
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

    protected function isAdminRoute(): bool
    {
        return str_starts_with(request()->route()?->getName() ?? '', 'admin.');
    }

    public function render()
    {
        $layout = $this->isAdminRoute() ? 'layouts.admin' : 'layouts.teacher';

        return view('livewire.assessment.bulk-assessment-entry', [
            'classes'       => SchoolClass::orderBy('grade_level')->get(),
            'learningAreas' => LearningArea::where('is_active', true)->orderBy('name')->get(),
            'strands'       => $this->learningAreaId
                ? Strand::where('learning_area_id', $this->learningAreaId)->orderBy('order')->get()
                : collect(),
            'rubricLevels'  => RubricLevel::cases(),
            'terms'         => TermEnum::cases(),
        ])->layout($layout, ['header' => 'Assessment Entry']);
    }
}
