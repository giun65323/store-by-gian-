# Laravel Simple Auth Backend

Backend sederhana Laravel dengan Login, Register, Logout, Dashboard, dan contoh CRUD Produk.

## Cara Pakai

1. **Buat project Laravel baru** (di komputer kamu, butuh PHP + Composer):
   ```bash
   composer create-project laravel/laravel nama-project
   cd nama-project
   ```

2. **Copy semua file dari folder ini** ke dalam project barumu, timpa/gabung ke folder yang sama:
   - `routes/web.php` → replace file routes/web.php bawaan
   - `app/Http/Controllers/Auth/*.php` → masuk ke app/Http/Controllers/Auth/
   - `app/Http/Controllers/*.php` → masuk ke app/Http/Controllers/
   - `app/Models/Product.php` → masuk ke app/Models/
   - `database/migrations/*.php` → masuk ke database/migrations/
   - `resources/views/**` → masuk ke resources/views/

   Model `User.php` TIDAK perlu diubah, sudah otomatis ada dari `laravel/laravel` bawaan Laravel dan sudah punya Auth trait.

3. **Setting `.env`** — sesuaikan koneksi database (contoh pakai MySQL):
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_db_kamu
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Jalankan migrasi**:
   ```bash
   php artisan migrate
   ```

5. **Jalankan server**:
   ```bash
   php artisan serve
   ```

6. Buka `http://127.0.0.1:8000` di browser → otomatis redirect ke halaman login.

## Fitur yang tersedia

- ✅ Register akun baru (`/register`)
- ✅ Login (`/login`)
- ✅ Logout
- ✅ Dashboard setelah login (`/dashboard`)
- ✅ CRUD Produk (`/products`) — tiap user hanya bisa lihat & kelola produknya sendiri
- ✅ Session-based authentication (bawaan Laravel, bukan JWT/token API)
- ✅ Styling pakai Tailwind CDN (tidak perlu setup npm/vite dulu)

## Catatan

- Ini pakai session-based auth (cocok untuk web app biasa). Kalau nanti butuh API untuk konsumsi dari frontend terpisah (React/Vue), bisa tambahkan Laravel Sanctum.
- Kalau mau tambah proteksi lebih (misal email verification), tinggal aktifkan `MustVerifyEmail` di model `User`.
