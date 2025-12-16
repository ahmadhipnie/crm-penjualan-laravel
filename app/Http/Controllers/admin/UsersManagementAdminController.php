<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UsersManagementAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users_management.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users_management.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nomor_telepon' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|string|in:L,P',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'nomor_telepon' => $request->nomor_telepon,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'password' => Hash::make($request->password),
                'role' => 'admin', // Force admin role
            ]);

            Alert::success('Berhasil', 'User admin berhasil ditambahkan');
            return redirect()->route('admin.users-management.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menambahkan user admin');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users_management.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users_management.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'nomor_telepon' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|string|in:L,P',
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|string|in:admin,customer',
        ]);

        try {
            $updateData = [
                'nama' => $request->nama,
                'email' => $request->email,
                'nomor_telepon' => $request->nomor_telepon,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'role' => $request->role,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            Alert::success('Berhasil', 'Data user berhasil diperbarui');
            return redirect()->route('admin.users-management.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal memperbarui data user');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Prevent deleting self
            if ($user->id === auth()->id()) {
                Alert::error('Error', 'Tidak dapat menghapus akun sendiri');
                return redirect()->back();
            }

            // Check if user has orders or cart items
            if ($user->penjualans()->exists() || $user->keranjangs()->exists()) {
                Alert::error('Error', 'Tidak dapat menghapus user yang memiliki pesanan atau keranjang');
                return redirect()->back();
            }

            $user->delete();

            Alert::success('Berhasil', 'User berhasil dihapus');
            return redirect()->route('admin.users-management.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menghapus user');
            return redirect()->back();
        }
    }
}
