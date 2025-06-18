<?php
// app/Livewire/InstrukturManager.php

namespace App\Livewire;

use App\Models\Instruktur;
use Livewire\Component;
use Livewire\WithPagination;

class InstrukturManager extends Component
{
    use WithPagination;

    public $nama, $email, $bio, $keahlian;
    public $instruktur_id;
    public $isEdit = false;
    public $showModal = false;
    public $search = '';

    protected $rules = [
        'nama' => 'required|min:3',
        'email' => 'required|email|unique:instrukturs,email',
        'bio' => 'nullable|string',
        'keahlian' => 'nullable|string'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->nama = '';
        $this->email = '';
        $this->bio = '';
        $this->keahlian = '';
        $this->instruktur_id = null;
        $this->isEdit = false;
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        Instruktur::create([
            'nama' => $this->nama,
            'email' => $this->email,
            'bio' => $this->bio,
            'keahlian' => $this->keahlian
        ]);

        session()->flash('message', 'Instruktur berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $instruktur = Instruktur::findOrFail($id);
        $this->instruktur_id = $id;
        $this->nama = $instruktur->nama;
        $this->email = $instruktur->email;
        $this->bio = $instruktur->bio;
        $this->keahlian = $instruktur->keahlian;
        $this->isEdit = true;
        $this->showModal = true;

        // Update validation rules untuk edit
        $this->rules['email'] = 'required|email|unique:instrukturs,email,' . $id;
    }

    public function update()
    {
        $this->rules['email'] = 'required|email|unique:instrukturs,email,' . $this->instruktur_id;
        $this->validate();

        $instruktur = Instruktur::findOrFail($this->instruktur_id);
        $instruktur->update([
            'nama' => $this->nama,
            'email' => $this->email,
            'bio' => $this->bio,
            'keahlian' => $this->keahlian
        ]);

        session()->flash('message', 'Instruktur berhasil diupdate.');
        $this->closeModal();
    }

    public function delete($id)
    {
        $instruktur = Instruktur::findOrFail($id);
        
        // Cek apakah instruktur memiliki kursus
        if ($instruktur->kursuses()->count() > 0) {
            session()->flash('error', 'Tidak dapat menghapus instruktur yang masih memiliki kursus.');
            return;
        }

        $instruktur->delete();
        session()->flash('message', 'Instruktur berhasil dihapus.');
    }

    public function render()
    {
        $instrukturs = Instruktur::where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('keahlian', 'like', '%' . $this->search . '%')
            ->withCount('kursuses')
            ->paginate(10);

        return view('livewire.instruktur-manager', compact('instrukturs'));
    }
}