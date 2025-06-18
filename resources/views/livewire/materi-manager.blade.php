{{-- resources/views/livewire/materi-manager.blade.php --}}
<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manajemen Materi</h2>
        <button class="btn btn-primary" wire:click="openModal">
            <i class="fas fa-plus"></i> Tambah Materi
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

    {{-- Filter --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" class="form-control" wire:model.live="search" placeholder="Cari materi...">
        </div>
        <div class="col-md-4">
            <select class="form-select" wire:model.live="filterKursus">
                <option value="">Semua Kursus</option>
                @foreach($kursuses as $kursus)
                    <option value="{{ $kursus->id }}">{{ $kursus->nama_kursus }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Materi Cards --}}
    <div class="row">
        @foreach($materis as $materi)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-book-open text-primary"></i>
                            {{ $materi->judul }}
                        </h6>
                        <small class="text-muted">{{ $materi->kursus->nama_kursus }}</small>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ Str::limit($materi->deskripsi, 120) }}</p>
                        
                        @if($materi->file_path)
                            <div class="mb-2">
                                <i class="fas fa-file text-info"></i>
                                <a href="{{ Storage::url($materi->file_path) }}" target="_blank" class="text-decoration-none">
                                    {{ basename($materi->file_path) }}
                                </a>
                            </div>
                        @endif

                        @if($materi->url_video)
                            <div class="mb-2">
                                <i class="fas fa-video text-danger"></i>
                                <a href="{{ $materi->url_video }}" target="_blank" class="text-decoration-none">
                                    Video Pembelajaran
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-calendar"></i>
                                {{ $materi->created_at->format('d/m/Y') }}
                            </small>
                            <div>
                                <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $materi->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" 
                                        wire:click="delete({{ $materi->id }})"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{ $materis->links() }}

    {{-- Modal Materi --}}
    @if($showModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $isEdit ? 'Edit Materi' : 'Tambah Materi' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
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

                            <div class="mb-3">
                                <label class="form-label">Judul Materi *</label>
                                <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                       wire:model="judul" placeholder="Masukkan judul materi">
                                @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi *</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                          wire:model="deskripsi" rows="4" placeholder="Masukkan deskripsi materi"></textarea>
                                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Upload File</label>
                                <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                       wire:model="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx">
                                @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                
                                @if($file)
                                    <div class="mt-2">
                                        <small class="text-success">
                                            <i class="fas fa-check"></i> File dipilih: {{ $file->getClientOriginalName() }}
                                        </small>
                                    </div>
                                @endif

                                @if($isEdit && $existing_file)
                                    <div class="mt-2">
                                        <small class="text-info">
                                            <i class="fas fa-file"></i> File saat ini: {{ basename($existing_file) }}
                                        </small>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">URL Video (Optional)</label>
                                <input type="url" class="form-control @error('url_video') is-invalid @enderror" 
                                       wire:model="url_video" placeholder="https://youtube.com/watch?v=...">
                                @error('url_video') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <small class="text-muted">Masukkan link YouTube, Vimeo, atau platform video lainnya</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Urutan</label>
                                <input type="number" class="form-control @error('urutan') is-invalid @enderror" 
                                       wire:model="urutan" min="1" placeholder="Urutan materi">
                                @error('urutan') <div class="invalid-feedback">{{ $message }}</div> @enderror
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