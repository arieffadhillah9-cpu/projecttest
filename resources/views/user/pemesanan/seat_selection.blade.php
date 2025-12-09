@extends('layout.app')

@section('content')

<section class="content py-5" style="background-color: #f4f6f9; min-height: 100vh;">
<div class="container-fluid">
<div class="row">
<div class="col-lg-8 col-md-10 mx-auto">

            {{-- Kembali ke Jadwal --}}
            <div class="mb-4">
                {{-- Menggunakan film_id dari $jadwal untuk kembali ke halaman pemilihan jadwal --}}
                {{-- Asumsi route untuk halaman jadwal film adalah 'film.schedule' --}}
                <a href="{{ route('film.schedule', ['filmId' => $jadwal->film_id]) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Pemilihan Jadwal
                </a>
            </div>

            <div class="card card-primary card-outline shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title font-weight-bold">Pilih Kursi Anda</h3>
                </div>
                <div class="card-body">
                    
                    {{-- Detail Jadwal --}}
                    {{-- Catatan: Pastikan relasi 'film' dan 'studio' di-load di Controller agar properti ini tersedia. --}}
                    <h1 class="text-2xl font-weight-bold text-dark mb-1">{{ $jadwal->film->judul ?? 'Film Tidak Ditemukan' }}</h1>
                    <p class="text-sm text-muted mb-3">
                        Studio & Waktu Tayang: 
                        <span class="font-weight-bold">{{ $jadwal->studio->nama ?? 'Studio N/A' }}</span> - 
                        {{ \Carbon\Carbon::parse($jadwal->tanggal)->isoFormat('dddd, D MMMM YYYY') }} 
                        <span class="font-weight-bold">{{ substr($jadwal->jam_mulai, 0, 5) }}</span>
                        | Harga Tiket: <span class="text-success font-weight-bold">Rp {{ number_format($jadwal->harga, 0, ',', '.') }}</span>
                    </p>

                    <div class="text-center mb-4">
                        <div class="bg-dark text-white p-2 rounded-lg shadow-sm mx-auto" style="width: 80%; max-width: 400px;">
                            Layar Bioskop
                        </div>
                    </div>

                    {{-- Form Pemilihan Kursi --}}
                    {{-- Ganti action URL ini ke route POST yang akan memproses pemesanan --}}
                    <form id="seat-selection-form" action="{{ route('user.pemesanan.process') }}" method="POST"> 
                        @csrf
                        <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                        {{-- Input ini akan diisi oleh JavaScript dengan daftar kursi yang dipilih --}}
                        <input type="hidden" id="selected_seats_input" name="seats" required>
                        
                        {{-- Peta Kursi (Contoh Sederhana) --}}
                        <div id="seat-map" class="d-flex flex-column align-items-center p-4 bg-light rounded-lg shadow-inner border">
                            @php
                                // Atur baris dan kolom sesuai studio Anda
                                $rows = range('A', 'G');
                                $cols = range(1, 10);
                                // Asumsi $kursi_terisi adalah array ID kursi yang sudah dipesan (misalnya: ['A1', 'C5'])
                                $reservedSeats = $kursi_terisi; 
                            @endphp

                            @foreach ($rows as $row)
                                <div class="seat-row d-flex justify-content-center mb-2" style="gap: 8px;">
                                    {{-- Label Baris --}}
                                    <div class="seat-label font-weight-bold text-dark text-sm mt-1" style="width: 30px; text-align: right;">{{ $row }}</div>
                                    
                                    @foreach ($cols as $col)
                                        @php
                                            $seatId = $row . $col;
                                            $isReserved = in_array($seatId, $reservedSeats);
                                            $seatClass = $isReserved ? 'seat-reserved bg-secondary' : 'seat-available bg-white border border-secondary text-dark';
                                        @endphp
                                        <button 
                                            type="button" 
                                            data-seat="{{ $seatId }}" 
                                            class="seat-button btn btn-sm rounded-sm p-0 {{ $seatClass }}" 
                                            style="width: 30px; height: 30px; line-height: 28px; font-size: 10px;"
                                            {{ $isReserved ? 'disabled' : '' }}
                                        >
                                            {{ $col }}
                                        </button>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        {{-- Keterangan dan Display Kursi Terpilih --}}
                        <div class="mt-4 text-center">
                            <span class="badge badge-success mr-3">Tersedia</span>
                            <span class="badge badge-secondary mr-3">Terisi</span>
                            <span class="badge badge-danger">Dipilih</span>
                        </div>

                        <div class="mt-4 text-center">
                            <h5 class="font-weight-bold text-dark">Kursi Dipilih:</h5>
                            <p id="selected-seats-display" class="text-xl font-weight-bold text-danger">Belum ada kursi yang dipilih</p>
                        </div>

                        {{-- Tombol Lanjut --}}
                        <div class="mt-4">
                            <button type="submit" id="submit-button" class="btn btn-danger btn-block btn-lg" disabled>
                                Lanjut ke Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>


</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
const seatMap = document.getElementById('seat-map');
const selectedSeatsInput = document.getElementById('selected_seats_input');
const selectedSeatsDisplay = document.getElementById('selected-seats-display');
const submitButton = document.getElementById('submit-button');
let selectedSeats = [];

    function updateDisplay() {
        selectedSeats.sort();
        selectedSeatsDisplay.textContent = selectedSeats.length &gt; 0 ? selectedSeats.join(&#39;, &#39;) : &#39;Belum ada kursi yang dipilih&#39;;
        selectedSeatsInput.value = selectedSeats.join(&#39;,&#39;);
        
        // Tombol diaktifkan hanya jika ada kursi yang dipilih
        submitButton.disabled = selectedSeats.length === 0; 
    }

    seatMap.addEventListener(&#39;click&#39;, function(e) {
        const button = e.target.closest(&#39;.seat-button&#39;);
        if (!button || button.disabled) return;

        const seatId = button.dataset.seat;
        const index = selectedSeats.indexOf(seatId);

        if (index &gt; -1) {
            // Hapus kursi jika sudah dipilih (Deselect)
            selectedSeats.splice(index, 1);
            button.classList.remove(&#39;btn-danger&#39;);
            button.classList.add(&#39;bg-white&#39;, &#39;border-secondary&#39;, &#39;text-dark&#39;);
        } else {
            // Maksimum 5 kursi
            if (selectedSeats.length &gt;= 5) {
                // Menggunakan alert sederhana, Anda bisa menggantinya dengan modal Bootstrap jika ingin lebih rapi
                alert(&quot;Maksimal 5 kursi dapat dipilih dalam satu transaksi.&quot;);
                return;
            }
            
            // Pilih kursi
            selectedSeats.push(seatId);
            button.classList.remove(&#39;bg-white&#39;, &#39;border-secondary&#39;, &#39;text-dark&#39;);
            button.classList.add(&#39;btn-danger&#39;);
        }

        updateDisplay();
    });

    // Inisialisasi tampilan
    updateDisplay();
});


</script>

@endsection