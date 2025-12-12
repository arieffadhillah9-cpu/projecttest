@extends('layout.app')

@section('title', 'Jadwal Tayang Film')

@section('content')

<style>
/* ===== WRAPPER ===== */
.jadwal-wrapper {
    padding: 100px 0;
    background: linear-gradient(180deg, #0b0b0b 0%, #000000 100%);
    min-height: 95vh;
    color: #fff;
}

/* ===== TITLE ===== */
.jadwal-title {
    font-weight: 700;
    font-size: 3rem;
    margin-bottom: 20px;
    color: #ff3b3b;
    text-shadow: 0 0 10px rgba(255,0,0,0.4);
}

.jadwal-subtitle {
    color: #ddd;
    font-size: 1.1rem;
}

/* ===== CARD LIST ===== */
.list-group-item {
    background: linear-gradient(180deg, #141414 0%, #0f0f0f 100%) !important;
    border: 1px solid rgba(255, 0, 0, 0.25) !important;
    border-radius: 14px !important;
    padding: 22px !important;
    margin-bottom: 25px;
    transition: 0.3s ease;
    position: relative;
    overflow: hidden;
}

.list-group-item::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at top left, rgba(255,0,0,0.08), transparent 60%);
    pointer-events: none;
}

.list-group-item:hover {
    transform: translateY(-4px) scale(1.01);
    border-color: #ff3b3b !important;
    box-shadow: 0 0 25px rgba(255, 0, 0, 0.35);
}

.list-group-item h5 {
    font-size: 1.35rem;
    color: #ff4d4d;
    font-weight: 700;
    text-shadow: 0 0 12px rgba(255,0,0,0.3);
}

.list-group-item p {
    color: #ccc;
    font-size: 1rem;
    line-height: 1.6;
    margin-top: 4px;
}

/* ===== BUTTONS ===== */
.btn-primary {
    background: linear-gradient(90deg, #d60000, #ff1b1b) !important;
    border: none !important;
    padding: 12px 26px;
    border-radius: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: 0.25s ease;
} {
    background: linear-gradient(90deg, #d60000, #ff1b1b) !important;
    border: none !important;
    padding: 10px 22px;
    border-radius: 10px;
    font-weight: 600;
    letter-spacing: 0.4px;
    transition: 0.25s;
}

.btn-primary:hover {
    background: linear-gradient(90deg, #ff2424, #ff4d4d) !important;
    transform: translateY(-2px);
    box-shadow: 0 0 15px rgba(255,0,0,0.35);
}

.btn-secondary {
    background: #333 !important;
    border: 1px solid #555 !important;
    padding: 10px 22px;
    border-radius: 10px;
    font-weight: 600;
    transition: 0.2s;
}

.btn-secondary:hover {
    background: #444 !important;
}
</style>

<div class="jadwal-wrapper">
<div class="container">
    <h2 class="jadwal-title">Jadwal Tayang: {{ $film->judul }}</h2>
    <p class="jadwal-subtitle">Pilih jadwal di bawah untuk memulai pemesanan tiket.</p>

    @if($jadwalTayang->isEmpty())

     <div class="alert alert-warning mt-4">
            Maaf, belum ada jadwal tayang tersedia untuk film ini.
        </div>
    @else
    <div class="list-group mt-4">
            @foreach ($jadwalTayang as $jadwal)
                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-2">Studio {{ $jadwal->studio->nama }}</h5>
                        <p class="mb-1">
                            Tanggal: <strong>{{ \Carbon\Carbon::parse($jadwal->tanggal_tayang)->format('d M Y') }}</strong><br>
                            Jam Mulai: <strong>{{ $jadwal->jam_mulai }}</strong><br>
                            Harga: <strong>Rp {{ number_format($jadwal->harga_tiket) }}</strong>
                        </p>
                    </div>
                    <div>
                        @auth
                        <a href="{{ route('user.pemesanan.select_seat', ['jadwalId' => $jadwal->id]) }}" class="btn btn-primary">Pilih Kursi</a>
                        @else
                         <a href="{{ route('user.login') }}" class="btn btn-secondary">Login untuk Pesan</a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
</div>
@endsection