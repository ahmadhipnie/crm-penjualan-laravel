@extends('admin.layout.sidebar_admin')

@section('title', 'Profil')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Edit Profil</h5>
                    <p class="text-sm mb-0">Perbarui informasi profil Anda</p>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('admin.profil.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nama -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                       value="{{ old('nama', $user->nama) }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Nomor Telepon -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="text" name="nomor_telepon" class="form-control @error('nomor_telepon') is-invalid @enderror"
                                       value="{{ old('nomor_telepon', $user->nomor_telepon) }}"
                                       placeholder="contoh: 081234567890">
                                @error('nomor_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Tanggal Lahir & Jenis Kelamin -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                       value="{{ old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}">
                                @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="horizontal dark my-4">

                        <h6 class="mb-3">Ubah Password (Opsional)</h6>
                        <p class="text-sm text-muted mb-3">Kosongkan jika tidak ingin mengubah password</p>

                        <!-- Current Password -->
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label">Password Saat Ini</label>
                                <div class="input-group">
                                    <input type="password" name="current_password" id="current_password"
                                           class="form-control @error('current_password') is-invalid @enderror"
                                           placeholder="Masukkan password saat ini">
                                    <span class="input-group-text cursor-pointer" onclick="togglePassword('current_password')">
                                        <i class="fas fa-eye" id="current_password_icon"></i>
                                    </span>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- New Password -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label class="form-label">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Minimal 8 karakter">
                                    <span class="input-group-text cursor-pointer" onclick="togglePassword('password')">
                                        <i class="fas fa-eye" id="password_icon"></i>
                                    </span>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="form-control"
                                           placeholder="Ulangi password baru">
                                    <span class="input-group-text cursor-pointer" onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye" id="password_confirmation_icon"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informasi Tambahan -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="mb-3">Informasi Akun</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-sm mb-1"><strong>Role:</strong> <span class="badge bg-gradient-primary">{{ ucfirst($user->role) }}</span></p>
                            <p class="text-sm mb-1"><strong>Jenis Kelamin:</strong> {{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : ($user->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</p>
                            <p class="text-sm mb-1"><strong>Terdaftar sejak:</strong> {{ $user->created_at->format('d F Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-sm mb-1"><strong>Terakhir diperbarui:</strong> {{ $user->updated_at->format('d F Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '_icon');

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Auto hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endsection
