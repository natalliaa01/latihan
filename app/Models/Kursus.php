<?php
// app/Models/Kursus.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kursus extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kursus',
        'durasi',
        'instruktur_id',
        'biaya',
        'deskripsi',
        'status'
    ];

    protected $casts = [
        'biaya' => 'decimal:2',
    ];

    // Relasi belongsTo ke Instruktur
    public function instruktur()
    {
        return $this->belongsTo(Instruktur::class);
    }

    // Relasi hasMany ke Pendaftaran
    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    // Relasi hasMany ke Materi
    public function materis()
    {
        return $this->hasMany(Materi::class)->orderBy('urutan');
    }

    // Accessor untuk total peserta
    public function getTotalPesertaAttribute()
    {
        return $this->pendaftarans()->where('status', 'diterima')->count();
    }

    // Accessor untuk format biaya
    public function getFormatBiayaAttribute()
    {
        return 'Rp ' . number_format($this->biaya, 0, ',', '.');
    }
}