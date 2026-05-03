<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\Assessment\BulkAssessmentEntry;

Route::get('/dashboard', fn() => view('teacher.dashboard'))->name('dashboard');
Route::get('/assessment', BulkAssessmentEntry::class)->name('assessment.index');
Route::get('/notes', fn() => view('teacher.notes.index'))->name('notes.index');
Route::get('/timetable', fn() => view('teacher.timetable.index'))->name('timetable.index');
Route::get('/attendance', fn() => view('teacher.attendance.index'))->name('attendance.index');
