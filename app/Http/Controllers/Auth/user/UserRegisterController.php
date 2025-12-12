<?php

namespace App\Http\Controllers\Auth\User; // Pastikan Namespace ini sudah benar

use App\Http\Controllers\Controller;
use App\Models\User; // Model User default Laravel
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; // Digunakan untuk login otomatis

class UserRegisterController extends Controller
{
    use RegistersUsers;

    // Arahkan user ke dashboard setelah register sukses
    protected $redirectTo = '/user/login';

    public function register(Request $request)
    {
        // Panggil validator (menggunakan method validator yang sudah Anda buat)
        $this->validator($request->all())->validate();

        // Ciptakan User (menggunakan method create yang sudah Anda buat)
        $user = $this->create($request->all());

        // JANGAN PANGGIL $this->guard()->login($user);
        // Cukup arahkan ke halaman login yang ditentukan oleh $this->redirectTo
        return redirect($this->redirectTo)
               ->with('status', 'Akun berhasil dibuat! Silakan Login untuk memesan tiket.');
    }
    

    // Guard yang digunakan untuk User biasa (default)
    protected function guard()
    {
        return Auth::guard('web');
    }

    public function showRegistrationForm()
    {
        // View sudah kita koreksi sebelumnya
        return view('auth.user.userregister'); 
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}