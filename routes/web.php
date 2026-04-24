<?php

use App\Http\Controllers\StudentResultController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StudentResultController::class, 'home'])->name('home');

Route::get('/results/course/{course_id}', [StudentResultController::class, 'index'])->name('results.index');
Route::post('/results/course/{course_id}/search', [StudentResultController::class, 'search'])->name('results.search');

