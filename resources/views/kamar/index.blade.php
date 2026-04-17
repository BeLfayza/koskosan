@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold mb-1">Data Kamar</h3>
        <p class="text-muted mb-0">Kelola nomor kamar dan status keterisian.</p>
    </div>
    <a href="{{ route('kamar.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Kamar</a>
</div>

<div class="card card-soft mb-3">
    <div class="card-body row g-2">
        <div class="col-md-4">
            <label class="form-label">Cari</label>
            <input id="kamarSearch" type="text" class="form-control" placeholder="Cari nomor kamar / status...">
        </div>
    </div>
</div>

<div class="card card-soft">
    <div class="card-body">
        <div class="table-responsive">
            <table id="kamarTable" class="table align-middle datatable">
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
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        const kamarTable = $('#kamarTable').DataTable({
            dom: 'lrtip',
            responsive: true,
            pageLength: 10,
            lengthChange: false,
        });

        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            if (settings.nTable.id !== 'kamarTable') {
                return true;
            }

            const min = parseFloat($('#minHarga').val()) || 0;
            const max = parseFloat($('#maxHarga').val()) || Infinity;
            const harga = parseFloat(data[1].replace(/[^0-9]/g, '')) || 0;

            return harga >= min && harga <= max;
        });

        $('#kamarSearch').on('input', function () {
            kamarTable.search(this.value).draw();
        });

        $('#minHarga, #maxHarga').on('input', function () {
            kamarTable.draw();
        });
    });
</script>
@endpush
