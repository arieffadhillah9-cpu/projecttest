<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLoginController extends Controller
{
    // Menampilkan Form Login User
    public function showLoginForm()
    {
        // View sudah kita koreksi sebelumnya
        return view('auth.user.userlogin'); 
    }

    // Memproses Login User
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required', // Ini akan menjadi PIN/Password User
        ]);

        // Menggunakan Guard 'web' (default untuk User) secara eksplisit untuk memastikan pemisahan
        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Arahkan langsung ke Dashboard User
            return redirect()->intended('/dashboard'); 
        }

        return back()->withErrors([
            'email' => 'Email atau Password/PIN salah.',
        ])->onlyInput('email');
    }

    // Logout User
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Kembali ke halaman utama
    }
}