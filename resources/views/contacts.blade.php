@extends('layout.app')

@section('title', 'Hubungi Kami')

@section('content')
<style>
   
    .content-wrapper {
        background-color: #1a1a1a !important;
    }
    body {
        background-color: #1a1a1a !important;
    }

    .contact-section {
        background-color: transparent; 
        /* [FIX 1: JARAK DARI NAVBAR] Menambah padding atas agar tidak terlalu mepet Navbar */
        padding-top: 100px; 
        padding-bottom: 150px; 
        min-height: 100vh;
        color: #ffffff; 
        font-family: 'Inter', sans-serif;
    }
    
    /* [FIX 3: INPUT TEXT] Memastikan teks input selalu putih, terutama saat fokus */
    .form-control {
        background-color: #333333; /* Warna field saat tidak aktif */
        border: 1px solid #555555;
        color: #ffffff !important; /* Warna teks yang diketik (Putih) */
        border-radius: 8px;
        padding: 10px 15px;
        max-width: 100%; 
        width: 100%;
        box-sizing: border-box; 
    }
    .form-control:focus {
        background-color: #444444; /* Warna field saat aktif */
        border-color: #dc3545; /* Border saat aktif */
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.4);
        color: #ffffff !important; /* SANGAT PENTING: Memastikan teks tetap putih saat input aktif */
    }

    /* Styling Lainnya */
    .card-contact {
        /* Kotak hitam utama */
        background-color: #000000;
        border: 1px solid #333333; 
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(220, 53, 69, 0.15); 
    }
    .contact-section header h1 {
        color: #dc3545 !important;
    }
    .contact-section header p {
        color: #cccccc;
    }
    .info-list i {
        color: #dc3545;
        min-width: 25px;
    }
    .info-list a {
        color: #ffffff;
        transition: color 0.3s;
    }
    .info-list a:hover {
        color: #dc3545;
        text-decoration: none;
    }
    .form-group label {
        color: #cccccc;
        font-weight: 500;
        margin-bottom: 5px;
    }
    .border-right-custom {
        border-right: 1px solid #444444 !important;
    }
    @media (max-width: 767px) {
        .border-right-custom {
            border-right: none !important;
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid #444444 !important;
        }
    }
    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        transition: background-color 0.3s, transform 0.2s;
        border-radius: 8px;
        font-size: 1rem;
        padding: 12px;
    }
    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
        transform: translateY(-2px);
    }
</style>

<div class="container contact-section">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <header class="text-center mb-5">
                <h1 class="font-weight-bolder">PUSAT BANTUAN SEATLY CINEMA</h1>
                <p class="lead">Kami di sini untuk mendengarkan. Sampaikan pertanyaan, saran, atau masukan Anda di bawah ini.</p>
            </header>
            
            <div class="card card-contact">
                <div class="card-body p-md-5">
                    
                    <div class="row">
                        
                        <!-- Informasi Kontak & Ikon -->
                        <div class="col-md-5 border-right-custom pr-md-4">
                            <h4 class="text-danger mb-4"><i class="fas fa-headset mr-2"></i> Kunjungi Kami</h4>
                            <ul class="list-unstyled info-list">
                                <li class="mb-4 d-flex align-items-start">
                                    <i class="fas fa-map-marker-alt mr-3 mt-1 fa-lg"></i> 
                                    <div>
                                        <strong class="text-white">Alamat Kantor Pusat:</strong><br>
                                        Jl. Teater Megah No. 50, Jakarta Pusat
                                    </div>
                                </li>
                                <li class="mb-4 d-flex align-items-start">
                                    <i class="fas fa-envelope mr-3 mt-1 fa-lg"></i> 
                                    <div>
                                        <strong class="text-white">Email Layanan Pelanggan:</strong><br>
                                        <a href="mailto:seatlycinema@gmail.com">seatlycinema@gmail.com</a>
                                    </div>
                                </li>
                                <li class="mb-4 d-flex align-items-start">
                                    <i class="fas fa-phone-alt mr-3 mt-1 fa-lg"></i> 
                                    <div>
                                        <strong class="text-white">Telepon (Bebas Pulsa):</strong><br>
                                        0800-FILM-NOW (0800-3456-669)
                                    </div>
                                </li>
                                <li class="mb-4 d-flex align-items-start">
                                    <i class="fab fa-instagram mr-3 mt-1 fa-lg"></i> 
                                    <div>
                                        <strong class="text-white">Ikuti Kami di Instagram:</strong><br>
                                        <a href="https://www.instagram.com/seatlycinema" target="_blank">@seatlycinema</a>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Formulir Pesan -->
                        <div class="col-md-7 pl-md-5">
                            <h4 class="text-danger mb-4"><i class="fas fa-paper-plane mr-2"></i> Kirim Pesan Langsung</h4>
                            <form action="#" method="POST">
                                @csrf 
                                <div class="form-group">
                                    <label for="name">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="name" name="name" required placeholder="Cth: Budi Santoso">
                                </div>
                                <div class="form-group">
                                    <label for="email">Alamat Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required placeholder="Cth: budi@email.com">
                                </div>
                                <div class="form-group">
                                    <label for="subject">Subjek / Topik Bantuan</label>
                                    <input type="text" class="form-control" id="subject" name="subject" required placeholder="Cth: Permintaan Refund Tiket">
                                </div>
                                <div class="form-group">
                                    <label for="message">Pesan Anda</label>
                                    <textarea class="form-control" id="message" name="message" rows="5" required placeholder="Tuliskan pesan Anda secara rinci di sini..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger btn-block mt-5 font-weight-bold">
                                    <i class="fas fa-share-square mr-2"></i> KIRIM PERMINTAAN BANTUAN
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection