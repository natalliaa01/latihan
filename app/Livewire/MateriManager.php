<?php
// app/Livewire/MateriManager.php

namespace App\Livewire;

use App\Models\Materi;
use App\Models\Kursus;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class MateriManager extends Component
{
    use WithPagination, WithFileUploads;

    public $kursus_id, $judul, $deskripsi, $tipe = 'document', $urutan = 1;
    public $file;
    public $materi_id;
    public $isEdit = false;
    public $showModal = false;
    public $search = '';
    public $selectedKursus = '';

    protected $rules = [
        'kursus_id' => 'required|exists:kursuses,id',
        'judul' => 'required|min:3',
        'deskripsi' => 'required|string',
        'tipe' => 'required|in:video,document,link,other',
        'urutan' => 'required|integer|min:1',
        'file' => 'nullable|file|max:10240' // 10MB
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedKursus()
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
        $this->kursus_id = '';
        $this->judul = '';
        $this->deskripsi = '';
        $this->tipe = 'document';
        $this->urutan = 1;
        $this->file = null;
        $this->materi_id = null;
        $this->isEdit = false;
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        $filePath = null;
        if ($this->file) {
            $filePath = $this->file->store('materi', 'public');
        }

        Materi::create([
            'kursus_id' => $this->kursus_id,
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'file_path' => $filePath,
            'tipe' => $this->tipe,
            'urutan' => $this->urutan
        ]);

        session()->flash('message', 'Materi berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $materi = Materi::findOrFail($id);
        $this->materi_id = $id;
        $this->kursus_id = $materi->kursus_id;
        $this->judul = $materi->judul;
        $this->deskripsi = $materi->deskripsi;
        $this->tipe = $materi->tipe;
        $this->urutan = $materi->urutan;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function update()
    {
        $rules = $this->rules;
        $rules['file'] = 'nullable|file|max:10240';
        $this->validate($rules);

        $materi = Materi::findOrFail($this->materi_id);
        
        $filePath = $materi->file_path;
        if ($this->file) {
            // Hapus file lama jika ada
            if ($materi->file_path) {
                Storage::disk('public')->delete($materi->file_path);
            }
            $filePath = $this->file->store('materi', 'public');
        }

        $materi->update([
            'kursus_id' => $this->kursus_id,
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'file_path' => $filePath,
            'tipe' => $this->tipe,
            'urutan' => $this->urutan
        ]);

        session()->flash('message', 'Materi berhasil diupdate.');
        $this->closeModal();
    }

    public function delete($id)
    {
        $materi = Materi::findOrFail($id);
        
        // Hapus file jika ada
        if ($materi->file_path) {
            Storage::disk('public')->delete($materi->file_path);
        }
        
        $materi->delete();
        session()->flash('message', 'Materi berhasil dihapus.');
    }

    public function downloadFile($id)
    {
        $materi = Materi::findOrFail($id);
        
        if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
            return Storage::disk('public')->download($materi->file_path, $materi->judul);
        }
        
        session()->flash('error', 'File tidak ditemukan.');
    }

    public function render()
    {
        $query = Materi::with('kursus');

        if ($this->selectedKursus) {
            $query->where('kursus_id', $this->selectedKursus);
        }

        if ($this->search) {
            $query->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
        }

        $materis = $query->orderBy('kursus_id')->orderBy('urutan')->paginate(10);
        $kursuses = Kursus::all();

        return view('livewire.materi-manager', compact('materis', 'kursuses'));
    }
}