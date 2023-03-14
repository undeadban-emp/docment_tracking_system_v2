@extends('layouts.app-auth')
@section('page-title', 'Login')
@section('content')
<div class="col-md-8 col-lg-6 col-xl-5">
    <div class="card overflow-hidden">
        <div class="bg-login text-center">
            <div class="bg-login-overlay"></div>
            <div class="position-relative">
                <h5 class="text-white font-size-20">Welcome Back !</h5>
                <p class="text-white mb-0">Sign in to continue to Surigao del Sur DTS.</p>
                <p href="" class="logo logo-admin mt-3">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="" width="70">
                </p>
            </div>
        </div>
        <div class="card-body pt-5">
            <div class="p-2">
                @include('templates.errors')
                <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="email">Username</label>
                        <input type="text" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" id="username" name="username" placeholder="Enter username">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="userpassword">Password</label>
                        <input type="password" value="{{ old('password') }}" class="form-control @error('password') is-invalid @enderror" id="userpassword" name="password" placeholder="Enter password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                    </div>

                    {{-- <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div> --}}

                    <div class="mt-3">
                        <button class="btn btn-success w-100 waves-effect waves-light" type="submit">Log
                            In</button>
                    </div>

                    <div class="mt-4 text-center">
                        <p>Don't have an account ? <a href="{{ route('register') }}" class="fw-medium text-primary"> Signup now </a> </p>
                        {{-- <a href="{{ route('password.request') }}" class="text-muted"><i class="mdi mdi-lock me-1"></i> Forgot your password?</a> --}}
                    </div>
                </form>
            </div>

        </div>
    </div>
    {{-- @section('footer')

    @endsection --}}
</div>
@endsection
