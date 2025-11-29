
@extends('admin.layout.adminapp')

@section('title', 'Detail Pemesanan ' . $pemesanan->kode_pemesanan)

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Detail Pemesanan: {{ $pemesanan->kode_pemesanan }}</h1>

    <div class="bg-white shadow-lg rounded-lg p-6">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 border-b pb-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Informasi Umum</h2>
                <p class="text-sm text-gray-600"><strong>Kode Pemesanan:</strong> {{ $pemesanan->kode_pemesanan }}</p>
                <p class="text-sm text-gray-600"><strong>Pemesan:</strong> {{ $pemesanan->user->name ?? 'N/A' }} (ID: {{ $pemesanan->user_id }})</p>
                <p class="text-sm text-gray-600"><strong>Waktu Pemesanan:</strong> {{ $pemesanan->waktu_pemesanan ? $pemesanan->waktu_pemesanan->format('d F Y H:i:s') : 'N/A' }}</p>
                {{-- Asumsi kolom waktu_pembayaran ada di model Pemesanan --}}
                <p class="text-sm text-gray-600"><strong>Waktu Pembayaran:</strong> {{ $pemesanan->waktu_pembayaran ? $pemesanan->waktu_pembayaran->format('d F Y H:i:s') : 'Belum Dibayar' }}</p>
            </div>

            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Ringkasan Pembayaran</h2>
                <p class="text-sm text-gray-600"><strong>Jumlah Tiket:</strong> {{ $pemesanan->jumlah_tiket }}</p>
                <p class="text-sm text-gray-600"><strong>Total Harga:</strong> <span class="font-bold text-indigo-600 text-lg">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</span></p>
                <p class="text-sm text-gray-600">
                    <strong>Status:</strong> 
                    @php
                        $color = match($pemesanan->status) {
                            'paid' => 'bg-green-100 text-green-800',
                            'canceled' => 'bg-red-100 text-red-800',
                            'expired' => 'bg-gray-100 text-gray-600',
                            default => 'bg-yellow-100 text-yellow-800', // pending
                        };
                    @endphp
                    <span class="px-2 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $color }}">
                        {{ ucfirst($pemesanan->status) }}
                    </span>
                </p>
                <div class="mt-4">
                    {{-- Mengarahkan ke fungsi edit() di controller --}}
                    <a href="{{ route('admin.pemesanan.edit', $pemesanan) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        Ubah Status
                    </a>
                </div>
            </div>
        </div>

        <h2 class="text-xl font-semibold text-gray-800 mb-4">Detail Tayangan</h2>
        <div class="border p-4 rounded-lg bg-gray-50 mb-6">
            <p class="text-sm text-gray-700"><strong>Film:</strong> {{ $pemesanan->jadwal->film->title ?? 'N/A' }}</p>
            <p class="text-sm text-gray-700"><strong>Studio:</strong> {{ $pemesanan->jadwal->studio->nama_studio ?? 'N/A' }}</p>
            <p class="text-sm text-gray-700"><strong>Waktu Tayang:</strong> {{ $pemesanan->jadwal->waktu_tayang ? \Carbon\Carbon::parse($pemesanan->jadwal->waktu_tayang)->format('d F Y, H:i') : 'N/A' }}</p>
            <p class="text-sm text-gray-700"><strong>Harga Tiket per Kursi:</strong> Rp {{ number_format($pemesanan->total_harga / $pemesanan->jumlah_tiket, 0, ',', '.') }}</p>
        </div>

        <h2 class="text-xl font-semibold text-gray-800 mb-4">Detail Kursi</h2>
        <div class="mt-2">
            @if ($pemesanan->detailPemesanan->isNotEmpty())
                <div class="flex flex-wrap gap-3">
                    @foreach ($pemesanan->detailPemesanan as $detail)
                        <span class="bg-indigo-100 text-indigo-800 text-sm font-medium me-2 px-3 py-1 rounded-full border border-indigo-300">
                            Kursi: {{ $detail->nomor_kursi }}
                        </span>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Tidak ada detail kursi ditemukan.</p>
            @endif
        </div>

        <div class="mt-8 pt-4 border-t flex justify-end">
            <a href="{{ route('admin.pemesanan.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Kembali ke Daftar Pemesanan
            </a>
        </div>
    </div>
</div>
@endsection