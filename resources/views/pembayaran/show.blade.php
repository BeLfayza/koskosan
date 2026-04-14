@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold mb-1">Pembayaran Kamar {{ $kamar->nomor_kamar }}</h3>
        <p class="text-muted mb-0">
            Harga per bulan: <strong>Rp {{ number_format($kamar->harga_per_bulan, 0, ',', '.') }}</strong>
        </p>
    </div>
    <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>

<div class="card card-soft">
    <div class="card-body">
        @forelse ($pembayaransByYear as $year => $list)
            <h5 class="fw-semibold mt-2">{{ $year }}</h5>
            <div class="row g-2 mb-3">
                @foreach ($list as $pay)
                    <div class="col-md-3 col-sm-4 col-6">
                        <div class="border rounded-3 p-2 h-100">
                            <div class="small text-muted mb-2">{{ \Carbon\Carbon::parse($pay->periode_bulan)->translatedFormat('F Y') }}</div>
                            <form action="{{ route('pembayaran.toggle', $pay) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm w-100 {{ $pay->status === 'lunas' ? 'btn-success' : 'btn-outline-danger' }}" type="submit">
                                    {{ $pay->status === 'lunas' ? 'Lunas' : 'Tidak Lunas' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            <p class="text-muted mb-0">Belum ada data pembayaran untuk kamar ini.</p>
        @endforelse
    </div>
</div>
@endsection
