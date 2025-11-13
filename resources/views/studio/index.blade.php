@extends('studio.studioapp')

@section('content')

{{-- Bagian Jumbotron (Header Konsisten) --}}
<div class="jumbotron jumbotron-fluid text-white" style="background-color: #1a1a1a; padding: 50px 0; margin-bottom: 0;">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="display-4 font-weight-bold">Daftar Studio Bioskop</h1>
        <a href="{{ route('studio.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus"></i> Tambah Studio
        </a>
    </div>
</div>

{{-- Wrapper Konten Utama --}}
<div class="bg-dark py-5 text-white" style="min-height: 80vh;">
    <div class="container">
        
        {{-- Pesan Sukses/Error --}}
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        {{-- Tabel Daftar Studio --}}
        <div class="card bg-secondary text-white shadow-lg">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover mb-0">
                        <thead class="bg-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama Studio</th>
                                <th>Kapasitas Kursi</th>
                                <th>Tipe Layar</th>
                                <th width="200px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($studios as $studio)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('studio.show', $studio->id) }}" class="text-warning font-weight-bold">
                                            {{ $studio->nama }}
                                        </a>
                                    </td>
                                    <td>{{ number_format($studio->kapasitas) }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $studio->tipe_layar ?? '2D Standard' }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('studio.edit', $studio->id) }}" class="btn btn-sm btn-warning mr-2">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('studio.destroy', $studio->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus studio {{ $studio->nama }}?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Belum ada data studio yang tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection