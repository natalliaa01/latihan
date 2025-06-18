<?php
// app/Livewire/Dashboard.php

namespace App\Livewire;

use App\Models\Instruktur;
use App\Models\Kursus;
use App\Models\Peserta;
use App\Models\Pendaftaran;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_instruktur' => Instruktur::count(),
            'total_kursus' => Kursus::count(),
            'total_peserta' => Peserta::count(),
            'total_pendaftaran' => Pendaftaran::count(),
        ];

        // Data untuk chart kursus per instruktur
        $kursusPerInstruktur = Kursus::with('instruktur')
            ->select('instruktur_id', DB::raw('count(*) as total'))
            ->groupBy('instruktur_id')
            ->get()
            ->map(function ($item) {
                return [
                    'instruktur' => $item->instruktur->nama,
                    'total_kursus' => $item->total
                ];
            });

        // Data untuk menampilkan jumlah peserta per kursus
        $pesertaPerKursus = Kursus::with('pendaftarans')
            ->get()
            ->map(function ($kursus) {
                return [
                    'nama_kursus' => $kursus->nama_kursus,
                    'total_peserta' => $kursus->pendaftarans->where('status', 'diterima')->count(),
                    'instruktur' => $kursus->instruktur->nama
                ];
            })
            ->sortByDesc('total_peserta');

        return view('livewire.dashboard', compact('stats', 'kursusPerInstruktur', 'pesertaPerKursus'));
    }
}