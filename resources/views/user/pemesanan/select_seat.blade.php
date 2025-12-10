@extends('layout.dashboard') {{-- Menggunakan layout dashboard user --}}

@section('title', 'Pilih Kursi')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        {{-- MODIFIKASI 1: Tambahkan text-dark di Judul Film --}}
        <h6 class="m-0 font-weight-bold text-dark">Pilih Kursi untuk: {{ $jadwal->film->judul }} ({{ $jadwal->jam_mulai }})</h6>
        
        {{-- MODIFIKASI 2: Ubah text-muted menjadi text-dark pada Studio --}}
        <p class="text-dark mb-0">Studio: {{ $jadwal->studio->nama }}</p>
    </div>
    @if ($errors->any())
    <div class="alert alert-danger">
        <h6 class="font-weight-bold">Opsi Pemesanan Gagal:</h6>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('user.pemesanan.process') }}" method="POST">
    <div class="card-body">
        
        {{-- Hapus duplikasi tag <form> di sini --}}
        {{-- <form action="{{ route('user.pemesanan.process') }}" method="POST"> --}}
            @csrf
            {{-- Data tersembunyi yang diperlukan untuk proses transaksi --}}
            <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
            
            <div class="text-center mb-4">
                <div class="bg-dark text-white p-2 rounded d-inline-block">LAYAR BIOSKOP</div>
            </div>
            
            <div class="seat-map d-flex justify-content-center flex-column align-items-center">
                {{-- Contoh sederhana representasi kursi. Implementasi nyata butuh CSS/JS --}}

@php
    $total_kursi = $jadwal->studio->kapasitas;
    
    // 1. Definisikan $baris lebih dulu
    $baris = ['A', 'B', 'C', 'D', 'E']; 
    
    // 2. Hitung $kursi_per_baris setelah $baris didefinisikan
    // (Asumsi: Kapasitas Studio dapat dibagi rata dengan jumlah baris)
    $kursi_per_baris = $total_kursi / count($baris); 
    
    // 3. Gunakan $kursi_terisi sebagai array (dari Controller)
    // Jika Anda TIDAK menghapus ->toArray() di Controller, gunakan ini:
    $kursi_terisi_list = $kursi_terisi; 
    
    // Jika Anda menghapus ->toArray() di Controller, gunakan ini:
    // $kursi_terisi_list = $kursi_terisi->pluck('kode_kursi')->toArray();
@endphp

{{-- ------------------------------------------------------------------ --}}
{{-- PASTIKAN STRUKTUR LOOP BERSIH dari duplikasi seperti di error sebelumnya --}}
{{-- ------------------------------------------------------------------ --}}

@foreach ($baris as $kode_baris)
    <div class="row-kursi mb-2 d-flex justify-content-center">
        
        {{-- MODIFIKASI 3: Tambahkan text-dark pada kode baris kursi --}}
        <span class="mr-3 font-weight-bold text-dark">{{ $kode_baris }}</span>
        
        @for ($i = 1; $i <= $kursi_per_baris; $i++)
            
            @php
                $kode_kursi = $kode_baris . $i; 
                $is_booked = in_array($kode_kursi, $kursi_terisi_list); 
            @endphp
            
            <label class="seat-label mx-1">
                <input 
                    type="checkbox" 
                    name="kursi_dipilih[]" 
                    value="{{ $kode_kursi }}" 
                    {{ $is_booked ? 'disabled' : '' }}
                >
                <span class="badge 
                    {{ $is_booked ? 'badge-danger' : 'badge-success seat-available' }}"
                    title="{{ $kode_kursi }}"
                >
                    {{ $i }}
                </span>
            </label>
            
        @endfor 
        
        {{-- MODIFIKASI 4: Tambahkan text-dark pada kode baris kursi --}}
        <span class="ml-3 font-weight-bold text-dark">{{ $kode_baris }}</span>
    </div>
@endforeach
                
                <div class="mt-4">
                    <span class="badge badge-success">Tersedia</span>
                    <span class="badge badge-danger">Terisi</span>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary btn-lg">Konfirmasi Pesanan & Bayar</button>
            </div>
        {{-- Hapus duplikasi tag </form> di sini, hanya satu tag penutup yang diperlukan --}}
    </div>
</form> {{-- Tag penutup form yang benar --}}
</div>
@endsection

@push('scripts')
{{-- Area ini dapat digunakan untuk kode JS kustom jika diperlukan --}}
@endpush