# Laravel API Backend - Documentation

Repository ini berisi backend API untuk aplikasi Next Level yang dibangun menggunakan Laravel 12 dan Laravel Sanctum untuk autentikasi.

## Stack Teknologi

- **Framework:** Laravel 12
- **Authentication:** Laravel Sanctum
- **Database:** MySQL / SQLite (Sesuai konfigurasi `.env`)
- **PHP Version:** ^8.2

## Persyaratan Sistem

- PHP >= 8.2
- Composer
- MySQL atau PostgreSQL (Opsional, bisa menggunakan SQLite)

## Instalasi

1. **Clone Repository dan Masuk ke Folder**

    ```bash
    git clone https://github.com/Xcricri/laravel-backend.git
    cd backend
    ```

2. **Install Dependensi**

    ```bash
    composer install
    ```

3. **Konfigurasi Environment**
   Salin file `.env.example` ke `.env` dan sesuaikan konfigurasi database.

    ```bash
    cp .env.example .env
    ```

4. **Generate App Key**

    ```bash
    php artisan key:generate
    ```

5. **Migrasi Database & Seeder**

    ```bash
    php artisan migrate --seed
    ```

6. **Jalankan Server**
    ```bash
    php artisan serve
    ```
    API akan berjalan secara default di `http://127.0.0.1:8000`.
