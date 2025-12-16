<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\GambarBarang;

class BerandaController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua kategori
        $getAllKategori = Kategori::all();

        // Query dasar untuk barang
        $query = Barang::with(['kategori', 'gambarBarangs']);

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'LIKE', '%' . $search . '%')
                  ->orWhere('deskripsi', 'LIKE', '%' . $search . '%')
                  ->orWhere('sku_barang', 'LIKE', '%' . $search . '%');
            });
        }

        // Filter berdasarkan kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('id_kategori', $request->kategori);
        }

        // Pagination dengan 12 item per halaman
        $getAllbarangs = $query->paginate(12);
        $getAllbarangs->appends($request->all());

        return view('index', compact('getAllKategori', 'getAllbarangs'));
    }

    public function detail($id)
    {
        $barang = Barang::with(['kategori', 'gambarBarangs'])->findOrFail($id);

        return view('detail_barang', compact('barang'));
    }
}
