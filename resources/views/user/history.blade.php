@extends('layout.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="min-h-screen bg-black py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('dashboard.user') }}" class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-zinc-400 hover:text-white transition-colors duration-200 mb-4 group">
                    <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i> Kembali ke Dashboard
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white flex items-center gap-3">
                    <i class="fas fa-history text-rose-500"></i> Riwayat Pesanan
                </h1>
            </div>
        </div>

        @if($pemesanans->isEmpty())
            <div class="text-center py-16 border border-dashed border-zinc-800 rounded-2xl bg-zinc-950/20">
                <i class="fas fa-info-circle text-4xl text-zinc-700 mb-4"></i>
                <h3 class="text-lg font-semibold text-zinc-400">Belum ada transaksi</h3>
                <p class="text-sm text-zinc-600 mt-1">Anda belum melakukan pemesanan tiket apa pun saat ini.</p>
            </div>
        @else
            <!-- Modern Card Wrapper -->
            <div class="bg-zinc-950/40 border border-zinc-900 rounded-3xl overflow-hidden backdrop-blur-sm shadow-xl shadow-red-950/5">
                <div class="p-6 border-b border-zinc-900/60 flex items-center justify-between">
                    <h3 class="text-sm font-semibold tracking-tight text-zinc-300">Transaksi Terkini</h3>
                    <span class="text-xs text-zinc-500 font-light">Menampilkan {{ $pemesanans->count() }} item</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-zinc-900 bg-zinc-950/50">
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-zinc-500">ID Pesanan</th>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-zinc-500">Film & Studio</th>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-zinc-500">Jadwal</th>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-zinc-500">Total Harga</th>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-zinc-500">Status</th>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-zinc-500 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-900/40 text-sm">
                            @foreach ($pemesanans as $pemesanan)
                                <tr class="hover:bg-zinc-900/20 transition-colors duration-150">
                                    <td class="px-6 py-4 font-mono text-xs text-zinc-400 font-medium">
                                        {{ $pemesanan->kode_pemesanan }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-zinc-200 block tracking-tight">{{ $pemesanan->jadwal->film->judul ?? 'N/A' }}</span>
                                        <span class="text-xs text-rose-500 font-medium">Studio {{ $pemesanan->jadwal->studio->nama ?? 'N/A' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-zinc-400 font-light">
                                        {{ \Carbon\Carbon::parse($pemesanan->jadwal->waktu_tayang)->format('d/m/y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-zinc-100">
                                        Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusBadge = [
                                                'paid' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                                'menunggu_pembayaran' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                                'expired' => 'bg-zinc-900 text-zinc-500 border-zinc-800',
                                                'canceled' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                            ][$pemesanan->status] ?? 'bg-zinc-800 text-zinc-300 border-zinc-700';
                                            
                                            $statusText = [
                                                'paid' => 'Lunas',
                                                'menunggu_pembayaran' => 'Pending',
                                                'expired' => 'Expired',
                                                'canceled' => 'Batal',
                                            ][$pemesanan->status] ?? strtoupper($pemesanan->status);
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider border {{ $statusBadge }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('user.pemesanan.show', $pemesanan->kode_pemesanan) }}" 
                                           class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold uppercase tracking-wider text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 rounded-full transition-all duration-300 active:scale-95 shadow-md shadow-red-950/20">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-zinc-900 bg-zinc-950/20 flex items-center justify-end">
                    <div class="text-zinc-500 text-xs">
                        {{ $pemesanans->links() }}
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection