<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('contacts', ContactController::class);
Route::get('import', [ImportController::class, 'showForm'])->name('import.form');
Route::post('import', [ImportController::class, 'import'])->name('import.run');
Route::get('import/{import}', [ImportController::class, 'status'])->name('import.status');
