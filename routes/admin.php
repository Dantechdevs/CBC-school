<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\Students\StudentList;
use App\Livewire\Assessment\BulkAssessmentEntry;
use App\Livewire\Staff\StaffList;
use App\Livewire\Timetable\TimetableBoard;
use App\Livewire\Reports\ReportsDashboard;
use App\Livewire\Kemis\KemisSyncPanel;
use App\Livewire\Notifications\SendNotification;
use App\Livewire\Homework\HomeworkManager;
use App\Livewire\Notes\LearningNotesList;
use App\Livewire\Pathways\PathwayTracker;
use App\Livewire\Students\BulkImport;
use App\Livewire\Exams\ExamManager;
use App\Livewire\Inventory\InventoryList;

Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
Route::get('/students', StudentList::class)->name('students.index');
Route::get('/students/import', BulkImport::class)->name('students.import');
Route::get('/students/import/template', function () {
    $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="learners_import_template.csv"'];
    $columns = ['admission_number', 'first_name', 'last_name', 'gender', 'date_of_birth', 'grade_level', 'class_name', 'boarding_status', 'kemis_upi'];
    $sample  = ['ADM-2027-001', 'Jane', 'Wanjiru', 'female', '2014-05-12', 'Grade 7', 'Grade 7 Blue', 'day', ''];

    return response()->streamDownload(function () use ($columns, $sample) {
        $out = fopen('php://output', 'w');
        fputcsv($out, $columns);
        fputcsv($out, $sample);
        fclose($out);
    }, 'learners_import_template.csv', $headers);
})->name('students.import.template');
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
Route::get('/exams', ExamManager::class)->name('exams.index');
Route::get('/inventory', InventoryList::class)->name('inventory.index');
Route::get('/staff', StaffList::class)->name('staff.index');
Route::get('/timetable', TimetableBoard::class)->name('timetable.index');
Route::get('/reports', ReportsDashboard::class)->name('reports.index');
Route::get('/pathways', PathwayTracker::class)->name('pathways.index');
Route::get('/settings', fn() => view('admin.settings.index'))->name('settings.index');
Route::get('/kemis', KemisSyncPanel::class)->name('kemis.index');
Route::get('/notifications', SendNotification::class)->name('notifications.index');
Route::get('/homework', HomeworkManager::class)->name('homework.index');
Route::get('/notes', LearningNotesList::class)->name('notes.index');
