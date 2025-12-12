@extends('layout.dashboard')

@section('title', 'Pilih Kursi')

@section('content')


<style>
/* ===== GLOBAL WRAPPER ===== */
.page-seat {
    background: linear-gradient(180deg, #000000 0%, #0b0b0b 100%);
    padding: 90px 0;
    min-height: calc(100vh - 120px);
    display: flex;
    justify-content: center;
}

/* ===== CARD ===== */
.card {
    background: rgba(15, 15, 15, 0.95) !important;
    border: 1px solid #1c1c1c !important;
    color: #fff;
    border-radius: 18px;
    backdrop-filter: blur(4px);
    box-shadow: 0 0 25px rgba(255, 0, 0, 0.15);
    transition: 0.3s;
}
.card:hover {
    box-shadow: 0 0 40px rgba(255, 0, 0, 0.25);
}

.card-header {
    background: transparent !important;
    border-bottom: 1px solid #222;
}
.card .text-dark { color: #fff !important; }

/* ===== SCREEN BOX ===== */
.screen-box {
    background: linear-gradient(90deg, #ff1c1c, #cc0000);
    border: 2px solid #ff3b3b;
    padding: 12px 45px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.1rem;
    letter-spacing: 1px;
    color: #fff;
    box-shadow: 0px 0px 20px rgba(255, 0, 0, 0.4);
    text-transform: uppercase;
}

/* ===== SEAT STYLE ===== */
.seat-label input { display: none; }

.seat-label .badge {
    padding: 12px 14px;
    min-width: 40px;
    cursor: pointer;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.25s ease;
    user-select: none;
    font-weight: 600;
}

/* Available */
.seat-available {
    background: #d60000;
    border: 1px solid #ff2d2d;
    color: #fff;
    box-shadow: 0 0 10px rgba(255, 0, 0, 0.2);
}
.seat-available:hover {
    background: #ff3b3b;
    transform: translateY(-3px);
    box-shadow: 0 0 15px rgba(255, 0, 0, 0.35);
}

/* Booked */
.badge-danger {
    background: #2b2b2b;
    color: #888;
    border: 1px solid #444;
    cursor: not-allowed;
    opacity: 0.6;
}

/* Selected */
.seat-label input:checked + span {
    background: #00c853 !important;
    border-color: #5efc82 !important;
    box-shadow: 0 0 18px rgba(0, 255, 120, 0.5);
    transform: translateY(-3px);
}

/* Row letters */
.row-kursi .text-dark {
    color: #fff !important;
    font-weight: 700;
    font-size: 1.1rem;
    text-shadow: 0 0 10px rgba(255, 0, 0, 0.4);
}

/* Legend */
.legend-badge {
    padding: 8px 13px;
    border-radius: 6px;
    font-size: 0.85rem;
    margin-right: 8px;
}
.d-flex.justify-content-end.mt-4 {
    position: relative;
    z-index: 99999;
}
/* Buttons */
.btn-danger {
    background: linear-gradient(90deg, #d60000, #ff1b1b) !important;
    border: none;
    padding: 12px 30px;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    box-shadow: 0 0 18px rgba(255, 0, 0, 0.3);
    transition: 0.2s;
}
.btn-danger:hover {
    background: linear-gradient(90deg, #ff2424, #ff4d4d) !important;
    transform: translateY(-3px);
    box-shadow: 0 0 25px rgba(255, 0, 0, 0.45);
}
</style>

<div class="page-seat">
<div class="card shadow-lg mb-4 w-100" style="max-width: 950px;">
    <div class="card-header py-3">

     <h5 class="m-0 font-weight-bold text-dark">Pilih Kursi - {{ $jadwal->film->judul }} ({{ $jadwal->jam_mulai }})</h5>
        <p class="text-dark mb-0">Studio: {{ $jadwal->studio->nama }}</p>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger mt-3 mx-3">
        <h6 class="font-weight-bold">Opsi Pemesanan Gagal:</h6>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
     <form action="{{ route('user.pemesanan.process') }}" method="POST" id="seat-selection-form"> 
        <div class="card-body">
            @csrf
            <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

            <div id="seat-error-message-container" class="mx-3"></div>

            <div class="text-center mb-4">
                <div class="screen-box">LAYAR BIOSKOP</div>
            </div>
               <div class="seat-map d-flex justify-content-center flex-column align-items-center">
                            @php
                             $total_kursi = $jadwal->studio->kapasitas;
                $baris = ['A', 'B', 'C', 'D', 'E'];
                $kursi_per_baris = $total_kursi / count($baris);
                $kursi_terisi_list = $kursi_terisi;
            @endphp
            @foreach ($baris as $kode_baris)
                <div class="row-kursi mb-3 d-flex justify-content-center align-items-center">
                    <span class="mr-3 text-dark">{{ $kode_baris }}</span>

                    @for ($i = 1; $i <= $kursi_per_baris; $i++)
                        @php
                            $kode_kursi = $kode_baris . $i;
                            $is_booked = in_array($kode_kursi, $kursi_terisi_list);
                        @endphp

                        <label class="seat-label mx-1">
                            <input type="checkbox" name="kursi_dipilih[]" value="{{ $kode_kursi }}" {{ $is_booked ? 'disabled' : '' }}>
                            <span class="badge {{ $is_booked ? 'badge-danger' : 'seat-available' }}">{{ $i }}</span>
                        </label>
                    @endfor

                    <span class="ml-3 text-dark">{{ $kode_baris }}</span>
                </div>
            @endforeach

            <div class="mt-3">
                <span class="legend-badge" style="background:#d60000; color:white;">Tersedia</span>
                <span class="legend-badge" style="background:#333; color:#ccc;">Terisi</span>
                <span class="legend-badge" style="background:#00c853; color:white;">Terpilih</span>
            </div>
             </div>

            <div class="d-flex justify-content-end mt-4" style="position: relative; z-index: 99999;">
    <button type="submit" class="btn btn-danger btn-lg">Konfirmasi Pesanan & Bayar</button>
</div>

        </div>
    </form>
</div>
</div>

@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('seat-selection-form');
        const confirmButton = document.getElementById('confirm-button'); // Ambil tombol berdasarkan ID baru
        const errorContainer = document.getElementById('seat-error-message-container');
        
        // Ganti event listener dari 'submit' menjadi 'click' pada tombol
        confirmButton.addEventListener('click', function (e) {
            e.preventDefault(); // Mencegah submit default (karena type="button")

            const selectedSeats = form.querySelectorAll('input[name="kursi_dipilih[]"]:checked');
            
            // Cek apakah ada kursi yang dipilih
            if (selectedSeats.length === 0) {
                // Tampilkan pesan error
                errorContainer.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        <strong>Perhatian!</strong> Anda harus memilih setidaknya satu kursi sebelum melanjutkan.
                    </div>
                `;
                
                window.scrollTo({ top: 0, behavior: 'smooth' });

                return false;
            } else {
                // Jika kursi sudah dipilih, kosongkan error
                errorContainer.innerHTML = '';
                
                // *** INI ADALAH PERUBAHAN PENTING ***
                // PAKSA SUBMIT FORM DARI JAVASCRIPT
                form.submit(); 
            }
        });
        
        // ... (sisa event listener untuk checkbox tetap sama) ...
        const seatCheckboxes = form.querySelectorAll('input[name="kursi_dipilih[]"]');
        seatCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const checkedSeats = form.querySelectorAll('input[name="kursi_dipilih[]"]:checked');
                if (checkedSeats.length > 0) {
                     errorContainer.innerHTML = '';
                }
            });
        });
    });
</script>
@endpush