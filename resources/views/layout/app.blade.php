<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Starter</title>

  @include('layout.partials.style')
  
</head>
<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
            @include('layout.partials.navbar')
    <!-- /Navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
       @include('layout.partials.sidebar')
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content pt-3">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <div class="callout callout-success">
                <p><i class="fas fa-book"></i> Informasi umum</p>

              </div>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>150</h3>
                  <p>New Orders</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>53<sup style="font-size: 20px">%</sup></h3>
                  <p>Bounce Rate</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3>44</h3>
                  <p>User Registrations</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->

            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3>65</h3>
                  <p>Unique Visitors</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->


          </div>
          <!-- ./row -->
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content -->

      <div class="col-md-12">
        <div class="small-box bg-success">
          <div class="inner">
            <h4><b>Rp. 38.850.000</b></h4>
            <p>Total Pemasukan</p>
          </div>
          <div class="icon">
            <i class="fas fa-chart-bar"></i>
          </div>
        </div>
      </div>


      <div class="content pt-3">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <div class="callout callout-success">
                <p><i class="fas fa-book"></i> Rekapitulasi verifikasi hasil pengujian</p>
              </div>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">

            <!-- Kolom kiri: tabel -->
            <div class="col-md-9">
              <div class="card">
                <div class="card-header bg-primary text-white">
                  <h3 class="card-title mb-0">Status Verifikasi Pengujian</h3>
                </div>
                <div class="card-body p-0">
                  <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                      <tr>
                        <th>Nama Pengujian</th>
                        <th>Sudah Diverifikasi</th>
                        <th>Belum Diverifikasi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>FTIR + Pengujian</td>
                        <td>1</td>
                        <td>0</td>
                      </tr>
                      <tr>
                        <td>SEM + Image</td>
                        <td>0</td>
                        <td>1</td>
                      </tr>
                      <tr>
                        <td>XRD + Pengujian</td>
                        <td>23</td>
                        <td>3</td>
                      </tr>
                      <tr>
                        <td>XRF + Pengujian</td>
                        <td>12</td>
                        <td>3</td>
                      </tr>
                      <tr class="fw-bold">
                        <td>Jumlah Data</td>
                        <td>41</td>
                        <td>7</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="card-footer text-muted small">
                  Pembaharuan data di tanggal 2025-10-10 pukul 13:53 WIB
                </div>
              </div>
            </div>

            <!-- Kolom kanan: info cards -->
            <div class="col-md-3">

              <div class="card bg-success text-white mb-3 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                  <i class="fas fa-check fa-2x opacity-75"></i>
                  <div>
                    <h6 class="mb-1 fw-bold">Sudah Diverifikasi</h6>
                    <h3 class="fw-bold mb-0">41</h3>
                  </div>
                </div>
              </div>

              <div class="card bg-danger text-white shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                  <i class="fas fa-times fa-2x opacity-75"></i>
                  <div>
                    <h6 class="mb-1 fw-bold">Belum Diverifikasi</h6>
                    <h3 class="fw-bold mb-0">7</h3>
                  </div>
                </div>
              </div>

            </div>

          </div>
        </div>
      </div>

    </div>
      <!-- /.content-wrapper -->

      <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
          <h5>Title</h5>
          <p>Sidebar content</p>
        </div>
      </aside>
      <!-- /.control-sidebar -->
      <!-- Main Footer -->
      <footer class="main-footer">
        <!-- To the right -->
       @include('layout.partials.footer')
      </footer>
  </div>
      <!-- ./wrapper -->
       <!-- jQuery -->
       @include('layout.partials.script')
      <!-- REQUIRED SCRIPTS -->
     
</body>
</html>
