 <!-- jQuery -->
      <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}">
      <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}">
      <!-- AdminLTE App -->
      <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}">
      
      <script>
    console.log("adminscript.blade.php berhasil dimuat!");
</script>

<!-- Menggunakan CDN untuk memastikan jQuery dan Bootstrap termuat -->


<!-- Script AdminLTE lokal Anda (Pastikan ini yang terakhir) -->
<!-- <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script> -->

<!-- KODE AUTO-CLOSE ALERT -->
<!-- **KODE AUTO-CLOSE ALERT (DIPASTIKAN BERJALAN SETELAH BOOTSTRAP)** -->
<script>
    // Memastikan DOM siap
    $(document).ready(function() {
        // Selector Alert yang paling umum di Bootstrap adalah '.alert'
        var $alert = $('.alert');

        if ($alert.length) {
            // Cek apakah alert memiliki kelas yang bisa ditutup
            if ($alert.hasClass('alert-dismissible')) {
                console.log("Alert ditemukan. Mengaktifkan Auto-Close...");

                // 1. Uji Fungsionalitas Tombol Silang (Manual)
                // Tombol silang harusnya berfungsi karena Bootstrap JS sudah dimuat.

                // 2. Auto-Close (Otomatis)
                setTimeout(function() {
                    // Coba trigger penutupan Alert
                    $alert.fadeTo(500, 0).slideUp(500, function() {
                        // Hilangkan elemen dari DOM setelah animasi selesai
                        $(this).remove(); 
                    });
                }, 4000); // 4 detik
            }
        }
    });
</script>
<!-- Akhir KODE AUTO-CLOSE -->