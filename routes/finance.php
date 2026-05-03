<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\Fees\FeePayment;

Route::get('/dashboard', fn() => view('finance.dashboard'))->name('dashboard');
Route::get('/payments', FeePayment::class)->name('payments.index');
Route::get('/invoices', fn() => view('finance.invoices.index'))->name('invoices.index');
Route::get('/inventory', fn() => view('finance.inventory.index'))->name('inventory.index');
Route::get('/reports', fn() => view('finance.reports.index'))->name('reports.index');
