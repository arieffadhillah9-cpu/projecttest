@extends('admin.layout.adminapp')

@section('content')
    <div class="container py-5">
        <div class="content-header mb-4">
            <h1 class="text-white">Tambah Film Baru</h1>
        </div>

        <section class="content">
            <div class="container-fluid p-0">
                <div class="card card-dark"> 
                    <div class="card-header">
                        <h3 class="card-title text-white">Form Film Baru</h3>
                    </div>
                    <form action="{{ route('admin.film.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
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

                            {{-- 3. DURASI & TANGGAL RILIS --}}
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="durasi_menit">Durasi (Menit)</label>
                                    <input type="number" name="durasi_menit" class="form-control @error('durasi_menit') is-invalid @enderror" id="durasi_menit" placeholder="Durasi (Menit)" value="{{ old('durasi_menit') }}" required min="1">
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

                            {{-- 4. SUTRADARA & GENRE (Simplified) --}}
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
                          <div class="form-group">
                                <label for="poster_path">Poster Film</label>
                                {{-- Type sudah benar 'file' --}}
                                <input type="file" 
                                    name="poster_path" 
                                    class="form-control-file @error('poster_path') is-invalid @enderror" 
                                    id="poster_path">  {{-- Hapus atribut value="" --}}
                                    
                                <small class="form-text text-muted">Maksimal 2MB (JPG, PNG)</small>
                                @error('poster_path')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- 6. IS_TAYANG Checkbox (PERBAIKAN FUNGSI) --}}
                            <div class="form-group clearfix">
                                
                                {{-- Langkah 1 (PENTING): Hidden field ini memastikan nilai 0 dikirim jika checkbox tidak dicentang --}}
                                <input type="hidden" name="is_tayang" value="0"> 
                                
                                <div class="form-check">
                                    {{-- Langkah 2: Checkbox yang sebenarnya (mengirim nilai 1 jika dicentang) --}}
                                    <input type="checkbox" 
                                        class="form-check-input" 
                                        id="is_tayang" 
                                        name="is_tayang" 
                                        value="1" 
                                        {{ old('is_tayang') == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_tayang">
                                        Film Sedang Tayang (Centang jika ya)
                                    </label>
                                </div>
                                @error('is_tayang')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>
                        
                        <div class="card-footer">
                            <button type="submit" class="btn btn-danger">Simpan Film</button>
                            <a href="{{ route('admin.film.index') }}" class="btn btn-default float-right">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection