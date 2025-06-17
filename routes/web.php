<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Instruktur\Index as InstrukturIndex;
use App\Livewire\Kursus\Index as KursusIndex;
use App\Livewire\Pendaftaran\Index as PendaftaranIndex;
use App\Livewire\Report\PesertaPerKursus;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Livewire routes untuk CRUD
    Route::get('/instruktur', InstrukturIndex::class)->name('instruktur.index');
    Route::get('/kursus', KursusIndex::class)->name('kursus.index');
    Route::get('/pendaftaran', PendaftaranIndex::class)->name('pendaftaran.index');
    Route::get('/report/peserta-per-kursus', PesertaPerKursus::class)->name('report.peserta-per-kursus');
});

require __DIR__.'/auth.php';
