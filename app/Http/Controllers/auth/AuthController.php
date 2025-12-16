<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Show register form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            Alert::error('Error', 'Email dan password harus diisi dengan benar');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Redirect based on role
            if ($user->role === 'admin') {
                Alert::success('Berhasil', 'Selamat datang Admin ' . $user->nama);
                return redirect()->route('dashboard.admin');
            } else {
                Alert::success('Berhasil', 'Selamat datang kembali ' . $user->nama);
                return redirect()->route('beranda');
            }
        } else {
            Alert::error('Error', 'Email atau password salah');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Handle register request (only for customers)
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nomor_telepon' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|string|in:L,P',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            Alert::error('Error', 'Mohon periksa kembali data yang diisi: ' . implode(', ', $errors));
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create user with customer role
        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'password' => Hash::make($request->password),
            'role' => 'customer', // Force customer role for registration
        ]);

        Alert::success('Berhasil', 'Pendaftaran berhasil! Silakan login dengan akun Anda.');
        return redirect()->route('ShowLogin');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Alert::success('Berhasil', 'Anda telah logout');
        return redirect()->route('beranda');
    }
}
