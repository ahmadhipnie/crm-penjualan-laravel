@extends('admin.layout.sidebar_admin')
@section('title', 'Kategori')
@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Manajemen Kategori Furniture</h6>
                    <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Kategori
                    </a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="kategoriTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Kategori</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jumlah Produk</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kategoris as $index => $kategori)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $kategoris->firstItem() + $index }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <div class="icon icon-shape icon-sm border-radius-md bg-gradient-primary shadow text-center me-2 d-flex align-items-center justify-content-center">
                                                    <i class="ni ni-tag text-white text-sm opacity-10"></i>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $kategori->nama_kategori }}</h6>
                                                <p class="text-xs text-secondary mb-0">ID: {{ $kategori->id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs text-secondary mb-0">
                                            <span class="badge badge-sm bg-gradient-info">{{ $kategori->barangs_count }} Produk</span>
                                        </p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.kategori.edit', $kategori->id) }}" class="btn btn-sm btn-primary text-white" title="Edit Kategori">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            @if($kategori->barangs_count == 0)
                                                <form action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger text-white" title="Hapus Kategori">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-sm btn-secondary text-white" disabled title="Tidak dapat dihapus karena masih memiliki produk">
                                                    <i class="fas fa-lock"></i> Hapus
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ni ni-folder-17 text-muted" style="font-size: 3rem;"></i>
                                            <h6 class="text-muted mt-3">Belum ada kategori</h6>
                                            <p class="text-sm text-muted">Silakan tambahkan kategori furniture pertama</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($kategoris->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $kategoris->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    @if($kategoris->count() > 0)
        $('#kategoriTable').DataTable({
            "paging": false,
            "searching": true,
            "ordering": true,
            "info": false,
            "language": {
                "search": "Cari Kategori:",
                "zeroRecords": "Tidak ada kategori yang ditemukan",
                "emptyTable": "Tidak ada data kategori"
            }
        });
    @endif
});
</script>

@endsection
