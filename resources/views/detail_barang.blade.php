<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $barang->nama_barang }} - Detail Furniture</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

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
                <a href="{{ route('beranda') }}" class="text-decoration-none">
                    <h1 class="m-0 display-5 font-weight-semi-bold"><span
                            class="text-primary font-weight-bold border px-3 mr-1">Furniture</span>.Mbl</h1>
                </a>
            </div>
            <div class="col-lg-6 col-6 text-left">
            </div>
            <div class="col-lg-3 col-6 text-right">
                @auth
                    <div class="d-flex align-items-center justify-content-end">
                        @if (Auth::user()->role == 'customer')
                            <a href="{{ route('keranjang') }}" class="btn btn-outline-primary mr-2 position-relative">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="badge badge-primary position-absolute" style="top: -8px; right: -8px; font-size: 10px;" id="cart-badge">0</span>
                            </a>
                        @endif
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user mr-2"></i>{{ Auth::user()->nama }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                @if (Auth::user()->role == 'admin')
                                    <a class="dropdown-item" href="{{ route('dashboard.admin') }}">
                                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Admin
                                    </a>
                                @else
                                    <a class="dropdown-item" href="{{ route('dashboard.customer') }}">
                                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Customer
                                    </a>
                                    <a class="dropdown-item" href="{{ route('keranjang') }}">
                                        <i class="fas fa-shopping-cart mr-2"></i>Keranjang Saya
                                    </a>
                                @endif
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger w-100 text-left" style="border: none; background: none;">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="btn-group" role="group">
                        <a href="{{ route('ShowLogin') }}" class="btn btn-outline-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                        <a href="{{ route('ShowRegister') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid">
        <div class="row border-top px-xl-5">
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                    <a href="{{ route('beranda') }}" class="text-decoration-none d-block d-lg-none">
                        <h1 class="m-0 display-5 font-weight-semi-bold"><span
                                class="text-primary font-weight-bold border px-3 mr-1">Furniture</span>.Mbl</h1>
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar End -->


    <!-- Page Header Start -->
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Detail Produk</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="{{ route('beranda') }}">Beranda</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Detail Produk</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Shop Detail Start -->
    <div class="container-fluid py-5">
        <div class="row px-xl-5">
            <div class="col-lg-5 pb-5">
                <div class="row">
                    <div class="col-12 mb-3">
                        @if($barang->gambarBarangs->where('is_primary', true)->first())
                            <img class="w-100" id="mainImage"
                                src="{{ asset($barang->gambarBarangs->where('is_primary', true)->first()->gambar_url) }}"
                                alt="{{ $barang->nama_barang }}"
                                onerror="this.src='{{ asset('img/placeholder-furniture.png') }}'">
                        @else
                            <img class="w-100" id="mainImage"
                                src="{{ asset('img/placeholder-furniture.png') }}"
                                alt="{{ $barang->nama_barang }}">
                        @endif
                    </div>
                    @if($barang->gambarBarangs->count() > 1)
                        <div class="col-12">
                            <div class="row">
                                @foreach($barang->gambarBarangs as $gambar)
                                    <div class="col-3 mb-2">
                                        <img class="w-100 thumbnail-img" style="cursor: pointer; height: 80px; object-fit: cover;"
                                             src="{{ asset($gambar->gambar_url) }}"
                                             alt="{{ $barang->nama_barang }}"
                                             onerror="this.src='{{ asset('img/placeholder-furniture.png') }}'"
                                             onclick="changeMainImage(this.src)">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-7 pb-5">
                <h3 class="font-weight-semi-bold">{{ $barang->nama_barang }}</h3>
                <div class="mb-3">
                    <span class="badge badge-primary">{{ $barang->kategori->nama_kategori ?? 'Tanpa Kategori' }}</span>
                    <span class="badge badge-info">SKU: {{ $barang->sku_barang }}</span>
                </div>
                <h3 class="font-weight-semi-bold mb-4 text-primary">Rp {{ number_format($barang->harga, 0, ',', '.') }}</h3>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Material:</strong> {{ $barang->material }}</p>
                        <p class="mb-2"><strong>Berat:</strong> {{ $barang->berat/1000 }} kg</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Stok:</strong>
                            @if($barang->stok > 0)
                                <span class="text-success">{{ $barang->stok }} pcs tersedia</span>
                            @else
                                <span class="text-danger">Stok habis</span>
                            @endif
                        </p>
                    </div>
                </div>

                @auth
                    @if (Auth::user()->role == 'customer')
                        @if ($barang->stok > 0)
                            <form action="{{ route('keranjang.add') }}" method="POST" class="mb-4">
                                @csrf
                                <div class="d-flex align-items-center mb-3">
                                    <label for="quantity" class="mr-3">Jumlah:</label>
                                    <input type="number" name="jumlah" id="quantity" class="form-control"
                                           style="width: 80px;" value="1" min="1" max="{{ $barang->stok }}">
                                </div>
                                <input type="hidden" name="id_barang" value="{{ $barang->id }}">
                                <button type="submit" class="btn btn-primary btn-lg px-4">
                                    <i class="fa fa-shopping-cart mr-2"></i>Tambah ke Keranjang
                                </button>
                            </form>
                        @else
                            <div class="mb-4">
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle mr-2"></i>
                                    Produk ini sedang tidak tersedia (Stok habis)
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="mb-4">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle mr-2"></i>
                                Anda login sebagai admin. Silakan logout dan login sebagai customer untuk berbelanja.
                            </div>
                        </div>
                    @endif
                @else
                    <div class="mb-4">
                        <p class="text-muted">Silakan <a href="{{ route('ShowLogin') }}">login</a> untuk melakukan pembelian</p>
                    </div>
                @endauth

                <div class="mt-4">
                    <h5>Deskripsi Produk:</h5>
                    <p class="mb-4">{{ $barang->deskripsi }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Shop Detail End -->




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

    <script>
        function changeMainImage(src) {
            document.getElementById('mainImage').src = src;
        }

        // Highlight active thumbnail
        document.addEventListener('DOMContentLoaded', function() {
            const thumbnails = document.querySelectorAll('.thumbnail-img');
            thumbnails.forEach(function(thumbnail) {
                thumbnail.addEventListener('click', function() {
                    // Remove active class from all thumbnails
                    thumbnails.forEach(function(thumb) {
                        thumb.classList.remove('border', 'border-primary');
                    });
                    // Add active class to clicked thumbnail
                    this.classList.add('border', 'border-primary');
                });
            });

            // Set first thumbnail as active by default
            if(thumbnails.length > 0) {
                thumbnails[0].classList.add('border', 'border-primary');
            }

            @auth
                @if (Auth::user()->role == 'customer')
                    // Update cart badge on page load
                    updateCartBadge();
                @endif
            @endauth
        });

        @auth
            @if (Auth::user()->role == 'customer')
                // Function to update cart badge
                function updateCartBadge() {
                    $.get('{{ route("keranjang.count") }}', function(data) {
                        $('#cart-badge').text(data.count);
                        if (data.count > 0) {
                            $('#cart-badge').show();
                        } else {
                            $('#cart-badge').hide();
                        }
                    });
                }

                // Handle add to cart form submission
                $(document).ready(function() {
                    $('form[action="{{ route("keranjang.add") }}"]').on('submit', function(e) {
                        e.preventDefault();

                        var form = $(this);
                        var formData = form.serialize();
                        var submitBtn = form.find('button[type="submit"]');

                        // Disable submit button
                        submitBtn.prop('disabled', true);
                        submitBtn.html('<i class="fa fa-spinner fa-spin mr-2"></i>Menambahkan...');

                        $.post(form.attr('action'), formData)
                        .done(function(data) {
                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Item berhasil ditambahkan ke keranjang',
                                showConfirmButton: false,
                                timer: 1500
                            });

                            // Update cart badge
                            updateCartBadge();
                        })
                        .fail(function(xhr) {
                            let errorMessage = 'Gagal menambahkan item ke keranjang!';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            // Show error message
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: errorMessage,
                                confirmButtonColor: '#d33'
                            });
                        })
                        .always(function() {
                            // Re-enable submit button
                            submitBtn.prop('disabled', false);
                            submitBtn.html('<i class="fa fa-shopping-cart mr-2"></i>Tambah ke Keranjang');
                        });
                    });
                });
            @endif
        @endauth
    </script>
</body>

</html>
