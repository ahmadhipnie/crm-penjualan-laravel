@extends('admin.layout.sidebar_admin')
@section('title', 'Produk Furniture')
@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Manajemen Produk Furniture</h6>
                    <a href="{{ route('admin.barang.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Produk
                    </a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="barangTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Produk</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kategori</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">SKU</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Harga</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Stok</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($barangs as $barang)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                @if($barang->gambarBarangs->first())
                                                    <img src="{{ asset($barang->gambarBarangs->first()->gambar_url) }}"
                                                         class="avatar avatar-sm me-3"
                                                         alt="{{ $barang->nama_barang }}"
                                                         onerror="this.src='{{ asset('img/placeholder-furniture.png') }}'">
                                                @else
                                                    <img src="{{ asset('img/placeholder-furniture.png') }}"
                                                         class="avatar avatar-sm me-3"
                                                         alt="{{ $barang->nama_barang }}">
                                                @endif
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $barang->nama_barang }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ Str::limit($barang->deskripsi, 50) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs text-secondary mb-0">{{ $barang->kategori->nama_kategori ?? 'Tidak ada' }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs text-secondary mb-0">{{ $barang->sku_barang }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs text-secondary mb-0">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm {{ $barang->stok > 10 ? 'bg-gradient-success' : ($barang->stok > 0 ? 'bg-gradient-warning' : 'bg-gradient-danger') }}">
                                            {{ $barang->stok }} pcs
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('detail.barang', $barang->id) }}" class="btn btn-sm btn-info text-white" target="_blank" title="Lihat Detail">
                                                Detail
                                            </a>
                                            <a href="{{ route('admin.barang.edit', $barang->id) }}" class="btn btn-sm btn-primary text-white" title="Edit Produk">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.barang.destroy', $barang->id) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini? Semua gambar akan ikut terhapus.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger text-white" title="Hapus Produk">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ni ni-box-2 text-muted" style="font-size: 3rem;"></i>
                                            <h6 class="text-muted mt-3">Belum ada produk</h6>
                                            <p class="text-sm text-muted">Silakan tambahkan produk furniture pertama</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($barangs->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $barangs->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    @if($barangs->count() > 0)
        $('#barangTable').DataTable({
            "paging": false,
            "searching": true,
            "ordering": true,
            "info": false,
            "language": {
                "search": "Cari Produk:",
                "zeroRecords": "Tidak ada produk yang ditemukan",
                "emptyTable": "Tidak ada data produk"
            }
        });
    @endif
});
</script>

@endsection
