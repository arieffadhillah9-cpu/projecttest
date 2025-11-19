@extends('admin.pemesanan.pemesananapp')

@section('title', 'Daftar Pemesanan')

@section('content')
{{-- Content Header (Page header) --}}
<div class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6">
<h1 class="m-0 text-white">
<i class="fas fa-ticket-alt mr-2"></i> Manajemen Pemesanan
</h1>
</div><!-- /.col -->
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
<li class="breadcrumb-item active">Pemesanan</li>
</ol>
</div><!-- /.col -->
</div><!-- /.row -->
</div><!-- /.container-fluid -->
</div>
{{-- /.content-header --}}

{{-- Main content --}}
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">

                {{-- Card untuk Daftar Pemesanan --}}
                <div class="card card-dark card-outline">
                    <div class="card-header border-0">
                        <h3 class="card-title">Daftar Semua Transaksi Pemesanan</h3>
                        <div class="card-tools">
                            {{-- Tombol untuk export/filter jika diperlukan --}}
                            <button type="button" class="btn btn-sm btn-outline-warning" data-card-widget="collapse">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <table class="table table-striped table-valign-middle table-hover">
                            <thead>
                                <tr>
                                    <th>ID Transaksi</th>
                                    <th>Pelanggan</th>
                                    <th>Total (Rp)</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data Dummy untuk Ilustrasi --}}
                                @php
                                    $pemesanans = [
                                        (object)['id' => 1, 'customer' => 'Adi Pradana', 'total_harga' => 75000, 'tanggal' => '2025-11-18 10:30:00', 'status' => 'Pending'],
                                        (object)['id' => 2, 'customer' => 'Bunga Citra', 'total_harga' => 120000, 'tanggal' => '2025-11-17 21:15:00', 'status' => 'Lunas'],
                                        (object)['id' => 3, 'customer' => 'Cahya Gumilang', 'total_harga' => 90000, 'tanggal' => '2025-11-16 12:00:00', 'status' => 'Dibatalkan'],
                                    ];
                                @endphp

                                @foreach ($pemesanans as $pemesanan)
                                <tr>
                                    <td class="text-bold">#{{ $pemesanan->id }}</td>
                                    <td>{{ $pemesanan->customer }}</td>
                                    <td>{{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pemesanan->tanggal)->format('d M y H:i') }}</td>
                                    <td>
                                        @if ($pemesanan->status == 'Lunas')
                                            <span class="badge badge-success"><i class="fas fa-check"></i> {{ $pemesanan->status }}</span>
                                        @elseif ($pemesanan->status == 'Pending')
                                            <span class="badge badge-warning"><i class="fas fa-clock"></i> {{ $pemesanan->status }}</span>
                                        @else
                                            <span class="badge badge-danger"><i class="fas fa-times"></i> {{ $pemesanan->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('pemesanan.show', $pemesanan->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('pemesanan.edit', $pemesanan->id) }}" class="btn btn-sm btn-primary" title="Edit/Ubah Status">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('pemesanan.destroy', $pemesanan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus transaksi #{{ $pemesanan->id }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus Permanen">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        {{-- Placeholder untuk Paginasi --}}
                        <ul class="pagination pagination-sm m-0 float-right">
                            <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                        </ul>
                        <span class="text-muted text-sm float-left pt-2">Menampilkan 1-10 dari 50 Pemesanan.</span>
                    </div>
                </div>
                {{-- /.card --}}
            </div>
        </div>
        {{-- /.row --}}
    </div>
    {{-- /.container-fluid --}}
</div>
{{-- /.content --}}


@endsection