# Indojuni

An e-commerce web application built with Laravel that serves both a traditional web storefront and a JSON API. The platform lets shoppers browse a catalog, manage carts, place orders, and review invoices, while giving administrators tools to curate products and monitor transactions.

## Features

-   **Product catalog** with rich filtering (category, subcategory, brand, type, variant, size, unit, price, stock, description) and fuzzy search to handle partial matches or similarity lookups.
-   **Authentication with Laravel Sanctum**, offering classic web sessions and personal access tokens for API usage.
-   **Shopping cart management** that keeps carts tied to users, supports bulk modifications, and enforces inventory limits.
-   **Checkout pipeline** that captures billing data, decrements product stock, generates order and payment records, and issues invoices.
-   **Invoice center** where customers and admins can review transaction history and retrieve detailed invoices.
-   **Admin dashboard** for maintaining product data (create, update, upload images) and reviewing sales activity.
-   **Chatbot integration** that proxies authenticated user conversations to an external agent API while keeping a short-lived history.
-   **Operational SQL query endpoint** guarded by a shared secret and banned keyword list for quick diagnostics.

## Web Experience

| Route                 | Description                                                  |
| --------------------- | ------------------------------------------------------------ |
| `/`                   | Landing page.                                                |
| `/catalogue`          | Product catalogue with search and filters.                   |
| `/checkout`           | Checkout page showing cart contents and totals.              |
| `/invoice`            | List of a user's transactions.                               |
| `/invoice/{id}`       | Detailed invoice for a specific order.                       |
| `/login`, `/register` | Authentication pages.                                        |
| `/admin`              | Admin dashboard for catalog management (`admin` middleware). |

Related routes cover logout, cart actions, admin invoice views, and chatbot messaging (`/chatbot/*`).

## API Overview

All API endpoints are under `/api/v1`. Authentication uses Laravel Sanctum tokens unless noted.

| Method & Path                              | Auth       | Purpose                                                           |
| ------------------------------------------ | ---------- | ----------------------------------------------------------------- |
| `GET /api/ping`                            | Public     | Health check.                                                     |
| `POST /api/v1/auth/login`                  | Guest      | Issue Sanctum token and shopping session.                         |
| `POST /api/v1/auth/logout`                 | Auth       | Revoke current token.                                             |
| `GET /api/v1/auth/user`                    | Auth       | Retrieve the authenticated user profile.                          |
| `GET /api/v1/product/all`                  | Public     | List products with pagination and filtering query params.         |
| `POST /api/v1/product/detail`              | Public     | Fetch a single product by ID.                                     |
| `POST /api/v1/product/search-similar-name` | Public     | Similarity search by product name.                                |
| `POST /api/v1/product/search-contain-name` | Public     | Substring search by product name.                                 |
| `GET /api/v1/product/filter-options`       | Public     | Retrieve available catalog facets.                                |
| `GET /api/v1/cart/current`                 | Auth       | Get shopping session and cart items.                              |
| `POST /api/v1/cart/modify`                 | Auth       | Bulk update cart quantities.                                      |
| `POST /api/v1/cart/add`                    | Auth       | Add multiple items in one call.                                   |
| `POST /api/v1/checkout`                    | Auth       | Complete checkout and create order.                               |
| `GET /api/v1/invoice`                      | Auth       | List invoices for the authenticated user.                         |
| `GET /api/v1/invoice/{id}`                 | Auth       | Retrieve a specific invoice (admins can access any invoice).      |
| `POST /api/v1/query`                       | Secret key | Execute read-only SQL for diagnostics (write operations blocked). |

## Architecture Highlights

-   **Modules**: Domain logic lives in `app/Modules/*`, each pairing repositories and services (e.g., `Product`, `ShoppingSession`, `OrderDetail`).
-   **HTTP Controllers**: `app/Http/Controllers` orchestrate both web views and API responses.
-   **Resources**: API payloads use Laravel resources (`app/Http/Resources`) for consistent serialization.
-   **Database**: Eloquent models (such as `Product` and `ShoppingSession`) backed by migrations in `database/migrations`.
-   **Frontend**: Blade templates under `resources/views` with assets built by Vite (`resources/js`, `resources/css`).
-   **Containers**: Dockerfiles and a compose definition are available for local and production deployments.

## Local Development

1. Install dependencies:
    ```bash
    composer install
    npm install
    ```
2. Copy `.env.example` to `.env` and configure database, cache, filesystem, and chatbot API settings.
3. Generate an application key and run migrations:
    ```bash
    php artisan key:generate
    php artisan migrate
    ```
4. (Optional) Seed data or use factories in `database/factories`.
5. Start services:
    ```bash
    php artisan serve
    npm run dev
    ```

### Docker

Dockerfiles (`Dockerfile.dev`, `Dockerfile.prod`, etc.) and `docker-compose.yml` are provided. To boot the stack locally:

```bash
docker compose up --build
```

Adjust your `.env` variables to match containerized services.

## Testing

The project uses Pest. Run the suite with:

```bash
php artisan test
```
