<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\JenisEkspedisi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class PenjualanAdminController extends Controller
{
    /**
     * Display a listing of penjualan
     */
    public function index()
    {
        $penjualans = Penjualan::with(['user', 'jenisEkspedisi', 'detailPenjualans.barang'])
                              ->orderBy('created_at', 'desc')
                              ->get();

        return view('admin.penjualan.index', compact('penjualans'));
    }

    /**
     * Show the form for editing the specified penjualan
     */
    public function edit($id)
    {
        $penjualan = Penjualan::with(['user', 'jenisEkspedisi', 'detailPenjualans.barang'])
                             ->findOrFail($id);
        $jenisEkspedisi = JenisEkspedisi::all();

        return view('admin.penjualan.edit', compact('penjualan', 'jenisEkspedisi'));
    }

    /**
     * Update status to 'dikirim' with resi and expedition details
     */
    public function updateDikirim(Request $request, $id)
    {
        $request->validate([
            'id_jenis_ekspedisi' => 'required|exists:jenis_ekspedisis,id',
            'nomor_resi' => 'required|string|max:255',
            'prakiraan_tanggal_sampai' => 'required|date|after:today'
        ]);

        try {
            $penjualan = Penjualan::findOrFail($id);

            $penjualan->update([
                'status' => 'dikirim',
                'id_jenis_ekspedisi' => $request->id_jenis_ekspedisi,
                'nomor_resi' => $request->nomor_resi,
                'prakiraan_tanggal_sampai' => $request->prakiraan_tanggal_sampai
            ]);

            Log::info('Penjualan status updated to dikirim', [
                'order_id' => $penjualan->kode_transaksi,
                'nomor_resi' => $request->nomor_resi
            ]);

            Alert::success('Berhasil', 'Status pesanan berhasil diubah menjadi Dikirim');
            return redirect()->route('admin.penjualan.index');

        } catch (\Exception $e) {
            Log::error('Update penjualan to dikirim error: ' . $e->getMessage());
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
            return back();
        }
    }

    /**
     * Update status to 'sampai' with bukti sampai image
     */
    public function updateSampai(Request $request, $id)
    {
        $request->validate([
            'gambar_bukti_sampai' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $penjualan = Penjualan::findOrFail($id);

            // Handle image upload to public path
            if ($request->hasFile('gambar_bukti_sampai')) {
                $image = $request->file('gambar_bukti_sampai');
                $imageName = time() . '_' . $penjualan->kode_transaksi . '.' . $image->getClientOriginalExtension();

                // Create directory if not exists
                $publicPath = public_path('gambar_bukti_sampai');
                if (!file_exists($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }

                // Move image to public path
                $image->move($publicPath, $imageName);

                // Delete old image if exists
                if ($penjualan->gambar_bukti_sampai && file_exists(public_path('gambar_bukti_sampai/' . $penjualan->gambar_bukti_sampai))) {
                    unlink(public_path('gambar_bukti_sampai/' . $penjualan->gambar_bukti_sampai));
                }

                $penjualan->update([
                    'status' => 'sampai',
                    'gambar_bukti_sampai' => $imageName
                ]);
            }

            Log::info('Penjualan status updated to sampai', [
                'order_id' => $penjualan->kode_transaksi,
                'image' => $imageName
            ]);

            Alert::success('Berhasil', 'Status pesanan berhasil diubah menjadi Sampai');
            return redirect()->route('admin.penjualan.index');

        } catch (\Exception $e) {
            Log::error('Update penjualan to sampai error: ' . $e->getMessage());
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
            return back();
        }
    }

    /**
     * Show detail of specific penjualan
     */
    public function show($id)
    {
        $penjualan = Penjualan::with(['user', 'jenisEkspedisi', 'detailPenjualans.barang.gambarBarangs'])
                             ->findOrFail($id);

        return view('admin.penjualan.show', compact('penjualan'));
    }
}
