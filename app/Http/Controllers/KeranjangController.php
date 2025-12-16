<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keranjang;
use App\Models\Barang;
use App\Models\User;
use App\Models\AlamatUser;
use App\Models\JenisEkspedisi;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class KeranjangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan halaman keranjang
     */
    public function index()
    {
        $keranjang = Keranjang::with(['barang.gambarBarangs', 'barang.kategori'])
                             ->where('id_user', Auth::id())
                             ->get();

        $total = $keranjang->sum('subtotal');

        return view('cart_pembeli', compact('keranjang', 'total'));
    }

    /**
     * Menambah item ke keranjang
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1'
        ]);

        $barang = Barang::findOrFail($request->id_barang);

        // Cek stok barang
        if ($barang->stok < $request->jumlah) {
            Alert::error('Error', 'Stok barang tidak mencukupi! Stok tersedia: ' . $barang->stok);
            return back();
        }

        try {
            DB::beginTransaction();

            // Cek apakah item sudah ada di keranjang
            $existingItem = Keranjang::where('id_user', Auth::id())
                                   ->where('id_barang', $request->id_barang)
                                   ->first();

            if ($existingItem) {
                $totalJumlah = $existingItem->jumlah + $request->jumlah;

                // Cek lagi stok untuk total jumlah
                if ($barang->stok < $totalJumlah) {
                    Alert::error('Error', 'Stok barang tidak mencukupi! Anda sudah memiliki ' . $existingItem->jumlah . ' item di keranjang. Stok tersedia: ' . $barang->stok);
                    return back();
                }

                $existingItem->update(['jumlah' => $totalJumlah]);
                Alert::success('Berhasil', 'Jumlah item di keranjang berhasil diperbarui');
            } else {
                Keranjang::create([
                    'id_user' => Auth::id(),
                    'id_barang' => $request->id_barang,
                    'jumlah' => $request->jumlah
                ]);
                Alert::success('Berhasil', 'Item berhasil ditambahkan ke keranjang');
            }

            DB::commit();

            // Handle AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item berhasil ditambahkan ke keranjang'
                ]);
            }

            return redirect()->route('keranjang');

        } catch (\Exception $e) {
            DB::rollback();

            // Handle AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan item ke keranjang'
                ], 500);
            }

            Alert::error('Error', 'Gagal menambahkan item ke keranjang');
            return back();
        }
    }

    /**
     * Update jumlah item di keranjang
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:keranjangs,id',
            'jumlah' => 'required|integer|min:1'
        ]);

        try {
            $keranjangItem = Keranjang::where('id', $request->id)
                                   ->where('id_user', Auth::id())
                                   ->firstOrFail();

            $barang = $keranjangItem->barang;

            // Cek stok
            if ($barang->stok < $request->jumlah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi! Stok tersedia: ' . $barang->stok
                ]);
            }

            $keranjangItem->update(['jumlah' => $request->jumlah]);

            // Refresh the model to get updated data
            $keranjangItem->refresh();

            $itemTotal = $keranjangItem->barang->harga * $keranjangItem->jumlah;
            $cartTotal = Keranjang::where('id_user', Auth::id())->with('barang')->get()->sum(function($item) {
                return $item->barang->harga * $item->jumlah;
            });

            return response()->json([
                'success' => true,
                'item_total' => $itemTotal,
                'cart_total' => $cartTotal,
                'message' => 'Keranjang berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui keranjang'
            ]);
        }
    }

    /**
     * Hapus item dari keranjang
     */
    public function removeFromCart($id)
    {
        try {
            $keranjangItem = Keranjang::where('id', $id)
                                   ->where('id_user', Auth::id())
                                   ->firstOrFail();

            $keranjangItem->delete();

            // Check if request is AJAX
            if (request()->ajax()) {
                $cartCount = Keranjang::where('id_user', Auth::id())->count();
                return response()->json([
                    'success' => true,
                    'cart_count' => $cartCount,
                    'message' => 'Item berhasil dihapus dari keranjang'
                ]);
            }

            Alert::success('Berhasil', 'Item berhasil dihapus dari keranjang');
            return back();

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus item dari keranjang'
                ]);
            }

            Alert::error('Error', 'Gagal menghapus item dari keranjang');
            return back();
        }
    }

    /**
     * Menampilkan halaman checkout
     */
    public function checkout()
    {
        $keranjang = Keranjang::with(['barang.gambarBarangs', 'barang.kategori'])
                             ->where('id_user', Auth::id())
                             ->get();

        if ($keranjang->isEmpty()) {
            Alert::error('Error', 'Keranjang Anda kosong!');
            return redirect()->route('keranjang');
        }

        // Cek stok untuk semua item di keranjang
        foreach ($keranjang as $item) {
            if ($item->barang->stok < $item->jumlah) {
                Alert::error('Error', 'Stok ' . $item->barang->nama_barang . ' tidak mencukupi! Stok tersedia: ' . $item->barang->stok);
                return redirect()->route('keranjang');
            }
        }

        $user = Auth::user();
        // Get all user addresses
        $alamatUsers = AlamatUser::where('id_user', $user->id)->get();
        $jenisEkspedisi = JenisEkspedisi::all();

        $subtotal = $keranjang->sum('subtotal');
        $total = $subtotal; // Bisa ditambah ongkir nanti

        return view('checkout_pembeli', compact('keranjang', 'user', 'alamatUsers', 'jenisEkspedisi', 'subtotal', 'total'));
    }

    /**
     * Menghitung jumlah item di keranjang (untuk badge)
     */
    public function getCartCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $count = Keranjang::where('id_user', Auth::id())->sum('jumlah');

        return response()->json(['count' => $count]);
    }

    /**
     * Membuat pesanan dari keranjang
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'alamat_user_id' => 'required|exists:alamat_users,id',
            'nama_penerima' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'jenis_ekspedisi_id' => 'required|exists:jenis_ekspedisis,id',
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'catatan' => 'nullable|string',
            'total_amount' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Get selected address
            $alamatUser = AlamatUser::where('id', $request->alamat_user_id)
                                    ->where('id_user', Auth::id())
                                    ->firstOrFail();

            // Get cart items
            $keranjang = Keranjang::with('barang')
                                ->where('id_user', Auth::id())
                                ->get();

            if ($keranjang->isEmpty()) {
                Alert::error('Error', 'Keranjang Anda kosong!');
                return redirect()->route('keranjang');
            }

            // Check stock for all items
            foreach ($keranjang as $item) {
                if ($item->barang->stok < $item->jumlah) {
                    Alert::error('Error', 'Stok ' . $item->barang->nama_barang . ' tidak mencukupi! Stok tersedia: ' . $item->barang->stok);
                    return redirect()->route('keranjang');
                }
            }

            // Handle file upload
            $buktiPembayaranPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $fileName = 'bukti_' . time() . '_' . Auth::id() . '.' . $file->getClientOriginalExtension();
                $buktiPembayaranPath = $file->move(public_path('gambar_bukti_sampai'), $fileName);
                $buktiPembayaranPath = 'gambar_bukti_sampai/' . $fileName;
            }

            // Build full address string from alamat_users
            $alamatLengkap = $alamatUser->alamat . ', ' . $alamatUser->kecamatan . ', ' .
                           $alamatUser->kabupaten . ', ' . $alamatUser->provinsi . ' ' . $alamatUser->kode_pos;

            // Generate kode transaksi
            $kodeTransaksi = Penjualan::generateKodeTransaksi();

            // Create order
            $penjualan = Penjualan::create([
                'id_user' => Auth::id(),
                'id_jenis_ekspedisi' => $request->jenis_ekspedisi_id,
                'kode_transaksi' => $kodeTransaksi,
                'snap_token' => '', // Will be filled if using Midtrans
                'alamat_pengiriman' => $alamatLengkap,
                'status' => 'menunggu_pembayaran',
                'total_harga' => $request->total_amount,
                'gambar_bukti_sampai' => $buktiPembayaranPath
            ]);

            // Create order details and update stock
            foreach ($keranjang as $item) {
                DetailPenjualan::create([
                    'id_penjualan' => $penjualan->id,
                    'id_barang' => $item->id_barang,
                    'jumlah' => $item->jumlah,
                    'harga' => $item->barang->harga,
                    'subtotal' => $item->barang->harga * $item->jumlah
                ]);

                // Update stock
                $item->barang->decrement('stok', $item->jumlah);
            }

            // Clear cart
            Keranjang::where('id_user', Auth::id())->delete();

            DB::commit();

            Alert::success('Berhasil', 'Pesanan berhasil dibuat dengan kode transaksi: ' . $kodeTransaksi . '. Pesanan Anda akan segera diproses.');
            return redirect()->route('beranda');

        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Gagal membuat pesanan: ' . $e->getMessage());
            return back()->withInput();
        }
    }
}
