
@extends('layout.app')

@section('content')

<style>
    /* 1. OVERRIDE ADMINLTE: Paksa background semua layer jadi hitam */
    body, 
    .wrapper, 
    .content-wrapper,
    .main-footer {
        background-color: #000000 !important;
        background-image: none !important;
        border: none !important;
        margin-left: 0 !important; /* Agar tidak terdorong sidebar jika ada */
    }

    /* 2. Container utama untuk centering vertikal & horizontal */
    .main-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at center, #111111 0%, #000000 100%);
    }

    /* 3. Card Login: Diperlebar (max-width: 750px) */
    .login-card-minimalist {
        width: 100%;
        max-width: 750px; 
        background: linear-gradient(180deg, #1a1a1a, #0d0d0d);
        color: #f2f2f2;
        border: 1px solid #333333;
        border-radius: 12px;
        box-shadow: 0 20px 50px rgba(255, 0, 0, 0.15);
        overflow: hidden;
    }

    /* Header Merah */
    .card-header-red {
        background: linear-gradient(135deg, #b30000, #7a0000);
        color: #ffffff;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        border-bottom: 2px solid #ff0000;
        padding: 25px;
    }

    /* Input Styling */
    .form-control {
        background-color: #121212 !important;
        border: 1px solid #444 !important;
        color: #ffffff !important;
        border-radius: 8px;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #ff3b3b !important;
        box-shadow: 0 0 10px rgba(255, 59, 59, 0.3) !important;
        background-color: #1a1a1a !important;
    }

    /* Label warna abu terang agar kontras di hitam */
    .col-form-label {
        color: #cccccc;
        font-weight: 500;
    }

    /* Tombol Login */
    .bg-red-strong {
        background: linear-gradient(135deg, #ff3b3b, #8b0000) !important;
        border: none !important;
        color: #ffffff !important;
        padding: 12px 35px;
        font-weight: 600;
        border-radius: 50px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .bg-red-strong:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(255, 0, 0, 0.4);
    }

    .text-red-strong {
        color: #ff3b3b !important;
    }

    /* Checkbox */
    .form-check-input {
        background-color: #333;
        border-color: #555;
    }

    .form-check-input:checked {
        background-color: #ff3b3b;
        border-color: #ff3b3b;
    }

    /* Hilangkan garis bawah default AdminLTE pada card */
    .card {
        margin-bottom: 0;
    }
</style>

<div class="main-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-9 col-xl-8">
                <div class="card login-card-minimalist">
                    
                    <div class="card-header card-header-red text-center">
                        <i class="fas fa-user-shield me-2"></i> {{ __('USER LOGIN') }}
                    </div>

                    <div class="card-body p-5">
                        <form method="POST" action="{{ route('user.login') }}">
                            @csrf

                            {{-- Email Address --}}
                            <div class="row mb-4 align-items-center">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                                <div class="col-md-7">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="name@example.com">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong class="text-red-strong">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Password --}}
                            <div class="row mb-4 align-items-center">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                                <div class="col-md-7">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong class="text-red-strong">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Remember Me --}}
                            <div class="row mb-4">
                                <div class="col-md-7 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label text-white-50" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-7 offset-md-4 d-flex align-items-center">
                                    <button type="submit" class="btn bg-red-strong me-3">
                                        <i class="fas fa-sign-in-alt me-2"></i> {{ __('Login') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link text-red-strong p-0" href="{{ route('password.request') }}" style="text-decoration: none;">
                                            {{ __('Forgot Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection