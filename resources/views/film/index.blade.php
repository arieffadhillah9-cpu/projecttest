@extends('layout.app')

@section('content')
    <div class="content-header">
        <h1>Daftar Film Tayang</h1>
        <a href="{{ route('film.create') }}" class="btn btn-success float-right">Tambah Film</a>
    </div>

    <section class="content">
        <div class="container-fluid">

            {{-- Menampilkan pesan sukses dari method store() (dengan timeout) --}}
            @if (session('success'))
                {{-- Pastikan class Bootstrap 'fade show' dan 'alert-dismissible' ada --}}
                <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    {{-- Tombol X penutup manual (data-dismiss="alert" dari Bootstrap) --}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    @if ($films->isEmpty())
                        <p>Belum ada data film yang tersedia. Silakan tambahkan film baru.</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Judul</th>
                                    <th>Durasi (Menit)</th>
                                    <th>Tanggal Rilis</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($films as $film)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $film->judul }}</td>
                                        <td>{{ $film->durasi_menit }}</td>
                                        <td>{{ $film->tanggal_rilis }}</td>
                                        <td>
                                            {{-- Koreksi: Mengubah label dari 'Edit' menjadi 'Detail' karena menggunakan route('film.show') --}}
                                            <a href="{{ route('film.show', $film->id) }}" class="btn btn-sm btn-info">Detail</a>
                                            
                                            {{-- Form Hapus (menggunakan route film.destroy) --}}
                                            <form action="{{ route('film.destroy', $film->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus film ini?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

{{-- PUSH SCRIPT DI BAWAH SECTION CONTENT --}}
@push('scripts') 
<script>
    // Pastikan jQuery tersedia di layout Anda
    $(document).ready(function(){
        // Seleksi alert dengan ID 'success-alert', delay 4 detik, slide up 0.5 detik, lalu tutup alert.
        $("#success-alert").delay(4000).slideUp(500, function(){
            $(this).alert('close'); 
        });
    });
</script>
@endpush