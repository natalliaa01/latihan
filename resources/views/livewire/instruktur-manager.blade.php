{{-- resources/views/livewire/instruktur-manager.blade.php --}}
<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manajemen Instruktur</h2>
        <button class="btn btn-primary" wire:click="openModal">
            <i class="fas fa-plus"></i> Tambah Instruktur
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
            <input type="text" class="form-control" wire:model.live="search" placeholder="Cari instruktur...">
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Keahlian</th>
                            <th>Total Kursus</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($instrukturs as $instruktur)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ $instruktur->nama }}</strong>
                                        @if($instruktur->bio)
                                            <br><small class="text-muted">{{ Str::limit($instruktur->bio, 50) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $instruktur->email }}</td>
                                <td>
                                    @if($instruktur->keahlian)
                                        <span class="badge bg-info">{{ $instruktur->keahlian }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $instruktur->kursuses_count }}</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $instruktur->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" 
                                            wire:click="delete({{ $instruktur->id }})"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus instruktur ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $instrukturs->links() }}
        </div>
    </div>

    {{-- Modal --}}
    @if($showModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $isEdit ? 'Edit Instruktur' : 'Tambah Instruktur' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                            <div class="mb-3">
                                <label class="form-label">Nama *</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                       wire:model="nama" placeholder="Masukkan nama instruktur">
                                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       wire:model="email" placeholder="Masukkan email">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Keahlian</label>
                                <input type="text" class="form-control @error('keahlian') is-invalid @enderror" 
                                       wire:model="keahlian" placeholder="Contoh: Laravel, PHP, JavaScript">
                                @error('keahlian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Bio</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" 
                                          wire:model="bio" rows="3" placeholder="Masukkan bio instruktur"></textarea>
                                @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
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