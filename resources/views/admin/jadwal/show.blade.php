
@extends('admin.layout.adminapp')

@section('content')

{{-- Bagian Jumbotron Hitam (Opsional, untuk konsistensi header) --}}
<div class="jumbotron jumbotron-fluid text-white" style="background-color: #1a1a1a; padding: 50px 0; margin-bottom: 0;">
    <div class="container text-center">
        <h1 class="display-4 font-weight-bold">Detail Film: {{ $film->judul }}</h1>
    </div>
</div>

{{-- Wrapper Konten Utama (Warna Hitam) --}}
<div class="bg-dark py-5 text-white">
    <div class="container">
        
        <div class="card bg-secondary text-white shadow-lg"> {{-- Menggunakan card gelap untuk kontras --}}
            
            <div class="card-header border-0">
                {{-- Tombol Kembali --}}
                <a href="{{ route('film.index') }}" class="btn btn-info">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                </a>
            </div>

            <div class="card-body">
                
                {{-- Detail Film dalam Tabel --}}
                <table class="table table-bordered table-dark table-striped"> {{-- Tambahkan table-dark --}}
                    <tbody>
                        <tr>
                            <th style="width: 200px">Judul Film</th>
                            <td>{{ $film->judul }}</td>
                        </tr>
                        <tr>
                            <th>Durasi</th>
                            <td>{{ $film->durasi_menit }} Menit</td>
                        </tr>
                        <tr>
                            <th>Sutradara</th>
                            <td>{{ $film->sutradara ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Genre</th>
                            <td>{{ $film->genre }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Rilis</th>
                            <td>{{ \Carbon\Carbon::parse($film->tanggal_rilis)->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Status Tayang</th>
                            <td>{{ $film->is_tayang ? 'Sedang Tayang' : 'Belum Tayang' }}</td>
                        </tr>
                        <tr>
                            <th>Sinopsis</th>
                            <td>{{ $film->deskripsi }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer d-flex justify-content-end">
                {{-- Tombol untuk Edit --}}
                <a href="{{ route('film.edit', $film->id) }}" class="btn btn-warning mr-2">Edit Film</a>
                
                {{-- Form untuk Hapus --}}
                <form action="{{ route('film.destroy', $film->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus film ini?')">Hapus Film</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection