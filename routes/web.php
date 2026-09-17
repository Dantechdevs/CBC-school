<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public / Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/forgot-password',  [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Routes — grouped by role
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Admin / Principal / Deputy / HOD
    Route::middleware(['role:super-admin|principal|deputy-principal|hod'])
        ->prefix('admin')
        ->name('admin.')
        ->group(base_path('routes/admin.php'));

    // Teacher / Class Teacher / HOD
    Route::middleware(['role:teacher|class-teacher|hod|principal|deputy-principal'])
        ->prefix('teacher')
        ->name('teacher.')
        ->group(base_path('routes/teacher.php'));

    // Parent / Guardian
    Route::middleware(['role:parent'])
        ->prefix('parent')
        ->name('parent.')
        ->group(base_path('routes/parent.php'));

    // Learner
    Route::middleware(['role:learner'])
        ->prefix('learner')
        ->name('learner.')
        ->group(base_path('routes/learner.php'));

    // Finance / Bursar
    Route::middleware(['role:bursar|super-admin|principal'])
        ->prefix('finance')
        ->name('finance.')
        ->group(base_path('routes/finance.php'));
});
