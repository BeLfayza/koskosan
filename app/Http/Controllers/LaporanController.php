<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $report = $this->buildReport($request);

        return view('laporan.index', $report);
    }

    public function print(Request $request)
    {
        $report = $this->buildReport($request);

        return view('laporan.print', $report);
    }

    private function buildReport(Request $request): array
    {
        $yearInput = (int) $request->query('tahun', now()->year);
        $start = Carbon::create($yearInput, 1, 1)->startOfDay();
        $end = Carbon::create($yearInput, 12, 31)->endOfDay();
        $labelPeriode = 'Tahunan - '.$yearInput;

        $transaksiLunas = Pembayaran::with('kamar')
            ->where('status', 'lunas')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('paid_at', [$start, $end])
                    // Fallback untuk data lunas lama yang belum punya paid_at.
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->whereNull('paid_at')
                            ->whereBetween('updated_at', [$start, $end]);
                    });
            })
            ->orderByRaw('COALESCE(paid_at, updated_at) ASC')
            ->get();

        $totalPemasukan = (float) $transaksiLunas->sum('nominal');
        $jumlahTransaksiLunas = $transaksiLunas->count();

        $monthRangeStart = $start->copy()->startOfMonth()->toDateString();
        $monthRangeEnd = $end->copy()->startOfMonth()->toDateString();
        $totalTunggakan = (float) Pembayaran::where('status', 'tidak_lunas')
            ->whereBetween('periode_bulan', [$monthRangeStart, $monthRangeEnd])
            ->sum('nominal');

        $totalKamar = Kamar::count();
        $kamarTerisi = Kamar::where('status', 'terisi')->count();
        $okupansi = $totalKamar > 0 ? round(($kamarTerisi / $totalKamar) * 100, 2) : 0;

        $pemasukanPerKamar = $transaksiLunas
            ->groupBy('kamar_id')
            ->map(function ($items) {
                return [
                    'kamar' => optional($items->first()->kamar)->nomor_kamar ?? '-',
                    'total' => (float) $items->sum('nominal'),
                    'jumlah_transaksi' => $items->count(),
                ];
            })
            ->sortByDesc('total')
            ->take(5)
            ->values();

        return [
            'inputTahun' => $yearInput,
            'labelPeriode' => $labelPeriode,
            'start' => $start,
            'end' => $end,
            'transaksiLunas' => $transaksiLunas,
            'totalPemasukan' => $totalPemasukan,
            'jumlahTransaksiLunas' => $jumlahTransaksiLunas,
            'totalTunggakan' => $totalTunggakan,
            'totalKamar' => $totalKamar,
            'kamarTerisi' => $kamarTerisi,
            'okupansi' => $okupansi,
            'pemasukanPerKamar' => $pemasukanPerKamar,
        ];
    }
}
