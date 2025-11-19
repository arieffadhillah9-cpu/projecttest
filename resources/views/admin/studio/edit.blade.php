@extends('admin.layout.adminapp')

@section('content')

{{-- Wrapper Konten Utama (Form) --}}
<div class="content-wrapper bg-black text-white py-5" style="min-height: 80vh;">
    <div class="container-fluid">
        
        {{-- Header Konten --}}
        <div class="container d-flex justify-content-start align-items-center mb-4 px-0">
            <a href="{{ route('admin.studio.index') }}" class="btn btn-secondary mr-3">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
            {{-- JUDUL: Menampilkan nama studio yang diedit --}}
            <h2 class="font-weight-bold">Edit Studio: {{ $studio->nama }}</h2>
        </div>

        {{-- Card Form - Menggunakan bg-black sesuai permintaan --}}
        <div class="card bg-black text-white border-secondary shadow-lg mx-auto" style="max-width: 700px;">
            <div class="card-header bg-dark text-white border-bottom border-secondary">
                <h4 class="mb-0">Form Perubahan Studio</h4>
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

                {{-- Form untuk Update --}}
                <form action="{{ route('admin.studio.update', $studio->id) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- PENTING: Method untuk UPDATE --}}
                    
                    {{-- Nama Studio --}}
                    <div class="form-group">
                        <label for="nama">Nama Studio <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="nama" 
                               class="form-control bg-dark text-white @error('nama') is-invalid @enderror" 
                               id="nama" 
                               value="{{ old('nama', $studio->nama) }}" {{-- Memuat data lama --}}
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
                               value="{{ old('kapasitas', $studio->kapasitas) }}" {{-- Memuat data lama --}}
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
                            <option value="" disabled>Pilih Tipe Layar</option>
                            @php
                                $tipeLayarOptions = ['2D Standard', 'IMAX', 'Dolby Atmos', 'Premiere'];
                            @endphp
                            @foreach ($tipeLayarOptions as $option)
                                <option value="{{ $option }}" 
                                    {{ old('tipe_layar', $studio->tipe_layar) == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipe_layar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Tombol Aksi --}}
                    <div class="d-flex justify-content-between pt-3">
                        {{-- Tombol Perbarui (Update) --}}
                        <button type="submit" class="btn btn-warning btn-lg"><i class="fas fa-sync"></i> Perbarui Studio</button>
                        {{-- Tombol Batal --}}
                        <a href="{{ route('admin.studio.index') }}" class="btn btn-danger btn-lg"><i class="fas fa-times"></i> Batal</a>
                    </div>
                </form>

            </div>
        </div>
        
    </div>
</div>
@endsection