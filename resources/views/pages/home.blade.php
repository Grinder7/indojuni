<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>IndoJuni</title>

        <link rel="icon" type="image/x-icon" href="{{ asset('images/app/xyXVxK19116nI6TPT5KF.png') }}" />
        {{-- Bootstrap --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        {{-- Font Awesome --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
            integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

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
        </style>
    </head>

    <body>
        @include('partials.app-navbar')

        <div class="d-flex h-100 w-100 text-center"
            style="padding-top:6rem; background-image:url({{ asset('images/app/ZiO1i56gJbwduUeaoQDW.jpg') }}); background-repeat:no-repeat; background-size:cover; background-position:center">
            <div class="cover-container d-flex w-100 h-100 flex-column mx-auto p-3">
                <main class="align-items-center mt-auto px-3">
                    <h1 class="text-white" style="text-shadow:0 0 50px black;">IndoJuni</h1>
                    <p class="lead fw-semibold text-white" style="text-shadow:0 0 20px black;">Mudah dan Cepat, Belanja
                        di
                        IndoJuni!
                    </p>
                </main>
                <div class="container mt-auto">
                    <div class="container">
                        <footer
                            class="d-flex justify-content-between align-items-center border-top my-4 flex-wrap border-white py-3">
                            <div class="col-md-4 d-flex align-items-center">
                                <a href="https://getbootstrap.com/" target="_blank"
                                    class="mb-md-0 text-decoration-none lh-1 mb-3 me-2">
                                    <i class="fa-brands fa-bootstrap" style="color: rgba(255,255,255,1);"></i>
                                </a>
                                <span class="mb-md-0 mb-3 text-white">&copy; 2023 IndoJuni, Inc</span>
                            </div>
                        </footer>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
        </script>
        @include('partials.sweet-alert')
    </body>

</html>
