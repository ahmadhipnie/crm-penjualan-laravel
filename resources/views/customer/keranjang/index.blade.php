@extends('customer.layout.sidebar_customer')

@section('title', 'Keranjang Belanja')

@section('content')
<style>
    /* Remove number input spinner arrows */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }

    /* Ensure input group buttons and input have same height */
    .input-group .form-control,
    .input-group .btn {
        height: 38px;
        line-height: 1.5;
    }
</style>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Keranjang Belanja Saya</h6>
                        @if($keranjang->count() > 0)
                        <div>
                            <a href="{{ route('beranda') }}" class="btn btn-sm btn-info">
                                <i class="fas fa-shopping-bag"></i> Lanjut Belanja
                            </a>
                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmClearCart()">
                                <i class="fas fa-trash"></i> Kosongkan Keranjang
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if($keranjang->count() > 0)
                    <div class="table-responsive p-4">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Produk</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Harga</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Subtotal</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($keranjang as $item)
                                @if($item->barang)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                @if($item->barang->gambarBarangs->where('is_primary', true)->first())
                                                <img src="{{ asset($item->barang->gambarBarangs->where('is_primary', true)->first()->gambar_url) }}"
                                                     class="avatar avatar-lg me-3"
                                                     alt="{{ $item->barang->nama_barang }}"
                                                     onerror="this.src='{{ asset('img/placeholder-furniture.png') }}'">
                                                @else
                                                <img src="{{ asset('img/placeholder-furniture.png') }}"
                                                     class="avatar avatar-lg me-3"
                                                     alt="{{ $item->barang->nama_barang }}">
                                                @endif
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $item->barang->nama_barang }}</h6>
                                                <p class="text-xs text-secondary mb-0">SKU: {{ $item->barang->sku_barang }}</p>
                                                <p class="text-xs text-secondary mb-0">Stok: {{ $item->barang->stok }} pcs</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($item->barang->harga, 0, ',', '.') }}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <form action="{{ route('customer.keranjang.update', $item->id) }}" method="POST" class="d-inline-flex align-items-center justify-content-center">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group" style="width: 150px;">
                                                <button type="button" class="btn btn-outline-secondary" onclick="decrementValue({{ $item->id }})">-</button>
                                                <input type="number"
                                                       id="jumlah-{{ $item->id }}"
                                                       name="jumlah"
                                                       class="form-control text-center"
                                                       value="{{ $item->jumlah }}"
                                                       min="1"
                                                       max="{{ $item->barang->stok }}"
                                                       onchange="this.form.submit()"
                                                       style="-moz-appearance: textfield;">
                                                <button type="button" class="btn btn-outline-secondary" onclick="incrementValue({{ $item->id }}, {{ $item->barang->stok }})">+</button>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">
                                            Rp {{ number_format($item->barang->harga * $item->jumlah, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <form action="{{ route('customer.keranjang.destroy', $item->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus item ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary Section -->
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-8 col-md-6">
                                <p class="text-sm mb-0">
                                    <i class="fas fa-info-circle text-info"></i>
                                    Total {{ $keranjang->count() }} item dalam keranjang
                                </p>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="card bg-gradient-primary">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="numbers">
                                                    <p class="text-sm mb-0 text-white font-weight-bold opacity-8">Total Harga</p>
                                                    <h5 class="font-weight-bolder text-white mb-0">
                                                        Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon icon-shape bg-white shadow-white text-center rounded-circle">
                                                    <i class="ni ni-money-coins text-lg opacity-10 text-primary" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="{{ route('checkout') }}" class="btn btn-success btn-lg w-100">
                                        <i class="fas fa-credit-card"></i> Lanjut ke Pembayaran
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <!-- Empty Cart -->
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fa-5x text-muted mb-3"></i>
                        <h4>Keranjang Belanja Kosong</h4>
                        <p class="text-muted">Anda belum menambahkan produk ke keranjang</p>
                        <a href="{{ route('beranda') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-shopping-bag"></i> Mulai Belanja
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function incrementValue(itemId, maxStock) {
    var input = document.getElementById('jumlah-' + itemId);
    var currentValue = parseInt(input.value);

    if (currentValue < maxStock) {
        input.value = currentValue + 1;
        input.form.submit();
    } else {
        alert('Jumlah tidak boleh melebihi stok tersedia (' + maxStock + ' pcs)');
    }
}

function decrementValue(itemId) {
    var input = document.getElementById('jumlah-' + itemId);
    var currentValue = parseInt(input.value);

    if (currentValue > 1) {
        input.value = currentValue - 1;
        input.form.submit();
    }
}

function confirmClearCart() {
    if (confirm('Yakin ingin mengosongkan seluruh keranjang?')) {
        // Create form and submit
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("customer.keranjang.clear") }}';

        var csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);

        var methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
