@extends('admin.layout.sidebar_admin')
@section('title', 'Detail Penjualan')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Penjualan</h1>
        <a href="{{ route('admin.penjualan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <!-- Informasi Pesanan -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pesanan</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Kode Transaksi</strong></td>
                            <td>{{ $penjualan->kode_transaksi }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Pesanan</strong></td>
                            <td>{{ $penjualan->created_at->format('d F Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>{{ $penjualan->status }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Harga</strong></td>
                            <td class="text-success font-weight-bold">Rp{{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Informasi Pelanggan -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pelanggan</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Nama</strong></td>
                            <td>{{ $penjualan->user->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>{{ $penjualan->user->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>No. Telepon</strong></td>
                            <td>{{ $penjualan->user->nomor_telepon ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Alamat Pengiriman</strong></td>
                            <td style="white-space: normal; word-wrap: break-word;">
                                {{ $penjualan->alamat_pengiriman }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Informasi Pengiriman & Produk -->
        <div class="col-lg-6">
            <!-- Informasi Pengiriman -->
            @if($penjualan->status == 'dikirim' || $penjualan->status == 'sampai')
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pengiriman</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Ekspedisi</strong></td>
                            <td>{{ $penjualan->jenisEkspedisi->nama_ekspedisi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nomor Resi</strong></td>
                            <td><code>{{ $penjualan->nomor_resi ?? '-' }}</code></td>
                        </tr>
                        <tr>
                            <td><strong>Prakiraan Sampai</strong></td>
                            <td>{{ $penjualan->prakiraan_tanggal_sampai?->format('d F Y') ?? '-' }}</td>
                        </tr>
                    </table>

                    @if($penjualan->status == 'sampai' && $penjualan->gambar_bukti_sampai)
                    <hr>
                    <h6 class="font-weight-bold">Bukti Sampai:</h6>
                    <img src="{{ asset('gambar_bukti_sampai/' . $penjualan->gambar_bukti_sampai) }}"
                         alt="Bukti Sampai" class="img-thumbnail" style="max-width: 100%;">
                    @endif
                </div>
            </div>
            @endif

            <!-- Detail Produk -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Produk</h6>
                </div>
                <div class="card-body">
                    @foreach($penjualan->detailPenjualans as $detail)
                    <div class="media mb-3">
                        @if($detail->barang->gambarBarangs->first())
                        <img src="{{ asset('gambar_barang/' . $detail->barang->gambarBarangs->first()->nama_file) }}"
                             alt="{{ $detail->barang->nama_barang }}"
                             class="mr-3" style="width: 80px; height: 80px; object-fit: cover;"
                             onerror="this.src='{{ asset('img/placeholder-furniture.png') }}'">
                        @else
                        <img src="{{ asset('img/placeholder-furniture.png') }}"
                             alt="{{ $detail->barang->nama_barang }}"
                             class="mr-3" style="width: 80px; height: 80px; object-fit: cover;">
                        @endif
                        <div class="media-body">
                            <h6 class="mt-0" style="word-wrap: break-word;">{{ $detail->barang->nama_barang }}</h6>
                            <p class="mb-1">
                                <small class="text-muted">{{ $detail->qty }} x Rp{{ number_format($detail->harga, 0, ',', '.') }}</small>
                            </p>
                            <p class="mb-0">
                                <strong>Subtotal: Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</strong>
                            </p>
                        </div>
                    </div>
                    <hr>
                    @endforeach

                    <div class="text-right">
                        <h5 class="font-weight-bold text-success">
                            Total: Rp{{ number_format($penjualan->total_harga, 0, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
