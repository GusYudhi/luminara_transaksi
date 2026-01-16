# 💳 Luminara Transaksi API

**Luminara Transaksi** adalah layanan Backend API yang bertugas menangani gerbang pembayaran (Payment Gateway) untuk ekosistem **Luminara Photobooth**. Layanan ini menjembatani aplikasi kasir Desktop/Mobile (Flutter) dengan **Midtrans**.

## 🚀 Fitur Utama

- **Integrasi Midtrans Snap:** Membuat token pembayaran untuk QRIS, Virtual Account, dll.
- **Audit Trail:** Menyimpan riwayat transaksi dan status pembayaran.
- **Webhook Handler:** Menerima notifikasi realtime dari Midtrans.
- **Manual Sync/Polling:** Endpoint khusus untuk memaksakan pengecekan status ke server Midtrans (solusi untuk Localhost tanpa Public IP).

## 🛠️ Teknologi

- **Framework:** Laravel 12 / PHP 8.2+
- **Database:** MySQL / MariaDB (via DDEV atau Native)
- **Payment Gateway:** Midtrans (Snap API)
- **Environment:** DDEV (Docker) atau Native PHP Server

## ⚙️ Instalasi & Setup

### 1. Clone & Dependencies
```bash
git clone https://github.com/Andndre/luminara_transaksi.git
cd luminara_transaksi
composer install
```

### 2. Environment Setup
Salin file konfigurasi dan atur kredensial Database serta Midtrans.
```bash
cp .env.example .env
php artisan key:generate
```

Isi `.env` dengan kredensial Midtrans Anda:
```env
MIDTRANS_MERCHANT_ID=...
MIDTRANS_CLIENT_KEY=...
MIDTRANS_SERVER_KEY=...
MIDTRANS_IS_PRODUCTION=false
```

### 3. Database Migration
```bash
php artisan migrate
```

## 🖥️ Cara Menjalankan (Local Network Access)

Agar Aplikasi Flutter di HP/Tablet bisa mengakses API ini melalui Wi-Fi lokal, server harus dijalankan dengan mengikat (bind) ke `0.0.0.0`.

### Opsi A: Menggunakan Native PHP (Disarankan untuk testing cepat)
Pastikan MySQL service menyala (atau gunakan DB dari DDEV), lalu jalankan:
```bash
php artisan serve --host 0.0.0.0 --port 8000
```
*Akses dari HP:* `http://192.168.x.x:8000/api/...`

### Opsi B: Menggunakan DDEV
Project ini sudah dikonfigurasi untuk mengekspos port 8000 dari dalam container ke host.

```bash
ddev start
ddev php artisan serve --host 0.0.0.0 --port 8000
```
*Pastikan Firewall Linux (UFW) mengizinkan port 8000.*

## 🔌 API Endpoints

| Method | Endpoint | Deskripsi |
| :--- | :--- | :--- |
| `POST` | `/api/transaction` | Membuat transaksi baru. Body: `{ "amount": 50000, "order_id": "optional" }` |
| `GET` | `/api/transaction/{orderId}` | Cek status transaksi di database lokal. |
| `POST` | `/api/transaction/{orderId}/sync` | **Force Sync:** Memaksa server cek status ke Midtrans & update DB. |
| `POST` | `/api/midtrans-callback` | Webhook URL untuk Midtrans Notification. |

## 📝 Catatan Penting

- **Local Development:** Karena Webhook Midtrans tidak bisa menembus localhost/LAN tanpa Public IP/Ngrok, aplikasi Flutter menggunakan metode **Polling + Force Sync**. Aplikasi akan memanggil endpoint `/sync` secara berkala untuk memastikan status pembayaran terupdate.

## 📄 License

Project ini bersifat private/proprietary untuk Luminara Photobooth. Didasarkan pada [Laravel Framework](https://laravel.com) (MIT License).