<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="robots" content="NOINDEX, NOFOLLOW">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
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

            .bi {
                vertical-align: -.125em;
                fill: currentColor;
            }
        </style>
    </head>

    <body class="d-flex flex-column min-vh-100 bg-light">
        <header class="w-100">
            <div class="navbar navbar-dark bg-dark px-3 shadow-sm">
                <a href="#" class="navbar-brand d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                        <path fill-rule="evenodd"
                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                    </svg>
                    <strong>&nbsp;Admin Page</strong>
                </a>
                <li><a href="/" class="text-white">Log Out</a></li>
            </div>
        </header>
        @yield('content')
        <footer class="d-flex border-top mx-3 mb-3 mt-auto border-black">
            <div class="container-fluid mt-1">
                <a href="https://getbootstrap.com/" target="_blank" class="mb-md-0 text-decoration-none lh-1 mb-3 me-2">
                    <i class="fa-brands fa-bootstrap"></i>
                </a>
                <span>&copy; 2023 IndoJuni, Inc</span>
            </div>
        </footer>
        @yield('script')
        @include('partials.sweet-alert')
    </body>

</html>
