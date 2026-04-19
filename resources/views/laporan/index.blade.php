@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold mb-1">Laporan Pemasukan</h3>
        <p class="text-muted mb-0">{{ $labelPeriode }}</p>
    </div>
    <a href="{{ route('laporan.print', request()->query()) }}" target="_blank" class="btn btn-primary">
        <i class="bi bi-printer me-1"></i>Cetak Laporan
    </a>
</div>

<div class="card card-soft mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Jenis Periode</label>
                <select name="periode" id="periodeType" class="form-select">
                    <option value="tanggal" {{ $periode === 'tanggal' ? 'selected' : '' }}>Per Tanggal</option>
                    <option value="minggu" {{ $periode === 'minggu' ? 'selected' : '' }}>Per Minggu</option>
                    <option value="bulan" {{ $periode === 'bulan' ? 'selected' : '' }}>Per Bulan</option>
                    <option value="tahun" {{ $periode === 'tahun' ? 'selected' : '' }}>Per Tahun</option>
                </select>
            </div>
            <div class="col-md-3 periode-field periode-tanggal {{ $periode !== 'tanggal' ? 'd-none' : '' }}">
                <label class="form-label">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" class="form-control" value="{{ $tanggalAwal }}">
            </div>
            <div class="col-md-3 periode-field periode-tanggal {{ $periode !== 'tanggal' ? 'd-none' : '' }}">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" class="form-control" value="{{ $tanggalAkhir }}">
            </div>
            <div class="col-md-3 periode-field periode-minggu {{ $periode !== 'minggu' ? 'd-none' : '' }}">
                <label class="form-label">Pilih Minggu</label>
                <input type="week" name="periode_minggu" class="form-control" value="{{ $periodeMinggu }}">
            </div>
            <div class="col-md-3 periode-field periode-bulan {{ $periode !== 'bulan' ? 'd-none' : '' }}">
                <label class="form-label">Pilih Bulan</label>
                <input type="month" name="periode_bulan" class="form-control" value="{{ $periodeBulan }}">
            </div>
            <div class="col-md-3 periode-field periode-tahun {{ $periode !== 'tahun' ? 'd-none' : '' }}">
                <label class="form-label">Pilih Tahun</label>
                <input type="number" min="2000" max="2100" name="tahun" class="form-control" value="{{ $inputTahun }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary w-100">Terapkan</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3"><div class="card card-soft"><div class="card-body"><small>Total Pemasukan</small><h5 class="mb-0">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h5></div></div></div>
    <div class="col-md-3"><div class="card card-soft"><div class="card-body"><small>Transaksi Lunas</small><h5 class="mb-0">{{ $jumlahTransaksiLunas }}</h5></div></div></div>
    <div class="col-md-3"><div class="card card-soft"><div class="card-body"><small>Potensi Tunggakan</small><h5 class="mb-0">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</h5></div></div></div>
    <div class="col-md-3"><div class="card card-soft"><div class="card-body"><small>Okupansi Kamar</small><h5 class="mb-0">{{ $okupansi }}%</h5></div></div></div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card card-soft h-100">
            <div class="card-header bg-white border-0 pt-3"><h6 class="mb-0 fw-semibold">Top 5 Kamar (Pemasukan)</h6></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Kamar</th><th>Transaksi</th><th>Total</th></tr></thead>
                        <tbody>
                        @forelse($pemasukanPerKamar as $item)
                            <tr>
                                <td>{{ $item['kamar'] }}</td>
                                <td>{{ $item['jumlah_transaksi'] }}</td>
                                <td>Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-muted text-center">Belum ada data.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-soft h-100">
            <div class="card-header bg-white border-0 pt-3"><h6 class="mb-0 fw-semibold">Detail Transaksi Lunas</h6></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Tanggal Bayar</th><th>Kamar</th><th>Periode</th><th>Nominal</th></tr></thead>
                        <tbody>
                        @forelse($transaksiLunas as $trx)
                            <tr>
                                <td>{{ $trx->paid_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td>{{ $trx->kamar?->nomor_kamar ?? '-' }}</td>
                                <td>{{ $trx->periode_bulan?->translatedFormat('F Y') }}</td>
                                <td>Rp {{ number_format($trx->nominal, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-muted text-center">Belum ada transaksi lunas pada periode ini.</td></tr>
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
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const periodSelect = document.getElementById('periodeType');
        const form = periodSelect.closest('form');
        const fields = {
            tanggal: document.querySelectorAll('.periode-field.periode-tanggal'),
            minggu: document.querySelectorAll('.periode-field.periode-minggu'),
            bulan: document.querySelectorAll('.periode-field.periode-bulan'),
            tahun: document.querySelectorAll('.periode-field.periode-tahun'),
        };

        const updateVisibility = () => {
            const value = periodSelect.value;
            Object.keys(fields).forEach((key) => {
                fields[key].forEach((field) => {
                    field.style.display = key === value ? '' : 'none';
                });
            });
        };

        const autoSubmit = () => {
            form.submit();
        };

        if (periodSelect) {
            periodSelect.addEventListener('change', () => {
                updateVisibility();
                autoSubmit();
            });
            updateVisibility();
        }

        // Auto-submit when any input field changes
        document.querySelectorAll('input[name="tanggal_awal"], input[name="tanggal_akhir"], input[name="periode_minggu"], input[name="periode_bulan"], input[name="tahun"]').forEach(input => {
            input.addEventListener('change', autoSubmit);
        });
    });
</script>
@endpush
