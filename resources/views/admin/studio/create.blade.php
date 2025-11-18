@extends('admin.studio.studioapp') {{-- Menggunakan layout utama yang sudah diperbaiki --}}

@section('content')

{{-- Wrapper Konten Utama (Form) --}}
{{-- Menggunakan content-wrapper bg-black agar serasi dengan layout --}}
<div class="content-wrapper bg-black text-white py-5" style="min-height: 80vh;">
    <div class="container-fluid">
        
        {{-- Header Konten --}}
        <div class="container d-flex justify-content-start align-items-center mb-4 px-0">
            <a href="{{ route('studio.index') }}" class="btn btn-secondary mr-3">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
            {{-- Mengganti display-4 menjadi h2 --}}
            <h2 class="font-weight-bold">Tambah Studio Baru</h2>
        </div>

        {{-- Card Form --}}
        {{-- PERUBAHAN: Mengganti bg-secondary pada card menjadi bg-black --}}
        <div class="card bg-black text-white border-secondary shadow-lg mx-auto" style="max-width: 700px;"> 
            <div class="card-header bg-dark text-white border-bottom border-secondary">
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
                    
                    {{-- PERUBAHAN: Mengganti warna tombol Tambah menjadi btn-danger --}}
                    <button type="submit" class="btn btn-danger btn-block mt-4"><i class="fas fa-plus"></i> Tambah Studio</button>
                </form>

            </div>
        </div>
        
    </div>
</div>
@endsection