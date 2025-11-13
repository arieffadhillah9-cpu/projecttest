@extends('studio.studioapp')

@section('content')

{{-- Header Konsisten --}}
<div class="jumbotron jumbotron-fluid text-white" style="background-color: #1a1a1a; padding: 50px 0; margin-bottom: 0;">
    <div class="container d-flex justify-content-start align-items-center">
        <a href="{{ route('studio.index', $studio->id) }}" class="btn btn-secondary btn-lg mr-3">
            <i class="fas fa-arrow-left"></i> Kembali ke detail
        </a>
        <h1 class="display-4 font-weight-bold">Edit Studio: {{ $studio->nama }}</h1>
    </div>
</div>

{{-- Wrapper Konten Utama (Form) --}}
<div class="py-5" style="background-color: #2c2c2c; min-height: 80vh;">
    <div class="container">
        
        <div class="card bg-secondary text-white shadow-lg mx-auto" style="max-width: 700px;">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Form Perubahan Studio</h4>
            </div>
            <div class="card-body">

                <form action="{{ route('studio.update', $studio->id) }}" method="POST">
                    @csrf
                    @method('PUT') 
                    
                    {{-- Nama Studio --}}
                    <div class="form-group">
                        <label for="nama">Nama Studio <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="nama" 
                               class="form-control bg-dark text-white @error('nama') is-invalid @enderror" 
                               id="nama" 
                               value="{{ old('nama', $studio->nama) }}"
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
                               value="{{ old('kapasitas', $studio->kapasitas) }}"
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
                    
                    <button type="submit" class="btn btn-warning btn-block mt-4"><i class="fas fa-sync"></i> Perbarui Studio</button>
                    <a href="{{ route('studio.show', $studio->id) }}" class="btn btn-danger btn-block mt-2"><i class="fas fa-times"></i> Batal</a>
                </form>

            </div>
        </div>
        
    </div>
</div>
@endsection