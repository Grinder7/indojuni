<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>IndoJuni</title>
        <meta name="robots" content="NOINDEX, NOFOLLOW">
        <link rel="icon" type="image/x-icon" href="{{ asset('images/app/xyXVxK19116nI6TPT5KF.png') }}" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <style>
            .nav-link {
                color: white;
            }

            .swal2-shown {
                display: flex;
                height: 100vh;
                justify-content: center;
                scrollbarPadding: false,
            }

            html,
            body {
                height: 100%;
                width: 100%;
                scroll-behavior: smooth;
                scroll-padding-top: 6rem;
            }
        </style>
    </head>

    <body class="d-flex flex-column min-vh-100">
        <div>
            @include('partials.app-navbar')
        </div>

        <div class="d-flex h-100 text-center"
            style="padding-top:6rem; background-image:url({{ asset('images/app/ZiO1i56gJbwduUeaoQDW.jpg') }}); background-repeat:no-repeat; background-size:cover; background-position:center">
            <div class="cover-container d-flex w-100 h-100 flex-column mx-auto p-3">
                <main class="align-items-center mt-auto px-3">
                    <h1 class="text-white" style="text-shadow:0 0 50px black;">IndoJuni</h1>
                    <p class="lead fw-semibold text-white" style="text-shadow:0 0 20px black;">Mudah dan Cepat, Belanja
                        di
                        IndoJuni!
                    </p>
                </main>
                @include('partials.app-footer')
            </div>
        </div>
        @include('partials.chatbot')
        @include('partials.sweet-alert')
    </body>

</html>
