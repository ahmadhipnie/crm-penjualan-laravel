@extends('admin.layout.sidebar_admin')

@section('title', 'Tambah Admin Baru')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <form action="{{ route('admin.users-management.store') }}" method="POST">
                @csrf
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6>Form Tambah Admin Baru</h6>
                        <a href="{{ route('admin.users-management.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" id="nama" name="nama"
                                               class="form-control form-control-alternative @error('nama') is-invalid @enderror"
                                               placeholder="Masukkan nama lengkap" value="{{ old('nama') }}" required>
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="email">Email <span class="text-danger">*</span></label>
                                        <input type="email" id="email" name="email"
                                               class="form-control form-control-alternative @error('email') is-invalid @enderror"
                                               placeholder="Masukkan email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="nomor_telepon">Nomor Telepon <span class="text-danger">*</span></label>
                                        <input type="text" id="nomor_telepon" name="nomor_telepon"
                                               class="form-control form-control-alternative @error('nomor_telepon') is-invalid @enderror"
                                               placeholder="Masukkan nomor telepon" value="{{ old('nomor_telepon') }}" required>
                                        @error('nomor_telepon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                               class="form-control form-control-alternative @error('tanggal_lahir') is-invalid @enderror"
                                               value="{{ old('tanggal_lahir') }}" required>
                                        @error('tanggal_lahir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select id="jenis_kelamin" name="jenis_kelamin"
                                                class="form-control form-control-alternative @error('jenis_kelamin') is-invalid @enderror" required>
                                            <option value="">Pilih jenis kelamin</option>
                                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="password">Password <span class="text-danger">*</span></label>
                                        <input type="password" id="password" name="password"
                                               class="form-control form-control-alternative @error('password') is-invalid @enderror"
                                               placeholder="Masukkan password (min. 6 karakter)" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                               class="form-control form-control-alternative"
                                               placeholder="Konfirmasi password" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Role Information -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <strong><i class="ni ni-single-02"></i> Role:</strong> User yang ditambahkan akan otomatis mendapat role <strong>Admin</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer py-4">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Tambah Admin
                                </button>
                                <a href="{{ route('admin.users-management.index') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .form-control-alternative {
        border: 1px solid #e3e3e3;
        border-radius: 0.375rem;
    }

    .form-control-alternative:focus {
        border-color: #5e72e4;
        box-shadow: 0 0 0 0.2rem rgba(94, 114, 228, 0.25);
    }

    .card {
        border: none;
        box-shadow: 0 0 2rem 0 rgba(136, 152, 170, 0.15);
    }

    .alert {
        border-radius: 0.375rem;
    }

    .text-danger {
        color: #f5365c !important;
    }
</style>
@endpush

@push('js')
<script>
    $(document).ready(function() {
        // Auto focus on first input
        $('#nama').focus();

        // Password confirmation validation
        $('#password_confirmation').on('keyup', function() {
            var password = $('#password').val();
            var confirmPassword = $(this).val();

            if (password !== confirmPassword) {
                $(this).addClass('is-invalid');
                if ($(this).siblings('.invalid-feedback').length === 0) {
                    $(this).after('<div class="invalid-feedback">Password tidak sama</div>');
                }
            } else {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').remove();
            }
        });

        // Form validation before submit
        $('form').on('submit', function(e) {
            var password = $('#password').val();
            var confirmPassword = $('#password_confirmation').val();

            if (password !== confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Password dan konfirmasi password tidak sama!'
                });
                return false;
            }
        });
    });
</script>
@endpush
