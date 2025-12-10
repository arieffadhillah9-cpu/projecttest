@extends('layout.app')

{{-- custom style modern --}}
@section('styles')
<style>
    /* Zoom poster saat hover */
    .film-card:hover img {
        transform: scale(1.08);
    }

    /* Card naik float */
    .film-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(255, 0, 0, 0.25);
    }

    .film-card {
        transition: .35s ease;
        position: relative;
    }

    /* Tombol Pesan Ticket fade-in */
    .pesan-ticket {
        opacity: 0;
        transform: translateY(10px);
        transition: .3s ease;
    }

    .film-card:hover .pesan-ticket {
        opacity: 1;
        transform: translateY(0);
    }

    .film-card img {
        transition: .35s ease;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
    <div class="p-5 text-white" 
    style="background-image: url({{ asset('img/seatly.png') }}); 
    background-size: cover; 
    background-position: right top; /* Fokuskan gambar ke kanan atas */
    position: top; 
    min-height: 400px; /* Gunakan min-height */
    width: 100%;
    display: flex; /* Tambahkan flex untuk penempatan konten */
    align-items: center; /* Posisikan konten di tengah secara vertikal */
    ">
        <!-- Mengurangi lebar overlay gradient agar logo di kanan lebih terlihat -->
        <div style="position: absolute; top: 0; left: 0; width: 40%; height: 100%; background: linear-gradient(to right, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0) 100%);"></div>

        <div class="row w-100" style="position: relative; z-index: 1;">
            <div class="col-md-6">
                <!-- Konten header diganti menjadi placeholder yang lebih umum atau dikosongkan -->
                <h1 class="display-4 font-weight-bold">Your Seat Awaits</h1>
                <p class="lead">
                    Book your movie tickets easily and enjoy the latest blockbusters with the best viewing experience.
                </p>
                <button class="btn btn-danger btn-lg"><i class="fas fa-ticket-alt mr-2"></i> Find Movies</button>
            </div>
        </div>
    </div>
    
    <div class="card card-dark rounded-0 mb-0" style="background-color: #1a1a1a;">
        <div class="card-body">
            <div class="row align-items-center">

                <div class="col-md-3">
                    <label class="text-white">CHOOSE DATE</label>
                    <div class="d-flex text-white">
                        <a href="#" class="text-white mr-3">MON <span class="badge bg-danger">30</span></a>
                        <a href="#" class="text-white mr-3">TUE</a>
                        <a href="#" class="text-white mr-3">WED</a>
                    </div>
                </div>

                <div class="col-md-5">
                    <label class="text-white">CHOOSE TIME</label>
                    <div class="d-flex text-white">
                        <a href="#" class="btn btn-outline-light btn-sm mr-2">15:00</a>
                        <a href="#" class="btn btn-outline-light btn-sm mr-2">17:00</a>
                        <a href="#" class="btn btn-outline-light btn-sm mr-2">19:00</a>
                        <a href="#" class="btn btn-danger btn-sm">21:00</a>
                    </div>
                </div>

                <div class="col-md-4 text-right">
                    <button class="btn btn-danger btn-lg">Buy ticket</button>
                </div>

            </div>
        </div>
    </div>
</div>

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
                    <input type="text" class="form-control" placeholder="Search movie" 
                    style="background-color: #333333; border: none; color: white;">
                    <div class="input-group-append">
                        <span class="input-group-text bg-danger border-0"><i class="fas fa-search"></i></span>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            @forelse ($films as $film)

            {{-- CARD FILM MODERN --}}
            <div class="col-lg-2 col-md-3 col-sm-4 mb-4"> 
                <div class="card film-card border-0 text-white h-100 d-flex flex-column"
                    style="background: #111; border-radius: 14px; overflow: hidden; cursor: pointer;">

                    <div style="overflow: hidden; border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <img src="{{ asset('storage/' . $film->poster_path) }}"
                            alt="{{ $film->judul }}"
                            class="card-img-top"
                            style="height: 280px; object-fit: cover; transition: .35s;">
                    </div>

                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        
                        <div>
                            <h6 class="card-title font-weight-bold mb-1"
                                style="line-height: 1.3; min-height: 2.6em;">
                                {{ $film->judul }}
                            </h6>

                            <p class="text-muted mb-2" style="font-size: 12px;">
                                {{ $film->genre }} • {{ $film->durasi_menit }} min
                            </p>
                        </div>

                        <div class="mt-auto">

                            <!-- Tombol Pesan Ticket (baru) -->
                            <a href="{{ route('film.schedule', ['filmId' => $film->id]) }}"
                               class="btn btn-danger btn-sm pesan-ticket w-100 mb-2"
                               style="border-radius: 20px; padding: 6px 14px;">
                                Pesan Ticket
                            </a>

                            <a href="#"
                               class="btn btn-outline-light btn-sm"
                               style="border-radius: 20px; padding: 6px 14px;">
                                Lihat Detail
                            </a>

                        </div>

                    </div>
                </div>
            </div>

            @empty
            <div class="col-12 text-center py-5">
                <h3 class="text-white">Belum ada film yang sedang tayang.</h3>
                <p class="text-muted">Silakan cek kembali nanti atau hubungi Admin.</p>
            </div>
            @endforelse
            
            <div class="col-12 mt-4 text-center">
                <button class="btn btn-danger btn-md">Show more</button> 
            </div>

        </div>
    </div>
</div>
@endsection