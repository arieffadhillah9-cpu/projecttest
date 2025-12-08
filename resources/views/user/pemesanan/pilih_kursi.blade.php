@extends('layout.app')

@section('content')
<div class="container mx-auto p-6">

    <!-- Judul -->
    <h2 class="text-2xl font-bold text-white mb-4">
        Pilih Kursi – {{ $jadwal->film->judul }}
    </h2>

    <!-- Info Jadwal -->
    <div class="bg-gray-800 text-white p-4 rounded-lg mb-6">
        <p><b>Tanggal:</b> {{ $jadwal->tanggal->format('d M Y') }}</p>
        <p><b>Jam Mulai:</b> {{ substr($jadwal->jam_mulai, 0, 5) }} WIB</p>
        <p><b>Studio:</b> {{ $jadwal->studio->nama }}</p>
        <p><b>Harga Tiket:</b> Rp {{ number_format($jadwal->harga, 0, ',', '.') }}</p>
    </div>

    <!-- SCREEN -->
    <div class="w-full bg-gray-300 text-center text-gray-700 font-bold py-2 rounded mb-6">
        LAYAR
    </div>

    <!-- LEGEND -->
    <div class="flex items-center gap-4 text-white mb-4">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 bg-green-500 rounded"></div>
            <span>Available</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 bg-red-500 rounded"></div>
            <span>Booked</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 bg-blue-500 rounded"></div>
            <span>Selected</span>
        </div>
    </div>

    <!-- GRID KURSI -->
    <div id="seat-grid" class="grid gap-3 bg-gray-900 p-6 rounded-lg text-white shadow-lg"
         style="grid-template-columns: repeat(12, minmax(0, 1fr));">

        @foreach ($jadwalSeats as $seat)
            @php
                $nomor = $seat->nomor_kursi;
                $isBooked = in_array($nomor, $kursiTerisi);
            @endphp

            <div
                class="seat text-center py-2 rounded cursor-pointer text-sm font-bold transition
                {{ $isBooked ? 'bg-red-500 cursor-not-allowed opacity-60' : 'bg-green-500 hover:bg-green-400' }}"
                data-seat="{{ $nomor }}"
                data-booked="{{ $isBooked }}"
            >
                {{ $nomor }}
            </div>
        @endforeach

    </div>

    <!-- FORM PEMESANAN -->
    <form action="{{ route('user.pemesanan.process', $jadwal->id) }}" method="POST" class="mt-6">
        @csrf

        <!-- Hidden input untuk seat yang di-pilih -->
        <input type="hidden" id="selected-seats" name="kursi" value="">

        <button type="submit"
            class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg mt-4 text-lg">
            Lanjutkan Pembayaran
        </button>
    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const seatDivs = document.querySelectorAll('.seat');
    const selectedSeatsInput = document.getElementById('selected-seats');
    let selectedSeats = [];

    seatDivs.forEach(seat => {
        seat.addEventListener('click', () => {
            const seatNumber = seat.dataset.seat;
            const isBooked = seat.dataset.booked === "1";

            if (isBooked) return; // kursi booked ga bisa dipilih

            // toggle
            if (selectedSeats.includes(seatNumber)) {
                selectedSeats = selectedSeats.filter(s => s !== seatNumber);
                seat.classList.remove('bg-blue-500');
                seat.classList.add('bg-green-500');
            } else {
                selectedSeats.push(seatNumber);
                seat.classList.remove('bg-green-500');
                seat.classList.add('bg-blue-500');
            }

            selectedSeatsInput.value = selectedSeats.join(',');
        });
    });
});
</script>

@endsection
