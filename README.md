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
   git clone <repository-url-backend>
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

## Dokumentasi API

Semua endpoint API diawali dengan prefix `/api`.

### Autentikasi

| Method | Endpoint | Deskripsi | Akses |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/login` | Login user untuk mendapatkan token | Public |
| `POST` | `/api/logout` | Logout dan menghapus session token | Private (Sanctum) |

### User Management (CRUD)

Semua endpoint di bawah ini memerlukan header `Authorization: Bearer <token-kamu>`.

| Method | Endpoint | Deskripsi |
| :--- | :--- | :--- |
| `GET` | `/api/user` | Mendapatkan data profil user yang sedang login |
| `GET` | `/api/users` | Mengambil semua daftar user |
| `POST` | `/api/users` | Menambahkan user baru |
| `GET` | `/api/users/{id}` | Melihat detail user berdasarkan ID |
| `PUT/PATCH` | `/api/users/{id}` | Mengupdate data user berdasarkan ID |
| `DELETE` | `/api/users/{id}` | Menghapus user berdasarkan ID |

## Struktur Penting
- `app/Http/Controllers/Api`: Folder berisi logic API.
- `routes/api.php`: File konfigurasi route API.
- `app/Models`: Model Eloquent untuk berinteraksi dengan database.

