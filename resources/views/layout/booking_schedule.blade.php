@extends('layout.app')

<style>
/* ... (Bagian Style Anda) ... */
.date-button.btn-danger {
border-color: #dc3545;
background-color: #dc3545;
color: white;
transition: all 0.2s ease;
box-shadow: 0 4px 6px rgba(0,0,0,.1);
}
.date-button.btn-outline-secondary {
border-color: #e9ecef;
color: #6c757d;
background-color: #ffffff;
transition: all 0.2s ease;
}
.date-button.btn-outline-secondary:hover {
background-color: #f8f9fa;
color: #495057;
}
#date-filters-container {
flex-wrap: nowrap;
overflow-x: auto;
}
</style>

@section('content')

<section class="content py-5" style="background-color: #f4f6f9; min-height: 100vh;">
<div class="container-fluid">
<div class="row">
<div class="col-lg-8 col-md-10 mx-auto">

    {{-- Kembali ke Daftar Film --}}
    <div class="mb-4">
        <a href="{{ route('homepage') }}" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Film
        </a>
    </div>

    {{-- Header Film --}}
    <div class="card card-danger card-outline shadow-lg mb-4">
        <div class="card-body">
            <h1 class="text-3xl font-weight-bold text-dark mb-1">{{ $film->judul }}</h1>
            <p class="text-sm text-muted mb-0">Durasi: {{ $film->durasi_menit }} menit | Rating: <span class="badge bg-danger">{{ $film->rating }}</span></p>
        </div>
    </div>

    {{-- Pilih Tanggal Tayang --}}
    <h2 class="text-xl font-weight-bold mt-4 mb-3 text-dark">1. Pilih Tanggal Tayang:</h2>

    <!-- Area Filter Tanggal (Horizontal Scroll) -->
    <div id="date-filters-container" class="d-flex flex-row pb-3 mb-4 bg-white p-3 rounded shadow">
        @forelse ($availableDates as $date)
            @php
                \Carbon\Carbon::setLocale('id');
                $carbonDate = \Carbon\Carbon::parse($date);
                $formattedDay = $carbonDate->isoFormat('dddd');
                $formattedDate = $carbonDate->isoFormat('D MMMM');
                $isToday = $carbonDate->isToday();
            @endphp
            <button
                type="button"
                data-tanggal="{{ $date }}"
                class="date-button btn btn-sm rounded-lg shadow-sm mr-2 flex-shrink-0
                    {{ $loop->first ? 'btn-danger' : 'btn-outline-secondary' }}"
                style="min-width: 150px; height: 60px; line-height: 1.2;"
            >
                <span class="d-block font-weight-bold">{{ $formattedDay }}</span>
                <span class="d-block text-sm">{{ $formattedDate }}</span>
                @if ($isToday)
                    <span class="ml-1 text-xs font-weight-bold d-inline">(Hari Ini)</span>
                @endif
            </button>
        @empty
            <div class="alert alert-info w-100 mb-0">Tidak ada tanggal tayang yang tersedia.</div>
        @endforelse
    </div>

    {{-- Area Jadwal yang Tersedia --}}
    <h2 class="text-xl font-weight-bold mt-4 mb-3 text-dark">2. Jadwal yang Tersedia:</h2>

    <div id="schedule-list" class="row" style="min-height: 150px; overflow: visible !important;">
        <!-- Jadwal akan di-render di sini oleh JavaScript -->
    </div>

    <div id="no-schedule-message" class="alert alert-warning mt-4 d-none" role="alert">
        <p class="font-weight-bold">Informasi</p>
        <p class="mb-0">Tidak ada jadwal tayang yang tersedia untuk tanggal yang dipilih.</p>
    </div>

</div>


</div>
</div>
</section>

{{-- MODAL PENGGANTI ALERT/CONFIRM --}}

<div class="modal fade" id="alert-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
<div class="modal-dialog modal-sm" role="document">
<div class="modal-content">
<div class="modal-header bg-danger text-white">
<h5 class="modal-title" id="modal-title">Pemberitahuan</h5>
<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
<span aria-hidden="true">X</span>
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

@endsection

@section('scripts')

<script>
// =========================================================
// DEBUG INIT
// =========================================================
console.log("DEBUG S0: Script dimulai...");

// =========================================================
// DATA BRIDGE PHP → JS
// =========================================================
const allSchedulesGrouped = @json($allSchedules);
console.log("DEBUG S1: allSchedulesGrouped:", allSchedulesGrouped);

// Base URL
const BASE_URL = "{!! url('user/pemesanan') !!}";
const SUFFIX_URL = "/select-seat";

// =========================================================
// HELPER FUNCTIONS
// =========================================================

function getSelectSeatUrl(scheduleId) {
    return `${BASE_URL}/${scheduleId}${SUFFIX_URL}`;
}

function formatRupiah(number) {
    const value = Number(number) || 0;
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value);
}

function closeModal() {
    if (typeof $ !== 'undefined' && $('#alert-modal').length) {
        $('#alert-modal').modal('hide');
    } else {
        console.warn("Modal cannot be hidden (jQuery / modal not found).");
    }
}

function groupSchedulesByStudio(schedules) {
    return schedules.reduce((acc, schedule) => {
        const studioName =
            schedule.studio_nama ||
            (schedule.studio ? schedule.studio.nama : 'Studio N/A');

        if (!acc[studioName]) {
            acc[studioName] = { studioName, schedules: [] };
        }

        acc[studioName].schedules.push(schedule);
        return acc;
    }, {});
}

// =========================================================
// RENDERING FUNCTION
// =========================================================

function renderSchedules(schedules) {
    console.log("DEBUG R1: renderSchedules() | jumlah:", schedules.length);

    const scheduleList = document.getElementById('schedule-list');
    const noScheduleMessage = document.getElementById('no-schedule-message');

    $(scheduleList).empty();

    if (schedules.length === 0) {
        $(noScheduleMessage).removeClass('d-none').show();
        return;
    }

    $(noScheduleMessage).addClass('d-none').hide();

    const groupedByStudio = groupSchedulesByStudio(schedules);

    for (const studioName in groupedByStudio) {
        const studioData = groupedByStudio[studioName];
        const studioContainer = document.createElement('div');
        studioContainer.className = 'col-12 mb-4';

        let scheduleHtml = `
            <div class="card card-dark card-outline shadow">
                <div class="card-header bg-dark">
                    <h4 class="card-title text-white font-weight-bold">Studio: ${studioName}</h4>
                </div>
                <div class="card-body d-flex flex-wrap" style="gap: 1rem;">
        `;

        // Sorting jadwal berdasarkan jam mulai
        studioData.schedules.sort((a, b) =>
            (a.jam_mulai || '00:00').localeCompare(b.jam_mulai || '00:00')
        );

        studioData.schedules.forEach(schedule => {
            const scheduleId = schedule.id || null;
            if (!scheduleId) {
                console.error("ERROR: schedule.id hilang:", schedule);
                return;
            }

            const selectSeatUrl = getSelectSeatUrl(scheduleId);
            console.log(`[DEBUG URL]: Schedule#${scheduleId} → ${selectSeatUrl}`);

            scheduleHtml += `
                <div class="shadow-md rounded-lg bg-white p-3 text-center flex-shrink-0 border border-dark hover:shadow-lg transition-shadow duration-300"
                     style="width: 150px;">
                    <div class="text-xl font-weight-bold mb-1 text-dark">
                        ${(schedule.jam_mulai || '00:00').substring(0, 5)}
                    </div>
                    <div class="text-sm text-success font-weight-bold mb-2">
                        ${formatRupiah(schedule.harga || 0)}
                    </div>
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

    console.log("DEBUG R4: Rendering jadwal selesai.");
}

// =========================================================
// EVENT HANDLER UTAMA
// =========================================================

function handleDateSelection(selectedDate, targetElement) {
    console.log(`DEBUG D1: handleDateSelection() | tanggal: ${selectedDate}`);

    $('.date-button')
        .removeClass('btn-danger')
        .addClass('btn-outline-secondary');

    if (targetElement) {
        $(targetElement)
            .removeClass('btn-outline-secondary')
            .addClass('btn-danger');
    } else {
        const activeBtn = $(`.date-button[data-tanggal="${selectedDate}"]`)[0];
        if (activeBtn) {
            $(activeBtn).removeClass('btn-outline-secondary').addClass('btn-danger');
        }
    }

    const filtered = allSchedulesGrouped[selectedDate] || [];
    console.log("DEBUG D2: Filter result:", filtered.length);

    renderSchedules(filtered);
}

// =========================================================
// INITIALIZER
// =========================================================

document.addEventListener('DOMContentLoaded', () => {
    if (typeof jQuery === 'undefined') {
        console.error("FATAL: jQuery tidak dimuat. Script berhenti.");
        return;
    }

    console.log("DEBUG A: DOM Ready | jQuery OK");

    const dateButtons = document.querySelectorAll('.date-button');

    // Event delegation
    $(document).on('click', '#date-filters-container .date-button', function (e) {
        e.preventDefault();
        const selectedDate = $(this).data('tanggal');
        console.log(`DEBUG Z: Klik tanggal → ${selectedDate}`);
        handleDateSelection(selectedDate, this);
    });

    // Initial load
    if (dateButtons.length > 0) {
        console.log("DEBUG F: Initial load pakai tanggal pertama...");
        const initialDate = dateButtons[0].dataset.tanggal;
        handleDateSelection(initialDate, dateButtons[0]);
    } else {
        console.warn("DEBUG W: Tidak ada tombol tanggal. Menampilkan pesan kosong.");
        $('#no-schedule-message').removeClass('d-none').show();
    }
});

</script>

@endsection
