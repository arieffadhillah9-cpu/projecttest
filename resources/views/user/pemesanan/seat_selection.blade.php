@extends('layout.app')

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8">
    <div class="bg-white p-6 rounded-xl shadow-lg max-w-4xl mx-auto">
        
        <!-- Tombol Kembali -->
        <a href="{{ url()->previous() }}" class="text-blue-600 hover:text-blue-800 flex items-center mb-6">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Film
        </a>

        <!-- Detail Film -->
        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $film->judul }}</h1>
        <p class="text-sm text-gray-500 italic mb-6">{{ $film->durasi_menit }} menit | Rating: {{ $film->rating }}</p>

        <!-- Area Pemilihan Tanggal -->
        <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Pilih Tanggal Tayang:</h2>
        <div class="flex flex-wrap gap-3 mb-6" id="date-filters">
            @foreach($availableDates as $date)
                @php
                    // Pastikan Carbon digunakan dengan benar
                    \Carbon\Carbon::setLocale('id');
                    $carbonDate = \Carbon\Carbon::parse($date);
                    $formattedDate = $carbonDate->isoFormat('dddd, D MMMM');
                    $isToday = $carbonDate->isToday();
                @endphp
                <button
                    data-tanggal="{{ $date }}"
                    class="date-button px-4 py-2 rounded-full text-sm font-medium border transition-all duration-300 whitespace-nowrap
                        @if ($loop->first) bg-indigo-600 text-white border-indigo-600 shadow-md @else bg-gray-100 text-gray-700 border border-gray-300 hover:bg-indigo-500 hover:text-white hover:border-indigo-500 @endif
                    "
                >
                    {{ $formattedDate }}
                    @if ($isToday)
                        <span class="ml-1 text-xs font-bold hidden sm:inline-block">(Hari Ini)</span>
                    @endif
                </button>
            @endforeach
        </div>

        <!-- Area Jadwal Tayang Tersedia -->
        <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Jadwal yang Tersedia:</h2>
        
        <!-- Container untuk menampilkan tombol-tombol jadwal tayang -->
        <div id="schedule-list" class="flex flex-wrap gap-4">
            <p id="no-schedule-message" class="text-gray-500 italic hidden">Tidak ada jadwal tayang tersedia untuk tanggal yang dipilih.</p>
        </div>

        <!-- Data Jadwal Tersembunyi (Disimpan dari Controller) -->
        <!-- Pastikan controller Anda mengirim variabel $allSchedules yang berisi semua jadwal tayang -->
        <input type="hidden" id="all-schedules-data" value="{{ json_encode($allSchedules) }}">
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dateButtons = document.querySelectorAll('.date-button');
        const scheduleList = document.getElementById('schedule-list');
        const noScheduleMessage = document.getElementById('no-schedule-message');
        
        // Ambil data jadwal dari hidden input dan parse
        const allSchedulesData = JSON.parse(document.getElementById('all-schedules-data').value);

        // Fungsi untuk format rupiah (hanya contoh, sesuaikan jika perlu)
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        }

        // Fungsi utama untuk merender jadwal
        function renderSchedules(selectedDate) {
            scheduleList.innerHTML = ''; // Kosongkan daftar jadwal sebelumnya
            noScheduleMessage.classList.add('hidden');

            const schedulesForDate = allSchedulesData.filter(schedule => schedule.tanggal === selectedDate);
            
            if (schedulesForDate.length === 0) {
                noScheduleMessage.classList.remove('hidden');
                return;
            }

            // Grouping schedules by studio and then rendering time buttons
            const groupedByStudio = schedulesForDate.reduce((acc, schedule) => {
                const studioName = schedule.studio.nama;
                if (!acc[studioName]) {
                    acc[studioName] = [];
                }
                acc[studioName].push(schedule);
                return acc;
            }, {});

            for (const studioName in groupedByStudio) {
                const studioContainer = document.createElement('div');
                studioContainer.className = 'w-full mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50';
                
                // Judul Studio
                const studioTitle = document.createElement('h3');
                studioTitle.className = 'text-lg font-bold text-gray-700 mb-3';
                studioTitle.textContent = studioName;
                studioContainer.appendChild(studioTitle);

                // Container Jam Tayang
                const timeButtonContainer = document.createElement('div');
                timeButtonContainer.className = 'flex flex-wrap gap-3';

                groupedByStudio[studioName].forEach(schedule => {
                    // --- Bagian PENTING: Tombol yang mengarah ke Seat Selection ---
                    const link = document.createElement('a');
                    // Rute Seat Selection: /user/pemesanan/{jadwal_id}/select-seat
                    // Sesuaikan dengan rute Laravel Anda jika berbeda
                    link.href = `/user/pemesanan/${schedule.id}/select-seat`; 
                    
                    const timeButton = document.createElement('button');
                    timeButton.className = 'px-4 py-2 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700 transition duration-150 shadow-md';
                    timeButton.innerHTML = `
                        ${schedule.jam_mulai.substring(0, 5)} 
                        <span class="ml-1 text-xs font-normal">(${formatRupiah(schedule.harga)})</span>
                    `;
                    
                    link.appendChild(timeButton);
                    timeButtonContainer.appendChild(link);
                });

                studioContainer.appendChild(timeButtonContainer);
                scheduleList.appendChild(studioContainer);
            }
        }

        // Event listener untuk tombol tanggal
        dateButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Hapus styling aktif dari semua tombol
                dateButtons.forEach(btn => {
                    btn.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-md');
                    btn.classList.add('bg-gray-100', 'text-gray-700', 'border', 'border-gray-300', 'hover:bg-indigo-500', 'hover:text-white', 'hover:border-indigo-500');
                });

                // Tambahkan styling aktif pada tombol yang diklik
                this.classList.remove('bg-gray-100', 'text-gray-700', 'border', 'border-gray-300', 'hover:bg-indigo-500', 'hover:text-white', 'hover:border-indigo-500');
                this.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-md');

                // Render jadwal untuk tanggal yang dipilih
                const selectedDate = this.getAttribute('data-tanggal');
                renderSchedules(selectedDate);
            });
        });

        // Panggil render untuk tanggal pertama saat halaman dimuat
        if (dateButtons.length > 0) {
            dateButtons[0].click();
        } else {
             // Tampilkan pesan jika tidak ada tanggal tersedia sama sekali
             scheduleList.innerHTML = '';
             noScheduleMessage.textContent = 'Tidak ada tanggal tayang yang tersedia untuk film ini.';
             noScheduleMessage.classList.remove('hidden');
             noScheduleMessage.classList.add('block', 'p-4', 'bg-red-100', 'text-red-700', 'rounded-lg');
        }
    });
</script>
@endsection