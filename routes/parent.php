<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\Homework\ParentHomeworkView;
use App\Livewire\Fees\ParentFeesView;

Route::get('/dashboard', fn() => view('parent.dashboard'))->name('dashboard');
Route::get('/progress', fn() => view('parent.progress.index'))->name('progress.index');
Route::get('/fees', ParentFeesView::class)->name('fees.index');
Route::get('/notes', fn() => view('parent.notes.index'))->name('notes.index');
Route::get('/homework', ParentHomeworkView::class)->name('homework.index');
