@extends('layout.app')

@section('content')

<style>
    /* Global Background Override */
    body, .wrapper, .content-wrapper {
        background-color: #000000 !important;
        color: #e0e0e0 !important;
    }

    /* Page Header */
    .content-header h1 {
        font-weight: 800;
        text-transform: uppercase;
        color: #ffffff !important;
        font-size: 1.5rem;
    }
    
    .breadcrumb-item a { color: #ff3b3b !important; }

    /* Card Ramping (Dikecilkan) */
    .card-modern {
        background: #111111 !important;
        border: 1px solid #333 !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
        overflow: hidden;
        /* Mengontrol lebar maksimal card */
        max-width: 950px; 
        margin: 0 auto;
    }

    .card-modern .card-header {
        background: linear-gradient(135deg, #b30000, #660000) !important;
        border-bottom: 2px solid #ff0000 !important;
        padding: 1rem 1.5rem;
    }

    /* Table Styling */
    .table-dark-custom {
        background-color: transparent !important;
        margin-bottom: 0;
        font-size: 0.9rem; /* Ukuran font tabel diperkecil sedikit agar proporsional */
    }

    .table-dark-custom thead th {
        background-color: #1a1a1a !important;
        border-bottom: 1px solid #333 !important;
        color: #ff3b3b !important;
        text-transform: uppercase;
        font-size: 0.75rem;
        padding: 12px;
    }

    .table-dark-custom td {
        padding: 12px !important;
        vertical-align: middle !important;
    }

    .btn-detail {
        background: linear-gradient(135deg, #ff3b3b, #b30000);
        border: none;
        color: white;
        border-radius: 6px;
        padding: 5px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-detail:hover {
        box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
        color: white;
    }
</style>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-3 justify-content-center">
            <div class="col-md-10" style="max-width: 950px;"> {{-- Menyelaraskan header dengan lebar card --}}
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="m-0"><i class="fas fa-history mr-2 text-red"></i> Riwayat Pesanan</h1>
                    <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.user') }}">Home</a></li>
                        <li class="breadcrumb-item active text-gray">Riwayat</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10"> {{-- Grid pembungkus card --}}
                
                @if($pemesanans->isEmpty())
                    <div class="alert bg-dark border border-secondary text-warning p-4 mx-auto" style="max-width: 950px;">
                        <h5><i class="icon fas fa-info-circle"></i> Kosong</h5>
                        Belum ada riwayat transaksi.
                    </div>
                @else
                    <div class="card card-modern">
                        <div class="card-header">
                            <h3 class="card-title" style="font-size: 1rem;"><i class="fas fa-list-ul mr-2"></i> Transaksi Terkini</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-dark-custom">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Film & Studio</th>
                                            <th>Jadwal</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pemesanans as $pemesanan)
                                            <tr>
                                                <td class="text-white small">{{ $pemesanan->kode_pemesanan }}</td>
                                                <td>
                                                    <b class="text-white d-block">{{ $pemesanan->jadwal->film->judul ?? 'N/A' }}</b>
                                                    <small class="text-info">Studio {{ $pemesanan->jadwal->studio->nama ?? 'N/A' }}</small>
                                                </td>
                                                <td class="small">
                                                    {{ \Carbon\Carbon::parse($pemesanan->jadwal->waktu_tayang)->format('d/m/y H:i') }}
                                                </td>
                                                <td class="text-danger font-weight-bold">
                                                    Rp{{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    @php
                                                        $statusClass = [
                                                            'paid' => 'bg-success',
                                                            'pending' => 'bg-warning text-dark',
                                                            'expired' => 'bg-danger',
                                                            'canceled' => 'bg-secondary',
                                                        ][$pemesanan->status] ?? 'bg-info';
                                                    @endphp
                                                    <span class="badge {{ $statusClass }}" style="font-size: 0.65rem;">
                                                        {{ strtoupper($pemesanan->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('user.pemesanan.show', $pemesanan->kode_pemesanan) }}" class="btn btn-detail">
                                                        DETAIL
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top border-dark">
                            <div class="float-right">
                                {{ $pemesanans->links() }}
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection