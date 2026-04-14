@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold mb-1">Catatan Pembayaran</h3>
        <p class="text-muted mb-0">Pilih kamar untuk melihat dan mengubah status pembayaran bulanan.</p>
    </div>
</div>

<form method="GET" class="card card-soft mb-3">
    <div class="card-body d-flex gap-2">
        <input type="text" name="q" class="form-control" placeholder="Cari nomor kamar..." value="{{ request('q') }}">
        <button class="btn btn-outline-primary">Cari</button>
    </div>
</form>

<div class="card card-soft">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Kamar</th>
                        <th>Harga/Bulan</th>
                        <th>Lunas</th>
                        <th>Tidak Lunas</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($kamars as $kamar)
                    <tr>
                        <td>{{ $kamar->nomor_kamar }}</td>
                        <td>Rp {{ number_format($kamar->harga_per_bulan, 0, ',', '.') }}</td>
                        <td><span class="badge text-bg-success">{{ $kamar->total_lunas }}</span></td>
                        <td><span class="badge text-bg-danger">{{ $kamar->total_tidak_lunas }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('pembayaran.show', $kamar) }}" class="btn btn-sm btn-primary">
                                Catat Pembayaran
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data pembayaran.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
