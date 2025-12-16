@extends('admin.layout.sidebar_admin')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Filter Card -->
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Filter Laporan</h6>
                        <div>
                            <button type="button" class="btn btn-sm btn-success" onclick="exportExcel()">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="exportPdf()">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.laporan.index') }}" method="GET" id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="start_date">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                           value="{{ $startDate }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="end_date">Tanggal Akhir</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                           value="{{ $endDate }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status Penjualan</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                                        <option value="menunggu_pembayaran" {{ $status == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                        <option value="sedang_diproses" {{ $status == 'sedang_diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                                        <option value="dikirim" {{ $status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                        <option value="sampai" {{ $status == 'sampai' ? 'selected' : '' }}>Sampai</option>
                                        <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="dibatalkan" {{ $status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="customer">Customer</label>
                                    <select class="form-control" id="customer" name="customer">
                                        <option value="">Semua Customer</option>
                                        @foreach($customers as $cust)
                                            <option value="{{ $cust->id }}" {{ $customer == $cust->id ? 'selected' : '' }}>
                                                {{ $cust->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Penjualan</p>
                                        <h5 class="font-weight-bolder">{{ $totalPenjualan }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                        <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Pendapatan</p>
                                        <h5 class="font-weight-bolder">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                        <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Pending</p>
                                        <h5 class="font-weight-bolder">{{ $totalPending }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                        <i class="ni ni-time-alarm text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Dibatalkan</p>
                                        <h5 class="font-weight-bolder">{{ $totalDibatalkan }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                        <i class="ni ni-fat-remove text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Data Laporan Penjualan</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-4">
                        <table id="laporanTable" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No Invoice</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Produk</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penjualan as $item)
                                <tr>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 px-3">{{ $item->kode_transaksi }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-xs">{{ $item->user->nama ?? '-' }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $item->user->email ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs mb-0">
                                            @foreach($item->detailPenjualans as $detail)
                                                {{ $detail->barang->nama_barang ?? '-' }} ({{ $detail->qty }}x)<br>
                                            @endforeach
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        @if($item->status == 'selesai')
                                            <span class="badge badge-sm badge-success">Selesai</span>
                                        @elseif($item->status == 'menunggu_pembayaran')
                                            <span class="badge badge-sm badge-warning">Menunggu Pembayaran</span>
                                        @elseif($item->status == 'sedang_diproses')
                                            <span class="badge badge-sm badge-info">Sedang Diproses</span>
                                        @elseif($item->status == 'dikirim')
                                            <span class="badge badge-sm badge-primary">Dikirim</span>
                                        @elseif($item->status == 'sampai')
                                            <span class="badge badge-sm badge-secondary">Sampai</span>
                                        @else
                                            <span class="badge badge-sm badge-danger">Dibatalkan</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('admin.penjualan.show', $item->id) }}"
                                           class="btn btn-sm btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
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
    </div>
</div>

<script>
$(document).ready(function() {
    $('#laporanTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        order: [[1, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
        }
    });
});

function exportExcel() {
    const form = document.getElementById('filterForm');
    const url = new URL('{{ route("admin.laporan.export-excel") }}');

    // Add form data to URL
    const formData = new FormData(form);
    for (let [key, value] of formData.entries()) {
        if (value) url.searchParams.append(key, value);
    }

    window.location.href = url.toString();
}

function exportPdf() {
    const form = document.getElementById('filterForm');
    const url = new URL('{{ route("admin.laporan.export-pdf") }}');

    // Add form data to URL
    const formData = new FormData(form);
    for (let [key, value] of formData.entries()) {
        if (value) url.searchParams.append(key, value);
    }

    window.location.href = url.toString();
}
</script>
@endsection
