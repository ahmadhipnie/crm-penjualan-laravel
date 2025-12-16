<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Barang;
use App\Models\Penjualan;
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        // Calculate dashboard statistics
        $totalPembeli = User::where('role', 'customer')->count();
        $totalPenjual = User::where('role', 'admin')->count();
        $totalPesanan = Penjualan::count();
        $totalProduk = Barang::count();

        return view('admin.dashboard.dashboard_admin', compact(
            'totalPembeli',
            'totalPenjual',
            'totalPesanan',
            'totalProduk'
        ));
    }
}
