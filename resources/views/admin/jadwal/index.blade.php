@extends('admin.layout.adminapp')

@section('content')

{{-- Wrapper konten --}}
<div class="content-wrapper bg-black text-white pt-5" style="min-height: 90vh;">
    <div class="container">

        {{-- =======================
            HEADER JUDUL + TOMBOL
        ======================== --}}
        <div class="mb-4 mx-auto" style="max-width: 900px;">

            <div class="card bg-dark border-secondary shadow-lg">
                <div class="card-body py-3">

                    <div class="d-flex flex-wrap justify-content-between align-items-center">

                        {{-- Judul --}}
                        <div class="text-white font-weight-bold" style="font-size: 1.6rem;">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Jadwal Tayang Bioskop
                        </div>

                        {{-- Tombol Tambah Jadwal --}}
                        <a href="{{ route('admin.jadwal.create') }}"
                            class="btn btn-danger d-flex align-items-center mt-3 mt-md-0 px-3"
                            style="white-space: nowrap; height: 40px;">
                            <i class="fas fa-plus mr-1"></i> Tambah Jadwal Baru
                        </a>

                    </div>

                </div>
            </div>

        </div>
        {{-- ======================= --}}
        {{-- END HEADER --}}
        {{-- ======================= --}}



        {{-- =======================
            ALERT + TABLE WRAPPER
        ======================== --}}
        <div class="mx-auto" style="max-width: 900px;">

            {{-- Alert Success --}}
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Alert Error --}}
            @if(Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif



            {{-- =======================
                TABLE JADWAL TAYANG
            ======================== --}}
            <div class="card bg-dark border-secondary text-white shadow-lg">
                <div class="card-body p-0">

                    <div class="table-responsive">
                        <table class="table table-dark table-striped table-hover mb-0">
                            <thead class="bg-black">
                                <tr>
                                    <th>#</th>
                                    <th>Film</th>
                                    <th>Studio</th>
                                    <th>Tanggal</th>
                                    <th>Jam Mulai</th>
                                    <th>Harga Tiket</th>
                                    <th style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($jadwalTayangs as $jadwal)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td>
                                            <strong class="text-warning">{{ $jadwal->film->judul }}</strong>
                                        </td>

                                        <td>{{ $jadwal->studio->nama }}</td>

                                        <td>{{ $jadwal->tanggal->format('d M Y') }}</td>

                                        <td>
                                            <span class="badge badge-info">
                                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} WIB
                                            </span>
                                        </td>

                                        <td>
                                            Rp {{ number_format($jadwal->harga, 0, ',', '.') }}
                                        </td>

                                        <td>
                                            <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}"
                                                class="btn btn-sm btn-warning mr-2">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>

                                            <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus jadwal tayang untuk film {{ $jadwal->film->judul }} pada studio {{ $jadwal->studio->nama }}?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            Belum ada jadwal tayang yang ditambahkan.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>

                </div>

                {{-- Pagination --}}
                @if ($jadwalTayangs->hasPages())
                    <div class="card-footer clearfix bg-dark border-top border-secondary">
                        {{ $jadwalTayangs->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>

        </div>
        {{-- ======================= --}}
        {{-- END TABLE --}}
        {{-- ======================= --}}

    </div>
</div>





{{-- =======================
    MODAL HAPUS
======================= --}}
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog"
    aria-labelledby="deleteModalLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content bg-dark text-white">

            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Jadwal</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span class="text-white">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                Apakah Anda yakin ingin menghapus jadwal ini? Tindakan ini tidak dapat dibatalkan.
            </div>

            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>

                <form id="deleteForm" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        Hapus Permanen
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>



{{-- =======================
    SCRIPT DELETE MODAL
======================= --}}
@push('scripts')
<script>
    $(document).ready(function () {
        $('.delete-btn').on('click', function () {
            var id = $(this).data('id');
            var url = '{{ url('jadwal') }}/' + id;
            $('#deleteForm').attr('action', url);
        });
    });
</script>
@endpush

@endsection
