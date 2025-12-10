{{-- resources/views/user/pemesanan/payment.blade.php --}}

@extends('layout.dashboard') 

@section('title', 'Pembayaran')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pembayaran Pesanan #{{ $pemesanan->id }}</h6>
        <p class="text-danger">Batas Waktu Pembayaran: <span id="countdown">10:00</span></p> 
    </div>
    <div class="card-body">
        
        <h5 class="mb-3">Detail Pesanan</h5>
        <ul class="list-group mb-4">
            <li class="list-group-item">Film: {{ $pemesanan->jadwal->film->judul }}</li>
            <li class="list-group-item">Studio & Jam: {{ $pemesanan->jadwal->studio->nama }} ({{ $pemesanan->jadwal->jam_mulai }})</li>
            <li class="list-group-item">Jumlah Kursi: {{ $pemesanan->total_kursi }}</li>
            {{-- Tambahkan list kursi yang dipilih di sini jika Anda menyimpannya di DB --}}
        </ul>

        <div class="alert alert-success text-center">
            <h3>Total Tagihan: Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</h3>
        </div>

        <h5 class="mt-4 mb-3">Pilih Metode Pembayaran</h5>
        {{-- Implementasi Opsi Pembayaran (Transfer Bank, Payment Gateway, dll) --}}
        <p>Logika integrasi pembayaran akan diletakkan di sini.</p>

        <div class="d-flex justify-content-end mt-4">
             <a href="{{ route('user.pemesanan.cancel', $pemesanan->id) }}" class="btn btn-secondary mr-2">Batalkan</a>
             <button type="button" class="btn btn-success">Lanjutkan Pembayaran</button> 
        </div>

    </div>
</div>
@endsection