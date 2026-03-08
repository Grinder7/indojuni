# Indojuni

Indojuni is a Laravel-based e-commerce application with a Blade web storefront and a JSON API for product browsing, cart operations, and checkout.

## Highlights

- Product catalogue with filtering and search.
- Session-based web auth plus Sanctum token support for JSON clients.
- Cart management and checkout flow.
- Invoice pages for authenticated users.
- Admin product management dashboard.
- Chatbot endpoints for message history and interactions.

## Tech Stack

- PHP 8.2+
- Laravel 12
- Laravel Sanctum
- Vite + Bootstrap + Sass
- PostgreSQL (default in `.env.example`)

## Quick Start

1. Install dependencies:

    ```bash
    composer install
    npm install
    ```

2. Prepare environment:

    ```bash
    copy .env.example .env
    php artisan key:generate
    ```

3. Configure `.env` database values, then run migrations:

    ```bash
    php artisan migrate
    ```

4. Start local development services:

    ```bash
    composer run dev
    ```

`composer run dev` starts:

- Laravel server (`php artisan serve --host=0.0.0.0 --port 8000`)
- Queue listener
- Vite dev server

## NPM Scripts

- `npm run dev` – Vite dev server (HMR)
- `npm run build` – production assets

## Main Web Routes

| Route                       | Notes                       |
| --------------------------- | --------------------------- |
| `/`                         | Home page                   |
| `/catalogue`                | Product catalogue           |
| `/aboutus`                  | About page                  |
| `/login`, `/register`       | Guest-only auth pages       |
| `/profile`                  | Authenticated user profile  |
| `/checkout`                 | Authenticated checkout page |
| `/invoice`, `/invoice/{id}` | Authenticated invoice pages |
| `/admin`                    | Admin-only dashboard        |

Additional web endpoints include:

- Cart operations under `/cart/*`
- Chatbot operations under `/chatbot/*`

## API Routes

Current API routes are defined in `routes/api.php`.

| Method | Path                   | Auth    | Purpose                                      |
| ------ | ---------------------- | ------- | -------------------------------------------- |
| GET    | `/api/ping`            | Public  | Health check                                 |
| GET    | `/api/v1/product/all`  | Public  | List products (supports filter query params) |
| GET    | `/api/v1/auth/user`    | Sanctum | Return authenticated user                    |
| GET    | `/api/v1/cart/current` | Sanctum | Get current cart                             |
| POST   | `/api/v1/cart/add`     | Sanctum | Add items to cart                            |
| POST   | `/api/v1/cart/modify`  | Sanctum | Modify cart items                            |
| POST   | `/api/v1/checkout`     | Sanctum | Perform checkout                             |

### Auth Notes

- There is no dedicated `/api/v1/auth/login` route at this time.
- Login is handled by `POST /login` (web route).
- `LoginController@login` returns JSON (including Sanctum token) when request `Accept` is JSON.

## Docker

Container files are included (`Dockerfile*`, `docker-compose.yml`, `docker-compose-dev.yml`).

Start with:

```bash
docker compose up --build
```

Ensure `.env` values match your container setup (ports, DB host, credentials).
