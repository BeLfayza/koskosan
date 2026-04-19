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
        $periode = $request->query('periode', 'tahun');
        $periode = in_array($periode, ['tanggal', 'minggu', 'bulan', 'tahun']) ? $periode : 'tahun';
        $tanggalAwal = $request->query('tanggal_awal', now()->format('Y-m-d'));
        $tanggalAkhir = $request->query('tanggal_akhir', now()->format('Y-m-d'));
        $periodeMinggu = $request->query('periode_minggu', now()->format('o-\WW'));
        $periodeBulan = $request->query('periode_bulan', now()->format('Y-m'));
        $yearInput = (int) $request->query('tahun', now()->year);

        switch ($periode) {
            case 'tanggal':
                $start = Carbon::parse($tanggalAwal)->startOfDay();
                $end = Carbon::parse($tanggalAkhir)->endOfDay();
                if ($end->lt($start)) {
                    $end = $start->copy()->endOfDay();
                }
                $labelPeriode = 'Tanggal ' . $start->format('d M Y') . ' - ' . $end->format('d M Y');
                break;
            case 'minggu':
                if (preg_match('/^(?<year>\d{4})-W(?<week>\d{2})$/', $periodeMinggu, $matches)) {
                    $start = Carbon::now()->setISODate((int) $matches['year'], (int) $matches['week'])->startOfWeek();
                } else {
                    $start = Carbon::now()->startOfWeek();
                    $periodeMinggu = $start->format('o-\WW');
                }
                $end = $start->copy()->endOfWeek();
                $labelPeriode = 'Minggu ' . $start->format('d M Y') . ' - ' . $end->format('d M Y');
                break;
            case 'bulan':
                try {
                    $start = Carbon::createFromFormat('Y-m', $periodeBulan)->startOfMonth();
                } catch (\Exception $e) {
                    $start = now()->startOfMonth();
                    $periodeBulan = $start->format('Y-m');
                }
                $end = $start->copy()->endOfMonth();
                $labelPeriode = 'Bulan ' . $start->translatedFormat('F Y');
                break;
            default:
                $start = Carbon::create($yearInput, 1, 1)->startOfDay();
                $end = Carbon::create($yearInput, 12, 31)->endOfDay();
                $labelPeriode = 'Tahunan - ' . $yearInput;
                break;
        }

        $transaksiLunas = Pembayaran::with('kamar')
            ->where('status', 'lunas')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('paid_at', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->whereNull('paid_at')
                            ->whereBetween('updated_at', [$start, $end]);
                    });
            })
            ->orderByRaw('COALESCE(paid_at, updated_at) ASC')
            ->get();

        $totalPemasukan = (float) $transaksiLunas->sum('nominal');
        $jumlahTransaksiLunas = $transaksiLunas->count();

        $periodeStart = $start->copy()->startOfMonth()->toDateString();
        $periodeEnd = $end->copy()->startOfMonth()->toDateString();
        $totalTunggakan = (float) Pembayaran::where('status', 'tidak_lunas')
            ->whereBetween('periode_bulan', [$periodeStart, $periodeEnd])
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
            'periode' => $periode,
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'periodeMinggu' => $periodeMinggu,
            'periodeBulan' => $periodeBulan,
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
