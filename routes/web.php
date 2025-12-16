<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\admin\DashboardAdminController;
use App\Http\Controllers\customer\DashboardCustomerController;
use App\Http\Controllers\admin\KategoriAdminController;
use App\Http\Controllers\admin\BarangAdminController;
use App\Http\Controllers\admin\JenisEkspedisiAdminController;
use App\Http\Controllers\admin\UsersManagementAdminController;
use App\Http\Controllers\admin\EmailSentAdminController;
use App\Http\Controllers\KeranjangController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route untuk beranda (public)
Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda.index');

// Route untuk detail barang (public)
Route::get('/detail-barang/{id}', [BerandaController::class, 'detail'])->name('detail.barang');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Guest routes (tidak boleh sudah login)
Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [AuthController::class, 'showLogin'])->name('ShowLogin');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Register routes (hanya untuk customer)
    Route::get('/register', [AuthController::class, 'showRegister'])->name('ShowRegister');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

// Logout route (harus sudah login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

    // Kategori routes
    Route::get('/kategori', [KategoriAdminController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriAdminController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [KategoriAdminController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [KategoriAdminController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id}', [KategoriAdminController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [KategoriAdminController::class, 'destroy'])->name('kategori.destroy');

    // Barang routes
    Route::get('/barang', [BarangAdminController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangAdminController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangAdminController::class, 'store'])->name('barang.store');
    Route::get('/barang/{id}/edit', [BarangAdminController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{id}', [BarangAdminController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id}', [BarangAdminController::class, 'destroy'])->name('barang.destroy');

    // Delete specific image
    Route::delete('/barang/image/{id}', [BarangAdminController::class, 'deleteImage'])->name('barang.image.delete');

    // Jenis Ekspedisi routes
    Route::get('/jenis-ekspedisi', [JenisEkspedisiAdminController::class, 'index'])->name('jenis-ekspedisi.index');
    Route::post('/jenis-ekspedisi', [JenisEkspedisiAdminController::class, 'store'])->name('jenis-ekspedisi.store');
    Route::get('/jenis-ekspedisi/{jenisEkspedisi}/edit', [JenisEkspedisiAdminController::class, 'edit'])->name('jenis-ekspedisi.edit');
    Route::put('/jenis-ekspedisi/{jenisEkspedisi}', [JenisEkspedisiAdminController::class, 'update'])->name('jenis-ekspedisi.update');
    Route::delete('/jenis-ekspedisi/{jenisEkspedisi}', [JenisEkspedisiAdminController::class, 'destroy'])->name('jenis-ekspedisi.destroy');

    // Users Management routes
    Route::get('/users-management', [UsersManagementAdminController::class, 'index'])->name('users-management.index');
    Route::get('/users-management/create', [UsersManagementAdminController::class, 'create'])->name('users-management.create');
    Route::post('/users-management', [UsersManagementAdminController::class, 'store'])->name('users-management.store');
    Route::get('/users-management/{id}', [UsersManagementAdminController::class, 'show'])->name('users-management.show');
    Route::get('/users-management/{id}/edit', [UsersManagementAdminController::class, 'edit'])->name('users-management.edit');
    Route::put('/users-management/{id}', [UsersManagementAdminController::class, 'update'])->name('users-management.update');
    Route::delete('/users-management/{id}', [UsersManagementAdminController::class, 'destroy'])->name('users-management.destroy');

    // Penjualan routes
    Route::get('/penjualan', [App\Http\Controllers\admin\PenjualanAdminController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/{id}', [App\Http\Controllers\admin\PenjualanAdminController::class, 'show'])->name('penjualan.show');
    Route::get('/penjualan/{id}/edit', [App\Http\Controllers\admin\PenjualanAdminController::class, 'edit'])->name('penjualan.edit');
    Route::put('/penjualan/{id}/dikirim', [App\Http\Controllers\admin\PenjualanAdminController::class, 'updateDikirim'])->name('penjualan.update-dikirim');
    Route::put('/penjualan/{id}/sampai', [App\Http\Controllers\admin\PenjualanAdminController::class, 'updateSampai'])->name('penjualan.update-sampai');

    // Email CRM routes
    Route::get('/emails', [App\Http\Controllers\admin\EmailAdminController::class, 'index'])->name('emails.index');
    Route::get('/emails/{id}', [App\Http\Controllers\admin\EmailAdminController::class, 'show'])->name('emails.show');
    Route::get('/emails/{id}/reply', [App\Http\Controllers\admin\EmailAdminController::class, 'reply'])->name('emails.reply');
    Route::post('/emails/{id}/reply', [App\Http\Controllers\admin\EmailAdminController::class, 'sendReply'])->name('emails.send-reply');
    Route::delete('/emails/{id}', [App\Http\Controllers\admin\EmailAdminController::class, 'destroy'])->name('emails.destroy');
    Route::post('/emails/{id}/mark-unread', [App\Http\Controllers\admin\EmailAdminController::class, 'markAsUnread'])->name('emails.mark-unread');

    // Broadcast routes
    Route::get('/broadcast', [App\Http\Controllers\admin\BroadcastController::class, 'index'])->name('broadcast.index');
    Route::post('/broadcast/send', [App\Http\Controllers\admin\BroadcastController::class, 'send'])->name('broadcast.send');
    Route::post('/broadcast/get-customer-emails', [App\Http\Controllers\admin\BroadcastController::class, 'getCustomerEmails'])->name('broadcast.get-customer-emails');

    // Email Sent routes
    Route::get('/email-sent', [App\Http\Controllers\admin\EmailSentAdminController::class, 'index'])->name('email-sent.index');
    Route::get('/email-sent/{id}', [App\Http\Controllers\admin\EmailSentAdminController::class, 'show'])->name('email-sent.show');
    Route::delete('/email-sent/{id}', [App\Http\Controllers\admin\EmailSentAdminController::class, 'destroy'])->name('email-sent.destroy');

    // Laporan routes
    Route::get('/laporan', [App\Http\Controllers\admin\LaporanAdminController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-excel', [App\Http\Controllers\admin\LaporanAdminController::class, 'exportExcel'])->name('laporan.export-excel');
    Route::get('/laporan/export-pdf', [App\Http\Controllers\admin\LaporanAdminController::class, 'exportPdf'])->name('laporan.export-pdf');

    // Profil routes
    Route::get('/profil', [App\Http\Controllers\admin\ProfilAdminController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [App\Http\Controllers\admin\ProfilAdminController::class, 'update'])->name('profil.update');
});

// Alias untuk dashboard admin
Route::get('/dashboard-admin', [DashboardAdminController::class, 'index'])
    ->name('dashboard.admin')
    ->middleware(['auth', 'role:admin']);

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [DashboardCustomerController::class, 'index'])->name('dashboard');

    // Alamat User routes
    Route::get('/alamat-user', [App\Http\Controllers\customer\AlamatUserCustomerController::class, 'index'])->name('alamat-user.index');
    Route::post('/alamat-user', [App\Http\Controllers\customer\AlamatUserCustomerController::class, 'store'])->name('alamat-user.store');
    Route::put('/alamat-user/{id}', [App\Http\Controllers\customer\AlamatUserCustomerController::class, 'update'])->name('alamat-user.update');
    Route::delete('/alamat-user/{id}', [App\Http\Controllers\customer\AlamatUserCustomerController::class, 'destroy'])->name('alamat-user.destroy');

    // Keranjang routes
    Route::get('/keranjang', [App\Http\Controllers\customer\KeranjangCustomerController::class, 'index'])->name('keranjang.index');
    Route::put('/keranjang/{id}', [App\Http\Controllers\customer\KeranjangCustomerController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/{id}', [App\Http\Controllers\customer\KeranjangCustomerController::class, 'destroy'])->name('keranjang.destroy');
    Route::delete('/keranjang-clear', [App\Http\Controllers\customer\KeranjangCustomerController::class, 'clear'])->name('keranjang.clear');

    // Status Pengiriman routes
    Route::get('/status-pengiriman', [App\Http\Controllers\customer\StatusPengirimanCustomerController::class, 'index'])->name('status-pengiriman.index');
    Route::put('/status-pengiriman/{id}', [App\Http\Controllers\customer\StatusPengirimanCustomerController::class, 'updateStatus'])->name('status-pengiriman.update');

    // Profil routes
    Route::get('/profil', [App\Http\Controllers\customer\ProfilCustomerController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [App\Http\Controllers\customer\ProfilCustomerController::class, 'update'])->name('profil.update');
});

// Alias untuk dashboard customer
Route::get('/dashboard-customer', [DashboardCustomerController::class, 'index'])
    ->name('dashboard.customer')
    ->middleware(['auth', 'role:customer']);

/*
|--------------------------------------------------------------------------
| Keranjang Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Keranjang routes
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang');
    Route::post('/keranjang/add', [KeranjangController::class, 'addToCart'])->name('keranjang.add');
    Route::post('/keranjang/update', [KeranjangController::class, 'updateCart'])->name('keranjang.update');
    Route::delete('/keranjang/remove/{id}', [KeranjangController::class, 'removeFromCart'])->name('keranjang.remove');
    Route::get('/keranjang/count', [KeranjangController::class, 'getCartCount'])->name('keranjang.count');

    // Checkout routes - using CheckoutCustomerController
    Route::get('/checkout', [App\Http\Controllers\customer\CheckoutCustomerController::class, 'index'])->name('checkout');
    Route::post('/buat-pesanan', [App\Http\Controllers\customer\CheckoutCustomerController::class, 'createOrder'])->name('buat_pesanan');
    Route::post('/payment/callback', [App\Http\Controllers\customer\CheckoutCustomerController::class, 'callback'])->name('payment.callback');
    Route::post('/payment/update-status', [App\Http\Controllers\customer\CheckoutCustomerController::class, 'updatePaymentStatus'])->name('payment.update-status');
    Route::get('/payment/success', [App\Http\Controllers\customer\CheckoutCustomerController::class, 'success'])->name('payment.success');
});
