namespace App\Livewire\Instruktur;

use App\Models\Instruktur;
use Livewire\Component;

class Index extends Component
{
    public $instructors, $name, $email, $instructor_id;
    public $isOpen = 0;

    public function render()
    {
        $this->instructors = Instruktur::all();
        return view('livewire.instruktur.index');
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
        $this->name = '';
        $this->email = '';
        $this->instructor_id = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:instruktur,email,' . $this->instructor_id,
        ]);

        Instruktur::updateOrCreate(
            ['id' => $this->instructor_id],
            ['nama' => $this->name, 'email' => $this->email]
        );

        session()->flash(
            'message',
            $this->instructor_id ? 'Instruktur berhasil diupdate.' : 'Instruktur berhasil ditambahkan.'
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $instructor = Instruktur::findOrFail($id);
        $this->instructor_id = $id;
        $this->name = $instructor->nama;
        $this->email = $instructor->email;

        $this->openModal();
    }

    public function delete($id)
    {
        Instruktur::find($id)->delete();
        session()->flash('message', 'Instruktur berhasil dihapus.');
    }
}