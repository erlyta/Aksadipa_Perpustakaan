<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ================= LOGIN =================
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors([
                'email' => 'email atau password salah'
            ]);
        }

        $request->session()->regenerate();
        $user = Auth::user();

        // redirect berdasarkan role
        if (in_array($user->role, ['admin', 'petugas'])) {
            return redirect('/dashboard');
        }

        // peminjam
        return redirect('/user/books');
    }

    // ================= REGISTER =================
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        // username otomatis dari email
        $username = explode('@', $request->email)[0];

        User::create([
            'full_name' => $request->name,
            'username'  => $username,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'peminjam', // ⬅️ FIX DI SINI
        ]);

        return redirect('/login')->with('success', 'register berhasil, silakan login');
    }

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
