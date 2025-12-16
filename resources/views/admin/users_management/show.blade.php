@extends('admin.layout.sidebar_admin')

@section('title', 'Detail User')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Informasi User</h6>
                    <div>
                        <a href="{{ route('admin.users-management.edit', $user->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.users-management.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <small class="text-muted text-uppercase font-weight-bold">Nama Lengkap</small>
                            <p class="mb-0 font-weight-bold">{{ $user->nama }}</p>
                        </div>
                        <div class="col-lg-6">
                            <small class="text-muted text-uppercase font-weight-bold">Role</small>
                            <p class="mb-0">
                                <span class="badge badge-sm bg-gradient-{{ $user->role == 'admin' ? 'success' : 'warning' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <small class="text-muted text-uppercase font-weight-bold">Email</small>
                            <p class="mb-0">{{ $user->email }}</p>
                        </div>
                        <div class="col-lg-6">
                            <small class="text-muted text-uppercase font-weight-bold">Nomor Telepon</small>
                            <p class="mb-0">{{ $user->nomor_telepon }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <small class="text-muted text-uppercase font-weight-bold">Tanggal Lahir</small>
                            <p class="mb-0">
                                {{ $user->tanggal_lahir ? $user->tanggal_lahir->format('d F Y') : 'Tidak diset' }}
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <small class="text-muted text-uppercase font-weight-bold">Jenis Kelamin</small>
                            <p class="mb-0">
                                <span class="badge badge-sm bg-gradient-{{ $user->jenis_kelamin == 'L' ? 'primary' : 'info' }}">
                                    {{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="row">
                        <div class="col-lg-6">
                            <small class="text-muted text-uppercase font-weight-bold">Tanggal Daftar</small>
                            <p class="mb-0">
                                <i class="fas fa-calendar-alt text-primary"></i>
                                {{ $user->created_at->format('d F Y, H:i') }} WIB
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <small class="text-muted text-uppercase font-weight-bold">Terakhir Diperbarui</small>
                            <p class="mb-0">
                                <i class="fas fa-clock text-info"></i>
                                {{ $user->updated_at->format('d F Y, H:i') }} WIB
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-12">
                    <div class="card mb-3">
                        <div class="card-body p-3">
                            <div class="d-flex">
                                <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                                    <i class="ni ni-bag-17 text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Pesanan</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            {{ $user->penjualans->count() }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card mb-3">
                        <div class="card-body p-3">
                            <div class="d-flex">
                                <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                    <i class="ni ni-basket text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Item di Keranjang</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            {{ $user->keranjangs->sum('jumlah') }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card mb-3">
                        <div class="card-body p-3">
                            <div class="d-flex">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                    <i class="ni ni-pin-3 text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Alamat Tersimpan</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            {{ $user->alamatUsers->count() }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Timeline -->
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Riwayat Akun</h6>
                </div>
                <div class="card-body p-3">
                    <ul class="list-group">
                        <li class="list-group-item border-0 d-flex align-items-start px-0 mb-2">
                            <div class="icon icon-shape icon-sm bg-gradient-success shadow text-center border-radius-md me-2">
                                <i class="fas fa-check text-white opacity-10"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">Akun Dibuat</h6>
                                <span class="text-xs">{{ $user->created_at->diffForHumans() }}</span>
                                <span class="text-xs text-muted">{{ $user->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </li>
                        @if($user->updated_at != $user->created_at)
                        <li class="list-group-item border-0 d-flex align-items-start px-0 mb-0">
                            <div class="icon icon-shape icon-sm bg-gradient-info shadow text-center border-radius-md me-2">
                                <i class="fas fa-edit text-white opacity-10"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">Profil Diperbarui</h6>
                                <span class="text-xs">{{ $user->updated_at->diffForHumans() }}</span>
                                <span class="text-xs text-muted">{{ $user->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .text-uppercase {
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    small.text-uppercase {
        font-size: 0.75rem;
    }

    .card-body p {
        font-size: 0.875rem;
    }

    .list-group-item {
        background-color: transparent;
    }
</style>
@endpush
