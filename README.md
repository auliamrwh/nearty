# Nearty - Titip Beli Jajanan Lewat Driver Terdekat

Aplikasi dashboard berbasis Laravel 13 untuk **titip beli jajanan lewat sesama pengguna yang lagi jadi driver dadakan** — anak kost nggak perlu keluar buat jajan hal kecil (es teh, baso ikan, kentang goreng), dan ojek online kurang cocok untuk jarak sedekat & order sekecil itu.

Satu akun bisa berperan sebagai **pembeli** maupun **driver**, tergantung status per transaksi (`pembeli_id` / `driver_id`) — bukan lewat role tetap.

## Fitur Utama

- Autentikasi (Login, Register, Logout) dengan Laravel Breeze + validasi & proteksi CSRF
- Role & Permission (Spatie): `admin` (kelola user & moderasi) dan `user` (pengguna aplikasi)
- CRUD **Titipan**: buat, lihat, ubah, batalkan (soft delete + alasan pembatalan)
- CRUD **Item Titipan**: daftar barang per titipan, kategori (makanan/minuman/lainnya)
- CRUD **Ulasan**: rating 1-5 bintang + komentar antara pembeli & driver setelah titipan selesai
- Mode Driver: toggle *available*, lihat titipan terdekat (dihitung pakai rumus Haversine), ambil order, update status (`diambil_driver` → `diantar` → `dibayar` → `selesai`)
- Dashboard: statistik ringkas + grafik tren titipan 7 hari terakhir (Chart.js)
- Search & pagination di semua tabel data
- REST API (JSON) untuk resource **Titipan** dan **Ulasan**, autentikasi token via Laravel Sanctum
- UI custom (Tailwind, sidebar branding Nearty) — bukan tampilan bawaan Breeze

## Tech Stack

- Laravel 13 • PHP 8.3
- MySQL 8
- Blade + Laravel Breeze (autentikasi)
- Tailwind CSS + Alpine.js (UI kustom)
- Spatie Laravel Permission (role & permission)
- Laravel Sanctum (REST API token)
- Chart.js (grafik dashboard, via CDN)

## Instalasi

```bash
git clone <url-repo-kelompok> nearty
cd nearty

composer install
npm install

cp .env.example .env
php artisan key:generate

# atur DB_DATABASE, DB_USERNAME, DB_PASSWORD di .env sesuai MySQL lokal kamu
php artisan migrate --seed

npm run build   # atau: npm run dev (saat development)
php artisan serve
```

Buka `http://127.0.0.1:8000`.

## 👥 Akun Default (hasil seeder)

| Email | Password | Role | Catatan |
|---|---|---|---|
| admin@nearty.test | password | admin | Kelola user & lihat semua titipan |
| pembeli@example.com | password | user | Sudah punya riwayat titipan (menunggu, selesai, dibatalkan) |
| driver@example.com | password | user | `is_driver_active = true`, sedang mengantar 1 titipan |
| regina@example.com | password | user | Contoh akun tambahan |

## REST API

Autentikasi memakai token Sanctum. Ambil token lewat:

```
POST /api/login
Body: { "email": "pembeli@example.com", "password": "password" }
Response: { "user": {...}, "token": "..." }
```

Kirim token di header setiap request berikutnya: `Authorization: Bearer <token>`

### Resource 1 — Titipan

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/titipans` | List titipan (support `?q=`, `?status=`, pagination) |
| GET | `/api/titipans/{id}` | Detail titipan + item |
| POST | `/api/titipans` | Buat titipan baru (+ array `items`) |
| PATCH | `/api/titipans/{id}` | Update status / total aktual |
| DELETE | `/api/titipans/{id}` | Batalkan (soft delete) |

### Resource 2 — Ulasan

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/ulasans` | List ulasan yang diterima user login |
| GET | `/api/ulasans/{id}` | Detail ulasan |
| POST | `/api/titipans/{id}/ulasans` | Beri ulasan untuk titipan yang sudah selesai |
| DELETE | `/api/ulasans/{id}` | Hapus ulasan (soft delete) |

Lainnya: `POST /api/logout`, `GET /api/me`.

## Struktur Database (ERD)

Lihat detail lengkap di [`PANDUAN_KOLABORASI.md`](PANDUAN_KOLABORASI.md) — termasuk diagram ERD, penjelasan soft delete, dan pembagian tugas tim.

## Screenshot

_(Tempel screenshot dashboard, buat titipan, mode driver, dan admin di sini sebelum submit.)_

## Bug yang Diketahui

_(Isi jujur di sini kalau ada bug saat demo — kejujuran dinilai positif oleh dosen.)_

## Tim

- **Aulia** — Setup project, Dashboard, Role & Permission, Git repository owner
- **Hawa** — Modul Titipan (CRUD pembeli)
- **Regina** — Mode Driver, Ulasan, REST API
