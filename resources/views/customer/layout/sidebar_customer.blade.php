<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
        Customer
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css?v=2.1.0') }}" rel="stylesheet" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.dataTables.css">

    <style>
        /* Active menu hover effect */
        .nav-link.active:hover {
            transform: translateX(5px);
            transition: all 0.3s ease;
        }

        .nav-link.active {
            transition: all 0.3s ease;
        }

        /* Fix modal z-index to appear above sidebar */
        .modal {
            z-index: 1060 !important;
        }

        .modal-backdrop {
            z-index: 1050 !important;
        }

        /* Ensure sidebar stays below modal */
        .sidenav {
            z-index: 1000 !important;
        }
    </style>
</head>

<body class="g-sidenav-show bg-gray-100">

    @include('sweetalert::alert')

    @if (View::yieldContent('title') == 'profil')
        <div class="position-absolute w-100 min-height-300 top-0"
            style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg'); background-position-y: 50%;">
            <span class="mask bg-primary opacity-6"></span>
        </div>
    @else
        <div class="min-height-300 bg-dark position-absolute w-100"></div>
    @endif
    <aside
        class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4"
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="" target="_blank">
                <img src="{{ asset('assets/img/logo-ct-dark.png') }}" width="26px" height="26px" class="navbar-brand-img h-100"
                    alt="main_logo">
                <span class="ms-1 font-weight-bold">Website Rongsokan</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="navbar w-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('dashboard.customer') || Request::routeIs('customer.dashboard') ? 'active' : '' }}" href="{{ route('dashboard.customer') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">SHOPPING</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('beranda*') ? 'active' : '' }}" href="{{ route('beranda') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-shop text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Catalog Furniture</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('customer.keranjang.*') ? 'active' : '' }}" href="{{ route('customer.keranjang.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-cart text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Keranjang Belanja</span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">ACCOUNT</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('customer.alamat-user.*') ? 'active' : '' }}" href="{{ route('customer.alamat-user.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-pin-3 text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Kelola Alamat</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('customer.profil.*') ? 'active' : '' }}" href="{{ route('customer.profil.edit') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-circle-08 text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Profil Saya</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('customer.status-pengiriman.*') ? 'active' : '' }}" href="{{ route('customer.status-pengiriman.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-delivery-fast text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Status Pengiriman</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-user-run text-danger text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1 text-danger">Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>

            </ul>
        </div>
    </aside>
    <!-- Navbar -->
    <main class="main-content position-relative border-radius-lg ">


        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white"
                                href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">@yield('title')</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">@yield('title')</h6>
                </nav>

                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <a href="" class="nav-link text-white font-weight-bold px-0">
                            <i class="fa fa-user me-sm-1"></i>
                            <span class="d-sm-inline d-none">{{ session('email') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>



        @yield('content')


    </main>

    <div class="fixed-plugin">
        <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
            <i class="ni ni-palette py-2"></i>
        </a>
        <div class="card shadow-lg">
            <div class="card-header pb-0 pt-3 ">
                <div class="float-start">
                    <h5 class="mt-3 mb-0">Ubah Tema</h5>
                    <p>Ubah tema sesuka hati anda</p>
                </div>
                <div class="float-end mt-4">
                    <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
                        <i class="ni ni-close">❌</i>
                    </button>
                </div>
                <!-- End Toggle Button -->
            </div>
            <hr class="horizontal dark my-1">
            <div class="card-body pt-sm-3 pt-0 overflow-auto">
                <!-- Sidebar Backgrounds -->
                <div>
                    <h6 class="mb-0">warna sidebar</h6>
                </div>
                <a href="javascript:void(0)" class="switch-trigger background-color">
                    <div class="badge-colors my-2 text-start">
                        <span class="badge filter bg-gradient-primary active" data-color="primary"
                            onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-dark" data-color="dark"
                            onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-info" data-color="info"
                            onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-success" data-color="success"
                            onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-warning" data-color="warning"
                            onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-danger" data-color="danger"
                            onclick="sidebarColor(this)"></span>
                    </div>
                </a>
                <!-- Sidenav Type -->
                <div class="mt-3">
                    <h6 class="mb-0">tipe sidebar</h6>
                    <p class="text-sm">pilih tipe dari sidebar yang anda inginkan</p>
                </div>
                <div class="d-flex">
                    <button class="btn bg-gradient-primary w-100 px-3 mb-2 active me-2" data-class="bg-white"
                        onclick="sidebarType(this)">Putih</button>
                    <button class="btn bg-gradient-primary w-100 px-3 mb-2" data-class="bg-default"
                        onclick="sidebarType(this)">Hitam</button>
                </div>
                <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>

                <hr class="horizontal dark my-sm-4">
                <div class="mt-2 mb-5 d-flex">
                    <h6 class="mb-0">Terang / Gelap</h6>
                    <div class="form-check form-switch ps-0 ms-auto my-auto">
                        <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version"
                            onclick="darkMode(this)">
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>



    <!-- script -->

    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
    <script>
        var ctx1 = document.getElementById("chart-line").getContext("2d");

        var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

        gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
        gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
        new Chart(ctx1, {
            type: "line",
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Mobile apps",
                    tension: 0.4,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "#5e72e4",
                    backgroundColor: gradientStroke1,
                    borderWidth: 3,
                    fill: true,
                    data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
                    maxBarThickness: 6

                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#fbfbfb',
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#ccc',
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });
    </script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('assets/js/argon-dashboard.min.js?v=2.1.0') }}"></script>

    <!-- DataTables JS -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.dataTables.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.print.min.js"></script>

    <!-- Theme Persistence Script -->
    <script>
        // Load saved theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedColor = localStorage.getItem('sidebarColor');
            const savedType = localStorage.getItem('sidebarType');
            const savedDarkMode = localStorage.getItem('darkMode');

            // Restore sidebar color
            if (savedColor) {
                const colorBadge = document.querySelector(`[data-color="${savedColor}"]`);
                if (colorBadge) {
                    sidebarColor(colorBadge);
                }
            }

            // Restore sidebar type
            if (savedType) {
                const typeButton = document.querySelector(`[data-class="${savedType}"]`);
                if (typeButton) {
                    sidebarType(typeButton);
                }
            }

            // Restore dark mode
            if (savedDarkMode === 'true') {
                const darkModeToggle = document.getElementById('dark-version');
                if (darkModeToggle && !darkModeToggle.checked) {
                    darkModeToggle.click();
                }
            }
        });

        // Override sidebarColor function to save preference
        const originalSidebarColor = window.sidebarColor;
        window.sidebarColor = function(element) {
            if (originalSidebarColor) {
                originalSidebarColor(element);
            }
            const color = element.getAttribute('data-color');
            localStorage.setItem('sidebarColor', color);
        };

        // Override sidebarType function to save preference
        const originalSidebarType = window.sidebarType;
        window.sidebarType = function(element) {
            if (originalSidebarType) {
                originalSidebarType(element);
            }
            const type = element.getAttribute('data-class');
            localStorage.setItem('sidebarType', type);
        };

        // Override darkMode function to save preference
        const originalDarkMode = window.darkMode;
        window.darkMode = function(element) {
            if (originalDarkMode) {
                originalDarkMode(element);
            }
            localStorage.setItem('darkMode', element.checked);
        };
    </script>

</body>
</html>
