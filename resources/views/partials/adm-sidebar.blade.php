<div class="d-flex flex-column bg-body-tertiary flex-shrink-0" style="width: 4.5rem;">
    <a href="/" class="d-block link-body-emphasis text-decoration-none p-3" title="Icon-only" data-bs-toggle="tooltip"
        data-bs-placement="right">
        <svg class="bi pe-none" width="40" height="32">
            <use xlink:href="#bootstrap" />
        </svg>
        <span class="visually-hidden">Icon-only</span>
    </a>
    <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}"
                class="{{ Request::is('admin/dashboard*') || Request::is('v2/admin/dashboard*') ? 'active' : '' }} nav-link border-bottom rounded-0 py-3"
                aria-current="page" title="Dashboard" data-bs-toggle="tooltip" data-bs-placement="right">
                <i class="fa-solid fa-gauge"></i>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.product') }}"
                class="nav-link {{ Request::is('admin/product') || Request::is('v2/admin/product') ? 'active' : '' }} border-bottom rounded-0 py-3"
                title="Product" data-bs-toggle="tooltip" data-bs-placement="right">
                <i class="fa-solid fa-table"></i>

            </a>
        </li>
        <li>
            <a href="{{ route('admin.invoice') }}"
                class="nav-link {{ Request::is('admin/invoice') || Request::is('v2/admin/invoice') ? 'active' : '' }} border-bottom rounded-0 py-3"
                title="Invoice" data-bs-toggle="tooltip" data-bs-placement="right">
                <i class="fa-solid fa-file-invoice"></i>
            </a>
        </li>
    </ul>
    <div class="dropdown border-top">
        <a href="#"
            class="d-flex align-items-center justify-content-center link-body-emphasis text-decoration-none dropdown-toggle p-3"
            data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-circle-user" style="font-size:1.5rem"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
            <li class="border-bottom px-3 pb-2"> @auth
                    <strong>Hello, {{ Auth::user()->username }}</strong>
                @endauth
            </li>
            <li><a class="dropdown-item" href="{{ route('app.home') }}">Back to homepage</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
        </ul>
    </div>
    {{-- <div class="dropdown border-top">
        <a href="#"
            class="d-flex align-items-center justify-content-center link-body-emphasis text-decoration-none dropdown-toggle p-3"
            data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="mdo" width="24" height="24" class="rounded-circle">
        </a>
        <ul class="dropdown-menu text-small shadow">
            <li><a class="dropdown-item" href="#">New project...</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="#">Sign out</a></li>
        </ul>
    </div> --}}

</div>
