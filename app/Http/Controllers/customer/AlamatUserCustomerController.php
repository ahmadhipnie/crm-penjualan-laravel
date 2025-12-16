<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\AlamatUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AlamatUserCustomerController extends Controller
{
    /**
     * Display a listing of user's addresses.
     */
    public function index()
    {
        $alamatUsers = AlamatUser::where('id_user', Auth::id())->get();
        return view('customer.alamat_user.index', compact('alamatUsers'));
    }

    /**
     * Store a newly created address.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'alamat' => 'required|string|max:500',
            'provinsi' => 'required|string|max:100',
            'kabupaten' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kode_pos' => 'required|digits:5',
        ], [
            'alamat.required' => 'Alamat lengkap harus diisi',
            'provinsi.required' => 'Provinsi harus diisi',
            'kabupaten.required' => 'Kabupaten/Kota harus diisi',
            'kecamatan.required' => 'Kecamatan harus diisi',
            'kode_pos.required' => 'Kode pos harus diisi',
            'kode_pos.digits' => 'Kode pos harus 5 digit',
        ]);

        $validated['id_user'] = Auth::id();

        AlamatUser::create($validated);

        Alert::success('Berhasil', 'Alamat berhasil ditambahkan');
        return redirect()->route('customer.alamat-user.index');
    }

    /**
     * Update the specified address.
     */
    public function update(Request $request, $id)
    {
        $alamatUser = AlamatUser::where('id_user', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'alamat' => 'required|string|max:500',
            'provinsi' => 'required|string|max:100',
            'kabupaten' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kode_pos' => 'required|digits:5',
        ], [
            'alamat.required' => 'Alamat lengkap harus diisi',
            'provinsi.required' => 'Provinsi harus diisi',
            'kabupaten.required' => 'Kabupaten/Kota harus diisi',
            'kecamatan.required' => 'Kecamatan harus diisi',
            'kode_pos.required' => 'Kode pos harus diisi',
            'kode_pos.digits' => 'Kode pos harus 5 digit',
        ]);

        $alamatUser->update($validated);

        Alert::success('Berhasil', 'Alamat berhasil diperbarui');
        return redirect()->route('customer.alamat-user.index');
    }

    /**
     * Remove the specified address.
     */
    public function destroy($id)
    {
        $alamatUser = AlamatUser::where('id_user', Auth::id())->findOrFail($id);

        $alamatUser->delete();

        Alert::success('Berhasil', 'Alamat berhasil dihapus');
        return redirect()->route('customer.alamat-user.index');
    }
}
