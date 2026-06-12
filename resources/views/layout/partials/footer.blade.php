<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
    <div class="flex items-center">
        <span class="text-sm font-semibold tracking-wider bg-gradient-to-r from-red-600 to-rose-500 bg-clip-text text-transparent">
            Seatly Cinema
        </span>
    </div>
    
    <div class="flex items-center space-x-6">
        <a href="{{ route('dashboard.user') }}" class="text-xs font-medium text-zinc-500 hover:text-white transition-colors duration-200 uppercase tracking-wider">Movies</a>
        <a href="{{ route('contacts') }}" class="text-xs font-medium text-zinc-500 hover:text-white transition-colors duration-200 uppercase tracking-wider">Contacts</a>
    </div>

    <div class="text-zinc-600 text-[10px] uppercase tracking-widest font-medium">
        &copy; {{ date('Y') }} Seatly. All Rights Reserved.
    </div>
</div>