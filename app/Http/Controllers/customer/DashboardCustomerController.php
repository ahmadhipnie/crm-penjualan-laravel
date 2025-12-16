<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Barang;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardCustomerController extends Controller
{
    /**
     * Display customer dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Customer specific statistics
        $totalPesananSaya = Penjualan::where('id_user', $user->id)->count();
        $totalProduk = Barang::count();

        return view('customer.dashboard.dashboard_customer', compact(
            'totalPesananSaya',
            'totalProduk'
        ));
    }
}
