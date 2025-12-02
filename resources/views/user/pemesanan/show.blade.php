@extends('layout.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                {{-- Mengubah text-dark menjadi text-white --}}
                <h1 class="m-0 text-white">Detail Transaksi: <small class="text-info">{{ $pemesanan->kode_pemesanan }}</small></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('user.history') }}">Riwayat</a></li>
                    <li class="breadcrumb-item active text-white">Detail</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Kolom Kiri: Detail Film & Kursi -->
            <div class="col-lg-8">
                {{-- Mengubah card-primary menjadi card-dark dengan outline info --}}
                <div class="card card-dark card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-ticket-alt mr-1"></i> Informasi Tayang & Tiket</h3>
                    </div>
                    <div class="card-body text-white"> {{-- Menambahkan text-white untuk seluruh body card --}}
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Judul Film</p>
                                <h4 class="font-weight-bold text-info">{{ $pemesanan->jadwal->film->judul ?? 'N/A' }}</h4>
                                <hr class="my-3 border-secondary">
                                <p class="text-muted mb-1">Jadwal & Waktu</p>
                                <h4 class="font-weight-bold">
                                    {{ \Carbon\Carbon::parse($pemesanan->jadwal->waktu_tayang)->format('l, d F Y') }}
                                </h4>
                                <h4 class="font-weight-bold text-warning">
                                    Pukul {{ \Carbon\Carbon::parse($pemesanan->jadwal->waktu_tayang)->format('H:i') }} WIB
                                </h4>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Lokasi Studio</p>
                                <h4 class="font-weight-bold text-warning">Studio {{ $pemesanan->jadwal->studio->nama ?? 'N/A' }}</h4>
                                <hr class="my-3 border-secondary">
                                <p class="text-muted mb-1">Kursi Anda (Total: {{ $pemesanan->jumlah_tiket }} tiket)</p>
                                <div class="row">
                                    {{-- Gaya Kursi Diseragamkan menjadi badge info di latar gelap --}}
                                    @forelse ($pemesanan->detailPemesanan as $detail)
                                        <div class="col-4 mb-2">
                                            <div class="bg-info text-white text-center py-2 rounded shadow-sm">
                                                <small>No. Kursi</small>
                                                <p class="h5 font-weight-bold mb-0">{{ $detail->nomor_kursi }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-danger col-12">Detail kursi tidak ditemukan.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Ringkasan Harga & Status -->
            <div class="col-lg-4">
                {{-- Mengubah card-warning menjadi card-dark dengan outline warning --}}
                <div class="card card-dark card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-file-invoice-dollar mr-1"></i> Ringkasan Transaksi</h3>
                    </div>
                    <div class="card-body text-white">
                        <ul class="list-group list-group-unbordered mb-3">
                            {{-- Menggunakan list-group-item-dark atau bg-transparent agar menyatu dengan card gelap --}}
                            <li class="list-group-item bg-transparent text-white border-secondary">
                                <b>Harga Per Tiket</b> <span class="float-right text-warning">Rp {{ number_format($pemesanan->total_harga / $pemesanan->jumlah_tiket, 0, ',', '.') }}</span>
                            </li>
                            <li class="list-group-item bg-transparent text-white border-secondary">
                                <b>Jumlah Tiket</b> <span class="float-right text-warning">{{ $pemesanan->jumlah_tiket }}</span>
                            </li>
                            <li class="list-group-item bg-secondary"> {{-- Menggunakan warna sekunder untuk total --}}
                                <b class="text-lg text-white">TOTAL HARGA</b> <span class="float-right text-lg text-white"><b>Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</b></span>
                            </li>
                        </ul>

                        <p class="text-center font-weight-bold mb-2">Status Pembayaran</p>
                        @php
                            $statusClass = [
                                'paid' => 'bg-success',
                                'pending' => 'bg-warning',
                                'expired' => 'bg-danger',
                                'canceled' => 'bg-secondary',
                            ][$pemesanan->status] ?? 'bg-info';
                        @endphp
                        {{-- Menggunakan kelas status sebagai background --}}
                        <div class="p-3 text-white text-center rounded {{ $statusClass }}">
                            <p class="h3 mb-0">{{ strtoupper($pemesanan->status) }}</p>
                        </div>
                        
                        @if ($pemesanan->status == 'pending')
                            <div class="mt-3 text-center">
                                <p class="text-warning font-weight-bold">Transaksi Belum Lunas!</p>
                                <small class="text-muted">Harap selesaikan pembayaran sebelum batas waktu berakhir.</small>
                                
                                {{-- Tombol aksi diseragamkan ke warna merah (btn-danger) seperti di tampilan studio --}}
                                <a href="#" class="btn btn-danger btn-block mt-3">
                                    <i class="fas fa-money-check-alt"></i> Lanjutkan Pembayaran
                                </a>
                            </div>
                        @elseif ($pemesanan->status == 'paid')
                            <div class="mt-3 text-center">
                                <p class="text-success font-weight-bold">Pembayaran berhasil!</p>
                                <a href="#" class="btn btn-warning btn-block mt-3" onclick="alert('Fungsi cetak tiket belum diimplementasikan.');">
                                    <i class="fas fa-print"></i> Cetak E-Tiket
                                </a>
                            </div>
                        @endif
                        
                        {{-- Mengubah tombol kembali agar lebih kontras --}}
                        <a href="{{ route('user.history') }}" class="btn btn-secondary btn-block mt-4">
                            <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection