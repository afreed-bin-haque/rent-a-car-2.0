@extends('layouts.app_layouts.frontend.frontend-master')
@section('content')
       <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9" style="padding-top: 10rem">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome to ride share system</h1>
                                    </div>
                                    @if (session('status'))
                                        <div class="mb-4 text-sm text-warning-600">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    @if(session('successfully registered'))
                                        <div class="alert alert-success" role="alert">
                                        {{ session('successfully registered') }}
                                        </div>
                                    @endif
                                    <x-jet-validation-errors class="text-center bg-danger text-light p-2" />
                                    <form class="user" method="POST" action="{{ route('login') }}">
                                         @csrf
                                        <div class="form-group pt-5">
                                            <input type="email" class="form-control form-control-user"
                                                id="email" aria-describedby="emailHelp"
                                                placeholder="Enter Email Address..." name="email" value="{{ old('email') }}" >
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="password" name="password" placeholder="Password">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <label for="remember_me" class="flex items-center">
                                                <x-jet-checkbox id="remember_me" name="remember" />
                                                <span class="ml-2 text-sm text-gray-600">Remember me</span>
                                            </label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                        <hr>
                                    </form>
                                    {{-- <div class="text-center">
                                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                                    </div> --}}
                                    <div class="text-center">
                                        <a class="small" href="#" id="trigger_register_model" name="trigger_register_model">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <script>
    $(document).ready(function() {
        $(document).on("click", "#trigger_register_model", function(e) {
            e.preventDefault();
            $('#register_model').modal('show');
        });

        $(document).on("click", "#close_register_model", function(e) {
            e.preventDefault();
            $('#register_model').modal('hide');
        });
    });
    </script>
@endsection
