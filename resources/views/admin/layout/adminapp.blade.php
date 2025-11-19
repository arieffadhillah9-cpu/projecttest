<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin - @yield('title')</title>

    @include('admin.layout.partials.adminstyle')
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        {{-- Sidebar (Menu Navigasi) --}}
        @include('admin.layout.partials.adminsidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                {{-- Topbar (Navbar atas) --}}
                @include('admin.layout.partials.adminnavbar')

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            {{-- Footer --}}
            @include('admin.layout.partials.adminfooter')

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

     <!-- ./wrapper -->
       <!-- jQuery -->
       @include('admin.layout.partials.adminscript')
      <!-- REQUIRED SCRIPTS -->

</body>
</html>




