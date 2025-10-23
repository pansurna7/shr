{{-- <x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}

@extends('layouts.app')
@section('title','Edit')
@section('content')

    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
        <div class="col mx-auto">
            <div class="mb-4 text-center">
                <img src="{{asset('storage/logo/logo-login.png')}}" width="180" alt="" />

            </div>
            <div class="card">
                <div class="card-body">
                    <div class="border p-4 rounded">
                        <div class="text-center">
                            <h3 class="">Sign in</h3>
                            {{-- <p>Don't have an account yet? <a href="authentication-signup.html">Sign up here</a> --}}
                            </p>
                        </div>
                        <div class="d-grid">
                            <a class="btn my-4 shadow-sm btn-light" href="javascript:;">
                                <span class="d-flex justify-content-center align-items-center">
                                    <img class="me-2" src="assets/images/icons/search.svg" width="16" alt="Image Description">
                                    <span>Sign in with Google</span>
                                </span>
                            </a>
                            <a href="javascript:;" class="btn btn-light"><i class="bx bxl-facebook">
                                </i>Sign in with Facebook
                            </a>
                        </div>
                        <div class="login-separater text-center mb-4"> <span>OR SIGN IN WITH EMAIL</span>
                            <hr/>
                        </div>
                        <div class="form-body">
                            <form class="row g-3" method="POST" action="{{route('login')}}">
                                @csrf
                                <div class="col-12">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" value="{{old('email')}}">
                                    @error('email')
                                        <span class="text-warning">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="password" class="form-label">Enter Password</label>
                                    <div class="input-group" id="show_hide_password">
                                        <input type="password" class="form-control border-end-0" id="password" name="password" placeholder="Enter Password">
                                        <a href="javascript:;" class="input-group-text bg-transparent">
                                            <i class='bx bx-hide'></i>
                                        </a>
                                    </div>
                                        @error('password')
                                            <span class="text-warning">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" name="remember" checked>
                                        <label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
                                    </div>
                                </div>
                                <div class="col-md-6 text-end">	<a href="authentication-forgot-password.html">Forgot Password ?</a>
                                </div>
                                <div class="col-12">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary"><i class="bx bxs-lock-open"></i>Sign in</button>
                                    </div>
                                </div>
                                <span class="text-center">Or Back To</span>
                                <div class="col-12">
                                    <div class="d-grid">
                                        <a class="btn btn-light" href="{{route('home')}}">Home</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>

    </script>
@endpush

