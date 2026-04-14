@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold mb-1">Data Kamar</h3>
        <p class="text-muted mb-0">Kelola nomor kamar dan status keterisian.</p>
    </div>
    <a href="{{ route('kamar.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Kamar</a>
</div>

<form method="GET" class="card card-soft mb-3">
    <div class="card-body d-flex gap-2">
        <input type="text" name="q" class="form-control" placeholder="Cari nomor kamar / status..." value="{{ request('q') }}">
        <button class="btn btn-outline-primary">Cari</button>
    </div>
</form>

<div class="card card-soft">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Nomor Kamar</th>
                        <th>Harga/Bulan</th>
                        <th>Status</th>
                        <th>Jumlah Penghuni</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kamars as $kamar)
                        <tr>
                            <td>{{ $kamar->nomor_kamar }}</td>
                            <td>Rp {{ number_format($kamar->harga_per_bulan, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $kamar->status === 'terisi' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ $kamar->status === 'terisi' ? 'Terisi' : 'Tidak Terisi' }}
                                </span>
                            </td>
                            <td>{{ $kamar->penghuni_count }}</td>
                            <td class="text-end">
                                <a href="{{ route('kamar.edit', $kamar) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('kamar.destroy', $kamar) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus kamar ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada data kamar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $kamars->links() }}
    </div>
</div>
@endsection
