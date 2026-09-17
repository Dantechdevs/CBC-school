<?php

namespace App\Livewire\Staff;

use App\Models\StaffMember;
use Livewire\Component;
use Livewire\WithPagination;

class StaffList extends Component
{
    use WithPagination;

    public string $search           = '';
    public string $departmentFilter = '';
    public string $typeFilter       = '';
    public string $statusFilter     = 'active';
    public int    $perPage          = 25;
    public ?int   $viewingId        = null;

    protected $queryString = ['search', 'departmentFilter', 'typeFilter'];

    public function updatingSearch(): void { $this->resetPage(); }

    public function view(int $id): void   { $this->viewingId = $id; }
    public function closeView(): void     { $this->viewingId = null; }

    public function render()
    {
        $staff = StaffMember::query()
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('first_name', 'like', "%{$this->search}%")
                  ->orWhere('last_name', 'like', "%{$this->search}%")
                  ->orWhere('staff_number', 'like', "%{$this->search}%")
                  ->orWhere('tsc_number', 'like', "%{$this->search}%");
            }))
            ->when($this->departmentFilter, fn($q) => $q->where('department', $this->departmentFilter))
            ->when($this->typeFilter, fn($q) => $q->where('staff_type', $this->typeFilter))
            ->when($this->statusFilter === 'active', fn($q) => $q->where('is_active', true))
            ->when($this->statusFilter === 'inactive', fn($q) => $q->where('is_active', false))
            ->orderBy('last_name')
            ->paginate($this->perPage);

        return view('livewire.staff.staff-list', [
            'staff'        => $staff,
            'departments'  => StaffMember::whereNotNull('department')->distinct()->pluck('department'),
            'totalActive'  => StaffMember::active()->count(),
            'teaching'     => StaffMember::active()->teaching()->count(),
            'nonTeaching'  => StaffMember::active()->nonTeaching()->count(),
            'viewingStaff' => $this->viewingId ? StaffMember::find($this->viewingId) : null,
        ])->layout('layouts.admin', ['header' => 'Staff & HR']);
    }
}
