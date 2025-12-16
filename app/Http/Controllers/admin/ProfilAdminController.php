<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class ProfilAdminController extends Controller
{
    /**
     * Show the form for editing the authenticated admin profile.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('admin.profil.edit', compact('user'));
    }

    /**
     * Update the authenticated admin profile.
     */
    public function update(Request $request)
    {
        try {
            // Validation rules
            $rules = [
                'nama' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
                'nomor_telepon' => 'nullable|string|max:20',
                'tanggal_lahir' => 'nullable|date',
                'jenis_kelamin' => 'nullable|in:L,P',
            ];

            // If password is provided, add password validation
            if ($request->filled('password')) {
                $rules['password'] = 'required|string|min:8|confirmed';
                $rules['current_password'] = 'required|string';
            }

            $validated = $request->validate($rules);

            // Get user using Eloquent
            $user = User::findOrFail(Auth::id());

            // If changing password, verify current password
            if ($request->filled('password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    Alert::error('Gagal', 'Password saat ini tidak sesuai');
                    return redirect()->back()->withInput();
                }
            }

            // Update using Eloquent fill method
            $user->fill([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'nomor_telepon' => $validated['nomor_telepon'] ?? $user->nomor_telepon,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? $user->tanggal_lahir,
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? $user->jenis_kelamin,
            ]);

            // Update password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            Log::info('Admin profile updated', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            Alert::success('Berhasil', 'Profil berhasil diperbarui');
            return redirect()->route('admin.profil.edit');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Alert::error('Gagal', 'Terjadi kesalahan validasi');
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating admin profile', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            Alert::error('Gagal', 'Terjadi kesalahan saat memperbarui profil');
            return redirect()->back()->withInput();
        }
    }
}
