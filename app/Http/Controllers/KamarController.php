<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KamarController extends Controller
{
    public function index()
    {
        $search = request('q');
        $kamars = Kamar::withCount('penghuni')
            ->when($search, function ($query) use ($search) {
                $query->where('nomor_kamar', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('kamar.index', compact('kamars'));
    }

    public function create()
    {
        return view('kamar.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_kamar' => ['required', 'max:50', 'unique:kamars,nomor_kamar'],
            'harga_per_bulan' => ['required', 'numeric', 'min:0'],
        ]);

        Kamar::create([
            'nomor_kamar' => $validated['nomor_kamar'],
            'harga_per_bulan' => $validated['harga_per_bulan'],
            'status' => 'tidak_terisi',
        ]);

        return redirect()->route('kamar.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function edit(Kamar $kamar)
    {
        return view('kamar.edit', compact('kamar'));
    }

    public function update(Request $request, Kamar $kamar)
    {
        $validated = $request->validate([
            'nomor_kamar' => [
                'required',
                'max:50',
                Rule::unique('kamars', 'nomor_kamar')->ignore($kamar->id),
            ],
            'harga_per_bulan' => ['required', 'numeric', 'min:0'],
        ]);

        $kamar->update($validated);
        $kamar->pembayarans()->where('status', 'tidak_lunas')->update([
            'nominal' => $kamar->harga_per_bulan,
        ]);

        return redirect()->route('kamar.index')->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function destroy(Kamar $kamar)
    {
        if ($kamar->penghuni()->exists()) {
            return back()->with('error', 'Kamar tidak bisa dihapus karena masih memiliki penghuni.');
        }

        $kamar->delete();

        return redirect()->route('kamar.index')->with('success', 'Kamar berhasil dihapus.');
    }
}
