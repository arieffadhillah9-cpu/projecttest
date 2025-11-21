<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; // Import kelas Request

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     *
     * PERBAIKAN: Diarahkan ke path Dashboard Admin yang baru.
     * Path ini harus sesuai dengan gabungan prefix ('admin') dan route ('/dashboardmin').
     */
    protected $redirectTo = '/admin/dashboardmin'; 

    /**
     * Handle a successful logout.
     * Metode ini akan menimpa metode default dari trait AuthenticatesUsers.
     * Tujuannya: Memastikan pengguna diarahkan ke halaman login.
     */
    protected function loggedOut(Request $request)
    {
        // Mengarahkan pengguna langsung ke route 'login'
        return redirect()->route('login');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}