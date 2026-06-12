@extends('layout.app')

@section('title', 'Hubungi Kami')

@section('content')
<div class="min-h-screen bg-black py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white uppercase">
                Pusat Bantuan <span class="bg-gradient-to-r from-red-600 to-rose-500 bg-clip-text text-transparent">Seatly Cinema</span>
            </h1>
            <p class="text-sm sm:text-base text-zinc-400 mt-4 max-w-2xl mx-auto font-light">
                Kami di sini untuk mendengarkan. Sampaikan pertanyaan, saran, atau masukan Anda di bawah ini.
            </p>
        </div>
        
        <!-- Card Wrapper -->
        <div class="bg-zinc-950/40 border border-zinc-900 rounded-3xl p-6 sm:p-10 backdrop-blur-sm shadow-xl shadow-red-950/5">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
                
                <!-- Left Details: Office Info -->
                <div class="md:col-span-5 space-y-8 md:border-r md:border-zinc-900/80 md:pr-10">
                    <h2 class="text-lg font-bold text-rose-500 flex items-center gap-2">
                        <i class="fas fa-headset"></i> Kunjungi Kami
                    </h2>
                    
                    <ul class="space-y-6">
                        <li class="flex items-start gap-4">
                            <i class="fas fa-map-marker-alt text-rose-500 mt-1 text-lg shrink-0"></i>
                            <div>
                                <span class="text-xs text-zinc-500 block font-medium">Alamat Kantor Pusat</span>
                                <span class="text-sm text-zinc-200 mt-1 block font-light">Jl. Teater Megah No. 50, Jakarta Pusat</span>
                            </div>
                        </li>
                        
                        <li class="flex items-start gap-4">
                            <i class="fas fa-envelope text-rose-500 mt-1 text-lg shrink-0"></i>
                            <div>
                                <span class="text-xs text-zinc-500 block font-medium">Email Layanan Pelanggan</span>
                                <a href="mailto:seatlycinema@gmail.com" class="text-sm text-zinc-200 mt-1 block font-light hover:text-rose-400 transition-colors duration-200">
                                    seatlycinema@gmail.com
                                </a>
                            </div>
                        </li>
                        
                        <li class="flex items-start gap-4">
                            <i class="fas fa-phone-alt text-rose-500 mt-1 text-lg shrink-0"></i>
                            <div>
                                <span class="text-xs text-zinc-500 block font-medium">Telepon (Bebas Pulsa)</span>
                                <span class="text-sm text-zinc-200 mt-1 block font-light">0800-FILM-NOW (0800-3456-669)</span>
                            </div>
                        </li>
                        
                        <li class="flex items-start gap-4">
                            <i class="fab fa-instagram text-rose-500 mt-1 text-lg shrink-0"></i>
                            <div>
                                <span class="text-xs text-zinc-500 block font-medium">Ikuti Kami di Instagram</span>
                                <a href="https://www.instagram.com/seatlycinema" target="_blank" class="text-sm text-zinc-200 mt-1 block font-light hover:text-rose-400 transition-colors duration-200">
                                    @seatlycinema
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Right Details: Contact Form -->
                <div class="md:col-span-7">
                    <h2 class="text-lg font-bold text-rose-500 flex items-center gap-2 mb-6">
                        <i class="fas fa-paper-plane"></i> Kirim Pesan Langsung
                    </h2>
                    
                    <form action="#" method="POST" class="space-y-6">
                        @csrf 
                        
                        <div>
                            <label for="name" class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Nama Lengkap</label>
                            <input type="text" id="name" name="name" required placeholder="Cth: Budi Santoso"
                                   class="w-full bg-zinc-950 border border-zinc-900 rounded-xl px-4 py-3 text-sm text-zinc-200 placeholder-zinc-600 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all duration-300">
                        </div>
                        
                        <div>
                            <label for="email" class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Alamat Email</label>
                            <input type="email" id="email" name="email" required placeholder="Cth: budi@email.com"
                                   class="w-full bg-zinc-950 border border-zinc-900 rounded-xl px-4 py-3 text-sm text-zinc-200 placeholder-zinc-600 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all duration-300">
                        </div>
                        
                        <div>
                            <label for="subject" class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Subjek / Topik Bantuan</label>
                            <input type="text" id="subject" name="subject" required placeholder="Cth: Permintaan Refund Tiket"
                                   class="w-full bg-zinc-950 border border-zinc-900 rounded-xl px-4 py-3 text-sm text-zinc-200 placeholder-zinc-600 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all duration-300">
                        </div>
                        
                        <div>
                            <label for="message" class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Pesan Anda</label>
                            <textarea id="message" name="message" rows="5" required placeholder="Tuliskan pesan Anda secara rinci di sini..."
                                      class="w-full bg-zinc-950 border border-zinc-900 rounded-xl px-4 py-3 text-sm text-zinc-200 placeholder-zinc-600 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all duration-300 resize-none"></textarea>
                        </div>
                        
                        <div class="pt-4">
                            <button type="submit" class="w-full text-center py-4 text-xs font-bold uppercase tracking-wider text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 rounded-xl transition-all duration-300 shadow-md shadow-red-950/40 active:scale-95 cursor-pointer">
                                <i class="fas fa-share-square mr-2"></i> Kirim Permintaan Bantuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection