<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="robots" content="NOINDEX, NOFOLLOW">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('images/app/xyXVxK19116nI6TPT5KF.png') }}" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('css/app/auth.css') }}">
        @yield('styles')
        <style>
            .swal2-shown {
                display: flex;
                height: 100vh;
                justify-content: center;
                scrollbarPadding: false,
            }
        </style>
    </head>

    <body class="bg-body-tertiary h-100 w-100 text-center">
        @yield('content')

        @yield('scripts')
    </body>

</html>
