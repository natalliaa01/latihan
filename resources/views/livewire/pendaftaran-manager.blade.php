{{-- resources/views/livewire/pendaftaran-manager.blade.php --}}
<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manajemen Pendaftaran</h2>
        <button class="btn btn-primary" wire:click="openModal">
            <i class="fas fa-plus"></i> Tambah Pendaftaran
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
            <input type="text" class="form-control" wire:model.live="search" placeholder="Cari peserta atau kursus...">
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Peserta</th>
                            <th>Kursus</th>
                            <th>Tanggal Daftar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendaftarans as $pendaftaran)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ $pendaftaran->peserta->nama }}</strong>
                                        <br><small class="text-muted">{{ $pendaftaran->peserta->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $pendaftaran->kursus->nama_kursus }}</strong>
                                        <br><small class="text-muted">{{ $pendaftaran->kursus->instruktur->nama }}</small>
                                    </div>
                                </td>
                                <td>{{ $pendaftaran->tanggal_daftar->format('d/m/Y') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <span class="badge {{ $pendaftaran->status_badge }} dropdown-toggle" 
                                              data-bs-toggle="dropdown" style="cursor: pointer;">
                                            {{ ucfirst($pendaftaran->status) }}
                                        </span>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" wire:click="updateStatus({{ $pendaftaran->id }}, 'pending')">Pending</a></li>
                                            <li><a class="dropdown-item" wire:click="updateStatus({{ $pendaftaran->id }}, 'diterima')">Diterima</a></li>
                                            <li><a class="dropdown-item" wire:click="updateStatus({{ $pendaftaran->id }}, 'ditolak')">Ditolak</a></li>
                                            <li><a class="dropdown-item" wire:click="updateStatus({{ $pendaftaran->id }}, 'selesai')">Selesai</a></li>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $pendaftaran->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" 
                                            wire:click="delete({{ $pendaftaran->id }})"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus pendaftaran ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $pendaftarans->links() }}
        </div>
    </div>

    {{-- Modal Pendaftaran --}}
    @if($showModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $isEdit ? 'Edit Pendaftaran' : 'Tambah Pendaftaran' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kursus *</label>
                                        <select class="form-select @error('kursus_id') is-invalid @enderror" wire:model="kursus_id">
                                            <option value="">Pilih Kursus</option>
                                            @foreach($kursuses as $kursus)
                                                <option value="{{ $kursus->id }}">{{ $kursus->nama_kursus }} - {{ $kursus->instruktur->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('kursus_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Peserta *</label>
                                        <div class="d-flex gap-2">
                                            <select class="form-select @error('peserta_id') is-invalid @enderror" wire:model="peserta_id">
                                                <option value="">Pilih Peserta</option>
                                                @foreach($pesertas as $peserta)
                                                    <option value="{{ $peserta->id }}">{{ $peserta->nama }} - {{ $peserta->email }}</option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-outline-success" wire:click="openPesertaModal">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        @error('peserta_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Daftar *</label>
                                        <input type="date" class="form-control @error('tanggal_daftar') is-invalid @enderror" 
                                               wire:model="tanggal_daftar">
                                        @error('tanggal_daftar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Status *</label>
                                        <select class="form-select @error('status') is-invalid @enderror" wire:model="status">
                                            <option value="pending">Pending</option>
                                            <option value="diterima">Diterima</option>
                                            <option value="ditolak">Ditolak</option>
                                            <option value="selesai">Selesai</option>
                                        </select>
                                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                          wire:model="catatan" rows="3" placeholder="Masukkan catatan tambahan"></textarea>
                                @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

    {{-- Modal Tambah Peserta --}}
    @if($showPesertaModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Peserta Baru</h5>
                        <button type="button" class="btn-close" wire:click="closePesertaModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="storePeserta">
                            <div class="mb-3">
                                <label class="form-label">Nama *</label>
                                <input type="text" class="form-control @error('peserta_nama') is-invalid @enderror" 
                                       wire:model="peserta_nama" placeholder="Masukkan nama peserta">
                                @error('peserta_nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control @error('peserta_email') is-invalid @enderror" 
                                       wire:model="peserta_email" placeholder="Masukkan email">
                                @error('peserta_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Telepon</label>
                                <input type="text" class="form-control @error('peserta_telepon') is-invalid @enderror" 
                                       wire:model="peserta_telepon" placeholder="Masukkan nomor telepon">
                                @error('peserta_telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea class="form-control @error('peserta_alamat') is-invalid @enderror" 
                                          wire:model="peserta_alamat" rows="2" placeholder="Masukkan alamat"></textarea>
                                @error('peserta_alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" wire:click="closePesertaModal">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-success">
                                    Simpan Peserta
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