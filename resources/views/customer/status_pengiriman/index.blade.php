@extends('customer.layout.sidebar_customer')
@section('title', 'Status Pengiriman')
@section('content')

<style>
    .badge {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    .timeline {
        position: relative;
        padding: 0;
        list-style: none;
    }

    .timeline-item {
        position: relative;
        padding-left: 3rem;
        padding-bottom: 2rem;
    }

    .timeline-item:before {
        content: '';
        position: absolute;
        left: 0.625rem;
        top: 2rem;
        height: 100%;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-item:last-child:before {
        display: none;
    }

    .timeline-marker {
        position: absolute;
        left: 0;
        top: 0.5rem;
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 50%;
        background: white;
        border: 2px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .timeline-marker.active {
        background: #5e72e4;
        border-color: #5e72e4;
    }

    .timeline-marker i {
        font-size: 0.75rem;
        color: white;
    }

    .gap-2 {
        gap: 0.5rem !important;
    }

    #statusTable td {
        vertical-align: middle;
    }

    .product-info {
        max-width: 250px;
    }

    .product-name {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }

    .table-responsive {
        scrollbar-width: thin;
        scrollbar-color: #888 #f1f1f1;
    }

    .table-responsive::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .custom-scroll {
        scrollbar-width: thin;
        scrollbar-color: #888 #f1f1f1;
    }

    .custom-scroll::-webkit-scrollbar {
        width: 8px;
    }

    .custom-scroll::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .custom-scroll::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .custom-scroll::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .card .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1) !important;
    }
</style>

<div class="container-fluid py-4">

    <!-- Filter Section -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Filter Status Pengiriman</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('customer.status-pengiriman.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status Pesanan</label>
                                    <select name="status" id="status" class="form-control" onchange="this.form.submit()">
                                        <option value="all" {{ $filterStatus == 'all' || !$filterStatus ? 'selected' : '' }}>Semua Status</option>
                                        <option value="sedang_diproses" {{ $filterStatus == 'sedang_diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                                        <option value="dikirim" {{ $filterStatus == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                        <option value="sampai" {{ $filterStatus == 'sampai' ? 'selected' : '' }}>Sampai</option>
                                        <option value="selesai" {{ $filterStatus == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Pengiriman List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Daftar Pesanan</h6>
                </div>
                <div class="card-body p-3">
                    @if($penjualan->count() > 0)
                        <div style="max-height: 600px; overflow-y: auto;" class="custom-scroll">
                            @foreach($penjualan as $index => $item)
                                <div class="card mb-3 shadow-sm border">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center">
                                            <!-- Kode Transaksi & Tanggal -->
                                            <div class="col-md-3">
                                                <h6 class="mb-1 text-sm font-weight-bold">{{ $item->kode_transaksi }}</h6>
                                                <p class="text-xs text-muted mb-0">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') }}
                                                </p>
                                            </div>

                                            <!-- Produk -->
                                            <div class="col-md-3">
                                                <div class="d-flex align-items-center">
                                                    @php
                                                        $firstProduct = $item->detailPenjualans && $item->detailPenjualans->count() > 0 ? $item->detailPenjualans->first() : null;
                                                        $totalProducts = $item->detailPenjualans ? $item->detailPenjualans->count() : 0;
                                                    @endphp
                                                    @if($firstProduct && $firstProduct->barang)
                                                        @if($firstProduct->barang->gambarBarangs && $firstProduct->barang->gambarBarangs->first())
                                                            <img src="{{ asset('gambar_barang/' . $firstProduct->barang->gambarBarangs->first()->gambar) }}"
                                                                 class="avatar avatar-sm me-2" alt="{{ $firstProduct->barang->nama_barang }}">
                                                        @else
                                                            <div class="avatar avatar-sm bg-gradient-secondary me-2">
                                                                <i class="ni ni-image text-white"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <p class="text-xs font-weight-bold mb-0" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $firstProduct->barang->nama_barang }}">
                                                                {{ $firstProduct->barang->nama_barang }}
                                                            </p>
                                                            @if($totalProducts > 1)
                                                                <p class="text-xs text-secondary mb-0">+{{ $totalProducts - 1 }} produk</p>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <p class="text-xs text-muted mb-0">Tidak ada produk</p>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Total Harga -->
                                            <div class="col-md-2">
                                                <p class="text-xs text-muted mb-0">Total Harga</p>
                                                <h6 class="mb-0 text-sm font-weight-bold text-dark">
                                                    Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                                </h6>
                                            </div>

                                            <!-- Status -->
                                            <div class="col-md-2">
                                                @if($item->status == 'sedang_diproses')
                                                    <span class="badge bg-gradient-warning w-100">Sedang Diproses</span>
                                                @elseif($item->status == 'dikirim')
                                                    <span class="badge bg-gradient-info w-100">Dikirim</span>
                                                @elseif($item->status == 'sampai')
                                                    <span class="badge bg-gradient-primary w-100">Sampai</span>
                                                @elseif($item->status == 'selesai')
                                                    <span class="badge bg-gradient-success w-100">Selesai</span>
                                                @endif
                                            </div>

                                            <!-- Aksi -->
                                            <div class="col-md-2 text-end">
                                                <button type="button" class="btn btn-sm btn-info mb-1 w-100" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}">
                                                    <i class="fas fa-eye"></i> Detail
                                                </button>
                                                @if($item->status == 'sampai')
                                                    <button type="button" class="btn btn-sm btn-success w-100" onclick="confirmSelesai({{ $item->id }})">
                                                        <i class="fas fa-check"></i> Selesai
                                                    </button>
                                                    <form id="selesai-form-{{ $item->id }}" action="{{ route('customer.status-pengiriman.update', $item->id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('PUT')
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Detail -->
                                <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="detailModalLabel{{ $item->id }}">Detail Pesanan - {{ $item->kode_transaksi }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Timeline Status -->
                                                <div class="mb-4">
                                                    <h6 class="mb-3">Timeline Pengiriman</h6>
                                                    <ul class="timeline">
                                                        <li class="timeline-item">
                                                            <div class="timeline-marker {{ in_array($item->status, ['sedang_diproses', 'dikirim', 'sampai', 'selesai']) ? 'active' : '' }}">
                                                                @if(in_array($item->status, ['sedang_diproses', 'dikirim', 'sampai', 'selesai']))
                                                                    <i class="fas fa-check"></i>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-1">Sedang Diproses</h6>
                                                                <p class="text-xs text-muted mb-0">Pesanan sedang dikemas oleh penjual</p>
                                                            </div>
                                                        </li>
                                                        <li class="timeline-item">
                                                            <div class="timeline-marker {{ in_array($item->status, ['dikirim', 'sampai', 'selesai']) ? 'active' : '' }}">
                                                                @if(in_array($item->status, ['dikirim', 'sampai', 'selesai']))
                                                                    <i class="fas fa-check"></i>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-1">Dikirim</h6>
                                                                <p class="text-xs text-muted mb-0">Pesanan dalam perjalanan</p>
                                                            </div>
                                                        </li>
                                                        <li class="timeline-item">
                                                            <div class="timeline-marker {{ in_array($item->status, ['sampai', 'selesai']) ? 'active' : '' }}">
                                                                @if(in_array($item->status, ['sampai', 'selesai']))
                                                                    <i class="fas fa-check"></i>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-1">Sampai</h6>
                                                                <p class="text-xs text-muted mb-0">Pesanan telah sampai di lokasi</p>
                                                            </div>
                                                        </li>
                                                        <li class="timeline-item">
                                                            <div class="timeline-marker {{ $item->status == 'selesai' ? 'active' : '' }}">
                                                                @if($item->status == 'selesai')
                                                                    <i class="fas fa-check"></i>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-1">Selesai</h6>
                                                                <p class="text-xs text-muted mb-0">Pesanan telah dikonfirmasi selesai</p>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <hr>

                                                <!-- Order Info -->
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <p class="text-sm mb-1"><strong>Tanggal Pesanan:</strong></p>
                                                        <p class="text-sm">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="text-sm mb-1"><strong>Kode Transaksi:</strong></p>
                                                        <p class="text-sm"><strong>{{ $item->kode_transaksi }}</strong></p>
                                                    </div>
                                                </div>

                                                <!-- Product List -->
                                                <h6 class="mb-3">Produk yang Dibeli</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm align-items-center mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Produk</th>
                                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Harga</th>
                                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah</th>
                                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Subtotal</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if($item->detailPenjualans)
                                                                @foreach($item->detailPenjualans as $detail)
                                                                <tr>
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            @if($detail->barang && $detail->barang->gambarBarangs && $detail->barang->gambarBarangs->first())
                                                                                <img src="{{ asset('gambar_barang/' . $detail->barang->gambarBarangs->first()->gambar) }}"
                                                                                     class="avatar avatar-xs me-2" alt="{{ $detail->barang->nama_barang }}">
                                                                            @endif
                                                                            <p class="text-xs font-weight-bold mb-0">{{ $detail->barang->nama_barang ?? 'N/A' }}</p>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($detail->harga, 0, ',', '.') }}</p>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <p class="text-xs font-weight-bold mb-0">{{ $detail->qty }}</p>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            @endif
                                                            <tr>
                                                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                                                <td class="text-center"><strong>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</strong></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                @if($item->status == 'sampai')
                                                    <button type="button" class="btn btn-success" onclick="confirmSelesai({{ $item->id }})" data-bs-dismiss="modal">
                                                        <i class="fas fa-check"></i> Konfirmasi Selesai
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-5x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada pesanan</h5>
                            <p class="text-sm text-muted">Belum ada pesanan yang sedang diproses</p>
                            <a href="{{ route('beranda') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-shopping-cart"></i> Mulai Belanja
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmSelesai(id) {
        if (confirm('Apakah Anda yakin pesanan sudah diterima dengan baik dan ingin menyelesaikan pesanan ini?')) {
            document.getElementById('selesai-form-' + id).submit();
        }
    }
</script>

@endsection
