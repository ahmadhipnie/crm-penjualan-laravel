<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Furniture - Keranjang</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    @include('sweetalert::alert')
    <!-- Topbar Start -->
    <div class="container-fluid">

        <div class="row align-items-center py-3 px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a href="" class="text-decoration-none">
                    <h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-3 mr-1">Furniture</span>.Mbl</h1>
                </a>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid">

    </div>
    <!-- Navbar End -->


    <!-- Page Header Start -->
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Keranjang</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="{{ route('beranda') }}">Beranda</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Keranjang</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Cart Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
            <div class="col-lg-8 table-responsive mb-5">
                <table class="table table-bordered text-center mb-0">
                    <thead class="bg-secondary text-dark">
                        <tr>
                            <th>produk</th>
                            <th>Harga</th>
                            <th>Kuantitas</th>
                            <th>Total</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @forelse ($keranjang as $item)
                        <tr data-keranjang-id="{{ $item->id }}">
                            <td class="align-middle">
                                @if($item->barang->gambarBarangs->where('is_primary', true)->first())
                                    <img src="{{ asset($item->barang->gambarBarangs->where('is_primary', true)->first()->gambar_url) }}"
                                         alt="{{ $item->barang->nama_barang }}" style="width: 50px; height: 50px; object-fit: cover;"
                                         onerror="this.src='{{ asset('img/placeholder-furniture.png') }}'">
                                @else
                                    <img src="{{ asset('img/placeholder-furniture.png') }}"
                                         alt="{{ $item->barang->nama_barang }}" style="width: 50px; height: 50px; object-fit: cover;">
                                @endif
                                <strong class="ml-2">{{ $item->barang->nama_barang }}</strong>
                            </td>
                            <td class="align-middle">Rp {{ number_format($item->barang->harga, 0, ',', '.') }}</td>
                            <td class="align-middle">
                                <div class="input-group quantity mx-auto" style="width: 120px;">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-sm btn-outline-secondary quantity-btn" type="button"
                                                data-action="decrease" data-keranjang-id="{{ $item->id }}">-</button>
                                    </div>
                                    <input type="number" class="form-control form-control-sm bg-light text-center quantity-input"
                                           value="{{ $item->jumlah }}" min="1" max="{{ $item->barang->stok }}"
                                           data-keranjang-id="{{ $item->id }}" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-outline-secondary quantity-btn" type="button"
                                                data-action="increase" data-keranjang-id="{{ $item->id }}">+</button>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1">Stok: {{ $item->barang->stok }}</small>
                            </td>
                            <td class="align-middle">
                                <span class="item-total">Rp {{ number_format($item->barang->harga * $item->jumlah, 0, ',', '.') }}</span>
                            </td>
                            <td class="align-middle">
                                <button class="btn btn-sm btn-danger remove-item" type="button"
                                        data-keranjang-id="{{ $item->id }}" data-item-name="{{ $item->barang->nama_barang }}">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fa fa-shopping-cart text-muted mb-3" style="font-size: 3rem;"></i>
                                    <h5 class="text-muted">Keranjang Anda kosong</h5>
                                    <p class="text-muted">Silakan tambahkan produk ke keranjang terlebih dahulu</p>
                                    <a href="{{ route('beranda') }}" class="btn btn-primary">
                                        <i class="fa fa-arrow-left mr-2"></i>Lanjut Belanja
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-lg-4">
                <div class="card border-secondary mb-5">
                    <div class="card-header bg-secondary border-0">
                        <h4 class="font-weight-semi-bold m-0">Hitung Total</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 pt-1">
                            <h6 class="font-weight-medium">Subtotal</h6>
                            <h6 class="font-weight-medium" id="subtotal">
                                Rp{{ number_format($keranjang->sum(fn($item) => $item->barang->harga * $item->jumlah), 0, ',', '.') }}
                            </h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">Shipping</h6>
                            <h6 class="font-weight-medium" id="shipping">Rp{{ number_format(10000, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <div class="d-flex justify-content-between mt-2">
                            <h5 class="font-weight-bold">Total</h5>
                            <h5 class="font-weight-bold" id="total">
                                Rp{{ number_format($keranjang->sum(fn($item) => $item->barang->harga * $item->jumlah) + 10000, 0, ',', '.') }}
                            </h5>
                        </div>
                        <form action="{{ route('checkout') }}" method="GET">
                            <button type="submit" class="btn btn-block btn-primary my-3 py-3">Pergi ke Checkout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart End -->


    <!-- Footer Start -->
    <div class="container-fluid bg-secondary text-dark mt-5 pt-5">
        <div class="row px-xl-5 pt-5">
            <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                <a href="" class="text-decoration-none">
                    <h1 class="mb-4 display-5 font-weight-semi-bold"><span
                            class="text-primary font-weight-bold border border-white px-3 mr-1">Furniture</span>.Mbl
                    </h1>
                </a>
                <p>CUSTOM FURNITURE JAKARTA Made By Order</p>
                <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>Kalideres, Jakarta Barat</p>
                <p class="mb-2">
                    <i class="fa fa-envelope text-primary mr-3"></i>
                    <a href="mailto:bbfurniture10@gmail.com" class="text-dark" style="text-decoration: none;">
                        bbfurniture10@gmail.com
                    </a>
                </p>
                <p class="mb-0">
                    <i class="fab fa-whatsapp text-primary mr-3"></i>
                    <a href="https://wa.me/6283822338795" target="_blank" class="text-dark" style="text-decoration: none;">
                        (+62) 838-2233-8795
                    </a>
                </p>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row">
                </div>
            </div>
        </div>
        <div class="row border-top border-light mx-xl-5 py-4">
            <div class="col-md-6 px-xl-0">
                <p class="mb-md-0 text-center text-md-left text-dark">
                    &copy; <a class="text-dark font-weight-semi-bold" href="#">Furniture.Mbl</a>. All Rights
                    Reserved. Designed
                    by
                    <a class="text-dark font-weight-semi-bold" href="">hf-tech</a>
                </p>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Contact Javascript File -->
    <script src="{{ asset('mail/jqBootstrapValidation.min.js') }}"></script>
    <script src="{{ asset('mail/contact.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('js/main.js') }}"></script>

    <!-- Cart JavaScript -->
    <script>
        $(document).ready(function() {
            // Update quantity on plus/minus button click
            $('.quantity-btn').on('click', function(e) {
                e.preventDefault();

                const button = $(this);
                const action = button.data('action');
                const keranjangId = button.data('keranjang-id');
                const input = button.closest('.input-group').find('.quantity-input');
                const maxStock = parseInt(input.attr('max'));
                let currentQuantity = parseInt(input.val());

                if (action === 'increase') {
                    if (currentQuantity < maxStock) {
                        currentQuantity++;
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Stok Tidak Cukup',
                            text: 'Jumlah melebihi stok yang tersedia!',
                            confirmButtonColor: '#d33'
                        });
                        return;
                    }
                } else if (action === 'decrease') {
                    if (currentQuantity > 1) {
                        currentQuantity--;
                    } else {
                        return;
                    }
                }

                // Update input value
                input.val(currentQuantity);

                // Send AJAX request to update cart
                updateCart(keranjangId, currentQuantity, button);
            });

            // Update quantity on direct input change
            $('.quantity-input').on('change', function() {
                const input = $(this);
                const keranjangId = input.data('keranjang-id');
                const maxStock = parseInt(input.attr('max'));
                let quantity = parseInt(input.val());

                if (quantity < 1) {
                    quantity = 1;
                    input.val(quantity);
                } else if (quantity > maxStock) {
                    quantity = maxStock;
                    input.val(quantity);
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stok Tidak Cukup',
                        text: 'Jumlah melebihi stok yang tersedia!',
                        confirmButtonColor: '#d33'
                    });
                }

                updateCart(keranjangId, quantity, input);
            });

            // Remove item from cart
            $('.remove-item').on('click', function(e) {
                e.preventDefault();
                const keranjangId = $(this).data('keranjang-id');
                const itemName = $(this).data('item-name');

                Swal.fire({
                    title: 'Hapus Item?',
                    text: `Apakah Anda yakin ingin menghapus "${itemName}" dari keranjang?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        removeFromCart(keranjangId);
                    }
                });
            });
        });

        // Function to update cart quantity via AJAX
        function updateCart(keranjangId, quantity, element) {
            $.ajax({
                url: '{{ route("keranjang.update") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: keranjangId,
                    jumlah: quantity
                },
                beforeSend: function() {
                    element.prop('disabled', true);
                },
                success: function(response) {
                    if (response.success) {
                        // Update item total
                        const itemRow = element.closest('tr');
                        const itemTotal = response.item_total;
                        itemRow.find('.item-total').text('Rp' + formatNumber(itemTotal));

                        // Update cart totals
                        updateCartTotals();

                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message || 'Terjadi kesalahan saat mengupdate keranjang!',
                            confirmButtonColor: '#d33'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat mengupdate keranjang!',
                        confirmButtonColor: '#d33'
                    });
                },
                complete: function() {
                    element.prop('disabled', false);
                }
            });
        }

        // Function to remove item from cart
        function removeFromCart(keranjangId) {
            $.ajax({
                url: '{{ route("keranjang.remove", ":id") }}'.replace(':id', keranjangId),
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Remove the item row
                        $(`tr[data-keranjang-id="${keranjangId}"]`).remove();

                        // Update cart totals
                        updateCartTotals();

                        // Check if cart is empty
                        if (response.cart_count === 0) {
                            location.reload(); // Reload to show empty cart message
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message || 'Terjadi kesalahan saat menghapus item!',
                            confirmButtonColor: '#d33'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menghapus item!',
                        confirmButtonColor: '#d33'
                    });
                }
            });
        }

        // Function to update cart totals
        function updateCartTotals() {
            let subtotal = 0;

            $('.item-total').each(function() {
                const totalText = $(this).text().replace(/[Rp.,]/g, '');
                subtotal += parseInt(totalText) || 0;
            });

            const shipping = 10000;
            const total = subtotal + shipping;

            $('#subtotal').text('Rp' + formatNumber(subtotal));
            $('#total').text('Rp' + formatNumber(total));
        }

        // Function to format number with thousand separator
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    </script>


</body>

</html>
