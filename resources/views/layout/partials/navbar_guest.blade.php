[Immersive content redacted for brevity.]
        <!-- LOGIKA PEMUATAN NAVBAR DI SINI -->
        @guest
            {{-- Belum login (di halaman login admin) -> Tampilkan navbar minimal --}}
            @include('partials.navbar_guest') 
        @else
            {{-- Sudah login (di halaman dashboard admin) -> Tampilkan navbar lengkap --}}
            @include('admin.layout.adminnavbar')
        @endguest
        
        <main class="py-4">
[Immersive content redacted for brevity.]