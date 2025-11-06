@extends('layout.app')

@section('content')

<div class="jumbotron jumbotron-fluid text-white" style="background-color: #1a1a1a; padding: 100px 0; margin-bottom: 0;">
    <div class="container text-center">
        <h1 class="display-4 font-weight-bold">Daftar Film Yang Sedang Tayang</h1>
        <p class="lead">Temukan Jadwal dan Detail Film Terbaru Hari Ini</p>
    </div>
</div>
<div class="bg-dark py-3">
    <div class="container">
        <nav class="nav nav-pills nav-fill">
            <a class="nav-item nav-link active" href="#">HARI INI</a>
            <a class="nav-item nav-link text-white-50" href="#">BESOK</a>
            <a class="nav-item nav-link text-white-50" href="#">SEMUA FILM</a>
            <a href="{{ route('film.create') }}" class="btn btn-sm btn-success ml-auto">
                <i class="fas fa-plus"></i> Tambah Film
            </a>
        </nav>
    </div>
</div>
<div class="container py-5">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        @foreach ($films as $film)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm">
                    {{-- PENTING: Menggunakan asset() untuk gambar lokal --}}
                    <img src="{{ asset($film->poster_path) }}" class="card-img-top" alt="{{ $film->judul }}" style="height: 350px; object-fit: cover;"> 
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $film->judul }}</h5>
                        <p class="card-text small text-muted">Durasi: {{ $film->durasi_menit }} Menit</p>
                        <p class="card-text small text-muted">Rilis: {{ $film->tanggal_rilis }}</p>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <a href="{{ route('film.show', $film->id) }}" class="btn btn-sm btn-info">Detail</a>
                            
                            <form action="{{ route('film.destroy', $film->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus film {{ $film->judul }}?')">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if ($films->isEmpty())
        <div class="alert alert-warning text-center">
            Belum ada data film yang tersedia.
        </div>
    @endif
    
</div>
@endsection