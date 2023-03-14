@extends('layouts.app-auth')
@section('page-title', 'Reset Password')
@section('content')
<div class="col-md-8 col-lg-6 col-xl-5">
    <div class="card overflow-hidden">
        <div class="bg-login text-center">
            <div class="bg-login-overlay"></div>
            <div class="position-relative">
                <h5 class="text-white font-size-20">Reset Password</h5>
                <p class="text-white-50 mb-0">Re-Password with DepED DTS.</p>
                <a href="index.html" class="logo logo-admin mt-4">
                    <img src="/assets/images/logo-sm-dark.png" alt="" height="30">
                </a>
            </div>
        </div>
        <div class="card-body pt-5">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group mb-3">
                <label for="email">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email }}" required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="password">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="password-confirm">{{ __('Confirm Password') }}</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="row mb-0">
                <div class="col-12 text-end">
                    <button class="btn btn-primary w-md waves-effect waves-light"
                        type="submit">Reset</button>
                </div>
            </div>
        </form>
        </div>
    </div>
    @section('footer')
    <p>Remember It ? <a href="{{ route('login') }}" class="fw-medium text-primary"> Sign In
        here</a> </p>
    @endsection
</div>
@endsection



