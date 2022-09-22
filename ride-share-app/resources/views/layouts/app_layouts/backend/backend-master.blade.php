<!DOCTYPE html>
<html lang="en">
@include('components.frontend.header')
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
    @if($user_status === 'Admin' || $user_status === 'Rider')
    @include('components.backend.sidebar')
    @endif
 <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
@include('components.backend.topbar')
  <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">{{ $user_status }} Dashboard</h1>
                    </div>
<nav>
        <ol class="breadcrumb">
         <li class="breadcrumb-item"><a href="{{ route('/') }}" class="text-info" style="text-decoration: none;">Root</a></li>
          <li class="breadcrumb-item">Pages</li>
           <li class="breadcrumb-item"><span class="text-success">{{ str_replace( "/", " ",request()->path()) }}</span></li>
        </ol>
      </nav>
@yield('content')

@include('components.frontend.footer')
</div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{ route('logout') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('app_assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('app_assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('app_assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('app_assets/js/sb-admin-2.min.js') }}"></script>

</body>

</html>
