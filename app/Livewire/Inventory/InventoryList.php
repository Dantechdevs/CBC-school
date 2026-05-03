<?php

namespace App\Livewire\Inventory;

use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class InventoryList extends Component
{
    use WithPagination;

    public string $search      = '';
    public string $typeFilter  = '';
    public bool   $lowStock    = false;
    public int    $perPage     = 20;

    // Issue item modal
    public bool   $showIssueModal = false;
    public ?int   $selectedItemId = null;
    public int    $issueQty       = 1;
    public string $issueType      = 'learner'; // learner | staff
    public ?int   $issueTo        = null;
    public string $issueRemarks   = '';

    protected $queryString = ['search', 'typeFilter', 'lowStock'];

    public function updatingSearch(): void { $this->resetPage(); }

    public function openIssueModal(int $itemId): void
    {
        $this->selectedItemId = $itemId;
        $this->showIssueModal = true;
        $this->issueQty       = 1;
    }

    public function closeIssueModal(): void { $this->showIssueModal = false; }

    public function issueItem(): void
    {
        $item = InventoryItem::findOrFail($this->selectedItemId);

        if ($this->issueQty > $item->quantity_in_stock) {
            $this->addError('issueQty', 'Insufficient stock. Available: ' . $item->quantity_in_stock);
            return;
        }

        InventoryTransaction::create([
            'item_id'          => $item->id,
            'type'             => 'issued',
            'quantity'         => $this->issueQty,
            'balance_after'    => $item->quantity_in_stock - $this->issueQty,
            'learner_id'       => $this->issueType === 'learner' ? $this->issueTo : null,
            'staff_id'         => $this->issueType === 'staff' ? $this->issueTo : null,
            'processed_by'     => auth()->user()->staffMember->id,
            'academic_year'    => config('school.academic_year'),
            'remarks'          => $this->issueRemarks,
            'transaction_date' => today(),
        ]);

        $item->decrement('quantity_in_stock', $this->issueQty);
        $item->increment('quantity_issued', $this->issueQty);

        $this->closeIssueModal();
        session()->flash('success', "{$this->issueQty} {$item->unit}(s) of '{$item->name}' issued successfully.");
    }

    public function render()
    {
        $items = InventoryItem::with('category')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('code', 'like', "%{$this->search}%"))
            ->when($this->typeFilter === 'textbook', fn($q) => $q->where('is_textbook', true))
            ->when($this->typeFilter === 'low_stock', fn($q) => $q->whereColumn('quantity_in_stock', '<=', 'minimum_stock_level'))
            ->when($this->lowStock, fn($q) => $q->whereColumn('quantity_in_stock', '<=', 'minimum_stock_level'))
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.inventory.inventory-list', ['items' => $items])
            ->layout('layouts.admin');
    }
}
