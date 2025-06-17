namespace App\Livewire\Pendaftaran;

use App\Models\Pendaftaran;
use App\Models\Kursus;
use App\Models\User; // Menggunakan model User sebagai Peserta
use Livewire\Component;
use Illuminate\Validation\Rule;

class Index extends Component
{
    public $registrations, $kursus_id, $peserta_id, $status, $registration_id;
    public $isOpen = 0;
    public $courses; // Untuk dropdown kursus
    public $pesertas; // Untuk dropdown peserta (users)

    public function mount()
    {
        $this->courses = Kursus::all();
        $this->pesertas = User::all(); // Ambil semua user yang akan dianggap sebagai peserta
    }

    public function render()
    {
        $this->registrations = Pendaftaran::with(['kursus', 'peserta'])->get();
        return view('livewire.pendaftaran.index');
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
        $this->kursus_id = '';
        $this->peserta_id = '';
        $this->status = 'pending';
        $this->registration_id = '';
    }

    public function store()
    {
        $rules = [
            'kursus_id' => 'required|exists:kursus,id',
            'peserta_id' => 'required|exists:users,id', // Validasi peserta_id harus ada di tabel users
            'status' => 'required|string',
        ];

        // Validasi unik untuk mencegah pendaftaran ganda
        if (!$this->registration_id) { // Hanya berlaku saat membuat baru
            $rules['kursus_id'] = [
                'required',
                'exists:kursus,id',
                Rule::unique('pendaftaran')->where(function ($query) {
                    return $query->where('peserta_id', $this->peserta_id);
                }),
            ];
        }

        $this->validate($rules, [
            'kursus_id.unique' => 'Peserta sudah terdaftar di kursus ini.',
        ]);

        Pendaftaran::updateOrCreate(
            ['id' => $this->registration_id],
            [
                'kursus_id' => $this->kursus_id,
                'peserta_id' => $this->peserta_id,
                'status' => $this->status,
            ]
        );

        session()->flash(
            'message',
            $this->registration_id ? 'Pendaftaran berhasil diupdate.' : 'Pendaftaran berhasil ditambahkan.'
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $registration = Pendaftaran::findOrFail($id);
        $this->registration_id = $id;
        $this->kursus_id = $registration->kursus_id;
        $this->peserta_id = $registration->peserta_id;
        $this->status = $registration->status;

        $this->openModal();
    }

    public function delete($id)
    {
        Pendaftaran::find($id)->delete();
        session()->flash('message', 'Pendaftaran berhasil dihapus.');
    }
}