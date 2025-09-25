<header
    class="d-flex align-items-center justify-content-center justify-content-md-between fixed-top mb-4 flex-wrap px-3 py-3"
    style="background: linear-gradient(180deg, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.35) 50%, rgba(255,255,255,0) 100%);">
    <div class="col-md-3 mb-md-0 align-items-center mb-2">
        <a href="/" class="d-inline-flex link-body-emphasis text-decoration-none align-items-center">
            <img src="{{ asset('images/app/xyXVxK19116nI6TPT5KF.png') }}" alt="logo" height="40">
        </a>
    </div>

    <ul class="nav col-12 col-md-auto justify-content-center mb-md-0 mb-2">
        <li><a href="{{ route('app.home.page') }}" class="nav-link px-2"
                style="{{ Request::is('/') ? 'text-decoration:underline' : '' }}">Home</a></li>
        <li><a href="{{ route('app.catalogue.page') }}" class="nav-link px-2"
                style="{{ Request::is('catalogue*') ? 'text-decoration:underline' : '' }}">Catalogue</a>
        </li>
        <li><a href="{{ route('app.aboutus.page') }}" class="nav-link px-2"
                style="{{ Request::is('aboutus*') ? 'text-decoration:underline' : '' }}">About Us</a></li>
    </ul>

    <div class="col-md-3 text-end">
        @auth
            <ul class="nav col-12 col-md-auto justify-content-end mb-md-0 mb-2">
                <li class="d-flex align-items-center">
                    <a href="{{ route('app.checkout.page') }}" class="me-3"><i class="fa-solid fa-cart-shopping fa-xl"
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
                                    <a href="{{ route('app.admin.dashboard.page') }}" class="dropdown-item">Dashboard</a>
                                </li>
                            @endcan
                            <li>
                                <a href="{{ route('app.invoice.page') }}" class="dropdown-item">Transaction</a>
                            </li>
                            <li>
                                <a href="{{ route('app.logout.logout') }}" class="dropdown-item">Logout</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        @else
            <a href="{{ route('app.login.page') }}" class="btn btn-light me-2">Login</a>
            <a href="{{ route('app.register.page') }}" class="btn btn-primary">Sign-up</a>
        @endauth
    </div>
</header>
