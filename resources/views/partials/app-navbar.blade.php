<header
    class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 fixed-top px-3"
    style="background: linear-gradient(180deg, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.35) 50%, rgba(255,255,255,0) 100%);">
    <div class="col-md-3 mb-2 mb-md-0 align-items-center">
        <a href="/" class="d-inline-flex link-body-emphasis text-decoration-none align-items-center">
            <img src="{{ asset('images/app/xyXVxK19116nI6TPT5KF.png') }}" alt="logo" height="40">
        </a>
    </div>

    <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
        <li><a href="{{ route('app.home') }}" class="nav-link px-2"
                style="{{ Request::is('/') ? 'text-decoration:underline' : '' }}">Home</a></li>
        <li><a href="{{ route('catalogue.index') }}" class="nav-link px-2  "
                style="{{ Request::is('catalogue*') ? 'text-decoration:underline' : '' }}">Catalogue</a>
        </li>
        <li><a href="{{ route('app.aboutus') }}" class="nav-link px-2"
                style="{{ Request::is('aboutus*') ? 'text-decoration:underline' : '' }}">About Us</a></li>
    </ul>

    <div class="col-md-3 text-end">
        @auth
            <ul class="nav col-12 col-md-auto mb-2 justify-content-end mb-md-0 ">
                <li class="d-flex align-items-center">
                    <a href="{{ route('app.checkout') }}" class="me-3"><i class="fa-solid fa-cart-shopping fa-xl "
                            style="color: rgb(255,255,255);{{ Request::is('checkout*') ? 'text-decoration:underline;text-underline-offset: 0.25rem;' : '' }}"></i></a>
                </li>
                <li>
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-circle-user fa-xl" style="color: rgb(255,255,255)"></i>
                        </button>
                        <ul class="dropdown-menu">
                            @can('isAdmin')
                                <li>
                                    <a href="{{ route('admin.dashboard') }}" class="dropdown-item">Dashboard</a>
                                </li>
                            @endcan
                            <li>
                                <a href="{{ route('app.transaction') }}" class="dropdown-item">Transaction</a>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}" class="dropdown-item">Logout</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        @else
            <a href="{{ route('login.page') }}" class="btn btn-light me-2">Login</a>
            {{-- <button type="button" class="btn btn-light me-2">Login</button> --}}
            <a href="{{ route('register.page') }}" class="btn btn-primary">Sign-up</a>
        @endauth
    </div>
</header>
