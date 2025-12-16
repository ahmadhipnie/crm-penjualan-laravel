@extends('admin.layout.sidebar_admin')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <form action="{{ route('admin.users-management.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Form Edit User</h6>
                            <p class="text-sm text-muted mb-0">Edit data untuk: <strong>{{ $user->nama }}</strong></p>
                        </div>
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
                                               placeholder="Masukkan nama lengkap" value="{{ old('nama', $user->nama) }}" required>
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
                                               placeholder="Masukkan email" value="{{ old('email', $user->email) }}" required>
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
                                               placeholder="Masukkan nomor telepon" value="{{ old('nomor_telepon', $user->nomor_telepon) }}" required>
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
                                               value="{{ old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}" required>
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
                                            <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="role">Role <span class="text-danger">*</span></label>
                                        <select id="role" name="role"
                                                class="form-control form-control-alternative @error('role') is-invalid @enderror" required>
                                            <option value="">Pilih role</option>
                                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Customer</option>
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h6 class="heading-small text-muted mb-4">Ubah Password (Opsional)</h6>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="password">Password Baru</label>
                                        <input type="password" id="password" name="password"
                                               class="form-control form-control-alternative @error('password') is-invalid @enderror"
                                               placeholder="Kosongkan jika tidak ingin mengubah password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Minimal 6 karakter</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="password_confirmation">Konfirmasi Password Baru</label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                               class="form-control form-control-alternative"
                                               placeholder="Konfirmasi password baru">
                                    </div>
                                </div>
                            </div>

                            <!-- User Info -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong><i class="ni ni-calendar-grid-58"></i> Terdaftar:</strong> {{ $user->created_at->format('d M Y H:i') }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong><i class="ni ni-settings-gear-65"></i> Terakhir diupdate:</strong> {{ $user->updated_at->format('d M Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer py-4">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Perbarui Data
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

    .text-muted {
        color: #8898aa !important;
    }

    .heading-small {
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
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

            if (password && confirmPassword && password !== confirmPassword) {
                $(this).addClass('is-invalid');
                if ($(this).siblings('.invalid-feedback').length === 0) {
                    $(this).after('<div class="invalid-feedback">Password tidak sama</div>');
                }
            } else {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').remove();
            }
        });

        // Clear password confirmation when password is cleared
        $('#password').on('keyup', function() {
            if ($(this).val() === '') {
                $('#password_confirmation').val('').removeClass('is-invalid');
                $('#password_confirmation').siblings('.invalid-feedback').remove();
            }
        });

        // Form validation before submit
        $('form').on('submit', function(e) {
            var password = $('#password').val();
            var confirmPassword = $('#password_confirmation').val();

            if (password && password !== confirmPassword) {
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
