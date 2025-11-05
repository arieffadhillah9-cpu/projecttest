@extends('layout.app')

@section('content')
    <div class="content-header">
        <h1>Tambah Tugas Baru</h1>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Tugas</h3>
                </div>
                <!-- /.card-header -->
                
                <!-- Form untuk mengirim data ke TaskController@store -->
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf {{-- Wajib untuk keamanan --}}
                    
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Judul Tugas</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Masukkan judul tugas, cth: Bayar listrik hari ini" value="{{ old('title') }}" required>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan Tugas</button>
                        <a href="{{ route('tasks.index') }}" class="btn btn-default float-right">Batal</a>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </section>
@endsection