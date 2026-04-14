<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');
        $kamars = Kamar::withCount([
            'pembayarans as total_lunas' => fn ($query) => $query->where('status', 'lunas'),
            'pembayarans as total_tidak_lunas' => fn ($query) => $query->where('status', 'tidak_lunas'),
        ])
            ->when($search, function ($query) use ($search) {
                $query->where('nomor_kamar', 'like', "%{$search}%");
            })
            ->orderBy('nomor_kamar')
            ->get();

        return view('pembayaran.index', [
            'kamars' => $kamars,
        ]);
    }

    public function show(Kamar $kamar)
    {
        $pembayarans = $kamar->pembayarans()
            ->orderBy('periode_bulan')
            ->get()
            ->groupBy(fn ($item) => Carbon::parse($item->periode_bulan)->format('Y'));

        return view('pembayaran.show', [
            'kamar' => $kamar,
            'pembayaransByYear' => $pembayarans,
        ]);
    }

    public function toggle(Pembayaran $pembayaran)
    {
        $nextStatus = $pembayaran->status === 'lunas' ? 'tidak_lunas' : 'lunas';

        $pembayaran->update([
            'status' => $nextStatus,
            'paid_at' => $nextStatus === 'lunas' ? now() : null,
        ]);

        return back()->with('success', 'Status pembayaran berhasil diubah.');
    }
}
