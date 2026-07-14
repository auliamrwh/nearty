# Nearty — Titip Beli Jajanan Lewat Driver Terdekat

Aplikasi web berbasis Laravel 13 untuk **titip beli jajanan lewat sesama pengguna yang lagi jadi driver dadakan**. Anak kost nggak perlu keluar buat jajan hal kecil (es teh, baso ikan, kentang goreng) — cukup bikin titipan, driver terdekat yang ambil. Satu akun bisa berperan sebagai **pembeli** maupun **driver** sekaligus.

## Fitur Utama

- **Autentikasi** — Login, Register, Logout via Laravel Breeze; validasi input & proteksi CSRF
- **Role & Permission** — 2 role: `admin` (kelola user & moderasi) dan `user` (pengguna aplikasi), dikelola Spatie
- **CRUD Titipan** — Buat, lihat, ubah, batalkan titipan (soft delete + alasan pembatalan)
- **CRUD User (Admin)** — Tambah, edit, nonaktifkan, pulihkan akun user dari panel admin
- **Moderasi Ulasan (Admin)** — Lihat semua ulasan, filter rating, hapus ulasan
- **Mode Driver** — Toggle available, lihat titipan terdekat (Haversine), ambil order, update status pengantaran
- **Ulasan** — Rating 1–5 bintang + komentar antara pembeli & driver setelah selesai
- **Dashboard** — Statistik ringkas per role (admin/pembeli/driver) + grafik tren 7 hari (Chart.js)
- **Search & Pagination** — Di semua tabel data (titipan, user, ulasan)
- **REST API** — 2 resource API (Titipan & Ulasan) berbasis JSON, autentikasi token Sanctum
- **UI Responsif** — Sidebar + mobile-friendly, Tailwind CSS + Alpine.js

## Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | Laravel 13 • PHP 8.3 |
| Database | MySQL 8 |
| Auth | Laravel Breeze |
| Frontend | Blade • Tailwind CSS • Alpine.js |
| Role & Permission | Spatie Laravel Permission v6 |
| API Auth | Laravel Sanctum |
| Chart | Chart.js (via CDN) |
| Version Control | GitHub |

## Instalasi

```bash
# 1. Clone & masuk ke direktori
git clone <url-repo-kelompok> nearty
cd nearty

# 2. Install dependencies
composer install
npm install

# 3. Konfigurasi environment
cp .env.example .env
php artisan key:generate

# 4. Atur database di .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# 5. Migrasi & seed data demo
php artisan migrate --seed

# 6. Build aset & jalankan server
npm run build         # atau: npm run dev (mode development)
php artisan serve
```

Buka `http://127.0.0.1:8000`

## 👤 Akun Default (hasil seeder)

| Email | Password | Role | Catatan |
|-------|----------|------|---------|
| admin@nearty.test | password | admin | Kelola user, moderasi ulasan, lihat semua titipan |
| pembeli@example.com | password | user | Sudah punya riwayat titipan (menunggu, selesai, dibatalkan) |
| driver@example.com | password | user | `is_driver_active = true`, bisa langsung ambil order |
| regina@example.com | password | user | Akun tambahan untuk demo multi-user |

## REST API

Autentikasi memakai token Sanctum. Ambil token via:

```
POST /api/login
Body: { "email": "pembeli@example.com", "password": "password" }
Response: { "user": {...}, "token": "1|abc..." }
```

Kirim token di header: `Authorization: Bearer <token>`

### Resource 1 — Titipan

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | `/api/titipans` | List titipan (`?q=`, `?status=`, pagination) |
| GET | `/api/titipans/{id}` | Detail titipan beserta items |
| POST | `/api/titipans` | Buat titipan baru (+ array `items`) |
| PATCH | `/api/titipans/{id}` | Update status / total aktual |
| DELETE | `/api/titipans/{id}` | Batalkan titipan (soft delete) |

### Resource 2 — Ulasan

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | `/api/ulasans` | List ulasan yang diterima user login |
| GET | `/api/ulasans/{id}` | Detail ulasan |
| POST | `/api/titipans/{id}/ulasans` | Beri ulasan untuk titipan selesai |
| DELETE | `/api/ulasans/{id}` | Hapus ulasan |

Endpoint lainnya: `POST /api/logout`, `GET /api/me`

## Screenshot

> 


| Dashboard Admin | _(![](image.png))_ 
| Dashboard User | ![](image-1.png)
| Buat Titipan | _(![](image-2.png))_ 
| Mode Driver | _(![](image-3.png))_ 
| Panel Admin — Kelola User | _(![](image-4.png))
| Panel Admin — Moderasi Ulasan | _(![](image-5.png))_ 

## Bug yang Diketahui

- Jarak titipan baru muncul setelah browser mengizinkan akses GPS; tanpa GPS, jarak tidak tampil.
- Pin peta di form buat titipan menggunakan OpenStreetMap (membutuhkan koneksi internet).
- Belum terlalu paham tentang github(commit, branch, push, dll)

## Lesson Learned

Membangun Nearty mengajarkan kami cara kerja ekosistem Laravel secara end-to-end.
Tantangan terbesar adalah mengelola hubungan antar tabel (Titipan, ItemTitipan, Ulasan)
dan memastikan seeder bisa dijalankan ulang dari kondisi database kosong. Kami belajar pentingnya Form Request untuk memusatkan validasi, soft delete untuk data yang tidak boleh hilang permanen, dan Haversine formula untuk menghitung jarak antar koordinat. Spatie Permission menyederhanakan kontrol akses yang sebelumnya kami bayangkan rumit. Ke depan, kami akan lebih disiplin dalam membuat branch terpisah per fitur sejak awal dan belajar memahami github


## Tim

| Nama | NIM | Kontribusi Utama |
| Aulia | _(230102027)_ | Setup project, Dashboard, Role & Permission, Admin panel |
| Hawa | _(230102056)_ | Modul Titipan (CRUD pembeli), Git |
| Regina | _(230102115)_ | Mode Driver, Ulasan, REST API |

---

Untuk panduan kolaborasi tim dan diagram ERD, lihat [`PANDUAN_KOLABORASI.md`](PANDUAN_KOLABORASI.md).
