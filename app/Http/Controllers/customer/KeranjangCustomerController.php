<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keranjang;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class KeranjangCustomerController extends Controller
{
    /**
     * Display keranjang customer
     */
    public function index()
    {
        $keranjang = Keranjang::with(['barang.gambarBarangs'])
            ->where('id_user', Auth::id())
            ->get();

        // Hitung total harga
        $totalHarga = 0;
        foreach ($keranjang as $item) {
            if ($item->barang) {
                $totalHarga += $item->barang->harga * $item->jumlah;
            }
        }

        return view('customer.keranjang.index', compact('keranjang', 'totalHarga'));
    }

    /**
     * Update jumlah item di keranjang
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);

        $keranjang = Keranjang::where('id', $id)
            ->where('id_user', Auth::id())
            ->first();

        if (!$keranjang) {
            Alert::error('Error', 'Item keranjang tidak ditemukan');
            return redirect()->back();
        }

        // Cek stok barang
        if ($request->jumlah > $keranjang->barang->stok) {
            Alert::warning('Peringatan', 'Jumlah melebihi stok tersedia (' . $keranjang->barang->stok . ' pcs)');
            return redirect()->back();
        }

        $keranjang->jumlah = $request->jumlah;
        $keranjang->save();

        Alert::success('Berhasil', 'Jumlah item berhasil diupdate');
        return redirect()->back();
    }

    /**
     * Hapus item dari keranjang
     */
    public function destroy($id)
    {
        $keranjang = Keranjang::where('id', $id)
            ->where('id_user', Auth::id())
            ->first();

        if (!$keranjang) {
            Alert::error('Error', 'Item keranjang tidak ditemukan');
            return redirect()->back();
        }

        $keranjang->delete();

        Alert::success('Berhasil', 'Item berhasil dihapus dari keranjang');
        return redirect()->back();
    }

    /**
     * Hapus semua item di keranjang
     */
    public function clear()
    {
        Keranjang::where('id_user', Auth::id())->delete();

        Alert::success('Berhasil', 'Keranjang berhasil dikosongkan');
        return redirect()->back();
    }
}
