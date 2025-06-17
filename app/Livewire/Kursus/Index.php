namespace App\Livewire\Kursus;

use App\Models\Kursus;
use App\Models\Instruktur;
use Livewire\Component;
use Livewire\WithFileUploads; // Import untuk upload file

class Index extends Component
{
    use WithFileUploads; // Menggunakan trait untuk upload file

    public $courses, $nama_kursus, $durasi, $instruktur_id, $biaya, $course_id;
    public $materi_judul, $materi_deskripsi, $materi_file; // Untuk upload materi
    public $selected_course_id_for_materi; // Untuk mengelola materi kursus tertentu
    public $showMateriModal = false;

    public $isOpen = 0;
    public $instructors; // Untuk dropdown instruktur

    protected $listeners = ['refreshCourses' => '$refresh']; // Untuk me-refresh daftar kursus

    public function mount()
    {
        $this->instructors = Instruktur::all(); // Ambil semua instruktur saat komponen dimuat
    }

    public function render()
    {
        $this->courses = Kursus::with('instruktur')->get(); // Eager load instruktur
        return view('livewire.kursus.index');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->nama_kursus = '';
        $this->durasi = '';
        $this->instruktur_id = '';
        $this->biaya = '';
        $this->course_id = '';
        $this->materi_judul = '';
        $this->materi_deskripsi = '';
        $this->materi_file = null;
        $this->selected_course_id_for_materi = null;
    }

    public function store()
    {
        $this->validate([
            'nama_kursus' => 'required',
            'durasi' => 'required',
            'instruktur_id' => 'required|exists:instruktur,id',
            'biaya' => 'required|numeric',
        ]);

        Kursus::updateOrCreate(
            ['id' => $this->course_id],
            [
                'nama_kursus' => $this->nama_kursus,
                'durasi' => $this->durasi,
                'instruktur_id' => $this->instruktur_id,
                'biaya' => $this->biaya,
            ]
        );

        session()->flash(
            'message',
            $this->course_id ? 'Kursus berhasil diupdate.' : 'Kursus berhasil ditambahkan.'
        );

        $this->closeModal();
        $this->resetInputFields();
        $this->emit('refreshCourses'); // Emit event untuk me-refresh daftar kursus
    }

    public function edit($id)
    {
        $course = Kursus::findOrFail($id);
        $this->course_id = $id;
        $this->nama_kursus = $course->nama_kursus;
        $this->durasi = $course->durasi;
        $this->instruktur_id = $course->instruktur_id;
        $this->biaya = $course->biaya;

        $this->openModal();
    }

    public function delete($id)
    {
        Kursus::find($id)->delete();
        session()->flash('message', 'Kursus berhasil dihapus.');
        $this->emit('refreshCourses');
    }

    // --- Fungsi untuk Upload Materi ---
    public function openMateriModal($courseId)
    {
        $this->selected_course_id_for_materi = $courseId;
        $this->materi_judul = '';
        $this->materi_deskripsi = '';
        $this->materi_file = null;
        $this->showMateriModal = true;
    }

    public function closeMateriModal()
    {
        $this->showMateriModal = false;
        $this->resetInputFields(); // Reset input materi juga
    }

    public function uploadMateri()
    {
        $this->validate([
            'materi_judul' => 'required',
            'materi_file' => 'nullable|file|max:10240', // Max 10MB
        ]);

        $filePath = null;
        if ($this->materi_file) {
            $filePath = $this->materi_file->store('materi', 'public'); // Simpan di storage/app/public/materi
        }

        \App\Models\Materi::create([ // Gunakan full namespace untuk menghindari konflik
            'kursus_id' => $this->selected_course_id_for_materi,
            'judul' => $this->materi_judul,
            'deskripsi' => $this->materi_deskripsi,
            'file_path' => $filePath,
        ]);

        session()->flash('message', 'Materi berhasil ditambahkan.');
        $this->closeMateriModal();
        $this->emit('refreshCourses'); // Refresh daftar kursus untuk melihat materi baru
    }
}