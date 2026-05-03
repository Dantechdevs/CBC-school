<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\Students\StudentList;
use App\Livewire\Assessment\BulkAssessmentEntry;

Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
Route::get('/students', StudentList::class)->name('students.index');
Route::get('/assessment', BulkAssessmentEntry::class)->name('assessment.index');
Route::get('/staff', fn() => view('admin.staff.index'))->name('staff.index');
Route::get('/timetable', fn() => view('admin.timetable.index'))->name('timetable.index');
Route::get('/reports', fn() => view('admin.reports.index'))->name('reports.index');
Route::get('/settings', fn() => view('admin.settings.index'))->name('settings.index');
Route::get('/kemis', fn() => view('admin.kemis.index'))->name('kemis.index');
