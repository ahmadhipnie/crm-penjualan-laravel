@extends('customer.layout.sidebar_customer')
@section('title', 'dashboard customer')
@section('content')


    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Selamat Datang, {{ Auth::user()->nama }}!</h6>
                        <p class="text-sm">Dashboard Customer - CRM Furniture</p>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="row">
                            <div class="col-xl-6 col-sm-6 mb-xl-0 mb-4">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="numbers">
                                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Pesanan Saya</p>
                                                    <h5 class="font-weight-bolder">
                                                        {{ $totalPesananSaya ?? 0 }}
                                                    </h5>
                                                    <p class="mb-0">
                                                        <span class="text-success text-sm font-weight-bolder">Total</span>
                                                        transaksi furniture
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                                    <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-sm-6 mb-xl-0 mb-4">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="numbers">
                                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Produk Tersedia</p>
                                                    <h5 class="font-weight-bolder text-success">
                                                        {{ $totalProduk ?? 0 }}
                                                    </h5>
                                                    <p class="mb-0">
                                                        <span class="text-success text-sm font-weight-bolder">Total</span>
                                                        furniture di catalog
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                                    <i class="ni ni-box-2 text-lg opacity-10" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header pb-0">
                                        <h6>Quick Actions</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <a href="{{ route('beranda') }}" class="btn btn-primary w-100">
                                                    <i class="ni ni-shop me-2"></i>
                                                    Lihat Catalog Furniture
                                                </a>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <button class="btn btn-outline-secondary w-100" disabled>
                                                    <i class="ni ni-cart me-2"></i>
                                                    Keranjang Belanja
                                                    <small class="d-block">Coming Soon</small>
                                                </button>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <button class="btn btn-outline-secondary w-100" disabled>
                                                    <i class="ni ni-briefcase-24 me-2"></i>
                                                    Riwayat Pesanan
                                                    <small class="d-block">Coming Soon</small>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Welcome Message -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card bg-gradient-primary">
                                    <div class="card-body text-white">
                                        <h5 class="text-white">Selamat datang di CRM Furniture!</h5>
                                        <p class="mb-0">Temukan koleksi furniture terbaik untuk rumah impian Anda. Dari sofa minimalis, meja makan kayu jati, hingga lemari pakaian modern - semuanya tersedia di catalog kami.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
