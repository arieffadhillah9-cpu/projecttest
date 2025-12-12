@extends('layout.app') {{-- <--- UBAH KE LAYOUT USER/UMUM --}}

@section('content')

{{-- Tambahkan CSS inline untuk tampilan Futuristik & Minimalis --}}
{{-- Tambahkan CSS inline untuk tampilan Futuristik & Minimalis --}}
<style>
    /* 1. Background Paling Gelap */
    body {
        background-color: #0d0d0d !important; /* Hitam sangat pekat */
        color: #f0f0f0 !important;
    }
    /* 2. Card Login Minimalis */
    .login-card-minimalist {
        background-color: #1c1c1c; /* Abu-abu gelap kontras */
        color: #f0f0f0;
        border: 1px solid #333333; /* Border tipis untuk definisi */
        border-radius: 4px;
        box-shadow: 0 4px 15px rgba(255, 0, 0, 0.15); /* Bayangan merah halus */
    }
    /* 3. Aksen Merah Tajam */
    .text-red-strong {
        color: #ff3333 !important; /* Merah terang/cerah */
    }
    .bg-red-strong {
        background-color: #ff3333 !important; /* Merah solid untuk tombol */
        border-color: #ff3333 !important;
        color: white !important;
        transition: background-color 0.2s;
    }
    .bg-red-strong:hover {
        background-color: #cc0000 !important;
    }
    /* 4. Header Card Merah Gelap */
    .card-header-red {
        background-color: #990000;
        color: white;
        font-weight: bold;
        border-bottom: 1px solid #ff3333;
        font-size: 1.25rem;
    }
    /* 5. Input Field Gelap */
    .form-control {
        background-color: #2a2a2a;
        border-color: #444444;
        color: #f0f0f0;
    }
    .form-control:focus {
        background-color: #2a2a2a;
        border-color: #ff3333; /* Border merah saat fokus */
        box-shadow: 0 0 0 0.25rem rgba(255, 51, 51, 0.25);
        color: #f0f0f0;
    }
    /* 6. Fix class text-md-end agar tetap putih */
    .text-md-end {
        color: #f0f0f0 !important;
    }
    /* 7. Fix link btn-link agar sesuai tema */
    .btn-link {
        color: #ff3333 !important;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card login-card-minimalist">
                
                <div class="card-header card-header-red text-center">
                    <i class="fas fa-lock mr-2"></i> {{ __('USER ACCESS') }}
                </div>

                <div class="card-body p-4">
                    {{-- UBAH ACTION KE RUTE LOGIN USER --}}
                    <form method="POST" action="{{ route('user.login') }}">
                        @csrf

                        {{-- Email Address --}}
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-red-strong">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-red-strong">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Remember Me --}}
                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label text-white" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                {{-- Tombol Login --}}
                                <button type="submit" class="btn btn-primary bg-red-strong">
                                    <i class="fas fa-sign-in-alt mr-2"></i> {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    {{-- Link Lupa Password --}}
                                    <a class="btn btn-link text-red-strong" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
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
@endsection