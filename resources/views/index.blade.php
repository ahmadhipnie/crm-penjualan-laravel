<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>CRM Furniture - Custom Furniture Jakarta</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Custom Furniture Jakarta - Sofa, Meja, Lemari, dan Furniture Berkualitas" name="description">

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

    <style>
        .fixed-size {
            height: 250px;
            /* Tetapkan tinggi tetap */
            object-fit: cover;
            /* Memastikan gambar dipotong secara proporsional */
        }
    </style>


    @include('sweetalert::alert')
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row align-items-center py-3 px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a href="" class="text-decoration-none">
                    <h1 class="m-0 display-5 font-weight-semi-bold"><span
                            class="text-primary font-weight-bold border px-3 mr-1">Furniture</span>.Mbl</h1>
                </a>
            </div>
            <div class="col-lg-6 col-6 text-left">
                <form action="{{ route('beranda') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari furniture..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
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
    <div class="container-fluid mb-5">
        <div class="row border-top px-xl-5">
            <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto py-0">
                            <a href="{{ route('beranda') }}" class="nav-item nav-link active">Beranda</a>
                        </div>


                    </div>
                </nav>
                <div id="header-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active" style="height: 410px;">
                            <img class="img-fluid" src="{{ asset('img/carousel-1.jpeg') }}" alt="Image">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h4 class="text-light text-uppercase font-weight-medium mb-3">furniture custom untuk segala kebutuhan
                                    </h4>
                                    <h3 class="display-4 text-white font-weight-semi-bold mb-4">segera hubungi sekarang</h3>
                                    {{-- <a href="" class="btn btn-light py-2 px-3">Shop Now</a> --}}
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item" style="height: 410px;">
                            <img class="img-fluid" src="{{ asset('img/carousel-2.jpeg') }}" alt="Image">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h4 class="text-light text-uppercase font-weight-medium mb-3">Dapatkan penawaran spesial untuk kamu yang istimewa</h4>
                                    <h3 class="display-4 text-white font-weight-semi-bold mb-4">harga bersahabatt</h3>
                                    {{-- <a href="" class="btn btn-light py-2 px-3">Shop Now</a> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#header-carousel" data-slide="prev">
                        <div class="btn btn-dark" style="width: 45px; height: 45px;">
                            <span class="carousel-control-prev-icon mb-n2"></span>
                        </div>
                    </a>
                    <a class="carousel-control-next" href="#header-carousel" data-slide="next">
                        <div class="btn btn-dark" style="width: 45px; height: 45px;">
                            <span class="carousel-control-next-icon mb-n2"></span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Navbar End -->


    <!-- Featured Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5 pb-3">
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fa fa-check text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">Kualitas premium</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fa fa-shipping-fast text-primary m-0 mr-2"></h1>
                    <h5 class="font-weight-semi-bold m-0">Pengiriman Gratis</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fas fa-exchange-alt text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">Garansi Pengembalian</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fa fa-phone-volume text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">Dukungan 24/7</h5>
                </div>
            </div>
        </div>
    </div>
    <!-- Featured End -->

    <!-- Shop Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
            <!-- Shop Sidebar Start -->
            <div class="col-lg-3 col-md-12">
                <!-- Price Start -->
                <div class="border-bottom mb-4 pb-4">
                    <h5 class="font-weight-semi-bold mb-4">kategori</h5>
                    <div>
                        <div class="mb-3">
                            <a href="{{ route('beranda') }}" class="d-block text-decoration-none {{ !request('kategori') ? 'text-primary font-weight-bold' : 'text-dark' }}">
                                <i class="fa fa-th-large mr-2"></i>Semua Kategori
                            </a>
                        </div>
                        @foreach ($getAllKategori as $kategori)
                            <div class="mb-2">
                                <a href="{{ route('beranda', ['kategori' => $kategori->id, 'search' => request('search')]) }}"
                                   class="d-block text-decoration-none {{ request('kategori') == $kategori->id ? 'text-primary font-weight-bold' : 'text-dark' }}">
                                    <i class="fa fa-angle-right mr-2"></i>{{ $kategori->nama_kategori }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Price End -->


            </div>
            <!-- Shop Sidebar End -->


            <!-- Shop Product Start -->
            <div class="col-lg-9 col-md-12">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <form action="{{ route('beranda') }}" method="GET" class="w-100">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari furniture berdasarkan nama, deskripsi, atau SKU..." value="{{ request('search') }}">
                                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fa fa-search"></i> Cari
                                        </button>
                                        @if(request('search') || request('kategori'))
                                            <a href="{{ route('beranda') }}" class="btn btn-outline-secondary ml-1">
                                                <i class="fa fa-times"></i> Atur Ulang
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @forelse ($getAllbarangs as $barang)
                        <div class="col-lg-4 col-md-6 col-sm-12 pb-1">
                            <div class="card product-item border-0 mb-4 h-100">
                                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                    @if($barang->gambarBarangs->where('is_primary', true)->first())
                                        <img class="img-fluid w-100 fixed-size"
                                             src="{{ asset($barang->gambarBarangs->where('is_primary', true)->first()->gambar_url) }}"
                                             alt="{{ $barang->nama_barang }}"
                                             onerror="this.src='{{ asset('img/placeholder-furniture.png') }}'">
                                    @else
                                        <img class="img-fluid w-100 fixed-size"
                                             src="{{ asset('img/placeholder-furniture.png') }}"
                                             alt="{{ $barang->nama_barang }}">
                                    @endif
                                </div>
                                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                    <h6 class="text-truncate mb-3">{{ $barang->nama_barang }}</h6>
                                    <div class="d-flex justify-content-center">
                                        <h6>Rp {{ number_format($barang->harga, 0, ',', '.') }}</h6>
                                    </div>
                                    <small class="text-muted">{{ $barang->kategori->nama_kategori ?? 'Tanpa Kategori' }}</small><br>
                                    <small class="text-muted">Stok: {{ $barang->stok }} pcs</small><br>
                                    <small class="text-muted">Berat: {{ $barang->berat/1000 }} kg</small>
                                </div>
                                <div class="card-footer d-flex justify-content-between bg-light border">
                                    <a href="{{ route('detail.barang', $barang->id) }}" class="btn btn-sm text-dark p-0">
                                        <i class="fas fa-eye text-primary mr-1"></i>Lihat Detail
                                    </a>
                                    @auth
                                        @if (Auth::user()->role == 'customer')
                                            @if($barang->stok > 0)
                                                <form action="{{ route('keranjang.add') }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <input type="hidden" name="id_barang" value="{{ $barang->id }}">
                                                    <input type="hidden" name="jumlah" value="1">
                                                    <button type="submit" class="btn btn-sm text-dark p-0" style="border: none; background: none;">
                                                        <i class="fas fa-shopping-cart text-primary mr-1"></i>Tambahkan ke Keranjang
                                                    </button>
                                                </form>
                                            @else
                                                <span class="btn btn-sm text-muted p-0">
                                                    <i class="fas fa-times text-muted mr-1"></i>Stok Habis
                                                </span>
                                            @endif
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <h4>Tidak ada furniture yang ditemukan</h4>
                                <p class="text-muted">Silakan coba dengan kata kunci lain atau pilih kategori yang berbeda</p>
                                <a href="{{ route('beranda') }}" class="btn btn-primary">Lihat Semua Furniture</a>
                            </div>
                        </div>
                    @endforelse

                    <div class="col-12 pb-1">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center mb-3">
                                {{-- Gunakan Laravel Pagination Links --}}
                                @if ($getAllbarangs->onFirstPage())
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">Sebelumnya</a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $getAllbarangs->previousPageUrl() }}">Sebelumnya</a>
                                    </li>
                                @endif

                                @foreach ($getAllbarangs->getUrlRange(1, $getAllbarangs->lastPage()) as $page => $url)
                                    <li class="page-item {{ $page == $getAllbarangs->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                @if ($getAllbarangs->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $getAllbarangs->nextPageUrl() }}">Berikutnya</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#">Berikutnya</a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- Shop Product End -->
        </div>
    </div>
    <!-- Shop End -->




    <!-- Footer Start -->
    <div class="container-fluid bg-secondary text-dark mt-5 pt-5">
        <div class="row px-xl-5 pt-5">
            <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                <a href="" class="text-decoration-none">
                    <h1 class="mb-4 display-5 font-weight-semi-bold"><span
                            class="text-primary font-weight-bold border border-white px-3 mr-1">Furniture</span>.Mbl
                    </h1>
                </a>
                <p>CUSTOM FURNITURE LAMPUNG Made By Order</p>
                <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>Lampung Timur, Lampung</p>
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

    <!-- Cart Badge Update -->
    @auth
        @if (Auth::user()->role == 'customer')
            <script>
                // Update cart badge count on page load
                $(document).ready(function() {
                    updateCartBadge();
                });

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

                // Update cart badge after add to cart forms are submitted
                $('form[action="{{ route("keranjang.add") }}"]').on('submit', function(e) {
                    e.preventDefault();

                    var form = $(this);
                    var formData = form.serialize();

                    $.post(form.attr('action'), formData)
                    .done(function(data) {
                        // Show success message with SweetAlert if available
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Item berhasil ditambahkan ke keranjang',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }

                        // Update cart badge
                        updateCartBadge();
                    })
                    .fail(function(xhr) {
                        // Show error message
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Gagal menambahkan item ke keranjang!',
                                confirmButtonColor: '#d33'
                            });
                        } else {
                            alert('Gagal menambahkan item ke keranjang!');
                        }
                    });
                });
            </script>
        @endif
    @endauth
</body>

</html>
