<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Pembayaran;
use App\Models\Penghuni;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PenghuniController extends Controller
{
    public function index()
    {
        $search = request('q');

        $penghunis = Penghuni::with('kamar')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")
                        ->orWhere('no_hp', 'like', "%{$search}%")
                        ->orWhereHas('kamar', function ($k) use ($search) {
                            $k->where('nomor_kamar', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('penghuni.index', compact('penghunis'));
    }

    public function create()
    {
        $kamars = Kamar::orderBy('nomor_kamar')->get();

        return view('penghuni.create', compact('kamars'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'max:150'],
            'nik' => ['required', 'digits_between:8,20', 'unique:penghunis,nik'],
            'no_hp' => ['required', 'max:20'],
            'tanggal_masuk' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_masuk'],
            'kamar_id' => ['nullable', 'exists:kamars,id'],
        ]);

        $penghuni = Penghuni::create($validated);
        $this->syncKamarStatus($penghuni->kamar);
        $this->syncPembayaranKamar($penghuni->kamar);

        return redirect()->route('penghuni.index')->with('success', 'Penghuni berhasil ditambahkan.');
    }

    public function edit(Penghuni $penghuni)
    {
        $kamars = Kamar::orderBy('nomor_kamar')->get();

        return view('penghuni.edit', compact('penghuni', 'kamars'));
    }

    public function update(Request $request, Penghuni $penghuni)
    {
        $oldKamar = $penghuni->kamar;

        $validated = $request->validate([
            'nama' => ['required', 'max:150'],
            'nik' => [
                'required',
                'digits_between:8,20',
                Rule::unique('penghunis', 'nik')->ignore($penghuni->id),
            ],
            'no_hp' => ['required', 'max:20'],
            'tanggal_masuk' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_masuk'],
            'kamar_id' => ['nullable', 'exists:kamars,id'],
        ]);

        $penghuni->update($validated);
        $this->syncKamarStatus($oldKamar);
        $this->syncPembayaranKamar($oldKamar);
        $penghuni = $penghuni->fresh();
        $this->syncKamarStatus($penghuni->kamar);
        $this->syncPembayaranKamar($penghuni->kamar);

        return redirect()->route('penghuni.index')->with('success', 'Data penghuni berhasil diperbarui.');
    }

    public function destroy(Penghuni $penghuni)
    {
        $kamar = $penghuni->kamar;
        $penghuni->delete();

        $this->syncKamarStatus($kamar);
        $this->syncPembayaranKamar($kamar);

        return redirect()->route('penghuni.index')->with('success', 'Penghuni berhasil dihapus.');
    }

    private function syncKamarStatus(?Kamar $kamar): void
    {
        if (! $kamar) {
            return;
        }

        $status = $kamar->penghuni()->exists() ? 'terisi' : 'tidak_terisi';
        $kamar->update(['status' => $status]);
    }

    private function syncPembayaranKamar(?Kamar $kamar): void
    {
        if (! $kamar) {
            return;
        }

        $penghuniAktif = $kamar->penghuni()
            ->whereNotNull('tanggal_masuk')
            ->whereNotNull('tanggal_selesai')
            ->get(['tanggal_masuk', 'tanggal_selesai']);

        // Jika kamar sudah tidak punya penghuni, reset semua pembayaran agar tidak menyisakan data lama.
        if ($penghuniAktif->isEmpty()) {
            $kamar->pembayarans()->delete();
            return;
        }

        $periodeValid = [];
        foreach ($penghuniAktif as $item) {
            $start = Carbon::parse($item->tanggal_masuk)->startOfMonth();
            $end = Carbon::parse($item->tanggal_selesai)->startOfMonth();

            while ($start->lte($end)) {
                $periodeValid[$start->toDateString()] = true;
                $start->addMonth();
            }
        }

        foreach (array_keys($periodeValid) as $periode) {
            Pembayaran::firstOrCreate(
                [
                    'kamar_id' => $kamar->id,
                    'periode_bulan' => $periode,
                ],
                [
                    'nominal' => $kamar->harga_per_bulan,
                    'status' => 'tidak_lunas',
                ]
            );
        }

        $kamar->pembayarans()
            ->whereNotIn('periode_bulan', array_keys($periodeValid))
            ->delete();

        $kamar->pembayarans()
            ->where('status', 'tidak_lunas')
            ->update(['nominal' => $kamar->harga_per_bulan]);
    }
}
