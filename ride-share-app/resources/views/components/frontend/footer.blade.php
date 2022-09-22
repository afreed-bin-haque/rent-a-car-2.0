  <!-- Footer -->
<footer class="sticky-footer bg-white">
        <div class="container my-auto">
              <div class="copyright text-center my-auto">
                <span>{{ config('appConfig.app.name') }} &copy; {{ date('Y') }}</span>
              </div>
         </div>
</footer>
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
