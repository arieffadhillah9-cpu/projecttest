@extends('admin.jadwal.jadwalapp')

@section('content')
    <div class="content-header">
        Edit Jadwal Tayang: 
        {{ $jadwalTayang->film->judul ?? 'Film Tidak Ditemukan' }} 
        di Studio 
        {{ $jadwalTayang->studio->nama ?? 'Studio Tidak Ditemukan' }}
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">

                    {{-- Menampilkan pesan error konflik jadwal --}}
                    @if (Session::has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ Session::get('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('jadwal.update', $jadwalTayang->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- 1. PILIH FILM --}}
                        <div class="form-group">
                            <label for="film_id">Pilih Film</label>
                            <select name="film_id" id="film_id" class="form-control @error('film_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Film Sedang Tayang --</option>
                                @foreach($films as $film)
                                    {{-- Menggunakan nilai lama atau nilai database jika tidak ada nilai lama --}}
                                    <option value="{{ $film->id }}" {{ old('film_id', $jadwalTayang->film_id) == $film->id ? 'selected' : '' }}>
                                        {{ $film->judul }} ({{ $film->durasi_menit }} Menit)
                                    </option>
                                @endforeach
                            </select>
                            @error('film_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 2. PILIH STUDIO --}}
                        <div class="form-group">
                            <label for="studio_id">Pilih Studio</label>
                            <select name="studio_id" id="studio_id" class="form-control @error('studio_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Studio --</option>
                                @foreach($studios as $studio)
                                    <option value="{{ $studio->id }}" {{ old('studio_id', $jadwalTayang->studio_id) == $studio->id ? 'selected' : '' }}>
                                        {{ $studio->nama }} (Kapasitas: {{ $studio->kapasitas_kursi }} Kursi)
                                    </option>
                                @endforeach
                            </select>
                            @error('studio_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 3. TANGGAL TAYANG --}}
                        <div class="form-group">
                            <label for="tanggal">Tanggal Tayang</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                                   value="{{ old('tanggal', $jadwalTayang->tanggal->format('Y-m-d')) }}" required min="{{ now()->toDateString() }}">
                            @error('tanggal')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 4. JAM MULAI --}}
                        <div class="form-group">
                            <label for="jam_mulai">Jam Mulai (Format HH:MM)</label>
                            <input type="time" name="jam_mulai" id="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror" 
                                   value="{{ old('jam_mulai', \Carbon\Carbon::parse($jadwalTayang->jam_mulai)->format('H:i')) }}" required>
                            @error('jam_mulai')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 5. HARGA TIKET --}}
                        <div class="form-group">
                            <label for="harga">Harga Tiket (Rp)</label>
                            <input type="number" name="harga" id="harga" class="form-control @error('harga') is-invalid @enderror" 
                                   value="{{ old('harga', $jadwalTayang->harga) }}" min="10000" required>
                            @error('harga')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Batal</a>
                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection