<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class StatusPengirimanCustomerController extends Controller
{
    /**
     * Display status pengiriman customer
     */
    public function index(Request $request)
    {
        // Get filter status
        $filterStatus = $request->get('status');

        // Query penjualan customer yang login
        $query = Penjualan::with(['detailPenjualans.barang.gambarBarangs'])
            ->where('id_user', Auth::id())
            ->whereNotIn('status', ['menunggu_pembayaran', 'dibatalkan']);

        // Apply filter if selected
        if ($filterStatus && $filterStatus != 'all') {
            $query->where('status', $filterStatus);
        }

        // Get data
        $penjualan = $query->orderBy('created_at', 'desc')->get();

        // Calculate statistics
        $totalPesanan = $penjualan->count();
        $sedangDiproses = $penjualan->where('status', 'sedang_diproses')->count();
        $dikirim = $penjualan->where('status', 'dikirim')->count();
        $sampai = $penjualan->where('status', 'sampai')->count();
        $selesai = $penjualan->where('status', 'selesai')->count();

        return view('customer.status_pengiriman.index', compact(
            'penjualan',
            'totalPesanan',
            'sedangDiproses',
            'dikirim',
            'sampai',
            'selesai',
            'filterStatus'
        ));
    }

    /**
     * Update status pesanan dari sampai menjadi selesai
     */
    public function updateStatus(Request $request, $id)
    {
        $penjualan = Penjualan::where('id', $id)
            ->where('id_user', Auth::id())
            ->first();

        if (!$penjualan) {
            Alert::error('Error', 'Pesanan tidak ditemukan');
            return redirect()->back();
        }

        // Hanya bisa update jika status saat ini adalah 'sampai'
        if ($penjualan->status !== 'sampai') {
            Alert::warning('Peringatan', 'Hanya pesanan dengan status "Sampai" yang bisa diubah menjadi "Selesai"');
            return redirect()->back();
        }

        // Update status menjadi selesai
        $penjualan->status = 'selesai';
        $penjualan->save();

        Alert::success('Berhasil', 'Pesanan telah dikonfirmasi selesai. Terima kasih!');
        return redirect()->back();
    }
}
