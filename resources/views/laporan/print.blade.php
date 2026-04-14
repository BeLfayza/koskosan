<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan - {{ $labelPeriode }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111827; margin: 24px; }
        h2, h4 { margin: 0 0 8px; }
        .muted { color: #6b7280; margin-bottom: 16px; }
        .grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin: 14px 0; }
        .card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; font-size: 12px; text-align: left; }
        th { background: #f9fafb; }
        .right { text-align: right; }
        .section { margin-top: 16px; }
        @media print { .no-print { display: none; } body { margin: 8px; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 14px;">
        <button onclick="window.print()">Print</button>
    </div>
    <h2>Laporan Keuangan Kos</h2>
    <div class="muted">
        Periode: {{ $labelPeriode }}<br>
        Dicetak: {{ now()->format('d M Y H:i') }}
    </div>

    <div class="grid">
        <div class="card"><small>Total Pemasukan</small><h4>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h4></div>
        <div class="card"><small>Transaksi Lunas</small><h4>{{ $jumlahTransaksiLunas }}</h4></div>
        <div class="card"><small>Potensi Tunggakan</small><h4>Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</h4></div>
        <div class="card"><small>Okupansi Kamar</small><h4>{{ $okupansi }}%</h4></div>
    </div>

    <div class="section">
        <h4>Top Kamar Berdasarkan Pemasukan</h4>
        <table>
            <thead>
                <tr><th>Kamar</th><th>Jumlah Transaksi</th><th class="right">Total</th></tr>
            </thead>
            <tbody>
                @forelse($pemasukanPerKamar as $item)
                    <tr>
                        <td>{{ $item['kamar'] }}</td>
                        <td>{{ $item['jumlah_transaksi'] }}</td>
                        <td class="right">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h4>Detail Transaksi Lunas</h4>
        <table>
            <thead>
                <tr>
                    <th>Tanggal Bayar</th>
                    <th>Kamar</th>
                    <th>Periode Bayar</th>
                    <th class="right">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksiLunas as $trx)
                    <tr>
                        <td>{{ $trx->paid_at?->format('d M Y H:i') ?? '-' }}</td>
                        <td>{{ $trx->kamar?->nomor_kamar ?? '-' }}</td>
                        <td>{{ $trx->periode_bulan?->translatedFormat('F Y') }}</td>
                        <td class="right">Rp {{ number_format($trx->nominal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">Tidak ada transaksi lunas pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
