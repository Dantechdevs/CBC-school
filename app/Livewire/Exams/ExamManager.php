<?php

namespace App\Livewire\Exams;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\LearningArea;
use App\Models\Learner;
use App\Models\SchoolClass;
use App\Models\StaffMember;
use Livewire\Component;
use Livewire\WithPagination;

class ExamManager extends Component
{
    use WithPagination;

    public string $tab          = 'exams'; // exams | marks | results
    public ?int   $selectedExam = null;

    // Create Exam form
    public bool   $showCreateModal = false;
    public string $examName       = '';
    public string $examGrade      = '';
    public ?int   $examAreaId     = null;
    public string $examType       = 'end_term';
    public string $examTerm       = '';
    public float  $totalMarks     = 100;
    public float  $passMark       = 50;
    public ?string $examDate      = null;

    // Mark entry
    public array  $marks          = []; // keyed by learner_id

    protected $rules = [
        'examName'  => 'required|string|max:200',
        'examGrade' => 'required',
        'examAreaId'=> 'required|exists:learning_areas,id',
        'examType'  => 'required',
        'examTerm'  => 'required',
        'totalMarks'=> 'required|numeric|min:1',
        'passMark'  => 'required|numeric|min:0',
    ];

    public function createExam(): void
    {
        $this->validate();
        $teacher = StaffMember::where('user_id', auth()->id())->first();

        Exam::create([
            'name'            => $this->examName,
            'grade_level'     => $this->examGrade,
            'learning_area_id'=> $this->examAreaId,
            'academic_year'   => config('school.academic_year'),
            'term'            => $this->examTerm,
            'exam_type'       => $this->examType,
            'total_marks'     => $this->totalMarks,
            'pass_mark'       => $this->passMark,
            'exam_date'       => $this->examDate,
            'status'          => 'published',
            'created_by'      => $teacher?->id ?? 1,
        ]);

        $this->dispatch('notify', type: 'success', message: 'Exam created successfully.');
        $this->showCreateModal = false;
        $this->reset(['examName','examGrade','examAreaId','examTerm','examDate']);
    }

    public function loadMarkEntry(int $examId): void
    {
        $this->selectedExam = $examId;
        $exam = Exam::findOrFail($examId);
        $learners = Learner::where('grade_level', $exam->grade_level)->where('is_active', true)->get();

        $existing = ExamResult::where('exam_id', $examId)->pluck('marks_obtained', 'learner_id');

        $this->marks = $learners->mapWithKeys(fn($l) => [
            $l->id => ['name' => $l->full_name, 'marks' => $existing[$l->id] ?? '']
        ])->toArray();

        $this->tab = 'marks';
    }

    public function saveMarks(): void
    {
        $exam    = Exam::findOrFail($this->selectedExam);
        $teacher = StaffMember::where('user_id', auth()->id())->first();
        $saved   = 0;

        foreach ($this->marks as $learnerId => $data) {
            if ($data['marks'] === '' || $data['marks'] === null) continue;

            $marks = (float) $data['marks'];
            $grade = $this->calculateGrade($marks, $exam->total_marks);

            ExamResult::updateOrCreate(
                ['exam_id' => $exam->id, 'learner_id' => $learnerId],
                [
                    'marks_obtained' => $marks,
                    'total_marks'    => $exam->total_marks,
                    'grade'          => $grade,
                    'marked_by'      => $teacher?->id ?? 1,
                ]
            );
            $saved++;
        }

        $this->dispatch('notify', type: 'success', message: "{$saved} results saved.");
    }

    private function calculateGrade(float $marks, float $total): string
    {
        $percent = ($marks / $total) * 100;
        return match(true) {
            $percent >= 80 => 'A',
            $percent >= 70 => 'B',
            $percent >= 60 => 'C',
            $percent >= 50 => 'D',
            default        => 'E',
        };
    }

    public function render()
    {
        $exams = Exam::with(['learningArea'])
            ->where('academic_year', config('school.academic_year'))
            ->latest()->paginate(20);

        return view('livewire.exams.exam-manager', [
            'exams'         => $exams,
            'learningAreas' => LearningArea::where('is_active', true)->get(),
            'gradeLevels'   => config('school.grade_levels'),
            'marks'         => $this->marks,
        ])->layout('layouts.admin');
    }
}
