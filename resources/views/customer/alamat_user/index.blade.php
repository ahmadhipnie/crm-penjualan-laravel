@extends('customer.layout.sidebar_customer')

@section('title', 'Kelola Alamat')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Kelola Alamat</h6>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus me-2"></i>Tambah Alamat
                    </button>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        @if($alamatUsers->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-map-marker-alt text-muted mb-3" style="font-size: 3rem;"></i>
                            <p class="text-muted">Belum ada alamat tersimpan</p>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                                <i class="fas fa-plus me-2"></i>Tambah Alamat Pertama
                            </button>
                        </div>
                        @else
                        <table class="table align-items-center mb-0" id="alamatTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alamat Lengkap</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kecamatan</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kabupaten</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Provinsi</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kode Pos</th>
                                    <th class="text-secondary opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alamatUsers as $index => $alamat)
                                <tr>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 ms-3">{{ $index + 1 }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $alamat->alamat }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $alamat->kecamatan }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $alamat->kabupaten }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $alamat->provinsi }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $alamat->kode_pos }}</p>
                                    </td>
                                    <td class="align-middle">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-warning btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $alamat->id }}"
                                                    title="Edit">
                                               Ubah
                                            </button>
                                            <form action="{{ route('customer.alamat-user.destroy', $alamat->id) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Alamat Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('customer.alamat-user.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required
                                      placeholder="Jl. Contoh No. 123, RT 01/RW 02">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="text-danger text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kecamatan" class="form-label">Kecamatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kecamatan" name="kecamatan" value="{{ old('kecamatan') }}" required>
                            @error('kecamatan')
                                <div class="text-danger text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kabupaten" class="form-label">Kabupaten/Kota <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kabupaten" name="kabupaten" value="{{ old('kabupaten') }}" required>
                            @error('kabupaten')
                                <div class="text-danger text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="provinsi" class="form-label">Provinsi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="provinsi" name="provinsi" value="{{ old('provinsi') }}" required>
                            @error('provinsi')
                                <div class="text-danger text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kode_pos" class="form-label">Kode Pos <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kode_pos" name="kode_pos" value="{{ old('kode_pos') }}"
                                   maxlength="5" pattern="[0-9]{5}" required placeholder="12345">
                            @error('kode_pos')
                                <div class="text-danger text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Alamat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modals -->
@foreach($alamatUsers as $alamat)
<div class="modal fade" id="editModal{{ $alamat->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $alamat->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $alamat->id }}">Edit Alamat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('customer.alamat-user.update', $alamat->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="alamat_edit{{ $alamat->id }}" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alamat_edit{{ $alamat->id }}" name="alamat" rows="3" required>{{ $alamat->alamat }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kecamatan_edit{{ $alamat->id }}" class="form-label">Kecamatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kecamatan_edit{{ $alamat->id }}" name="kecamatan" value="{{ $alamat->kecamatan }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kabupaten_edit{{ $alamat->id }}" class="form-label">Kabupaten/Kota <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kabupaten_edit{{ $alamat->id }}" name="kabupaten" value="{{ $alamat->kabupaten }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="provinsi_edit{{ $alamat->id }}" class="form-label">Provinsi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="provinsi_edit{{ $alamat->id }}" name="provinsi" value="{{ $alamat->provinsi }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kode_pos_edit{{ $alamat->id }}" class="form-label">Kode Pos <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kode_pos_edit{{ $alamat->id }}" name="kode_pos" value="{{ $alamat->kode_pos }}"
                                   maxlength="5" pattern="[0-9]{5}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Alamat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    $(document).ready(function() {
        @if($alamatUsers->isNotEmpty())
        $('#alamatTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            "pageLength": 10,
            "ordering": true,
            "searching": true
        });
        @endif

        // Auto-open create modal if validation errors exist
        @if($errors->any() && !request()->has('_method'))
        var createModal = new bootstrap.Modal(document.getElementById('createModal'));
        createModal.show();
        @endif

        // Auto-open edit modal if validation errors exist for update
        @if($errors->any() && request()->has('_method'))
        @foreach($alamatUsers as $alamat)
        var editModal = new bootstrap.Modal(document.getElementById('editModal{{ $alamat->id }}'));
        editModal.show();
        @endforeach
        @endif
    });
</script>
@endpush

@endsection

