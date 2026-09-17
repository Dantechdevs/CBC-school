<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\Homework\LearnerHomeworkPortal;

Route::get('/dashboard', fn() => redirect()->route('learner.homework.index'))->name('dashboard');
Route::get('/homework', LearnerHomeworkPortal::class)->name('homework.index');
