<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware(['auth', 'verified'])->group(function () {

    Route::middleware(['role:super-admin|principal|deputy-principal|hod'])
        ->prefix('admin')->name('admin.')
        ->group(base_path('routes/admin.php'));

    Route::middleware(['role:teacher|class-teacher|hod|principal|deputy-principal'])
        ->prefix('teacher')->name('teacher.')
        ->group(base_path('routes/teacher.php'));

    Route::middleware(['role:parent'])
        ->prefix('parent')->name('parent.')
        ->group(base_path('routes/parent.php'));

    Route::middleware(['role:bursar|super-admin|principal'])
        ->prefix('finance')->name('finance.')
        ->group(base_path('routes/finance.php'));
});
