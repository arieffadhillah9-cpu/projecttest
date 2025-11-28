@forelse ($films as $film)
<div class="col-lg-2 col-md-3 col-sm-4 mb-4 film-item"> 
    <div class="card film-card border-0 text-white h-100 d-flex flex-column"
        style="background: #111; border-radius: 14px; overflow: hidden; cursor: pointer;">

        <div style="overflow: hidden; border-bottom: 1px solid rgba(255,255,255,0.05);">
            <img src="{{ asset('storage/' . $film->poster_path) }}"
                alt="{{ $film->judul }}"
                class="card-img-top"
                style="height: 280px; object-fit: cover; transition: .35s;">
        </div>

        <div class="card-body p-3 d-flex flex-column justify-content-between">
            
            <div>
                <h6 class="card-title font-weight-bold mb-1"
                    style="line-height: 1.3; min-height: 2.6em;">
                    {{ $film->judul }}
                </h6>

                <p class="text-muted mb-2" style="font-size: 12px;">
                    {{ $film->genre }} • {{ $film->durasi_menit }} min
                </p>
            </div>

            <div class="mt-auto">

                <!-- Tombol Pesan Ticket -->
                <a href="{{ route('film.schedule', $film->id) }}"
                   class="btn btn-danger btn-sm pesan-ticket w-100 mb-2"
                   style="border-radius: 20px; padding: 6px 14px;">
                    Pesan Ticket
                </a>

                <a href="#"
                   class="btn btn-outline-light btn-sm"
                   style="border-radius: 20px; padding: 6px 14px;">
                    Lihat Detail
                </a>

            </div>

        </div>
    </div>
</div>
@empty
<div class="col-12 text-center py-5">
    <h3 class="text-white">Tidak ada film yang tayang pada jadwal ini.</h3>
    <p class="text-muted">Coba pilih tanggal atau jam tayang lainnya.</p>
</div>
@endforelse