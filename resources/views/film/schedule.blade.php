@extends('layout.app')

@section('title', 'Jadwal Tayang - ' . $film->judul)

@section('content')
<div class="min-h-screen bg-black py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-12">
            <a href="{{ route('dashboard.user') }}" class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-zinc-400 hover:text-white transition-colors duration-200 mb-6 group">
                <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i> Kembali ke Dashboard
            </a>
            
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white">
                Jadwal Tayang: <span class="bg-gradient-to-r from-red-600 to-rose-500 bg-clip-text text-transparent">{{ $film->judul }}</span>
            </h1>
            <p class="text-sm text-zinc-400 mt-2 font-light">Pilih jadwal tayang di bawah untuk memulai pemesanan tiket Anda.</p>
        </div>

        @if($jadwalTayang->isEmpty())
            <div class="text-center py-16 border border-dashed border-zinc-800 rounded-2xl bg-zinc-950/20">
                <i class="fas fa-calendar-times text-4xl text-zinc-700 mb-4"></i>
                <h3 class="text-lg font-semibold text-zinc-400">Belum ada jadwal tayang.</h3>
                <p class="text-sm text-zinc-600 mt-1">Maaf, belum ada jadwal tayang tersedia untuk film ini saat ini.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach ($jadwalTayang as $jadwal)
                    <div class="relative overflow-hidden bg-zinc-950/40 border border-zinc-900/60 rounded-2xl p-6 transition-all duration-300 hover:border-zinc-800 hover:-translate-y-1 hover:shadow-xl hover:shadow-red-500/5 backdrop-blur-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                        <!-- Left Details -->
                        <div class="flex-grow">
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 text-[10px] font-semibold uppercase tracking-wider text-rose-500 bg-rose-500/10 rounded-full border border-rose-500/20">
                                    Studio {{ $jadwal->studio->nama }}
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-4 text-sm">
                                <div>
                                    <span class="text-xs text-zinc-500 block">Tanggal</span>
                                    <span class="font-medium text-zinc-200">{{ \Carbon\Carbon::parse($jadwal->tanggal_tayang)->format('d M Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-zinc-500 block">Jam Mulai</span>
                                    <span class="font-medium text-zinc-200">{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} WIB</span>
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <span class="text-xs text-zinc-500 block">Harga Tiket</span>
                                    <span class="font-bold text-rose-500">Rp {{ number_format($jadwal->harga_tiket, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="w-full sm:w-auto shrink-0 flex items-center justify-end">
                            @auth
                                <a href="{{ route('user.pemesanan.select_seat', ['jadwalId' => $jadwal->id]) }}" 
                                   class="w-full sm:w-auto text-center px-6 py-3 text-xs font-bold uppercase tracking-wider text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 rounded-full transition-all duration-300 shadow-md shadow-red-950/40 active:scale-95">
                                    Pilih Kursi
                                </a>
                            @else
                                <a href="{{ route('user.login') }}" 
                                   class="w-full sm:w-auto text-center px-6 py-3 text-xs font-bold uppercase tracking-wider text-zinc-300 hover:text-white bg-zinc-900 hover:bg-zinc-800 border border-zinc-850 rounded-full transition-all duration-300 active:scale-95">
                                    Login untuk Pesan
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection