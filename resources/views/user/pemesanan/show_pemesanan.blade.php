@extends('layout.app') 

@section('title', 'Detail Pembayaran')

@section('content')
<div class="min-h-screen bg-black py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('user.history') }}" class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-zinc-400 hover:text-white transition-colors duration-200 mb-4 group">
                    <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i> Riwayat Pesanan
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white flex items-center gap-3">
                    <i class="fas fa-receipt text-rose-500"></i> Detail Transaksi <span class="text-zinc-500 text-lg sm:text-xl font-medium">#{{ $pemesanan->kode_pemesanan }}</span>
                </h1>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-950/20 border border-emerald-900/40 rounded-2xl text-sm text-emerald-400 flex items-center gap-3">
                <i class="fas fa-check-circle text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-950/20 border border-red-900/40 rounded-2xl text-sm text-red-400 flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-base"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
            
            <!-- Left Details -->
            <div class="md:col-span-7 space-y-8">
                
                <!-- Status Box -->
                <div class="bg-zinc-950/40 border border-zinc-900 rounded-3xl p-6 backdrop-blur-sm">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-3">Status Pembayaran</h3>
                    
                    @if ($pemesanan->status === 'menunggu_pembayaran')
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 text-amber-500 border border-amber-500/20">
                                Menunggu Pembayaran
                            </span>
                            <div class="text-sm">
                                Batas Waktu: 
                                <span id="countdown-timer" data-expires="{{ $waktuKadaluwarsa->timestamp }}" class="font-mono bg-red-600/15 border border-red-500/25 text-red-400 font-bold px-2 py-0.5 rounded">
                                    {{ $waktuKadaluwarsa->format('H:i:s') }}
                                </span>
                            </div>
                        </div>
                    @elseif ($pemesanan->status === 'paid')
                        <div class="space-y-1">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <i class="fas fa-check-circle"></i> Sudah Dibayar
                            </span>
                            <p class="text-xs text-zinc-500 pt-2">Waktu Pembayaran: {{ \Carbon\Carbon::parse($pemesanan->waktu_pembayaran)->format('H:i, d M Y') }}</p>
                        </div>
                    @elseif ($pemesanan->status === 'expired')
                        <div class="space-y-1">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-zinc-900 text-zinc-500 border border-zinc-800">
                                <i class="fas fa-times-circle"></i> Kadaluwarsa
                            </span>
                            <p class="text-xs text-zinc-500 pt-2">Batas waktu pembayaran telah berakhir.</p>
                        </div>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-zinc-800 text-zinc-300 border border-zinc-700">
                            {{ strtoupper($pemesanan->status) }}
                        </span>
                    @endif
                </div>

                <!-- Film & Studio Details -->
                <div class="bg-zinc-950/40 border border-zinc-900 rounded-3xl p-6 backdrop-blur-sm space-y-6">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Detail Tiket</h3>
                    
                    <div class="space-y-4">
                        <div class="border-b border-zinc-900/60 pb-4">
                            <span class="text-xs text-zinc-500 block">Judul Film</span>
                            <strong class="text-base text-white tracking-tight font-semibold mt-0.5 block">{{ $pemesanan->jadwal->film->judul }}</strong>
                        </div>
                        <div class="border-b border-zinc-900/60 pb-4">
                            <span class="text-xs text-zinc-500 block">Studio & Jadwal</span>
                            <span class="text-sm text-zinc-200 font-medium mt-0.5 block">
                                Studio {{ $pemesanan->jadwal->studio->nama }} &bull; {{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal)->format('d M Y') }} ({{ $pemesanan->jadwal->jam_mulai }})
                            </span>
                        </div>
                        
                        @php
                            $daftar_kursi = $pemesanan->detailPemesanan->pluck('nomor_kursi')->implode(', ');
                        @endphp
                        <div>
                            <span class="text-xs text-zinc-500 block">Nomor Kursi ({{ $pemesanan->jumlah_tiket }} Tiket)</span>
                            <strong class="text-base text-rose-500 font-bold tracking-wider mt-0.5 block">{{ $daftar_kursi }}</strong>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column (Checkout/Invoice) -->
            <div class="md:col-span-5 space-y-6">
                
                <!-- Bill Card -->
                <div class="relative overflow-hidden bg-gradient-to-b from-zinc-900/50 to-zinc-950/50 border border-zinc-900 rounded-3xl p-6 shadow-xl shadow-red-950/5">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-red-600/5 rounded-full blur-3xl pointer-events-none"></div>

                    <h3 class="text-xs font-semibold uppercase tracking-wider text-zinc-500 text-center mb-4">Total Tagihan</h3>
                    <div class="text-center py-4 border-y border-zinc-900/60">
                        <span class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-red-500 via-rose-400 to-rose-500 bg-clip-text text-transparent">
                            Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="mt-6 space-y-4">
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Instruksi Pembayaran</h4>
                        <div class="p-4 bg-zinc-950/60 border border-zinc-900 rounded-2xl flex flex-col gap-1">
                            <span class="text-[10px] uppercase tracking-wider text-zinc-500">Transfer Bank Manual / Gateway</span>
                            <span class="text-sm font-bold text-zinc-100 mt-1">Bank XYZ: 123-456-7890</span>
                            <span class="text-xs text-zinc-400">a.n. Cinema Ticketing</span>
                        </div>
                    </div>

                    @if ($pemesanan->status === 'menunggu_pembayaran')
                        <div class="mt-8">
                            <form id="payment-form" action="{{ route('user.pemesanan.generatePayment', $pemesanan->kode_pemesanan) }}" method="POST">
                                @csrf
                                <button type="submit" id="pay-button" class="w-full text-center py-4 text-xs font-bold uppercase tracking-wider text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 rounded-full transition-all duration-300 shadow-md shadow-red-950/40 active:scale-95 cursor-pointer">
                                    <i class="fas fa-credit-card mr-2"></i> Bayar Sekarang
                                </button> 
                            </form>
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if ($pemesanan->status === 'menunggu_pembayaran')
            const countdownElement = document.getElementById('countdown-timer');
            const payButton = document.getElementById('pay-button');
            const expiresAt = countdownElement.getAttribute('data-expires') * 1000;

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = expiresAt - now;

                if (distance < 0) {
                    countdownElement.innerHTML = "EXPIRED";
                    if(payButton) {
                        payButton.setAttribute('disabled', 'disabled');
                        payButton.classList.remove('bg-gradient-to-r', 'from-red-600', 'to-rose-600', 'hover:from-red-500', 'hover:to-rose-500', 'cursor-pointer');
                        payButton.classList.add('bg-zinc-900', 'border', 'border-zinc-800', 'text-zinc-600', 'cursor-not-allowed');
                        payButton.innerHTML = "<i class='fas fa-lock mr-2'></i> WAKTU HABIS";
                    }
                    return; 
                }

                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                countdownElement.innerHTML = minutes + "m " + seconds + "s ";
                setTimeout(updateCountdown, 1000);
            }

            updateCountdown();
        @endif
    });
</script>
@endpush