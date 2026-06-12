@extends('layout.app')

@section('title', 'Pilih Kursi - ' . $jadwal->film->judul)

@section('content')
<div class="min-h-screen bg-black py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-12">
            <a href="{{ route('film.schedule', ['filmId' => $jadwal->film->id]) }}" class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-zinc-400 hover:text-white transition-colors duration-200 mb-6 group">
                <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i> Kembali ke Jadwal
            </a>
            
            <h1 class="text-3xl font-extrabold tracking-tight text-white">
                Pilih Kursi: <span class="bg-gradient-to-r from-red-600 to-rose-500 bg-clip-text text-transparent">{{ $jadwal->film->judul }}</span>
            </h1>
            <p class="text-sm text-zinc-400 mt-2 font-light">
                Studio: <span class="text-zinc-200 font-medium">{{ $jadwal->studio->nama }}</span> &bull; 
                Waktu: <span class="text-zinc-200 font-medium">{{ \Carbon\Carbon::parse($jadwal->tanggal_tayang)->format('d M Y') }} ({{ $jadwal->jam_mulai }})</span>
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-8 p-4 bg-red-950/20 border border-red-900/40 rounded-2xl text-sm text-red-400">
                <h6 class="font-bold mb-2">Pemesanan Gagal:</h6>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.pemesanan.process') }}" method="POST" id="seat-selection-form" class="space-y-12">
            @csrf
            <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

            <!-- Error Message Placeholder from JS -->
            <div id="seat-error-message-container" class="transition-all duration-300"></div>

            <!-- Seating Layout Card -->
            <div class="bg-zinc-950/40 border border-zinc-900/60 rounded-3xl p-8 backdrop-blur-sm relative overflow-hidden">
                <!-- Curved Screen -->
                <div class="relative w-full max-w-lg mx-auto mb-16 text-center">
                    <div class="h-[6px] w-full bg-gradient-to-r from-red-800 via-rose-500 to-red-800 rounded-full shadow-[0_4px_20px_rgba(244,63,94,0.6)]"></div>
                    <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-zinc-500 mt-3 block">Layar Bioskop</span>
                </div>

                <!-- Seat Map Grid -->
                <div class="flex flex-col items-center justify-center overflow-x-auto py-4">
                    <div class="min-w-[480px] space-y-4">
                        @php
                            $total_kursi = $jadwal->studio->kapasitas;
                            $baris = ['A', 'B', 'C', 'D', 'E'];
                            $kursi_per_baris = $total_kursi / count($baris);
                            $kursi_terisi_list = $kursi_terisi;
                        @endphp
                        
                        @foreach ($baris as $kode_baris)
                            <div class="flex items-center justify-center gap-2">
                                <!-- Row label left -->
                                <span class="w-8 text-center text-xs font-bold text-zinc-600 tracking-wider mr-2">{{ $kode_baris }}</span>

                                @for ($i = 1; $i <= $kursi_per_baris; $i++)
                                    @php
                                        $kode_kursi = $kode_baris . $i;
                                        $is_booked = in_array($kode_kursi, $kursi_terisi_list);
                                    @endphp

                                    <label class="relative block select-none group">
                                        <input type="checkbox" name="kursi_dipilih[]" value="{{ $kode_kursi }}" class="peer hidden" {{ $is_booked ? 'disabled' : '' }}>
                                        
                                        @if($is_booked)
                                            <!-- Booked seat -->
                                            <div class="w-8 h-8 rounded-lg bg-zinc-900/80 border border-zinc-950 flex items-center justify-center text-[11px] font-bold text-zinc-700 cursor-not-allowed opacity-30">
                                                {{ $i }}
                                            </div>
                                        @else
                                            <!-- Available seat with Peer Selector for Checked State -->
                                            <div class="w-8 h-8 rounded-lg bg-red-950/20 border border-red-900/30 flex items-center justify-center text-[11px] font-bold text-red-400 cursor-pointer transition-all duration-300 hover:scale-110 active:scale-95 hover:border-red-500/50 hover:bg-red-600 hover:text-white peer-checked:bg-gradient-to-r peer-checked:from-emerald-500 peer-checked:to-green-400 peer-checked:border-emerald-400 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-emerald-500/30">
                                                {{ $i }}
                                            </div>
                                        @endif
                                    </label>
                                @endfor

                                <!-- Row label right -->
                                <span class="w-8 text-center text-xs font-bold text-zinc-600 tracking-wider ml-2">{{ $kode_baris }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Legend -->
                <div class="flex items-center justify-center gap-6 mt-12 pt-8 border-t border-zinc-900/50 text-xs">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-red-950/20 border border-red-900/30"></div>
                        <span class="text-zinc-400 font-light">Tersedia</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-zinc-900/80 border border-zinc-950 opacity-30"></div>
                        <span class="text-zinc-400 font-light">Terisi</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-gradient-to-r from-emerald-500 to-green-400 border border-emerald-400"></div>
                        <span class="text-zinc-400 font-light">Terpilih</span>
                    </div>
                </div>
            </div>

            <!-- CTA Actions -->
            <div class="flex justify-end pt-4">
                <button type="button" id="confirm-button" class="w-full sm:w-auto text-center px-8 py-4 text-sm font-bold uppercase tracking-wider text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 rounded-full transition-all duration-300 shadow-lg shadow-red-950/40 active:scale-95 cursor-pointer">
                    Konfirmasi Pesanan & Bayar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('seat-selection-form');
        const confirmButton = document.getElementById('confirm-button');
        const errorContainer = document.getElementById('seat-error-message-container');
        
        confirmButton.addEventListener('click', function (e) {
            e.preventDefault();

            const selectedSeats = form.querySelectorAll('input[name="kursi_dipilih[]"]:checked');
            
            if (selectedSeats.length === 0) {
                errorContainer.innerHTML = `
                    <div class="mb-6 p-4 bg-red-950/20 border border-red-900/40 rounded-2xl text-sm text-red-400 flex items-center gap-3">
                        <i class="fas fa-exclamation-circle text-base"></i>
                        <span><strong>Perhatian!</strong> Anda harus memilih setidaknya satu kursi sebelum melanjutkan.</span>
                    </div>
                `;
                
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return false;
            } else {
                errorContainer.innerHTML = '';
                form.submit(); 
            }
        });
        
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