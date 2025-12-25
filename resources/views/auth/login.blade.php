@extends('layout.master2')

@section('noauth')
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="page-content d-flex align-items-center justify-content-center">

            <div class="row w-100 mx-0 auth-page">
                <div class="col-md-8 col-xl-6 mx-auto">
                    <div class="card">
                        <div class="row">
                            {{-- <div class="col-md-4 pr-md-0">
                                <div class="auth-left-wrapper"
                                    style="background-image: url({{ asset('storage/images/login/login.jpg') }})">
                                </div>
                            </div> --}}
                            <div class="col-md-12 pl-md-0">
                                <div class="auth-form-wrapper px-4 py-5">
                                    <a href="https://hts.com.pk" target="_blank" class="noble-ui-logo d-block mb-2">Haris Technical Solutions <span
                                            style="font-size:20px;"> (Pvt.)
                                            Ltd.</span></a>
                                    <h5 class="text-muted font-weight-normal mb-4">Welcome back! Log in to your account.
                                    </h5>
                                    <form class="forms-sample">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email address</label>
                                            <div class="">
                                                <input id="email" type="email"
                                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                                    value="{{ old('email')??'demo@hts.com.pk' }}" required autocomplete="email" autofocus>

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Password</label>
                                            <div class="">
                                                <input id="password" type="password"
                                                    value="00000000"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" required autocomplete="current-password">

                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-check form-check-flat form-check-primary">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                Remember me
                                            </label>
                                        </div>
                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-primary mr-2 mb-2 mb-md-0">
                                                {{ __('Login') }}
                                            </button>
                                            @if (Route::has('password.request'))
                                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                                    <button type="button"
                                                        class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                                                        {{ __('Forgot Your Password?') }}
                                                    </button>
                                                </a>
                                            @endif
                                        </div>
                                        {{-- <div class="mt-3">
                                            <button type="button"
                                                class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                                                <i class="btn-icon-prepend" data-feather="facebook"></i>
                                                Login with facebook
                                            </button>

                                            <button type="button"
                                                class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                                                <i class="btn-icon-prepend fab fa-google"></i>
                                                Login with Gmail
                                            </button>
                                        </div> --}}
                                        {{-- {{ url('/register') }} --}}
                                        {{-- <a href="" class="d-block mt-3 text-muted">Not a user? Sign up</a> --}}

                                        <span class="text-danger">Click on login for demo</span>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>



@endsection
