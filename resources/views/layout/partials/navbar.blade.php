<nav class="fixed top-0 left-0 right-0 z-50 bg-black/60 border-b border-zinc-900/50 backdrop-blur-md transition-all duration-300">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      
      <!-- Logo -->
      <div class="flex-shrink-0 flex items-center">
        <a class="flex items-center gap-2 text-xl font-bold tracking-tight text-white hover:opacity-90 transition-opacity" href="{{ route('dashboard.user') }}">
          <span class="bg-gradient-to-r from-red-600 to-rose-500 bg-clip-text text-transparent flex items-center gap-2">
            <i class="fas fa-ticket-alt"></i> Seatly
          </span>
        </a>
      </div>

      <!-- Center Menu -->
      <div class="hidden md:flex items-center space-x-6">
        <a class="text-xs font-semibold uppercase tracking-wider text-zinc-400 hover:text-white transition-colors duration-200" href="{{ route('contacts') }}">Contacts</a>
      </div>

      <!-- Right Actions (Auth) -->
      <div class="hidden md:flex items-center space-x-4">
        @auth
          <span class="text-sm font-medium text-zinc-300">{{ Auth::user()->name }}</span>
          <a class="text-xs font-semibold uppercase tracking-wider bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 text-white px-4 py-2 rounded-full transition-all duration-300 shadow-md shadow-red-950/20 active:scale-95" 
             href="{{ route('user.logout') }}" 
             onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt mr-1"></i> Logout
          </a>
        @endauth

        @guest
          <a class="text-xs font-semibold uppercase tracking-wider text-zinc-300 hover:text-white transition-colors duration-200" href="{{ route('user.login.form') }}">
            Login
          </a>
          <a class="text-xs font-semibold uppercase tracking-wider bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 text-white px-4 py-2 rounded-full transition-all duration-300 shadow-md shadow-red-950/20 active:scale-95" 
             href="{{ route('user.register.form') }}">
            Register
          </a>
        @endguest
      </div>

      <!-- Mobile menu button -->
      <div class="flex md:hidden">
        <button id="mobile-menu-button" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-zinc-400 hover:text-white hover:bg-zinc-900 focus:outline-none transition-colors duration-200" aria-controls="mobile-menu" aria-expanded="false">
          <span class="sr-only">Open main menu</span>
          <i class="fas fa-bars text-xl" id="menu-icon-open"></i>
          <i class="fas fa-times text-xl hidden" id="menu-icon-close"></i>
        </button>
      </div>

    </div>
  </div>

  <!-- Mobile menu, show/hide based on menu state. -->
  <div class="hidden md:hidden bg-black/95 border-b border-zinc-900/80 transition-all duration-300" id="mobile-menu">
    <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
      <a class="block px-3 py-2 rounded-md text-base font-medium text-zinc-400 hover:text-white hover:bg-zinc-900 transition-colors" href="{{ route('contacts') }}">Contacts</a>
      
      <div class="border-t border-zinc-900/80 my-2 pt-2"></div>
      
      @auth
        <div class="px-3 py-2 text-base font-medium text-zinc-300">{{ Auth::user()->name }}</div>
        <a class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-rose-500 hover:bg-zinc-900 transition-colors" 
           href="{{ route('user.logout') }}" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt mr-1"></i> Logout
        </a>
      @endauth

      @guest
        <a class="block px-3 py-2 rounded-md text-base font-medium text-zinc-300 hover:text-white hover:bg-zinc-900 transition-colors" href="{{ route('user.login.form') }}">
          Login
        </a>
        <a class="block px-3 py-2 rounded-md text-base font-medium text-rose-500 hover:text-rose-400 transition-colors" href="{{ route('user.register.form') }}">
          Register
        </a>
      @endguest
    </div>
  </div>

  <form id="logout-form" action="{{ route('user.logout') }}" method="POST" class="hidden">
      @csrf
  </form>
</nav>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const button = document.getElementById('mobile-menu-button');
    const menu = document.getElementById('mobile-menu');
    const openIcon = document.getElementById('menu-icon-open');
    const closeIcon = document.getElementById('menu-icon-close');

    if (button && menu) {
      button.addEventListener('click', () => {
        const isExpanded = button.getAttribute('aria-expanded') === 'true';
        button.setAttribute('aria-expanded', !isExpanded);
        menu.classList.toggle('hidden');
        openIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
      });
    }
  });
</script>