<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Pembayaran;
use App\Models\Penghuni;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKamar = Kamar::count();
        $kamarTerisi = Kamar::where('status', 'terisi')->count();
        $kamarKosong = Kamar::where('status', 'tidak_terisi')->count();
        $totalPenghuni = Penghuni::count();
        $penghuniBaru = Penghuni::with('kamar')->latest('tanggal_masuk')->take(5)->get();
        $kamarPalingPadat = Kamar::withCount('penghuni')
            ->orderByDesc('penghuni_count')
            ->take(5)
            ->get();
        $pendapatanBulanan = Pembayaran::select(
            DB::raw("DATE_FORMAT(periode_bulan, '%Y-%m') as bulan"),
            DB::raw('SUM(nominal) as total')
        )
            ->where('status', 'lunas')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $labelsPendapatan = $pendapatanBulanan->pluck('bulan')->toArray();
        $dataPendapatan = $pendapatanBulanan->pluck('total')->map(fn ($item) => (float) $item)->toArray();

        return view('dashboard', compact(
            'totalKamar',
            'kamarTerisi',
            'kamarKosong',
            'totalPenghuni',
            'penghuniBaru',
            'kamarPalingPadat',
            'labelsPendapatan',
            'dataPendapatan'
        ));
    }
}
