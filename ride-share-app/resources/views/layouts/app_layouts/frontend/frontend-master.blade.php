<!DOCTYPE html>
<html lang="en">
@include('components.frontend.header')
<body class="bg-gradient-success">
 <div class="container">
    @yield('content')
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
<!-- Modal -->
<div class="modal fade" id="register_model" name="register_model" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Choose your preference</h5>
        <button type="button" class="btn btn-danger rounded-pill" id="close_register_model" name="close_register_model">X</button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-12 col-md-6">
                <a href="{{ route('register') }}">
                    <img src="https://img.freepik.com/free-vector/man-tracking-taxi-driver-cab-tablet-map-guy-using-device-car-location-with-gps-system-app-male-checking-navigation-smartphone-booking-taxi-cartography-display-flat-illustration_74855-20647.jpg?w=1380&t=st=1660573875~exp=1660574475~hmac=50ff04ebc2dc92c793a67fd39f29faea91264db10ff9082a0a86a0801e57ffd7"    class="img-fluid" alt="Register as passenger">
                    <h4 class="text-center">Register as passenger</h4>
                </a>
            </div>
            <div class="col-12 col-md-6">
                <a href="{{ route('register.rider') }}">
                    <img src="https://img.freepik.com/free-vector/city-driver-concept-illustration_114360-1209.jpg?w=1380&t=st=1660574166~exp=1660574766~hmac=a7b2ec2f6b231d643634aeade25ecd9ea8f1396b974f5e67344de127ea268a2f"  class="img-fluid" alt="Register as rider">
                    <h4 class="text-center">Register as rider</h4>
                </a>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="{{ asset('app_assets/js/sweetalert2.all.min.js') }}"></script>
<!-- End of Footer -->
<script>
    $(document).ready(function() {
        @if(session('success'))
        Swal.fire({
        icon: 'success',
        title: 'Successful &#11088;',
        text: '{{ session('success') }}',
        })
        @endif
        @if(session('error'))
        Swal.fire({
        icon: 'error',
        title: 'Sorry.... &#10071',
        text: '{{ session('error') }}',
        })
        @endif
});
</script>
</html>
