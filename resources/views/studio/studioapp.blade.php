<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Seatly</title>

  @include('studio.partials.adminstyle')
  
<body class="hold-transition sidebar-collapse bg-dark">
  <div class="wrapper">
    <!-- Navbar -->
            @include('studio.partials.adminnavbar')
    <!-- /Navbar -->
     <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
           
        </aside>

    <!-- Main Sidebar Container -->
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      @yield('content')
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
      <footer class="main-footer bg-dark text-white border-top-0" style="background-color: #000000 !important; padding: 20px 0;">
        <!-- To the right -->
       @include('studio.partials.adminfooter')
      </footer>
  </div>
      <!-- ./wrapper -->
       <!-- jQuery -->
       @include('studio.partials.adminscript')
      <!-- REQUIRED SCRIPTS -->
     
</body>
</html>
