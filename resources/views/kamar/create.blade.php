@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card card-soft">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-3">Tambah Kamar</h4>
                <form action="{{ route('kamar.store') }}" method="POST" class="d-grid gap-3">
                    @csrf
                    <div>
                        <label class="form-label">Nomor Kamar</label>
                        <input type="text" name="nomor_kamar" class="form-control @error('nomor_kamar') is-invalid @enderror" value="{{ old('nomor_kamar') }}" required>
                        @error('nomor_kamar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">Harga Per Bulan</label>
                        <input type="number" step="1000" min="0" name="harga_per_bulan" class="form-control @error('harga_per_bulan') is-invalid @enderror" value="{{ old('harga_per_bulan') }}" required>
                        @error('harga_per_bulan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary">Simpan</button>
                        <a href="{{ route('kamar.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
