@extends('layout.app')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center bg-black py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8 bg-zinc-950/40 border border-zinc-900 rounded-3xl p-8 backdrop-blur-sm shadow-xl shadow-red-950/5 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-red-600/5 rounded-full blur-3xl pointer-events-none"></div>
        
        <!-- Header -->
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-rose-500/10 text-rose-500 border border-rose-500/20 mb-4">
                <i class="fas fa-user-plus text-lg"></i>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-white">
                USER REGISTRATION
            </h2>
            <p class="text-xs text-zinc-500 mt-2">Create a new Seatly account to book tickets</p>
        </div>

        <form class="mt-8 space-y-6" method="POST" action="{{ route('user.register') }}">
            @csrf

            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Your Full Name"
                           class="w-full bg-zinc-950 border border-zinc-900 rounded-xl px-4 py-3 text-sm text-zinc-200 placeholder-zinc-700 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all duration-300 @error('name') border-red-500 @enderror">
                    @error('name')
                        <span class="text-xs text-red-500 mt-1 block">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="email@example.com"
                           class="w-full bg-zinc-950 border border-zinc-900 rounded-xl px-4 py-3 text-sm text-zinc-200 placeholder-zinc-700 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all duration-300 @error('email') border-red-500 @enderror">
                    @error('email')
                        <span class="text-xs text-red-500 mt-1 block">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••"
                           class="w-full bg-zinc-950 border border-zinc-900 rounded-xl px-4 py-3 text-sm text-zinc-200 placeholder-zinc-700 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all duration-300 @error('password') border-red-500 @enderror">
                    @error('password')
                        <span class="text-xs text-red-500 mt-1 block">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password-confirm" class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Konfirmasi Password</label>
                    <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••"
                           class="w-full bg-zinc-950 border border-zinc-900 rounded-xl px-4 py-3 text-sm text-zinc-200 placeholder-zinc-700 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all duration-300">
                </div>
            </div>

            <!-- Submit -->
            <div>
                <button type="submit" class="w-full text-center py-4 text-xs font-bold uppercase tracking-wider text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 rounded-xl transition-all duration-300 shadow-md shadow-red-950/40 active:scale-95 cursor-pointer">
                    <i class="fas fa-user-check mr-2"></i> Register Now
                </button>
            </div>
            
            <div class="text-center text-xs text-zinc-500 mt-4">
                Sudah punya akun? <a href="{{ route('user.login.form') }}" class="text-rose-500 hover:underline">Log in disini</a>
            </div>
        </form>
    </div>
</div>
@endsection