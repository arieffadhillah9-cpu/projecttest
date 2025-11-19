@extends('admin.layout.adminapp')

@section('content')

{{-- content-wrapper diatur ke bg-black dan min-height agar konten terlihat rapi --}}
<div class="content-wrapper bg-black text-white pt-5" style="min-height: 80vh;">
    <div class="container"> 
        
        {{-- START: KONTROL JUDUL DAN TOMBOL --}}
        <div class="mb-4 mx-auto" style="max-width: 800px;"> 
            
            <div class="card bg-dark border-secondary shadow-lg">
                
                {{-- FIX PRESISI ADA DI BAGIAN INI --}}
                <div class="card-body py-3 d-flex align-items-center" style="gap: 10px;">
                    
                    {{-- Judul (diberi flex-grow agar tombol tetap presisi kanan) --}}
                    <h2 class="font-weight-bold text-white mb-0 flex-grow-1">
                        <i class="fas fa-desktop mr-2"></i> Daftar Studio Bioskop
                    </h2>
                    
                    {{-- Tombol Tambah Studio --}}
                    <a href="{{ route('studio.create') }}" 
                       class="btn btn-danger d-flex align-items-center"
                       style="white-space: nowrap;">
                        <i class="fas fa-plus mr-1"></i> Tambah Studio
                    </a>

                </div>
            </div>
        </div>
        {{-- END: KONTROL JUDUL DAN TOMBOL --}}
        
        {{-- Kontainer untuk Alert & Tabel --}}
        <div class="mx-auto" style="max-width: 800px;">
            
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ $message }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            
            <div class="card bg-dark border-secondary text-white shadow-lg">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        
                        <table class="table table-dark table-striped table-hover mb-0">
                            <thead class="bg-black">
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
                                            <span class="badge badge-danger">{{ $studio->tipe_layar ?? '2D Standard' }}</span>
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
</div>

@endsection
