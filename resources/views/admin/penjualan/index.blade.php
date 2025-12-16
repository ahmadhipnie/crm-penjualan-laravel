@extends('admin.layout.sidebar_admin')
@section('title', 'Kelola Penjualan')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Penjualan</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Penjualan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Transaksi</th>
                            <th>Pelanggan</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Nomor Resi</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penjualans as $index => $penjualan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $penjualan->kode_transaksi }}</strong></td>
                            <td>{{ $penjualan->user->nama ?? '-' }}</td>
                            <td>Rp{{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                            <td>{{ $penjualan->status }}</td>
                            <td>{{ $penjualan->nomor_resi ?? '-' }}</td>
                            <td>{{ $penjualan->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.penjualan.show', $penjualan->id) }}"
                                   class="btn btn-info btn-sm" title="Detail">
                                    Detail
                                </a>
                                <a href="{{ route('admin.penjualan.edit', $penjualan->id) }}"
                                   class="btn btn-warning btn-sm" title="Ubah Status">
                                    Ubah
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "order": [[6, "desc"]], // Sort by date descending
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });
    });
</script>
@endsection
