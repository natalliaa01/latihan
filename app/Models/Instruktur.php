namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instruktur extends Model
{
    use HasFactory;

    protected $table = 'instruktur'; // Pastikan nama tabel sesuai
    protected $fillable = ['nama', 'email'];

    public function kursus()
    {
        return $this->hasMany(Kursus::class);
    }
}