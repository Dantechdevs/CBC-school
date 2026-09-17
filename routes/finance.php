<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\Fees\FeePayment;
use App\Livewire\Fees\InvoiceList;
use App\Livewire\Fees\FinanceReports;
use App\Livewire\Inventory\InventoryList;

Route::get('/dashboard', fn() => view('finance.dashboard'))->name('dashboard');
Route::get('/payments', FeePayment::class)->name('payments.index');
Route::get('/invoices', InvoiceList::class)->name('invoices.index');
Route::get('/inventory', InventoryList::class)->name('inventory.index');
Route::get('/reports', FinanceReports::class)->name('reports.index');
