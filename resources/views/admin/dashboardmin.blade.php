@extends('admin.layout.adminapp')

@section('content')

{{-- Tambahkan CSS inline untuk tampilan Kontras Tinggi --}}
<style>
    /* 1. Background Halaman Paling Gelap */
    body {
        background-color: #000000 !important; /* Hitam pekat */
        color: #e0e0e0 !important;
    }
    /* 2. Style untuk Card Konten (Sangat gelap, hampir hitam) */
    .contrast-card {
        background-color: #1a1a1a;
        color: #ffffff;
        border: 1px solid #333333; /* Border tipis untuk definisi */
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(255, 0, 0, 0.1); /* Bayangan merah samar */
        transition: all 0.3s ease;
    }
    .contrast-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(255, 0, 0, 0.2);
    }
    /* 3. Aksen Merah Tajam */
    .text-red-strong {
        color: #FF0000 !important; /* Merah neon/terang */
    }
    .bg-red-strong {
        background-color: #CC0000 !important; /* Merah solid */
        border-color: #CC0000 !important;
    }
    /* 4. Style untuk Menu Link */
    .menu-link-item {
        background-color: #1a1a1a !important;
        border: none;
        color: #cccccc !important;
        transition: background-color 0.2s, border-left 0.2s;
        border-left: 5px solid transparent;
        border-radius: 0;
    }
    .menu-link-item:hover {
        background-color: #2a2a2a !important;
        border-left-color: #FF0000; /* Aksen merah saat di hover */
        color: #ffffff !important;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="mb-5 p-4 text-center contrast-card bg-red-strong">
    <h1 class="display-4 font-weight-bolder text-white">
        COMMAND CENTER
    </h1>
    <p class="lead text-white">AREA ADMINISTRATOR</p>
</div>

            {{-- 1. KONTEN SELAMAT DATANG & INFO --}}
            <div class="row mb-5">
                <div class="col-12">
                    <div class="contrast-card p-4">
                        <p class="lead text-white border-bottom border-secondary pb-2 mb-3">Selamat datang, <strong class="text-red-strong">{{ Auth::user()->name }}</strong>!</p>
                        <p class="text-white-50 mb-0">Anda telah berhasil masuk ke Dashboard Admin. Semua kontrol sistem berada di bawah pengawasan Anda. Gunakan menu di bawah untuk mulai mengelola.</p>
                    </div>
                </div>
            </div>

            {{-- 2. BAGIAN MENU UTAMA (Tombol Aksi Kuat) --}}
            <h4 class="mb-3 text-red-strong border-bottom border-secondary pb-2">Manajemen Inti</h4>

            <div class="row">
                <div class="col-12">
                    <div class="list-group">
                        
                        {{-- Menu 1: Kelola Film --}}
                        <a href="{{ route('admin.film.index') }}" class="list-group-item list-group-item-action menu-link-item">
                            <div class="d-flex w-100 justify-content-between align-items-center py-2">
                                <h5 class="mb-1"><i class="fas fa-film mr-3 text-red-strong"></i> Kelola Film</h5>
                                <span class="text-muted"><i class="fas fa-chevron-right"></i></span>
                            </div>
                        </a>

                        {{-- Menu 2: Kelola Studio --}}
                        <a href="{{ route('admin.studio.index') }}" class="list-group-item list-group-item-action menu-link-item">
                            <div class="d-flex w-100 justify-content-between align-items-center py-2">
                                <h5 class="mb-1"><i class="fas fa-chair mr-3 text-red-strong"></i> Kelola Studio</h5>
                                <span class="text-muted"><i class="fas fa-chevron-right"></i></span>
                            </div>
                        </a>
                        
                        {{-- Menu 3: Kelola Jadwal Tayang --}}
                        <a href="{{ route('admin.jadwal.index') }}" class="list-group-item list-group-item-action menu-link-item">
                            <div class="d-flex w-100 justify-content-between align-items-center py-2">
                                <h5 class="mb-1"><i class="fas fa-clock mr-3 text-red-strong"></i> Kelola Jadwal Tayang</h5>
                                <span class="text-muted"><i class="fas fa-chevron-right"></i></span>
                            </div>
                        </a>
                        
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <p class="text-muted"><small>Akses ini dilindungi secara ketat. Semua aktivitas diawasi.</small></p>
            </div>
            
        </div>
    </div>
</div>
@endsection