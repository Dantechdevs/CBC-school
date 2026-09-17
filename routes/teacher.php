<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\Assessment\BulkAssessmentEntry;
use App\Livewire\Homework\HomeworkManager;
use App\Livewire\Notes\LearningNotesList;
use App\Livewire\Timetable\TimetableBoard;
use App\Livewire\Attendance\AttendanceMarker;
use App\Livewire\Exams\ExamManager;

Route::get('/dashboard', fn() => view('teacher.dashboard'))->name('dashboard');
Route::get('/assessment', BulkAssessmentEntry::class)->name('assessment.index');
Route::get('/assessment/import/template', function () {
    $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="assessment_import_template.csv"'];
    return response()->streamDownload(function () {
        $out = fopen('php://output', 'w');
        fputcsv($out, ['admission_number', 'rubric_level', 'remarks']);
        fputcsv($out, ['ADM-2026-001', 'EE', 'Excellent grasp of the concept']);
        fclose($out);
    }, 'assessment_import_template.csv', $headers);
})->name('assessment.import.template');
Route::get('/homework', HomeworkManager::class)->name('homework.index');
Route::get('/exams', ExamManager::class)->name('exams.index');
Route::get('/notes', LearningNotesList::class)->name('notes.index');
Route::get('/timetable', TimetableBoard::class)->name('timetable.index');
Route::get('/attendance', AttendanceMarker::class)->name('attendance.index');
