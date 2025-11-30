@extends('admin.layout.adminapp')

@section('content')

{{-- Wrapper Konten Utama (Form) --}}
{{-- Menggunakan content-wrapper bg-black agar serasi dengan layout Studio --}}
<div class="content-wrapper bg-black text-white py-5" style="min-height: 80vh;">
    <div class="container-fluid">
        
        {{-- Header Konten --}}
        <div class="container d-flex justify-content-start align-items-center mb-4 px-0" style="max-width: 700px;">
            <a href="{{ route('admin.film.index') }}" class="btn btn-secondary mr-3" style="background-color: #343a40; border-color: #343a40;">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <h2 class="font-weight-bold">Tambah Film Baru</h2>
        </div>

        {{-- Card Form --}}
        <div class="card bg-black text-white border-secondary shadow-lg mx-auto" style="max-width: 700px;"> 
            <div class="card-header bg-dark text-white border-bottom border-secondary">
                <h4 class="mb-0">Form Film Baru</h4>
            </div>
            <div class="card-body">

                {{-- Pesan Error Validasi (Seperti di kode Studio) --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.film.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    {{-- 1. JUDUL FILM --}}
                    <div class="form-group">
                        <label for="judul">Judul Film <span class="text-danger">*</span></label>
                        <input type="text" 
                            name="judul" 
                            class="form-control bg-dark text-white @error('judul') is-invalid @enderror" 
                            id="judul" 
                            value="{{ old('judul') }}"
                            placeholder="Masukkan judul film" required>
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 2. DESKRIPSI / SINOPSIS --}}
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi / Sinopsis <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" 
                            class="form-control bg-dark text-white @error('deskripsi') is-invalid @enderror" 
                            id="deskripsi" 
                            rows="3" 
                            placeholder="Masukkan deskripsi film" required>{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 3. DURASI & TANGGAL RILIS (Menggunakan row) --}}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="durasi_menit">Durasi (Menit) <span class="text-danger">*</span></label>
                            <input type="number" 
                                name="durasi_menit" 
                                class="form-control bg-dark text-white @error('durasi_menit') is-invalid @enderror" 
                                id="durasi_menit" 
                                value="{{ old('durasi_menit') }}"
                                placeholder="Durasi (Menit, min. 1)" required min="1">
                            @error('durasi_menit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="tanggal_rilis">Tanggal Rilis <span class="text-danger">*</span></label>
                            <input type="date" 
                                name="tanggal_rilis" 
                                class="form-control bg-dark text-white @error('tanggal_rilis') is-invalid @enderror" 
                                id="tanggal_rilis" 
                                value="{{ old('tanggal_rilis') }}" required>
                            @error('tanggal_rilis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- 4. SUTRADARA & GENRE (Menggunakan row) --}}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="sutradara">Sutradara</label>
                            <input type="text" 
                                name="sutradara" 
                                class="form-control bg-dark text-white @error('sutradara') is-invalid @enderror" 
                                id="sutradara" 
                                placeholder="Nama sutradara" 
                                value="{{ old('sutradara') }}">
                            @error('sutradara')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="genre">Genre <span class="text-danger">*</span></label>
                            <input type="text" 
                                name="genre" 
                                class="form-control bg-dark text-white @error('genre') is-invalid @enderror" 
                                id="genre" 
                                placeholder="Genre film (cth: Action, Drama)" 
                                value="{{ old('genre') }}" required>
                            @error('genre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- 5. POSTER FILM (File Upload) --}}
                    <div class="form-group">
                        <label for="poster_path">Poster Film</label>
                        {{-- Menggunakan input type file biasa, disesuaikan styling-nya --}}
                        <input type="file" 
                            name="poster_path" 
                            class="form-control-file bg-dark text-white @error('poster_path') is-invalid @enderror" 
                            id="poster_path"> 
                        <small class="form-text text-secondary">Maksimal 2MB (JPG, PNG)</small>
                        @error('poster_path')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 6. IS_TAYANG Checkbox --}}
                    <div class="form-group clearfix mt-4">
                        {{-- Hidden field untuk memastikan nilai 0 dikirim jika checkbox tidak dicentang --}}
                        <input type="hidden" name="is_tayang" value="0"> 
                        
                        <div class="form-check">
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
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Tombol Submit --}}
                    <button type="submit" class="btn btn-danger btn-block mt-4">
                        <i class="fas fa-save"></i> Simpan Film
                    </button>
                </form>

            </div>
        </div>
        
    </div>
</div>
@endsection