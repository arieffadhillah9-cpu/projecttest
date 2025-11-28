<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Seatly Admin</title>

    <!-- Scripts (app.js) -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Admin Styles -->
    {{-- Ini harus ada untuk AdminLTE --}}
    @include('admin.layout.partials.adminstyle')
    
</head>

{{-- Body class hanya aktif saat Login untuk fitur AdminLTE (sidebar-collapse) --}}
<body class="@auth hold-transition sidebar-collapse @endauth" style="background-color: #000000 !important;">
    
    @guest
        {{-- 1. Tampilan Guest (Halaman Login/Register) --}}
        <div id="app">
            {{-- MEMUAT NAVBAR MINIMAL UNTUK HALAMAN LOGIN/GUEST ADMIN --}}
            @include('admin.layout.partials.adminloginnavbar') {{-- PERBAIKAN: Masuk ke folder partials --}}
            
            <main class="py-4">
                @yield('content') {{-- Konten Login Form dari auth/login.blade.php --}}
            </main>
        </div>
        
    @else
        {{-- 2. Tampilan Authenticated (Dashboard/CRUD) --}}
        <div class="wrapper">
            
            {{-- Navbar Admin Lengkap (Menggunakan partials/adminnavbar) --}}
            @include('admin.layout.partials.adminnavbar')

            {{-- Sidebar --}}
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                {{-- Anda bisa memuat sidebar di sini, contoh: @include('admin.layout.partials.adminsidebar') --}}
            </aside>

            {{-- Content Wrapper --}}
            <div class="content-wrapper text-white" style="background-color: #000000 !important;">
                {{-- Content --}}
                <main class="py-4">
                    @yield('content') 
                </main>
            </div>
            
            {{-- Control Sidebar --}}
            <aside class="control-sidebar control-sidebar-dark">
                <div class="p-3">
                    <h5>Title</h5>
                    <p>Sidebar content</p>
                </div>
            </aside>
            
            {{-- Footer (Menggunakan partials/adminfooter) --}}
            <footer class="main-footer bg-dark text-white border-top-0" style="background-color: #000000 !important; padding: 20px 0;">
                @include('admin.layout.partials.adminfooter')
            </footer>
        </div>
        
        {{-- Admin Scripts (Dibutuhkan untuk fitur AdminLTE seperti sidebar) --}}
        @include('admin.layout.partials.adminscript')
    @endauth
    
</body>
</html>