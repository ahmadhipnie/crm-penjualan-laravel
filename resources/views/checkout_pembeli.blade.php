<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Furniture - Checkout</title>
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
</head>

<body>
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

        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid">
        <div class="row border-top px-xl-5">
            <div class="col-lg-9">
            </div>
        </div>
    </div>
    <!-- Navbar End -->


    <!-- Page Header Start -->
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Checkout</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="{{ route('beranda') }}">Beranda</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Checkout</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Checkout Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
            <div class="col-lg-8">
                @if($keranjang->isEmpty())
                    <div class="card border-secondary">
                        <div class="card-body text-center py-5">
                            <i class="fa fa-shopping-cart text-muted mb-3" style="font-size: 3rem;"></i>
                            <h5 class="text-muted">Keranjang Anda kosong</h5>
                            <p class="text-muted">Silakan tambahkan produk ke keranjang terlebih dahulu sebelum checkout</p>
                            <a href="{{ route('beranda') }}" class="btn btn-primary">
                                <i class="fa fa-arrow-left mr-2"></i>Mulai Belanja
                            </a>
                        </div>
                    </div>
                @else
                <div class="mb-4">
                    <h5 class="font-weight-semi-bold mb-3">Informasi Pengiriman</h5>
                </div>

                @if($alamatUsers->isEmpty())
                    <div class="alert alert-warning" role="alert">
                        <h5 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> Belum Ada Alamat Tersimpan</h5>
                        <p>Anda belum memiliki alamat pengiriman. Silakan tambahkan alamat terlebih dahulu untuk melanjutkan checkout.</p>
                        <hr>
                        <a href="{{ route('customer.alamat-user.index') }}" class="btn btn-primary">
                            <i class="fa fa-plus mr-2"></i>Kelola Alamat
                        </a>
                    </div>
                @else
                <form action="{{ route('buat_pesanan') }}" method="POST" enctype="multipart/form-data" id="checkout-form">
                    @csrf
                    <div class="row">
                        <!-- Address Selection -->
                        <div class="col-md-12 form-group">
                            <label for="alamat_user_id">Pilih Alamat Pengiriman <span class="text-danger">*</span></label>
                            <select class="form-control @error('alamat_user_id') is-invalid @enderror"
                                    id="alamat_user_id" name="alamat_user_id" required>
                                <option value="">-- Pilih Alamat --</option>
                                @foreach($alamatUsers as $alamat)
                                <option value="{{ $alamat->id }}"
                                        data-alamat="{{ $alamat->alamat }}"
                                        data-kecamatan="{{ $alamat->kecamatan }}"
                                        data-kabupaten="{{ $alamat->kabupaten }}"
                                        data-provinsi="{{ $alamat->provinsi }}"
                                        data-kode-pos="{{ $alamat->kode_pos }}"
                                        {{ old('alamat_user_id') == $alamat->id ? 'selected' : '' }}>
                                    {{ $alamat->alamat }}, {{ $alamat->kecamatan }}, {{ $alamat->kabupaten }}, {{ $alamat->provinsi }} - {{ $alamat->kode_pos }}
                                </option>
                                @endforeach
                            </select>
                            @error('alamat_user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <a href="{{ route('customer.alamat-user.index') }}" target="_blank">
                                    <i class="fa fa-plus-circle"></i> Tambah alamat baru
                                </a>
                            </small>
                        </div>

                        <!-- Display Selected Address Details (Read-only) -->
                        <div class="col-md-12" id="address-details" style="display: none;">
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="font-weight-semi-bold mb-2">Detail Alamat Terpilih:</h6>
                                    <p class="mb-1"><strong>Alamat:</strong> <span id="display-alamat"></span></p>
                                    <p class="mb-1"><strong>Kecamatan:</strong> <span id="display-kecamatan"></span></p>
                                    <p class="mb-1"><strong>Kabupaten/Kota:</strong> <span id="display-kabupaten"></span></p>
                                    <p class="mb-1"><strong>Provinsi:</strong> <span id="display-provinsi"></span></p>
                                    <p class="mb-0"><strong>Kode Pos:</strong> <span id="display-kode-pos"></span></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="nama_penerima">Nama Penerima <span class="text-danger">*</span></label>
                            <input class="form-control @error('nama_penerima') is-invalid @enderror"
                                   type="text" id="nama_penerima" name="nama_penerima"
                                   value="{{ old('nama_penerima', Auth::user()->nama ?? '') }}"
                                   placeholder="Masukkan nama penerima" readonly>
                            @error('nama_penerima')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="no_telp">Nomor Telepon <span class="text-danger">*</span></label>
                            <input class="form-control @error('no_telp') is-invalid @enderror"
                                   type="tel" id="no_telp" name="no_telp"
                                   value="{{ old('no_telp', Auth::user()->nomor_telepon ?? '') }}"
                                   placeholder="Masukkan nomor telepon" readonly>
                            @error('no_telp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                    </div>

                    <!-- Hidden field for total amount -->
                    <input type="hidden" name="total_amount" id="total_amount" value="{{ $keranjang->sum(fn($item) => $item->barang->harga * $item->jumlah) }}">

                    <button type="submit" class="btn btn-primary btn-block py-3" id="pay-button">
                        <i class="fa fa-credit-card mr-2"></i>Lanjutkan ke Pembayaran
                    </button>
                </form>
                @endif
                @endif
            </div>
            <div class="col-lg-4">


                <div class="card border-secondary mb-5">
                    <div class="card-header bg-secondary border-0">
                        <h4 class="font-weight-semi-bold m-0">pesanan Total</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="font-weight-medium mb-3">Ringkasan Pesanan</h5>
                        @if($keranjang->isNotEmpty())
                            @foreach ($keranjang as $item)
                                <div class="d-flex justify-content-between mb-2">
                                    <small>{{ $item->barang->nama_barang }} (x{{ $item->jumlah }})</small>
                                    <small>Rp{{ number_format($item->barang->harga * $item->jumlah, 0, ',', '.') }}</small>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-3">
                                <p class="text-muted">Keranjang kosong</p>
                                <a href="{{ route('beranda') }}" class="btn btn-primary btn-sm">Mulai Belanja</a>
                            </div>
                        @endif
                        <hr class="mt-0">
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <div class="d-flex justify-content-between mt-2">
                            <h5 class="font-weight-bold">Total Pembayaran</h5>
                            <h5 class="font-weight-bold">Rp{{ number_format($keranjang->sum(fn($item) => $item->barang->harga * $item->jumlah), 0, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Checkout End -->


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

    <!-- Midtrans Snap -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.clientKey') }}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Address Selection Script -->
    <script>
        $(document).ready(function() {
            // Handle address selection
            $('#alamat_user_id').change(function() {
                var selectedOption = $(this).find('option:selected');

                if (selectedOption.val()) {
                    // Show address details
                    $('#address-details').show();
                    $('#display-alamat').text(selectedOption.data('alamat'));
                    $('#display-kecamatan').text(selectedOption.data('kecamatan'));
                    $('#display-kabupaten').text(selectedOption.data('kabupaten'));
                    $('#display-provinsi').text(selectedOption.data('provinsi'));
                    $('#display-kode-pos').text(selectedOption.data('kode-pos'));
                } else {
                    // Hide address details
                    $('#address-details').hide();
                }
            });

            // Trigger change on page load if address is already selected
            if ($('#alamat_user_id').val()) {
                $('#alamat_user_id').trigger('change');
            }

            // Handle checkout form submission
            $('#checkout-form').submit(function(e) {
                e.preventDefault();

                var form = $(this);
                var button = $('#pay-button');
                var buttonText = button.html();

                // Disable button and show loading
                button.prop('disabled', true);
                button.html('<i class="fa fa-spinner fa-spin mr-2"></i>Memproses...');

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.snap_token) {
                            // Open Midtrans Snap popup
                            snap.pay(response.snap_token, {
                                onSuccess: function(result) {
                                    console.log('Payment success:', result);

                                    // Update payment status manually
                                    $.ajax({
                                        url: '{{ route("payment.update-status") }}',
                                        method: 'POST',
                                        data: {
                                            _token: '{{ csrf_token() }}'
                                        },
                                        success: function(response) {
                                            console.log('Status updated:', response);
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Pembayaran Berhasil!',
                                                text: 'Pesanan Anda sedang diproses.',
                                                confirmButtonText: 'OK'
                                            }).then(() => {
                                                window.location.href = '{{ route("beranda") }}';
                                            });
                                        },
                                        error: function(xhr) {
                                            console.error('Update status error:', xhr);
                                            // Still show success message even if update fails
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Pembayaran Berhasil!',
                                                text: 'Pesanan Anda sedang diproses.',
                                                confirmButtonText: 'OK'
                                            }).then(() => {
                                                window.location.href = '{{ route("beranda") }}';
                                            });
                                        }
                                    });
                                },
                                onPending: function(result) {
                                    console.log('Payment pending:', result);
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'Menunggu Pembayaran',
                                        text: 'Silakan selesaikan pembayaran Anda.',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        window.location.href = '{{ route("beranda") }}';
                                    });
                                },
                                onError: function(result) {
                                    console.log('Payment error:', result);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Pembayaran Gagal',
                                        text: 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.',
                                        confirmButtonText: 'OK'
                                    });
                                    button.prop('disabled', false);
                                    button.html(buttonText);
                                },
                                onClose: function() {
                                    console.log('Payment popup closed');
                                    button.prop('disabled', false);
                                    button.html(buttonText);
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        var errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Validation errors
                            var errors = xhr.responseJSON.errors;
                            errorMessage = 'Validasi gagal:\n';
                            Object.keys(errors).forEach(function(key) {
                                errorMessage += '- ' + errors[key][0] + '\n';
                            });
                        } else if (xhr.responseText) {
                            console.log('Response Text:', xhr.responseText);
                            errorMessage = 'Error: ' + xhr.status + ' - ' + xhr.statusText;
                        }

                        alert(errorMessage);
                        button.prop('disabled', false);
                        button.html(buttonText);
                    }
                });
            });
        });
    </script>
</body>

</html>
