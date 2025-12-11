@extends('admin.layout.adminapp')

@section('content')

{{-- Wrapper Konten Utama (Form) --}}
<div class="content-wrapper bg-black text-white py-5" style="min-height: 80vh;">
    <div class="container-fluid">
        
        {{-- Header Konten --}}
        <div class="container d-flex justify-content-start align-items-center mb-4 px-0" style="max-width: 700px;">
            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary mr-3" style="background-color: #343a40; border-color: #343a40;">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <h2 class="font-weight-bold">Tambah Jadwal Tayang Baru</h2>
        </div>

        {{-- Card Form Utama --}}
        <div class="card bg-black text-white border-secondary shadow-lg mx-auto" style="max-width: 700px;"> 
            
            <div class="card-header bg-dark text-white border-bottom border-secondary">
                <h4 class="mb-0">Form Jadwal Tayang</h4>
            </div>
            
            <div class="card-body">

                {{-- Menampilkan pesan error validasi (Laravel Validation) --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                {{-- Menampilkan pesan error khusus (misal: "Tidak ada film yang sedang tayang") --}}
                @if (Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show bg-danger text-white border-0" role="alert">
                        {{ Session::get('error') }}
                        <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- FORM MULAI --}}
                {{-- Route sudah dikoreksi menjadi route('...') --}}
                <form action="{{ route('admin.jadwal.store') }}" method="POST">
                    @csrf

                    {{-- 1. PILIH FILM --}}
                    <div class="form-group">
                        <label for="film_id" class="text-white">Pilih Film <span class="text-danger">*</span></label>
                        <select name="film_id" id="film_id" 
                            class="form-control bg-dark text-white @error('film_id') is-invalid @enderror" 
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
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 2. PILIH STUDIO --}}
                    <div class="form-group">
                        <label for="studio_id" class="text-white">Pilih Studio <span class="text-danger">*</span></label>
                        <select name="studio_id" id="studio_id" 
                            class="form-control bg-dark text-white @error('studio_id') is-invalid @enderror" 
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
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 3. TANGGAL TAYANG & JAM MULAI --}}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="tanggal" class="text-white">Tanggal Tayang <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" id="tanggal" 
                                class="form-control bg-dark text-white @error('tanggal') is-invalid @enderror" 
                                value="{{ old('tanggal', now()->toDateString()) }}" required min="{{ now()->toDateString() }}">
                            @error('tanggal')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="jam_mulai" class="text-white">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_mulai" id="jam_mulai" 
                                class="form-control bg-dark text-white @error('jam_mulai') is-invalid @enderror" 
                                value="{{ old('jam_mulai') }}" required>
                            @error('jam_mulai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- 4. HARGA TIKET --}}
                    <div class="form-group">
                        <label for="harga" class="text-white">Harga Tiket (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="harga" id="harga" 
                            class="form-control bg-dark text-white @error('harga') is-invalid @enderror" 
                            value="{{ old('harga', 40000) }}" min="10000" required placeholder="Masukkan harga tiket (min. 10000)">
                        @error('harga')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Tombol Submit --}}
                    <button type="submit" class="btn btn-danger btn-block mt-4 py-2">
                        <i class="fas fa-save mr-1"></i> Simpan Jadwal
                    </button>
                    
                </form>

            </div>
        </div>
        
    </div>
</div>
@section('scripts')
<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        // Ambil nilai dari input terpisah
        const tanggal = document.getElementById('tanggal').value;
        const jamMulai = document.getElementById('jam_mulai').value;
        
        // Buat nilai gabungan (format YYYY-MM-DD HH:MM:00)
        const waktuTayang = tanggal + ' ' + jamMulai + ':00';

        // Buat input tersembunyi yang akan dikirim ke Controller
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'waktu_tayang'; // <--- Nama ini harus dicari Controller!
        hiddenInput.value = waktuTayang;

        // Tambahkan input tersembunyi ke dalam form
        this.appendChild(hiddenInput);
    });
</script>
@endsection