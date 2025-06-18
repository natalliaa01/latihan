<?php
// app/Models/Peserta.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'alamat'
    ];

    // Relasi hasMany ke Pendaftaran
    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    // Relasi many-to-many ke Kursus melalui Pendaftaran
    public function kursuses()
    {
        return $this->belongsToMany(Kursus::class, 'pendaftarans')
                    ->withPivot('status', 'tanggal_daftar', 'catatan')
                    ->withTimestamps();
    }
}