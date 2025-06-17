namespace App\Livewire\Report;

use App\Models\Kursus;
use Livewire\Component;

class PesertaPerKursus extends Component
{
    public $courses;

    public function render()
    {
        // Menghitung jumlah peserta per kursus
        $this->courses = Kursus::withCount('pendaftaran')->get();
        return view('livewire.report.peserta-per-kursus');
    }
}