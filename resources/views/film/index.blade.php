@extends('film.adminapp')

@section('content')

{{-- Bagian Jumbotron Hitam (Header Konsisten) --}}
<div class="jumbotron jumbotron-fluid text-white" style="background-color: #1a1a1a; padding: 50px 0; margin-bottom: 0;">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="display-4 font-weight-bold">Daftar Film Bioskop</h1>
        {{-- Tombol untuk membuat film baru --}}
        <a href="{{ route('film.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus"></i> Tambah Film Baru
        </a>
    </div>
</div>

{{-- Wrapper Konten Utama (Warna Hitam) --}}
<div class="bg-dark py-5 text-white" style="min-height: 80vh;">
    <div class="container">
        
        {{-- Pesan Sukses/Error --}}
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        {{-- Grid Film Cards --}}
        <div class="row">
            @forelse ($films as $film)
                {{-- Layout Responsif: 4 kolom di layar besar (lg), 3 kolom di md, 2 kolom di sm --}}
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card bg-secondary text-white shadow-lg h-100" style="border: none;">
                        
                        {{-- DIV WRAPPER: KUNCI UNTUK RASIO POSTER 2:3 (Ganti height: 350px) --}}
                        <div style="position: relative; width: 100%; height: 0; padding-bottom: 150%;"> 
                            <a href="{{ route('film.show', $film->id) }}" style="text-decoration: none;">
                                
                                <img src="{{ $film->poster_path ? asset('storage/' . str_replace('storage/', '', $film->poster_path)) : asset('images/placeholder.jpg') }}" 
                                     alt="Poster {{ $film->judul }}" 
                                     {{-- Styling gambar untuk mengisi wrapper --}}
                                     style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 0.25rem 0.25rem 0 0;">
                            </a>
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title font-weight-bold">{{ $film->judul }}</h5>
                            <p class="card-text">
                                <span class="badge badge-info">{{ $film->genre }}</span>
                                <span class="badge badge-warning">{{ $film->durasi_menit }} Min</span>
                            </p>
                            
                            {{-- Deskripsi dibatasi dan mb-auto memastikan tombol selalu di bawah --}}
                            <p class="card-text text-sm mb-auto text-muted">{{ Str::limit($film->deskripsi, 50) }}</p>
                            
                            <div class="mt-3">
                                <a href="{{ route('film.show', $film->id) }}" class="btn btn-info btn-block btn-sm">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center mt-4">
                        Tidak ada film yang tersedia saat ini. Silakan tambahkan film baru!
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection