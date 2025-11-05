@extends('layout.app') <!-- Sesuaikan dengan nama layout utama Anda (misal: layout.dashboard) -->

@section('content')
    <div class="content-header">
        <h1>Daftar Tugas</h1>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Manajemen Tugas</h3>
                    <!-- Tombol Tambah Tugas -->
                    <div class="card-tools">
                        <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Tugas Baru
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Nama Tugas</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                                <th style="width: 150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tasks as $task)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="{{ $task->is_completed ? 'text-success' : '' }}">
                                            {{ $task->title }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($task->is_completed)
                                            <span class="badge badge-success">Selesai</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $task->created_at->diffForHumans() }}</td>
                                    <td>
                                        <!-- Tombol Aksi (Edit, Hapus) akan ditambahkan nanti -->
                                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <!-- Form Delete akan ditambahkan nanti -->
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada tugas yang dibuat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    Total: {{ $tasks->count() }} Tugas
                </div>
            </div>
        </div>
    </section>
@endsection