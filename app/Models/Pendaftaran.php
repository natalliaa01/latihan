<?php
// app/Models/Pendaftaran.php - Update dengan accessor untuk status badge

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftarans';

    protected $fillable = [
        'kursus_id',
        'peserta_id',
        'tanggal_daftar',
        'status',
        'catatan'
    ];

    protected $casts = [
        'tanggal_daftar' => 'date'
    ];

    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
    }

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'diterima' => 'bg-success',
            'ditolak' => 'bg-danger',
            'selesai' => 'bg-info',
            default => 'bg-secondary'
        };
    }
}

// app/Models/Peserta.php - Update dengan fields tambahan

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    use HasFactory;

    protected $table = 'pesertas';

    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'alamat'
    ];

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function kursuses()
    {
        return $this->belongsToMany(Kursus::class, 'pendaftarans')
                    ->withPivot('status', 'tanggal_daftar', 'catatan')
                    ->withTimestamps();
    }
}

// app/Models/Kursus.php - Update dengan accessor untuk format biaya

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kursus extends Model
{
    use HasFactory;

    protected $table = 'kursuses';

    protected $fillable = [
        'nama_kursus',
        'deskripsi',
        'durasi',
        'instruktur_id',
        'biaya',
        'status'
    ];

    protected $casts = [
        'biaya' => 'integer'
    ];

    public function instruktur()
    {
        return $this->belongsTo(Instruktur::class);
    }

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function materis()
    {
        return $this->hasMany(Materi::class);
    }

    public function pesertas()
    {
        return $this->belongsToMany(Peserta::class, 'pendaftarans')
                    ->withPivot('status', 'tanggal_daftar', 'catatan')
                    ->withTimestamps();
    }

    // Accessor untuk format biaya
    public function getFormattedBiayaAttribute()
    {
        return 'Rp ' . number_format($this->biaya, 0, ',', '.');
    }

    // Accessor untuk jumlah peserta
    public function getJumlahPesertaAttribute()
    {
        return $this->pendaftarans()->whereIn('status', ['diterima', 'selesai'])->count();
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'aktif' => 'bg-success',
            'tidak_aktif' => 'bg-danger',
            'draft' => 'bg-warning',
            default => 'bg-secondary'
        };
    }
}

// app/Models/Materi.php - Update dengan method untuk file

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materis';

    protected $fillable = [
        'kursus_id',
        'judul',
        'deskripsi',
        'file_path',
        'url_video',
        'urutan'
    ];

    protected $casts = [
        'urutan' => 'integer'
    ];

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

    // Method untuk menghapus file
    public function deleteFile()
    {
        if ($this->file_path && Storage::exists($this->file_path)) {
            Storage::delete($this->file_path);
        }
    }
}