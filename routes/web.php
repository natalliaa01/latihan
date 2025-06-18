<?php
// routes/web.php

use App\Livewire\Dashboard;
use App\Livewire\InstrukturManager;
use App\Livewire\KursusManager;
use App\Livewire\PendaftaranManager;
use App\Livewire\MateriManager;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/instruktur', InstrukturManager::class)->name('instruktur.index');
    Route::get('/kursus', KursusManager::class)->name('kursus.index');
    Route::get('/pendaftaran', PendaftaranManager::class)->name('pendaftaran.index');
    Route::get('/materi', MateriManager::class)->name('materi.index');
});

require __DIR__.'/auth.php';