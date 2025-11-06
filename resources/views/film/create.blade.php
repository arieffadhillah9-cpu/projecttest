@extends('layout.app')

@section('content')
    <div class="content-header">
        <h1>Tambah Film Baru</h1>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Film Baru</h3>
                </div>
                <form action="{{ route('film.store') }}" method="POST">
                    @csrf {{-- Wajib untuk keamanan --}}
                    
                    <div class="card-body">
                        
                        {{-- 1. JUDUL FILM --}}
                        <div class="form-group">
                            <label for="judul">Judul Film</label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" id="judul" placeholder="Masukkan judul film" value="{{ old('judul') }}" required>
                            @error('judul')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- 2. DESKRIPSI / SINOPSIS --}}
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi / Sinopsis</label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" rows="3" placeholder="Masukkan deskripsi film" required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- 3. DURASI & TANGGAL RILIS (Dibuat berdampingan jika Anda menggunakan Grid/Bootstrap) --}}
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="durasi_menit">Durasi (Menit)</label>
                                <input type="number" name="durasi_menit" class="form-control @error('durasi_menit') is-invalid @enderror" id="durasi_menit" value="{{ old('durasi_menit') }}" required min="1">
                                @error('durasi_menit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="tanggal_rilis">Tanggal Rilis</label>
                                <input type="date" name="tanggal_rilis" class="form-control @error('tanggal_rilis') is-invalid @enderror" id="tanggal_rilis" value="{{ old('tanggal_rilis') }}" required>
                                @error('tanggal_rilis')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- 4. SUTRADARA & GENRE --}}
                         <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="sutradara">Sutradara</label>
                                <input type="text" name="sutradara" class="form-control @error('sutradara') is-invalid @enderror" id="sutradara" placeholder="Nama sutradara" value="{{ old('sutradara') }}">
                                @error('sutradara')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="genre">Genre</label>
                                <input type="text" name="genre" class="form-control @error('genre') is-invalid @enderror" id="genre" placeholder="Genre film (cth: Action, Drama)" value="{{ old('genre') }}" required>
                                @error('genre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        {{-- Field POSTER_PATH dan IS_TAYANG bisa ditambahkan sesuai kebutuhan --}}

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan Film</button>
                        {{-- Pastikan ini mengarah ke route daftar film --}}
                        <a href="{{ route('film.index') }}" class="btn btn-default float-right">Batal</a>
                    </div>
                </form>
            </div>
            </div>
    </section>
@endsection