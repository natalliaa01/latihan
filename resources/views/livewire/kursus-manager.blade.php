{{-- resources/views/livewire/kursus-manager.blade.php --}}
<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manajemen Kursus</h2>
        <button class="btn btn-primary" wire:click="openModal">
            <i class="fas fa-plus"></i> Tambah Kursus
        </button>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Search --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" class="form-control" wire:model.live="search" placeholder="Cari kursus atau instruktur...">
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Kursus</th>
                            <th>Instruktur</th>
                            <th>Durasi</th>
                            <th>Biaya</th>
                            <th>Peserta</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kursuses as $kursus)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ $kursus->nama_kursus }}</strong>
                                        @if($kursus->deskripsi)
                                            <br><small class="text-muted">{{ Str::limit($kursus->deskripsi, 50) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $kursus->instruktur->nama }}</td>
                                <td>{{ $kursus->durasi }} jam</td>
                                <td>{{ $kursus->format_biaya }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $kursus->total_peserta }}</span>
                                </td>
                                <td>
                                    @if($kursus->status == 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $kursus->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" 
                                            wire:click="delete({{ $kursus->id }})"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus kursus ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $kursuses->links() }}
        </div>
    </div>

    {{-- Modal --}}
    @if($showModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $isEdit ? 'Edit Kursus' : 'Tambah Kursus' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Kursus *</label>
                                        <input type="text" class="form-control @error('nama_kursus') is-invalid @enderror" 
                                               wire:model="nama_kursus" placeholder="Masukkan nama kursus">
                                        @error('nama_kursus') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Durasi (jam) *</label>
                                        <input type="number" class="form-control @error('durasi') is-invalid @enderror" 
                                               wire:model="durasi" placeholder="Contoh: 40">
                                        @error('durasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Instruktur *</label>
                                        <select class="form-select @error('instruktur_id') is-invalid @enderror" wire:model="instruktur_id">
                                            <option value="">Pilih Instruktur</option>
                                            @foreach($instrukturs as $instruktur)
                                                <option value="{{ $instruktur->id }}">{{ $instruktur->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('instruktur_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Biaya *</label>
                                        <input type="number" class="form-control @error('biaya') is-invalid @enderror" 
                                               wire:model="biaya" placeholder="Contoh: 500000">
                                        @error('biaya') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status *</label>
                                <select class="form-select @error('status') is-invalid @enderror" wire:model="status">
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                          wire:model="deskripsi" rows="3" placeholder="Masukkan deskripsi kursus"></textarea>
                                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" wire:click="closeModal">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    {{ $isEdit ? 'Update' : 'Simpan' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>