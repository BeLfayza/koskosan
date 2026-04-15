@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold mb-1">Data Penghuni</h3>
        <p class="text-muted mb-0">Kelola data penghuni kos dan penempatan kamar.</p>
    </div>
    <a href="{{ route('penghuni.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Penghuni</a>
</div>

<div class="card card-soft mb-3">
    <div class="card-body">
        <label class="form-label">Cari Penghuni</label>
        <input id="penghuniSearch" type="text" class="form-control" placeholder="Cari nama / NIK / no hp / kamar...">
    </div>
</div>

<div class="card card-soft">
    <div class="card-body">
        <div class="table-responsive">
            <table id="penghuniTable" class="table align-middle datatable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>No HP</th>
                        <th>Tanggal Masuk</th>
                        <th>Tanggal Selesai</th>
                        <th>Kamar</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($penghunis as $item)
                        <tr>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->nik }}</td>
                            <td>{{ $item->no_hp }}</td>
                            <td>{{ $item->tanggal_masuk?->format('d M Y') }}</td>
                            <td>{{ $item->tanggal_selesai?->format('d M Y') }}</td>
                            <td>{{ $item->kamar?->nomor_kamar ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('penghuni.edit', $item) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('penghuni.destroy', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus penghuni ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data penghuni.</td>
                        </tr>
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
        const penghuniTable = $('#penghuniTable').DataTable({
            dom: 'lrtip',
            responsive: true,
            pageLength: 10,
            lengthChange: false,
        });

        $('#penghuniSearch').on('input', function () {
            penghuniTable.search(this.value).draw();
        });
    });
</script>
@endpush
