@extends('layout.app')

@section('title', 'Seatly - Dashboard')

@section('content')
<div class="w-full">
    {{-- HERO BANNER --}}
    <div class="relative overflow-hidden min-h-[450px] flex items-center bg-black">
        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-right-top md:bg-right" 
             style="background-image: url('{{ asset('img/seatly.png') }}');">
        </div>
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-black via-black/85 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>

        <!-- Content -->
        <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 relative z-10 py-16">
            <div class="max-w-xl">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-white via-zinc-200 to-zinc-400">
                    Your Seat Awaits
                </h1>
                <p class="mt-4 text-base sm:text-lg text-zinc-400 leading-relaxed font-light">
                    Book your movie tickets easily and enjoy the latest blockbusters with a premium, seamless viewing experience.
                </p>
                <div class="mt-8">
                    <a href="#movies-list" class="inline-flex items-center gap-2 px-6 py-3.5 text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 rounded-full transition-all duration-300 shadow-lg shadow-red-950/40 hover:shadow-red-500/20 active:scale-95 transform">
                        <i class="fas fa-ticket-alt"></i> Find Movies
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CONTENT --}}
<div id="movies-list" class="bg-black py-16 border-t border-zinc-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-white">Now Showing</h2>
                <p class="text-sm text-zinc-500 mt-1">Select a movie to book your tickets.</p>
            </div>
        </div>

        {{-- FILM GRID --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">

            @forelse ($films as $film)
            <div class="group relative bg-zinc-950/40 border border-zinc-900/60 rounded-2xl overflow-hidden hover:border-zinc-800 transition-all duration-500 hover:shadow-2xl hover:shadow-red-500/10 hover:-translate-y-2 flex flex-col h-full cursor-pointer backdrop-blur-sm">
                <!-- Film Poster -->
                <div class="overflow-hidden relative aspect-[2/3] w-full bg-zinc-900">
                    <img src="{{ asset('storage/' . $film->poster_path) }}"
                         alt="{{ $film->judul }}"
                         class="w-full h-full object-cover transition-all duration-500 group-hover:scale-105">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                        <a href="{{ route('film.schedule', ['filmId' => $film->id]) }}"
                           class="w-full text-center py-2.5 text-xs font-semibold uppercase tracking-wider text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 rounded-full transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 active:scale-95 shadow-md shadow-red-950/40">
                            Pesan Tiket
                        </a>
                    </div>
                </div>

                <!-- Film Details -->
                <div class="p-4 flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-100 group-hover:text-white line-clamp-2 leading-snug transition-colors duration-200 min-h-[40px]">
                            {{ $film->judul }}
                        </h3>
                        <p class="text-xs text-zinc-500 mt-1">
                            {{ $film->genre }}
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-zinc-900/50 flex items-center justify-between text-[10px] text-zinc-400 font-medium uppercase tracking-wider">
                        <span>{{ $film->durasi_menit }} min</span>
                        <span class="text-rose-500 font-semibold">Active</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16 border border-dashed border-zinc-800 rounded-2xl bg-zinc-950/20">
                <i class="fas fa-film text-4xl text-zinc-700 mb-4"></i>
                <h3 class="text-lg font-semibold text-zinc-400">Belum ada film yang sedang tayang.</h3>
                <p class="text-sm text-zinc-600 mt-1">Silakan cek kembali nanti.</p>
            </div>
            @endforelse

        </div>
    </div>
</div>
@endsection