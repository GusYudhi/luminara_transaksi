# 💳 Luminara Transaksi - Payment Service

<div align="center">
  <img src="public/favicon.ico" alt="Luminara Transaksi" width="100" height="100">

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Midtrans](https://img.shields.io/badge/Midtrans-Payment-blue?style=for-the-badge&logo=credit-card&logoColor=white)

**Dedicated Payment Gateway Service for Luminara Photobooth**

[Features](#-features) • [Installation](#-installation) • [API Reference](#-api-endpoints) • [Usage](#-usage)

</div>

---

## 📖 About

**Luminara Transaksi** is a robust backend microservice designed to handle payment processing for the **Luminara Photobooth Ecosystem**. It acts as a secure bridge between the local offline-first Flutter application and the **Midtrans Payment Gateway**.

This service solves the challenge of processing online payments (QRIS, VA, E-Wallet) within a Local Area Network (LAN) environment by implementing smart polling and manual synchronization mechanisms.

---

## ✨ Features

### 🔐 **Payment Processing**
- **Midtrans Snap Integration:** Generates secure payment tokens for QRIS, Virtual Accounts, and E-Wallets.
- **Smart Synchronization:** **Force Sync** endpoint allows the Flutter client to trigger status updates manually, bypassing the need for public webhooks in a localhost environment.
- **Real-time Validation:** Validates transaction signatures to prevent fraud.

### 🗄️ **Data Integrity**
- **Audit Trail:** Logs every transaction attempt, Snap token, and payment method details.
- **Status Tracking:** Tracks the lifecycle of a payment from `pending` -> `settlement` -> `capture` or `expire`.

### ⚡ **Infrastructure**
- **DDEV Ready:** Pre-configured Docker environment for consistent development.
- **LAN Accessible:** Configured to serve requests across the local network (Bind `0.0.0.0`).

---

## 🛠️ Technical Stack

- **Framework:** Laravel 12 (PHP 8.2+)
- **Database:** MySQL / MariaDB (via DDEV or Native)
- **Gateway:** Midtrans Snap API
- **Containerization:** Docker (DDEV)

---

## 🚀 Installation & Setup

### **1. Clone & Install**
```bash
git clone https://github.com/Andndre/luminara_transaksi.git
cd luminara_transaksi
composer install
```

### **2. Environment Configuration**
Copy the example file and generate your application key:
```bash
cp .env.example .env
php artisan key:generate
```

Configure your database and Midtrans credentials in `.env`:
```env
# Database (DDEV Default)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306

# Midtrans Configuration
MIDTRANS_MERCHANT_ID=your_merchant_id
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_IS_PRODUCTION=false
```

### **3. Database Migration**
```bash
php artisan migrate
```

---

## 🖥️ Running the Server (LAN Access)

To allow the Flutter app (running on mobile devices) to access this API, the server must be accessible via your local IP address.

### **Option A: Native PHP Serve (Recommended for Testing)**
Run the server binding to all interfaces:
```bash
php artisan serve --host 0.0.0.0 --port 8000
```
> **Access:** `http://192.168.x.x:8000`

### **Option B: Using DDEV**
This project includes DDEV configuration to expose port 8000.
```bash
ddev start
ddev php artisan serve --host 0.0.0.0 --port 8000
```

*Note: Ensure your Firewall (e.g., UFW on Linux) allows incoming traffic on port 8000.*

---

## 🔌 API Endpoints

| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `POST` | `/api/transaction` | Create a new transaction. <br> **Body:** `{ "amount": 50000, "order_id": "optional-uuid" }` |
| `GET` | `/api/transaction/{orderId}` | Check local transaction status. <br> **Returns:** `status`, `payment_type` |
| `POST` | `/api/transaction/{orderId}/sync` | **Force Sync:** Pull latest status from Midtrans Cloud & update DB. |
| `POST` | `/api/midtrans-callback` | Webhook URL for Midtrans Notification (Public Server only). |

---

## 🤝 Contributing

We welcome contributions! Please follow standard Laravel coding standards.

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 👥 Team

- **Developer**: [Andndre](https://github.com/Andndre)
- **Project**: Luminara Photobooth
