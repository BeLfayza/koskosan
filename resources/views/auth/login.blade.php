@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 85vh;">
    <div class="col-md-5">
        <div class="card card-soft">
            <div class="card-body p-4 p-md-5">
                <h3 class="fw-bold mb-1">Login Pemilik Kos</h3>
                <p class="text-muted mb-4">Masuk untuk mengelola kamar dan penghuni.</p>

                <form action="{{ route('login.attempt') }}" method="POST" class="d-grid gap-3">
                    @csrf
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Ingat saya</label>
                    </div>
                    <button class="btn btn-primary py-2">Masuk</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
