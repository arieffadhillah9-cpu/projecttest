@extends('layout.app')

{{-- NOTE PENTING: Untuk AdminLTE bekerja dengan benar, Anda harus memastikan bahwa file CSS dan JS Bootstrap serta AdminLTE dimuat di file layout.app. --}}

@section('content')
{{-- Kontainer AdminLTE standard --}}
<section class="content py-5" style="background-color: #f4f6f9;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mx-auto" style="max-width: 900px;">

                {{-- Kembali ke Daftar Film --}}
                <div class="mb-4">
                    <a href="{{ route('homepage') }}" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Film
                    </a>
                </div>

                {{-- Header Film - Menggunakan Card AdminLTE --}}
                <div class="card card-danger card-outline">
                    <div class="card-body">
                        <h1 class="text-3xl font-weight-bold text-dark mb-1">{{ $film->judul }}</h1>
                        <p class="text-sm text-muted mb-0">Durasi: {{ $film->durasi_menit }} menit | Rating: <span class="badge bg-danger">{{ $film->rating }}</span></p>
                    </div>
                </div>

                <h2 class="text-xl font-weight-bold mt-4 mb-3 text-dark">Pilih Tanggal Tayang:</h2>

                <!-- Area Filter Tanggal -->
                <div class="d-flex flex-row overflow-auto pb-3 mb-4" id="date-filters">
                    @foreach ($availableDates as $date)
                        @php
                            // Menggunakan Carbon secara langsung untuk parsing dan formatting
                            $carbonDate = \Carbon\Carbon::parse($date)->locale('id');
                            $formattedDate = $carbonDate->isoFormat('dddd, D MMMM');
                            $isToday = $carbonDate->isToday();
                        @endphp
                        <button
                            data-tanggal="{{ $date }}"
                            class="date-button btn btn-sm rounded-pill shadow-sm mr-2 flex-shrink-0
                                {{ $loop->first ? 'btn-danger' : 'btn-outline-secondary' }}"
                            style="min-width: 150px;"
                        >
                            {{ $formattedDate }}
                            @if ($isToday)
                                <span class="ml-1 text-xs font-weight-bold d-none d-sm-inline">(Hari Ini)</span>
                            @endif
                        </button>
                    @endforeach
                </div>

                {{-- Area Jadwal yang Tersedia --}}
                <h2 class="text-xl font-weight-bold mt-4 mb-3 text-dark">Jadwal yang Tersedia:</h2>

                <div id="schedule-list" class="row">
                    {{-- Jadwal akan di-render di sini oleh JavaScript --}}
                </div>

                <div id="no-schedule-message" class="alert alert-warning hidden mt-4" role="alert" style="display: none;">
                    <p class="font-weight-bold">Informasi</p>
                    <p class="mb-0">Tidak ada jadwal tayang yang tersedia untuk tanggal yang dipilih.</p>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- Modal Pengganti Alert/Confirm (Disesuaikan dengan Bootstrap Modal) --}}
{{-- Catatan: Modal ini membutuhkan Bootstrap JS dan jQuery --}}
<div class="modal fade" id="alert-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modal-title">Pemberitahuan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="modal-message" class="text-sm text-gray-700"></p>
            </div>
            <div class="modal-footer">
                <button id="modal-close-button" onclick="closeModal()" class="btn btn-danger btn-block">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const dateButtons = document.querySelectorAll('.date-button');
    const scheduleList = document.getElementById('schedule-list');
    const noScheduleMessage = document.getElementById('no-schedule-message');

    // MENGGUNAKAN VARIABEL YANG ANDA KIRIM DARI CONTROLLER: $jadwal_tayang
    const allSchedulesData = @json($jadwal_tayang); 

    // Helper untuk menampilkan/menyembunyikan pesan
    function toggleNoScheduleMessage(show) {
        noScheduleMessage.style.display = show ? 'block' : 'none';
    }

    // --- Modal Functions (Pengganti Alert) ---
    // Pastikan jQuery dan Bootstrap JS dimuat di layout.app
    function showModal(title, message) {
        document.getElementById('modal-title').textContent = title;
        document.getElementById('modal-message').textContent = message;
        // Memastikan jQuery tersedia untuk memanggil modal
        if (typeof $ !== 'undefined' && $.fn.modal) {
             $('#alert-modal').modal('show'); 
        } else {
             console.error("jQuery atau Bootstrap JS Modal tidak dimuat.");
             // Fallback sederhana jika modal tidak berfungsi
             alert(title + ": " + message); 
        }
       
    }
    
    function closeModal() {
        if (typeof $ !== 'undefined' && $.fn.modal) {
             $('#alert-modal').modal('hide'); 
        }
    }
    window.closeModal = closeModal; 

    // --- Rendering Functions ---

    function formatRupiah(number) {
        if (number === null || number === undefined) return 'Rp 0';
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    }

    function renderSchedules(schedules) {
        scheduleList.innerHTML = ''; // Kosongkan daftar

        if (schedules.length === 0) {
            toggleNoScheduleMessage(true);
            return;
        }

        toggleNoScheduleMessage(false);

        // Grouping schedules by studio name
        const groupedByStudio = schedules.reduce((acc, schedule) => {
            // Pastikan properti studio dan nama ada, jika tidak, gunakan fallback 'Unknown Studio'
            const studioName = schedule.studio ? (schedule.studio.nama || 'Studio N/A') : 'Studio N/A';
            if (!acc[studioName]) {
                acc[studioName] = {
                    studioName: studioName,
                    schedules: []
                };
            }
            acc[studioName].schedules.push(schedule);
            return acc;
        }, {});
        
        // Render tiap kelompok Studio
        for (const studioName in groupedByStudio) {
            const studioData = groupedByStudio[studioName];
            
            const studioContainer = document.createElement('div');
            studioContainer.className = 'col-12 mb-4';

            let scheduleHtml = `
                <div class="card card-dark card-outline shadow">
                    <div class="card-header bg-secondary">
                        <h4 class="card-title text-white font-weight-bold">Studio: ${studioName}</h4>
                    </div>
                    <div class="card-body d-flex flex-wrap gap-3 py-3">
            `;
            
            // Loop melalui jadwal di studio ini
            studioData.schedules.forEach(schedule => {
                // **Tautan Pemesanan Tiket (Ke Selection Seat)**
                const selectSeatUrl = `/user/pemesanan/${schedule.id}/select-seat`;

                scheduleHtml += `
                    <div class="shadow-sm rounded bg-dark p-2 text-white text-center flex-shrink-0" style="width: 150px;">
                        <div class="text-xl font-weight-bold mb-1">${(schedule.jam_mulai || '00:00').substring(0, 5)}</div>
                        <div class="text-sm text-success font-weight-bold mb-2">${formatRupiah(schedule.harga)}</div>
                        <a href="${selectSeatUrl}" class="btn btn-danger btn-block btn-xs text-uppercase font-weight-bold">
                            Pesan Tiket
                        </a>
                    </div>
                `;
            });

            scheduleHtml += `
                    </div>
                </div>
            `;
            studioContainer.innerHTML = scheduleHtml;
            scheduleList.appendChild(studioContainer);
        }
    }


    // --- Event Handlers (Fungsionalitas Filter Tanggal) ---

    function handleDateSelection(selectedDate) {
        // Hapus kelas aktif dari semua tombol
        dateButtons.forEach(btn => {
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-outline-secondary');
        });

        // Tambahkan kelas aktif ke tombol yang dipilih
        const activeBtn = document.querySelector(`.date-button[data-tanggal="${selectedDate}"]`);
        if (activeBtn) {
            activeBtn.classList.remove('btn-outline-secondary');
            activeBtn.classList.add('btn-danger');
        }

        // Filter jadwal berdasarkan tanggal yang dipilih
        // PERBAIKAN KRITIS: Menggunakan 'tanggal' (nama kolom) BUKAN 'tanggal_tayang'
        const filteredSchedules = allSchedulesData.filter(schedule => schedule.tanggal === selectedDate);
        
        // Urutkan jadwal berdasarkan jam_mulai (asc)
        filteredSchedules.sort((a, b) => {
            const timeA = a.jam_mulai;
            const timeB = b.jam_mulai;
            if (timeA < timeB) return -1;
            if (timeA > timeB) return 1;
            return 0;
        });

        renderSchedules(filteredSchedules);
    }

    dateButtons.forEach(button => {
        button.addEventListener('click', function() {
            const selectedDate = this.dataset.tanggal;
            handleDateSelection(selectedDate);
        });
    });

    // --- Initial Load ---
    if (dateButtons.length > 0) {
        const initialDate = dateButtons[0].dataset.tanggal;
        handleDateSelection(initialDate);
    } else {
        toggleNoScheduleMessage(true);
        noScheduleMessage.innerHTML = '<p class="font-weight-bold">Informasi</p><p class="mb-0">Tidak ada tanggal tayang yang tersedia untuk film ini.</p>';
    }
});
</script>
@endsection