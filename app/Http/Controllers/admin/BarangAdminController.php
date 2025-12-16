<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\GambarBarang;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class BarangAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Barang::with(['kategori', 'gambarBarangs' => function($query) {
            $query->where('is_primary', true);
        }])->orderBy('nama_barang')->paginate(10);

        return view('admin.barang.index', compact('barangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('admin.barang.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kategori' => 'required|exists:kategoris,id',
            'sku_barang' => 'required|string|max:100|unique:barangs,sku_barang',
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'material' => 'nullable|string|max:255',
            'harga' => 'required|numeric|min:0',
            'berat' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar.*' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'primary_image' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            Alert::error('Error', 'Data tidak valid. Periksa kembali input Anda');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // Create barang
            $barang = Barang::create([
                'id_kategori' => $request->id_kategori,
                'sku_barang' => $request->sku_barang,
                'nama_barang' => $request->nama_barang,
                'deskripsi' => $request->deskripsi,
                'material' => $request->material,
                'harga' => $request->harga * 1, // Convert to integer
                'berat' => $request->berat * 1000, // Convert to grams
                'stok' => $request->stok,
            ]);

            // Handle image uploads
            if ($request->hasFile('gambar')) {
                $primaryIndex = $request->input('primary_image', 0);

                foreach ($request->file('gambar') as $index => $file) {
                    $path = FileUploadService::uploadGambarBarang($file, $barang->id);

                    GambarBarang::create([
                        'id_barang' => $barang->id,
                        'gambar_url' => $path,
                        'is_primary' => $index == $primaryIndex
                    ]);
                }
            }

            DB::commit();
            Alert::success('Berhasil', 'Produk berhasil ditambahkan');
            return redirect()->route('admin.barang.index');

        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Terjadi kesalahan saat menyimpan data');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $barang = Barang::with('gambarBarangs')->findOrFail($id);
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('admin.barang.edit', compact('barang', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'id_kategori' => 'required|exists:kategoris,id',
            'sku_barang' => 'required|string|max:100|unique:barangs,sku_barang,' . $id,
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'material' => 'nullable|string|max:255',
            'harga' => 'required|numeric|min:0',
            'berat' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar.*' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'primary_image' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            Alert::error('Error', 'Data tidak valid. Periksa kembali input Anda');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // Update barang
            $barang->update([
                'id_kategori' => $request->id_kategori,
                'sku_barang' => $request->sku_barang,
                'nama_barang' => $request->nama_barang,
                'deskripsi' => $request->deskripsi,
                'material' => $request->material,
                'harga' => $request->harga * 1, // Convert to integer
                'berat' => $request->berat * 1000, // Convert to grams
                'stok' => $request->stok,
            ]);

            // Handle new image uploads
            if ($request->hasFile('gambar')) {
                $primaryIndex = $request->input('primary_image', 0);

                foreach ($request->file('gambar') as $index => $file) {
                    $path = FileUploadService::uploadGambarBarang($file, $barang->id);

                    GambarBarang::create([
                        'id_barang' => $barang->id,
                        'gambar_url' => $path,
                        'is_primary' => $index == $primaryIndex
                    ]);
                }
            }

            // Handle primary image update from existing images
            if ($request->has('existing_primary') && $request->existing_primary) {
                // Reset all primary flags
                $barang->gambarBarangs()->update(['is_primary' => false]);

                // Set new primary
                $primaryImage = $barang->gambarBarangs()->find($request->existing_primary);
                if ($primaryImage) {
                    $primaryImage->update(['is_primary' => true]);
                }
            }

            DB::commit();
            Alert::success('Berhasil', 'Produk berhasil diperbarui');
            return redirect()->route('admin.barang.index');

        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Terjadi kesalahan saat memperbarui data');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $barang = Barang::with('gambarBarangs')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Delete image files
            foreach ($barang->gambarBarangs as $gambar) {
                FileUploadService::deleteFile($gambar->gambar_url);
            }

            // Delete barang (cascade will delete gambar records)
            $barang->delete();

            DB::commit();
            Alert::success('Berhasil', 'Produk berhasil dihapus');
            return redirect()->route('admin.barang.index');

        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Terjadi kesalahan saat menghapus data');
            return redirect()->back();
        }
    }

    /**
     * Delete specific image
     */
    public function deleteImage($id)
    {
        $gambar = GambarBarang::findOrFail($id);

        // Don't allow deletion if it's the only image
        $barang = $gambar->barang;
        if ($barang->gambarBarangs()->count() <= 1) {
            Alert::error('Error', 'Tidak dapat menghapus gambar terakhir');
            return redirect()->back();
        }

        // If this is primary image, set another image as primary
        if ($gambar->is_primary) {
            $newPrimary = $barang->gambarBarangs()->where('id', '!=', $gambar->id)->first();
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        }

        // Delete file and record
        FileUploadService::deleteFile($gambar->gambar_url);
        $gambar->delete();

        Alert::success('Berhasil', 'Gambar berhasil dihapus');
        return redirect()->back();
    }
}
