<nav class="navbar navbar-expand-lg navbar-dark border-bottom border-danger fixed-top" style="background-color: #000000 !important;">

 <div class="container-fluid">

 <a class="navbar-brand text-danger font-weight-bold" href="{{ route('dashboard.user') }}">

 <i class="fas fa-ticket-alt"></i> Seatly

   

        </a>

       

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavUser" aria-controls="navbarNavUser" aria-expanded="false" aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>

       

        <div class="collapse navbar-collapse" id="navbarNavUser">

            <ul class="navbar-nav mr-auto">

                </li>

                <li class="nav-item">

                    <a class="nav-link text-white" href="#">MOVIES</a>

                </li>

                <li class="nav-item">

                    <a class="nav-link text-white" href="#">TICKET</a>

                </li>

                 <li class="nav-item">

    <a class="nav-link text-white" href="{{ route('contacts') }}">CONTACTS</a>

</li>

            </ul>

        </div>


        <div class="ml-auto">

            <ul class="navbar-nav">

                @auth
                {{-- Nama Pengguna --}}
                <li class="nav-item">
                    <span class="nav-link text-light">{{ Auth::user()->name }}</span>
                </li>
    
                {{-- TOMBOL LOGOUT LANGSUNG --}}
                <li class="nav-item">
                    <a class="nav-link text-danger" href="{{ route('user.logout') }}"  {{-- <--- KOREKSI DI SINI --}}
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
            @endauth

                @guest

                {{-- KOTAK REGISTER --}}

                <li class="nav-item" style="margin-right: 10px;">

                    <a class="nav-link text-light btn btn-danger btn-sm"

                        {{-- GANTI href="#" MENJADI RUTE REGISTER USER --}}

                        href="{{ route('user.register.form') }}"

                        style="padding: 0.25rem 0.5rem; line-height: 1.5; border-radius: 0.25rem;">

                        REGISTER

                    </a>
                </li>
                @endguest

                @guest

                {{-- KOTAK LOGIN --}}

                <li class="nav-item">

                    <a class="nav-link text-light btn btn-danger btn-sm"

                        {{-- GANTI href="#" MENJADI RUTE LOGIN USER --}}

                        href="{{ route('user.login.form') }}"

                        style="padding: 0.25rem 0.5rem; line-height: 1.5; border-radius: 0.25rem;">

                        LOGIN

                    </a>
                </li>
                @endguest

            </ul>
        </div>
    </ul>

        </div>

    </div>

<form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
    @csrf
</form>

</nav>