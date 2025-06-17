namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran'; // Pastikan nama tabel sesuai
    protected $fillable = ['kursus_id', 'peserta_id', 'status'];

    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
    }

    public function peserta()
    {
        // Menggunakan model User sebagai Peserta
        return $this->belongsTo(User::class, 'peserta_id');
    }
}