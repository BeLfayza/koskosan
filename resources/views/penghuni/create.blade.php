@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-soft">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-3">Tambah Penghuni</h4>
                <form action="{{ route('penghuni.store') }}" method="POST" class="row g-3" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NIK</label>
                        <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik') }}" required>
                        @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No HP</label>
                        <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp') }}" required>
                        @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" class="form-control @error('tanggal_masuk') is-invalid @enderror" value="{{ old('tanggal_masuk') }}" required>
                        @error('tanggal_masuk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Selesai Ngekos</label>
                        <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai') }}" required>
                        @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Pilih Kamar</label>
                        <select name="kamar_id" class="form-select @error('kamar_id') is-invalid @enderror">
                            <option value="">-- Belum ditempatkan --</option>
                            @foreach ($kamars as $kamar)
                                <option value="{{ $kamar->id }}" @selected(old('kamar_id') == $kamar->id)>
                                    {{ $kamar->nomor_kamar }} ({{ $kamar->status === 'terisi' ? 'Terisi' : 'Tidak Terisi' }})
                                </option>
                            @endforeach
                        </select>
                        @error('kamar_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Foto KTP</label>
                        <input type="file" name="foto_ktp" class="form-control @error('foto_ktp') is-invalid @enderror" accept="image/*">
                        @error('foto_ktp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Foto KK</label>
                        <input type="file" name="foto_kk" class="form-control @error('foto_kk') is-invalid @enderror" accept="image/*">
                        @error('foto_kk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Foto Diri</label>
                        <input type="file" name="foto_diri" class="form-control @error('foto_diri') is-invalid @enderror" accept="image/*">
                        @error('foto_diri') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <button class="btn btn-primary">Simpan</button>
                        <a href="{{ route('penghuni.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
