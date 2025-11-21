<nav class="navbar navbar-expand-lg navbar-dark border-bottom border-danger" style="background-color: #000000 !important;">
    <div class="container-fluid">
        <!-- Brand/Logo -->
        <a class="navbar-brand text-danger font-weight-bold" href="#">
            <i class="fas fa-ticket-alt"></i> Seatly Admin
        </a>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAdmin" aria-controls="navbarNavAdmin" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Link Navigasi Utama (Diperbaiki: menggunakan .index) -->
        <div class="collapse navbar-collapse" id="navbarNavAdmin">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    {{-- Perbaikan: Menggunakan admin.film.index --}}
                    <a class="nav-link" href="{{ route('admin.film.index') }}">Film</a> 
                </li>
                <li class="nav-item">
                    {{-- Perbaikan: Menggunakan admin.jadwal.index --}}
                    <a class="nav-link" href="{{ route('admin.jadwal.index') }}">Jadwal</a> 
                </li>
                <li class="nav-item">
                    {{-- Perbaikan: Menggunakan admin.studio.index --}}
                    <a class="nav-link" href="{{ route('admin.studio.index') }}">Studio</a> 
                </li>
            </ul>
        </div>

        <!-- Menu Kanan: Nama Pengguna & Logout Langsung -->
        <div class="ml-auto">
            <ul class="navbar-nav">
                @auth
                
                {{-- Nama Pengguna (tanpa Dropdown, hanya teks) --}}
                <li class="nav-item">
                    <span class="nav-link text-light">{{ Auth::user()->name }}</span>
                </li>
                
                {{-- TOMBOL LOGOUT LANGSUNG (tetap sama) --}}
                <li class="nav-item">
                    <a class="nav-link text-danger" href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
                @endauth

                @guest
                <li class="nav-item">
                    <a class="nav-link text-light" href="{{ route('login') }}">Login</a>
                </li>
                @endguest
            </ul>
        </div>
    </div>

    <!-- FORM TERSEMBUNYI UNTUK REQUEST POST LOGOUT (PENTING!) -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</nav>