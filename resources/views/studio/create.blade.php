@extends('studio.studioapp')

@section('content')

{{-- Header Konsisten --}}
<div class="jumbotron jumbotron-fluid text-white" style="background-color: #1a1a1a; padding: 50px 0; margin-bottom: 0;">
    <div class="container d-flex justify-content-start align-items-center">
        <a href="{{ route('studio.index') }}" class="btn btn-secondary btn-lg mr-3">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
        <h1 class="display-4 font-weight-bold">Tambah Studio Baru</h1>
    </div>
</div>

{{-- Wrapper Konten Utama (Form) --}}
<div class="py-5" style="background-color: #2c2c2c; min-height: 80vh;">
    <div class="container">
        
        <div class="card bg-secondary text-white shadow-lg mx-auto" style="max-width: 700px;">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Form Studio</h4>
            </div>
            <div class="card-body">

                {{-- Pesan Error Validasi --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('studio.store') }}" method="POST">
                    @csrf
                    
                    {{-- Nama Studio --}}
                    <div class="form-group">
                        <label for="nama">Nama Studio <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="nama" 
                               class="form-control bg-dark text-white @error('nama') is-invalid @enderror" 
                               id="nama" 
                               value="{{ old('nama') }}"
                               placeholder="Masukkan nama studio (misal: Studio 1 atau IMAX)">
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kapasitas Kursi --}}
                    <div class="form-group">
                        <label for="kapasitas">Kapasitas Kursi <span class="text-danger">*</span></label>
                        <input type="number" 
                               name="kapasitas" 
                               class="form-control bg-dark text-white @error('kapasitas') is-invalid @enderror" 
                               id="kapasitas" 
                               value="{{ old('kapasitas') }}"
                               placeholder="Masukkan jumlah kursi (min. 1)"
                               min="1">
                        @error('kapasitas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Tipe Layar (Dropdown) --}}
                    <div class="form-group">
                        <label for="tipe_layar">Tipe Layar</label>
                        <select name="tipe_layar" 
                                class="form-control bg-dark text-white @error('tipe_layar') is-invalid @enderror" 
                                id="tipe_layar">
                            <option value="" selected disabled>Pilih Tipe Layar</option>
                            <option value="2D Standard" {{ old('tipe_layar') == '2D Standard' ? 'selected' : '' }}>2D Standard</option>
                            <option value="IMAX" {{ old('tipe_layar') == 'IMAX' ? 'selected' : '' }}>IMAX</option>
                            <option value="Dolby Atmos" {{ old('tipe_layar') == 'Dolby Atmos' ? 'selected' : '' }}>Dolby Atmos</option>
                            <option value="Premiere" {{ old('tipe_layar') == 'Premiere' ? 'selected' : '' }}>Premiere</option>
                        </select>
                        @error('tipe_layar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block mt-4"><i class="fas fa-plus"></i> Tambah Studio</button>
                </form>

            </div>
        </div>
        
    </div>
</div>
@endsection