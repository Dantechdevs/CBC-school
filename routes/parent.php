<?php
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', fn() => view('parent.dashboard'))->name('dashboard');
Route::get('/progress', fn() => view('parent.progress.index'))->name('progress.index');
Route::get('/fees', fn() => view('parent.fees.index'))->name('fees.index');
Route::get('/notes', fn() => view('parent.notes.index'))->name('notes.index');
