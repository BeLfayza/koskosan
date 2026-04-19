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
                        <input id="hargaPerBulan" type="text" inputmode="numeric" name="harga_per_bulan" class="form-control @error('harga_per_bulan') is-invalid @enderror" value="{{ old('harga_per_bulan') }}" required>
                        @error('harga_per_bulan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="invalid-feedback d-none" id="hargaPerBulanFeedback">Teks yang dimasukkan harus angka.</div>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const hargaInput = document.getElementById('hargaPerBulan');
        const feedback = document.getElementById('hargaPerBulanFeedback');

        if (!hargaInput) {
            return;
        }

        const validateHarga = () => {
            const value = hargaInput.value.trim();
            const valid = /^\d*$/.test(value);

            if (!valid && value !== '') {
                hargaInput.classList.add('is-invalid');
                feedback.classList.remove('d-none');
                hargaInput.value = value.replace(/[^0-9]/g, '');
            } else {
                hargaInput.classList.remove('is-invalid');
                feedback.classList.add('d-none');
            }
        };

        hargaInput.addEventListener('input', validateHarga);
        hargaInput.addEventListener('blur', validateHarga);
    });
</script>
@endpush
