@extends('layouts.app_layouts.error-layout.error-master')
@section('content')
  <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- 500 Error Text -->
                    <div class="text-center">
                        <div class="error mx-auto" data-text="500">500</div>
                        <p class="lead text-gray-800 mb-5">Internal Server Error</p>
                        <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
                        <a href="{{ route('/') }}">&larr; Back to Dashboard</a>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
@endsection
