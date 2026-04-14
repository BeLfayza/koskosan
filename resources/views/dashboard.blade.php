@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Dashboard Manajemen Kos</h2>
        <p class="text-muted mb-0">Ringkasan kondisi kamar dan penghuni saat ini.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stats-card p-3" style="background: linear-gradient(135deg,#6366f1,#4f46e5);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small>Total Kamar</small>
                    <h3>{{ $totalKamar }}</h3>
                </div>
                <i class="bi bi-door-open icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card p-3" style="background: linear-gradient(135deg,#10b981,#059669);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small>Kamar Terisi</small>
                    <h3>{{ $kamarTerisi }}</h3>
                </div>
                <i class="bi bi-house-check icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card p-3" style="background: linear-gradient(135deg,#f59e0b,#d97706);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small>Kamar Kosong</small>
                    <h3>{{ $kamarKosong }}</h3>
                </div>
                <i class="bi bi-house-dash icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card p-3" style="background: linear-gradient(135deg,#ec4899,#db2777);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small>Total Penghuni</small>
                    <h3>{{ $totalPenghuni }}</h3>
                </div>
                <i class="bi bi-people-fill icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">
        <div class="card card-soft">
            <div class="card-header bg-white border-0 pt-3">
                <h5 class="mb-0 fw-semibold">Grafik Pendapatan Lunas per Bulan</h5>
            </div>
            <div class="card-body">
                <canvas id="pendapatanChart" height="90"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-soft">
            <div class="card-header bg-white border-0 pt-3">
                <h5 class="mb-0 fw-semibold">Penghuni Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kamar</th>
                                <th>Tgl Masuk</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($penghuniBaru as $item)
                            <tr>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->kamar?->nomor_kamar ?? '-' }}</td>
                                <td>{{ $item->tanggal_masuk?->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">Belum ada data penghuni.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-soft">
            <div class="card-header bg-white border-0 pt-3">
                <h5 class="mb-0 fw-semibold">Kamar Paling Padat</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Nomor Kamar</th>
                                <th>Status</th>
                                <th>Jumlah Penghuni</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($kamarPalingPadat as $kamar)
                            <tr>
                                <td>{{ $kamar->nomor_kamar }}</td>
                                <td>
                                    <span class="badge {{ $kamar->status === 'terisi' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        {{ $kamar->status === 'terisi' ? 'Terisi' : 'Tidak Terisi' }}
                                    </span>
                                </td>
                                <td>{{ $kamar->penghuni_count }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">Belum ada data kamar.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labelsPendapatan = @json($labelsPendapatan);
    const dataPendapatan = @json($dataPendapatan);

    const ctx = document.getElementById('pendapatanChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelsPendapatan,
                datasets: [{
                    label: 'Pendapatan Lunas',
                    data: dataPendapatan,
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.15)',
                    fill: true,
                    tension: 0.25
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
</script>
@endpush
