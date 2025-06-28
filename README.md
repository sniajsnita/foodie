# Foodie REST API

Backend API untuk aplikasi resep masakan "Foodie", dibangun menggunakan Laravel 11, MySQL, dan Laravel Sanctum untuk otentikasi.

---

## 🚀 Ringkasan

Foodie adalah REST API yang menyediakan fitur:

* Registrasi & Login (Sanctum Auth)
* Manajemen kategori resep (CRUD)
* Manajemen resep lengkap dengan foto, bahan, langkah (CRUD)
* Sistem transaksi koin: top up dan tarik saldo

---

## ⚙️ Teknologi

| Teknologi  | Keterangan                    |
| ---------- | ----------------------------- |
| Laravel 11 | Framework utama               |
| Sanctum    | Otentikasi token berbasis SPA |
| MySQL      | Database relasional           |
| Docker     | Kontainerisasi lokal/dev/test |
| Postman    | Testing dan dokumentasi API   |

---

## 🔐 Autentikasi

Gunakan Laravel Sanctum.

### Endpoint:

* `POST /api/register`
* `POST /api/login`
* `POST /api/logout` (require token)

Tambahkan `Authorization: Bearer {token}` pada setiap request ke endpoint yang dilindungi.

---

## 📦 API Endpoint

### Category

* `GET /api/categories`
* `POST /api/categories`
* `PUT /api/categories/{id}`
* `DELETE /api/categories/{id}`

### Recipe

* `GET /api/recipes`
* `POST /api/recipes` *(with image upload)*
* `GET /api/recipes/{id}`
* `PUT /api/recipes/{id}` *(with image upload optional)*
* `DELETE /api/recipes/{id}`

### Coin Transactions

* `GET /api/coin-transactions`
* `POST /api/coin-transactions/topup`
* `POST /api/coin-transactions/tarik`
* `GET /api/coin-transactions/{id}`

---

## 📂 Cara Menjalankan Lokal

```bash
git clone https://github.com/sniajsnita/foodie.git
cd foodie
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

API tersedia di `http://127.0.0.1:8000`

---

## 🧪 Postman Collection

Import koleksi berikut untuk uji coba:

📥 https://soniajusnita.postman.co/workspace/Sonia-Jusnita's-Workspace~e6834fbb-6952-42f4-8639-e7929c42f150/collection/44565984-90571582-9207-446e-be5c-425eea8e0593?action=share&source=copy-link&creator=44565984

---

## 📌 Catatan Teknis

* Validasi laravel built-in (`FormRequest`/`$request->validate()`)
* Upload gambar disimpan di `storage/app/public/recipes`
* Gunakan middleware `auth:sanctum` untuk proteksi route

---

## ✨ Kontribusi

Pull request terbuka! Silakan fork dan kirimkan perbaikan.

---

## 👩‍💻 Developer

Sonia Jusnita – [@sniajsnita](https://github.com/sniajsnita)

---
