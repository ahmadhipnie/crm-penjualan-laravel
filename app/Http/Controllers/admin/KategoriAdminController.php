<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class KategoriAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = Kategori::withCount('barangs')->orderBy('nama_kategori')->paginate(10);
        return view('admin.kategori.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori',
        ]);

        if ($validator->fails()) {
            Alert::error('Error', 'Nama kategori sudah ada atau tidak valid');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        Alert::success('Berhasil', 'Kategori berhasil ditambahkan');
        return redirect()->route('admin.kategori.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('admin.kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori,' . $id,
        ]);

        if ($validator->fails()) {
            Alert::error('Error', 'Nama kategori sudah ada atau tidak valid');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        Alert::success('Berhasil', 'Kategori berhasil diperbarui');
        return redirect()->route('admin.kategori.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);

        // Check if kategori has barang
        if ($kategori->barangs()->count() > 0) {
            Alert::error('Error', 'Kategori tidak dapat dihapus karena masih memiliki produk');
            return redirect()->back();
        }

        $kategori->delete();

        Alert::success('Berhasil', 'Kategori berhasil dihapus');
        return redirect()->route('admin.kategori.index');
    }
}
