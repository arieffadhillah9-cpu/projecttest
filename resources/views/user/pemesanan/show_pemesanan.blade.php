{{-- resources/views/user/pemesanan/show_pemesanan.blade.php --}}

@extends('layout.dashboard') 

@section('title', 'Detail Pembayaran')

@section('content')
<style>
    /* Global Background & Text */
    body, .wrapper, .content-wrapper {
        background-color: #000000 !important;
        color: #e0e0e0 !important;
    }

    /* Card Styling - Ramping dan Elegan */
    .card-payment {
        background: #111111 !important;
        border: 1px solid #333 !important;
        border-radius: 15px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
        max-width: 850px;
        margin: 2rem auto;
        overflow: hidden;
    }

    .card-payment .card-header {
        background: linear-gradient(135deg, #b30000, #660000) !important;
        border-bottom: 2px solid #ff0000 !important;
        padding: 1.25rem;
    }

    .card-payment .card-header h6 {
        color: #ffffff !important;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        margin: 0;
    }

    /* List Group Custom */
    .list-group-item-dark {
        background-color: #1a1a1a !important;
        border-color: #333 !important;
        color: #ccc !important;
    }

    .list-group-item-dark strong {
        color: #ffffff;
    }

    /* Alert & Section Titles */
    h5 {
        color: #ff3b3b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .bill-box {
        background: linear-gradient(135deg, #1a1a1a, #0d0d0d);
        border: 1px solid #ff3b3b;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
    }

    .bill-box h3 {
        color: #ff3b3b;
        font-weight: 800;
        margin-top: 10px;
    }

    /* Buttons */
    .btn-red {
        background: linear-gradient(135deg, #ff3b3b, #b30000);
        border: none;
        color: white;
        font-weight: 700;
        padding: 10px 25px;
        border-radius: 8px;
        transition: 0.3s;
    }

    .btn-red:hover:not(:disabled) {
        box-shadow: 0 0 15px rgba(255, 0, 0, 0.5);
        transform: translateY(-2px);
        color: white;
    }

    .btn-red:disabled {
        background: #444;
        color: #888;
    }

    .btn-outline-gray {
        border: 1px solid #444;
        color: #aaa;
        background: transparent;
        border-radius: 8px;
        padding: 10px 25px;
    }

    .btn-outline-gray:hover {
        background: #222;
        color: #fff;
    }

    #countdown-timer {
        font-family: 'Courier New', Courier, monospace;
        font-weight: bold;
        background: #b30000;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
    }
</style>

<div class="container-fluid">
    <div class="card card-payment">
        <div class="card-header">
            <h6><i class="fas fa-receipt mr-2"></i> Detail Transaksi #{{ $pemesanan->kode_pemesanan }}</h6>
        </div>
        <div class="card-body p-4">
            
            @if (session('success'))
                <div class="alert alert-success bg-success text-white border-0">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger bg-danger text-white border-0">{{ session('error') }}</div>
            @endif
            
            <div class="row">
                <div class="col-md-6 border-right border-secondary">
                    
                    {{-- 1. STATUS PEMBAYARAN --}}
                    @if ($pemesanan->status === 'menunggu_pembayaran')
                        <h5>Waktu Pembayaran</h5>
                        <p class="text-white">
                            Batas: 
                            <span id="countdown-timer" data-expires="{{ $waktuKadaluwarsa->timestamp }}">
                                {{ $waktuKadaluwarsa->format('H:i:s') }}
                            </span>
                        </p>
                    @elseif ($pemesanan->status === 'paid')
                        <h5 class="text-success"><i class="fas fa-check-circle mr-1"></i> SUDAH DIBAYAR</h5>
                        <p class="small">Waktu: {{ \Carbon\Carbon::parse($pemesanan->waktu_pembayaran)->format('H:i, d M Y') }}</p>
                    @elseif ($pemesanan->status === 'expired')
                        <h5 class="text-warning"><i class="fas fa-times-circle mr-1"></i> KADALUWARSA</h5>
                        <p class="small text-muted">Batas waktu telah berakhir.</p>
                    @else
                        <h5 class="text-muted">STATUS: {{ strtoupper($pemesanan->status) }}</h5>
                    @endif
                    
                    <h5 class="mt-4">Detail Film</h5>
                    <div class="list-group">
                        <div class="list-group-item list-group-item-dark">
                            <small class="text-red d-block">Judul Film</small>
                            <strong>{{ $pemesanan->jadwal->film->judul }}</strong>
                        </div>
                        <div class="list-group-item list-group-item-dark">
                            <small class="text-red d-block">Studio & Jadwal</small>
                            {{ $pemesanan->jadwal->studio->nama }} | {{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal)->format('d M Y') }} ({{ $pemesanan->jadwal->jam_mulai }})
                        </div>
                        
                        @php
                            $daftar_kursi = $pemesanan->detailPemesanan->pluck('nomor_kursi')->implode(', ');
                        @endphp
                        <div class="list-group-item list-group-item-dark">
                            <small class="text-red d-block">Nomor Kursi ({{ $pemesanan->jumlah_tiket }} Tiket)</small>
                            <strong>{{ $daftar_kursi }}</strong>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 pl-md-4">
                    <div class="bill-box">
                        <h5>Total Tagihan</h5>
                        <h3>Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</h3>
                    </div>
                    
                    <h5 class="mt-4">Metode Pembayaran</h5>
                    <div class="p-3 mb-3" style="background: #1a1a1a; border-radius: 8px; border-left: 3px solid #ff3b3b;">
                        <p class="mb-1 small text-muted">Transfer Bank (Manual/Gateway):</p>
                        <p class="mb-0 font-weight-bold">Bank XYZ: 123-456-7890</p>
                        <p class="small">a.n. Cinema Ticketing</p>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('user.history') }}" class="btn btn-outline-gray">
                            <i class="fas fa-arrow-left mr-1"></i> Riwayat
                        </a>
                        
                        @if ($pemesanan->status === 'menunggu_pembayaran')
                            <form id="payment-form" action="{{ route('user.pemesanan.generatePayment', $pemesanan->kode_pemesanan) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-red" id="pay-button">
                                    <i class="fas fa-credit-card mr-2"></i> BAYAR SEKARANG
                                </button> 
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if ($pemesanan->status === 'menunggu_pembayaran')
            const countdownElement = document.getElementById('countdown-timer');
            const payButton = document.getElementById('pay-button');
            const expiresAt = countdownElement.getAttribute('data-expires') * 1000;

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = expiresAt - now;

                if (distance < 0) {
                    countdownElement.innerHTML = "EXPIRED";
                    if(payButton) {
                        payButton.setAttribute('disabled', 'disabled');
                        payButton.innerHTML = "<i class='fas fa-lock mr-2'></i> WAKTU HABIS";
                    }
                    return; 
                }

                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                countdownElement.innerHTML = minutes + "m " + seconds + "s ";
                setTimeout(updateCountdown, 1000);
            }

            updateCountdown();
        @endif
    });
</script>
@endpush