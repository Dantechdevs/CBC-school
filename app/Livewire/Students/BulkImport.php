<?php

namespace App\Livewire\Students;

use App\Imports\LearnersImport;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class BulkImport extends Component
{
    use WithFileUploads;

    public $file = null;
    public bool $processed = false;
    public int $importedCount = 0;
    public array $importErrors = [];

    protected $rules = [
        'file' => 'required|file|mimes:csv,xlsx,xls|max:5120',
    ];

    public function import(): void
    {
        $this->validate();

        $importer = new LearnersImport();
        Excel::import($importer, $this->file->getRealPath());

        $this->importedCount = count($importer->imported);
        $this->importErrors  = $importer->errors;
        $this->processed     = true;
        $this->file           = null;
    }

    public function startOver(): void
    {
        $this->reset(['file', 'processed', 'importedCount', 'importErrors']);
    }

    public function render()
    {
        return view('livewire.students.bulk-import')
            ->layout('layouts.admin', ['header' => 'Import Learners']);
    }
}
