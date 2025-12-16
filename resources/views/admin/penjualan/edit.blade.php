@extends('admin.layout.sidebar_admin')
@section('title', 'Ubah Status Penjualan')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ubah Status Penjualan</h1>
        <a href="{{ route('admin.penjualan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <!-- Informasi Pesanan -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pesanan</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Kode Transaksi:</strong></td>
                            <td>{{ $penjualan->kode_transaksi }}</td>
                        </tr>
                        <tr>
                            <td><strong>Pelanggan:</strong></td>
                            <td>{{ $penjualan->user->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Harga:</strong></td>
                            <td>Rp{{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status Saat Ini:</strong></td>
                            <td>
                                @if($penjualan->status == 'menunggu_pembayaran')
                                    <span class="badge badge-warning">Menunggu Pembayaran</span>
                                @elseif($penjualan->status == 'sedang_diproses')
                                    <span class="badge badge-info">Sedang Diproses</span>
                                @elseif($penjualan->status == 'dikirim')
                                    <span class="badge badge-primary">Dikirim</span>
                                @elseif($penjualan->status == 'sampai')
                                    <span class="badge badge-success">Sampai</span>
                                @elseif($penjualan->status == 'dibatalkan')
                                    <span class="badge badge-danger">Dibatalkan</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Alamat:</strong></td>
                            <td style="white-space: normal; word-wrap: break-word;">
                                {{ $penjualan->alamat_pengiriman }}
                            </td>
                        </tr>
                    </table>

                    <h6 class="font-weight-bold mt-3">Produk Dibeli:</h6>
                    <ul class="list-unstyled">
                        @foreach($penjualan->detailPenjualans as $detail)
                        <li class="mb-2">
                            <small style="word-wrap: break-word; display: block;">
                                <strong>{{ $detail->barang->nama_barang }}</strong><br>
                                {{ $detail->qty }} x Rp{{ number_format($detail->harga, 0, ',', '.') }}
                            </small>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Form Ubah Status -->
        <div class="col-lg-8">
            <!-- Form Ubah ke Dikirim -->
            @if($penjualan->status == 'sedang_diproses')
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary">
                    <h6 class="m-0 font-weight-bold text-white">Ubah Status ke DIKIRIM</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.penjualan.update-dikirim', $penjualan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="id_jenis_ekspedisi">Jenis Ekspedisi <span class="text-danger">*</span></label>
                            <select class="form-control @error('id_jenis_ekspedisi') is-invalid @enderror"
                                    id="id_jenis_ekspedisi" name="id_jenis_ekspedisi" required>
                                <option value="">-- Pilih Ekspedisi --</option>
                                @foreach($jenisEkspedisi as $ekspedisi)
                                <option value="{{ $ekspedisi->id }}"
                                        {{ old('id_jenis_ekspedisi', $penjualan->id_jenis_ekspedisi) == $ekspedisi->id ? 'selected' : '' }}>
                                    {{ $ekspedisi->nama_ekspedisi }}
                                </option>
                                @endforeach
                            </select>
                            @error('id_jenis_ekspedisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nomor_resi">Nomor Resi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nomor_resi') is-invalid @enderror"
                                   id="nomor_resi" name="nomor_resi"
                                   value="{{ old('nomor_resi', $penjualan->nomor_resi) }}"
                                   placeholder="Masukkan nomor resi pengiriman" required>
                            @error('nomor_resi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="prakiraan_tanggal_sampai">Prakiraan Tanggal Sampai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('prakiraan_tanggal_sampai') is-invalid @enderror"
                                   id="prakiraan_tanggal_sampai" name="prakiraan_tanggal_sampai"
                                   value="{{ old('prakiraan_tanggal_sampai', $penjualan->prakiraan_tanggal_sampai?->format('Y-m-d')) }}"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            @error('prakiraan_tanggal_sampai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-shipping-fast"></i> Ubah ke Status Dikirim
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Form Ubah ke Sampai -->
            @if($penjualan->status == 'dikirim')
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success">
                    <h6 class="m-0 font-weight-bold text-white">Ubah Status ke SAMPAI</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Informasi Pengiriman:</strong><br>
                        <small>
                            Ekspedisi: <strong>{{ $penjualan->jenisEkspedisi->nama_ekspedisi ?? '-' }}</strong><br>
                            Nomor Resi: <strong>{{ $penjualan->nomor_resi ?? '-' }}</strong><br>
                            Prakiraan Sampai: <strong>{{ $penjualan->prakiraan_tanggal_sampai?->format('d/m/Y') ?? '-' }}</strong>
                        </small>
                    </div>

                    <form action="{{ route('admin.penjualan.update-sampai', $penjualan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="gambar_bukti_sampai">Upload Gambar Bukti Sampai <span class="text-danger">*</span></label>
                            <input type="file" class="form-control-file @error('gambar_bukti_sampai') is-invalid @enderror"
                                   id="gambar_bukti_sampai" name="gambar_bukti_sampai"
                                   accept="image/jpeg,image/png,image/jpg" required>
                            <small class="form-text text-muted">
                                Format: JPG, JPEG, PNG. Maksimal 2MB.
                            </small>
                            @error('gambar_bukti_sampai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 300px; display: none;">
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-circle"></i> Ubah ke Status Sampai
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Info jika tidak bisa diubah -->
            @if($penjualan->status == 'menunggu_pembayaran')
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                Pesanan masih menunggu pembayaran dari pelanggan. Status tidak dapat diubah.
            </div>
            @endif

            @if($penjualan->status == 'sampai')
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                Pesanan sudah sampai ke pelanggan.
                @if($penjualan->gambar_bukti_sampai)
                <br><br>
                <strong>Bukti Sampai:</strong><br>
                <img src="{{ asset('gambar_bukti_sampai/' . $penjualan->gambar_bukti_sampai) }}"
                     alt="Bukti Sampai" class="img-thumbnail" style="max-width: 400px;">
                @endif
            </div>
            @endif

            @if($penjualan->status == 'dibatalkan')
            <div class="alert alert-danger">
                <i class="fas fa-times-circle"></i>
                Pesanan telah dibatalkan. Status tidak dapat diubah.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Preview image before upload
    document.getElementById('gambar_bukti_sampai')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('preview');
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
