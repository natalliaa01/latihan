namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kursus extends Model
{
    use HasFactory;

    protected $table = 'kursus'; // Pastikan nama tabel sesuai
    protected $fillable = ['nama_kursus', 'durasi', 'instruktur_id', 'biaya'];

    public function instruktur()
    {
        return $this->belongsTo(Instruktur::class);
    }

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function materi()
    {
        return $this->hasMany(Materi::class);
    }
}