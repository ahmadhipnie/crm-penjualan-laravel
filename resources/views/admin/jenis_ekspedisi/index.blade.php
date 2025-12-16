@extends('admin.layout.sidebar_admin')

@section('title', 'Kelola Jenis Ekspedisi')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Kelola Jenis Ekspedisi</h6>
                        <p class="text-sm mb-0">
                            Manage shipping expedition types for orders
                        </p>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus me-2"></i>Tambah Jenis Ekspedisi
                    </button>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="jenisEkspedisiTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        No
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Nama Ekspedisi
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Dibuat
                                    </th>
                                    <th class="text-secondary opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jenisEkspedisi as $index => $ekspedisi)
                                <tr>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $index + 1 }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $ekspedisi->nama_ekspedisi }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs text-secondary mb-0">
                                            {{ $ekspedisi->created_at->format('d M Y') }}
                                        </p>
                                    </td>
                                    <td class="align-middle">
                                        <button type="button" class="btn btn-sm btn-info me-1 edit-btn"
                                                data-id="{{ $ekspedisi->id }}"
                                                data-bs-toggle="modal" data-bs-target="#editModal">
                                            Ubah
                                        </button>
                                        <form method="POST" action="{{ route('admin.jenis-ekspedisi.destroy', $ekspedisi->id) }}"
                                              style="display: inline-block;"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis ekspedisi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <p class="text-muted mb-0">Tidak ada data jenis ekspedisi</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Jenis Ekspedisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.jenis-ekspedisi.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="create_nama_ekspedisi" class="form-label">Nama Ekspedisi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_ekspedisi') is-invalid @enderror"
                               id="create_nama_ekspedisi" name="nama_ekspedisi"
                               placeholder="Contoh: JNE, TIKI, Pos Indonesia"
                               value="{{ old('nama_ekspedisi') }}" required>
                        @error('nama_ekspedisi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Jenis Ekspedisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="edit_nama_ekspedisi" class="form-label">Nama Ekspedisi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control"
                               id="edit_nama_ekspedisi" name="nama_ekspedisi"
                               placeholder="Contoh: JNE, TIKI, Pos Indonesia" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Handle edit button click
    $('.edit-btn').on('click', function() {
        const id = $(this).data('id');

        // Fetch data via AJAX
        $.get(`{{ url('admin/jenis-ekspedisi') }}/${id}/edit`, function(data) {
            $('#edit_nama_ekspedisi').val(data.nama_ekspedisi);
            $('#editForm').attr('action', `{{ url('admin/jenis-ekspedisi') }}/${id}`);
        });
    });

    // Clear form when create modal is closed
    $('#createModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').remove();
    });

    // Clear form when edit modal is closed
    $('#editModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
    });
</script>
@endsection
