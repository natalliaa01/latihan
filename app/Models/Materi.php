<?php
// app/Models/Materi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Materi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kursus_id',
        'judul',
        'deskripsi',
        'file_path',
        'tipe',
        'urutan'
    ];

    // Relasi belongsTo ke Kursus
    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
    }

    // Accessor untuk URL file
    public function getFileUrlAttribute()
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    // Accessor untuk nama file
    public function getFileNameAttribute()
    {
        return $this->file_path ? basename($this->file_path) : null;
    }

    // Accessor untuk icon tipe
    public function getTipeIconAttribute()
    {
        $icons = [
            'video' => 'bi-play-circle',
            'document' => 'bi-file-text',
            'link' => 'bi-link-45deg',
            'other' => 'bi-file'
        ];

        return $icons[$this->tipe] ?? 'bi-file';
    }
}