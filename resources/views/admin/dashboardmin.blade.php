@extends('layouts.app') 

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">AREA ADMINISTRATOR</h5>
                </div>

                <div class="card-body">
                    <p class="lead">Selamat datang, {{ Auth::user()->name }}!</p>
                    <p>Anda telah berhasil masuk ke Dashboard Admin. Ini adalah area rahasia.</p>
                    
                    <hr>
                    <h6 class="text-primary">Menu Utama:</h6>
                    <ul>
                        <li><a href="{{ route('admin.film.index') }}" class="text-decoration-none">Kelola Film</a></li>
                        <li><a href="{{ route('admin.studio.index') }}" class="text-decoration-none">Kelola Studio</a></li>
                        <li><a href="{{ route('admin.jadwal.index') }}" class="text-decoration-none">Kelola Jadwal Tayang</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection