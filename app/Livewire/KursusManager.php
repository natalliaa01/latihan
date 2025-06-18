<?php
// app/Livewire/KursusManager.php

namespace App\Livewire;

use App\Models\Kursus;
use App\Models\Instruktur;
use Livewire\Component;
use Livewire\WithPagination;

class KursusManager extends Component
{
    use WithPagination;

    public $nama_kursus, $durasi, $instruktur_id, $biaya, $deskripsi, $status = 'aktif';
    public $kursus_id;
    public $isEdit = false;
    public $showModal = false;
    public $search = '';

    protected $rules = [
        'nama_kursus' => 'required|min:3',
        'durasi' => 'required|integer|min:1',
        'instruktur_id' => 'required|exists:instrukturs,id',
        'biaya' => 'required|numeric|min:0',
        'deskripsi' => 'nullable|string',
        'status' => 'required|in:aktif,nonaktif'
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
        $this->nama_kursus = '';
        $this->durasi = '';
        $this->instruktur_id = '';
        $this->biaya = '';
        $this->deskripsi = '';
        $this->status = 'aktif';
        $this->kursus_id = null;
        $this->isEdit = false;
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        Kursus::create([
            'nama_kursus' => $this->nama_kursus,
            'durasi' => $this->durasi,
            'instruktur_id' => $this->instruktur_id,
            'biaya' => $this->biaya,
            'deskripsi' => $this->deskripsi,
            'status' => $this->status
        ]);

        session()->flash('message', 'Kursus berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $kursus = Kursus::findOrFail($id);
        $this->kursus_id = $id;
        $this->nama_kursus = $kursus->nama_kursus;
        $this->durasi = $kursus->durasi;
        $this->instruktur_id = $kursus->instruktur_id;
        $this->biaya = $kursus->biaya;
        $this->deskripsi = $kursus->deskripsi;
        $this->status = $kursus->status;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate();

        $kursus = Kursus::findOrFail($this->kursus_id);
        $kursus->update([
            'nama_kursus' => $this->nama_kursus,
            'durasi' => $this->durasi,
            'instruktur_id' => $this->instruktur_id,
            'biaya' => $this->biaya,
            'deskripsi' => $this->deskripsi,
            'status' => $this->status
        ]);

        session()->flash('message', 'Kursus berhasil diupdate.');
        $this->closeModal();
    }

    public function delete($id)
    {
        $kursus = Kursus::findOrFail($id);
        
        // Cek apakah kursus memiliki pendaftaran
        if ($kursus->pendaftarans()->count() > 0) {
            session()->flash('error', 'Tidak dapat menghapus kursus yang sudah memiliki pendaftaran.');
            return;
        }

        $kursus->delete();
        session()->flash('message', 'Kursus berhasil dihapus.');
    }

    public function render()
    {
        $kursuses = Kursus::with(['instruktur', 'pendaftarans'])
            ->where('nama_kursus', 'like', '%' . $this->search . '%')
            ->orWhereHas('instruktur', function($query) {
                $query->where('nama', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        $instrukturs = Instruktur::all();

        return view('livewire.kursus-manager', compact('kursuses', 'instrukturs'));
    }
}