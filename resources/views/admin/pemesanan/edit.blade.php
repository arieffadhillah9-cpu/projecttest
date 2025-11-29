@extends('admin.layout.adminapp')
@section('title', 'Edit Status Pemesanan')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit Status Pemesanan ({{ $pemesanan->kode_pemesanan }})</h1>

    <div class="bg-white shadow-lg rounded-lg p-6 max-w-lg mx-auto">
        <h2 class="text-xl font-semibold mb-4 border-b pb-2">Informasi Pemesanan</h2>
        <p class="text-gray-600 mb-2">
            Film: <span class="font-semibold">{{ $pemesanan->jadwal->film->title ?? 'N/A' }}</span>
        </p>
        <p class="text-gray-600 mb-2">
            Tayang: <span class="font-semibold">{{ \Carbon\Carbon::parse($pemesanan->jadwal->waktu_tayang ?? now())->format('d F Y, H:i') }} di {{ $pemesanan->jadwal->studio->nama_studio ?? 'N/A' }}</span>
        </p>
        <p class="text-gray-600 mb-4">
            Pemesan: <span class="font-semibold">{{ $pemesanan->user->name ?? 'N/A' }}</span>
        </p>
        <p class="text-gray-600 mb-6">
            Total Harga: <span class="font-semibold text-lg text-indigo-600">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</span>
        </p>

        {{-- Menggunakan rute PUT spesifik untuk update status --}}
        <form action="{{ route('admin.pemesanan.update.status', $pemesanan) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Saat Ini:</label>
                @php
                    $color = match($pemesanan->status) {
                        'paid' => 'bg-green-100 text-green-800',
                        'canceled' => 'bg-red-100 text-red-800',
                        'expired' => 'bg-gray-100 text-gray-600',
                        default => 'bg-yellow-100 text-yellow-800', // pending
                    };
                @endphp
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $color }} mb-4">
                    {{ ucfirst($pemesanan->status) }}
                </span>
                
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Ubah Status Menjadi:</label>
                <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    @foreach(['pending', 'paid', 'expired', 'canceled'] as $status)
                        <option value="{{ $status }}" @selected($status === $pemesanan->status)>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('admin.pemesanan.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Batal
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Perbarui Status
                </button>
            </div>
        </form>
    </div>
</div>
@endsection