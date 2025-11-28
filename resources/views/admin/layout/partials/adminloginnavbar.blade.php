<nav class="navbar navbar-expand-lg navbar-dark border-bottom border-danger" style="background-color: #000000 !important;">
    <div class="container-fluid">
        <a class="navbar-brand text-danger font-weight-bold" href="{{ url('/') }}">
            <i class="fas fa-ticket-alt"></i> Seatly Admin
        </a>
        
        
        <div class="ml-auto">
            <ul class="navbar-nav">
               
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link text-light" href="{{ route('register') }}">Register</a>
                    </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link text-danger" href="{{ route('login') }}">Login Admin</a>
                </li>
            </ul>
        </div>
    </div>
</nav>