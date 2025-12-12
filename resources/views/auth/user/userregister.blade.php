@extends('layout.app') {{-- MENGGUNAKAN Master Layout Admin --}}

@section('content')

{{-- Tambahkan CSS inline untuk tampilan Futuristik & Minimalis, diseragamkan dengan login.blade.php --}}
<style>
    /* Menggunakan style yang sama dengan Login.blade.php */
    
    /* 1. Background Paling Gelap (Diambil dari adminapp body) */
    /* 2. Card Register Minimalis (Menggunakan card default) */
    .login-card-minimalist {
        background-color: #1c1c1c; /* Abu-abu gelap kontras */
        color: #f0f0f0;
        border: 1px solid #333333; /* Border tipis untuk definisi */
        border-radius: 8px; /* Sedikit lebih membulat */
        box-shadow: 0 4px 20px rgba(255, 0, 0, 0.2); /* Bayangan merah lebih kuat */
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
        border-radius: 4px;
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
    /* Menyesuaikan lebar kolom untuk form register */
    .col-md-7-reg {
        flex: 0 0 auto;
        width: 58.33333333%; /* Sedikit lebih lebar dari login untuk menampung 4 field */
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        {{-- Mengubah lebar kolom menjadi lebih minimalis --}}
        <div class="col-md-5">
            {{-- Mengubah card default menjadi login-card-minimalist --}}
            <div class="card login-card-minimalist">
                
                {{-- Header Card dengan Aksen Merah --}}
                <div class="card-header card-header-red text-center">
                    <i class="fas fa-user-plus mr-2"></i> {{ __('USER REGISTRATION') }}
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('user.register') }}">
                        @csrf
                        {{-- Name --}}
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-7">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-red-strong">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Email Address --}}
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-7">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

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

                            <div class="col-md-7">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-red-strong">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-7">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-7 offset-md-4">
                                {{-- Tombol Register diubah menjadi Merah Solid (bg-red-strong) --}}
                                <button type="submit" class="btn btn-primary bg-red-strong">
                                    <i class="fas fa-user-plus mr-2"></i> {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection