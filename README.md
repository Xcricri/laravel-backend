# Company-profile Backend API

Backend API untuk aplikasi company-profile, dibangun dengan Laravel 12 dan Laravel Sanctum untuk website company-profile.

## Tech Stack

- Laravel 12
- PHP ^8.2
- Laravel Sanctum
- MySQL / PostgreSQL / SQLite (sesuai konfigurasi `.env`)

## Fitur Utama

- Autentikasi API (login, logout) berbasis Sanctum
- Public API untuk:
    - pages
    - services
    - portfolios
    - contact messages
- Admin API (protected `auth:sanctum`) untuk kelola:
    - users
    - pages
    - services
    - portfolios
    - messages

## Instalasi

1. Masuk ke folder `laravel-backend`.
2. Install dependency Composer `composer install`.
3. Copy file environment dari `.env.example` ke `.env`.
4. Atur konfigurasi database pada `.env`.
5. Generate application key.
6. Jalankan migrasi dan seeder.
7. Start server.

## Menjalankan Project

- `php artisan key:generate` → membuat key
- `php artisan migrate --seed` → migrasi + data awal
- `php artisan serve` → menjalankan API lokal

Default local API: `http://127.0.0.1:8000`

## Catatan

- Endpoint admin membutuhkan token Sanctum.
- Pastikan URL backend ini diset pada frontend melalui `NEXT_PUBLIC_BACKEND_URL`.
