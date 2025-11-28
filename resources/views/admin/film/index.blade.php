@extends('admin.layout.adminapp')

@section('content')

{{-- Wrapper Konten Seragam --}}
<div class="content-wrapper bg-black text-white pt-5" style="min-height: 90vh;">
    <div class="container">

        {{-- =======================
            HEADER JUDUL + TOMBOL
        ======================== --}}
        {{-- Wrapper header dengan lebar max 900px --}}
        <div class="mb-4 mx-auto" style="max-width: 900px;">

            <div class="card bg-dark border-secondary shadow-lg">
                <div class="card-body py-3">

                    <div class="d-flex flex-wrap justify-content-between align-items-center">

                        {{-- Judul --}}
                        <div class="text-white font-weight-bold" style="font-size: 1.6rem;">
                            <i class="fas fa-film mr-2"></i>
                            Daftar Film Bioskop
                        </div>

                        {{-- Tombol Tambah Film --}}
                        <a href="{{ route('admin.film.create') }}"
                            class="btn btn-danger d-flex align-items-center mt-3 mt-md-0 px-3"
                            style="white-space: nowrap; height: 40px;">
                            <i class="fas fa-plus mr-1"></i> Tambah Film Baru
                        </a>

                    </div>

                </div>
            </div>

        </div>
        {{-- ======================= --}}
        {{-- END HEADER --}}
        {{-- ======================= --}}


        {{-- =======================
            ALERT + GRID FILM
        ======================== --}}
        {{-- Wrapper konten data dengan lebar max 900px --}}
        <div class="mx-auto" style="max-width: 900px;">

            {{-- Pesan Sukses/Error --}}
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ $message }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $message }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif


            {{-- =======================
                GRID FILM (Tanpa Card pembungkus utama, langsung row)
            ======================== --}}
            {{-- Row mt-4 diganti mt-0 karena sudah ada margin dari header sebelumnya --}}
            <div class="row justify-content-start m-0">

                @forelse ($films as $film)

                    <div class="film-card-col mb-4 px-2">
                        <div class="card film-card h-100">

                            {{-- Poster (Rasio 2:3) --}}
                            <div class="poster-link-wrapper">
                                <a href="{{ route('admin.film.show', $film->id) }}" style="text-decoration: none;">
                                    <img src="{{ $film->poster_path ? asset('storage/' . str_replace('storage/', '', $film->poster_path)) : asset('images/placeholder.jpg') }}"
                                        alt="Poster {{ $film->judul }}"
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border-radius: 8px 8px 0 0;">
                                </a>
                            </div>

                            {{-- Body Card --}}
                            <div class="card-body card-body-custom d-flex flex-column text-left">
                                <h5 class="card-title card-title-custom font-weight-bold text-white mb-1" title="{{ $film->judul }}">
                                    {{ $film->judul }}
                                </h5>

                                <p class="card-text mb-2">
                                    <span class="badge badge-info">{{ $film->genre }}</span>
                                    <span class="badge badge-warning">{{ $film->durasi_menit }} Min</span>
                                </p>

                                <p class="card-text text-description mb-auto">
                                    {{ Str::limit($film->deskripsi, 40) }}
                                </p>

                                <div class="mt-3">
                                    <a href="{{ route('admin.film.show', $film->id) }}" class="btn btn-info btn-block btn-sm btn-detail-custom">
                                        Lihat & Edit
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>

                @empty
                    <div class="col-12">
                        <div class="alert alert-warning text-center mt-4 mx-2">
                            Tidak ada film yang tersedia saat ini. Silakan tambahkan film baru!
                        </div>
                    </div>
                @endforelse

            </div> {{-- Tutup Row --}}

            {{-- Pagination (Jika ada, diasumsikan $films dipaginasi) --}}
            {{-- Ditaruh di luar row, dan mungkin di dalam card footer terpisah jika ingin ada border bawah --}}
            @if (isset($films) && method_exists($films, 'links') && $films->hasPages())
                <div class="clearfix bg-dark p-3 rounded shadow-lg mt-4"> {{-- Menambahkan div baru untuk pagination --}}
                    {{ $films->links('pagination::bootstrap-4') }}
                </div>
            @endif


        </div>
        {{-- ======================= --}}
        {{-- END GRID FILM WRAPPER --}}
        {{-- ======================= --}}

    </div>
</div>

{{-- Tambahkan Style Khusus di sini untuk mempercantik card --}}
<style>
    /* Mengubah lebar card agar 5 kolom di layar besar (lg) */
    .film-card-col {
        /* Ukuran default untuk mobile (1 kolom) */
        flex: 0 0 100%;
        max-width: 100%;
    }
    /* Ukuran medium (tablet) - 3 kolom per baris */
    @media (min-width: 768px) {
        .film-card-col {
            flex: 0 0 33.33333%; /* 3 kolom */
            max-width: 33.33333%;
        }
    }
    /* Ukuran large (desktop) - 5 kolom per baris */
    @media (min-width: 992px) {
        .film-card-col {
            flex: 0 0 20%; /* 5 kolom */
            max-width: 20%;
        }
    }

    .film-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        background-color: #212529; /* Darker background */
        overflow: hidden;
        border-radius: 8px; /* Sudut lebih melengkung */
        position: relative;
    }

    .film-card:hover {
        transform: translateY(-5px); /* Efek terangkat saat di-hover */
        box-shadow: 0 15px 30px rgba(255, 255, 255, 0.1); /* Bayangan putih samar */
    }

    .poster-link-wrapper {
        position: relative;
        width: 100%;
        height: 0;
        padding-bottom: 180%; /* Disesuaikan untuk rasio yang lebih tinggi (misal 1:1.8 atau 5:9) */
        display: block;
    }

    .poster-link-wrapper img {
        transition: transform 0.5s ease;
        object-fit: cover;
    }

    .film-card:hover .poster-link-wrapper img {
        transform: scale(1.05); /* Zoom in saat di-hover */
    }

    .card-body-custom {
        padding: 10px 12px; /* Padding lebih kecil */
    }

    .card-title-custom {
        font-size: 1rem; /* Judul lebih kecil */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 5px;
    }

    .badge-info, .badge-warning {
        font-size: 0.65rem; /* Badge lebih kecil */
        padding: 4px 6px;
    }

    .text-description {
        font-size: 0.75rem; /* Deskripsi lebih kecil */
        color: #adb5bd !important; /* Warna abu-abu yang lebih jelas di background gelap */
        height: 30px; /* Batasi tinggi deskripsi */
        overflow: hidden;
    }

    .btn-detail-custom {
        font-size: 0.8rem;
        padding: 6px 12px;
        background-color: #00bcd4; /* Warna Biru Cyan yang menarik */
        border-color: #00bcd4;
        font-weight: bold;
    }
</style>

@endsection