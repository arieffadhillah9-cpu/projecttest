@extends('layout.app') 

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                {{-- Mengubah text-dark menjadi text-white untuk tema gelap --}}
                <h1 class="m-0 text-white">Riwayat Pemesanan Saya</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.user') }}">Home</a></li>
                    <li class="breadcrumb-item active text-white">Riwayat Pemesanan</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        @if($pemesanans->isEmpty())
            {{-- Mengubah alert agar lebih cocok dengan tema gelap --}}
            <div class="alert alert-warning alert-dismissible bg-dark-warning border border-warning text-warning">
                <h5><i class="icon fas fa-exclamation-triangle"></i> Info!</h5>
                Anda belum memiliki riwayat pemesanan tiket saat ini.
            </div>
        @else
            {{-- Mengubah card menjadi card-dark agar seragam dengan tampilan studio --}}
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-history mr-1"></i> Daftar Transaksi</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        {{-- Menambahkan kelas table-dark untuk tabel gelap --}}
                        <table class="table table-striped table-hover table-dark">
                            <thead>
                                <tr>
                                    <th>Kode Transaksi</th>
                                    <th>Film</th>
                                    <th>Jadwal & Studio</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pemesanans as $pemesanan)
                                    <tr>
                                        <td>{{ $pemesanan->kode_pemesanan }}</td>
                                        <td>{{ $pemesanan->jadwal->film->judul ?? 'Film Tidak Ditemukan' }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($pemesanan->jadwal->waktu_tayang)->format('d M Y, H:i') }} <br>
                                            <small class="text-info">Studio {{ $pemesanan->jadwal->studio->nama ?? 'N/A' }}</small>
                                        </td>
                                        <td class="text-success font-weight-bold">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td>
                                        <td>
                                            @php
                                                // Warna badge sudah benar dan akan tetap kontras di latar belakang gelap
                                                $class = [
                                                    'paid' => 'badge-success',
                                                    'pending' => 'badge-warning',
                                                    'expired' => 'badge-danger',
                                                    'canceled' => 'badge-secondary',
                                                ][$pemesanan->status] ?? 'badge-info';
                                            @endphp
                                            <span class="badge {{ $class }}">{{ ucfirst($pemesanan->status) }}</span>
                                        </td>
                                        <td>
                                            {{-- Menggunakan btn-warning (kuning) atau btn-info (biru) seperti pada gambar 3 --}}
                                            <a href="{{ route('user.pemesanan.show', $pemesanan->kode_pemesanan) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="float-right">
                        {{-- Menggunakan links() jika $pemesanans adalah Paginator instance dan Anda telah menyiapkan view pagination --}}
                        @if ($pemesanans->hasPages())
                            {{-- Saya hapus 'vendor.pagination.bootstrap-4' karena mungkin itu yang menyebabkan error. Gunakan links() default: --}}
                            {{ $pemesanans->links() }}
                        @endif
                    </div>
                </div>
                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
        @endif
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection