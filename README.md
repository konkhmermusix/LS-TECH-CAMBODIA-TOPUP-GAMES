# LS TECH CAMBODIA — GAME TOP-UP WEB APPLICATION

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-v4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpinedotjs&logoColor=white)](https://alpinejs.dev)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![ABA KHQR](https://img.shields.io/badge/ABA-PayWay_KHQR-005C8A?style=for-the-badge)](https://www.ababank.com)

A production-ready, high-performance **Game Top-Up Web Application** built specifically for Cambodia. Customers can purchase diamonds for **Free Fire** and **Mobile Legends: Bang Bang** using official **ABA KHQR** payments with instant, automated top-up delivery.

## 🛠️ Technology Stack

| Layer | Technology |
| :--- | :--- |
| **Backend Framework** | Laravel 12.x (PHP 8.2+) |
| **Database** | MySQL 8.0+ |
| **Frontend Templates** | Laravel Blade Components |
| **Styling** | Tailwind CSS v4 (with `@tailwindcss/vite`) |
| **Interactivity** | Alpine.js v3 + Vanilla Fetch API |
| **Typography** | Google Sans + Kantumruy Pro (Google CDN) |
| **Payment Gateway** | ABA PayWay KHQR (HMAC SHA-512) |
| **QR Code Engine** | QRCode.js (Client-side fast rendering) |

## Getting Started

### Prerequisites
Make sure your development machine has:
* **PHP 8.2 or higher** with `pdo_mysql`, `mbstring`, `openssl`, `curl` extensions
* **Composer** (v2+)
* **Node.js** (v18+ or v20+) & **NPM**
* **MySQL** (v8.0+ or MariaDB 10.4+)

### Step-by-Step Installation

#### 1. Navigate to the Backend Directory
```bash
cd backend
```

#### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Frontend dependencies
npm install
```

#### 3. Environment Configuration
Copy `.env.example` to `.env`:
```bash
copy .env.example .env
```

Generate the application key:
```bash
php artisan key:generate
```

Configure your database connection in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ls_tech_topup_game
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

#### 4. Run Migrations & Seeders
Create the database in MySQL (`ls_tech_topup_game`), then run:
```bash
php artisan migrate:fresh --seed
```

This populates:
* **Admin User:** `admin@lstech.com` / `Admin@123456`
* **Games:** Free Fire & Mobile Legends with field validation schemas
* **Packages:** 16 diamond packages with KHR and USD pricing
* **Payment Methods:** ABA KHQR
* **Promotions & Settings:** Sample active vouchers and default system settings

#### 5. Build Frontend Assets
```bash
# For development with Hot-Module-Replacement
npm run dev

# Or compile for production
npm run build
```

#### 6. Start the Local Server
```bash
php artisan serve
```

The web application is now live at **`http://127.0.0.1:8000`**!


## Default Admin Credentials

| Role | Email | Password | Access URL |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `admin@lstech.com` | `Admin@123456` | `http://127.0.0.1:8000/admin/login` |


## Testing Credentials & Scenarios

### Customer Top-Up Test Data
* **Free Fire:**
  * Player UID: `3245770826` (Valid)
  * Player UID: `9999999999` (Simulates Provider Failure)
* **Mobile Legends:**
  * User ID: `12345678`
  * Zone ID: `1234`
* **Promo Code:**
  * Code: `LSTECH5` (5% discount voucher)

### Sandbox Payment Simulation
1. Pick a game and diamond package on the homepage.
2. Enter your test Player ID and click **Checkout**.
3. On the payment page, click the **"Simulate Sandbox Payment (Success)"** button to immediately simulate bank payment confirmation and top-up execution without scanning with real money.


## Payment & Top-Up Configuration

In `.env`:

```env
# --------------------------------------------------------------------------
# ABA PayWay KHQR Settings
# --------------------------------------------------------------------------
ABA_PAYWAY_API_URL=https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/purchase
ABA_PAYWAY_MERCHANT_ID=ec439126
ABA_PAYWAY_API_KEY=your_api_key_here
ABA_PAYWAY_PUBLIC_KEY=your_public_key_here

# --------------------------------------------------------------------------
# Authorized Top-Up Provider Settings
# --------------------------------------------------------------------------
# Options: 'mock_provider' (default testing) or 'authorized_api' (production B2B)
TOPUP_ACTIVE_PROVIDER=mock_provider
TOPUP_MOCK_AUTO_SUCCESS=true

# When switching to 'authorized_api', fill credentials from your B2B aggregator:
TOPUP_PROVIDER_API_URL=https://api.partner-topup.com/v1
TOPUP_PROVIDER_MERCHANT_ID=your_merchant_id
TOPUP_PROVIDER_API_KEY=your_api_key
TOPUP_PROVIDER_SECRET=your_api_secret
```

## Running Automated Tests

Run the full suite of automated feature and unit tests:

```bash
php artisan test
```

### Test Coverage Highlights:
* `GuestCheckoutTest`:
  * Customer can initiate guest checkout without an account
  * Server strictly calculates pricing from DB (ignores tampered client amounts)
  * Promo discount vouchers validate and apply properly
  * Payment verification triggers automatic top-up delivery
* `AdminAuthTest`:
  * Admin login screen rendering
  * Authentication & dashboard access
  * Unauthenticated guest redirection
  * Inactive user login blocking

## Project Directory Structure

```text
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # 15 Admin Back-Office Controllers
│   │   │   └── Website/        # Customer & Guest Checkout Controllers
│   │   └── Middleware/         # AdminMiddleware with Rate Limiting
│   ├── Jobs/                   # ProcessTopUpJob (Queue Worker)
│   ├── Models/                 # 11 Eloquent Models
│   ├── Services/
│   │   ├── Payment/            # ABA PayWay KHQR Implementation
│   │   └── TopUp/              # TopUp Provider Interface & Implementations
│   └── View/Components/        # Class-based Layout Components
├── config/
│   ├── payment.php             # ABA PayWay Config
│   └── topup.php               # Top-Up Engine Config
├── database/
│   ├── migrations/             # 11 Database Migrations
│   └── seeders/                # Database Seeders
├── resources/
│   ├── css/app.css             # Tailwind v4 Tokens, Google Sans, Liquid Glass
│   ├── js/app.js               # Alpine.js & Theme Controller
│   └── views/
│       ├── admin/              # Admin Blade Views
│       ├── components/         # 18 Reusable UI Blade Components
│       ├── layouts/            # Master App & Admin Layouts
│       └── website/            # Customer Facing Store Views
├── routes/
│   └── web.php                 # 62 Registered Routes
└── tests/
    └── Feature/                # Automated Feature Tests
```

## Security Best Practices Implemented

* **Strict Price Integrity:** Pricing is resolved strictly from `game_packages` in the database; user-supplied price parameters in HTTP requests are rejected.
* **Signed Webhooks:** ABA payment webhooks verify HMAC SHA-512 signatures before updating payment state.
* **Audit Logging:** Every administrative mutation (game edits, package updates, order cancellations, setting modifications) is logged to `audit_logs` with admin ID, action, IP, and payload diffs.
* **Brute-Force Protection:** Rate limiting applied to admin authentication attempts (5 per minute per IP).


## License

This software is developed for **LS TECH CAMBODIA**. All rights reserved.
