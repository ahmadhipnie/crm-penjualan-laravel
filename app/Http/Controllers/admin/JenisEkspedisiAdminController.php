<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JenisEkspedisi;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class JenisEkspedisiAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenisEkspedisi = JenisEkspedisi::orderBy('created_at', 'desc')->get();
        return view('admin.jenis_ekspedisi.index', compact('jenisEkspedisi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_ekspedisi' => 'required|string|max:255|unique:jenis_ekspedisis,nama_ekspedisi'
        ]);

        try {
            JenisEkspedisi::create([
                'nama_ekspedisi' => $request->nama_ekspedisi
            ]);

            Alert::success('Berhasil', 'Jenis ekspedisi berhasil ditambahkan');
            return redirect()->route('admin.jenis-ekspedisi.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menambahkan jenis ekspedisi');
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisEkspedisi $jenisEkspedisi)
    {
        return response()->json($jenisEkspedisi);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisEkspedisi $jenisEkspedisi)
    {
        $request->validate([
            'nama_ekspedisi' => 'required|string|max:255|unique:jenis_ekspedisi,nama_ekspedisi,' . $jenisEkspedisi->id
        ]);

        try {
            $jenisEkspedisi->update([
                'nama_ekspedisi' => $request->nama_ekspedisi
            ]);

            Alert::success('Berhasil', 'Jenis ekspedisi berhasil diperbarui');
            return redirect()->route('admin.jenis-ekspedisi.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal memperbarui jenis ekspedisi');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisEkspedisi $jenisEkspedisi)
    {
        try {
            // Check if ekspedisi is being used
            if ($jenisEkspedisi->penjualans()->exists()) {
                Alert::error('Error', 'Tidak dapat menghapus jenis ekspedisi yang sedang digunakan');
                return redirect()->back();
            }

            $jenisEkspedisi->delete();

            Alert::success('Berhasil', 'Jenis ekspedisi berhasil dihapus');
            return redirect()->route('admin.jenis-ekspedisi.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menghapus jenis ekspedisi');
            return redirect()->back();
        }
    }
}
