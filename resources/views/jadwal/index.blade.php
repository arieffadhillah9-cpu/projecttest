@extends('jadwal.jadwalapp')


@section('content')
    <div class="content-header">
        <h1 class="display-4 font-weight-bold">Daftar Jadwal Tayang Bioskop</h1>
        <a href="{{ route('jadwal.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus"></i> Tambah Jadwal Baru
        </a>
    </div>

    <section class="content">
        <div class="container-fluid">
            {{-- Pesan Sukses/Error --}}
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Film</th>
                                <th>Studio</th>
                                <th>Tanggal</th>
                                <th>Jam Mulai</th>
                                <th>Harga Tiket</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jadwalTayangs as $jadwal)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $jadwal->film->judul }}</strong></td>
                                    <td>{{ $jadwal->studio->nama }}</td>
                                    {{-- Format Tanggal dan Jam --}}
                                    <td>{{ $jadwal->tanggal->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} WIB</td>
                                    <td>Rp {{ number_format($jadwal->harga, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('jadwal.edit', $jadwal->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                                data-id="{{ $jadwal->id }}" 
                                                data-toggle="modal" 
                                                data-target="#deleteModal">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada jadwal tayang yang ditambahkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="card-footer clearfix">
                    {{ $jadwalTayangs->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </section>

    