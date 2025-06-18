{{-- resources/views/livewire/dashboard.blade.php --}}
<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Dashboard Kursus</h2>
        <div class="text-muted">
            {{ now()->format('d F Y') }}
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ $stats['total_instruktur'] }}</h4>
                            <p class="card-text">Total Instruktur</p>
                        </div>
                        <i class="fas fa-chalkboard-teacher fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ $stats['total_kursus'] }}</h4>
                            <p class="card-text">Total Kursus</p>
                        </div>
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ $stats['total_peserta'] }}</h4>
                            <p class="card-text">Total Peserta</p>
                        </div>
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ $stats['total_pendaftaran'] }}</h4>
                            <p class="card-text">Total Pendaftaran</p>
                        </div>
                        <i class="fas fa-clipboard-list fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Jumlah Peserta per Kursus --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Jumlah Peserta per Kursus</h5>
                </div>
                <div class="card-body">
                    @if($pesertaPerKursus->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Kursus</th>
                                        <th>Instruktur</th>
                                        <th>Jumlah Peserta</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pesertaPerKursus->take(10) as $item)
                                        <tr>
                                            <td>{{ $item['nama_kursus'] }}</td>
                                            <td>{{ $item['instruktur'] }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $item['total_peserta'] }}</span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    @php
                                                        $maxPeserta = $pesertaPerKursus->max('total_peserta');
                                                        $percentage = $maxPeserta > 0 ? ($item['total_peserta'] / $maxPeserta) * 100 : 0;
                                                    @endphp
                                                    <div class="progress-bar bg-success" 
                                                         style="width: {{ $percentage }}%">
                                                        {{ number_format($percentage, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada data kursus</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kursus per Instruktur --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Kursus per Instruktur</h5>
                </div>
                <div class="card-body">
                    @if($kursusPerInstruktur->count() > 0)
                        @foreach($kursusPerInstruktur as $item)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-0">{{ $item['instruktur'] }}</h6>
                                    <small class="text-muted">{{ $item['total_kursus'] }} kursus</small>
                                </div>
                                <span class="badge bg-secondary">{{ $item['total_kursus'] }}</span>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada data instruktur</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>