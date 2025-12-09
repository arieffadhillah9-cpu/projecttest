@extends('layout.app')

<style>
.content {
    position: relative;
    z-index: 10;
    overflow: visible !important;
}
</style>

@section('content')
<section class="content py-5" style="background-color: #f4f6f9; min-height: 100vh;">
<div class="container-fluid">
<div class="row">
<div class="col-lg-8 col-md-10 mx-auto">

    <div class="mb-4">
        <a href="{{ route('homepage') }}" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Film
        </a>
    </div>

    <div class="card card-danger card-outline shadow-sm">
        <div class="card-body">
            <h1 class="text-3xl font-weight-bold text-dark mb-1">{{ $film->judul }}</h1>
            <p class="text-sm text-muted mb-0">
                Durasi: {{ $film->durasi_menit }} menit |
                Rating: <span class="badge bg-danger">{{ $film->rating }}</span>
            </p>
        </div>
    </div>

    <h2 class="text-xl font-weight-bold mt-4 mb-3 text-dark">1. Pilih Tanggal Tayang:</h2>

    <div id="date-filters-container" class="d-flex flex-row overflow-auto pb-3 mb-4 bg-white p-3 rounded shadow-sm">
        @forelse ($availableDates as $date)
            @php
                \Carbon\Carbon::setLocale('id');
                $carbonDate = \Carbon\Carbon::parse($date);
            @endphp
            <button
                type="button"
                data-tanggal="{{ $date }}"
                class="date-button btn btn-sm rounded-lg shadow-sm mr-2 flex-shrink-0
                {{ $loop->first ? 'btn-danger' : 'btn-outline-secondary' }}"
                style="min-width: 150px; height: 60px; line-height: 1.2;"
            >
                <span class="d-block font-weight-bold">{{ $carbonDate->isoFormat('dddd') }}</span>
                <span class="d-block text-sm">{{ $carbonDate->isoFormat('D MMMM') }}</span>

                @if ($carbonDate->isToday())
                    <span class="text-xs font-weight-bold">(Hari Ini)</span>
                @endif
            </button>
        @empty
            <div class="alert alert-info w-100 mb-0">Tidak ada tanggal tayang yang tersedia.</div>
        @endforelse
    </div>

    <h2 class="text-xl font-weight-bold mt-4 mb-3 text-dark">2. Jadwal yang Tersedia:</h2>

    <div id="schedule-list" class="row" style="min-height: 150px; overflow: visible !important;"></div>

    <div id="no-schedule-message" class="alert alert-warning mt-4 d-none">
        <p class="font-weight-bold">Informasi</p>
        <p class="mb-0">Tidak ada jadwal tayang yang tersedia untuk tanggal yang dipilih.</p>
    </div>

</div>
</div>
</div>
</section>

{{-- MODAL --}}
<div class="modal fade" id="alert-modal" tabindex="-1">
<div class="modal-dialog modal-sm">
<div class="modal-content">
<div class="modal-header bg-danger text-white">
    <h5 class="modal-title">Pemberitahuan</h5>
    <button type="button" class="close text-white" data-dismiss="modal" onclick="closeModal()">
        <span>&times;</span>
    </button>
</div>
<div class="modal-body">
    <p id="modal-message" class="text-sm text-gray-700"></p>
</div>
<div class="modal-footer">
    <button onclick="closeModal()" class="btn btn-danger btn-block">OK</button>
</div>
</div>
</div>
</div>
@endsection


@section('scripts')
<script>
// ===============================
// DATA BRIDGE
// ===============================
const allSchedulesGrouped = @json($allSchedules ?? []);
const SELECT_SEAT_BASE = "{{ route('user.pemesanan.select_seat', ['jadwalId' => '__ID__']) }}";

// ===============================
function getSelectSeatUrl(id) {
    return SELECT_SEAT_BASE.replace("__ID__", id);
}

function formatRupiah(value) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0
    }).format(Number(value) || 0);
}

function closeModal() {
    $("#alert-modal").modal("hide");
}

// ===============================
$(document).ready(function () {
    const dateButtons = document.querySelectorAll(".date-button");
    const scheduleList = $("#schedule-list");
    const noScheduleMessage = $("#no-schedule-message");

    if (!dateButtons.length) {
        noScheduleMessage.removeClass("d-none");
        return;
    }

    // ===========================
    function toggleNoSchedule(show) {
        noScheduleMessage.toggleClass("d-none", !show);
    }

    // ===========================
    function groupByStudio(list) {
        return list.reduce((acc, item) => {
            const studioName = item.studio_nama ?? "Studio";
            (acc[studioName] ??= []).push(item);
            return acc;
        }, {});
    }

    // ===========================
    function render(list) {
        scheduleList.empty();

        if (!list.length) {
            toggleNoSchedule(true);
            return;
        }

        toggleNoSchedule(false);

        const grouped = groupByStudio(list);

        Object.entries(grouped).forEach(([studio, schedules]) => {
            schedules.sort((a, b) =>
                (a.jam_mulai ?? "").localeCompare(b.jam_mulai ?? "")
            );

            let html = `
                <div class="col-12 mb-4">
                    <div class="card card-dark card-outline shadow-sm">
                        <div class="card-header bg-dark">
                            <h4 class="card-title text-white font-weight-bold">Studio: ${studio}</h4>
                        </div>
                        <div class="card-body d-flex flex-wrap" style="gap: 1rem;">
            `;

            schedules.forEach(s => {
                html += `
                    <div class="shadow-md rounded-lg bg-gray-100 p-3 text-center border border-dark"
                         style="width: 150px;">
                        <div class="text-xl font-weight-bold mb-1">
                            ${(s.jam_mulai ?? "").substring(0,5)}
                        </div>
                        <div class="text-sm text-success font-weight-bold mb-2">
                            ${formatRupiah(s.harga ?? 0)}
                        </div>
                        <a href="${getSelectSeatUrl(s.id)}"
                           class="btn btn-danger btn-block btn-xs text-uppercase font-weight-bold">
                           Pesan Tiket
                        </a>
                    </div>
                `;
            });

            html += `</div></div></div>`;
            scheduleList.append(html);
        });
    }

    // ===========================
    function applyDate(date, btn) {
        $(".date-button")
            .removeClass("btn-danger")
            .addClass("btn-outline-secondary");

        $(btn).removeClass("btn-outline-secondary").addClass("btn-danger");

        const schedules = allSchedulesGrouped[date] || [];
        render(schedules);
    }

    // ===========================
    $(document).on("click", ".date-button", function () {
        applyDate($(this).data("tanggal"), this);
    });

    // initial load
    const firstDate = dateButtons[0].dataset.tanggal;
    applyDate(firstDate, dateButtons[0]);
});
</script>
@endsection
