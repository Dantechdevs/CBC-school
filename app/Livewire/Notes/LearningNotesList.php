<?php

namespace App\Livewire\Notes;

use App\Models\LearningNote;
use App\Models\LearningArea;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class LearningNotesList extends Component
{
    use WithPagination, WithFileUploads;

    public string $search        = '';
    public string $gradeFilter   = '';
    public string $areaFilter    = '';
    public string $termFilter    = '';
    public bool   $showModal     = false;

    // Upload form
    public string $title         = '';
    public string $description   = '';
    public string $grade         = '';
    public string $term          = '';
    public string $resourceType  = 'pdf';
    public string $externalUrl   = '';
    public        $uploadedFile  = null;
    public ?int   $learningAreaId = null;

    protected $rules = [
        'title'         => 'required|string|max:200',
        'grade'         => 'required|string',
        'term'          => 'required|string',
        'resourceType'  => 'required|in:pdf,video,image,document,link,other',
        'learningAreaId'=> 'required|integer|exists:learning_areas,id',
        'uploadedFile'  => 'nullable|file|max:51200',
        'externalUrl'   => 'nullable|url',
    ];

    public function upload(): void
    {
        $this->validate();

        $filePath = null;
        if ($this->uploadedFile) {
            $filePath = $this->uploadedFile->store("notes/{$this->grade}", 'public');
        }

        LearningNote::create([
            'teacher_id'       => Auth::user()->staffMember->id,
            'learning_area_id' => $this->learningAreaId,
            'grade_level'      => $this->grade,
            'title'            => $this->title,
            'description'      => $this->description,
            'term'             => $this->term,
            'academic_year'    => config('school.academic_year'),
            'resource_type'    => $this->resourceType,
            'file_path'        => $filePath,
            'external_url'     => $this->externalUrl ?: null,
            'is_published'     => true,
        ]);

        $this->showModal = false;
        $this->reset(['title', 'description', 'grade', 'term', 'uploadedFile', 'externalUrl', 'learningAreaId']);
        session()->flash('success', 'Learning note uploaded successfully.');
    }

    public function togglePublish(int $id): void
    {
        $note = LearningNote::findOrFail($id);
        $note->update(['is_published' => !$note->is_published]);
    }

    public function delete(int $id): void
    {
        $note = LearningNote::findOrFail($id);
        if ($note->file_path) {
            Storage::disk('public')->delete($note->file_path);
        }
        $note->delete();
    }

    public function render()
    {
        $notes = LearningNote::with(['teacher', 'learningArea'])
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->gradeFilter, fn($q) => $q->where('grade_level', $this->gradeFilter))
            ->when($this->areaFilter, fn($q) => $q->where('learning_area_id', $this->areaFilter))
            ->when($this->termFilter, fn($q) => $q->where('term', $this->termFilter))
            ->latest()
            ->paginate(20);

        return view('livewire.notes.learning-notes-list', [
            'notes'         => $notes,
            'learningAreas' => LearningArea::orderBy('name')->get(),
        ])->layout('layouts.admin');
    }
}
