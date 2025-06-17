namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate->Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi'; // Pastikan nama tabel sesuai
    protected $fillable = ['kursus_id', 'judul', 'deskripsi', 'file_path'];

    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
    }
}