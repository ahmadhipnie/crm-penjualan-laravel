@extends('admin.layout.sidebar_admin')

@section('title', 'Manajemen Users')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Manajemen Users</h6>
                    <a href="{{ route('admin.users-management.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Admin Baru
                    </a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="usersTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">No. Telepon</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jenis Kelamin</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal Daftar</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $index => $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $index + 1 }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <div class="icon icon-shape icon-sm border-radius-md bg-gradient-{{ $user->role == 'admin' ? 'success' : 'info' }} shadow text-center me-2 d-flex align-items-center justify-content-center">
                                                        <i class="ni ni-single-02 text-white text-sm opacity-10"></i>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $user->nama }}</h6>
                                                    <p class="text-xs text-secondary mb-0">ID: {{ $user->id }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $user->email }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-secondary mb-0">{{ $user->nomor_telepon }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-secondary mb-0">
                                                <span class="badge badge-sm bg-gradient-{{ $user->jenis_kelamin == 'L' ? 'primary' : 'info' }}">
                                                    {{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                </span>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-secondary mb-0">
                                                <span class="badge badge-sm bg-gradient-{{ $user->role == 'admin' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-secondary mb-0">{{ $user->created_at->format('d M Y') }}</p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.users-management.show', $user->id) }}" class="btn btn-sm btn-info text-white" title="Detail User">
                                                    Detail
                                                </a>
                                                <a href="{{ route('admin.users-management.edit', $user->id) }}" class="btn btn-sm btn-primary text-white" title="Edit User">
                                                    Ubah
                                                </a>
                                                @if($user->id !== auth()->id())
                                                    <form action="{{ route('admin.users-management.destroy', $user->id) }}" method="POST" class="d-inline"
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger text-white" title="Hapus User">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-sm btn-secondary text-white" disabled title="Tidak dapat menghapus akun sendiri">
                                                        Hapus
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ni ni-single-02 text-muted" style="font-size: 3rem;"></i>
                                            <h6 class="text-muted mt-3">Belum ada data user</h6>
                                            <p class="text-sm text-muted">Silakan tambahkan user admin pertama</p>
                                        </div>
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

<script>
$(document).ready(function() {
    @if($users->count() > 0)
        $('#usersTable').DataTable({
            "paging": true,
            "pageLength": 10,
            "searching": true,
            "ordering": true,
            "info": true,
            "language": {
                "search": "Cari User:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada user yang ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data user",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    @endif
});
</script>

@endsection
