<?php

use App\Http\Controllers\CaseStudyController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::get('/case-studies', [CaseStudyController::class, 'index'])->name('case-studies.index');
Route::get('/case-studies/{caseStudy:slug}', [CaseStudyController::class, 'show'])->name('case-studies.show');
