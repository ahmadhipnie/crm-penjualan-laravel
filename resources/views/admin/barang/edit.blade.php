@extends('admin.layout.sidebar_admin')
@section('title', 'Edit Produk')
@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">Edit Produk: {{ $barang->nama_barang }}</p>
                        <a href="{{ route('admin.barang.index') }}" class="btn btn-outline-secondary btn-sm ms-auto">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <!-- Basic Information -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama_barang" class="form-control-label">Nama Produk *</label>
                                            <input class="form-control @error('nama_barang') is-invalid @enderror"
                                                   type="text" name="nama_barang" id="nama_barang"
                                                   value="{{ old('nama_barang', $barang->nama_barang) }}"
                                                   placeholder="Sofa Minimalis Modern"
                                                   required>
                                            @error('nama_barang')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="id_kategori" class="form-control-label">Kategori *</label>
                                            <select class="form-control @error('id_kategori') is-invalid @enderror"
                                                    name="id_kategori" id="id_kategori" required>
                                                <option value="">Pilih Kategori</option>
                                                @foreach($kategoris as $kategori)
                                                    <option value="{{ $kategori->id }}" {{ old('id_kategori', $barang->id_kategori) == $kategori->id ? 'selected' : '' }}>
                                                        {{ $kategori->nama_kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_kategori')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sku_barang" class="form-control-label">SKU Produk *</label>
                                            <input class="form-control @error('sku_barang') is-invalid @enderror"
                                                   type="text" name="sku_barang" id="sku_barang"
                                                   value="{{ old('sku_barang', $barang->sku_barang) }}"
                                                   placeholder="SFA-MIN-001"
                                                   required>
                                            @error('sku_barang')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="material" class="form-control-label">Material</label>
                                            <input class="form-control @error('material') is-invalid @enderror"
                                                   type="text" name="material" id="material"
                                                   value="{{ old('material', $barang->material) }}"
                                                   placeholder="Kayu Jati, Besi, dll">
                                            @error('material')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="deskripsi" class="form-control-label">Deskripsi</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                              name="deskripsi" id="deskripsi" rows="4"
                                              placeholder="Deskripsi detail tentang produk furniture ini...">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Price & Stock -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="harga" class="form-control-label">Harga (Rp) *</label>
                                            <input class="form-control @error('harga') is-invalid @enderror"
                                                   type="number" name="harga" id="harga"
                                                   value="{{ old('harga', $barang->harga) }}"
                                                   placeholder="3500000"
                                                   min="0" step="1000"
                                                   required>
                                            @error('harga')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="berat" class="form-control-label">Berat (Kg) *</label>
                                            <input class="form-control @error('berat') is-invalid @enderror"
                                                   type="number" name="berat" id="berat"
                                                   value="{{ old('berat', $barang->berat/1000) }}"
                                                   placeholder="25.5"
                                                   min="0" step="0.1"
                                                   required>
                                            @error('berat')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="stok" class="form-control-label">Stok *</label>
                                            <input class="form-control @error('stok') is-invalid @enderror"
                                                   type="number" name="stok" id="stok"
                                                   value="{{ old('stok', $barang->stok) }}"
                                                   placeholder="10"
                                                   min="0"
                                                   required>
                                            @error('stok')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Image Management Section -->
                            <div class="col-md-4">
                                <!-- Existing Images -->
                                @if($barang->gambarBarangs->count() > 0)
                                <div class="card bg-gradient-light mb-3">
                                    <div class="card-header pb-0">
                                        <h6 class="text-dark">🖼️ Gambar Saat Ini</h6>
                                    </div>
                                    <div class="card-body">
                                        @foreach($barang->gambarBarangs as $gambar)
                                        <div class="d-flex align-items-center mb-2 p-2 border rounded">
                                            <img src="{{ asset($gambar->gambar_url) }}"
                                                 class="img-thumbnail me-2"
                                                 style="width: 60px; height: 60px; object-fit: cover;"
                                                 onerror="this.src='{{ asset('img/placeholder-furniture.png') }}'">
                                            <div class="flex-grow-1">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                           name="existing_primary" value="{{ $gambar->id }}"
                                                           {{ $gambar->is_primary ? 'checked' : '' }}>
                                                    <label class="form-check-label text-sm">
                                                        {{ $gambar->is_primary ? 'Utama' : 'Set Utama' }}
                                                    </label>
                                                </div>
                                            </div>
                                            @if($barang->gambarBarangs->count() > 1)
                                            <form action="{{ route('admin.barang.image.delete', $gambar->id) }}"
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Hapus gambar ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Hapus
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <!-- Upload New Images -->
                                <div class="card bg-gradient-light">
                                    <div class="card-header pb-0">
                                        <h6 class="text-dark">📸 Tambah Gambar Baru</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="gambar" class="form-control-label">Pilih Gambar</label>
                                            <input class="form-control @error('gambar.*') is-invalid @enderror"
                                                   type="file" name="gambar[]" id="gambar"
                                                   accept="image/*" multiple>
                                            @error('gambar.*')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                - Max 2MB per gambar<br>
                                                - Format: JPG, PNG, GIF<br>
                                                - Bisa upload multiple gambar
                                            </small>
                                        </div>

                                        <div id="imagePreview" class="mt-3"></div>

                                        <div class="form-group mt-3" id="primaryImageGroup" style="display: none;">
                                            <label class="form-control-label">Gambar Utama Baru:</label>
                                            <div id="primaryImageOptions"></div>
                                            <small class="form-text text-muted">
                                                Pilih gambar baru yang akan dijadikan utama
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="horizontal dark">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.barang.index') }}" class="btn btn-outline-secondary me-2">
                                        <i class="fas fa-times"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Produk
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

<script>
document.getElementById('gambar').addEventListener('change', function(e) {
    const files = e.target.files;
    const imagePreview = document.getElementById('imagePreview');
    const primaryImageGroup = document.getElementById('primaryImageGroup');
    const primaryImageOptions = document.getElementById('primaryImageOptions');

    imagePreview.innerHTML = '';
    primaryImageOptions.innerHTML = '';

    if (files.length > 0) {
        primaryImageGroup.style.display = 'block';

        Array.from(files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Image preview
                    const imgContainer = document.createElement('div');
                    imgContainer.className = 'mb-2';
                    imgContainer.innerHTML = `
                        <img src="${e.target.result}" class="img-thumbnail" style="max-height: 80px;">
                        <small class="d-block text-muted">${file.name}</small>
                    `;
                    imagePreview.appendChild(imgContainer);

                    // Primary image radio button
                    const radioContainer = document.createElement('div');
                    radioContainer.className = 'form-check';
                    radioContainer.innerHTML = `
                        <input class="form-check-input" type="radio" name="primary_image" value="${index}" ${index === 0 ? 'checked' : ''} id="primary_${index}">
                        <label class="form-check-label" for="primary_${index}">
                            ${file.name} ${index === 0 ? '(Default)' : ''}
                        </label>
                    `;
                    primaryImageOptions.appendChild(radioContainer);
                };
                reader.readAsDataURL(file);
            }
        });
    } else {
        primaryImageGroup.style.display = 'none';
    }
});
</script>

@endsection
