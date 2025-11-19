@extends('admin.pemesanan.pemesananapp')

@section('content')
    <div class="content-header">
        <h1>Edit Film: {{ $film->judul }}</h1>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    
                    {{-- Form mengarah ke method UPDATE dan menggunakan method PUT --}}
                    <form action="{{ route('film.update', $film->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') 
                        
                        {{-- Field Judul --}}
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
                        <div class="form-group">
                            <label for="judul">Judul Film</label>
                            {{-- Menggunakan old() untuk error, dan $film->judul untuk nilai awal --}}
                            <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul', $film->judul) }}" required>
                            @error('judul')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Field Durasi --}}
                        <div class="form-group">
                            <label for="durasi_menit">Durasi (Menit)</label>
                            <input type="number" name="durasi_menit" id="durasi_menit" class="form-control" value="{{ old('durasi_menit', $film->durasi_menit) }}" required>
                            @error('durasi_menit')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- Field Sutradara --}}
                         <div class="form-group">
                            <label for="sutradara">Sutradara</label>
                            <input type="text" name="sutradara" id="sutradara" class="form-control" value="{{ old('sutradara', $film->sutradara) }}">
                            @error('sutradara')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Field Genre --}}
                         <div class="form-group">
                            <label for="genre">Genre</label>
                            <input type="text" name="genre" id="genre" class="form-control" value="{{ old('genre', $film->genre) }}" required>
                            @error('genre')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Field Tanggal Rilis --}}
                        <div class="form-group">
                            <label for="tanggal_rilis">Tanggal Rilis</label>
                            {{-- Format tanggal agar kompatibel dengan input type="date" --}}
                            <input type="date" name="tanggal_rilis" id="tanggal_rilis" class="form-control" value="{{ old('tanggal_rilis', date('Y-m-d', strtotime($film->tanggal_rilis))) }}" required>
                            @error('tanggal_rilis')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Field Deskripsi --}}
                        <div class="form-group">
                            <label for="deskripsi">Sinopsis / Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" required>{{ old('deskripsi', $film->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- 8. Checkbox IS_TAYANG (DENGAN PERBAIKAN) --}}
                        <div class="form-group clearfix">
                            {{-- PENTING: Hidden field untuk memastikan nilai 0 terkirim --}}
                            <input type="hidden" name="is_tayang" value="0">
                            
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="is_tayang" 
                                       name="is_tayang" 
                                       value="1" 
                                       {{-- Logika untuk menampilkan nilai lama (old) atau nilai film saat ini --}}
                                       @checked(old('is_tayang', $film->is_tayang)) 
                                >
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
                        {{-- END Checkbox IS_TAYANG --}}

                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('film.show', $film->id) }}" class="btn btn-secondary">Batal</a>
                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection