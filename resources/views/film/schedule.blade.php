@extends('layout.app') {{-- Menggunakan layout publik --}}

@section('title', 'Jadwal Tayang Film')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Jadwal Tayang untuk Film: {{ $film->judul }}</h2>
    <p class="lead">Pilih jadwal di bawah ini untuk memulai pemesanan tiket.</p>

    @if($jadwalTayang->isEmpty())
        <div class="alert alert-warning">
            Maaf, belum ada jadwal tayang tersedia untuk film ini.
        </div>
    @else
        <div class="list-group">
            @foreach ($jadwalTayang as $jadwal)
                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">Studio {{ $jadwal->studio->nama }}</h5>
                        <p class="mb-1">
                            Tanggal: **{{ \Carbon\Carbon::parse($jadwal->tanggal_tayang)->format('d M Y') }}**
                            <br>
                            Jam Mulai: **{{ $jadwal->jam_mulai }}**
                            <br>
                            Harga: **Rp {{ number_format($jadwal->harga_tiket) }}**
                        </p>
                    </div>
                    <div>
                        @auth
                            {{-- Jika user sudah login, arahkan ke rute pemilihan kursi --}}
                            <a href="{{ route('user.pemesanan.select_seat', ['jadwalId' => $jadwal->id]) }}" class="btn btn-primary">
                                Pilih Kursi
                            </a>
                        @else
                            {{-- Jika user belum login, minta login --}}
                            <a href="{{ route('login') }}" class="btn btn-secondary">
                                Login untuk Pesan
                            </a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection