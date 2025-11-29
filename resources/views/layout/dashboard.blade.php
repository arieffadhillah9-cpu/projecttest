@extends('layout.app')

{{-- Menambahkan custom style untuk background lembut seperti referensi --}}
@section('styles')

@endsection
@section('content')

@php
    // Definisikan film hero (atau placeholder jika tidak ada film)
    $filmHero = $filmHero ?? (object)['title' => 'Film Not Found', 'sinopsis' => 'No movie data available.', 'durasi' => 0];
    
    // Ambil jadwal pertama hari ini sebagai patokan tautan Buy Ticket
    $firstJadwal = $jadwalHariIni->first();
@endphp

<div class="container-fluid p-0">
    <!-- HERO BANNER (Top Section) -->
    <div class="p-5 text-white hero-banner" 
    style="background-image: url('{{ asset('adminlte/dist/img/background_placeholder.jpg') }}');"
    >
        {{-- Gradasi Hitam (Opacity Overlay) --}}
        <div style="position: absolute; top: 0; left: 0; width: 60%; height: 100%; background: linear-gradient(to right, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%);"></div>

        <div class="row" style="position: relative; z-index: 1;">
            <div class="col-md-6 pt-5">
                <p class="mt-5 mb-0 text-right"><small>{{ $filmHero->durasi ?? 'N/A' }} mins.</small></p>
                <h1 class="display-4 font-weight-bold">{{ $filmHero->title }}</h1>
                <p class="lead">{{ Str::limit($filmHero->sinopsis ?? 'Sinopsis tidak tersedia.', 200) }}</p>
                <button class="btn btn-danger btn-lg"><i class="fas fa-play mr-2"></i> Watch trailer</button>
            </div>
        </div>
    </div>

    <!-- Pilihan Tanggal & Waktu (Cek Jadwal Film Hero Hari Ini) -->
    <div class="card card-dark rounded-0 mb-0">
        <div class="card-body">
            @if ($filmHero->title != 'Film Not Found' && $jadwalHariIni->isNotEmpty())
                <div class="row align-items-center">
                    {{-- CHOOSE DATE (Hanya Menampilkan Hari Ini) --}}
                    <div class="col-md-3">
                        <label class="text-white">CHOOSE DATE</label>
                        <div class="d-flex text-white">
                            {{-- Kita hanya menampilkan hari ini (asumsi hari ini adalah hari yang dipilih) --}}
                            <span class="text-white mr-3">{{ strtoupper(\Carbon\Carbon::today()->isoFormat('ddd')) }} 
                                <span class="badge {{ \Carbon\Carbon::today()->isToday() ? 'bg-danger' : 'bg-secondary' }}">
                                    {{ \Carbon\Carbon::today()->format('d') }}
                                </span>
                            </span>
                            {{-- Tambahkan lebih banyak link tanggal jika diperlukan, tapi ini sederhana untuk demo --}}
                        </div>
                    </div>
                    
                    {{-- CHOOSE TIME (Jadwal Hari Ini untuk Film Hero) --}}
                    <div class="col-md-5">
                        <label class="text-white">CHOOSE TIME</label>
                        <div class="d-flex text-white flex-wrap">
                            @foreach ($jadwalHariIni as $jadwal)
                                @php
                                    $time = \Carbon\Carbon::parse($jadwal->waktu_tayang)->format('H:i');
                                @endphp
                                <a href="{{ route('pemesanan.seatpicker', $jadwal->id) }}" 
                                   class="btn btn-outline-light btn-sm mr-2 mb-2 {{ $loop->first ? 'btn-danger border-danger' : '' }}">
                                    {{ $time }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    
                    {{-- BUTTON BUY TICKET (Arahkan ke jadwal pertama hari ini) --}}
                    <div class="col-md-4 text-right">
                        {{-- Link Buy Ticket mengarahkan ke halaman pemilihan kursi untuk jadwal pertama yang tersedia hari ini --}}
                        <a href="{{ route('pemesanan.seatpicker', $firstJadwal->id) }}" class="btn btn-danger btn-lg">Buy ticket</a>
                    </div>
                </div>
            @else
                <div class="text-white text-center py-2">
                    <p class="mb-0">Tidak ada jadwal tayang untuk film utama hari ini.</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- FILM GRID (Content Section) -->
    <div class="content" style="background-color: #000000; padding-top: 20px; padding-bottom: 50px;">
        <div class="container">
            <div class="row mb-4 text-white align-items-center">
                <div class="col-md-8 d-flex">
                    <a href="#" class="text-white mr-3 border-right pr-3">All movies</a>
                    <a href="#" class="text-white mr-3 border-right pr-3">By Date <i class="fas fa-caret-down ml-1"></i></a>
                    <a href="#" class="text-white mr-3 border-right pr-3">By Category <i class="fas fa-caret-down ml-1"></i></a>
                    <a href="#" class="text-danger">Coming soon</a>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="Search movie" style="background-color: #333333; border: none; color: white;">
                        <div class="input-group-append">
                            <span class="input-group-text bg-danger border-0"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                @forelse ($films as $film)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card bg-dark border-0 text-white movie-poster-card">
                            {{-- Gambar Poster Dinamis --}}
                            <img src="https://placehold.co/400x600/333333/FFFFFF?text={{ urlencode($film->title) }}" 
                                 class="card-img-top rounded-0" alt="{{ $film->title }}">
                            
                            <div class="card-body p-2">
                                <h5 class="card-title font-weight-bold mb-1">{{ $film->title }}</h5>
                                <div class="d-flex flex-wrap">
                                    {{-- Tampilkan Jadwal Tayang Film ini (maksimal 4 jam tayang berbeda) --}}
                                    @php
                                        // Ambil 4 jadwal unik hari ini (hanya waktu tayang)
                                        $jadwalsFilm = $film->jadwalTayang
                                            ->filter(fn($j) => \Carbon\Carbon::parse($j->waktu_tayang)->isToday())
                                            ->unique(fn($j) => \Carbon\Carbon::parse($j->waktu_tayang)->format('H:i'))
                                            ->take(4);
                                    @endphp

                                    @forelse ($jadwalsFilm as $jadwal)
                                        <a href="{{ route('pemesanan.seatpicker', $jadwal->id) }}">
                                            <small class="mr-2 {{ $loop->last ? 'text-danger' : 'text-white' }}">
                                                {{ \Carbon\Carbon::parse($jadwal->waktu_tayang)->format('H:i') }}
                                            </small>
                                        </a>
                                    @empty
                                        <small class="text-muted">Tidak ada jadwal hari ini.</small>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-white py-5">
                        <p class="lead">Tidak ada film yang sedang tayang saat ini.</p>
                    </div>
                @endforelse
                
                <div class="col-12 mt-4 text-center">
                    <button class="btn btn-danger btn-lg">Show more</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection