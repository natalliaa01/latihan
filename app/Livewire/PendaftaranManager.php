<?php
// app/Livewire/PendaftaranManager.php

namespace App\Livewire;

use App\Models\Pendaftaran;
use App\Models\Kursus;
use App\Models\Peserta;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\QueryException;

class PendaftaranManager extends Component
{
    use WithPagination;

    public $kursus_id, $peserta_id, $status = 'pending', $tanggal_daftar, $catatan;
    public $pendaftaran_id;
    public $isEdit = false;
    public $showModal = false;
    public $showPesertaModal = false;
    public $search = '';
    
    // Data untuk peserta baru
    public $peserta_nama, $peserta_email, $peserta_telepon, $peserta_alamat;

    protected $rules = [
        'kursus_id' => 'required|exists:kursuses,id',
        'peserta_id' => 'required|exists:pesertas,id',
        'status' => 'required|in:pending,diterima,ditolak,selesai',
        'tanggal_daftar' => 'required|date',
        'catatan' => 'nullable|string'
    ];

    protected $rulesPeserta = [
        'peserta_nama' => 'required|min:3',
        'peserta_email' => 'required|email|unique:pesertas,email',
        'peserta_telepon' => 'nullable|string',
        'peserta_alamat' => 'nullable|string'
    ];

    public function mount()
    {
        $this->tanggal_daftar = now()->format('Y-m-d');
    }

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

    public function openPesertaModal()
    {
        $this->resetPesertaForm();
        $this->showPesertaModal = true;
    }

    public function closePesertaModal()
    {
        $this->showPesertaModal = false;
        $this->resetPesertaForm();
    }

    public function resetForm()
    {
        $this->kursus_id = '';
        $this->peserta_id = '';
        $this->status = 'pending';
        $this->tanggal_daftar = now()->format('Y-m-d');
        $this->catatan = '';
        $this->pendaftaran_id = null;
        $this->isEdit = false;
        $this->resetValidation();
    }

    public function resetPesertaForm()
    {
        $this->peserta_nama = '';
        $this->peserta_email = '';
        $this->peserta_telepon = '';
        $this->peserta_alamat = '';
        $this->resetValidation(['peserta_nama', 'peserta_email', 'peserta_telepon', 'peserta_alamat']);
    }

    public function storePeserta()
    {
        $this->validate($this->rulesPeserta);

        $peserta = Peserta::create([
            'nama' => $this->peserta_nama,
            'email' => $this->peserta_email,
            'telepon' => $this->peserta_telepon,
            'alamat' => $this->peserta_alamat
        ]);

        $this->peserta_id = $peserta->id;
        session()->flash('message', 'Peserta baru berhasil ditambahkan.');
        $this->closePesertaModal();
    }

    public function store()
    {
        $this->validate();

        try {
            Pendaftaran::create([
                'kursus_id' => $this->kursus_id,
                'peserta_id' => $this->peserta_id,
                'status' => $this->status,
                'tanggal_daftar' => $this->tanggal_daftar,
                'catatan' => $this->catatan
            ]);

            session()->flash('message', 'Pendaftaran berhasil ditambahkan.');
            $this->closeModal();
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry error
                session()->flash('error', 'Peserta sudah terdaftar di kursus ini.');
            } else {
                session()->flash('error', 'Terjadi kesalahan saat menyimpan data.');
            }
        }
    }

    public function edit($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $this->pendaftaran_id = $id;
        $this->kursus_id = $pendaftaran->kursus_id;
        $this->peserta_id = $pendaftaran->peserta_id;
        $this->status = $pendaftaran->status;
        $this->tanggal_daftar = $pendaftaran->tanggal_daftar->format('Y-m-d');
        $this->catatan = $pendaftaran->catatan;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate();

        $pendaftaran = Pendaftaran::findOrFail($this->pendaftaran_id);
        $pendaftaran->update([
            'kursus_id' => $this->kursus_id,
            'peserta_id' => $this->peserta_id,
            'status' => $this->status,
            'tanggal_daftar' => $this->tanggal_daftar,
            'catatan' => $this->catatan
        ]);

        session()->flash('message', 'Pendaftaran berhasil diupdate.');
        $this->closeModal();
    }

    public function delete($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->delete();
        session()->flash('message', 'Pendaftaran berhasil dihapus.');
    }

    public function updateStatus($id, $status)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update(['status' => $status]);
        session()->flash('message', 'Status pendaftaran berhasil diupdate.');
    }

    public function render()
    {
        $pendaftarans = Pendaftaran::with(['kursus', 'peserta'])
            ->when($this->search, function($query) {
                $query->whereHas('peserta', function($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                })->orWhereHas('kursus', function($q) {
                    $q->where('nama_kursus', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $kursuses = Kursus::where('status', 'aktif')->get();
        $pesertas = Peserta::all();

        return view('livewire.pendaftaran-manager', compact('pendaftarans', 'kursuses', 'pesertas'));
    }
}