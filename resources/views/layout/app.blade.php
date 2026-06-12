<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Seatly - Premium Cinema Ticket Booking')</title>

  <!-- Google Fonts: Instrument Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css?family=Instrument+Sans:400,500,600,700&display=swap" rel="stylesheet">
  
  <!-- FontAwesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Tailwind CSS & Vite scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  @yield('styles')
  @stack('styles')
</head>
  
<body class="bg-black text-zinc-100 font-sans min-h-screen flex flex-col selection:bg-rose-500 selection:text-white">
  
  <!-- Navbar -->
  @include('layout.partials.navbar')
  <!-- /Navbar -->

  <!-- Main Content Wrapper -->
  <main class="flex-grow pt-16">
    @yield('content')
  </main>
  <!-- /Main Content Wrapper -->

  <!-- Footer -->
  <footer class="bg-black/80 border-t border-zinc-900/50 backdrop-blur-md py-8">
    @include('layout.partials.footer')
  </footer>
  <!-- /Footer -->

  <!-- Custom Scripts -->
  @stack('scripts')
</body>
</html>
