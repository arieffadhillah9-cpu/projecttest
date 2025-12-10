{{-- resources/views/user/pemesanan/show_pemesanan.blade.php --}}

@extends('layout.dashboard') 

@section('title', 'Detail Pembayaran')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Detail Transaksi #{{ $pemesanan->kode_pemesanan }}</h6>
    </div>
    <div class="card-body">
        
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <div class="row">
            <div class="col-md-6">
                
                {{-- ------------------------------------------------ --}}
                {{-- 1. DISPLAY BATAS WAKTU (HANYA JIKA STATUS MENUNGGU_PEMBAYARAN) --}}
                {{-- ------------------------------------------------ --}}
                @if ($pemesanan->status === 'menunggu_pembayaran')
                    <h5>Waktu Pembayaran</h5>
                    <p class="text-danger">
                        Batas Waktu: 
                        <span id="countdown-timer" data-expires="{{ $waktuKadaluwarsa->timestamp }}">
                            {{ $waktuKadaluwarsa->format('H:i:s, d M Y') }}
                        </span>
                    </p>
                @elseif ($pemesanan->status === 'paid')
                    <h5 class="text-success">Status Pembayaran: SUDAH DIBAYAR</h5>
                    <p>Waktu Pembayaran: {{ \Carbon\Carbon::parse($pemesanan->waktu_pembayaran)->format('H:i:s, d M Y') }}</p>
                @elseif ($pemesanan->status === 'expired')
                    <h5 class="text-warning">Status Transaksi: KADALUWARSA</h5>
                    <p>Pemesanan ini sudah melewati batas waktu pembayaran dan dibatalkan.</p>
                @else
                    <h5 class="text-muted">Status Transaksi: {{ strtoupper($pemesanan->status) }}</h5>
                @endif
                
                
                <h5 class="mt-4">Detail Film</h5>
                <ul class="list-group">
                    {{-- MODIFIKASI: Tambahkan class text-dark pada setiap list-group-item --}}
                    <li class="list-group-item text-dark">Film: <strong>{{ $pemesanan->jadwal->film->judul }}</strong></li>
                    <li class="list-group-item text-dark">Studio: {{ $pemesanan->jadwal->studio->nama }}</li>
                    <li class="list-group-item text-dark">Tayang: {{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal)->format('d M Y') }} - {{ $pemesanan->jadwal->jam_mulai }}</li>
                    
                    {{-- ------------------------------------------------ --}}
                    {{-- 2. PERUBAHAN KRUSIAL: Menampilkan Daftar Kursi Nyata --}}
                    {{-- ------------------------------------------------ --}}
                    @php
                        // Memastikan relasi detailPemesanan sudah dimuat (seperti di UserProfileController)
                        $daftar_kursi = $pemesanan->detailPemesanan->pluck('nomor_kursi')->implode(', ');
                    @endphp
                    <li class="list-group-item text-dark">
                        Kursi: 
                        <strong>{{ $daftar_kursi }}</strong> 
                        ({{ $pemesanan->jumlah_tiket }} Tiket)
                    </li>
                    {{-- ------------------------------------------------ --}}
                    
                </ul>
            </div>
            
            <div class="col-md-6">
                <div class="alert alert-primary text-center">
                    <h5>Total Tagihan</h5>
                    <h3>Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</h3>
                </div>
                
                <h5 class="mt-4">Instruksi Pembayaran</h5>
                {{-- Area ini untuk Opsi Pembayaran (Transfer, Gateway) --}}
                <p>Pembayaran dapat dilakukan melalui transfer ke Rekening Dummy kami:</p>
                <p>Bank XYZ: **123-456-7890 (a.n. Cinema Ticketing)**</p>
                
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('user.history') }}" class="btn btn-secondary mr-2">Cek Riwayat</a>
                    
                    {{-- ------------------------------------------------ --}}
                    {{-- 3. INTEGRASI TOMBOL KONFIRMASI PEMBAYARAN --}}
                    {{-- Tombol hanya muncul dan bisa diakses jika status 'menunggu_pembayaran' --}}
                    {{-- ------------------------------------------------ --}}
                   @if ($pemesanan->status === 'menunggu_pembayaran')
    {{-- PASTIKAN ROUTE BARU INI SUDAH DIDAFTARKAN DI routes/web.php --}}
    <form id="payment-form" action="{{ route('user.pemesanan.generatePayment', $pemesanan->kode_pemesanan) }}" method="POST">
        @csrf
        {{-- Ganti id pay-button jika Anda tidak menggunakan Midtrans Pop-up --}}
        <button type="submit" class="btn btn-success" id="pay-button">Bayar Sekarang</button> 
    </form>
@endif
                    {{-- ------------------------------------------------ --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Logika Countdown Timer
    document.addEventListener('DOMContentLoaded', function () {
        // Hanya jalankan countdown jika pemesanan masih menunggu pembayaran
        @if ($pemesanan->status === 'menunggu_pembayaran')
            const countdownElement = document.getElementById('countdown-timer');
            const payButton = document.getElementById('pay-button');
            const expiresAt = countdownElement.getAttribute('data-expires') * 1000; // waktu dalam ms

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = expiresAt - now;

                if (distance < 0) {
                    countdownElement.innerHTML = "Waktu Habis";
                    // Nonaktifkan tombol bayar jika waktu habis
                    if(payButton) {
                        payButton.setAttribute('disabled', 'disabled');
                        payButton.textContent = "Waktu Habis";
                    }
                    // Opsional: Redirect atau tampilkan notifikasi expired.
                    return; 
                }

                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                countdownElement.innerHTML = minutes + "m " + seconds + "s ";
                
                // Aktifkan tombol bayar saat countdown berjalan
                if(payButton && payButton.hasAttribute('disabled')) {
                    payButton.removeAttribute('disabled');
                }

                setTimeout(updateCountdown, 1000);
            }

            updateCountdown();
        @endif
    });
</script>
@endpush