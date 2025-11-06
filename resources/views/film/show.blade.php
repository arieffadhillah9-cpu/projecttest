@extends('layout.app')

@section('content')
    <div class="content-header">
        <h1>Detail Film: {{ $film->judul }}</h1>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    
                    {{-- Tombol Kembali --}}
                    <a href="{{ route('film.index') }}" class="btn btn-default mb-3">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                    </a>

                    <table class="table table-bordered">
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
                                <td>{{ $film->is_tayang ? 'Sedang Tayang' : 'Tidak Tayang' }}</td>
                            </tr>
                            <tr>
                                <th>Sinopsis</th>
                                <td>{{ $film->deskripsi }}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <div class="card-footer">
                    {{-- Tombol untuk Edit --}}
                    <a href="{{ route('film.edit', $film->id) }}" class="btn btn-warning">Edit Film</a>
                    
                    {{-- Form untuk Hapus --}}
                    <form action="{{ route('film.destroy', $film->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus film ini?')">Hapus Film</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection