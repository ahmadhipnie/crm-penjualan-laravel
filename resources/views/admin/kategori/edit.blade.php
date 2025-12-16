@extends('admin.layout.sidebar_admin')
@section('title', 'Edit Kategori')
@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">Edit Kategori: {{ $kategori->nama_kategori }}</p>
                        <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-secondary btn-sm ms-auto">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="nama_kategori" class="form-control-label">
                                        <i class="fas fa-tag text-primary"></i> Nama Kategori *
                                    </label>
                                    <input class="form-control @error('nama_kategori') is-invalid @enderror"
                                           type="text"
                                           name="nama_kategori"
                                           id="nama_kategori"
                                           value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                                           placeholder="Contoh: Sofa & Kursi, Meja & Makan, dll"
                                           required>
                                    @error('nama_kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Masukkan nama kategori furniture yang unik dan mudah dipahami
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-gradient-light">
                                    <div class="card-body p-3">
                                        <h6 class="text-dark">📊 Info Kategori</h6>
                                        <ul class="text-sm text-dark mb-0">
                                            <li>ID: {{ $kategori->id }}</li>
                                            <li>Jumlah Produk: {{ $kategori->barangs()->count() }}</li>
                                            <li>Dibuat: {{ $kategori->created_at->format('d M Y') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="horizontal dark">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-secondary me-2">
                                        <i class="fas fa-times"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Kategori
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
