@extends('layouts.auth')

@section('title', 'Login - IndoJuni')

@section('styles')
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .btn-bd-primary {
            --bd-violet-bg: #712cf9;
            --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

            --bs-btn-font-weight: 600;
            --bs-btn-color: var(--bs-white);
            --bs-btn-bg: var(--bd-violet-bg);
            --bs-btn-border-color: var(--bd-violet-bg);
            --bs-btn-hover-color: var(--bs-white);
            --bs-btn-hover-bg: #6528e0;
            --bs-btn-hover-border-color: #6528e0;
            --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
            --bs-btn-active-color: var(--bs-btn-hover-color);
            --bs-btn-active-bg: #5a23c8;
            --bs-btn-active-border-color: #5a23c8;
        }

        .bd-mode-toggle {
            z-index: 1500;
        }
    </style>
@endsection

@section('content')
    <main class="d-grid form-signin w-100 h-100 align-items-center m-auto">
        <form id="loginForm" action={{ route('app.login.login') }} method="POST">
            @csrf
            <img class="mb-4" src="{{ asset('images/app/xyXVxK19116nI6TPT5KF.png') }}" alt="" height="57">
            <h1 class="h3 fw-normal mb-3">Please sign in</h1>

            <div class="form-floating">
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="floatingInput"
                    placeholder="name@example.com" name="email" value={{ old('email') }}>
                <label for="floatingInput">Email address</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password"
                    name="password" id="floatingPasswordIn">
                <label for="floatingPasswordIn">Password</label>
            </div>
            <div class="d-flex float-end pb-3">
                Doesn't have an account? <span><a href="{{ route('app.register.page') }}" class="ps-1">Register</a></span>
            </div>
            <div class="checkbox my-3">
                <label>
                    <input type="checkbox" name="remember-me" value="1"> Remember me
                </label>
            </div>
            @error('errorMessage')
                <div class="text-danger m-3"id="errorMessage" style="max-width: 50vw">{{ $message }}</div>
            @enderror
            <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
            <p class="text-body-secondary mb-3 mt-5">&copy; 2023 IndoJuni, Inc</p>
        </form>
    </main>
@endsection
