@extends('layout.app')

{{-- custom style modern --}}
@section('styles')
<style>
    .film-card:hover img {
        transform: scale(1.08);
    }

    .film-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(255, 0, 0, 0.25);
    }

    .film-card {
        transition: .35s ease;
        position: relative;
    }

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
    {{-- HERO BANNER --}}
    <div class="p-5 text-white" 
        style="background-image: url({{ asset('img/seatly.png') }}); 
        background-size: cover; 
        background-position: right top;
        min-height: 400px;
        width: 100%;
        display: flex;
        align-items: center;
        position: relative;
    ">
        <div style="position: absolute; top: 0; left: 0; width: 40%; height: 100%; background: linear-gradient(to right, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0) 100%);"></div>

        <div class="row w-100" style="position: relative; z-index: 1;">
            <div class="col-md-6">
                <h1 class="display-4 font-weight-bold">Your Seat Awaits</h1>
                <p class="lead">
                    Book your movie tickets easily and enjoy the latest blockbusters with the best viewing experience.
                </p>
                <button class="btn btn-danger btn-lg">
                    <i class="fas fa-ticket-alt mr-2"></i> Find Movies
                </button>
            </div>
        </div>
    </div>
</div>

{{-- CONTENT --}}
<div class="content" style="background-color: #000000; padding-top: 30px; padding-bottom: 50px;">
    <div class="container">


        {{-- FILM LIST --}}
        <div class="row">

            @forelse ($films as $film)
            <div class="col-lg-2 col-md-3 col-sm-4 mb-4"> 
                <div class="card film-card border-0 text-white h-100 d-flex flex-column"
                    style="background: #111; border-radius: 14px; overflow: hidden; cursor: pointer;">

                    <div style="overflow: hidden; border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <img src="{{ asset('storage/' . $film->poster_path) }}"
                            alt="{{ $film->judul }}"
                            class="card-img-top"
                            style="height: 280px; object-fit: cover;">
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
                            <a href="{{ route('film.schedule', ['filmId' => $film->id]) }}"
                               class="btn btn-danger btn-sm pesan-ticket w-100 mb-2"
                               style="border-radius: 20px;">
                                Pesan Ticket
                            </a>

                           
                        </div>
                    </div>
                </div>
            </div>

            @empty
            <div class="col-12 text-center py-5">
                <h3 class="text-white">Belum ada film yang sedang tayang.</h3>
                <p class="text-muted">Silakan cek kembali nanti.</p>
            </div>
            @endforelse

           

        </div>
    </div>
</div>
@endsection