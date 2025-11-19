@extends('admin.layout.adminapp')

@section('content')

{{-- content-wrapper diatur ke bg-black dan min-height agar konten terlihat rapi --}}
<div class="content-wrapper bg-black text-white pt-5" style="min-height: 80vh;">
    <div class="container">
        
        {{-- Kontainer Utama Form, dibatasi max-width 800px dan di tengah --}}
        <div class="mx-auto" style="max-width: 800px;">
            
            {{-- START: KONTROL JUDUL --}}
            <div class="mb-4">
                <div class="card bg-dark border-secondary shadow-lg">
                    <div class="card-body py-3">
                        <h2 class="font-weight-bold text-white mb-0">
                            <i class="fas fa-plus-circle mr-2 text-danger"></i> Tambah Jadwal Tayang Baru
                        </h2>
                    </div>
                </div>
            </div>
            {{-- END: KONTROL JUDUL --}}

            {{-- Kartu Form Utama --}}
            <div class="card bg-dark border-secondary text-white shadow-lg">
                <div class="card-body">

                    {{-- Menampilkan pesan error konflik jadwal --}}
                    @if (Session::has('error'))
                        <div class="alert alert-danger alert-dismissible fade show bg-danger text-white border-0" role="alert">
                            {{ Session::get('error') }}
                            <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('jadwal.store') }}" method="POST">
                        @csrf

                        {{-- 1. PILIH FILM --}}
                        <div class="form-group">
                            <label for="film_id" class="text-white">Pilih Film</label>
                            <select name="film_id" id="film_id" 
                                class="form-control form-control-dark bg-secondary border-secondary text-white @error('film_id') is-invalid @enderror" 
                                required>
                                <option value="" class="bg-dark">-- Pilih Film Sedang Tayang --</option>
                                @foreach($films as $film)
                                    <option value="{{ $film->id }}" 
                                            class="bg-dark"
                                            {{ old('film_id') == $film->id ? 'selected' : '' }}>
                                        {{ $film->judul }} ({{ $film->durasi_menit }} Menit)
                                    </option>
                                @endforeach
                            </select>
                            @error('film_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 2. PILIH STUDIO --}}
                        <div class="form-group">
                            <label for="studio_id" class="text-white">Pilih Studio</label>
                            <select name="studio_id" id="studio_id" 
                                class="form-control form-control-dark bg-secondary border-secondary text-white @error('studio_id') is-invalid @enderror" 
                                required>
                                <option value="" class="bg-dark">-- Pilih Studio --</option>
                                @foreach($studios as $studio)
                                    <option value="{{ $studio->id }}" 
                                            class="bg-dark"
                                            {{ old('studio_id') == $studio->id ? 'selected' : '' }}>
                                        {{ $studio->nama }} (Kapasitas: {{ $studio->kapasitas_kursi }} Kursi)
                                    </option>
                                @endforeach
                            </select>
                            @error('studio_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 3. TANGGAL TAYANG --}}
                        <div class="form-group">
                            <label for="tanggal" class="text-white">Tanggal Tayang</label>
                            <input type="date" name="tanggal" id="tanggal" 
                                class="form-control bg-secondary border-secondary text-white @error('tanggal') is-invalid @enderror" 
                                value="{{ old('tanggal', now()->toDateString()) }}" required min="{{ now()->toDateString() }}">
                            @error('tanggal')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 4. JAM MULAI --}}
                        <div class="form-group">
                            <label for="jam_mulai" class="text-white">Jam Mulai (Format HH:MM)</label>
                            <input type="time" name="jam_mulai" id="jam_mulai" 
                                class="form-control bg-secondary border-secondary text-white @error('jam_mulai') is-invalid @enderror" 
                                value="{{ old('jam_mulai') }}" required>
                            @error('jam_mulai')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 5. HARGA TIKET --}}
                        <div class="form-group">
                            <label for="harga" class="text-white">Harga Tiket (Rp)</label>
                            <input type="number" name="harga" id="harga" 
                                class="form-control bg-secondary border-secondary text-white @error('harga') is-invalid @enderror" 
                                value="{{ old('harga', 40000) }}" min="10000" required>
                            @error('harga')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mt-4 pt-3 border-top border-secondary d-flex justify-content-between">
                            <button type="submit" class="btn btn-danger font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Simpan Jadwal
                            </button>
                            <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times-circle mr-1"></i> Batal
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection