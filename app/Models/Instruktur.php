<?php
// app/Models/Instruktur.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instruktur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'email',
        'bio',
        'keahlian'
    ];

    // Relasi hasMany ke Kursus
    public function kursuses()
    {
        return $this->hasMany(Kursus::class);
    }

    // Accessor untuk total kursus
    public function getTotalKursusAttribute()
    {
        return $this->kursuses()->count();
    }
}