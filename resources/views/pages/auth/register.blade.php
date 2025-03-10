@extends('layouts.auth')

@section('title', 'Register - IndoJuni')

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

        .b-example-divider {
            width: 100%;
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
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
    <main class="form-signup w-100 m-auto">
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <img class="mb-4" src="{{ asset('images/app/xyXVxK19116nI6TPT5KF.png') }}" alt="" height="57">
            <h1 class="h3 mb-3 fw-normal">Register</h1>
            <div class="form-floating">
                <input type="text" class="form-control @error('username') is-invalid @enderror" id="floatingUsername"
                    placeholder="Username" name="username" value="{{ old('username') }}">
                <label for="floatingUsername">Username</label>
            </div>
            <div class="form-floating">
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="floatingInput"
                    placeholder="name@example.com" name="email" value="{{ old('email') }}">
                <label for="floatingInput">Email address</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="floatingPasswordUp"
                    placeholder="Password" name="password">
                <label for="floatingPassword">Password</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                    id="floatingPasswordConfirmation" placeholder="Password Confirmation" name="password_confirmation">
                <label for="floatingPasswordConfirmation">Password Confirmation</label>
            </div>
            <div class="d-flex float-end pb-3">
                Already have an account? <span><a href="{{ route('login.page') }}" class="ps-1"> Login</a></span>
            </div>
            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" name="remember-me" value="1"> Remember me
                </label>
            </div>
            @error('email')
                <div class="text-danger m-3">
                    <small>
                        {{ $message }}
                    </small>
                </div>
            @enderror
            @error('password')
                <div class="text-danger m-3">
                    <small>
                        {{ $message }}
                    </small>
                </div>
            @enderror
            <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
            <p class="mt-5 mb-3 text-body-secondary">&copy; 2023 IndoJuni, Inc</p>
        </form>
    </main>
@endsection

@section('scripts')
    @include('partials.sweet-alert')
@endsection
